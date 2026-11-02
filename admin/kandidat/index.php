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
         include('./kandidat/add.php');
         break;

      case 'edit':
         include('./kandidat/edit.php');
         break;

      case 'view':
         include('./kandidat/view.php');
         break;

      case 'hapus':

         if (isset($_GET['id'])) {

            $id = trim($_GET['id']);

            // 1. Ambil nama file foto menggunakan PDO Prepared Statement
            $stmt_select = $con->prepare("SELECT foto FROM t_kandidat WHERE id_kandidat = ?");
            $stmt_select->execute([$id]);
            $row = $stmt_select->fetch(PDO::FETCH_ASSOC);

            if ($row && !empty($row['foto'])) {
                $f = $row['foto'];
                $file_path = '../assets/img/kandidat/' . $f;
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }

            // 2. Hapus data kandidat dari database menggunakan PDO Prepared Statement
            $stmt_delete = $con->prepare("DELETE FROM t_kandidat WHERE id_kandidat = ?");
            $stmt_delete->execute([$id]);

            header('location: ?page=kandidat');
            exit;

         } else {

            header('location: ./');
            exit;

         }

         break;
      default:
         include('./kandidat/list.php');
         break;
   }

} else {

   include('./kandidat/list.php');

}
?>
<div style="margin-bottom: 200px;"></div>