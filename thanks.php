<?php
defined('BASEPATH') or die("No Access Allowed");
?>
<style>
         body::before {
          content: "";
          display: block;
          position: absolute;
          z-index: -1;
          width: 100%;
          height: 100%;
          top: 0;
          left: 0;
          /* Background Dark Blue Gradient */
          background: #0f172a;
          background: -webkit-linear-gradient(top, #0f172a, #1e3a8a);
          background: -o-linear-gradient(top, #0f172a, #1e3a8a);
          background: -moz-linear-gradient(top, #0f172a, #1e3a8a);
          background: linear-gradient(top, #0f172a, #1e3a8a);
          opacity: 0.95; 
         }
         body{
          background-image : url('images/waw.jpg');
          background-size: cover;
          width: 100vw;  
          min-height: 100vh;
          display: -webkit-box;
          display: -webkit-flex;
          display: -moz-box;
          display: -ms-flexbox;
          display: flex;
          flex-wrap: wrap;
          justify-content: center;
          align-items: center;
          padding: 15px;
          background-repeat: no-repeat;
          background-size: cover;
          background-position: center;
          position: relative;
          z-index: 1;
          margin: 0;
        }
        
        .img-thanks {
          width: 140px;
          height: auto;
          filter: drop-shadow(0 10px 15px rgba(0, 0, 0, 0.4));
          transition: transform 0.3s ease;
        }

        .img-thanks:hover {
          transform: scale(1.05);
        }
</style>
<link rel="icon" href="images/almadani.png">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

<div class="text-center" style="padding-top:20px;padding-bottom:40px;">
   <div style="padding-bottom:15px">
      <img src="./assets/img/osis.png" class="img-thanks" alt="Logo OSIS" >
   </div>
   <h1 class="text-white abcd font-weight-bold" style="font-size:36px; text-shadow: 0 2px 10px rgba(0,0,0,0.3);">Terima kasih atas partisipasi anda</h1>
   <p class="text-light abcd" style="font-size:18px; opacity: 0.85; margin-bottom: 25px;">Suara yang anda berikan menentukan masa depan sekolah</p>
   
   <a href="./" class="btn btn-light btn-lg rounded-pill shadow font-weight-bold" style="padding-left:45px; padding-right:45px; padding-bottom:12px; padding-top:12px; font-size:18px; color: #1e3a8a; transition: all 0.3s ease;"> Back To Login </a>
</div>