<?php
$host = getenv('DB_HOST') ?: 'gateway01.ap-northeast-1.prod.aws.tidbcloud.com';
$port = getenv('DB_PORT') ?: '4000';
$user = getenv('DB_USER') ?: '33GoGFSrwK3DNNf.root';
$pass = getenv('DB_PASS') ?: 'Ju4HkZjKlP8Zpq8V';
$db   = getenv('DB_NAME') ?: 'pilketos';

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Koneksi Gagal: " . $e->getMessage());
}
?>