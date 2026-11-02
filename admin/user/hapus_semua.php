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

try {
    // Matikan foreign key check sementara jika ada relasi tabel, lalu hapus data
    $con->exec("SET FOREIGN_KEY_CHECKS = 0");
    $affected_rows = $con->exec("DELETE FROM pemilosdb.t_user");
    $con->exec("SET FOREIGN_KEY_CHECKS = 1");

    // Jika perintah sukses dijalankan
    echo '<script>alert("Semua data siswa berhasil dihapus! (Total terhapus: ' . $affected_rows . ' baris)"); window.location="dashboard.php?page=user";</script>';
    exit;

} catch (PDOException $e) {
    // Aktifkan kembali foreign key jika terjadi error
    $con->exec("SET FOREIGN_KEY_CHECKS = 1");
    
    // Tampilkan pesan error aslinya agar ketahuan kendala database-nya
    $error_msg = addslashes($e->getMessage());
    echo '<script>alert("Gagal menghapus data siswa! Error: ' . $error_msg . '"); window.history.go(-1);</script>';
    exit;
}
?>