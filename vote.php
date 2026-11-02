<?php
define("BASEPATH", dirname(__FILE__));
session_start();
if (!isset($_SESSION['siswa'])) {
    header('Location: ./index.php');
    exit;
}

require './include/connection.php';

// Penyelarasan variabel koneksi PDO
if (!isset($con)) {
    if (isset($pdo)) { $con = $pdo; }
    elseif (isset($conn)) { $con = $conn; }
    elseif (isset($koneksi)) { $con = $koneksi; }
}

$thn     = date('Y');
$dpn     = date('Y') + 1;
$periode = $thn . '/' . $dpn;
$nis_login = $_SESSION['siswa'];

// CEK APAKAH SISWA SUDAH MEMILIH PADA PERIODE INI MENGGUNAKAN PDO
$stmt_cek = $con->prepare("SELECT * FROM t_pemilih WHERE nis = ? AND periode = ?");
$stmt_cek->execute([$nis_login, $periode]);
if ($stmt_cek->rowCount() > 0) {
    // Jika sudah memilih, hancurkan session siswa dan alihkan ke halaman utama dengan pesan peringatan
    session_destroy();
    echo "<script>alert('Mohon maaf, Anda sudah menggunakan hak suara pada periode ini dan tidak dapat memilih lagi!'); window.location='index.php';</script>";
    exit;
}

// Mengambil data kandidat menggunakan PDO
$stmt_kan = $con->prepare("SELECT * FROM t_kandidat WHERE periode = ?");
$stmt_kan->execute([$periode]);
$kandidat_list = $stmt_kan->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bilik Suara - Pemilihan Ketua OSIS</title>
    <link rel="icon" href="images/almadani.png">
    
    <!-- Tailwind CSS & Fonts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(rgba(15, 23, 42, 0.85), rgba(15, 23, 42, 0.95)), url('images/bg-sekolah.jpg') no-repeat center center fixed;
            background-size: cover;
        }
    </style>
</head>
<body class="min-h-screen text-slate-200 py-8 px-4 sm:px-6 lg:px-8">

    <div class="max-w-6xl mx-auto">
        
        <!-- ============================== -->
        <!-- HEADER SECTION                 -->
        <!-- ============================== -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8 bg-slate-900/60 p-5 md:p-6 rounded-3xl border border-slate-700/50 backdrop-blur-md shadow-lg">
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 bg-slate-800 rounded-2xl p-2 border border-slate-700 shadow-inner flex items-center justify-center shrink-0">
                    <img src="images/almadani.png" alt="Logo" class="max-w-full max-h-full object-contain">
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl font-black text-white leading-tight">Pilih Calon Ketua & Wakil OSIS</h1>
                    <p class="text-blue-400 font-bold text-xs sm:text-sm tracking-wide mt-1">Periode <?php echo htmlspecialchars($periode); ?> &mdash; SMK Metland Cibitung</p>
                </div>
            </div>
            
            <div class="shrink-0 flex justify-start">
                <span class="inline-flex items-center gap-2 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-5 py-2.5 rounded-full text-xs font-black uppercase tracking-wider">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Langkah 2 &mdash; Memilih Calon
                </span>
            </div>
        </div>

        <!-- ============================== -->
        <!-- PETUNJUK SECTION               -->
        <!-- ============================== -->
        <div class="mb-10 bg-slate-800/80 border-l-4 border-blue-500 rounded-2xl p-5 md:p-6 backdrop-blur-sm border-y border-r border-slate-700/50 shadow-xl">
            <h3 class="text-white font-extrabold text-sm md:text-base mb-4 flex items-center gap-2">
                <i class="fas fa-info-circle text-blue-400 text-lg"></i> Petunjuk Singkat:
            </h3>
            <ul class="space-y-3 text-slate-300 text-xs md:text-sm">
                <li class="flex items-start gap-3">
                    <div class="bg-blue-500/20 p-1 rounded-full shrink-0 mt-0.5"><i class="fas fa-check text-blue-400 text-[10px] w-3 h-3 flex justify-center items-center"></i></div>
                    <span>Pelajari profil setiap pasangan calon melalui tombol <b class="text-white">Lihat Profil & Beri Suara</b>.</span>
                </li>
                <li class="flex items-start gap-3">
                    <div class="bg-blue-500/20 p-1 rounded-full shrink-0 mt-0.5"><i class="fas fa-check text-blue-400 text-[10px] w-3 h-3 flex justify-center items-center"></i></div>
                    <span>Pastikan Anda benar-benar yakin sebelum mengkonfirmasi pilihan di halaman berikutnya.</span>
                </li>
                <li class="flex items-start gap-3">
                    <div class="bg-blue-500/20 p-1 rounded-full shrink-0 mt-0.5"><i class="fas fa-check text-blue-400 text-[10px] w-3 h-3 flex justify-center items-center"></i></div>
                    <span>Setiap NIS/NISN hanya mempunyai <b class="text-white px-2 py-0.5 bg-red-500/20 border border-red-500/30 rounded text-[11px] md:text-xs">1 KESEMPATAN</b> untuk memberikan suara.</span>
                </li>
            </ul>
        </div>

        <!-- ============================== -->
        <!-- GRID KANDIDAT SECTION          -->
        <!-- ============================== -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8 pb-10">
            
            <?php
            if (count($kandidat_list) > 0) {
                foreach ($kandidat_list as $data) {
                    $id      = isset($data['id_kandidat']) ? $data['id_kandidat'] : (isset($data['id']) ? $data['id'] : '');
                    
                    $nama    = '';
                    if (isset($data['nama_calon'])) { $nama = $data['nama_calon']; }
                    elseif (isset($data['nama'])) { $nama = $data['nama']; }
                    elseif (isset($data['fullname'])) { $nama = $data['fullname']; }

                    $foto    = isset($data['foto']) ? $data['foto'] : '';
                    $program = isset($data['program']) && !empty($data['program']) ? $data['program'] : 'Belum ada data program unggulan.';
                    $no_k    = isset($data['no_k']) ? $data['no_k'] : 1;
                    $nomor_tampil = str_pad((int)$no_k, 2, '0', STR_PAD_LEFT);
            ?>
            
            <!-- Card Paslon -->
            <div class="relative bg-slate-800 rounded-[2rem] overflow-hidden border border-slate-700 shadow-xl flex flex-col h-full group hover:-translate-y-2 hover:shadow-[0_20px_40px_-15px_rgba(37,99,235,0.3)] transition-all duration-300">
                
                <!-- Badge Nomor -->
                <div class="absolute top-4 left-4 z-20">
                    <span class="bg-gradient-to-r from-blue-600 to-blue-500 text-white text-[11px] font-black px-4 py-2 rounded-full shadow-lg border border-blue-400/30 tracking-wider">
                        PASLON <?php echo $nomor_tampil; ?>
                    </span>
                </div>

                <!-- Area Foto -->
                <div class="relative h-60 sm:h-64 w-full bg-slate-900 overflow-hidden flex items-end justify-center">
                    <div class="absolute inset-0 bg-blue-500/10 mix-blend-overlay z-10"></div>
                    <img src="./assets/img/kandidat/<?php echo htmlspecialchars($foto); ?>" alt="Foto <?php echo htmlspecialchars($nama); ?>" class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500 relative z-0">
                    <!-- Gradasi bawah foto -->
                    <div class="absolute bottom-0 inset-x-0 h-28 bg-gradient-to-t from-slate-800 to-transparent z-10"></div>
                </div>

                <!-- Area Konten (Nama & Tombol) -->
                <div class="p-6 flex flex-col flex-grow bg-slate-800 relative z-20 text-center -mt-6">
                    
                    <div class="mb-4">
                        <h2 class="text-xl font-black text-white leading-tight mb-1 capitalize"><?php echo htmlspecialchars($nama); ?></h2>
                        <p class="text-[10px] font-extrabold text-blue-400 tracking-widest uppercase">Calon Ketua & Wakil Ketua</p>
                    </div>

                    <div class="bg-slate-900/50 border border-slate-700/50 rounded-xl p-4 mb-6 flex-grow flex flex-col justify-center">
                        <strong class="text-slate-500 text-[10px] uppercase tracking-wider mb-2 block">Program Unggulan:</strong>
                        <p class="text-slate-300 text-xs italic leading-relaxed line-clamp-3">
                            &ldquo;<?php echo htmlspecialchars($program); ?>&rdquo;
                        </p>
                    </div>

                    <!-- Tombol Action -->
                    <a href="./detail.php?id=<?php echo (int)$id; ?>" class="mt-auto block w-full bg-blue-600 hover:bg-blue-500 text-white font-extrabold py-3.5 px-6 rounded-xl transition-all shadow-lg shadow-blue-500/20 border border-blue-500/50 text-sm">
                        <i class="fas fa-vote-yea mr-2"></i> Lihat Profil & Beri Suara
                    </a>
                    
                </div>
            </div>
            
            <?php 
                } 
            } else {
            ?>
                <!-- State Kosong / Belum Ada Calon -->
                <div class="col-span-full text-center py-20 bg-slate-800/50 rounded-3xl border border-slate-700 border-dashed backdrop-blur-sm">
                    <div class="w-16 h-16 bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users-slash text-2xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-1">Belum Ada Calon Ketua OSIS</h3>
                    <p class="text-sm text-slate-400">Panitia belum menambahkan data kandidat untuk periode <?php echo htmlspecialchars($periode); ?>.</p>
                </div>
            <?php
            }
            ?>

        </div>
        
        <!-- Footer -->
        <footer class="mt-4 mb-10 text-center border-t border-slate-700/50 pt-8">
            <p class="text-xs text-slate-400 mb-2">Setelah Anda mengkonfirmasi pilihan di halaman berikutnya, suara tidak dapat diubah kembali.</p>
            <p class="text-[11px] font-bold text-slate-500 tracking-wide uppercase">
                &copy; 2026 E-Voting OSIS SMK Metland Cibitung &middot; Supported by SIACI Network
            </p>
        </footer>

    </div>

</body>
</html>