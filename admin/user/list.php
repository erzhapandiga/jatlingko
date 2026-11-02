<?php
if (!isset($_SESSION['id_admin'])) {
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

// 1. Tentukan Periode Aktif
$thn = date('Y');
$dpn = date('Y') + 1;
$periode = $thn . '/' . $dpn;

// 2. Logika Pencarian Diperluas (Mendukung Nama, NIS, NISN, Kelas, JK, dan Status Memilih) dengan penguncian pemilosdb
$keyword = '';
$where_clause = "";
$params = [];

if (isset($_GET['cari']) && !empty(trim($_GET['cari']))) {
     $keyword = trim($_GET['cari']);
     $kw_lower = strtolower($keyword);

     // Deteksi jenis kelamin
     $jk_condition = "";
     if ($kw_lower == 'l' || $kw_lower == 'laki') {
          $jk_condition = "OR pemilosdb.t_user.jk = 'L'";
     } elseif ($kw_lower == 'p' || $kw_lower == 'perempuan') {
          $jk_condition = "OR pemilosdb.t_user.jk = 'P'";
     }

     // Deteksi status memilih (sudah / belum)
     $status_condition = "";
     if (strpos($kw_lower, 'sudah') !== false) {
          $status_condition = " OR pemilosdb.t_pemilih.nis IS NOT NULL";
     } elseif (strpos($kw_lower, 'belum') !== false) {
          $status_condition = " OR pemilosdb.t_pemilih.nis IS NULL";
     }

     // Gabungkan kondisi pencarian aman menggunakan parameter binding dengan awalan pemilosdb
     $where_clause = " AND (
          pemilosdb.t_user.fullname LIKE ? 
          OR pemilosdb.t_user.id_user LIKE ? 
          OR pemilosdb.t_user.nisn LIKE ? 
          OR pemilosdb.t_kelas.nama_kelas LIKE ?
          $jk_condition
          $status_condition
     )";

     $like_kw = "%$keyword%";
     $params = [$like_kw, $like_kw, $like_kw, $like_kw];
}
?>

<div class="row align-items-center px-4 pt-4">
     <!-- Judul Daftar Siswa -->
     <div class="col-md-3 col-sm-12 mb-2">
          <h3 class="m-0">Daftar Siswa</h3>
     </div>

     <!-- Search Bar -->
     <div class="col-md-4 col-sm-12 mb-2">
          <form action="" method="GET">
               <input type="hidden" name="page" value="user">
               <div class="input-group">
                    <input type="text" name="cari" class="form-control" placeholder="Cari nama, NIS, NISN, status..." value="<?php echo htmlspecialchars($keyword); ?>" autocomplete="off">
                    <div class="input-group-append">
                         <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                         <?php if (!empty($keyword)) { ?>
                              <a href="?page=user" class="btn btn-secondary"><i class="fas fa-times"></i></a>
                         <?php } ?>
                    </div>
               </div>
          </form>
     </div>

     <!-- Tombol Hapus Semua, Tambah, & Import -->
     <div class="col-md-5 col-sm-12 mb-2 text-right">
          <div class="d-inline-flex align-items-center" style="gap: 5px;">
               <a class="btn btn-danger btn-sm text-white" href="?page=user&action=hapus_semua" onclick="return confirm('PERINGATAN: Yakin ingin MENGHAPUS SEMUA data siswa? Tindakan ini tidak dapat dibatalkan!');" title="Hapus Semua Siswa" style="padding: 7px 10px;">
                    <i class="fas fa-trash-alt"></i> Hapus Semua
               </a>
               <a class="btn btn-primary btn-sm" href="?page=user&action=tambah" style="padding: 7px 10px;">Tambah Siswa</a>
               <a class="btn btn-success btn-sm" href="?page=user&action=import" style="padding: 7px 10px;">Import Siswa</a>
          </div>
     </div>
</div>

<div class="col-md-12 px-4">
     <hr />
</div>

<div class="row">
     <div class="col-md-11 col-sm-12 ml-5 ">
          <?php
          if (isset($_GET['pesan'])) {
               if ($_GET['pesan'] == "Berhasil") {
                    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'> Data Berhasil di tambahkan <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true' style='color:white;'>&times;</span></button></div>";
               } elseif ($_GET['pesan'] == "") {
                    echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'> Data Gagal di tambahkan <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true' style='color:white;'>&times;</span></button></div>";
               }
          }
          ?>
          <div class="table-responsive mb-5">
               <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                         <tr class="bg-primary">
                              <th style="text-align:center;" class="text-white">NO</th>
                              <th style="text-align:center;" class="text-white">Nama Siswa & NIS/NISN</th>
                              <th style="text-align:center" class="text-white">Kelas</th>
                              <th width="130px" class="text-white text-center">Jenis Kelamin</th>
                              <th width="140px" style="text-align:center;" class="text-white">Status Memilih</th>
                              <th width="130px" style="text-align:center;" class="text-white">Opsi</th>
                         </tr>
                    </thead>
                    <tbody>
                         <?php
                         $no = 1;

                         // Query utama menggunakan PDO Prepared Statement dengan penguncian mutlak ke pemilosdb
                         $query = "SELECT pemilosdb.t_user.id_user, pemilosdb.t_user.fullname, pemilosdb.t_user.nisn, pemilosdb.t_user.jk, pemilosdb.t_kelas.nama_kelas, pemilosdb.t_pemilih.nis AS status_vote 
                              FROM pemilosdb.t_user 
                              JOIN pemilosdb.t_kelas ON pemilosdb.t_user.id_kelas = pemilosdb.t_kelas.id_kelas 
                              LEFT JOIN pemilosdb.t_pemilih ON pemilosdb.t_user.id_user = pemilosdb.t_pemilih.nis 
                              AND pemilosdb.t_pemilih.periode = ?
                              WHERE 1=1 $where_clause";

                         // Gabungkan parameter periode dengan parameter keyword jika ada pencarian
                         $exec_params = array_merge([$periode], $params);

                         $stmt = $con->prepare($query);
                         $stmt->execute($exec_params);
                         $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                         if (count($rows) > 0) {
                              foreach ($rows as $data) {
                                   if ($data['status_vote'] != NULL) {
                                        $status_label = '<span class="badge badge-success" style="padding: 8px 12px;">Sudah Memilih</span>';
                                   } else {
                                        $status_label = '<span class="badge badge-danger" style="padding: 8px 12px;">Belum Memilih</span>';
                                   }

                                   $nisn_val = (!empty($data['nisn']) && $data['nisn'] != '-') ? $data['nisn'] : '-';
                         ?>
                                   <tr>
                                        <td style="text-align:center;vertical-align:middle;">
                                             <?php echo $no++; ?>
                                        </td>
                                        <td style="padding-left:20px;vertical-align:middle;">
                                             <span class="text-dark font-weight-bold" style="font-size: 15px;"><?php echo htmlspecialchars($data['fullname']); ?></span><br>
                                             <small class="text-muted">NIS: <strong><?php echo htmlspecialchars($data['id_user']); ?></strong> | NISN: <strong><?php echo htmlspecialchars($nisn_val); ?></strong></small>
                                        </td>
                                        <td style="text-align:center;vertical-align:middle;">
                                             <?php echo htmlspecialchars($data['nama_kelas']); ?>
                                        </td>
                                        <td style="text-align:center;vertical-align:middle;">
                                             <?php echo ($data['jk'] == 'L') ? 'Laki - laki' : 'Perempuan'; ?>
                                        </td>
                                        <td style="text-align:center;vertical-align:middle;">
                                             <?php echo $status_label; ?>
                                        </td>
                                        <td style="text-align:center;vertical-align:middle;">
                                             <div class="d-flex justify-content-center align-items-center" style="gap: 5px;">
                                                  <a href="?page=user&action=edit&id=<?php echo $data['id_user']; ?>" class="btn btn-warning btn-sm text-white" title="Edit">
                                                       <i class="fas fa-edit"></i> Edit
                                                  </a>
                                                  <a href="?page=user&action=hapus&id=<?php echo $data['id_user']; ?>" onclick="return confirm('Yakin ingin menghapus user ini ?');" class="btn btn-danger btn-sm" title="Hapus">
                                                       <i class="fas fa-trash"></i> Hapus
                                                  </a>
                                             </div>
                                        </td>
                                   </tr>
                         <?php
                              }
                         } else {
                              echo '<tr><td colspan="6" class="text-center py-4 text-muted">Data siswa tidak ditemukan.</td></tr>';
                         }
                         ?>
                    </tbody>
               </table>
          </div>
     </div>
</div>