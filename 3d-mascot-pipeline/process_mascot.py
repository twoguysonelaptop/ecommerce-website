"""
Step 2: Blender Python Script — Rig, Animate, and Export Mascot
================================================================
Run headlessly:  blender -b -P process_mascot.py

This script:
  1. Clears the default scene
  2. Imports mascot_raw.glb
  3. Creates a humanoid armature (head, spine, arms)
  4. Parents the mesh to the armature with automatic weights
  5. Keyframes a 60-frame "waving" loop on the right arm
  6. Exports mascot_waving.glb (Three.js-compatible)
"""

import bpy
import os
import math
import mathutils

SCRIPT_DIR = os.path.dirname(os.path.abspath(__file__))
INPUT_FILE = os.path.join(SCRIPT_DIR, "mascot_raw.glb")
OUTPUT_FILE = os.path.join(SCRIPT_DIR, "mascot_waving.glb")


# ─── 1. Clear Default Scene ──────────────────────────────────────

def clear_scene():
    """Remove everything from the default scene."""
    bpy.ops.object.select_all(action='SELECT')
    bpy.ops.object.delete()

    # Remove orphan data
    for block in bpy.data.meshes:
        if block.users == 0:
            bpy.data.meshes.remove(block)
    for block in bpy.data.materials:
        if block.users == 0:
            bpy.data.materials.remove(block)
    for block in bpy.data.textures:
        if block.users == 0:
            bpy.data.textures.remove(block)
    for block in bpy.data.images:
        if block.users == 0:
            bpy.data.images.remove(block)

    print("[Blender] Scene cleared")


# ─── 2. Import GLB ───────────────────────────────────────────────

def import_glb(filepath):
    """Import the raw GLB mesh."""
    if not os.path.exists(filepath):
        raise FileNotFoundError(f"Input file not found: {filepath}")

    bpy.ops.import_scene.gltf(filepath=filepath)
    print(f"[Blender] Imported: {filepath}")

    # Gather all mesh objects that were imported
    meshes = [obj for obj in bpy.context.scene.objects if obj.type == 'MESH']
    if not meshes:
        raise RuntimeError("No mesh objects found in the imported file")

    print(f"[Blender] Found {len(meshes)} mesh object(s)")
    return meshes


# ─── 3. Analyze Mesh & Create Armature ────────────────────────────

def get_mesh_bounds(meshes):
    """Calculate the combined bounding box of all mesh objects."""
    min_co = mathutils.Vector((float('inf'), float('inf'), float('inf')))
    max_co = mathutils.Vector((float('-inf'), float('-inf'), float('-inf')))

    for obj in meshes:
        for corner in obj.bound_box:
            world_co = obj.matrix_world @ mathutils.Vector(corner)
            min_co.x = min(min_co.x, world_co.x)
            min_co.y = min(min_co.y, world_co.y)
            min_co.z = min(min_co.z, world_co.z)
            max_co.x = max(max_co.x, world_co.x)
            max_co.y = max(max_co.y, world_co.y)
            max_co.z = max(max_co.z, world_co.z)

    return min_co, max_co


def create_armature(meshes):
    """Create a simple humanoid armature sized to the mascot mesh."""
    min_co, max_co = get_mesh_bounds(meshes)
    height = max_co.z - min_co.z
    width = max_co.x - min_co.x
    center_x = (min_co.x + max_co.x) / 2
    center_y = (min_co.y + max_co.y) / 2

    print(f"[Blender] Mesh bounds: height={height:.3f}, width={width:.3f}")

    # Proportions relative to the mascot's squat body shape
    # The ODD mascot has a large head, short body, stubby arms
    hip_z = min_co.z + height * 0.05       # just above the feet
    spine_z = min_co.z + height * 0.35     # mid-torso
    chest_z = min_co.z + height * 0.50     # upper torso
    neck_z = min_co.z + height * 0.60      # where neck starts
    head_z = max_co.z                       # top of head

    shoulder_offset = width * 0.30          # how far shoulders are from center
    arm_length = height * 0.20             # stubby arm length
    forearm_drop = height * 0.15

    # Create armature
    bpy.ops.object.armature_add(enter_editmode=True, location=(center_x, center_y, 0))
    armature_obj = bpy.context.active_object
    armature_obj.name = "MascotArmature"
    armature = armature_obj.data
    armature.name = "MascotRig"

    # Remove default bone
    for bone in armature.edit_bones:
        armature.edit_bones.remove(bone)

    # ── Spine chain ──
    hip = armature.edit_bones.new("Hip")
    hip.head = (center_x, center_y, hip_z)
    hip.tail = (center_x, center_y, spine_z)

    spine = armature.edit_bones.new("Spine")
    spine.head = (center_x, center_y, spine_z)
    spine.tail = (center_x, center_y, chest_z)
    spine.parent = hip

    chest = armature.edit_bones.new("Chest")
    chest.head = (center_x, center_y, chest_z)
    chest.tail = (center_x, center_y, neck_z)
    chest.parent = spine

    neck = armature.edit_bones.new("Neck")
    neck.head = (center_x, center_y, neck_z)
    neck.tail = (center_x, center_y, neck_z + height * 0.05)
    neck.parent = chest

    head = armature.edit_bones.new("Head")
    head.head = (center_x, center_y, neck_z + height * 0.05)
    head.tail = (center_x, center_y, head_z)
    head.parent = neck

    # ── Left arm ──
    l_shoulder = armature.edit_bones.new("Shoulder.L")
    l_shoulder.head = (center_x, center_y, chest_z)
    l_shoulder.tail = (center_x - shoulder_offset, center_y, chest_z)
    l_shoulder.parent = chest

    l_arm = armature.edit_bones.new("Arm.L")
    l_arm.head = (center_x - shoulder_offset, center_y, chest_z)
    l_arm.tail = (center_x - shoulder_offset - arm_length, center_y, chest_z - forearm_drop)
    l_arm.parent = l_shoulder

    # ── Right arm (this one will wave) ──
    r_shoulder = armature.edit_bones.new("Shoulder.R")
    r_shoulder.head = (center_x, center_y, chest_z)
    r_shoulder.tail = (center_x + shoulder_offset, center_y, chest_z)
    r_shoulder.parent = chest

    r_arm = armature.edit_bones.new("Arm.R")
    r_arm.head = (center_x + shoulder_offset, center_y, chest_z)
    r_arm.tail = (center_x + shoulder_offset + arm_length, center_y, chest_z - forearm_drop)
    r_arm.parent = r_shoulder

    # Exit edit mode
    bpy.ops.object.mode_set(mode='OBJECT')

    print(f"[Blender] Armature created with {len(armature.bones)} bones")
    return armature_obj


# ─── 4. Parent Mesh to Armature ───────────────────────────────────

def parent_mesh_to_armature(meshes, armature_obj):
    """Parent all mesh objects to the armature with automatic weights."""
    bpy.ops.object.select_all(action='DESELECT')

    for mesh_obj in meshes:
        mesh_obj.select_set(True)

    armature_obj.select_set(True)
    bpy.context.view_layer.objects.active = armature_obj

    # Parent with automatic weights
    try:
        bpy.ops.object.parent_set(type='ARMATURE_AUTO')
        print("[Blender] Parented mesh to armature with automatic weights")
    except RuntimeError as e:
        # Fallback: parent with empty groups if auto-weights fail
        # (can happen with non-manifold meshes from AI generation)
        print(f"[Blender] Auto-weights failed ({e}), using empty groups...")
        bpy.ops.object.parent_set(type='ARMATURE_NAME')
        print("[Blender] Parented mesh to armature with empty vertex groups")


# ─── 5. Create Waving Animation ──────────────────────────────────

def create_wave_animation(armature_obj, total_frames=60):
    """Create a looping wave animation on the right arm."""
    scene = bpy.context.scene
    scene.frame_start = 1
    scene.frame_end = total_frames
    scene.render.fps = 30

    # Switch to pose mode
    bpy.context.view_layer.objects.active = armature_obj
    bpy.ops.object.mode_set(mode='POSE')

    # Get the right arm bone
    r_arm = armature_obj.pose.bones.get("Arm.R")
    r_shoulder = armature_obj.pose.bones.get("Shoulder.R")

    if not r_arm:
        print("[Blender] WARNING: Arm.R bone not found, skipping animation")
        bpy.ops.object.mode_set(mode='OBJECT')
        return

    # Set rotation mode to Euler for easier keyframing
    r_arm.rotation_mode = 'XYZ'
    if r_shoulder:
        r_shoulder.rotation_mode = 'XYZ'

    # ── Raise the right arm up first (shoulder) ──
    if r_shoulder:
        # Rotate shoulder to raise arm outward and up
        r_shoulder.rotation_euler = (0, math.radians(-60), 0)
        r_shoulder.keyframe_insert(data_path="rotation_euler", frame=1)
        r_shoulder.keyframe_insert(data_path="rotation_euler", frame=total_frames)

    # ── Wave animation on the arm bone ──
    # Keyframe pattern: rest → wave forward → wave back → rest (loop)
    wave_angles = [
        (1,  0),          # Start: neutral
        (8,  25),         # Wave forward
        (22, -25),        # Wave back
        (36,  25),        # Wave forward
        (50, -25),        # Wave back
        (57,  0),         # Return to neutral
        (60,  0),         # Hold for clean loop
    ]

    for frame, angle_deg in wave_angles:
        r_arm.rotation_euler = (0, math.radians(angle_deg), 0)
        r_arm.keyframe_insert(data_path="rotation_euler", frame=frame)

    # Make the animation loop smoothly using cyclic F-curve modifier
    if armature_obj.animation_data and armature_obj.animation_data.action:
        for fcurve in armature_obj.animation_data.action.fcurves:
            # Add cyclic modifier for looping
            mod = fcurve.modifiers.new(type='CYCLES')
            mod.mode_before = 'REPEAT'
            mod.mode_after = 'REPEAT'

            # Set keyframe interpolation to smooth
            for kf in fcurve.keyframe_points:
                kf.interpolation = 'BEZIER'
                kf.handle_left_type = 'AUTO_CLAMPED'
                kf.handle_right_type = 'AUTO_CLAMPED'

    bpy.ops.object.mode_set(mode='OBJECT')
    print(f"[Blender] Wave animation created: {total_frames} frames at {scene.render.fps} fps")


# ─── 6. Export GLB ────────────────────────────────────────────────

def export_glb(filepath):
    """Export the scene as GLB with animations baked for Three.js."""
    # Select all objects for export
    bpy.ops.object.select_all(action='SELECT')

    export_settings = {
        'filepath': filepath,
        'check_existing': False,
        'export_format': 'GLB',
        'use_selection': False,         # Export everything
        'export_animations': True,
        'export_frame_range': True,
        'export_anim_slide_to_zero': True,
        'export_apply': True,           # Apply modifiers
        'export_texcoords': True,
        'export_normals': True,
        'export_colors': True,
        'export_cameras': False,
        'export_lights': False,
        'export_image_format': 'AUTO',
    }

    # Blender 4+ / 5+ changed some parameter names
    try:
        bpy.ops.export_scene.gltf(**export_settings)
    except TypeError:
        # Fallback for different Blender versions
        fallback = {
            'filepath': filepath,
            'check_existing': False,
            'export_format': 'GLB',
            'export_animations': True,
            'export_apply': True,
        }
        bpy.ops.export_scene.gltf(**fallback)

    size_mb = os.path.getsize(filepath) / (1024 * 1024)
    print(f"[Blender] Exported: {filepath} ({size_mb:.1f} MB)")


# ─── Main Pipeline ───────────────────────────────────────────────

def main():
    print("=" * 50)
    print("  ODD Care Co. Mascot -- Rig & Animate Pipeline")
    print("=" * 50)

    # 1. Clear scene
    clear_scene()

    # 2. Import mesh
    meshes = import_glb(INPUT_FILE)

    # 3. Create armature
    armature = create_armature(meshes)

    # 4. Parent mesh to armature
    parent_mesh_to_armature(meshes, armature)

    # 5. Animate
    create_wave_animation(armature, total_frames=60)

    # 6. Export
    export_glb(OUTPUT_FILE)

    print()
    print("[OK] Pipeline complete!")
    print(f"  Output: {OUTPUT_FILE}")
    print(f"  Load in Three.js with: new GLTFLoader().load('mascot_waving.glb')")


if __name__ == "__main__":
    main()
