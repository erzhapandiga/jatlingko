<?php defined('BASEPATH') or die("No Access Allowed"); ?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Pilkaos - Login NIS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <style>
  html, body {
    margin: 0;
    padding: 0;
    height: 100%;
  }

  /* ===== GLOBAL PAGE STYLE (KHUSUS HALAMAN INI) ===== */
  body {
    font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI",
      Roboto, sans-serif;
    color: #e5e7eb;
    /* Background foto sekolah + overlay gelap */
    background:
      linear-gradient(rgba(15, 23, 42, 0.88), rgba(15, 23, 42, 0.96)),
      url('images/bg-sekolah.jpg') center center / cover no-repeat fixed;
  }

  .ev-wrapper {
    width: 100%;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px 16px 8px;
    box-sizing: border-box; /* Tambahan agar padding tidak melebar */
  }

  .ev-card {
    width: 100%;
    max-width: 520px;
    border-radius: 22px;
    background: radial-gradient(circle at top left,
              rgba(249, 250, 251, 0.14), rgba(15, 23, 42, 0.94));
    box-shadow: 0 28px 60px rgba(0, 0, 0, 0.75);
    border: 1px solid rgba(148, 163, 184, 0.55);
    overflow: hidden;
    backdrop-filter: blur(18px);
  }

  .ev-card-header {
    padding: 18px 22px 14px;
   background: linear-gradient(120deg, #1e3a8a, #3b82f6, #60a5fa); /* warna OSIS */
    color: #f9fafb;
    display: flex;
    align-items: center;
    gap: 14px;
  }

  .ev-logo-box {
    width: 56px;
    height: 56px;
    border-radius: 18px;
    background: rgba(15, 23, 42, 0.65);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    box-shadow: 0 16px 32px rgba(15, 23, 42, 0.9);
    flex-shrink: 0; /* Agar logo tidak gepeng */
  }

  .ev-logo-box img {
    max-width: 46px;
    max-height: 46px;
  }

  .ev-header-text {
    flex: 1;
  }

  .ev-title-main {
    font-size: 18px;
    font-weight: 700;
    margin: 0;
  }

  .ev-title-sub {
    font-size: 12px;
    opacity: .94;
    margin: 2px 0 2px;
  }

  .ev-tagline {
    font-size: 11px;
    opacity: 0.96;
    font-style: italic;
  }

  .ev-countdown-pill {
    margin-left: auto;
    padding: 4px 10px;
    border-radius: 999px;
    background: rgba(15, 23, 42, 0.95);
    font-size: 10px;
    white-space: nowrap;
  }

  .ev-card-body {
    padding: 20px 22px 18px;
  }

  .ev-info {
    font-size: 12px;
    color: #d1d5db;
    margin-bottom: 14px;
  }

  .ev-info ul {
    padding-left: 18px;
    margin: 6px 0 0;
  }

  .ev-info li {
    margin-bottom: 4px;
  }

  /* ===== GALERI PASLON ===== */
  .ev-gallery {
    margin-top: 6px;
    margin-bottom: 16px;
  }

  .ev-gallery-title {
    font-size: 13px;
    font-weight: 600;
    color: #e5e7eb;
    margin: 0 0 2px;
  }

  .ev-gallery-sub {
    font-size: 11px;
    color: #9ca3af;
    margin: 0 0 8px;
  }

  .ev-gallery-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 8px;
  }

  .ev-paslon {
    position: relative;
    border: none;
    padding: 0;
    border-radius: 14px;
    overflow: hidden;
    cursor: pointer;
    background: rgba(15, 23, 42, 0.9);
    box-shadow: 0 10px 20px rgba(15, 23, 42, 0.85);
    transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease;
  }

  .ev-paslon img {
    width: 100%;
    height: 110px;
    object-fit: cover;
    display: block;
    filter: saturate(1.05);
  }

  .ev-paslon-tag {
    position: absolute;
    bottom: 6px;
    left: 8px;
    padding: 3px 8px;
    border-radius: 999px;
    font-size: 10px;
    background: rgba(15, 23, 42, 0.9);
    color: #f9fafb;
  }

  .ev-paslon:hover {
    transform: translateY(-3px);
    box-shadow: 0 14px 26px rgba(15, 23, 42, 1);
  }

  .ev-paslon:focus {
    outline: 2px solid #0ea5e9;
    outline-offset: 2px;
  }

  .ev-form-group {
    margin-bottom: 12px;
  }

  .ev-label {
    display: block;
    font-size: 13px;
    font-weight: 500;
    color: #e5e7eb;
    margin-bottom: 4px;
  }

  .ev-input {
    width: 100%;
    border-radius: 999px;
    border: 1px solid #4b5563;
    background: rgba(15, 23, 42, 0.85);
    color: #f9fafb;
    padding: 10px 14px;
    font-size: 14px;
    outline: none;
    transition: border-color .15s ease, box-shadow .15s ease,
      background .15s ease;
    box-sizing: border-box; /* Agar padding tidak merusak lebar */
  }

  .ev-input::placeholder {
    color: #9ca3af;
  }

  .ev-input:focus {
    border-color: #0ea5e9;
    background: rgba(15, 23, 42, 0.95);
    box-shadow: 0 0 0 2px rgba(14, 165, 233, 0.35);
  }

  .ev-btn-submit {
    width: 100%;
    border-radius: 999px;
    border: none;
    background: linear-gradient(135deg, #0f172a, #020617);
    color: #f9fafb;
    font-size: 14px;
    font-weight: 600;
    padding: 10px 14px;
    cursor: pointer;
    margin-top: 6px;
    box-shadow: 0 12px 26px rgba(15, 23, 42, 0.85);
    transition: background .16s ease, transform .16s ease,
      box-shadow .16s ease;
  }

  .ev-btn-submit:hover {
    background: linear-gradient(135deg, #020617, #020617);
    transform: translateY(-1px);
    box-shadow: 0 16px 32px rgba(15, 23, 42, 0.95);
  }

  .ev-btn-submit:active {
    transform: translateY(0);
    box-shadow: 0 9px 18px rgba(15, 23, 42, 0.85);
  }

  .ev-footer {
    padding-top: 10px;
    font-size: 11px;
    color: #9ca3af;
    text-align: center;
  }

  .ev-swipe-hint {
    margin-top: 8px;
    font-size: 11px;
    color: #9ca3af;
    text-align: center;
    display: none;
  }

  /* animasi muncul */
  .ev-card {
    animation: ev-fade-up .4s ease-out;
  }

  @keyframes ev-fade-up {
    from { opacity: 0; transform: translateY(24px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  .ev-footer-global {
    width:100%;
    text-align:center;
    font-size:11px;
    color:#e5e7eb;
    margin-top:8px;
    padding-top:4px;
    padding-bottom:4px;
    border-top:1px solid rgba(148, 163, 184, 0.35);
    font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI",
      Roboto, sans-serif;
  }

  /* ===== MODAL FULLSCREEN FOTO PASLON ===== */
  .ev-modal {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.85);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
  }

  .ev-modal.show {
    display: flex;
  }

  .ev-modal-content {
    position: relative;
    max-width: 92vw;
    max-height: 92vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
  }

  .ev-modal-img-wrap {
    max-width: 92vw;
    max-height: 88vh;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .ev-modal-content img {
    max-height: 88vh;
    max-width: 90vw;
    width: auto;
    height: auto;
    border-radius: 18px;
    object-fit: contain;
    box-shadow: 0 30px 60px rgba(0, 0, 0, 0.9);
    transform-origin: center center;
    transition: transform 0.15s ease;
  }

  .ev-modal-toolbar {
    display: flex;
    gap: 8px;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
  }

  .ev-modal-btn {
    border-radius: 999px;
    border: none;
    padding: 5px 12px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    background: rgba(15, 23, 42, 0.95);
    color: #f9fafb;
    box-shadow: 0 10px 20px rgba(0,0,0,0.8);
  }

  .ev-modal-btn:hover {
    background: rgba(15, 23, 42, 1);
  }

  .ev-modal-caption {
    font-size: 12px;
    color: #e5e7eb;
    text-align: center;
  }

  .ev-modal-btn-main {
    background: #facc15;
    color: #111827;
  }

  .ev-modal-btn-main:hover {
    background: #eab308;
  }

  @media (max-width: 640px) {
    .ev-card {
      border-radius: 18px;
    }
    .ev-card-header {
      padding: 16px 18px 12px;
      flex-wrap: wrap;
    }
    .ev-countdown-pill {
      margin-top: 6px;
    }
    .ev-card-body {
      padding: 16px 18px 14px;
    }
    .ev-swipe-hint {
      display: block;
    }
    .ev-gallery-grid {
      gap: 6px;
    }
    .ev-paslon img {
      height: 100px;
    }
  }

 /* ===== POPUP GAMBAR PPDB SMK METLAND CIBITUNG (FINAL FIX) ===== */
  .ppdb-popup-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100vw;               /* Pakai Viewport Width agar full layar */
    height: 100vh;              /* Pakai Viewport Height agar full layar */
    background: rgba(15, 23, 42, 0.92);
    display: grid;              /* Ganti ke GRID agar lebih sakti */
    place-items: center;        /* Kunci posisi di tengah vertikal & horizontal */
    z-index: 99999;             /* Z-index sangat tinggi agar paling depan */
    opacity: 0;
    pointer-events: none;
    transition: opacity .3s ease;
    padding: 20px;
    box-sizing: border-box;
    margin: 0 !important;       /* Reset margin global jika ada */
  }

  .ppdb-popup-backdrop.show {
    opacity: 1;
    pointer-events: auto;
  }

  .ppdb-popup-modal {
    position: relative;
    width: auto !important;     /* Pastikan lebar mengikuti konten gambar */
    height: auto !important;
    max-width: 90vw;
    max-height: 85vh;
    display: flex;              /* Hilangkan whitespace di bawah gambar */
    justify-content: center;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.8);
    border-radius: 18px;
    background: transparent;    /* Pastikan tidak ada background putih */
  }

  .ppdb-popup-modal img {
    display: block;
    max-width: 100%;
    max-height: 85vh;           /* Batasi tinggi gambar agar tidak scroll */
    width: auto;
    height: auto;
    border-radius: 18px;
    object-fit: contain;
  }

  /* Tombol Close di Pojok Kanan Atas Gambar */
  .ppdb-popup-close {
    position: absolute;
    top: -15px;
    right: -15px;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: 3px solid #fff;     /* Border putih lebih tebal */
    background: #ef4444;
    color: #fff;
    font-size: 14px;
    font-weight: 800;
    cursor: pointer;
    box-shadow: 0 6px 15px rgba(0,0,0,0.6);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 100000;
    padding: 0;
    transition: transform 0.2s ease;
  }

  .ppdb-popup-close:hover {
    background: #dc2626;
    transform: scale(1.1);
  }

  /* Perbaikan Mobile: Geser tombol agar tidak keluar layar */
  @media (max-width: 640px) {
    .ppdb-popup-close {
      top: 5px;
      right: 5px;
      width: 30px;
      height: 30px;
      border: 2px solid rgba(255,255,255,0.8);
      background: rgba(220, 38, 38, 0.9);
      font-size: 12px;
    }
  }
  </style>
</head>
<body>

<div class="ev-wrapper">
  <div class="ev-card">
    <div class="ev-card-header">
      <div class="ev-logo-box">
        <img src="images/almadani.png" alt="Logo Sekolah">
      </div>
      <div class="ev-header-text">
        <h2 class="ev-title-main">Aplikasi Pemilihan Ketua OSIS</h2>
        <p class="ev-title-sub">SMK METLAND CIBITUNG &mdash; Panel Pemilih</p>
        <div class="ev-tagline">“Suara Anda Menentukan Arah Organisasi.”</div>
      </div>
      <div class="ev-countdown-pill" id="ev-countdown">
        Menghitung jadwal...
      </div>
    </div>

    <div class="ev-card-body">
      <div class="ev-info">
        Masukkan Akun yang sudah diberikan untuk memulai proses pemilihan.
        <ul>
          <li>Satu AKUN hanya dapat digunakan <strong>satu kali</strong>.</li>
          <li>Pastikan AKUN yang dimasukkan sudah benar.</li>
          <li>Setelah memilih, Anda tidak dapat mengubah pilihan.</li>
        </ul>
      </div>

      <div class="ev-gallery">
        <h3 class="ev-gallery-title">Lihat Foto Calon Sebelum Memilih</h3>
        <p class="ev-gallery-sub">Klik salah satu foto untuk melihat tampilan layar penuh, lalu geser ke paslon lainnya.</p>
        <div class="ev-gallery-grid">
          <button type="button" class="ev-paslon" data-index="0">
            <div class="ev-paslon-tag">Paslon 1</div>
            <img src="images/paslon-1.jpeg" alt="Pasangan Calon 1">
          </button>
          <button type="button" class="ev-paslon" data-index="1">
            <div class="ev-paslon-tag">Paslon 2</div>
            <img src="images/paslon-2.jpeg" alt="Pasangan Calon 2">
          </button>
          <button type="button" class="ev-paslon" data-index="2">
            <div class="ev-paslon-tag">Paslon 3</div>
            <img src="images/paslon-3.jpeg" alt="Pasangan Calon 3">
          </button>
        </div>
      </div>

      <form method="post" action="" autocomplete="off">
        <div class="ev-form-group">
          <label for="nis" class="ev-label">AKUN Pemilihan Ketua OSIS</label>
          <input
            type="number"
            name="nis"
            id="nis"
            class="ev-input"
            placeholder="Masukkan AKUN Kamu"
            required
          >
        </div>

        <button type="submit" name="submit" class="ev-btn-submit">
          Login dan Mulai Memilih
        </button>
      </form>

      <div class="ev-footer">
        &copy; <?php echo date('Y'); ?> Panitia Pemilihan OSIS SMK Metland Cibitung
      </div>

      <div class="ev-swipe-hint">
        👆 Jika tampilan tidak penuh di layar, gulir perlahan ke atas atau bawah.
      </div>
    </div>
  </div>
</div>

<div class="ev-modal" id="evPaslonModal">
  <div class="ev-modal-content">
    <div class="ev-modal-img-wrap">
      <img src="" alt="Foto Pasangan Calon" id="evPaslonModalImg">
    </div>
    <div class="ev-modal-toolbar">
      <button type="button" class="ev-modal-btn" id="evPrevPaslon">⟵ Sebelumnya</button>
      <button type="button" class="ev-modal-btn" id="evZoomOut">- Zoom</button>
      <button type="button" class="ev-modal-btn" id="evZoomReset">Reset</button>
      <button type="button" class="ev-modal-btn" id="evZoomIn">+ Zoom</button>
      <button type="button" class="ev-modal-btn" id="evNextPaslon">Berikutnya ⟶</button>
      <button type="button" class="ev-modal-btn ev-modal-btn-main" id="evPaslonModalClose">Kembali</button>
    </div>
    <div class="ev-modal-caption" id="evPaslonCaption"></div>
  </div>
</div>

<div class="ev-footer-global">
  © <?php echo date('Y'); ?> E-Voting OSIS SMK Metland Cibitung · Supported by SIACI Network
</div>

<div class="ppdb-popup-backdrop" id="ppdbPopup">
  <div class="ppdb-popup-modal">
    <button type="button" class="ppdb-popup-close" id="ppdbPopupClose">
      Tutup
    </button>
    <img src="images/popup-smk-metland.png" alt="PPDB SMK Metland Cibitung">
  </div>
</div>

<script>
  (function () {
    // 🔁 UBAH tanggal & jam sesuai hari-H pemilihan
    // Format: YYYY-MM-DDTHH:MM:SS
    var target = new Date('2025-11-28T08:00:00');

    function updateCountdown() {
      var now = new Date();
      var diff = target - now;
      var el = document.getElementById('ev-countdown');
      if (!el) return;

      if (diff <= 0) {
        el.textContent = 'Pemilihan sedang berlangsung';
        return;
      }

      var days  = Math.floor(diff / (1000 * 60 * 60 * 24));
      var hours = Math.floor(diff / (1000 * 60 * 60)) % 24;

      el.textContent = 'Sisa ' + days + ' hari ' + hours + ' jam';
    }

    updateCountdown();
    setInterval(updateCountdown, 60000); // update tiap 1 menit
  })();

  // Modal foto paslon + NAV + ZOOM
  (function () {
    var modal     = document.getElementById('evPaslonModal');
    var modalImg  = document.getElementById('evPaslonModalImg');
    var closeBtn  = document.getElementById('evPaslonModalClose');
    var prevBtn   = document.getElementById('evPrevPaslon');
    var nextBtn   = document.getElementById('evNextPaslon');
    var zoomInBtn = document.getElementById('evZoomIn');
    var zoomOutBtn= document.getElementById('evZoomOut');
    var zoomReset = document.getElementById('evZoomReset');
    var captionEl = document.getElementById('evPaslonCaption');
    var triggers  = document.querySelectorAll('.ev-paslon');

    var zoomLevel = 1;
    var currentIndex = 0;

    var paslonData = [
      { src: 'images/paslon-1.jpeg', label: 'Paslon 1' },
      { src: 'images/paslon-2.jpeg', label: 'Paslon 2' },
      { src: 'images/paslon-3.jpeg', label: 'Paslon 3' }
    ];

    if (!modal || !modalImg) return;

    function applyZoom() {
      modalImg.style.transform = 'scale(' + zoomLevel + ')';
    }

    function showPaslon(index) {
      if (index < 0) index = paslonData.length - 1;
      if (index >= paslonData.length) index = 0;
      currentIndex = index;
      zoomLevel = 1;
      applyZoom();
      modalImg.src = paslonData[index].src;
      captionEl.textContent = paslonData[index].label + ' — gunakan panah untuk berpindah calon.';
    }

    function openModal(index) {
      showPaslon(index);
      modal.classList.add('show');
      document.body.style.overflow = 'hidden';
    }

    function closeModal() {
      modal.classList.remove('show');
      modalImg.src = '';
      document.body.style.overflow = '';
    }

    triggers.forEach(function (btn) {
      btn.addEventListener('click', function () {
        var idx = parseInt(this.getAttribute('data-index'), 10);
        if (!isNaN(idx)) {
          openModal(idx);
        }
      });
    });

    if (closeBtn) {
      closeBtn.addEventListener('click', closeModal);
    }
    if (prevBtn) {
      prevBtn.addEventListener('click', function () {
        showPaslon(currentIndex - 1);
      });
    }
    if (nextBtn) {
      nextBtn.addEventListener('click', function () {
        showPaslon(currentIndex + 1);
      });
    }
    if (zoomInBtn) {
      zoomInBtn.addEventListener('click', function () {
        zoomLevel = Math.min(zoomLevel + 0.25, 3);
        applyZoom();
      });
    }
    if (zoomOutBtn) {
      zoomOutBtn.addEventListener('click', function () {
        zoomLevel = Math.max(zoomLevel - 0.25, 0.5);
        applyZoom();
      });
    }
    if (zoomReset) {
      zoomReset.addEventListener('click', function () {
        zoomLevel = 1;
        applyZoom();
      });
    }

    // klik area gelap untuk menutup
    modal.addEventListener('click', function (e) {
      if (e.target === modal) {
        closeModal();
      }
    });

    // keyboard
    document.addEventListener('keydown', function (e) {
      if (!modal.classList.contains('show')) return;
      if (e.key === 'Escape') {
        closeModal();
      } else if (e.key === 'ArrowRight') {
        showPaslon(currentIndex + 1);
      } else if (e.key === 'ArrowLeft') {
        showPaslon(currentIndex - 1);
      }
    });
  })();

  // POPUP GAMBAR PPDB SMK METLAND (muncul setiap refresh)
  (function () {
    var popup = document.getElementById('ppdbPopup');
    var close = document.getElementById('ppdbPopupClose');

    if (!popup) return;

    function showPopup() {
      popup.classList.add('show');       // bikin terlihat
      document.body.style.overflow = 'hidden';
    }

    function hidePopup() {
      popup.classList.remove('show');
      document.body.style.overflow = '';
    }

    // selalu tampil 0.4 detik setelah halaman siap
    window.addEventListener('load', function () {
      setTimeout(showPopup, 400);
    });

    if (close) close.addEventListener('click', hidePopup);

    // klik area gelap di luar gambar => tutup
    popup.addEventListener('click', function (e) {
      if (e.target === popup) {
        hidePopup();
      }
    });
  })();
</script>
</body>
</html>