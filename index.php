<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
define("BASEPATH", dirname(__FILE__));
session_start();

// Panggil koneksi database
require './include/connection.php';

// Penyelarasan variabel koneksi PDO
if (!isset($con)) {
    if (isset($pdo)) { $con = $pdo; }
    elseif (isset($conn)) { $con = $conn; }
    elseif (isset($koneksi)) { $con = $koneksi; }
}

$error = "";

// Proses Login Pemilih (Mendukung NIS maupun NISN) menggunakan PDO Prepared Statement
if (isset($_POST['submit'])) {
    $nis = trim($_POST['nis'] ?? '');

    if (empty($nis)) {
        $error = "AKUN / NIS / NISN tidak boleh kosong!";
    } else {
        $stmt = $con->prepare("SELECT * FROM t_user WHERE id_user = ? OR nisn = ?");
        $stmt->execute([$nis, $nis]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $_SESSION['siswa'] = $data['id_user'];
            $_SESSION['fullname'] = $data['fullname'];
            
            header('location: vote.php');
            exit;
        } else {
            $error = "AKUN / NIS / NISN tidak terdaftar di dalam sistem!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Pemilih - Pemilihan Ketua OSIS</title>
    <link rel="icon" href="images/almadani.png">
    
    <!-- Tailwind & Fonts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(rgba(15, 23, 42, 0.8), rgba(15, 23, 42, 0.9)), url('images/bg-sekolah.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        /* Hilangkan scrollbar untuk galeri paslon */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-between py-6 px-4">

    <!-- Kontainer Kosong untuk mendorong card ke tengah -->
    <div></div>

    <!-- Card Utama -->
    <div class="bg-slate-900 rounded-[2rem] border-t-[8px] border-blue-500 w-full max-w-[540px] mx-auto p-6 md:p-8 shadow-2xl relative border border-slate-700/50">
        
        <!-- Pesan Error Login -->
        <?php if (!empty($error)) { ?>
            <div class="bg-red-500/10 border border-red-500/50 text-red-400 px-4 py-3 rounded-xl text-sm text-center mb-5 font-semibold animate-pulse">
                <i class="fas fa-exclamation-triangle mr-1"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php } ?>

        <!-- Header (Logo & Sekolah) -->
        <div class="flex items-center gap-4 mb-6">
            <div class="w-14 h-14 bg-slate-800 rounded-xl p-2 border border-slate-700 shadow-inner flex items-center justify-center shrink-0">
                <img src="images/almadani.png" alt="Logo" class="max-w-full max-h-full object-contain">
            </div>
            <div>
                <h1 class="font-black text-white text-xl leading-tight tracking-wide">PILKAOS</h1>
                <p class="text-sm text-blue-400 font-bold">SMK METLAND CIBITUNG</p>
            </div>
        </div>

        <!-- Judul -->
        <div class="text-blue-500 text-xs font-black uppercase tracking-widest mb-1">Suara Siswa, Perubahan Nyata</div>
        <h2 class="text-3xl font-black text-white leading-tight mb-2">Pemilihan Ketua OSIS 2026/2027</h2>
        <p class="text-slate-400 text-sm italic mb-5">&ldquo;Suara Anda Menentukan Arah Organisasi.&rdquo;</p>

        <!-- Badge Status & Waktu -->
        <div class="flex flex-wrap items-center gap-3 mb-6 border-b border-slate-700/50 pb-6">
            <span class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-4 py-1.5 rounded-full text-xs font-bold flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span> Berlangsung
            </span>
            <span class="text-slate-300 text-xs font-bold flex items-center gap-1.5 bg-slate-800 px-4 py-1.5 rounded-full border border-slate-700">
                <i class="far fa-calendar-alt text-blue-400"></i> <span id="ev-countdown">Menghitung jadwal...</span>
            </span>
        </div>

        <!-- Section: Cara Memulai Pemilihan -->
        <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-4 mb-6">
            <h3 class="text-white font-extrabold text-sm mb-3">Cara memulai pemilihan</h3>
            <div class="grid grid-cols-2 gap-3">
                
                <!-- Step 1 -->
                <div class="bg-slate-800 rounded-xl p-3 border border-slate-600 flex items-start gap-3">
                    <div class="w-6 h-6 rounded-full bg-blue-500 text-white flex items-center justify-center text-xs font-black shrink-0 mt-0.5">1</div>
                    <div>
                        <div class="font-bold text-white text-sm">Masukkan NIS</div>
                        <div class="text-slate-400 text-[11px] mt-1 leading-snug">Gunakan NIS yang terdaftar.</div>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="bg-slate-800 rounded-xl p-3 border border-slate-600 flex items-start gap-3">
                    <div class="w-6 h-6 rounded-full bg-blue-500 text-white flex items-center justify-center text-xs font-black shrink-0 mt-0.5">2</div>
                    <div>
                        <div class="font-bold text-white text-sm">Mulai memilih</div>
                        <div class="text-slate-400 text-[11px] mt-1 leading-snug">Satu akun untuk satu kali pemilihan.</div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Section: Foto Calon -->
        <div class="mb-7">
            <h3 class="text-white text-center font-extrabold text-sm mb-1">Lihat Foto Calon Sebelum Memilih</h3>
            <p class="text-slate-400 text-center text-xs mb-4">Klik salah satu foto untuk melihat tampilan layar penuh.</p>
            
            <div class="flex justify-center gap-4 overflow-x-auto pb-2 no-scrollbar">
                <?php
                // Mengambil data kandidat dari database menggunakan PDO
                $stmt_kan = $con->query("SELECT * FROM t_kandidat");
                $kandidat_rows = $stmt_kan ? $stmt_kan->fetchAll(PDO::FETCH_ASSOC) : [];
                $js_paslon = [];
                $idx = 0;

                if (count($kandidat_rows) > 0) {
                    foreach ($kandidat_rows as $row) {
                        $fotoFile = isset($row['foto']) ? $row['foto'] : ''; 
                        $fotoPath = !empty($fotoFile) ? 'assets/img/kandidat/' . $fotoFile : 'images/default.png';
                        $labelPaslon = isset($row['nama_calon']) ? $row['nama_calon'] : 'Paslon ' . ($idx + 1);
                        
                        // Simpan untuk dipakai di modal popup JS nanti
                        $js_paslon[] = "{ src: '" . htmlspecialchars($fotoPath, ENT_QUOTES) . "', label: '" . htmlspecialchars($labelPaslon, ENT_QUOTES) . "' }";
                ?>
                <!-- Looping Paslon -->
                <button type="button" class="ev-paslon-trigger relative bg-slate-800 border border-slate-600 rounded-xl w-28 h-28 shrink-0 overflow-hidden group focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-md" data-index="<?php echo $idx; ?>">
                    <img src="<?php echo htmlspecialchars($fotoPath); ?>" alt="<?php echo htmlspecialchars($labelPaslon); ?>" class="w-full h-full object-cover object-center group-hover:scale-110 transition-transform duration-300">
                    <div class="absolute bottom-0 inset-x-0 bg-slate-900/80 backdrop-blur-sm p-1.5 text-center border-t border-slate-700/50">
                        <span class="text-white text-[10px] font-bold line-clamp-1"><?php echo htmlspecialchars($labelPaslon); ?></span>
                    </div>
                </button>
                <?php
                        $idx++;
                    }
                } else {
                    echo '<p class="text-slate-500 text-xs text-center w-full py-4 border border-slate-700 border-dashed rounded-lg">Belum ada data kandidat.</p>';
                }
                ?>
            </div>
        </div>

        <!-- Form Login -->
        <form method="post" action="" autocomplete="off">
            <label class="block text-white font-extrabold text-xs mb-2">AKUN PEMILIHAN KETUA OSIS (NIS / NISN)</label>
            <input type="text" name="nis" required class="w-full bg-slate-800 border border-slate-600 rounded-xl px-5 py-3.5 text-sm font-bold text-white placeholder-slate-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all shadow-inner" placeholder="Masukkan NIS atau NISN Kamu">
            
            <button type="submit" name="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-extrabold py-3.5 rounded-xl mt-4 transition-colors shadow-lg shadow-blue-600/30 text-sm">
                Login dan Mulai Memilih
            </button>
        </form>

    </div>

    <!-- Footer -->
    <div class="mt-8 text-center text-[11px] font-semibold text-slate-500">
        &copy; 2026 E-Voting OSIS SMK Metland Cibitung &middot; Supported by SIACI Network
    </div>

    <!-- ========================================== -->
    <!-- MODAL & POPUP SECTION (HIDDEN BY DEFAULT)  -->
    <!-- ========================================== -->

    <!-- POPUP PENGUMUMAN OTOMATIS -->
    <div id="evWelcomePopup" class="fixed inset-0 bg-slate-900/90 backdrop-blur-sm z-50 flex items-center justify-center p-4 transition-opacity duration-300">
        <div class="relative bg-slate-800 p-2 rounded-2xl shadow-2xl border border-slate-600 max-w-lg w-full transform transition-transform duration-300 scale-100">
            <button type="button" onclick="tutupPopupGlobal()" class="absolute -top-3 -right-3 bg-red-500 hover:bg-red-600 text-white w-8 h-8 rounded-full font-bold shadow-lg flex items-center justify-center transition-transform hover:scale-110 z-10">
                &times;
            </button>
            <img src="images/popup-smk-metland.png" alt="Pengumuman" class="w-full h-auto rounded-xl max-h-[80vh] object-contain">
        </div>
    </div>

    <!-- MODAL ZOOM FOTO KANDIDAT -->
    <div id="evPaslonModal" class="fixed inset-0 bg-slate-900/95 backdrop-blur-md z-50 hidden flex-col items-center justify-center p-4 transition-opacity duration-300">
        <div class="relative max-w-4xl w-full flex flex-col items-center">
            <div class="relative w-full h-[60vh] md:h-[75vh] flex items-center justify-center mb-6 overflow-hidden bg-black/20 rounded-2xl border border-slate-700/50">
                <img id="evPaslonModalImg" src="" alt="Foto Kandidat" class="max-h-full max-w-full object-contain transition-transform duration-300">
            </div>
            
            <!-- Toolbar Zoom & Navigasi -->
            <div class="flex flex-wrap items-center justify-center gap-2 md:gap-3 bg-slate-800 p-2.5 rounded-full border border-slate-600 shadow-xl">
                <button type="button" id="evPrevPaslon" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-full text-xs font-bold transition-colors">&#10235; Prev</button>
                <div class="w-px h-6 bg-slate-600 hidden md:block"></div>
                <button type="button" id="evZoomOut" class="px-3 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-full text-xs font-bold transition-colors"><i class="fas fa-search-minus"></i></button>
                <button type="button" id="evZoomReset" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-full text-xs font-bold transition-colors">Reset</button>
                <button type="button" id="evZoomIn" class="px-3 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-full text-xs font-bold transition-colors"><i class="fas fa-search-plus"></i></button>
                <div class="w-px h-6 bg-slate-600 hidden md:block"></div>
                <button type="button" id="evNextPaslon" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-full text-xs font-bold transition-colors">Next &#10236;</button>
                <button type="button" id="evPaslonModalClose" class="px-5 py-2 bg-red-500 hover:bg-red-600 text-white rounded-full text-xs font-extrabold transition-colors ml-2">TUTUP</button>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- JAVASCRIPT SECTION                         -->
    <!-- ========================================== -->
    <script>
        // --- LOGIKA HITUNG MUNDUR ---
        (function () {
            var target = new Date('2026-11-28T08:00:00'); // Ganti jadwal disini
            function updateCountdown() {
                var now = new Date();
                var diff = target - now;
                var el = document.getElementById('ev-countdown');
                if (!el) return;
                
                if (diff <= 0) {
                    el.innerHTML = '<span class="text-emerald-400">Pemilihan Sedang Berlangsung</span>';
                    return;
                }
                var days  = Math.floor(diff / (1000 * 60 * 60 * 24));
                var hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var mins  = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                el.textContent = 'Sisa ' + days + ' hari ' + hours + ' jam ' + mins + ' mnt';
            }
            updateCountdown();
            setInterval(updateCountdown, 60000); 
        })();

        // --- LOGIKA TUTUP POPUP PENGUMUMAN ---
        function tutupPopupGlobal() {
            var welcomePopup = document.getElementById('evWelcomePopup');
            if(welcomePopup) {
                welcomePopup.classList.add('opacity-0');
                setTimeout(() => {
                    welcomePopup.style.display = 'none';
                    document.body.style.overflow = ''; 
                }, 300);
            }
        }

        // Kunci scroll saat popup muncul pertama kali
        document.addEventListener("DOMContentLoaded", function() {
            var welcomePopup = document.getElementById('evWelcomePopup');
            if (welcomePopup && welcomePopup.style.display !== 'none') {
                document.body.style.overflow = 'hidden';
                welcomePopup.addEventListener('click', function (e) {
                    if (e.target === welcomePopup) tutupPopupGlobal();
                });
            }
        });

        // --- LOGIKA MODAL ZOOM PASLON ---
        (function () {
            var modal = document.getElementById('evPaslonModal');
            var modalImg = document.getElementById('evPaslonModalImg');
            var closeBtn = document.getElementById('evPaslonModalClose');
            var triggers = document.querySelectorAll('.ev-paslon-trigger');
            var zoomLevel = 1;
            var currentIndex = 0;
            
            // Mengambil array paslon dari PHP
            var paslonData = [ <?php echo implode(',', $js_paslon); ?> ];

            if (!modal || !modalImg) return;

            function applyZoom() {
                modalImg.style.transform = 'scale(' + zoomLevel + ')';
            }

            function showPaslon(index) {
                if (paslonData.length === 0) return;
                if (index < 0) index = paslonData.length - 1;
                if (index >= paslonData.length) index = 0;
                currentIndex = index;
                zoomLevel = 1;
                applyZoom();
                modalImg.src = paslonData[index].src;
            }

            triggers.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var idx = parseInt(this.getAttribute('data-index'));
                    showPaslon(idx);
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    setTimeout(() => modal.classList.remove('opacity-0'), 10);
                    document.body.style.overflow = 'hidden';
                });
            });

            function closeModal() {
                modal.classList.add('opacity-0');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    document.body.style.overflow = '';
                }, 300);
            }

            if (closeBtn) closeBtn.addEventListener('click', closeModal);
            modal.addEventListener('click', function (e) {
                if (e.target === modal) closeModal();
            });

            document.getElementById('evPrevPaslon')?.addEventListener('click', function () { showPaslon(currentIndex - 1); });
            document.getElementById('evNextPaslon')?.addEventListener('click', function () { showPaslon(currentIndex + 1); });
            document.getElementById('evZoomIn')?.addEventListener('click', function () { zoomLevel += 0.25; applyZoom(); });
            document.getElementById('evZoomOut')?.addEventListener('click', function () { zoomLevel = Math.max(0.5, zoomLevel - 0.25); applyZoom(); });
            document.getElementById('evZoomReset')?.addEventListener('click', function () { zoomLevel = 1; applyZoom(); });
        })();
    </script>
</body>
</html>