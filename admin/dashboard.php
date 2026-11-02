<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ob_start();
session_start();

if (!isset($_SESSION['id_admin'])) {
    header('location: ./');
    exit;
}

define('BASEPATH', dirname(__FILE__));
require_once '../include/connection.php';

// Pastikan variabel koneksi global siap
if (!isset($con) && isset($conn)) { $con = $conn; }
if (!isset($con) && isset($koneksi)) { $con = $koneksi; }
if (!isset($con) && isset($pdo)) { $con = $pdo; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>E-Voting Dashboard &mdash; Osis</title>
  <link rel="icon" href="../images/almadani.png">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/hover-min.css">
  <link rel="stylesheet" href="assets/css/all.min.css">
  <link rel="stylesheet" href="assets/css/components.css">
  <link href="assets/table/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
  <script>
    if (localStorage.getItem('sidebar_mini') === 'true') {
      document.documentElement.classList.add('sidebar-mini-preload');
      document.addEventListener("DOMContentLoaded", function() {
        document.body.classList.add('sidebar-mini');
      });
    }
  </script>
  <style>
    html.sidebar-mini-preload .main-sidebar { width: 95px !important; }
    html.sidebar-mini-preload .main-content, html.sidebar-mini-preload .main-footer { padding-left: 125px !important; }
    html.sidebar-mini-preload .main-navbar { left: 95px !important; }
    body {
      font-family: 'Nunito', sans-serif !important;
      background: linear-gradient(rgba(15, 23, 42, 0.45), rgba(15, 23, 42, 0.55)), url('../images/background.jpg') no-repeat center center fixed !important;
      background-size: cover !important;
      color: #1e293b !important;
    }
    .navbar-bg { display: none !important; }
    .main-navbar {
      background: rgba(255, 255, 255, 0.95) !important;
      backdrop-filter: blur(10px);
      left: 250px; height: 75px;
      border-bottom: 1px solid rgba(226, 232, 240, 0.8) !important;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08) !important;
      transition: all 0.3s ease; padding-left: 25px !important;
    }
    .main-navbar .nav-link-user { color: #1e293b !important; font-weight: 700; }
    .main-navbar .nav-link-user i { color: #3b82f6; }
    .navbar-brand-text {
      font-size: 18px !important; font-weight: 900 !important;
      color: #0f172a !important; text-decoration: none !important;
      letter-spacing: 0.8px; white-space: nowrap;
    }
    body.sidebar-mini .main-navbar { left: 95px !important; }
    .main-sidebar {
      background: rgba(255, 255, 255, 0.98) !important;
      backdrop-filter: blur(10px);
      border-right: 1px solid rgba(226, 232, 240, 0.8);
      box-shadow: 4px 0 20px rgba(0,0,0,0.05);
    }
    body.sidebar-mini .main-sidebar, body.sidebar-mini .main-sidebar .sidebar-wrapper { width: 95px !important; transition: all 0.3s; z-index: 890; }
    body.sidebar-mini .main-content, body.sidebar-mini .main-footer { padding-left: 125px !important; transition: all 0.3s; }
    .sidebar-brand {
      display: flex !important; justify-content: space-between !important; 
      align-items: center !important; height: 75px !important; padding: 0 15px !important;
      margin: 0 !important; border-bottom: none !important; background: transparent !important; overflow: hidden;
    }
    body.sidebar-mini .sidebar-brand { justify-content: center !important; padding: 0 !important; width: 95px !important; }
    .brand-text { font-size: 11px !important; font-weight: 900; color: #0f172a !important; text-decoration: none !important; letter-spacing: 0.3px; white-space: nowrap; }
    body.sidebar-mini .brand-text { display: none !important; }
    .btn-toggle-custom {
      background: #3b82f6 !important; color: #ffffff !important; width: 34px !important; height: 34px !important;
      min-width: 34px !important; min-height: 34px !important; padding: 0 !important; display: flex !important;
      justify-content: center !important; align-items: center !important; border-radius: 8px !important;
      font-size: 14px !important; cursor: pointer; text-decoration: none !important; transition: all 0.2s ease;
      box-shadow: none !important; border: none !important; flex-shrink: 0 !important;
    }
    .btn-toggle-custom:hover { background: #2563eb !important; }
    .sidebar-brand-sm { display: none !important; }
    body.sidebar-mini .sidebar-menu li.menu-header { display: none !important; }
    body.sidebar-mini .sidebar-menu li a { justify-content: center !important; padding: 15px 0 !important; }
    body.sidebar-mini .sidebar-menu li a i { margin-right: 0 !important; font-size: 20px !important; }
    body.sidebar-mini .sidebar-menu li a span { display: none !important; }
    .sidebar-menu li { margin-bottom: 0 !important; padding-top: 0 !important; padding-bottom: 0 !important; }
    .sidebar-menu li.menu-header { font-size: 12px !important; font-weight: 700 !important; color: #1e293b !important; letter-spacing: 1px; padding: 15px 25px 5px 25px !important; }
    .sidebar-menu li a {
      display: flex !important; align-items: center; white-space: nowrap; width: auto !important;
      color: #1e293b !important; font-weight: 600 !important; border-radius: 8px; margin: 2px 15px !important;
      padding: 10px 15px !important; height: auto !important; line-height: normal !important; transition: 0.2s ease;
    }
    .sidebar-menu li a i { margin-right: 15px; }
    .sidebar-menu li a:hover { background-color: #f1f5f9; color: #0f172a !important; }
    .sidebar-menu li.active a { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important; color: #ffffff !important; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3) !important; }
    .sidebar-menu li.active a i { color: #ffffff !important; }
    .main-content { padding-left: 280px; padding-right: 30px; padding-top: 100px; transition: all 0.3s ease; }
    .main-content .section, .main-content .card, .main-content .container-fluid, .main-content .content-body, .main-content .section-body, .main-content .card-body {
      background: #ffffff !important; color: #1e293b !important; border-radius: 14px !important; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
    }
    .main-content .card { border: 1px solid #e2e8f0 !important; margin-bottom: 25px; }
    .stat-nis-card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 20px; height: 100%; box-shadow: 0 2px 6px rgba(0,0,0,0.02); }
    .stat-nis-icon { width: 56px; height: 56px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; color: #fff; flex-shrink: 0; background: #3b82f6; }
    .stat-nis-label { font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
    .stat-nis-value { font-size: 28px; font-weight: 800; color: #0f172a; line-height: 1.1; }
    .card-chart { background: #ffffff !important; border: 1px solid #e2e8f0 !important; border-radius: 14px !important; box-shadow: none !important; }
    .card-chart .card-header { background: transparent !important; border-bottom: 1px solid #f1f5f9 !important; padding: 20px 24px !important; }
    .card-chart .card-header h4 { color: #0f172a !important; font-weight: 800 !important; font-size: 16px !important; }
    .card-chart .card-footer { background: #f8fafc !important; border-top: 1px solid #f1f5f9 !important; padding: 16px 24px !important; border-bottom-left-radius: 14px !important; border-bottom-right-radius: 14px !important; }
    .btn-flat { border: none !important; border-radius: 8px !important; font-weight: 700 !important; padding: 9px 18px !important; color: #fff !important; box-shadow: none !important; transition: 0.2s; }
    .btn-flat-excel { background: #10b981 !important; } .btn-flat-excel:hover { background: #059669 !important; }
    .btn-flat-pdf { background: #ef4444 !important; } .btn-flat-pdf:hover { background: #dc2626 !important; }
    .btn-flat-chart { background: #3b82f6 !important; } .btn-flat-chart:hover { background: #2563eb !important; }
    .main-footer {
      background: rgba(255, 255, 255, 0.95) !important; backdrop-filter: blur(10px);
      border-top: 1px solid #e2e8f0 !important; color: #64748b !important;
      padding: 20px 30px 20px 280px !important; display: flex !important;
      justify-content: center !important; align-items: center !important; text-align: center !important;
      font-size: 13px !important; font-weight: 600 !important; margin-top: 30px !important;
    }
    @media (max-width: 1024px) {
      .main-footer { padding: 20px !important; }
      .main-content { padding-left: 20px !important; padding-right: 20px !important; }
    }
  </style>
</head>
<body>
  <div id="app">
    <div class="main-wrapper">
      <nav class="navbar navbar-expand-lg main-navbar">
        <div class="mr-auto d-flex align-items-center">
          <a href="./" class="navbar-brand-text">METSCHOO E-VOTING</a>
        </div>
        <ul class="d-flex align-items-center m-0 p-0" style="list-style: none;">
          <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
              <i class="fas fa-user-circle" style="font-size: 20px; margin-right:6px;"></i>
              <div class="d-sm-none d-lg-inline-block">Administrator</div>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow-lg border-0 rounded-lg mt-2">
              <a href="?page=edit_profil" class="dropdown-item has-icon font-weight-bold">
                <i class="fas fa-edit text-primary"></i> Edit Profile
              </a>
              <a href="?page=change_password" class="dropdown-item has-icon font-weight-bold">
                <i class="fas fa-key text-warning"></i> Ganti Password
              </a>
              <div class="dropdown-divider"></div>
              <a href="?page=logout" class="dropdown-item has-icon text-danger font-weight-bold confirmation">
                <i class="fas fa-sign-out-alt"></i> Sign Out
              </a>
            </div>
          </li>
        </ul>
      </nav>

      <div class="main-sidebar">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="./" class="brand-text">ADMIN DASHBOARD</a>
            <a href="#" id="gemini-toggle-btn" class="btn-toggle-custom">
              <i class="fas fa-bars"></i>
            </a>
          </div>
          <ul class="sidebar-menu" style="margin-top: 15px;">
            <li class="menu-header">DASHBOARD</li>
            <li class="nav-item dropdown <?php if (!isset($_GET['page'])) { echo 'active'; } ?>">
              <a href="./" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a>
            </li>
            <li class="menu-header">MANAJEMEN</li>
            <li class="nav-item dropdown <?php if (isset($_GET['page']) && $_GET['page'] == 'user') { echo 'active'; } ?>">
              <a href="?page=user" class="nav-link"><i class="fas fa-users"></i><span>Manajemen User</span></a>
            </li>
            <li class="nav-item dropdown <?php if (isset($_GET['page']) && $_GET['page'] == 'kandidat') { echo 'active'; } ?>">
              <a href="?page=kandidat" class="nav-link"><i class="fas fa-user-tie"></i><span>Manajemen Kandidat</span></a>
            </li>
            <li class="nav-item dropdown <?php if (isset($_GET['page']) && $_GET['page'] == 'kelas') { echo 'active'; } ?>">
              <a href="?page=kelas" class="nav-link"><i class="fas fa-school"></i><span>Manajemen Kelas</span></a>
            </li>
            <li class="menu-header">HASIL</li>
            <li class="nav-item dropdown <?php if (isset($_GET['page']) && $_GET['page'] == 'perolehan') { echo 'active'; } ?>">
              <a href="?page=perolehan" class="nav-link"><i class="fas fa-chart-pie"></i><span>Perolehan Suara</span></a>
            </li>
            <li class="nav-item">
              <a href="lacak_pilihan.php" class="nav-link"><i class="fas fa-user-check"></i><span>Detail Pilihan Siswa</span></a>
            </li>
          </ul>
        </aside>
      </div>

      <div class="main-content">
        <section class="section">
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
              <div>
                <div class="p-0">
                  <?php
                  $page = isset($_GET['page']) ? $_GET['page'] : '';
                  switch ($page) {
                      case 'user':
                          include('./user/index.php');
                          break;
                      case 'kelas':
                          include('./kelas/index.php');
                          break;
                      case 'kandidat':
                          include('./kandidat/index.php');
                          break;
                      case 'perolehan':
                          include('./perolehan.php');
                          break;
                      case 'about':
                          include('about.php');
                          break;
                      case 'edit_profil':
                          include('./edit.php');
                          break;
                      case 'change_password':
                          include('./pass.php');
                          break;
                      case 'logout':
                          unset($_SESSION['id_admin']);
                          unset($_SESSION['user']);
                          header('location:./');
                          exit;
                          break;
                      default:
                          // KUNCI MUTLAK KE PEMILOSDB (BARIS 240)
                          $stmt_user = $con->query("SELECT COUNT(*) AS total_user FROM pemilosdb.t_user");
                          $data_user = $stmt_user->fetch();
                          $total_user = $data_user['total_user'] ?? 0;

                          $stmt_suara = $con->query("SELECT SUM(suara) AS total_suara FROM pemilosdb.t_kandidat");
                          $data_suara = $stmt_suara->fetch();
                          $total_suara = $data_suara['total_suara'] ?? 0;
                          ?>
                          <div class="container-fluid p-0">
                            <div class="row mb-4">
                              <div class="col-lg-6 col-md-6 col-sm-12 mb-3 mb-md-0">
                                <div class="stat-nis-card">
                                  <div class="stat-nis-icon">
                                    <i class="fas fa-users"></i>
                                  </div>
                                  <div>
                                    <div class="stat-nis-label">Total Siswa / Pemilih</div>
                                    <div class="stat-nis-value"><?php echo number_format($total_user); ?></div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="stat-nis-card">
                                  <div class="stat-nis-icon" style="background: #10b981;">
                                    <i class="fas fa-check-circle"></i>
                                  </div>
                                  <div>
                                    <div class="stat-nis-label">Total Suara Masuk</div>
                                    <div class="stat-nis-value"><?php echo number_format($total_suara); ?></div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="row">
                              <div class="col-lg-12">
                                <div class="card card-chart">
                                  <div class="card-header">
                                    <h4>Data Perolehan Suara</h4>
                                  </div>
                                  <div class="card-body pt-4">
                                    <div style="width: 100%; height: 380px;">
                                      <canvas id="canvas"></canvas>
                                    </div>
                                  </div>
                                  <div class="card-footer text-right">
                                    <a href="export.php" target="_blank" class="btn btn-flat btn-flat-excel mr-2">
                                      <i class="fas fa-file-excel mr-1"></i> Export ke Excel
                                    </a>
                                    <a href="berita_acara.php" target="_blank" class="btn btn-flat btn-flat-pdf mr-2">
                                      <i class="fas fa-file-pdf mr-1"></i> Download Berita Acara
                                    </a>
                                    <button class="btn btn-flat btn-flat-chart" id="save-img">
                                      <i class="fas fa-download mr-1"></i> Download Chart
                                    </button>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <?php
                          break;
                  }
                  ?>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>

      <footer class="main-footer">
        <div>
          <strong>Copyright &copy; 2025-2026 PPLG SMK METLAND CIBITUNG.</strong> &nbsp;All rights reserved.
        </div>
      </footer>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
  
  <?php if (!isset($_GET['page']) || (isset($_GET['page']) && $_GET['page'] == 'perolehan')) { ?>
    <script type="text/javascript" src="../assets/js/chart-bundle.js"></script>
    <script type="text/javascript" src="../assets/js/utils.js"></script>
    <script type="text/javascript" src="../assets/js/FileSaver.min.js"></script>
    <script type="text/javascript" src="../assets/js/canvas-toBlob.js"></script>
  <?php } ?>

  <script src="assets/table/vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="assets/table/vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="assets/js/scripts.js"></script>

  <script>
    document.getElementById('gemini-toggle-btn').addEventListener('click', function(e) {
      e.preventDefault();
      document.body.classList.toggle('sidebar-mini');
      if (document.body.classList.contains('sidebar-mini')) {
        localStorage.setItem('sidebar_mini', 'true');
      } else {
        localStorage.setItem('sidebar_mini', 'false');
      }
    });

    $(window).on('load', function() {
      document.documentElement.classList.remove('sidebar-mini-preload');
    });

    $(document).ready(function() {
      $("#save-img").click(function() {
        if(document.getElementById('canvas')) {
          document.getElementById('canvas').toBlob(function(blob) {
            saveAs(blob, 'chart.png');
          });
        }
      });
    });
  </script>

  <script type="text/javascript">
    <?php if (!isset($_GET['page']) || (isset($_GET['page']) && $_GET['page'] == 'perolehan')) {
        $thn = date('Y');
        $dpn = date('Y') + 1;
        $periode = $thn . '/' . $dpn;
        
        $stmt_kan = $con->prepare("SELECT nama_calon, suara FROM pemilosdb.t_kandidat WHERE periode = ?");
        $stmt_kan->execute([$periode]);
        $kandidat_list = $stmt_kan->fetchAll();

        $label = '';
        $data = '';

        foreach ($kandidat_list as $row) {
            $label .= "'" . $row['nama_calon'] . "',";
            $data .= $row['suara'] . ',';
        }
        $labels = trim($label, ',');
        $datas  = trim($data, ',');
    ?>
      var chartData = {
        labels: [<?php echo $labels; ?>],
        datasets: [{
          type: 'bar',
          label: 'Jumlah Suara',
          backgroundColor: ['rgba(255, 193, 7, 0.85)', 'rgba(255, 107, 129, 0.85)', 'rgba(72, 168, 255, 0.85)', 'rgba(16, 185, 129, 0.85)'],
          borderColor: ['#ffc107', '#ff6b81', '#48a8ff', '#10b981'],
          borderWidth: 1,
          data: [<?php echo $datas; ?>]
        }],
      };

      window.onload = function() {
        if(document.getElementById('canvas')) {
          var ctx = document.getElementById('canvas').getContext('2d');
          window.myMixedChart = new Chart(ctx, {
            type: 'bar',
            data: chartData,
            options: {
              responsive: true,
              maintainAspectRatio: false,
              animation: {
                  onComplete: function () {
                      var chartInstance = this.chart,
                          ctx = chartInstance.ctx;
                      ctx.font = 'bold 13px "Nunito", sans-serif';
                      ctx.fillStyle = '#0f172a';
                      ctx.textAlign = 'center';
                      ctx.textBaseline = 'bottom';

                      this.data.datasets.forEach(function (dataset, i) {
                          var meta = chartInstance.controller.getDatasetMeta(i);
                          meta.data.forEach(function (bar, index) {
                              var data = dataset.data[index];
                              ctx.fillText(data, bar._model.x, bar._model.y - 8);
                          });
                      });
                  }
              },
              scales: { 
                yAxes: [{ 
                    ticks: { 
                        beginAtZero: true, 
                        stepSize: 1,
                        suggestedMax: 15,
                        fontFamily: "'Nunito', sans-serif",
                        fontColor: "#64748b"
                    },
                    gridLines: {
                        color: "#f1f5f9",
                        drawBorder: false
                    }
                }],
                xAxes: [{
                    gridLines: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        fontFamily: "'Nunito', sans-serif",
                        fontColor: "#334155"
                    }
                }]
              },
              legend: {
                  labels: {
                      fontFamily: "'Nunito', sans-serif",
                      fontColor: "#64748b"
                  }
              }
            }
          });
        }
      };
    <?php } ?>
  </script>
</body>
</html>
<?php ob_flush(); ?>