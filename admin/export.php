<?php
session_start();
if (!isset($_SESSION['id_admin'])) {
    header('location: ./');
    exit;
}

require_once '../include/connection.php';

// Penyelarasan variabel koneksi PDO
if (!isset($con)) {
    if (isset($pdo)) { $con = $pdo; }
    elseif (isset($conn)) { $con = $conn; }
    elseif (isset($koneksi)) { $con = $koneksi; }
}

// Tentukan Periode Aktif
$thn = date('Y');
$dpn = date('Y') + 1;
$periode = $thn.'/'.$dpn;

$filename = "Laporan_Data_Pemilih_OSIS_" . date('Y-m-d') . ".xls";

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");
?>
<center>
    <h3>LAPORAN DATA PEMILIH & STATUS PEMILIHAN OSIS</h3>
    <h4>SMK METLAND CIBITUNG &mdash; PERIODE <?php echo $periode; ?></h4>
</center>
<br>
<table border="1">
    <thead>
        <tr style="background-color: #0ea5e9; color: #ffffff; font-weight: bold; text-align: center;">
            <th>No</th>
            <th>NIS / ID User</th>
            <th>NISN</th>
            <th>Nama Lengkap</th>
            <th>Kelas</th>
            <th>Status Memilih</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;

        // Query menggunakan PDO Prepared Statement
        $query = "SELECT t_user.id_user, t_user.fullname, t_user.nisn, t_user.jk, t_kelas.nama_kelas, t_pemilih.nis AS status_vote 
                  FROM t_user 
                  JOIN t_kelas ON t_user.id_kelas = t_kelas.id_kelas 
                  LEFT JOIN t_pemilih ON t_user.id_user = t_pemilih.nis 
                  AND t_pemilih.periode = ?
                  ORDER BY t_user.fullname ASC";

        $stmt = $con->prepare($query);
        $stmt->execute([$periode]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($rows) > 0) {
            foreach ($rows as $data) {
                if ($data['status_vote'] != NULL) {
                    $status_milih = '<span style="color:green; font-weight:bold;">Sudah Memilih</span>';
                } else {
                    $status_milih = '<span style="color:red; font-weight:bold;">Belum Memilih</span>';
                }

                $nisn_val = (!empty($data['nisn']) && $data['nisn'] != '-') ? $data['nisn'] : '-';
                $nama_kelas = !empty($data['nama_kelas']) ? $data['nama_kelas'] : '-';
                ?>
                <tr>
                    <td align="center"><?php echo $no++; ?></td>
                    <td>&nbsp;<?php echo $data['id_user']; ?></td>
                    <td>&nbsp;<?php echo $nisn_val; ?></td>
                    <td><?php echo $data['fullname']; ?></td>
                    <td align="center"><?php echo $nama_kelas; ?></td>
                    <td align="center"><?php echo $status_milih; ?></td>
                </tr>
                <?php
            }
        } else {
            echo '<tr><td colspan="6" align="center">Data tidak ditemukan.</td></tr>';
        }
        ?>
    </tbody>
</table>