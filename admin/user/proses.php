<?php
// Matiin warning agar proses import tetap bersih
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE & ~E_DEPRECATED);

require_once '../include/connection.php';
require_once 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

// Penyelarasan variabel koneksi PDO
if (!isset($con)) {
    if (isset($pdo)) { $con = $pdo; }
    elseif (isset($conn)) { $con = $conn; }
    elseif (isset($koneksi)) { $con = $koneksi; }
}

$file_mimes = array(
    'application/octet-stream', 
    'application/vnd.ms-excel', 
    'application/x-csv', 
    'text/x-csv', 
    'text/csv', 
    'application/csv', 
    'application/excel', 
    'application/vnd.msexcel', 
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
);

if (isset($_FILES['berkas_excel']['name']) && in_array($_FILES['berkas_excel']['type'], $file_mimes)) {

    $arr_file = explode('.', $_FILES['berkas_excel']['name']);
    $extension = strtolower(end($arr_file));

    if ('csv' == $extension) {
        $reader = new Csv();
    } else {
        $reader = new Xlsx();
    }

    $reader->setReadDataOnly(true);
    $spreadsheet = $reader->load($_FILES['berkas_excel']['tmp_name']);
    $sheetData = $spreadsheet->getActiveSheet()->toArray();

    try {
        $con->beginTransaction();

        // KOSONGKAN TABEL LAMA DENGAN MENONAKTIFKAN FOREIGN KEY CHECKS (PDO)
        $con->exec("SET FOREIGN_KEY_CHECKS = 0");
        $con->exec("TRUNCATE TABLE t_user");
        $con->exec("TRUNCATE TABLE t_kelas");
        $con->exec("SET FOREIGN_KEY_CHECKS = 1");

        $kelas_map = [];
        $k_index = 1;
        $seen_nis = [];
        $guru_counter = 1;

        // Prepared Statements untuk Insert Data
        $stmt_ins_kelas = $con->prepare("INSERT INTO t_kelas (id_kelas, nama_kelas) VALUES (?, ?)");
        $stmt_ins_user  = $con->prepare("INSERT INTO t_user (id_user, fullname, id_kelas, jk, pemilih) VALUES (?, ?, ?, ?, ?)");

        // LOOPING MULAI DARI INDEX 5 (Baris ke-6 di Excel)
        for ($i = 5; $i < count($sheetData); $i++) {
            $nama_asli = isset($sheetData[$i][2]) ? trim($sheetData[$i][2]) : '';
            if ($nama_asli == '') continue; // Lewati baris jika nama kosong

            $nama       = $nama_asli;
            $jk         = isset($sheetData[$i][3]) ? trim($sheetData[$i][3]) : 'L';
            if (empty($jk)) { $jk = 'L'; }
            
            $kelas_asli = isset($sheetData[$i][4]) ? trim($sheetData[$i][4]) : '';
            $pemilih    = 'Belum Memilih';

            // 1. OLAH KELAS OTOMATIS
            if (!isset($kelas_map[$kelas_asli]) && $kelas_asli != '') {
                $id_kelas_baru = 'k' . str_pad($k_index, 2, '0', STR_PAD_LEFT);
                $kelas_map[$kelas_asli] = $id_kelas_baru;

                // Simpan kelas baru ke database via PDO
                $stmt_ins_kelas->execute([$id_kelas_baru, $kelas_asli]);
                $k_index++;
            }
            $id_kelas = isset($kelas_map[$kelas_asli]) ? $kelas_map[$kelas_asli] : 'k01';

            // 2. OLAH NIS/NIP (FOTO/GURU NIS BILA KOSONG & PENANGANAN DUPLIKAT)
            $nis = isset($sheetData[$i][1]) ? trim($sheetData[$i][1]) : '';
            if ($nis == '' || strtolower($nis) == 'nis/nip') {
                $nis = 'GURU' . str_pad($guru_counter, 3, '0', STR_PAD_LEFT);
                $guru_counter++;
            }

            $original_nis = $nis;
            $suffix = 1;

            // Jika ketemu NIS yang dobel di Excel, tambahkan suffix _1, _2 dst
            while (in_array($nis, $seen_nis)) {
                $nis = $original_nis . '_' . $suffix;
                $suffix++;
            }
            $seen_nis[] = $nis;

            // 3. MASUKKAN DATA SISWA/GURU VIA PDO
            $stmt_ins_user->execute([$nis, $nama, $id_kelas, $jk, $pemilih]);
        }

        $con->commit();
        header("Location: ../dashboard.php?page=user&pesan=Berhasil");
        exit;

    } catch (Exception $e) {
        if ($con->inTransaction()) {
            $con->rollBack();
        }
        header("Location: ../dashboard.php?page=user&pesan=Gagal");
        exit;
    }
} else {
    header("Location: ../dashboard.php?page=user&pesan=Gagal");
    exit;
}
?>