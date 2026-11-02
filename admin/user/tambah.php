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
<h3 class="pt-5 pl-5 pb-3">Tambah Data Siswa</h3>
<hr class="ml-5 mr-5" />
<div class="row">
   <div class="col-md-8 col-sm-12">
      <form action="" method="post" class="form-horizontal ml-4">
         <table class="table table-borderless">
            <tr>
               <th class="pl-5"><label for="nis">NIS</label></th>
               <td><input class="form-control" type="text" name="nis" id="nis" placeholder="NIS" autocomplete="off" required value="<?php echo htmlspecialchars($nis ?? '', ENT_QUOTES, 'UTF-8'); ?>" /></td>
            </tr>
            <tr>
               <th class="pl-5"><label for="nisn">NISN</label></th>
               <td><input class="form-control" type="text" name="nisn" id="nisn" placeholder="NISN" autocomplete="off" required value="<?php echo htmlspecialchars($nisn ?? '', ENT_QUOTES, 'UTF-8'); ?>" /></td>
            </tr>
            <tr>
               <th class="pl-5"><label for="nama">Nama Siswa</label></th>
               <td><input class="form-control" type="text" name="nama" id="nama" placeholder="Nama Siswa" autocomplete="off" required value="<?php echo htmlspecialchars($nama ?? '', ENT_QUOTES, 'UTF-8'); ?>" /></td>
            </tr>
            <tr>
               <th class="pl-5">Jenis Kelamin</th>
               <td>
                  <label class="radio-inline mr-3">
                     <input type="radio" name="jk" value="L" required <?php echo (($jk ?? '') === 'L') ? 'checked' : ''; ?>> Laki-laki
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="jk" value="P" required <?php echo (($jk ?? '') === 'P') ? 'checked' : ''; ?>> Perempuan
                  </label>
               </td>
            </tr>
            <tr>
               <th class="pl-5"><label for="kelas">Kelas</label></th>
               <td><input class="form-control" type="text" name="kelas" id="kelas" placeholder="Contoh: X RPL 1" autocomplete="off" required value="<?php echo htmlspecialchars($kls ?? '', ENT_QUOTES, 'UTF-8'); ?>" /></td>
            </tr>
         </table>
         <div class="form-group ml-4">
            <div class="col-md-8 col-md-offset-2">
               <button type="submit" name="add_user" value="Tambah User" class="btn btn-success">Tambah Siswa</button>
               <button type="button" onclick="window.history.back()" class="btn btn-danger">Kembali</button>
            </div>
         </div>
      </form>
   </div>
</div>