<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>JASINTA - STMIK Jayakarta - Sistem Informasi Tugas Akhir</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      background: linear-gradient(135deg, #a8e6cf, #dcedc1, #f0f8ff, #d4efdf, #b2dfdb, #a8e6cf);
      background-size: 300% 300%;
      animation: gradientBG 6s ease infinite;
      text-align: center;
      color: green;
    }

    @keyframes gradientBG {
      0% {
        background-position: 0% 50%;
      }

      50% {
        background-position: 100% 50%;
      }

      100% {
        background-position: 0% 50%;
      }
    }

    img {
      max-width: 50%;
      height: auto;
      border-radius: 15px;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    button {
      margin: 5px;
    }

    button a {
      color: white !important;
    }
  </style>
</head>

<body>
  <div class="container text-center">
    <h1>SISTEM INFORMASI MONITORING TUGAS AKHIR</h1>
    <h3>STMIK JAYAKARTA JAKARTA</h3>
    <img src="<?= f_images('welcome.jpg') ?>" style="height: 60vh;" alt="STMIK Jayakarta">
    <p><button type="button" class="btn btn-success"><a href="<?= base_url('login') ?>">START</a></button></p>
  </div>
</body>

</html>