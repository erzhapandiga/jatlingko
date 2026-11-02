<?php
if (!isset($_SESSION['id_admin'])) {
   header('location:./');
   exit;
}

// Pastikan koneksi global menggunakan PDO
if (!isset($con)) {
    if (isset($pdo)) { $con = $pdo; }
    elseif (isset($conn)) { $con = $conn; }
    elseif (isset($koneksi)) { $con = $koneksi; }
}

if (isset($_POST['update_profil'])) {
   $nama     = $_POST['username'];
   $fullname = $_POST['fullname'];
   $pass     = $_POST['pass'];

   if($nama == '' || $fullname == '' || $pass == '') {
      echo '<script type="text/javascript">alert("Semua form harus diisi");</script>';
   } else {
      $stmt = $con->prepare("SELECT id_admin, username, fullname, password FROM t_admin WHERE id_admin = ?");
      $stmt->execute([$_SESSION['id_admin']]);
      $admin = $stmt->fetch(PDO::FETCH_ASSOC);

      if($admin) {
         // Validasi password menggunakan password_verify
         if(password_verify($pass, $admin['password'])) {
            $update = $con->prepare("UPDATE t_admin SET username = ?, fullname = ? WHERE id_admin = ?");
            $update->execute([$nama, $fullname, $_SESSION['id_admin']]);

            $_SESSION['user'] = $fullname;

            echo '<script type="text/javascript">window.location.replace("./");</script>';
            exit;
         } else {
            echo '<script type="text/javascript">alert("Akses Illegal !!");window.location.replace("?page=logout");</script>';
            exit;
         }
      }
   }
}

$stmt_get = $con->prepare("SELECT id_admin, username, fullname, password FROM t_admin WHERE id_admin = ?");
$stmt_get->execute([$_SESSION['id_admin']]);
$data_admin = $stmt_get->fetch(PDO::FETCH_ASSOC);

$username = $data_admin['username'] ?? '';
$fullname = $data_admin['fullname'] ?? '';
?>

<h3 class="pl-5 pb-3 pt-5">Edit Profil</h3>
<hr class="ml-5 mr-5" />
<div class="row">
   <div class="col-md-11 col-md-offset-1">
        <form action="" method="post" class="form-horizontal ml-4">
         <table class="table table-borderless">
            <tr>
               <td> Username </td>
               <td> <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" required placeholder="Username" class="form-control"> </td>
            </tr>
            <tr>
               <td> Fullname  </td>
               <td> <input type="text" name="fullname" value="<?php echo htmlspecialchars($fullname); ?>" required placeholder="Fullname" class="form-control"></td>
            </tr>
            <tr>
               <td> Password </td>
               <td> <input type="password" class="form-control" name="pass" required placeholder="Masukkan Password Anda"></td>
            </tr>
         </table>

            <div class="form-group ml-2">
                <div class="col-md-8 col-md-offset-3">
                    <button type="submit" name="update_profil" class="btn btn-success" value="Update Profil">
                        Update Profil
                    </button>
                    <button type="button" onclick="window.history.go(-1)" class="btn btn-danger">
                        Kembali
                    </button>
                </div>
            </div>
         </form>
   </div>
</div>