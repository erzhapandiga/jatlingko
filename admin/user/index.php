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
         include('./user/add.php');
         break;

      case 'edit':
         include('./user/edit.php');
         break;

      case 'import':
         include('./user/import.php');
         break;

      case 'hapus_semua':
         try {
             // Gunakan transaction agar aman dari foreign key constraint
             $con->beginTransaction();

             // Kosongkan dulu tabel t_pemilih
             $con->exec("DELETE FROM t_pemilih");
             
             // Setelah itu kosongkan tabel t_user
             $con->exec("DELETE FROM t_user");

             $con->commit();

             echo '<script>alert("Semua data siswa berhasil dihapus!");window.location="dashboard.php?page=user";</script>';
         } catch (Exception $e) {
             if ($con->inTransaction()) {
                 $con->rollBack();
             }
             echo '<script>alert("Gagal menghapus data siswa!");window.history.go(-1);</script>';
         }
         break;

      case 'hapus':
         if (isset($_GET['id'])) {
            $nis = trim($_GET['id']);

            try {
                $con->beginTransaction();

                // Hapus dulu data pilihannya di t_pemilih (jika ada)
                $del_pemilih = $con->prepare("DELETE FROM t_pemilih WHERE nis = ?");
                $del_pemilih->execute([$nis]);

                // Setelah itu hapus data usernya di t_user
                $sql = $con->prepare("DELETE FROM t_user WHERE id_user = ?");
                $sql->execute([$nis]);

                $con->commit();
            } catch (Exception $e) {
                if ($con->inTransaction()) {
                    $con->rollBack();
                }
            }

            header('location: ?page=user');
            exit;
         } else {
            header('location: ./');
            exit;
         }
         break;

      default:
         include('./user/list.php');
         break;
   }

} else {
   include('./user/list.php');
}
?>