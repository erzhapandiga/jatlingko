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

   // Cek apakah NIS sudah terdaftar menggunakan PDO
   $get_id = $con->prepare("SELECT COUNT(*) FROM t_user WHERE id_user = ?");
   $get_id->execute([$nis]);
   $numb = $get_id->fetchColumn();

   // Validasi Form Kosong (termasuk nisn)
   if($nis == '' || $nisn == '' || $nama == '' || $jk == '' || $kls == '') {
      echo '<script type="text/javascript">alert("Semua form harus terisi");</script>';
   } else if(!preg_match("/^[a-zA-z \'.]*$/",$nama)) {
      echo '<script type="text/javascript">alert("Nama hanya boleh mengandung huruf, titik(.), petik tunggal");</script>';
   } else if($numb > 0){
      echo '<script type="text/javascript">alert("NIS tidak dapat digunakan");window.history.go(-1);</script>';
   } else {
      // Query Insert ditambah kolom nisn menggunakan PDO
      $sql = $con->prepare("INSERT INTO t_user(id_user, nisn, fullname, id_kelas, jk) VALUES(?, ?, ?, ?, ?)");
      $sql->execute([$nis, $nisn, $nama, $kls, $jk]);

      header('location: ?page=user');
      exit;
   }
}
?>
<h3 class="pt-5 pl-5 pb-3" >Tambah Data Siswa</h3>
<hr class="ml-5 mr-5" />
<div class="row">
    <div class="col-md-8 col-sm-12">
        <form action="" method="post" class="form-horizontal ml-4">
        <table class="table table-borderless">
            <tr>
                <td> NIS </td>
                <td> <input class="form-control" type="number" name="nis" placeholder="NIS" autofocus autocomplete='off'/> </td>
            </tr>
            <tr>
                <td> NISN </td>
                <td> <input class="form-control" type="number" name="nisn" placeholder="NISN" autocomplete='off'/> </td>
            </tr>
            <tr>
                <td> Nama Siswa </td>
                <td> <input class="form-control" name="nama" type="text" autocomplete='off' placeholder="Nama Siswa"/> </td>
            </tr>
            <tr>
                <td> Jenis Kelamin </td>
                <td> <label class="radio-inline mr-3">
                        <input type="radio" name="jk" value="L" id="L"> Laki - laki
                    </label>

                    <label class="radio-inline">
                        <input type="radio" name="jk" value="P" id="P"> Perempuan
                    </label> </td>
            </tr>
            <tr>
                <td> Kelas </td>
                <td> 
                    <select name="kelas" required class="form-control">
                        <option value="">-- Pilih Kelas --</option>
                        <?php
                            $kelas_stmt = $con->query("SELECT * FROM t_kelas");
                            while ($key = $kelas_stmt->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                                <option value="<?php echo $key['id_kelas']; ?>">
                                    <?php echo htmlspecialchars($key['nama_kelas']); ?>
                                </option>
                        <?php
                            }
                        ?>
                    </select> </td>
            </tr>
            </table>
            <div class="form-group ml-3">
                <div class="col-md-8 col-md-offset-2">
                    <button type="submit" name="add_user" value="Tambah User" class="btn btn-success">Tambah Siswa</button>
                    <button type="button" onclick="window.history.back()" class="btn btn-danger">Kembali</button>
                </div>
            </div>
        </form>
    </div>
</div>