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
            $stmt = $con->prepare("INSERT INTO t_user (id_user, fullname, id_kelas, jk, pemilih) VALUES (?, ?, ?, ?, 'Y') 
                                   ON DUPLICATE KEY UPDATE fullname = VALUES(fullname), id_kelas = VALUES(id_kelas), jk = VALUES(jk)");

            // Looping data mulai baris ke-3 (sesuaikan dengan struktur baris Excel Anda)
            for ($i = 3; $i <= count($sheetData); $i++) {
                $nis   = trim($sheetData[$i]['A'] ?? '');
                $nama  = trim($sheetData[$i]['B'] ?? '');
                $kelas = trim($sheetData[$i]['C'] ?? '');
                $jenis = trim($sheetData[$i]['D'] ?? '');

                if ($nis != "" && $nama != "" && $kelas != "" && $jenis != "") {
                    $stmt->execute([$nis, $nama, $kelas, $jenis]);
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