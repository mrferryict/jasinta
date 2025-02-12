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

      pre {
         text-align: left;
         background: #f8d7da;
         padding: 10px;
         border-radius: 5px;
         overflow: auto;
      }
   </style>
</head>

<body>
   <div class="container">
      <h1>Terjadi Kesalahan!</h1>
      <p><?= isset($message) ? esc($message) : 'Kesalahan tidak diketahui.' ?></p>

      <?php if (ENVIRONMENT === 'development'): ?>
         <h2>Detail Error:</h2>
         <pre><?= esc($exception->getMessage() ?? 'No error message') ?></pre>
         <h3>File:</h3>
         <pre><?= esc($exception->getFile()) ?> di baris <?= esc($exception->getLine()) ?></pre>
         <h3>Trace:</h3>
         <pre><?= esc($exception->getTraceAsString()) ?></pre>
      <?php endif; ?>

      <p><a href="<?= base_url() ?>">Kembali ke Beranda</a></p>
   </div>
</body>

</html>