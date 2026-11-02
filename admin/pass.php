<?php
if (!isset($_SESSION['id_admin'])) {
   header('location:./');
   exit;
}

// Penyelarasan variabel koneksi PDO
if (!isset($con)) {
    if (isset($pdo)) { $con = $pdo; }
    elseif (isset($conn)) { $con = $conn; }
    elseif (isset($koneksi)) { $con = $koneksi; }
}

if (isset($_POST['update_pass'])) {
   $pass_lama = $_POST['lama'] ?? '';
   $pass_baru = $_POST['baru'] ?? '';

   if ($pass_lama === '' || $pass_baru === '') {
      echo '<script type="text/javascript">alert("Semua form harus diisi");</script>';
   } else {
      $stmt = $con->prepare("SELECT password FROM t_admin WHERE id_admin = ?");
      $stmt->execute([$_SESSION['id_admin']]);
      $admin = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($admin) {
         // Validasi password lama
         if (password_verify($pass_lama, $admin['password'])) {
            $hashed_baru = password_hash($pass_baru, PASSWORD_BCRYPT, ['cost' => 10]);

            $update = $con->prepare("UPDATE t_admin SET password = ? WHERE id_admin = ?");
            $update->execute([$hashed_baru, $_SESSION['id_admin']]);

            echo '<script type="text/javascript">window.location.replace("?page=logout");</script>';
            exit;
         } else {
            echo '<script type="text/javascript">alert("Password lama salah!");window.location.replace("?page=pass");</script>';
            exit;
         }
      } else {
         echo '<script type="text/javascript">alert("Akses Illegal");window.location.replace("?page=logout");</script>';
         exit;
      }
   }
}
?>
<h3 class="pl-5 pb-3 pt-5">Ganti Password</h3>
<hr class="ml-5 mr-5" />
<div class="row">
    <div class="col-md-10">
        <form action="" method="post" class="form-horizontal ml-4">
         <table class="table table-borderless">
            <tr>
               <td> Password Lama </td>
               <td> <input type="password" class="form-control" name="lama" placeholder="Password Lama" required /> </td>
            </tr>
            <tr>
               <td> Password Baru </td>
               <td> <input type="password" class="form-control" name="baru" placeholder="Password Baru" required /> </td>
            </tr>
         </table>
            <div class="form-group ml-2">
                <div class="col-md-8 col-md-offset-3">
                    <p class="help-text" style="color:#666464;">
                        Note : Anda harus login ulang saat password berhasil diupdate
                    </p>
                    <button type="submit" name="update_pass" class="btn btn-success" value="Update Password">
                        Update Password
                    </button>
                    <button type="button" onclick="window.history.go(-1)" class="btn btn-danger">
                        Kembali
                    </button>
                </div>
            </div>

         </form>
    </div>
</div>