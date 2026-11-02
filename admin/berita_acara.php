<?php
session_start();
if (!isset($_SESSION['id_admin'])) {
    header('location: ../');
    exit;
}

require_once '../include/connection.php';

// Penyelarasan variabel koneksi PDO
if (!isset($con)) {
    if (isset($pdo)) { $con = $pdo; }
    elseif (isset($conn)) { $con = $conn; }
    elseif (isset($koneksi)) { $con = $koneksi; }
}

$thn = date('Y');
$dpn = date('Y') + 1;
$periode = $thn.'/'.$dpn;

$bulan = array (1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
$tanggal_sekarang = date('d') . ' ' . $bulan[(int)date('m')] . ' ' . date('Y');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Acara Pemilihan OSIS</title>
    <style>
        /* Pengaturan Pasti Ukuran Kertas HVS A4 dengan Margin Profesional */
        @page { 
            size: A4; 
            margin: 1.5cm 2cm 1.5cm 2cm; 
        }
        body { 
            font-family: "Times New Roman", Times, serif; 
            font-size: 11.5pt; 
            line-height: 1.45; 
            color: #000; 
            background: #fff;
            margin: 0;
            padding: 0;
        }
        
        /* Kop Surat Proporsional */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            border: none;
            border-bottom: 3px double #000;
            padding-bottom: 6px;
            margin-bottom: 15px;
        }
        .kop-table td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }
        .logo-td {
            width: 75px;
            text-align: left;
        }
        .logo-sekolah {
            width: 70px;
            height: auto;
            display: block;
        }
        .text-kop {
            text-align: center;
        }
        .text-kop h2 { font-size: 13pt; font-weight: normal; margin: 0; letter-spacing: 0.5px; }
        .text-kop h3 { font-size: 15pt; font-weight: bold; margin: 2px 0; letter-spacing: 0.5px; }
        .text-kop p { font-size: 9pt; margin: 0; }
        
        .judul { text-align: center; margin-bottom: 15px; font-weight: bold; }
        .judul h4 { margin: 0; text-decoration: underline; font-size: 11.5pt; letter-spacing: 0.5px; }
        .judul p { margin: 2px 0 0 0; font-size: 10.5pt; font-weight: normal; }

        .paragraf {
            text-indent: 40px;
            text-align: justify;
            margin-bottom: 10px;
        }

        table.data-table { width: 100%; border-collapse: collapse; margin: 12px 0 15px 0; }
        table.data-table, th, td { border: 1px solid black; }
        th { padding: 6px; text-align: center; font-size: 10.5pt; background-color: #f2f2f2; }
        td { padding: 5px 8px; font-size: 10.5pt; vertical-align: middle; }
        
        .tengah { text-align: center; }
        .kiri { text-align: left; }
        .kanan { text-align: right; padding-right: 12px; }

        .ttd-container { 
            width: 100%; 
            margin-top: 20px; 
            display: flex; 
            justify-content: space-between; 
            page-break-inside: avoid;
        }
        .ttd-box { 
            width: 42%; 
            text-align: center; 
        }
        .ttd-space { height: 55px; }
        .ttd-nama { font-weight: bold; text-decoration: underline; }

        .mengetahui-box {
            width: 100%;
            text-align: center;
            margin-top: 15px;
            page-break-inside: avoid;
        }
    </style>
</head>
<body onload="window.print()">

    <!-- Kop Surat HVS A4 -->
    <table class="kop-table">
        <tr>
            <td class="logo-td">
                <img src="../images/almadani.png" class="logo-sekolah" alt="Logo">
            </td>
            <td class="text-kop">
                <h2>YAYASAN PENDIDIKAN METLAND</h2>
                <h3>SMK METLAND CIBITUNG</h3>
                <p>Jl. Perumahan Metland Cibitung, Wanajaya, Kec. Cibitung, Kabupaten Bekasi, Jawa Barat 17520</p>
            </td>
            <td style="width: 75px;"></td>
        </tr>
    </table>

    <div class="judul">
        <h4>BERITA ACARA</h4>
        <p>NOMOR: 001 /OSIS-SMK.MET/IX/<?php echo date('Y'); ?></p>
        <br>
        Tentang<br>HASIL PEMILIHAN KETUA OSIS<br>PERIODE <?php echo $periode; ?>
    </div>

    <div class="paragraf">
        Pada hari ini, tanggal <?php echo $tanggal_sekarang; ?>, rangkaian proses Pemilihan Ketua Organisasi Siswa Intra Sekolah (OSIS) SMK Metland Cibitung periode <?php echo $periode; ?> telah selesai dilaksanakan dengan lancar. Berikut adalah rincian perolehan suara sah dari masing-masing calon:
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="12%">NO. URUT</th>
                <th width="48%">NAMA PASANGAN CALON</th>
                <th width="20%">JABATAN</th>
                <th width="20%">JUMLAH SUARA</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $total_suara = 0;
            
            // Query menggunakan PDO Prepared Statement
            $stmt_kan = $con->prepare("SELECT * FROM t_kandidat ORDER BY id_kandidat ASC");
            $stmt_kan->execute();
            $kandidat_list = $stmt_kan->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($kandidat_list) > 0) {
                foreach ($kandidat_list as $kan) {
                    $suara = isset($kan['suara']) ? $kan['suara'] : 0;
                    $total_suara += $suara;
                    ?>
                    <tr>
                        <td class="tengah"><?php echo $no++; ?></td>
                        <td class="kiri"><?php echo htmlspecialchars($kan['nama_calon']); ?></td>
                        <td class="tengah">Ketua OSIS</td>
                        <td class="tengah"><?php echo $suara; ?></td>
                    </tr>
                    <?php
                }
            }

            // HITUNG STATISTIK PEMILIH MENGGUNAKAN PDO
            $q_total = $con->query("SELECT COUNT(*) as jml FROM t_user");
            $data_total = $q_total->fetch(PDO::FETCH_ASSOC);
            $total_pemilih = $data_total ? $data_total['jml'] : 0;
            
            $stmt_voted = $con->prepare("SELECT COUNT(t_user.id_user) as jml 
                                       FROM t_user 
                                       JOIN t_pemilih ON t_user.id_user = t_pemilih.nis 
                                       WHERE t_pemilih.periode = ?");
            $stmt_voted->execute([$periode]);
            $data_voted = $stmt_voted->fetch(PDO::FETCH_ASSOC);
            $pemilih_hadir = $data_voted ? $data_voted['jml'] : 0;
            
            $pemilih_absen = max(0, $total_pemilih - $pemilih_hadir);
            ?>
            
            <tr>
                <td colspan="3" class="kanan"><strong>Total Suara Sah</strong></td>
                <td class="tengah"><strong><?php echo $total_suara; ?></strong></td>
            </tr>
            <tr>
                <td colspan="3" class="kanan">Total Keseluruhan Daftar Pemilih</td>
                <td class="tengah"><?php echo $total_pemilih; ?></td>
            </tr>
            <tr>
                <td colspan="3" class="kanan">Pemilih yang Telah Menggunakan Hak Suara</td>
                <td class="tengah"><?php echo $pemilih_hadir; ?></td>
            </tr>
            <tr>
                <td colspan="3" class="kanan">Pemilih yang Tidak Menggunakan Hak Suara (Golput)</td>
                <td class="tengah"><?php echo $pemilih_absen; ?></td>
            </tr>
        </tbody>
    </table>

    <div class="paragraf">
        Demikian berita acara ini disusun secara transparan sesuai dengan hasil perolehan suara di lapangan, untuk dapat dipergunakan sebagaimana mestinya.
    </div>

    <!-- Area Tanda Tangan -->
    <div class="ttd-container">
        <div class="ttd-box">
            Ketua Panitia Pemilihan,<br>
            <div class="ttd-space"></div>
            <span class="ttd-nama">_______________________</span>
        </div>
        <div class="ttd-box">
            Bekasi, <?php echo $tanggal_sekarang; ?><br>
            Pembina OSIS,<br>
            <div class="ttd-space"></div>
            <span class="ttd-nama">_______________________</span>
        </div>
    </div>
    
    <div class="mengetahui-box">
        Mengetahui,<br>
        Kepala SMK Metland Cibitung<br>
        <div class="ttd-space"></div>
        <span class="ttd-nama">Dr. Darmawan Sunarja, Drs., MM. Par.</span>
    </div>

</body>
</html>