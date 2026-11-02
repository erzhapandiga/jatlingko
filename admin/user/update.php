<?php
if (!isset($_POST['update'])) {

   header('location: ../');
   exit;

} else {
   define('BASEPATH', dirname(__FILE__));

   include('../../include/connection.php');

   // Penyelarasan variabel koneksi PDO
   if (!isset($con)) {
       if (isset($pdo)) { $con = $pdo; }
       elseif (isset($conn)) { $con = $conn; }
       elseif (isset($koneksi)) { $con = $koneksi; }
   }

   $nis  = trim($_POST['nis'] ?? '');
   $nama = trim($_POST['nama'] ?? '');
   $jk   = trim($_POST['jk'] ?? '');
   $kls  = trim($_POST['kelas'] ?? '');
   $pil  = trim($_POST['pemilih'] ?? '');

   if($nis == '' || $nama == '' || $jk == '' || $kls == '') {

      echo '<script type="text/javascript">alert("Semua form harus terisi");window.history.go(-1);</script>';
      exit;

   } else if(!preg_match("/^[a-zA-Z \'.]*$/",$nama)) {

      echo '<script type="text/javascript">alert("Nama hanya boleh mengandung huruf, titik(.), petik tunggal");window.history.go(-1);</script>';
      exit;

   } else {

      // Update data menggunakan PDO Prepared Statement
      $sql = $con->prepare("UPDATE t_user SET fullname = ?, id_kelas = ?, jk = ?, pemilih = ? WHERE id_user = ?");
      $sql->execute([$nama, $kls, $jk, $pil, $nis]);

      header('location:../dashboard.php?page=user');
      exit;

   }

}
?>