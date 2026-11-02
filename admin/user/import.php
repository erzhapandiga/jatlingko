<?php
// Matiin warning agar proses import tetap bersih
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE & ~E_DEPRECATED);

// Hubungkan dengan file koneksi pusat
require_once '../../include/connection.php';
require_once '../vendor/autoload.php'; // Sesuaikan path vendor jika diperlukan

// Penyelarasan variabel koneksi PDO
if (!isset($con)) {
    if (isset($pdo)) { $con = $pdo; }
    elseif (isset($conn)) { $con = $conn; }
    elseif (isset($koneksi)) { $con = $koneksi; }
}

use PhpOffice\PhpSpreadsheet\IOFactory;

$berhasil = 0;

// Periksa apakah file diupload menggunakan input name 'berkas_excel' (atau 'filepegawai' jika mengikuti form lama)
$input_file_name = isset($_FILES['berkas_excel']) ? 'berkas_excel' : 'filepegawai';

if (isset($_FILES[$input_file_name]['name']) && !empty($_FILES[$input_file_name]['name'])) {
    $target_file = basename($_FILES[$input_file_name]['name']);
    
    if (move_uploaded_file($_FILES[$input_file_name]['tmp_name'], $target_file)) {
        try {
            // Load file Excel menggunakan PhpSpreadsheet
            $spreadsheet = IOFactory::load($target_file);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            $con->beginTransaction();
            
            // Perbaikan: Menggunakan skema pemilosdb.t_user dengan kolom lengkap (termasuk password)
            $stmt = $con->prepare("INSERT INTO pemilosdb.t_user (nis, id_user, nisn, nama, kelas, jk, password) VALUES (?, ?, ?, ?, ?, ?, ?) 
                                   ON DUPLICATE KEY UPDATE nisn = VALUES(nisn), nama = VALUES(nama), kelas = VALUES(kelas), jk = VALUES(jk)");

            // Looping data mulai baris ke-3 (sesuaikan dengan struktur baris Excel Anda)
            for ($i = 3; $i <= count($sheetData); $i++) {
                $nis   = trim($sheetData[$i]['A'] ?? '');
                $nisn  = trim($sheetData[$i]['B'] ?? '');
                $nama  = trim($sheetData[$i]['C'] ?? '');
                $kelas = trim($sheetData[$i]['D'] ?? '');
                $jenis = trim($sheetData[$i]['E'] ?? '');
                $password = md5($nis); // Password default menggunakan md5(nis)

                if ($nis != "" && $nama != "") {
                    $stmt->execute([$nis, $nis, $nisn, $nama, $kelas, $jenis, $password]);
                    $berhasil++;
                }
            }

            $con->commit();
        } catch (Exception $e) {
            if ($con->inTransaction()) {
                $con->rollBack();
            }
        }

        // Hapus file fisik yang di-upload setelah selesai diproses
        if (file_exists($target_file)) {
            unlink($target_file);
        }
    }
}

// Alihkan halaman kembali ke list / dashboard dengan parameter berhasil
header("location:../dashboard.php?page=user&pesan=Berhasil");
exit;
?>