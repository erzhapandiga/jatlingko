<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Perbaikan jalur koneksi menggunakan __DIR__ agar akurat
require_once __DIR__ . '/../../include/connection.php';

// Penyelarasan variabel koneksi PDO
if (!isset($con)) {
    if (isset($pdo)) { $con = $pdo; }
    elseif (isset($conn)) { $con = $conn; }
    elseif (isset($koneksi)) { $con = $koneksi; }
}

if (!isset($_SESSION['id_admin'])) {
    header('location: ../');
    exit;
}

if (isset($_POST['add_kelas'])) {
    $id = trim($_POST['id_kelas'] ?? '');
    $kelas = trim($_POST['kelas'] ?? '');

    if ($id === '' || $kelas === '') {
        echo '<script type="text/javascript">alert("Semua form harus terisi");window.history.go(-1);</script>';
        exit;
    }

    // Insert menggunakan PDO Prepared Statement
    $sql = $con->prepare("INSERT INTO t_kelas (id_kelas, nama_kelas) VALUES (?, ?)");
    $sql->execute([$id, $kelas]);

    header('location:?page=kelas&pesan=berhasil');
    exit;
}

// Auto-generate ID Kelas menggunakan PDO
$stmt = $con->query("SELECT id_kelas FROM t_kelas ORDER BY id_kelas DESC LIMIT 1");
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    $get = (intval(substr($row['id_kelas'], 1))) + 1;
    $id_kelas = "K" . sprintf("%02d", $get);
} else {
    $id_kelas = 'K01';
}
?>
<h3 class="pl-5 pt-5 pb-3">Tambah Kelas</h3>
<hr class="ml-5 mr-5" />
<div class="row">
    <div class="col-md-9">
        <form action="" method="post" class="ml-5">
            <table class="table table-borderless">
                <tr>
                    <td> ID Kelas </td>
                    <td> <input class="form-control" type="text" name="id_kelas" readonly value="<?php echo htmlspecialchars($id_kelas); ?>" /> </td>
                </tr>
                <tr>
                    <td> Nama Kelas </td>
                    <td> <input class="form-control" name="kelas" type="text" placeholder="Nama Kelas" autocomplete="off" required /> </td>
                </tr>
            </table>

            <div class="form-group ml-4">
                <button type="submit" name="add_kelas" value="Tambah Kelas" class="btn btn-success">
                    Tambah Kelas
                </button>
                <button type="button" onclick="window.history.go(-1)" class="btn btn-danger">
                    Kembali
                </button>
            </div>

        </form>
    </div>
</div>