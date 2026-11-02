<?php
if (!isset($_SESSION['id_admin'])) {
    header('location: ../');
    exit;
}

// Penyelarasan variabel koneksi PDO
if (!isset($con)) {
    if (isset($pdo)) { $con = $pdo; }
    elseif (isset($conn)) { $con = $conn; }
    elseif (isset($koneksi)) { $con = $koneksi; }
}

$id = isset($_GET['id']) ? $_GET['id'] : '';

// Proses Update Data Siswa
if (isset($_POST['update_user'])) {
    $nisn  = trim($_POST['nisn'] ?? '');
    $nama  = trim($_POST['nama'] ?? '');
    $kelas = trim($_POST['kelas'] ?? '');
    $jk    = trim($_POST['jk'] ?? '');

    // Perbaikan: Menggunakan skema pemilosdb.t_user pada kueri UPDATE
    $stmt = $con->prepare("UPDATE pemilosdb.t_user SET nisn = ?, nama = ?, kelas = ?, jk = ? WHERE id_user = ?");
    $stmt->execute([$nisn, $nama, $kelas, $jk, $id]);

    header('location: ?page=user');
    exit;
}

// Ambil data siswa berdasarkan id_user menggunakan skema pemilosdb
$stmt = $con->prepare("SELECT * FROM pemilosdb.t_user WHERE id_user = ?");
$stmt->execute([$id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    echo '<div class="alert alert-danger m-4">Data siswa tidak ditemukan.</div>';
    exit;
}
?>

<h3 class="pt-5 pl-5 pb-3">Edit Data Siswa</h3>
<hr class="ml-5 mr-5" />
<div class="row">
    <div class="col-md-8 col-sm-12">
        <form action="" method="post" class="form-horizontal ml-4">
        <table class="table table-borderless">
            <tr>
                <td> NIS (ID) </td>
                <td> <input class="form-control" type="text" value="<?php echo htmlspecialchars($row['id_user']); ?>" disabled /> </td>
            </tr>
            <tr>
                <td> NISN </td>
                <td> <input class="form-control" type="number" name="nisn" value="<?php echo htmlspecialchars($row['nisn']); ?>" required autocomplete='off'/> </td>
            </tr>
            <tr>
                <td> Nama Siswa </td>
                <td> <input class="form-control" name="nama" type="text" value="<?php echo htmlspecialchars($row['nama']); ?>" required autocomplete='off'/> </td>
            </tr>
            <tr>
                <td> Jenis Kelamin </td>
                <td> 
                    <label class="radio-inline mr-3">
                        <input type="radio" name="jk" value="L" <?php echo ($row['jk'] == 'L') ? 'checked' : ''; ?>> Laki - laki
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="jk" value="P" <?php echo ($row['jk'] == 'P') ? 'checked' : ''; ?>> Perempuan
                    </label> 
                </td>
            </tr>
            <tr>
                <td> Kelas </td>
                <td> <input class="form-control" type="text" name="kelas" value="<?php echo htmlspecialchars($row['kelas']); ?>" required autocomplete='off'/> </td>
            </tr>
        </table>
        <div class="form-group ml-3">
            <div class="col-md-8 col-md-offset-2">
                <button type="submit" name="update_user" class="btn btn-success">Simpan Perubahan</button>
                <button type="button" onclick="window.history.back()" class="btn btn-danger">Kembali</button>
            </div>
        </div>
        </form>
    </div>
</div>