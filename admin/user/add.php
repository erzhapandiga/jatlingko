<?php
if(!isset($_SESSION['id_admin'])) {
   header('location: ../');
   exit;
}

// Penyelarasan variabel koneksi PDO
if (!isset($con)) {
    if (isset($pdo)) { $con = $pdo; }
    elseif (isset($conn)) { $con = $conn; }
    elseif (isset($koneksi)) { $con = $koneksi; }
}

if(isset($_POST['add_user'])) {
   $nis  = trim($_POST['nis'] ?? '');
   $nisn = trim($_POST['nisn'] ?? '');
   $nama = trim($_POST['nama'] ?? '');
   $jk   = trim($_POST['jk'] ?? '');
   $kls  = trim($_POST['kelas'] ?? '');

   // Cek apakah NIS sudah terdaftar dengan skema pemilosdb
   $get_id = $con->prepare("SELECT COUNT(*) FROM pemilosdb.t_user WHERE id_user = ?");
   $get_id->execute([$nis]);
   $numb = $get_id->fetchColumn();

   // Validasi Form Kosong
   if($nis == '' || $nisn == '' || $nama == '' || $jk == '' || $kls == '') {
      echo '<script type="text/javascript">alert("Semua form harus terisi");</script>';
   } else if(!preg_match("/^[a-zA-z \'.]*$/",$nama)) {
      echo '<script type="text/javascript">alert("Nama hanya boleh mengandung huruf, titik(.), petik tunggal");</script>';
   } else if($numb > 0){
      echo '<script type="text/javascript">alert("NIS tidak dapat digunakan");window.history.go(-1);</script>';
   } else {
      // Perbaikan: Menggunakan id_user, nisn, nama, kelas, jk (tanpa kolom nis)
      $sql = $con->prepare("INSERT INTO pemilosdb.t_user(id_user, nisn, nama, kelas, jk) VALUES(?, ?, ?, ?, ?)");
      $sql->execute([$nis, $nisn, $nama, $kls, $jk]);

      header('location: ?page=user');
      exit;
   }
}
?>
<!-- Bagian HTML form tetap sama -->