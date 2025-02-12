<!DOCTYPE html>
<html lang="id">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Terjadi Kesalahan</title>
   <style>
      body {
         font-family: Arial, sans-serif;
         text-align: center;
         background-color: #f8f9fa;
         padding: 50px;
      }

      .container {
         background: #fff;
         padding: 20px;
         border-radius: 10px;
         box-shadow: 0px 0px 10px #ccc;
      }

      h1 {
         color: #dc3545;
      }

      p {
         font-size: 18px;
         color: #6c757d;
      }
   </style>
</head>

<body>
   <div class="container">
      <h1>Terjadi Kesalahan!</h1>
      <p><?= isset($message) ? esc($message) : 'Terjadi kesalahan pada sistem.' ?></p>
      <p><a href="<?= base_url() ?>">Kembali ke Beranda</a></p>
   </div>
</body>

</html>