<?php
session_start();

if (isset($_SESSION['id_admin'])) {
  header('location: ./dashboard.php');
  exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
  define('BASEPATH', dirname(__FILE__));
  require '../include/connection.php';

  // Penyelarasan variabel koneksi PDO
  if (!isset($con)) {
      if (isset($pdo)) { $con =$pdo; }
      elseif (isset($conn)) { $con =$conn; }
      elseif (isset($koneksi)) { $con =$koneksi; }
  }

  $user = trim($_POST['username'] ?? '');
  $pass = trim($_POST['pass'] ?? '');

  if ($user === '' || $pass === '') {$error = 'Username dan password wajib diisi.';
  } else {
    // Menggunakan PDO prepared statement
    $stmt =$con->prepare("SELECT id_admin, username, fullname, password FROM t_admin WHERE username = ?");
    $stmt->execute([$user]);
    $admin =$stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin) {
      if (password_verify($pass,$admin['password'])) {
        $_SESSION['id_admin'] =$admin['id_admin'];
        $_SESSION['user']     =$admin['fullname'];
        header('location: ./dashboard.php');
        exit;
      } else {
        $error = 'Password yang Anda masukkan salah.';
      }
    } else {
      $error = 'Username tidak dikenali.';
    }
  }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Login Admin &mdash; E-Voting OSIS</title>

  <!-- Favicon -->
  <link rel="icon" href="../images/almadani.png">

  <!-- Bootstrap -->
  <link rel="stylesheet"
    href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
    crossorigin="anonymous">

  <!-- Stisla / Custom -->
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/components.css">
  <link rel="stylesheet" href="../assets/css/custom.css">

  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      min-height: 100vh;
      font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI",
        Roboto, sans-serif;
      background: radial-gradient(circle at top left, #0ea5e9 0, #1f2937 40%, #020617 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 16px;
    }

    .login-wrapper {
      width: 100%;
      max-width: 960px;
      display: grid;
      grid-template-columns: minmax(0, 1.2fr) minmax(0, 1fr);
      gap: 0;
      border-radius: 20px;
      overflow: hidden;
      background: rgba(15, 23, 42, 0.92);
      box-shadow: 0 28px 60px rgba(0, 0, 0, 0.6);
      backdrop-filter: blur(16px);
      border: 1px solid rgba(148, 163, 184, 0.35);
    }

    .login-left {
      position: relative;
      padding: 32px 32px 28px;
      color: #f9fafb;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      background: radial-gradient(circle at top left, rgba(56, 189, 248, 0.3), transparent 55%),
        radial-gradient(circle at bottom right, rgba(96, 165, 250, 0.35), transparent 55%);
    }

    .login-left-header {
      display: flex;
      align-items: center;
      gap: 14px;
      margin-bottom: 18px;
    }

    .login-logo {
      width: 52px;
      height: 52px;
      border-radius: 14px;
      background: rgba(15, 23, 42, 0.8);
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 18px 40px rgba(15, 23, 42, 0.7);
    }

    .login-logo img {
      max-width: 38px;
      max-height: 38px;
    }

    .login-title {
      font-size: 22px;
      font-weight: 700;
      margin: 0;
    }

    .login-subtitle {
      font-size: 13px;
      color: #e5e7eb;
      margin-top: 2px;
    }

    .login-highlight {
      margin-top: 20px;
    }

    .pill {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 3px 10px;
      border-radius: 999px;
      background: rgba(34, 197, 94, 0.18);
      border: 1px solid rgba(34, 197, 94, 0.4);
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: 0.12em;
    }

    .pill-dot {
      width: 8px;
      height: 8px;
      border-radius: 999px;
      background: #22c55e;
    }

    .login-desc {
      margin-top: 14px;
      font-size: 13px;
      color: #cbd5f5;
      max-width: 380px;
    }

    .login-footer-left {
      margin-top: 28px;
      font-size: 11px;
      color: #9ca3af;
    }

    .login-right {
      background: #f9fafb;
      padding: 32px 28px 26px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .login-right-title {
      font-size: 18px;
      font-weight: 600;
      color: #111827;
      margin-bottom: 6px;
    }

    .login-right-subtitle {
      font-size: 13px;
      color: #6b7280;
      margin-bottom: 16px;
    }

    .form-label {
      font-size: 13px;
      font-weight: 500;
      color: #374151;
    }

    .form-control-custom {
      border-radius: 999px;
      border: 1px solid #d1d5db;
      padding: 10px 14px;
      font-size: 14px;
      outline: none;
      width: 100%;
      transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .form-control-custom:focus {
      border-color: #38bdf8;
      box-shadow: 0 0 0 2px rgba(56, 189, 248, 0.35);
    }

    .btn-login {
      border-radius: 999px;
      width: 100%;
      border: none;
      padding: 10px 14px;
      font-size: 14px;
      font-weight: 600;
      color: #f9fafb;
      background: #111827;
      box-shadow: 0 12px 24px rgba(15, 23, 42, 0.45);
      cursor: pointer;
      transition: background 0.16s ease, transform 0.16s ease,
        box-shadow 0.16s ease;
    }

    .btn-login:hover {
      background: #020617;
      transform: translateY(-1px);
      box-shadow: 0 16px 30px rgba(15, 23, 42, 0.6);
    }

    .btn-login:active {
      transform: translateY(0);
      box-shadow: 0 8px 16px rgba(15, 23, 42, 0.45);
    }

    .error-alert {
      border-radius: 10px;
      font-size: 13px;
      padding: 8px 10px;
      margin-bottom: 12px;
    }

    .login-meta {
      margin-top: 10px;
      font-size: 11px;
      color: #9ca3af;
      text-align: center;
    }

    @media (max-width: 900px) {
      .login-wrapper {
        max-width: 540px;
        grid-template-columns: minmax(0, 1fr);
      }

      .login-left {
        display: none;
      }
    }

    @media (max-width: 576px) {
      body {
        padding: 12px;
      }

      .login-right {
        padding: 24px 18px 20px;
      }
    }
  </style>
</head>

<body>
  <div class="login-wrapper">
    <!-- Kiri: branding -->
    <div class="login-left">
      <div>
        <div class="login-left-header">
          <div class="login-logo">
            <img src="../images/almadani.png" alt="Logo Sekolah">
          </div>
          <div>
            <h1 class="login-title">Panel Admin E-Voting</h1>
            <p class="login-subtitle">Sistem Pemilihan Ketua & Wakil Ketua OSIS</p>
          </div>
        </div>

        <div class="login-highlight">
          <div class="pill">
            <span class="pill-dot"></span>
            <span>Secure Voting</span>
          </div>
          <p class="login-desc">
            Kelola data kandidat, pemilih, dan hasil perolehan suara dengan aman dan
            terpusat melalui panel admin ini.
          </p>
        </div>
      </div>

      <div class="login-footer-left">
        &copy; <?php echo date('Y'); ?> Panitia Pemilihan OSIS &middot; SMK Metland Cibitung
      </div>
    </div>

    <!-- Kanan: form login -->
    <div class="login-right">
      <h2 class="login-right-title">Login Administrator</h2>
      <p class="login-right-subtitle">
        Masukkan username dan password admin untuk mengakses dashboard E-Voting.
      </p>

      <?php if ($error !== ''): ?>
        <div class="alert alert-danger error-alert mb-3" role="alert">
          <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
        </div>
      <?php endif; ?>

      <form method="post" autocomplete="off">
        <div class="form-group">
          <label for="username" class="form-label">Username</label>
          <input
            type="text"
            name="username"
            id="username"
            class="form-control-custom"
            placeholder="Masukkan username admin"
            required>
        </div>

        <div class="form-group mt-2">
          <label for="pass" class="form-label">Password</label>
          <input
            type="password"
            name="pass"
            id="pass"
            class="form-control-custom"
            placeholder="Masukkan password"
            required>
        </div>

        <button type="submit" name="submit" class="btn-login mt-3">
          Masuk ke Dashboard
        </button>
      </form>

      <div class="login-meta">
        Pastikan kredensial tidak dibagikan kepada pihak yang tidak berkepentingan.
      </div>
    </div>