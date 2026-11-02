<?php
session_start();

// Panggil file koneksi database
require_once 'include/connection.php';

// Penyelarasan variabel koneksi PDO
if (!isset($con)) {
    if (isset($pdo)) { $con = $pdo; }
    elseif (isset($conn)) { $con = $conn; }
    elseif (isset($koneksi)) { $con = $koneksi; }
}

$error = '';

// Proses saat form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nisn = trim($_POST['NISN'] ?? '');

    if (empty($nisn)) {
        $error = "NISN tidak boleh kosong!";
    } else {
        try {
            // Cek data user di database menggunakan PDO Prepared Statement
            $stmt = $con->prepare("SELECT * FROM t_user WHERE id_user = ? OR nisn = ?");
            $stmt->execute([$nisn, $nisn]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Set Session Pemilih
                $_SESSION['siswa'] = $user['id_user'];
                $_SESSION['fullname'] = $user['fullname'];

                // Lempar ke halaman voting
                header('Location: vote.php');
                exit;
            } else {
                $error = "NISN tidak terdaftar di dalam sistem!";
            }
        } catch (PDOException $e) {
            $error = "Terjadi kesalahan pada database.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Login Pemilih - E-Voting</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->  
    <link rel="icon" href="images/almadani.png">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->  
    <link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
</head>
<body>
    
    <div class="limiter">
        <div class="container-login100" style="background-image: url('images/1.jpg');">
            <div class="wrap-login100 p-t-150 p-b-30">
                <form class="login100-form validate-form" method="POST" action="">
                    
                    <div class="login100-form-avatar">
                        <img src="images/osis.png" height="200" alt="AVATAR">
                    </div>
                    <div class="login100-form-avatar1">
                        <img src="images/almadani.png" height="180" alt="AVATAR">
                    </div>

                    <span class="login100-form-title p-t-20 p-b-25">
                        PEMILIHAN KETUA OSIS <br>SMK METLAND CIBITUNG
                    </span>

                    <!-- Tampilkan Pesan Error jika NISN salah/kosong -->
                    <?php if (!empty($error)) : ?>
                        <div class="alert alert-danger text-center mb-4" role="alert" style="font-size: 13px; border-radius: 10px;">
                            <i class="fa fa-exclamation-triangle"></i> <?= htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <div class="wrap-input100 validate-input m-b-10" data-validate = "NISN is required">
                        <input class="input100" type="text" name="NISN" placeholder="Masukkan NISN" required autocomplete="off">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-user"></i>
                        </span>
                    </div>

                    <div class="container-login100-form-btn p-t-10">
                        <button type="submit" class="login100-form-btn">
                            Login
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    
<!--===============================================================================================-->  
    <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
    <script src="vendor/bootstrap/js/popper.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
    <script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
    <script src="js/main.js"></script>

</body>
</html>