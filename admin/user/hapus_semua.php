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

// Perintah SQL untuk menghapus seluruh data di tabel t_user menggunakan PDO
try {
    $sql = $con->exec("DELETE FROM t_user");
    
    if ($sql !== false) {
          echo '<script>alert("Semua data siswa berhasil dihapus!");window.location="dashboard.php?page=user";</script>';
    } else {
          echo '<script>alert("Gagal menghapus data siswa!");window.history.go(-1);</script>';
    }
} catch (PDOException $e) {
    echo '<script>alert("Gagal menghapus data siswa!");window.history.go(-1);</script>';
}
?>