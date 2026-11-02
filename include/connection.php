<?php
$host = getenv('DB_HOST');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');
$port = getenv('DB_PORT') ?: 4000;

$db   = getenv('DB_NAME') ?: 'pemilosdb';

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
    
    // Konfigurasi PDO dengan SSL untuk TiDB Cloud di Render
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-certificates.crt',
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
    ];
    
    // Fallback untuk XAMPP lokal
    if (empty($host)) {
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