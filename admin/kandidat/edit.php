<?php
if (!isset($_SESSION['id_admin']) || !isset($_GET['id'])) {
    header('location:../');
    exit;
}

// Penyelarasan variabel koneksi PDO
if (!isset($con)) {
    if (isset($pdo)) { $con = $pdo; }
    elseif (isset($conn)) { $con = $conn; }
    elseif (isset($koneksi)) { $con = $koneksi; }
}

$id = trim($_GET['id']);

/* Menggunakan PDO Prepared Statement */
$stmt = $con->prepare("SELECT id_kandidat, nama_calon, foto, visi, misi, suara, periode, program, no_k FROM t_kandidat WHERE id_kandidat = ?");
$stmt->execute([$id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    echo '<script>alert("Data kandidat tidak ditemukan!"); window.location="dashboard.php?page=kandidat";</script>';
    exit;
}

$id_kandidat = $row['id_kandidat'];
$nama        = $row['nama_calon'];
$foto        = $row['foto'];
$visi        = $row['visi'] ?? '';
$misi        = $row['misi'] ?? '';
$suara       = $row['suara'];
$periode     = $row['periode'];
$program     = $row['program'] ?? '';
$no_k        = $row['no_k'];
?>

<div class="container-fluid px-4 py-4">
    <h3 class="font-weight-bold mb-1" style="color: #0f172a;">Edit Data Kandidat</h3>
    <p class="text-muted mb-4">Silakan perbarui informasi kandidat di bawah ini.</p>
    <hr class="mb-4" />

    <div class="row">
        <!-- KOLOM KIRI: PREVIEW FOTO KANDIDAT -->
        <div class="col-lg-4 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm rounded-lg p-3 text-center bg-white h-100">
                <label class="font-weight-bold text-muted mb-3 text-uppercase" style="font-size: 12px; letter-spacing: 0.5px;">Foto Saat Ini</label>
                <div class="d-flex justify-content-center align-items-center mb-3">
                    <img src="../assets/img/kandidat/<?php echo htmlspecialchars($foto); ?>" class="img-fluid rounded-lg shadow-sm" style="max-height: 320px; object-fit: cover; width: 100%;" alt="Foto Kandidat"/>
                </div>
                <span class="badge badge-primary py-2 px-3 font-weight-bold rounded-pill">Kandidat No. <?php echo htmlspecialchars($no_k); ?></span>
            </div>
        </div>

        <!-- KOLOM KANAN: FORM INPUT & EDIT -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-lg p-4 bg-white">
                <form action="./kandidat/update.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id_kandidat); ?>" />
                    <input type="hidden" name="f" value="<?php echo htmlspecialchars($foto); ?>" />

                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label font-weight-bold text-dark">No Kandidat</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" name="no_k" required value="<?php echo htmlspecialchars($no_k); ?>"/>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label font-weight-bold text-dark">Nama Kandidat</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="nama" required autocomplete="off" value="<?php echo htmlspecialchars($nama); ?>"/>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label font-weight-bold text-dark">Foto Kandidat</label>
                        <div class="col-sm-9">
                            <input type="file" class="form-control-file pt-1" name="foto"/>
                            <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengganti foto.</small>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label font-weight-bold text-dark">Detail Data Calon</label>
                        <div class="col-sm-9">
                            <textarea name="detail_data" class="form-control" rows="3" placeholder="Masukkan detail data calon (Tempat/Tanggal Lahir, Kelas, dll)"></textarea>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label font-weight-bold text-dark">Visi</label>
                        <div class="col-sm-9">
                            <textarea name="visi" class="form-control" rows="3" required><?php echo htmlspecialchars(trim($visi)); ?></textarea>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label font-weight-bold text-dark">Misi</label>
                        <div class="col-sm-9">
                            <textarea name="misi" class="form-control" rows="3" required><?php echo htmlspecialchars(trim($misi)); ?></textarea>
                        </div>
                    </div>

                    <div class="form-group row mb-4">
                        <label class="col-sm-3 col-form-label font-weight-bold text-dark">Program</label>
                        <div class="col-sm-9">
                            <textarea name="program" class="form-control" rows="3" required><?php echo htmlspecialchars(trim($program)); ?></textarea>
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit" name="update" value="Update Kandidat" class="btn btn-success font-weight-bold px-4 py-2 mr-2">
                                <i class="fas fa-save mr-1"></i> Update Kandidat
                            </button>
                            <button type="button" onclick="window.history.go(-1)" class="btn btn-danger font-weight-bold px-4 py-2">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>