<?php
if (!isset($_SESSION['id_admin'])) {
   header('location: ../');
   exit;
}

// Penyelarasan variabel koneksi PDO
if (!isset($con)) {
    if (isset($pdo)) { $con = $pdo; }
    elseif (isset($conn)) { $con = $conn; }
    elseif (isset($koneksi)) { $con = $koneksi; }
}

if (isset($_GET['action'])) {

   switch ($_GET['action']) {
      case 'tambah':
         include('./kelas/add.php');
         break;

      case 'edit':
         include('./kelas/edit.php');
         break;

      case 'hapus':

         if (isset($_GET['id'])) {

            $id_kelas = trim($_GET['id']);

            try {
                // Hapus data kelas menggunakan PDO Prepared Statement
                $sql = $con->prepare("DELETE FROM t_kelas WHERE id_kelas = ?");
                $sql->execute([$id_kelas]);
            } catch (Exception $e) {
                // Jika gagal karena constraint foreign key (masih ada siswa di kelas tersebut)
                echo '<script>alert("Gagal menghapus kelas! Kemungkinan masih ada siswa yang terdaftar di kelas ini.");</script>';
            }

            header('location: ?page=kelas');
            exit;

         } else {

            header('location: ./');
            exit;

         }

         break;
      default:
         include('./kelas/list.php');
         break;
   }

} else {

   include('./kelas/list.php');

}
?>