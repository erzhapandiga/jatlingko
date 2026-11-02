<?php
define('BASEPATH', dirname(__FILE__));
session_start();

if (!isset($_SESSION['siswa'])) {
    header('location:./');
    exit;
}

if (isset($_GET['id'])) {
    require('./include/connection.php');

    // Penyelarasan variabel koneksi PDO
    if (!isset($con)) {
        if (isset($pdo)) { $con = $pdo; }
        elseif (isset($conn)) { $con = $conn; }
        elseif (isset($koneksi)) { $con = $koneksi; }
    }

    $id_kandidat = intval($_GET['id']);
    
    // Cek apakah kolom detail_data sudah ada di database atau belum menggunakan PDO
    $has_detail = false;
    try {
        $check_col = $con->query("SHOW COLUMNS FROM t_kandidat LIKE 'detail_data'");
        if ($check_col && $check_col->rowCount() > 0) {
            $has_detail = true;
        }
    } catch (PDOException $e) {
        $has_detail = false;
    }

    if ($has_detail) {
        $sql = $con->prepare("SELECT id_kandidat, nama_calon, foto, visi, misi, suara, periode, program, no_k, detail_data FROM t_kandidat WHERE id_kandidat = ?");
    } else {
        $sql = $con->prepare("SELECT id_kandidat, nama_calon, foto, visi, misi, suara, periode, program, no_k FROM t_kandidat WHERE id_kandidat = ?");
    }

    $sql->execute([$id_kandidat]);
    $row = $sql->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $id      = $row['id_kandidat'];
        $nama    = $row['nama_calon'];
        $foto    = $row['foto'];
        $visi    = $row['visi'];
        $misi    = $row['misi'];
        $suara   = $row['suara'];
        $periode = $row['periode'];
        $program = $row['program'];
        $no_k    = $row['no_k'];
        $detail  = $has_detail ? $row['detail_data'] : 'Kolom detail_data belum ditambahkan di database.';
    } else {
        header('location: ./');
        exit;
    }
} else {
    header('location: ./');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
   <meta charset="utf-8">
   <title>Profil Calon Ketua dan Wakil OSIS</title>
   <link rel="icon" href="images/almadani.png">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
   <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
   
   <style type="text/css">
      body {
         font-family: 'Nunito', sans-serif !important;
         width: 100%;
         min-height: 100vh;
         margin: 0;
         padding: 0;
         background: linear-gradient(rgba(15, 23, 42, 0.75), rgba(15, 23, 42, 0.85)), url('images/bg-sekolah.jpg') no-repeat center center fixed !important;
         background-size: cover !important;
         display: flex;
         align-items: center;
         justify-content: center;
      }

      .main-card-box {
         background: rgba(255, 255, 255, 0.98);
         backdrop-filter: blur(15px);
         border-radius: 20px;
         border: 1px solid rgba(255, 255, 255, 0.4);
         box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
         width: 100%;
         max-width: 1050px;
         padding: 35px 40px;
         color: #0f172a;
      }

      /* HEADER DENGAN LOGO DI KIRI ATAS */
      .card-header-custom {
         display: flex;
         align-items: center;
         gap: 16px;
         margin-bottom: 25px;
         border-bottom: 2px solid #cbd5e1;
         padding-bottom: 20px;
      }

      .header-logo {
         width: 52px;
         height: 52px;
         object-fit: contain;
         flex-shrink: 0;
         filter: drop-shadow(0 2px 4px rgba(0,0,0,0.15));
      }

      /* KOTAK PEMBUNGKUS KONTEN TULISAN DENGAN WARNA LEBIH TERANG DAN KONTRAS */
      .content-display-box {
         background: #ffffff;
         border: 1.5px solid #cbd5e1;
         border-radius: 14px;
         padding: 24px;
         min-height: 230px;
         box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
      }

      /* FLIP CARD STYLING */
      .flip-card {
         background-color: transparent;
         width: 100%;
         max-width: 320px;
         height: 400px;
         perspective: 1000px;
         margin: 0 auto;
      }

      .flip-card-inner {
         position: relative;
         width: 100%;
         height: 100%;
         text-align: center;
         transition: transform 0.8s;
         transform-style: preserve-3d;
      }

      .flip-card:hover .flip-card-inner {
         transform: rotateY(180deg);
      }

      .flip-card-front,
      .flip-card-back {
         position: absolute;
         width: 100%;
         height: 100%;
         backface-visibility: hidden;
         border-radius: 16px;
         box-shadow: 0 10px 25px rgba(0,0,0,0.15);
      }

      .flip-card-front {
         background-color: #fff;
         overflow: hidden;
      }

      .flip-card-front img {
         width: 100%;
         height: 100%;
         object-fit: cover;
      }

      .flip-card-back {
         background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
         color: white;
         transform: rotateY(180deg);
         display: flex;
         flex-direction: column;
         justify-content: center;
         align-items: center;
         padding: 20px;
      }

      /* TAB NAVIGATION */
      .tab-container-custom {
         background: #cbd5e1;
         border-radius: 12px;
         padding: 6px;
         display: flex;
         gap: 5px;
         margin-top: 20px;
      }

      .tablink {
         background-color: transparent;
         color: #1e293b;
         font-weight: 800;
         border: none;
         outline: none;
         cursor: pointer;
         padding: 10px 5px;
         font-size: 13px;
         flex: 1;
         border-radius: 8px;
         transition: all 0.2s ease;
         text-align: center;
      }

      .tablink:hover {
         color: #0f172a;
         background: #94a3b8;
      }

      .tablink.active {
         background: #2563eb !important;
         color: #ffffff !important;
         box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
      }

      .tabcontent {
         display: none;
      }

      .tabcontent.active-tab {
         display: block !important;
      }

      .tabcontent h3 {
         font-weight: 900;
         color: #0f172a;
         font-size: 21px;
      }

      .tabcontent p {
         color: #0f172a !important;
         font-size: 15px;
         font-weight: 600;
         line-height: 1.7;
         margin-bottom: 0;
      }

      /* ACTION BUTTONS */
      .action-btn-group {
         display: flex;
         gap: 12px;
         margin-top: 25px;
         border-top: 2px solid #cbd5e1;
         padding-top: 20px;
      }

      .action-btn-group .btn {
         border-radius: 10px;
         font-weight: 800;
         padding: 10px 24px;
         box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      }

      @media (max-width: 768px) {
         body {
            align-items: flex-start;
            padding: 15px;
         }
         .main-card-box {
            padding: 20px;
         }
         .card-header-custom {
            flex-direction: column;
            text-align: center;
         }
         .tablink {
            font-size: 11px;
            padding: 8px 2px;
         }
      }
   </style>
</head>

<body>
   <div class="main-card-box">
      <!-- HEADER DENGAN LOGO DI KIRI ATAS -->
      <div class="card-header-custom">
         <img src="images/almadani.png" alt="Logo Sekolah" class="header-logo">
         <div>
            <h2 style="font-weight: 900; color: #0f172a; font-size: 23px; margin-bottom: 2px; letter-spacing: 0.5px;">PROFIL KANDIDAT OSIS</h2>
            <p class="mb-0" style="font-size: 14px; color: #334155; font-weight: 700;">Kenali lebih dekat visi, misi, dan program kerja calon pilihan Anda.</p>
         </div>
      </div>

      <div class="row align-items-center">
         <!-- FOTO / FLIP CARD -->
         <div class="col-lg-4 mb-4 mb-lg-0">
            <div class="flip-card">
               <div class="flip-card-inner">
                  <div class="flip-card-front">
                     <img src="./assets/img/kandidat/<?php echo htmlspecialchars($foto); ?>" alt="Foto Kandidat">
                  </div>
                  <div class="flip-card-back">
                     <img src="images/almadani.png" alt="Logo" style="width: 60px; margin-bottom: 15px; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.3));">
                     <span style="font-size: 11px; font-weight: 800; letter-spacing: 1.5px; color: #cbd5e1; text-transform: uppercase;">Nomor Kandidat</span>
                     <h1 style='font-size: 60px; font-weight: 900; margin: 5px 0 10px; color: #38bdf8;'><?php echo htmlspecialchars($no_k); ?></h1>
                     <hr style="background: rgba(255,255,255,0.3); width: 60px; margin: 5px 0 10px;">
                     <h5 style="font-size: 16px; font-weight: 800; margin: 0; color: #ffffff;"><?php echo htmlspecialchars($nama); ?></h5>
                  </div>
               </div>
            </div>
         </div>

         <!-- KONTEN TAB INFORMASI -->
         <div class="col-lg-8 pl-lg-4">
            <div class="content-display-box">
               <div id="HomeTab" class="tabcontent active-tab">
                  <h3 style="color: #0f172a; font-size: 21px; font-weight: 900; margin-bottom: 10px;">SELAMAT DATANG DI PEMILIHAN OSIS</h3>
                  <p class="mb-2 text-primary font-weight-bold" style="font-size: 16px; color: #2563eb !important;">Periode <?php echo htmlspecialchars($periode); ?></p>
                  <p style="color: #334155 !important; font-size: 15px; font-weight: 600;">Silakan gunakan tombol navigasi di bawah untuk melihat Detail Data Calon, Visi, Misi, hingga Program Kerja unggulan dari kandidat ini sebelum memberikan suara.</p>
               </div>

               <div id="ProfilTab" class="tabcontent">
                  <h3 style="color: #0f172a;">Detail Data Calon</h3>
                  <hr style="width: 60px; margin-left: 0; border-top: 3px solid #2563eb; margin-bottom: 15px;">
                  <p style="white-space: pre-line; color: #0f172a !important;"><?php echo nl2br(htmlspecialchars(!empty($detail) ? $detail : 'Belum ada detail data.')); ?></p>
               </div>

               <div id="VisiTab" class="tabcontent">
                  <h3 style="color: #0f172a;">Visi Kandidat</h3>
                  <hr style="width: 60px; margin-left: 0; border-top: 3px solid #2563eb; margin-bottom: 15px;">
                  <p style="white-space: pre-line; color: #0f172a !important;"><?php echo nl2br(htmlspecialchars($visi)); ?></p>
               </div>

               <div id="MisiTab" class="tabcontent">
                  <h3 style="color: #0f172a;">Misi Kandidat</h3>
                  <hr style="width: 60px; margin-left: 0; border-top: 3px solid #2563eb; margin-bottom: 15px;">
                  <p style="white-space: pre-line; color: #0f172a !important;"><?php echo nl2br(htmlspecialchars($misi)); ?></p>
               </div>

               <div id="ProgramTab" class="tabcontent">
                  <h3 style="color: #0f172a;">Program Kerja</h3>
                  <hr style="width: 60px; margin-left: 0; border-top: 3px solid #2563eb; margin-bottom: 15px;">
                  <p style="white-space: pre-line; color: #0f172a !important;"><?php echo nl2br(htmlspecialchars($program)); ?></p>
               </div>
            </div>

            <!-- TOMBOL NAVIGASI TAB -->
            <div class="tab-container-custom">
               <button class="tablink active" onclick="openTab(event, 'HomeTab')"><i class="fas fa-home mr-1"></i> Home</button>
               <button class="tablink" onclick="openTab(event, 'ProfilTab')"><i class="fas fa-user mr-1"></i> Profil</button>
               <button class="tablink" onclick="openTab(event, 'VisiTab')"><i class="fas fa-eye mr-1"></i> Visi</button>
               <button class="tablink" onclick="openTab(event, 'MisiTab')"><i class="fas fa-bullseye mr-1"></i> Misi</button>
               <button class="tablink" onclick="openTab(event, 'ProgramTab')"><i class="fas fa-tasks mr-1"></i> Program</button>
            </div>

            <!-- TOMBOL AKSI KEMBALI & BERI SUARA -->
            <div class="action-btn-group">
               <button onclick="window.history.go(-1)" class="btn btn-secondary font-weight-bold px-4" style="background: #475569; border: none;"><i class="fas fa-arrow-left mr-1"></i> Kembali</button>
               <a href="./submit.php?id=<?php echo $id; ?>&s=<?php echo $suara; ?>" class="btn btn-primary font-weight-bold px-4 ml-auto" style="background: #2563eb; border: none;"><i class="fas fa-check-circle mr-1"></i> Beri Suara</a>
            </div>
         </div>
      </div>
   </div>

   <script>
      function openTab(evt, cityName) {
         var i, tabcontent, tablinks;
         
         tabcontent = document.getElementsByClassName("tabcontent");
         for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].classList.remove("active-tab");
         }
         
         tablinks = document.getElementsByClassName("tablink");
         for (i = 0; i < tablinks.length; i++) {
            tablinks[i].classList.remove("active");
         }
         
         document.getElementById(cityName).classList.add("active-tab");
         evt.currentTarget.classList.add("active");
      }
   </script>

   <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>

</html>