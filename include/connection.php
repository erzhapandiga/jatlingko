<?php
$host = getenv('DB_HOST');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');
$db   = getenv('DB_NAME');
$port = getenv('DB_PORT') ?: 4000;

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
    
    // Konfigurasi PDO agar kompatibel dengan SSL TiDB Cloud di Docker
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false, // Mengabaikan verifikasi ketat sertifikat
    ];
    
    // Cek apakah variabel environment ada isinya
    if (empty($host)) {
        // Fallback untuk local XAMPP jika sedang dijalankan offline
        $host = '127.0.0.1';
        $user = 'root';
        $pass = '';
        $db   = 'db_pemilos';
        $port = 3306;
        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
        $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
    }

    $con = new PDO($dsn, $user, $pass, $options);

} catch (\PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>