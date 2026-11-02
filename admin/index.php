<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ob_start();
session_start();

require_once '../include/connection.php';

// Pastikan variabel koneksi global siap
if (!isset($con) && isset($conn)) { $con = $conn; }
if (!isset($con) && isset($koneksi)) { $con = $koneksi; }
if (!isset($con) && isset($pdo)) { $con = $pdo; }

$error = '';

if (isset($_POST['login'])) {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = "Username dan password wajib diisi!";
    } else {
        try {
            // Kunci mutlak ke pemilosdb.t_admin
            $stmt = $con->prepare("SELECT * FROM pemilosdb.t_admin WHERE username = ?");
            $stmt->execute([$username]);
            $admin = $stmt->fetch();

            if ($admin) {
                // Cek password (mendukung MD5 atau password biasa sesuai database lama)
                if (md5($password) === $admin['password'] || $password === $admin['password']) {
                    $_SESSION['id_admin'] = $admin['id_admin'];
                    $_SESSION['user'] = $admin['username'];
                    header('location: dashboard.php');
                    exit;
                } else {
                    $error = "Password yang Anda masukkan salah!";
                }
            } else {
                $error = "Akun administrator tidak ditemukan!";
            }
        } catch (\PDOException $e) {
            $error = "Database Error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Login Admin &mdash; E-Voting Osis</title>
  <link rel="icon" href="../images/almadani.png">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/components.css">
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Nunito', sans-serif !important;
      background: linear-gradient(rgba(15, 23, 42, 0.6), rgba(15, 23, 42, 0.7)), url('../images/background.jpg') no-repeat center center fixed !important;
      background-size: cover !important;
    }
    .card-login {
      border: 1px solid #e2e8f0 !important;
      border-radius: 16px !important;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2) !important;
      background: rgba(255, 255, 255, 0.98) !important;
      backdrop-filter: blur(10px);
    }
    .btn-login {
      background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important;
      border: none !important;
      border-radius: 10px !important;
      font-weight: 700 !important;
      padding: 12px !important;
      box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3) !important;
      transition: all 0.3s ease;
    }
    .btn-login:hover {
      background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%) !important;
      transform: translateY(-1px);
    }
    .form-control {
      border-radius: 10px !important;
      padding: 12px 15px !important;
      height: auto !important;
      border: 1px solid #cbd5e1 !important;
    }
    .form-control:focus {
      border-color: #3b82f6 !important;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15) !important;
    }
  </style>
</head>
<body>
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row justify-content-center">
          <div class="col-12 col-md-8 col-lg-5">
            <div class="login-brand text-center mb-4">
              <img src="../images/almadani.png" alt="logo" width="90" class="shadow-light rounded-circle mb-3 bg-white p-2">
              <h4 class="text-white font-weight-bold">ADMIN E-VOTING</h4>
              <p class="text-white-50 small">PPLG SMK METLAND CIBITUNG</p>
            </div>

            <div class="card card-login p-4">
              <div class="card-header border-0 bg-transparent pb-0">
                <h4 class="text-dark font-weight-bold">Sign In</h4>
              </div>

              <div class="card-body">
                <?php if (!empty($error)) { ?>
                  <div class="alert alert-danger alert-has-icon rounded-lg mb-4">
                    <div class="alert-icon"><i class="fas fa-exclamation-circle"></i></div>
                    <div class="alert-body">
                      <div class="font-weight-bold">Gagal Login</div>
                      <?php echo $error; ?>
                    </div>
                  </div>
                <?php } ?>

                <form method="POST" action="" class="needs-validation" novalidate="">
                  <div class="form-group">
                    <label for="username" class="font-weight-bold text-dark">Username</label>
                    <input id="username" type="text" class="form-control" name="username" tabindex="1" required autofocus placeholder="Masukkan username admin">
                  </div>

                  <div class="form-group">
                    <label for="password" class="font-weight-bold text-dark">Password</label>
                    <input id="password" type="password" class="form-control" name="password" tabindex="2" required placeholder="Masukkan password">
                  </div>

                  <div class="form-group mt-4">
                    <button type="submit" name="login" class="btn btn-primary btn-lg btn-block btn-login" tabindex="3">
                      Login ke Dashboard
                    </button>
                  </div>
                </form>
              </div>
            </div>
            <div class="simple-footer text-center text-white-50 mt-4 small">
              Copyright &copy; 2025-2026 PPLG SMK METLAND CIBITUNG
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <script src="assets/js/scripts.js"></script>
</body>
</html>
<?php ob_flush(); ?>