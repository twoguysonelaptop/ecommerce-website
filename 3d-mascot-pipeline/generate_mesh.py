"""
Step 1: 3D Mesh Generation from Reference Image
=================================================
Sends mascot.jpeg to a 3D generation API and downloads the result as mascot_raw.glb.

Supported backends:
  - Tripo3D (default, set TRIPO_API_KEY)
  - CSM / Masterpiece Studio (set CSM_API_KEY)

Usage:
  python generate_mesh.py [--backend tripo|csm] [--image path/to/image.jpeg]
"""

import os
import sys
import time
import json
import argparse
import requests

SCRIPT_DIR = os.path.dirname(os.path.abspath(__file__))
DEFAULT_IMAGE = os.path.join(os.path.expanduser("~"), "Downloads", "mascot.jpeg")
OUTPUT_FILE = os.path.join(SCRIPT_DIR, "mascot_raw.glb")

# ─── Tripo3D Backend ──────────────────────────────────────────────

TRIPO_BASE = "https://api.tripo3d.ai/v2/openapi"

def tripo_generate(image_path: str, api_key: str) -> str:
    """Upload image to Tripo and return the task ID."""
    print(f"[Tripo] Uploading image: {image_path}")
    with open(image_path, "rb") as f:
        resp = requests.post(
            f"{TRIPO_BASE}/upload",
            headers={"Authorization": f"Bearer {api_key}"},
            files={"file": (os.path.basename(image_path), f, "image/jpeg")},
        )
    resp.raise_for_status()
    file_token = resp.json()["data"]["image_token"]
    print(f"[Tripo] Image uploaded, token: {file_token[:20]}...")

    print("[Tripo] Starting 3D generation task...")
    resp = requests.post(
        f"{TRIPO_BASE}/task",
        headers={
            "Authorization": f"Bearer {api_key}",
            "Content-Type": "application/json",
        },
        json={
            "type": "image_to_model",
            "file": {"type": "image_token", "file_token": file_token},
            "model_version": "v2.0-20240919",
            "face_limit": 30000,        # web-friendly poly count
            "texture": True,
        },
    )
    if resp.status_code != 200:
        print(f"[Tripo] Task creation failed ({resp.status_code}): {resp.text}")
        resp.raise_for_status()
    task_id = resp.json()["data"]["task_id"]
    print(f"[Tripo] Task created: {task_id}")
    return task_id


def tripo_poll(task_id: str, api_key: str, timeout: int = 300) -> str:
    """Poll until the task finishes. Returns the GLB download URL."""
    start = time.time()
    while time.time() - start < timeout:
        resp = requests.get(
            f"{TRIPO_BASE}/task/{task_id}",
            headers={"Authorization": f"Bearer {api_key}"},
        )
        resp.raise_for_status()
        data = resp.json()["data"]
        status = data["status"]
        progress = data.get("progress", 0)
        print(f"  status={status}  progress={progress}%", end="\r")

        if status == "success":
            print()
            model = data["output"]["model"]
            return model  # URL string
        elif status in ("failed", "cancelled", "unknown"):
            raise RuntimeError(f"Task {task_id} ended with status: {status}")

        time.sleep(5)
    raise TimeoutError(f"Task {task_id} timed out after {timeout}s")


def tripo_download(url: str, out_path: str):
    """Download the GLB file from Tripo."""
    print(f"[Tripo] Downloading GLB from: {url[:60]}...")
    resp = requests.get(url, stream=True)
    resp.raise_for_status()
    with open(out_path, "wb") as f:
        for chunk in resp.iter_content(chunk_size=8192):
            f.write(chunk)
    size_mb = os.path.getsize(out_path) / (1024 * 1024)
    print(f"[Tripo] Saved to {out_path} ({size_mb:.1f} MB)")


def run_tripo(image_path: str):
    api_key = os.environ.get("TRIPO_API_KEY")
    if not api_key:
        print("ERROR: TRIPO_API_KEY environment variable is not set.")
        print("  Set it with:  export TRIPO_API_KEY='your_key_here'")
        sys.exit(1)

    task_id = tripo_generate(image_path, api_key)
    glb_url = tripo_poll(task_id, api_key)
    tripo_download(glb_url, OUTPUT_FILE)


# ─── CSM / Masterpiece Studio Backend ─────────────────────────────

CSM_BASE = "https://api.csm.ai/v1"

def run_csm(image_path: str):
    api_key = os.environ.get("CSM_API_KEY")
    if not api_key:
        print("ERROR: CSM_API_KEY environment variable is not set.")
        print("  Set it with:  export CSM_API_KEY='your_key_here'")
        sys.exit(1)

    print(f"[CSM] Uploading image: {image_path}")
    with open(image_path, "rb") as f:
        resp = requests.post(
            f"{CSM_BASE}/image-to-3d",
            headers={"Authorization": f"Bearer {api_key}"},
            files={"image": (os.path.basename(image_path), f, "image/jpeg")},
            data={"format": "glb", "quality": "medium"},
        )
    resp.raise_for_status()
    session_id = resp.json().get("session_id") or resp.json().get("id")
    print(f"[CSM] Session: {session_id}")

    # Poll for completion
    start = time.time()
    while time.time() - start < 300:
        resp = requests.get(
            f"{CSM_BASE}/sessions/{session_id}",
            headers={"Authorization": f"Bearer {api_key}"},
        )
        resp.raise_for_status()
        data = resp.json()
        status = data.get("status", "unknown")
        print(f"  status={status}", end="\r")

        if status in ("completed", "done"):
            print()
            glb_url = data.get("output", {}).get("glb_url") or data.get("glb_url")
            if glb_url:
                r = requests.get(glb_url, stream=True)
                r.raise_for_status()
                with open(OUTPUT_FILE, "wb") as f:
                    for chunk in r.iter_content(8192):
                        f.write(chunk)
                size_mb = os.path.getsize(OUTPUT_FILE) / (1024 * 1024)
                print(f"[CSM] Saved to {OUTPUT_FILE} ({size_mb:.1f} MB)")
                return
        elif status in ("failed", "error"):
            raise RuntimeError(f"CSM session {session_id} failed: {data}")

        time.sleep(5)
    raise TimeoutError("CSM session timed out")


# ─── Main ─────────────────────────────────────────────────────────

def main():
    parser = argparse.ArgumentParser(description="Generate 3D mesh from image")
    parser.add_argument("--backend", choices=["tripo", "csm"], default="tripo",
                        help="Which 3D generation API to use (default: tripo)")
    parser.add_argument("--image", default=DEFAULT_IMAGE,
                        help=f"Path to input image (default: {DEFAULT_IMAGE})")
    args = parser.parse_args()

    if not os.path.exists(args.image):
        print(f"ERROR: Image not found: {args.image}")
        sys.exit(1)

    print(f"=== 3D Mesh Generation ===")
    print(f"  Backend: {args.backend}")
    print(f"  Image:   {args.image}")
    print(f"  Output:  {OUTPUT_FILE}")
    print()

    if args.backend == "tripo":
        run_tripo(args.image)
    else:
        run_csm(args.image)

    print("\n[OK] Step 1 complete: mascot_raw.glb generated")


if __name__ == "__main__":
    main()
