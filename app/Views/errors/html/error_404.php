<!DOCTYPE html>
<html lang="id">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>404 - Halaman Tidak Ditemukan</title>
   <style>
      body {
         font-family: Arial, sans-serif;
         text-align: center;
         background-color: #f8f9fa;
      }

      .container {
         margin-top: 10%;
      }

      h1 {
         font-size: 50px;
         color: #dc3545;
      }

      p {
         font-size: 20px;
         color: #6c757d;
      }

      a {
         text-decoration: none;
         color: #007bff;
         font-weight: bold;
      }

      a:hover {
         text-decoration: underline;
      }
   </style>
</head>

<body>
   <div class="container">
      <h1>404</h1>
      <p>Oops! Halaman yang Anda cari tidak ditemukan.</p>
      <p><a href="<?= base_url() ?>">Kembali ke Beranda</a></p>
   </div>
</body>

</html>