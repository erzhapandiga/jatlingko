<?php
// Pastikan session sudah aktif jika belum dipanggil sebelumnya
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_admin'])) {
    header('location: ./');
    exit;
}
?>
<div class="container py-4">
    <div class="row">
        <div class="col-md-12 mb-4 text-center">
            <h2 class="font-weight-bold">About Us</h2>
            <hr class="w-25">
        </div>
    </div>

    <!-- Pimpinan / Fullstack Developer -->
    <div class="row justify-content-center mb-5">
        <div class="col-md-8 text-center">
            <div id="one" style="cursor: pointer;" title="Klik untuk lihat detail">
                <img src="../images/pa.png" class="m-2 rounded-circle border border-primary shadow-sm" alt="Mulki" style="width: 150px; height: 150px; object-fit: cover;">
                <h4 class="mt-3 font-weight-bold">Mulki Anaz Aliza S.Kom</h4>
                <h5 class="text-muted">( Fullstack Developer )</h5>
            </div>
            
            <!-- Detail yang muncul saat diklik -->
            <div id="manager" style="display: none;" class="card mt-3 shadow-sm border-0 bg-light">
                <div class="card-body">
                    <table class="table table-borderless text-left mb-0">
                        <tr>
                            <th style="width: 35%;">Nama</th>
                            <td>: Mulki Anaz Aliza S.Kom</td>
                        </tr>
                        <tr>
                            <th>Jabatan</th>
                            <td>: Fullstack Developer</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>: Garut</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Tim Anggota Lainnya -->
    <div class="row text-center">
        <!-- Nasep Ependi -->
        <div class="col-md-4 mb-4">
            <div id="three" style="cursor: pointer;" title="Klik untuk lihat detail">
                <img src="../images/eek.png" class="rounded-circle border border-primary shadow-sm" alt="Nasep" style="width: 130px; height: 130px; object-fit: cover;">
                <h4 class="mt-3 font-weight-bold">Nasep Ependi</h4>
                <h5 class="text-muted font-size-sm">( Analysis System )</h5>
            </div>
            <div id="nasep" style="display: none;" class="card mt-3 shadow-sm border-0 bg-light text-left">
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th style="width: 40%;">Nama</th>
                            <td>: Nasep Ependi</td>
                        </tr>
                        <tr>
                            <th>Jabatan</th>
                            <td>: Analysis System</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>: Kp. Babakan Hantap</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Aldi Yunan Anwari -->
        <div class="col-md-4 mb-4">
            <div id="two" style="cursor: pointer;" title="Klik untuk lihat detail">
                <img src="../images/dli.png" class="rounded-circle border border-primary shadow-sm" alt="Aldi" style="width: 130px; height: 130px; object-fit: cover;">
                <h4 class="mt-3 font-weight-bold">Aldi Yunan Anwari</h4>
                <h5 class="text-muted font-size-sm">( Back End Developer )</h5>
            </div>
            <div id="aldi" style="display: none;" class="card mt-3 shadow-sm border-0 bg-light text-left">
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th style="width: 40%;">Nama</th>
                            <td>: Aldi Yunan Anwari</td>
                        </tr>
                        <tr>
                            <th>TTL</th>
                            <td>: 04 Februari 2003</td>
                        </tr>
                        <tr>
                            <th>Jabatan</th>
                            <td>: Back End Developer</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>: Kp. Rantun</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Fadlan Abdul Rojak -->
        <div class="col-md-4 mb-4">
            <div id="img" style="cursor: pointer;" title="Klik untuk lihat detail">
                <img src="../images/dlan1.png" class="rounded-circle border border-primary shadow-sm" alt="Fadlan" style="width: 130px; height: 130px; object-fit: cover;">
                <h4 class="mt-3 font-weight-bold">Fadlan Abdul Rojak</h4>
                <h5 class="text-muted font-size-sm">( Front End Developer )</h5>
            </div>
            <div id="4" style="display: none;" class="card mt-3 shadow-sm border-0 bg-light text-left">
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th style="width: 40%;">Nama</th>
                            <td>: Fadlan Abdul Rojak</td>
                        </tr>
                        <tr>
                            <th>TTL</th>
                            <td>: Garut, 09 Agustus 2003</td>
                        </tr>
                        <tr>
                            <th>Jabatan</th>
                            <td>: Front End Developer</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>: Bongkor</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery Script untuk Interaksi Klik Foto -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script> 
$(document).ready(function(){
  $("#img").click(function(){
    $("#4").fadeToggle(300);
    $("#nasep").fadeOut(300);
    $("#aldi").fadeOut(300);
    $("#manager").fadeOut(300);
  });

  $("#one").click(function(){
    $("#manager").fadeToggle(300);
    $("#nasep").fadeOut(300);
    $("#aldi").fadeOut(300);
    $("#4").fadeOut(300);
  });

  $("#two").click(function(){
    $("#aldi").fadeToggle(300);
    $("#nasep").fadeOut(300);
    $("#manager").fadeOut(300);
    $("#4").fadeOut(300);
  });

  $("#three").click(function(){
    $("#nasep").fadeToggle(300);
    $("#manager").fadeOut(300);
    $("#4").fadeOut(300);
    $("#aldi").fadeOut(300);
  });
});
</script>