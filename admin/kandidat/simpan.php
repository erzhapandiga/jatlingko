<?php
define("BASEPATH", dirname(__FILE__));
if (!isset($_POST['add_kandidat'])) {
   header('location:../');
   exit;
} else {
   $nama        = trim($_POST['nama'] ?? '');
   $detail_data = trim($_POST['detail_data'] ?? ''); // Tangkap input detail data calon
   $visi        = trim($_POST['visi'] ?? '');
   $misi        = trim($_POST['misi'] ?? '');
   $program     = trim($_POST['program'] ?? '');
   $no_k        = trim($_POST['no_k'] ?? '');
   $foto        = $_FILES['foto'] ?? null;
   $thn         = date('Y');
   $dpn         = date('Y') + 1;
   $periode     = $thn . '/' . $dpn;

   if ($nama == '' || $detail_data == '' || $visi == '' || $misi == '' || $no_k == '') {
      echo '<script type="text/javascript">alert("Semua form harus terisi");window.history.go(-1);</script>';
      exit;
   } else {
      $ext  = array('image/jpg', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/x-png');
      $tipe = $_FILES['foto']['type'] ?? '';
      $size = $_FILES['foto']['size'] ?? 0;

      if ($foto && is_uploaded_file($foto['tmp_name'])) { // cek apakah ada file yang diupload
         if (!in_array($tipe, $ext)) { // cek ekstensi file
            echo '<script type="text/javascript">alert("Format gambar tidak diperbolehkan!");window.history.go(-1);</script>';
            exit;
         } else if ($size > 2097152) {
            echo '<script type="text/javascript">alert("Ukuran gambar terlalu besar!");window.history.go(-1);</script>';
            exit;
         } else {
            $extractFile = pathinfo($foto['name']);
            $dir         = "../../assets/img/kandidat/";
            $newName     = microtime(true) . '.' . ($extractFile['extension'] ?? 'jpg');

            // pindahkan file yang diupload ke directory tujuan
            if (move_uploaded_file($foto['tmp_name'], $dir . $newName)) {
               include('../../include/connection.php');

               // Penyelarasan variabel koneksi PDO
               if (!isset($con)) {
                  if (isset($pdo)) {
                     $con = $pdo;
                  } elseif (isset($conn)) {
                     $con = $conn;
                  } elseif (isset($koneksi)) {
                     $con = $koneksi;
                  }
               }

               // Tambahkan data menggunakan PDO Prepared Statement dengan penguncian mutlak ke pemilosdb.t_kandidat
               $sql = $con->prepare("INSERT INTO pemilosdb.t_kandidat(nama_calon, foto, detail_data, visi, misi, periode, program, no_k) VALUES(?, ?, ?, ?, ?, ?, ?, ?)");
               $sql->execute([$nama, $newName, $detail_data, $visi, $misi, $periode, $program, $no_k]);

               header('location:../dashboard.php?page=kandidat&pesan=berhasil');
               exit;
            } else {
               echo '<script type="text/javascript">alert("Foto gagal diupload");window.history.go(-1);</script>';
               exit;
            }
         }
      } else {
         echo '<script type="text/javascript">alert("Tidak ada foto yang dipilih");window.history.go(-1);</script>';
         exit;
      }
   }
}