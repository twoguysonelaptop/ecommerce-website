/* ============================================================
   ODD Care Co — 3D Mascot Module (Cel-Shaded Edition)
   Requires Three.js (loaded via CDN before this script)
   Usage:
     var mascot = OddMascot.create({
       container: '.hero-visual',
       size: 150,
       position: { x: 0, y: 0 },
       animation: 'pop-up',   // 'idle' | 'pop-up' | 'hidden'
       popUpDelay: 2000,
       waveSpeed: 8,           // sinusoidal wave oscillation speed
       waveAmplitude: 0.4      // wave oscillation range (radians)
     });
     mascot.wave();
     mascot.point();
     mascot.setSize(100);
     mascot.destroy();
   ============================================================ */

(function () {
  'use strict';

  if (typeof THREE === 'undefined') {
    console.warn(
      'OddMascot: Three.js is required. Add this before odd-mascot.js:\n' +
      '<script src="https://cdn.jsdelivr.net/npm/three@0.160.0/build/three.min.js"><\/script>'
    );
    return;
  }

  /* ───── constants ───── */
  var BEIGE  = 0xF5F0EB;
  var DARK   = 0x0d0d0d;
  var SEAM_C = 0xddd8d1;
  var OUTLINE_THICKNESS = 0.045;
  var isMobile = window.innerWidth < 768;
  var SEG = isMobile ? 16 : 32;
  var ARM_REST_R = -0.12;
  var ARM_REST_L =  0.12;

  /* ───── easing helpers ───── */
  function easeOutCubic(t) { return 1 - Math.pow(1 - t, 3); }
  function easeOutBack(t) {
    var c1 = 1.70158, c3 = c1 + 1;
    return 1 + c3 * Math.pow(t - 1, 3) + c1 * Math.pow(t - 1, 2);
  }
  function lerp(a, b, t) { return a + (b - a) * t; }

  /* ───── 3-step toon gradient map ───── */
  function createToonGradient() {
    var c = document.createElement('canvas');
    c.width = 3;
    c.height = 1;
    var ctx = c.getContext('2d');
    ctx.fillStyle = '#555';   // shadow
    ctx.fillRect(0, 0, 1, 1);
    ctx.fillStyle = '#bbb';   // mid-tone
    ctx.fillRect(1, 0, 1, 1);
    ctx.fillStyle = '#fff';   // highlight
    ctx.fillRect(2, 0, 1, 1);
    var tex = new THREE.CanvasTexture(c);
    tex.minFilter = THREE.NearestFilter;
    tex.magFilter = THREE.NearestFilter;
    return tex;
  }

  /* ───── inverted-hull outline ───── */
  var _outlineMat = null;
  function getOutlineMaterial() {
    if (!_outlineMat) {
      _outlineMat = new THREE.MeshBasicMaterial({
        color: 0x000000,
        side: THREE.BackSide
      });
    }
    return _outlineMat;
  }

  function addOutline(mesh, thickness) {
    var outline = new THREE.Mesh(mesh.geometry, getOutlineMaterial());
    outline.position.copy(mesh.position);
    outline.rotation.copy(mesh.rotation);
    outline.scale.copy(mesh.scale).multiplyScalar(1 + (thickness || OUTLINE_THICKNESS));
    outline.name = (mesh.name || '') + '_outline';
    outline.userData.isOutline = true;
    mesh.parent.add(outline);
    return outline;
  }

  /* ───── "ODD" chest texture ───── */
  function createOddTexture() {
    var c = document.createElement('canvas');
    c.width = 256; c.height = 128;
    var ctx = c.getContext('2d');
    ctx.clearRect(0, 0, 256, 128);
    ctx.fillStyle = '#6b665f';
    ctx.font = 'bold 56px Arial, Helvetica, sans-serif';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    ctx.fillText('ODD', 128, 64);
    var tex = new THREE.CanvasTexture(c);
    tex.needsUpdate = true;
    return tex;
  }

  /* ═══════════════════════════════════════════════
     BUILD MASCOT — procedural cel-shaded geometry
     ═══════════════════════════════════════════════ */
  function buildMascot() {
    var root = new THREE.Group();
    root.name = 'mascotRoot';

    var gradientMap = createToonGradient();

    /* — toon materials — */
    var bodyMat = new THREE.MeshToonMaterial({
      color: BEIGE, gradientMap: gradientMap
    });
    var visorMat = new THREE.MeshToonMaterial({
      color: DARK, gradientMap: gradientMap
    });
    var eyeMat = new THREE.MeshBasicMaterial({ color: 0xffffff });
    var seamMat = new THREE.MeshToonMaterial({
      color: SEAM_C, gradientMap: gradientMap
    });

    var outlineable = [];

    /* ── HEAD GROUP (pivot for bobbing) ── */
    var headGroup = new THREE.Group();
    headGroup.name = 'headGroup';
    headGroup.position.y = 1.72;

    var head = new THREE.Mesh(
      new THREE.SphereGeometry(0.88, SEG, SEG), bodyMat
    );
    head.name = 'head';
    head.position.y = 0.73;
    head.scale.y = 0.93;
    headGroup.add(head);
    outlineable.push(head);

    // visor — extruded semi-ellipse
    var vs = new THREE.Shape();
    vs.absellipse(0, 0, 0.62, 0.2, 0, Math.PI * 2, false, 0);
    var visorGeo = new THREE.ExtrudeGeometry(vs, {
      depth: 0.06, bevelEnabled: true,
      bevelThickness: 0.01, bevelSize: 0.02, bevelSegments: 3
    });
    var visor = new THREE.Mesh(visorGeo, visorMat);
    visor.name = 'visor';
    visor.position.set(0, 0.68, 0.78);
    headGroup.add(visor);
    outlineable.push(visor);

    // eyes
    var eyeGeo = new THREE.BoxGeometry(0.065, 0.15, 0.02);
    var leftEye = new THREE.Mesh(eyeGeo, eyeMat);
    leftEye.name = 'leftEye';
    leftEye.position.set(-0.17, 0.68, 0.9);
    headGroup.add(leftEye);

    var rightEye = new THREE.Mesh(eyeGeo, eyeMat);
    rightEye.name = 'rightEye';
    rightEye.position.set(0.17, 0.68, 0.9);
    headGroup.add(rightEye);

    root.add(headGroup);

    /* ── NECK ── */
    var neck = new THREE.Mesh(
      new THREE.CylinderGeometry(0.3, 0.42, 0.12, SEG), bodyMat
    );
    neck.name = 'neck';
    neck.position.y = 1.66;
    root.add(neck);
    outlineable.push(neck);

    /* ── BODY ── */
    var bodyGroup = new THREE.Group();
    bodyGroup.name = 'bodyGroup';

    var bodyCyl = new THREE.Mesh(
      new THREE.CylinderGeometry(0.6, 0.52, 1.0, SEG), bodyMat
    );
    bodyCyl.name = 'bodyMain';
    bodyCyl.position.y = 1.1;
    bodyGroup.add(bodyCyl);
    outlineable.push(bodyCyl);

    var botHemi = new THREE.Mesh(
      new THREE.SphereGeometry(
        0.52, SEG, Math.max(8, SEG / 2),
        0, Math.PI * 2, Math.PI / 2, Math.PI / 2
      ), bodyMat
    );
    botHemi.name = 'bodyBottom';
    botHemi.position.y = 0.6;
    bodyGroup.add(botHemi);

    var topHemi = new THREE.Mesh(
      new THREE.SphereGeometry(
        0.6, SEG, Math.max(8, SEG / 2),
        0, Math.PI * 2, 0, Math.PI / 2
      ), bodyMat
    );
    topHemi.name = 'bodyShoulder';
    topHemi.position.y = 1.6;
    bodyGroup.add(topHemi);

    // "ODD" text on chest
    var oddTex = createOddTexture();
    var oddMat = new THREE.MeshBasicMaterial({
      map: oddTex, transparent: true, depthWrite: false
    });
    var oddPlane = new THREE.Mesh(
      new THREE.PlaneGeometry(0.5, 0.22), oddMat
    );
    oddPlane.position.set(0, 1.22, 0.535);
    bodyGroup.add(oddPlane);

    // seam lines
    var seam1 = new THREE.Mesh(
      new THREE.TorusGeometry(0.58, 0.007, 4, SEG), seamMat
    );
    seam1.position.y = 1.35;
    seam1.rotation.x = Math.PI / 2;
    bodyGroup.add(seam1);

    var seam2 = new THREE.Mesh(
      new THREE.TorusGeometry(0.54, 0.007, 4, SEG), seamMat
    );
    seam2.position.y = 0.9;
    seam2.rotation.x = Math.PI / 2;
    bodyGroup.add(seam2);

    root.add(bodyGroup);

    /* ── ARMS (in pivot Groups for rotation) ── */
    var armGeo = new THREE.CapsuleGeometry(0.1, 0.28, 4, Math.max(8, SEG / 2));

    var rArmPivot = new THREE.Group();
    rArmPivot.name = 'rightArmPivot';
    rArmPivot.position.set(0.63, 1.4, 0);
    rArmPivot.rotation.z = ARM_REST_R;
    var rArm = new THREE.Mesh(armGeo, bodyMat);
    rArm.name = 'rightArm';
    rArm.position.y = -0.2;
    rArmPivot.add(rArm);
    outlineable.push(rArm);
    root.add(rArmPivot);

    var lArmPivot = new THREE.Group();
    lArmPivot.name = 'leftArmPivot';
    lArmPivot.position.set(-0.63, 1.4, 0);
    lArmPivot.rotation.z = ARM_REST_L;
    var lArm = new THREE.Mesh(armGeo, bodyMat);
    lArm.name = 'leftArm';
    lArm.position.y = -0.2;
    lArmPivot.add(lArm);
    outlineable.push(lArm);
    root.add(lArmPivot);

    /* ── LEGS ── */
    var legGeo = new THREE.CapsuleGeometry(0.13, 0.15, 4, Math.max(8, SEG / 2));

    var lLeg = new THREE.Mesh(legGeo, bodyMat);
    lLeg.name = 'leftLeg';
    lLeg.position.set(-0.2, 0.28, 0);
    root.add(lLeg);
    outlineable.push(lLeg);

    var rLeg = new THREE.Mesh(legGeo, bodyMat);
    rLeg.name = 'rightLeg';
    rLeg.position.set(0.2, 0.28, 0);
    root.add(rLeg);
    outlineable.push(rLeg);

    /* ── ADD OUTLINES to all marked meshes ── */
    for (var i = 0; i < outlineable.length; i++) {
      addOutline(outlineable[i], OUTLINE_THICKNESS);
    }

    return {
      root: root,
      headGroup: headGroup,
      leftEye: leftEye,
      rightEye: rightEye,
      bodyGroup: bodyGroup,
      rightArmPivot: rArmPivot,
      leftArmPivot: lArmPivot
    };
  }

  /* ═══════════════════════════════════════════════
     MASCOT INSTANCE
     ═══════════════════════════════════════════════ */
  function MascotInstance(opts) {
    var def = {
      container: document.body,
      size: 150,
      position: { x: 0, y: 0 },
      animation: 'idle',
      popUpDelay: 0,
      waveSpeed: 8,
      waveAmplitude: 0.4
    };
    this.opts = {};
    for (var k in def) this.opts[k] = opts[k] !== undefined ? opts[k] : def[k];
    if (typeof this.opts.container === 'string') {
      this.opts.container = document.querySelector(this.opts.container);
    }
    if (!this.opts.container) { console.error('OddMascot: container not found'); return; }

    this.waveSpeed = this.opts.waveSpeed;
    this.waveAmplitude = this.opts.waveAmplitude;

    this.state = 'hidden';
    this.time = 0;
    this.blinkTimer = 0;
    this.nextBlink = 3 + Math.random() * 3;
    this.isBlinking = false;
    this.blinkProgress = 0;
    this.animTime = 0;
    this.animDuration = 0;
    this.baseY = 0;
    this.popUpTarget = 0;
    this.destroyed = false;
    this.visible = true;
    this.lastFrame = 0;
    this._waveReturnStart = undefined;

    this._init();
  }

  MascotInstance.prototype._init = function () {
    var self = this;
    var size = this.opts.size;
    var cW = Math.round(size * 1.4);
    var cH = Math.round(size * 1.7);

    // renderer
    this.renderer = new THREE.WebGLRenderer({ alpha: true, antialias: !isMobile });
    this.renderer.setSize(cW, cH);
    this.renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    this.renderer.setClearColor(0x000000, 0);

    // canvas styling
    this.canvas = this.renderer.domElement;
    this.canvas.style.position = 'absolute';
    this.canvas.style.pointerEvents = 'none';
    this.canvas.style.zIndex = '0';
    this.canvas.style.right  = this.opts.position.x + 'px';
    this.canvas.style.bottom = this.opts.position.y + 'px';

    var cs = window.getComputedStyle(this.opts.container);
    if (cs.position === 'static') this.opts.container.style.position = 'relative';
    this.opts.container.appendChild(this.canvas);

    // scene
    this.scene = new THREE.Scene();

    // camera
    this.camera = new THREE.PerspectiveCamera(38, cW / cH, 0.1, 100);
    this.camera.position.set(0, 1.35, 5.5);
    this.camera.lookAt(0, 1.35, 0);

    // toon lighting — strong directional for crisp cel-shaded bands
    this.scene.add(new THREE.AmbientLight(0xffffff, 0.45));
    var dir = new THREE.DirectionalLight(0xffffff, 1.2);
    dir.position.set(2, 3, 4);
    this.scene.add(dir);

    // model
    this.model = buildMascot();
    this.scene.add(this.model.root);

    // initial state
    if (this.opts.animation === 'hidden' || this.opts.animation === 'pop-up') {
      this.model.root.position.y = -4;
      this.state = 'hidden';
    } else {
      this.state = 'idle';
    }

    // auto pop-up
    if (this.opts.animation === 'pop-up') {
      setTimeout(function () {
        if (!self.destroyed) self.popUp();
      }, this.opts.popUpDelay || 0);
    }

    // visibility observer
    this.observer = new IntersectionObserver(function (entries) {
      self.visible = entries[0].isIntersecting;
    }, { threshold: 0.1 });
    this.observer.observe(this.canvas);

    // click to wave
    this.canvas.style.pointerEvents = 'auto';
    this.canvas.addEventListener('click', function () {
      if (self.state === 'idle') self.wave();
    });

    // start loop
    this.lastFrame = performance.now();
    this._loop();
  };

  /* ── render loop (setTimeout for Chrome extension compatibility) ── */
  MascotInstance.prototype._loop = function () {
    if (this.destroyed) return;
    var self = this;
    var interval = isMobile ? 33 : 16;
    this._timerId = setTimeout(function () { self._loop(); }, interval);

    if (!this.visible) return;

    var now = performance.now();
    var elapsed = now - this.lastFrame;
    this.lastFrame = now;

    // delta time, capped at 50ms to prevent jumps
    var dt = Math.min(elapsed / 1000, 0.05);
    this.time += dt;
    this._update(dt);
    this.renderer.render(this.scene, this.camera);
  };

  /* ── animation update (frame-rate independent via dt) ── */
  MascotInstance.prototype._update = function (dt) {
    var m = this.model;

    /* — idle: always active when visible — */
    if (this.state !== 'hidden') {
      // gentle bob
      if (this.state !== 'popping-up' && this.state !== 'hiding') {
        m.root.position.y = this.baseY + Math.sin(this.time * 1.5) * 0.03;
      }
      // subtle head tilt
      m.headGroup.rotation.z = Math.sin(this.time * 1.2) * 0.025;

      // blink
      this.blinkTimer += dt;
      if (!this.isBlinking && this.blinkTimer >= this.nextBlink) {
        this.isBlinking = true;
        this.blinkProgress = 0;
      }
      if (this.isBlinking) {
        this.blinkProgress += dt * 8;
        if (this.blinkProgress < 1) {
          var s = this.blinkProgress < 0.5
            ? 1 - this.blinkProgress * 2
            : (this.blinkProgress - 0.5) * 2;
          m.leftEye.scale.y = Math.max(0.05, s);
          m.rightEye.scale.y = Math.max(0.05, s);
        } else {
          m.leftEye.scale.y = 1;
          m.rightEye.scale.y = 1;
          this.isBlinking = false;
          this.blinkTimer = 0;
          this.nextBlink = 3 + Math.random() * 3;
        }
      }
    }

    /* — pop-up (spring easing) — */
    if (this.state === 'popping-up') {
      this.animTime += dt;
      var t = Math.min(this.animTime / this.animDuration, 1);
      m.root.position.y = lerp(-4, this.popUpTarget, easeOutBack(t));
      if (t >= 1) {
        this.baseY = this.popUpTarget;
        this.state = 'idle';
        var self = this;
        setTimeout(function () {
          if (!self.destroyed && self.state === 'idle') self.wave();
        }, 300);
      }
    }

    /* — hide — */
    if (this.state === 'hiding') {
      this.animTime += dt;
      var t = Math.min(this.animTime / 0.5, 1);
      m.root.position.y = lerp(this.baseY, -4, easeOutCubic(t));
      if (t >= 1) this.state = 'hidden';
    }

    /* — wave (smooth sinusoidal via Math.sin) — */
    if (this.state === 'waving') {
      this.animTime += dt;
      var t = Math.min(this.animTime / this.animDuration, 1);
      var armZ;
      var WAVE_RAISED = -2.2;
      var raiseEnd = 0.15;
      var waveEnd  = 0.8;

      if (t < raiseEnd) {
        // smoothly raise arm to wave position
        armZ = lerp(ARM_REST_R, WAVE_RAISED, easeOutCubic(t / raiseEnd));
      } else if (t < waveEnd) {
        // smooth sinusoidal oscillation
        var waveT = this.animTime - (raiseEnd * this.animDuration);
        armZ = WAVE_RAISED + Math.sin(waveT * this.waveSpeed) * this.waveAmplitude;
      } else {
        // smoothly lower arm back to rest
        if (this._waveReturnStart === undefined) {
          var waveT = this.animTime - (raiseEnd * this.animDuration);
          this._waveReturnStart = WAVE_RAISED + Math.sin(waveT * this.waveSpeed) * this.waveAmplitude;
        }
        armZ = lerp(this._waveReturnStart, ARM_REST_R, easeOutCubic((t - waveEnd) / (1 - waveEnd)));
      }

      m.rightArmPivot.rotation.z = armZ;

      if (t >= 1) {
        m.rightArmPivot.rotation.z = ARM_REST_R;
        this._waveReturnStart = undefined;
        this.state = 'idle';
      }
    }

    /* — point — */
    if (this.state === 'pointing') {
      this.animTime += dt;
      var t = Math.min(this.animTime / this.animDuration, 1);
      var armZ, armX, headY;
      if (t < 0.3) {
        var pt = easeOutCubic(t / 0.3);
        armZ = lerp(ARM_REST_R, -1.5, pt);
        armX = lerp(0, -0.3, pt);
        headY = lerp(0, 0.25, pt);
      } else if (t < 0.7) {
        armZ = -1.5; armX = -0.3; headY = 0.25;
      } else {
        var pt = easeOutCubic((t - 0.7) / 0.3);
        armZ = lerp(-1.5, ARM_REST_R, pt);
        armX = lerp(-0.3, 0, pt);
        headY = lerp(0.25, 0, pt);
      }
      m.rightArmPivot.rotation.z = armZ;
      m.rightArmPivot.rotation.x = armX;
      m.headGroup.rotation.y = headY;
      if (t >= 1) {
        m.rightArmPivot.rotation.z = ARM_REST_R;
        m.rightArmPivot.rotation.x = 0;
        m.headGroup.rotation.y = 0;
        this.state = 'idle';
      }
    }
  };

  /* ── public instance methods ── */

  MascotInstance.prototype.wave = function () {
    if (this.state === 'hidden' || this.state === 'popping-up') return;
    this.state = 'waving';
    this.animTime = 0;
    this.animDuration = 2.5;
    this._waveReturnStart = undefined;
  };

  MascotInstance.prototype.point = function () {
    if (this.state === 'hidden' || this.state === 'popping-up') return;
    this.state = 'pointing';
    this.animTime = 0;
    this.animDuration = 2.0;
  };

  MascotInstance.prototype.popUp = function () {
    this.state = 'popping-up';
    this.animTime = 0;
    this.animDuration = 0.8;
    this.popUpTarget = 0;
  };

  MascotInstance.prototype.hide = function () {
    if (this.state === 'hidden') return;
    this.state = 'hiding';
    this.animTime = 0;
  };

  MascotInstance.prototype.setSize = function (newSize) {
    this.opts.size = newSize;
    var cW = Math.round(newSize * 1.4);
    var cH = Math.round(newSize * 1.7);
    this.renderer.setSize(cW, cH);
    this.camera.aspect = cW / cH;
    this.camera.updateProjectionMatrix();
  };

  MascotInstance.prototype.destroy = function () {
    this.destroyed = true;
    if (this._timerId) clearTimeout(this._timerId);
    if (this.observer) this.observer.disconnect();
    this.model.root.traverse(function (obj) {
      if (obj.geometry) obj.geometry.dispose();
      if (obj.material) {
        if (obj.material.map) obj.material.map.dispose();
        if (obj.material.gradientMap) obj.material.gradientMap.dispose();
        obj.material.dispose();
      }
    });
    this.renderer.dispose();
    if (this.canvas.parentNode) this.canvas.parentNode.removeChild(this.canvas);
    var idx = instances.indexOf(this);
    if (idx > -1) instances.splice(idx, 1);
  };

  /* ═══════════════════════════════════════════════
     PUBLIC API — window.OddMascot
     ═══════════════════════════════════════════════ */
  var instances = [];

  window.OddMascot = {
    create: function (opts) {
      var inst = new MascotInstance(opts || {});
      instances.push(inst);
      return inst;
    },
    destroyAll: function () {
      while (instances.length) instances[0].destroy();
    }
  };
})();
