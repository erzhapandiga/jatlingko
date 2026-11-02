<?php
define("BASEPATH", dirname(__FILE__));
session_start();

if(!isset($_SESSION['id_admin'])) {
   header('location:./');
   exit;
}

include('../include/connection.php');

// Penyelarasan variabel koneksi PDO
if (!isset($con)) {
    if (isset($pdo)) { $con = $pdo; }
    elseif (isset($conn)) { $con = $conn; }
    elseif (isset($koneksi)) { $con = $koneksi; }
}

// Jika periode dipilih di dropdown, filter berdasarkan periode. 
// Jika kosong/tidak dipilih, otomatis tampilkan semua data.
if (isset($_POST['periode']) && $_POST['periode'] != '') {
   $periode = $_POST['periode'];
   $get = $con->prepare("SELECT id_kandidat, nama_calon, foto, visi, misi, suara, periode, program, no_k FROM t_kandidat WHERE periode = ?");
   $get->execute([$periode]);
} else {
   $get = $con->prepare("SELECT id_kandidat, nama_calon, foto, visi, misi, suara, periode, program, no_k FROM t_kandidat");
   $get->execute();
}

$kandidat_list = $get->fetchAll(PDO::FETCH_ASSOC);
$htg = count($kandidat_list);

if($htg > 0) {
   ?>
   <div class="row mt-4">
      <?php
      foreach ($kandidat_list as $row) {
         $id = $row['id_kandidat'];
         $nama = $row['nama_calon'];
         $foto = $row['foto'];
         $suara = $row['suara'];
      ?>
      <div class="col-md-4 mb-4">
         <div class="card shadow-sm text-center p-3">
               <img class="kandidat img-fluid rounded mb-3" src="../assets/img/kandidat/<?php echo htmlspecialchars($foto); ?>" alt="<?php echo htmlspecialchars($nama); ?>" style="height: 250px; object-fit: cover;">
               <div class="suara mb-2">
                  <span class="badge badge-success" style="font-size: 14px; padding: 8px 12px;"><?php echo $suara; ?> Suara</span>
               </div>
               <div class="caption">
                     <h5 class="font-weight-bold mb-3"><?php echo htmlspecialchars($nama); ?></h5>
                     <a href="?page=kandidat&action=edit&id=<?php echo $id; ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
                     <a href="?page=kandidat&action=view&id=<?php echo $id; ?>" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> View</a>
                     <a href="?page=kandidat&action=hapus&id=<?php echo $id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus kandidat ini ?')"><i class="fas fa-trash"></i> Hapus</a>
               </div>
         </div>
      </div>
      <?php
      }
      ?>
   </div>
   <?php
} else {
   echo '<div class="col-md-12 mt-5">
            <div class="alert alert-warning text-center" style="padding: 30px;">
               <h5 class="mb-0">Tidak ada kandidat</h5>
            </div>
         </div>';
}
?>