<?php
if (!isset($_SESSION['id_admin']) && !isset($_GET['id'])) {
    header('location: ../');
    exit;
}

// Penyelarasan variabel koneksi PDO
if (!isset($con)) {
    if (isset($pdo)) {
        $con = $pdo;
    } elseif (isset($conn)) {
        $con = $conn;
    } elseif (isset($koneksi)) {
        $con = $koneksi;
    }
}

$id = trim($_GET['id'] ?? '');

// Ambil data kelas menggunakan PDO Prepared Statement
$stmt = $con->prepare("SELECT * FROM t_kelas WHERE id_kelas = ?");
$stmt->execute([$id]);
$row_kelas = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row_kelas) {
    $idk = $row_kelas['id_kelas'];
    $kelas = $row_kelas['nama_kelas'];
?>
    <h3 class="pt-5 pl-5 pb-3">Update Kelas</h3>
    <hr class="ml-5 mr-5" />
    <div class="row">
        <div class="col-md-12">

            <form action="./kelas/update.php" class="ml-4" method="post">
                <table class="table table-borderless">
                    <tr>
                        <td> ID Kelas </td>
                        <td> <input class="form-control" type="text" name="id" readonly value="<?php echo htmlspecialchars($idk); ?>" /> </td>
                    </tr>
                    <tr>
                        <td> Nama Kelas </td>
                        <td> <input class="form-control" name="kelas" type="text" placeholder="Nama Kelas" value="<?php echo htmlspecialchars($kelas); ?>" required /> </td>
                    </tr>
                </table>
                <div class="form-group ml-4">
                    <button type="submit" name="update" value="Update" class="btn btn-success">
                        Update Kelas
                    </button>
                    <button type="button" onclick="window.history.go(-1)" class="btn btn-danger">
                        Kembali
                    </button>
                </div>
            </form>

            <!-- BAGIAN TAMBAHAN: DAFTAR SISWA DENGAN TOMBOL EDIT -->
            <hr class="ml-5 mr-5 mt-5" />
            <h4 class="pl-5 pb-3">Daftar Siswa di Kelas <?php echo htmlspecialchars($kelas); ?></h4>
            <div class="table-responsive ml-4 pr-4 pl-3">
                <table class="table table-bordered table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="25%">NIS (ID User)</th>
                            <th>Nama Siswa</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql_siswa = $con->prepare("SELECT id_user, fullname FROM t_user WHERE id_kelas = ? ORDER BY fullname ASC");
                        $sql_siswa->execute([$id]);
                        $siswa_list = $sql_siswa->fetchAll(PDO::FETCH_ASSOC);

                        if (count($siswa_list) > 0) {
                            $no = 1;
                            foreach ($siswa_list as $siswa) {
                                $nis_siswa = $siswa['id_user'];
                                $nama_siswa = $siswa['fullname'];
                                echo "<tr>
                                    <td class='text-center'>{$no}</td>
                                    <td>" . htmlspecialchars($nis_siswa) . "</td>
                                    <td>" . htmlspecialchars($nama_siswa) . "</td>
                                    <td class='text-center'>
                                        <a href='?page=user&action=edit&id=" . urlencode($nis_siswa) . "' class='btn btn-primary btn-sm'>
                                            <i class='fas fa-edit'></i> Edit
                                        </a>
                                    </td>
                                  </tr>";
                                $no++;
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-center text-muted'><i>Belum ada data siswa yang terdaftar di kelas ini.</i></td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <br><br>
            <!-- END BAGIAN TAMBAHAN -->

        </div>
    </div>

<?php
} else {
    header('location:?page=kelas');
    exit;
}
?>