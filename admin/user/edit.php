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

$id = $_GET['id'] ?? '';

// Ambil data user menggunakan PDO Prepared Statement
$sql = $con->prepare("SELECT id_user, nisn, fullname, id_kelas, jk, pemilih FROM t_user WHERE id_user = ?");
$sql->execute([$id]);
$row = $sql->fetch(PDO::FETCH_ASSOC);

if ($row) {
    $id_user  = $row['id_user'];
    $nisn     = $row['nisn'];
    $fullname = $row['fullname'];
    $kls      = $row['id_kelas'];
    $jk       = $row['jk'];
    $pemilih  = $row['pemilih'];
} else {
    $id_user = $nisn = $fullname = $kls = $jk = $pemilih = '';
}
?>
<h3 class="pt-5 pl-5 pb-3">Update Data Siswa</h3>
<hr class="ml-5 mr-5" />
<div class="row">
    <div class="col-md-8 col-sm-12">
        <form action="./user/update.php" method="post" class="form-horizontal">
        <table class="table table-borderless">
        <tr>
        <th class="pl-5"> NIS </th>
        <td> <input class="form-control" type="number" name="nis" placeholder="NIS" readonly value="<?php echo htmlspecialchars($id_user); ?>"/> </td>
        </tr>
        <tr>
        <th class="pl-5"> NISN </th>
        <td> <input class="form-control" type="number" name="nisn" placeholder="NISN" autocomplete="off" value="<?php echo htmlspecialchars($nisn); ?>"/> </td>
        </tr>
        <tr>
        <th class="pl-5"> Nama Siswa </th>
        <td> <input class="form-control" name="nama" type="text" placeholder="Nama Siswa" value="<?php echo htmlspecialchars($fullname); ?>"/> </td>
        </tr>
        <tr>
        <th class="pl-5"> Jenis Kelamin  </th>
        <td> 
                    <label class="radio-inline mr-3">
                        <input type="radio" name="jk" value="L" id="L" <?php if($jk == 'L') { echo 'checked'; } ?>> Laki - laki
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="jk" value="P" id="P" <?php if($jk == 'P') { echo 'checked'; } ?>> Perempuan
                    </label> </td>
        </tr>
        <tr>
        <th class="pl-5"> Kelas </th>
        <td> 
                    <select name="kelas" required class="form-control">
                        <option value="#">-- Pilih Kelas --</option>
                        <?php
                            $kelas_stmt = $con->query("SELECT * FROM t_kelas");
                            while ($key = $kelas_stmt->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                                <option value="<?php echo $key['id_kelas']; ?>" <?php if ($kls == $key['id_kelas']) { echo 'selected'; } ?> >
                                    <?php echo htmlspecialchars($key['nama_kelas']); ?>
                                </option>
                        <?php
                            }
                        ?>
                    </select></td>
        </tr>
        <tr>
        <th class="pl-5"> Pemilih </th>
        <td> 
                    <select name="pemilih" required class="form-control">
                        <option value="Y" <?php if($pemilih == 'Y') { echo 'selected';} ?>>Aktif</option>
                        <option value="N" <?php if($pemilih == 'N') { echo 'selected';} ?>>Tidak</option>
                    </select> </td>
        </tr>
                
       </table>
            
            <div class="form-group ml-4">
                <div class="col-md-8 col-md-offset-2">
                    <button type="submit" name="update" value="Update User" class="btn btn-success">Update Siswa</button>
                    <button type="button" onclick="window.history.go(-1)" class="btn btn-danger">Kembali</button>
                </div>
            </div>
      
        </form>
    </div>
</div>