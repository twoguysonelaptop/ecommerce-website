"""
Step 3: Pipeline Runner — Chain Mesh Generation + Blender Processing
=====================================================================
Usage:
  python run_pipeline.py [--backend tripo|csm] [--image path/to/mascot.jpeg] [--skip-generate]

Requires:
  - TRIPO_API_KEY or CSM_API_KEY environment variable set
  - Blender 5.0 installed at the default path
  - Python 'requests' package: pip install requests
"""

import os
import sys
import subprocess
import argparse

SCRIPT_DIR = os.path.dirname(os.path.abspath(__file__))
GENERATE_SCRIPT = os.path.join(SCRIPT_DIR, "generate_mesh.py")
PROCESS_SCRIPT = os.path.join(SCRIPT_DIR, "process_mascot.py")
RAW_GLB = os.path.join(SCRIPT_DIR, "mascot_raw.glb")
FINAL_GLB = os.path.join(SCRIPT_DIR, "mascot_waving.glb")

# Common Blender install paths on Windows
BLENDER_PATHS = [
    r"C:\Program Files\Blender Foundation\Blender 5.0\blender.exe",
    r"C:\Program Files\Blender Foundation\Blender 4.2\blender.exe",
    r"C:\Program Files\Blender Foundation\Blender 4.1\blender.exe",
    r"C:\Program Files\Blender Foundation\Blender 4.0\blender.exe",
]


def find_blender():
    """Locate Blender executable."""
    for path in BLENDER_PATHS:
        if os.path.exists(path):
            return path
    # Try PATH
    try:
        result = subprocess.run(["where", "blender"], capture_output=True, text=True)
        if result.returncode == 0:
            return result.stdout.strip().split("\n")[0]
    except FileNotFoundError:
        pass
    return None


def run_step1(backend, image_path):
    """Run generate_mesh.py to create mascot_raw.glb."""
    print("=" * 50)
    print("  STEP 1: Generate 3D Mesh from Image")
    print("=" * 50)
    print()

    cmd = [sys.executable, GENERATE_SCRIPT, "--backend", backend, "--image", image_path]
    result = subprocess.run(cmd)
    if result.returncode != 0:
        print(f"\nERROR: Step 1 failed (exit code {result.returncode})")
        sys.exit(1)

    if not os.path.exists(RAW_GLB):
        print(f"\nERROR: Expected output not found: {RAW_GLB}")
        sys.exit(1)

    size_mb = os.path.getsize(RAW_GLB) / (1024 * 1024)
    print(f"\n[OK] Step 1 complete: mascot_raw.glb ({size_mb:.1f} MB)")


def run_step2(blender_path):
    """Run process_mascot.py in Blender headless mode."""
    print()
    print("=" * 50)
    print("  STEP 2: Rig & Animate in Blender")
    print("=" * 50)
    print()

    cmd = [blender_path, "-b", "-P", PROCESS_SCRIPT]
    result = subprocess.run(cmd)
    if result.returncode != 0:
        print(f"\nERROR: Step 2 failed (exit code {result.returncode})")
        sys.exit(1)

    if not os.path.exists(FINAL_GLB):
        print(f"\nERROR: Expected output not found: {FINAL_GLB}")
        sys.exit(1)

    size_mb = os.path.getsize(FINAL_GLB) / (1024 * 1024)
    print(f"\n[OK] Step 2 complete: mascot_waving.glb ({size_mb:.1f} MB)")


def main():
    parser = argparse.ArgumentParser(description="Run full 3D mascot pipeline")
    parser.add_argument("--backend", choices=["tripo", "csm"], default="tripo",
                        help="3D generation API (default: tripo)")
    parser.add_argument("--image", default=os.path.join(os.path.expanduser("~"), "Downloads", "mascot.jpeg"),
                        help="Path to mascot reference image")
    parser.add_argument("--skip-generate", action="store_true",
                        help="Skip Step 1 (use existing mascot_raw.glb)")
    args = parser.parse_args()

    # Pre-flight checks
    blender = find_blender()
    if not blender:
        print("ERROR: Blender not found. Install Blender 5.0 or add it to PATH.")
        sys.exit(1)
    print(f"Blender: {blender}")

    if not args.skip_generate:
        if not os.path.exists(args.image):
            print(f"ERROR: Image not found: {args.image}")
            sys.exit(1)

        # Check API key
        if args.backend == "tripo" and not os.environ.get("TRIPO_API_KEY"):
            print("ERROR: TRIPO_API_KEY not set.")
            print("  Set it with:  set TRIPO_API_KEY=your_key_here")
            sys.exit(1)
        elif args.backend == "csm" and not os.environ.get("CSM_API_KEY"):
            print("ERROR: CSM_API_KEY not set.")
            print("  Set it with:  set CSM_API_KEY=your_key_here")
            sys.exit(1)

    print()
    print("=" * 48)
    print("   ODD Care Co. -- 3D Mascot Pipeline")
    print("=" * 48)
    print()

    # Step 1
    if args.skip_generate:
        if not os.path.exists(RAW_GLB):
            print(f"ERROR: --skip-generate used but {RAW_GLB} not found")
            sys.exit(1)
        print("Skipping Step 1 (using existing mascot_raw.glb)")
    else:
        run_step1(args.backend, args.image)

    # Step 2
    run_step2(blender)

    # Done
    print()
    print("=" * 48)
    print("   Pipeline Complete!")
    print("=" * 48)
    print()
    print(f"  Output: {FINAL_GLB}")
    print()
    print("  Load in Three.js:")
    print("    import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';")
    print("    const loader = new GLTFLoader();")
    print("    loader.load('mascot_waving.glb', (gltf) => {")
    print("      scene.add(gltf.scene);")
    print("      const mixer = new THREE.AnimationMixer(gltf.scene);")
    print("      gltf.animations.forEach(clip => mixer.clipAction(clip).play());")
    print("    });")


if __name__ == "__main__":
    main()
