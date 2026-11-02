<?php
session_start();
define('BASEPATH', dirname(__FILE__));

if (!isset($_SESSION['id_admin']) || !isset($_POST['update'])) {
   header('location:../');
   exit;
}

include('../../include/connection.php');

// Penyelarasan variabel koneksi PDO
if (!isset($con)) {
    if (isset($pdo)) { $con = $pdo; }
    elseif (isset($conn)) { $con = $conn; }
    elseif (isset($koneksi)) { $con = $koneksi; }
}

$id      = trim($_POST['id'] ?? '');
$nama    = trim($_POST['nama'] ?? '');
$foto    = $_FILES['foto'] ?? null;
$visi    = trim($_POST['visi'] ?? '');
$misi    = trim($_POST['misi'] ?? '');
$program = trim($_POST['program'] ?? '');
$no_k    = trim($_POST['no_k'] ?? '');
$detail  = trim($_POST['detail_data'] ?? ''); // Menangkap input detail data calon
$f       = trim($_POST['f'] ?? '');

if ($nama == '' || $visi == '' || $misi == '' || $program == '' || $detail == '' || $no_k == '') {

   echo '<script type="text/javascript">alert("Semua form harus terisi");window.history.go(-1);</script>';
   exit;

} else {

   if ($foto && is_uploaded_file($foto['tmp_name'])) {

      $ext  = array('image/jpg','image/jpeg','image/pjpeg','image/png','image/x-png');
      $tipe = $foto['type'];
      $size = $foto['size'];

      if (!in_array($tipe, $ext)){ // cek ekstensi file

         echo '<script type="text/javascript">alert("Format gambar tidak diperbolehkan!");window.history.go(-1);</script>';
         exit;

      } else if($size > 2097152) {

         echo '<script type="text/javascript">alert("Ukuran gambar terlalu besar!");window.history.go(-1);</script>';
         exit;

      } else {

         $extractFile = pathinfo($foto['name']);
         $dir         = "../../assets/img/kandidat/";
         $newName     = microtime(true) . '.' . ($extractFile['extension'] ?? 'jpg');

         // pindahkan file yang di-upload ke directory tujuan
         if (move_uploaded_file($foto['tmp_name'], $dir.$newName)) {

            // Hapus foto lama jika ada
            if (!empty($f) && file_exists('../../assets/img/kandidat/'.$f)) {
                unlink('../../assets/img/kandidat/'.$f);
            }

            // Query update dengan foto baru menggunakan PDO Prepared Statement
            $sql = $con->prepare("UPDATE t_kandidat SET nama_calon = ?, foto = ?, visi = ?, misi = ?, program = ?, no_k = ?, detail_data = ? WHERE id_kandidat = ?");
            $sql->execute([$nama, $newName, $visi, $misi, $program, $no_k, $detail, $id]);

            header('location:../dashboard.php?page=kandidat');
            exit;

         } else {

            echo '<script type="text/javascript">alert("Foto gagal diupload");window.history.go(-1);</script>';
            exit;

         }

      }

   } else {

      // Query update saat foto tidak diganti menggunakan PDO Prepared Statement
      $sql = $con->prepare("UPDATE t_kandidat SET nama_calon = ?, visi = ?, misi = ?, program = ?, no_k = ?, detail_data = ? WHERE id_kandidat = ?");
      $sql->execute([$nama, $visi, $misi, $program, $no_k, $detail, $id]);

      header('location:../dashboard.php?page=kandidat');
      exit;

   }
}
?>