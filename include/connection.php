<?php
$host = 'gateway01.ap-northeast-1.prod.aws.tidbcloud.com';
$port = '4000';
$db   = 'db_pemilos';
$user = '33GoGFSrwK3DNnF.root';
$pass = 'Ju4HkZjKlP8Zpq8V';

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass);
    echo "Koneksi ke TiDB Cloud berhasil! 🚀";
} catch (\PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
}
?>