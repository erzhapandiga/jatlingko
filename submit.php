<?php
session_start();
if(!isset($_SESSION['siswa']) || !isset($_GET['id'])) {
   header('location:./');
   exit;
}

define('BASEPATH', dirname(__FILE__));

require('./include/connection.php');

// Penyelarasan variabel koneksi PDO
if (!isset($con)) {
    if (isset($pdo)) { $con = $pdo; }
    elseif (isset($conn)) { $con = $conn; }
    elseif (isset($koneksi)) { $con = $koneksi; }
}

$thn     = date('Y');
$dpn     = date('Y') + 1;
$periode = $thn.'/'.$dpn;
$suara   = intval($_GET['s']) + 1;
$id_kandidat = intval($_GET['id']);
$nis_pemilih = $_SESSION['siswa'];

try {
    // Mulai transaksi database agar aman dan konsisten
    $con->beginTransaction();

    // 1. UPDATE SUARA KANDIDAT MENGGUNAKAN PDO
    $update = $con->prepare("UPDATE t_kandidat SET suara = ? WHERE id_kandidat = ?");
    $update->execute([$suara, $id_kandidat]);

    // 2. SIMPAN DATA KE TABEL PEMILIH MENGGUNAKAN PDO
    $save = $con->prepare("INSERT INTO t_pemilih(nis, periode) VALUES(?, ?)");
    $save->execute([$nis_pemilih, $periode]);

    // 3. --- FITUR LACAK PILIHAN ---
    // Update status siswa jadi 'Sudah Memilih' dan simpan ID Kandidat yang dia coblos
    $update_user = $con->prepare("UPDATE t_user SET pemilih = 'Sudah Memilih', pilihan_paslon = ? WHERE id_user = ?");
    $update_user->execute([$id_kandidat, $nis_pemilih]);

    // Commit transaksi jika semua query berhasil
    $con->commit();

} catch (Exception $e) {
    // Batalkan transaksi jika terjadi kesalahan
    if ($con->inTransaction()) {
        $con->rollBack();
    }
    // Debugging opsional jika ada kendala
    die("Error Database: " . $e->getMessage());
}

// Hapus sesi siswa setelah memilih
unset($_SESSION['siswa']);

header('location:./index.php?page=thanks');
exit;
?>