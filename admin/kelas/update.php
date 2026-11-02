<?php
define('BASEPATH', dirname(__FILE__));

session_start();
if(!isset($_SESSION['id_admin'])) {

   header('location:../');
   exit;

}

if (isset($_POST['update'])) {
   // include file koneksi
   include('../../include/connection.php');

   // Penyelarasan variabel koneksi PDO
   if (!isset($con)) {
       if (isset($pdo)) { $con = $pdo; }
       elseif (isset($conn)) { $con = $conn; }
       elseif (isset($koneksi)) { $con = $koneksi; }
   }

   // Tampung data dari form dengan aman
   $id    = trim($_POST['id'] ?? '');
   $kelas = trim($_POST['kelas'] ?? '');

   if ($id === '' || $kelas === '') {

      echo '<script type="text/javascript">alert("Form Harus terisi");window.history.go(-1);</script>';
      exit;

   } else {

      // Update data menggunakan PDO Prepared Statement
      $sql = $con->prepare("UPDATE t_kelas SET nama_kelas = ? WHERE id_kelas = ?");
      $sql->execute([$kelas, $id]);

      header('location:../dashboard.php?page=kelas');
      exit;

   }

} else {

   header('location:../dashboard.php');
   exit;

}
?>