<?php
ob_start();
session_start();

if (!isset($_SESSION['id_admin'])) {
    header('location: ./');
    exit;
}

define('BASEPATH', dirname(__FILE__));
require_once '../include/connection.php';

// Penyelarasan variabel koneksi agar mendukung PDO / MySQLi
if (!isset($con)) {
    if (isset($pdo)) { $con = $pdo; }
    elseif (isset($conn)) { $con = $conn; }
    elseif (isset($koneksi)) { $con = $koneksi; }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Dashboard &mdash; AMG E-Voting</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="../assets/css/all.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/components.css">
  <link rel="stylesheet" href="../assets/css/custom.css">
  <link rel="stylesheet" href="../assets/node_modules/bootstrap-social/bootstrap-social.css">
  <link rel="stylesheet" href="../assets/node_modules/summernote/dist/summernote-bs4.css">
</head>

<body>
  <div id="app">
    <div class="main-wrapper">
      <div class="navbar-bg bg-primary"></div>
      <nav class="navbar navbar-expand-lg main-navbar">
        <a href="#" class="navbar-brand sidebar-gone-hide">AMG E-Voting</a>
        <a href="#" class="nav-link sidebar-gone-show" data-toggle="sidebar"><i class="fas fa-bars"></i></a>
        <form class="form-inline ml-auto">
          <ul class="navbar-nav">
            <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
          </ul>
        </form>
        <ul class="navbar-nav navbar-right">
          <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
              <img alt="image" src="../images/admin.png" class="rounded-circle mr-1">
              <div class="d-sm-none d-lg-inline-block"><?php echo isset($_SESSION['user']) ? $_SESSION['user'] : 'Admin'; ?></div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
              <a href="?page=about" class="dropdown-item has-icon">
                <i class="fas fa-users"></i> About
              </a>
              <a href="?page=pass" class="dropdown-item has-icon">
                <i class="fas fa-key"></i> Ganti Password
              </a>
              <div class="dropdown-divider"></div>
              <a href="?page=logout" class="dropdown-item has-icon text-danger confirmation">
                <i class="fas fa-sign-out-alt"></i> Sign Out
              </a>
            </div>
          </li>
        </ul>
      </nav>
      
      <div class="main-sidebar">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="#">AMG E-Voting</a>
          </div>
          <div class="sidebar-brand sidebar-brand-sm">
            <a href="#"><img src="../images/almadani.png" alt="" width="50"></a>
          </div>
          <ul class="sidebar-menu">
              <li class="menu-header">Dashboard</li>
              <li class="nav-item dropdown">
                <a href="./" class="nav-link text-primary"><i class="fas fa-fire text-primary"></i><span>Dashboard</span></a>
              </li>
              <li class="menu-header">Data</li>
              <li class="nav-item dropdown <?php if (isset($_GET['page']) && $_GET['page'] == 'kelas') { echo 'active'; } ?>">
                <a href="?page=kelas" class="nav-link text-primary"><i class="fas fa-door-open text-primary"></i> <span>Data Kelas</span></a>
              </li>
              <li class="nav-item dropdown <?php if (isset($_GET['page']) && $_GET['page'] == 'datapemilih') { echo 'active'; } ?>">
                <a href="?page=datapemilih" class="nav-link text-primary"><i class="fas fa-users text-primary"></i> <span>Data Pemilih</span></a>
              </li>
              <li class="nav-item dropdown <?php if (isset($_GET['page']) && $_GET['page'] == 'datakandidat') { echo 'active'; } ?>">
                <a href="?page=datakandidat" class="nav-link text-primary"><i class="fas fa-user-tie text-primary"></i> <span>Data Kandidat</span></a>
              </li>
              <li class="menu-header">Menu</li>
              <li class="nav-item dropdown <?php if (isset($_GET['page']) && $_GET['page'] == 'perolehan') { echo 'active'; } ?>">
                <a href="?page=perolehan" class="nav-link text-primary"><i class="fas fa-chart-bar text-primary"></i><span>Perolehan</span></a>
              </li>
              <li class="menu-header">About</li>
              <li class="nav-item dropdown <?php if (isset($_GET['page']) && $_GET['page'] == 'about') { echo 'active'; } ?>">
                <a href="?page=about" class="nav-link text-primary"><i class="fas fa-users text-primary"></i><span>About Us</span></a>
              </li>
          </ul>
        </aside>
      </div>

      <!-- Main Content -->
      <div class="main-content">
          <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title" id="myModalLabel">Sign Out</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <p>Apakah anda yakin ingin keluar aplikasi ?</p>
                </div>
                <div class="modal-footer">
                  <a type="button" class="btn btn-primary btn-sm pull-left text-white" data-dismiss="modal">Batal</a>
                  <a href="?page=logout" type="button" class="btn btn-danger btn-sm pull-right text-white">Sign Out</a>
                </div>
              </div>
            </div>
          </div>  

        <section class="section">
          <div class="row">
            <div class="col-12 col-sm-12 col-lg-12">
              <div class="card">
                <div class="card-header">
                  <h4>Dashboard</h4>
                </div>
                <div class="card-body">
                  <?php
                  if (isset($_GET['page'])) {
                    switch ($_GET['page']) {
                      case 'kelas':
                        include('kelas/index.php');
                        break;
                      case 'datapemilih':
                        include('pemilih/index.php');
                        break;
                      case 'datakandidat':
                        include('kandidat/index.php');
                        break;
                      case 'perolehan':
                        include('perolehan/perolehan.php');
                        break;
                      case 'about':
                        include('about/about.php');
                        break;
                      case 'pass':
                        include('pass.php');
                        break;
                      case 'logout':
                        include('logout.php');
                        break;
                      default:
                        include('welcome.php');
                        break;
                    }
                  } else {
                    include('welcome.php');
                  }
                  ?>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
      
      <footer class="main-footer">
        <div class="footer-left">
          Copyright &copy; <script>document.write(new Date().getFullYear());</script> <div class="bullet"></div> AMG E-Voting by <a href="https://siaci.net/">Siaci.net</a>
        </div>
        <div class="footer-right">
          1.0.0
        </div>
      </footer>
    </div>
  </div>

  <?php if (isset($_GET['page']) && $_GET['page'] == 'perolehan') { ?>
  <script type="text/javascript" src="../assets/js/chart-bundle.js"></script>
  <script type="text/javascript" src="../assets/js/utils.js"></script>
  <script type="text/javascript" src="../assets/js/FileSaver.min.js"></script>
  <script type="text/javascript" src="../assets/js/canvas-toBlob.js"></script>
  <?php } ?>

  <script type="text/javascript">
  $(document).ready(function() {
    $(".dropdown-toggle").click(function() {
        $(this).parent().find(".dropdown-menu").slideToggle();
    });
    $("#menu-toggle").click(function() {
        $(this).parent().find(".menu").slideToggle();
    });
  });

  $("#save-img").click(function(){
    $('#canvas').get(0).toBlob(function(blob){
        saveAs(blob, 'chart.png');
    });
  });

  function tampil(){
    $.ajax({
        url : 'ajax.php',
        data : "",
        dataType : 'html',
        type: "GET",
        success: function(data) {
          $('#data').html(data);
        }
      });
  }

  $(document).ready(function(){
      $('#periode').change(function(){
        var periode = $('#periode').val();
        $.ajax({
          url: "ajax.php",
          method: "POST",
          data: {periode : periode},
          success: function(data) {
            $('#data').html(data);
          }
        });
      });
  });

  window.onload = function(){
      tampil();
  };
  </script>

  <?php
  if (isset($_GET['page']) && $_GET['page'] == 'perolehan') {
      $thn = date('Y');
      $dpn = date('Y') + 1;
      $periode = $thn . '/' . $dpn;

      // Menggunakan PDO query aman untuk TiDB Cloud
      $stmt = $con->prepare("SELECT id_kandidat, nama_calon, foto, visi, misi, suara, periode, program, no_k FROM t_kandidat WHERE periode = ?");
      $stmt->execute([$periode]);
      $kandidat_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $label = '';
      $data = '';
      foreach ($kandidat_list as $row) {
          $label .= "'" . $row['nama_calon'] . "',";
          $data .= $row['suara'] . ',';
      }
      $labels = trim($label, ',');
      $datas  = trim($data, ',');
  ?>
  <script type="text/javascript">
  var chartData = {
    labels: [<?php echo $labels; ?>],
    datasets: [{
      type: 'bar',
      label: 'Jumlah Suara',
      borderColor: '#000',
      backgroundColor: [
        '#ffc107',
        '#dc3545',
        '#007bff',
        '#28a745',
      ],
      borderWidth: 2,
      fill: false,
      data: [<?php echo $datas; ?>]
    }]
  };

  window.onload = function(){
      var ctx = document.getElementById('canvas').getContext('2d');
      window.myMixedChart = new Chart(ctx, {
          type: 'bar',
          data: chartData,
          options: {
              responsive: true,
              title: {
                  display: true,
                  fontSize: 24,
                  text: 'Data Perolehan Suara'
              },
              tooltips: {
                  mode: 'index',
                  intersect: true
              },
              animation: {
                  onComplete: function () {
                      var chartInstance = this.chart,
                          ctx = chartInstance.ctx;
                      ctx.font = 'bold 12px Arial';
                      ctx.fillStyle = '#333';
                      ctx.textAlign = 'center';
                      ctx.textBaseline = 'bottom';

                      this.data.datasets.forEach(function (dataset, i) {
                          var meta = chartInstance.controller.getDatasetMeta(i);
                          meta.data.forEach(function (bar, index) {
                              var data = dataset.data[index];
                              ctx.fillText(data, bar._model.x, bar._model.y - 15);
                          });
                      });
                  }
              },
              scales: {
                xAxes: [{
                  ticks: { fontSize: 14 }
                }],
                yAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        fontSize: 14,
                        labelString: 'Jumlah Suara'
                    },
                    ticks: {
                        fontSize: 14,
                        min: 0
                    }
                }]
              }
          }
      });
  };
  </script>
  <?php } ?>

  <!-- General JS Scripts -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="../assets/js/jquery.nicescroll.min.js"></script>
  <script src="../assets/js/moment.min.js"></script>
  <script src="../assets/js/stisla.js"></script>

  <!-- JS Libraries -->
  <script src="../assets/node_modules/summernote/dist/summernote-bs4.js"></script>
  <script src="../assets/node_modules/chocolat/dist/js/jquery.chocolat.min.js"></script>

  <!-- Template JS File -->
  <script src="../assets/js/scripts.js"></script>
  <script src="../assets/js/custom.js"></script>
</body>
</html>
<?php ob_flush(); ?>