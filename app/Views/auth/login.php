<!doctype html>
<html lang="id">

<head>
   <!-- Required meta tags -->
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">

   <link rel="stylesheet" href="<?= f_login() ?>fonts/icomoon/style.css">
   <link rel="stylesheet" href="<?= f_login() ?>css/owl.carousel.min.css">
   <link rel="stylesheet" href="<?= f_login() ?>css/bootstrap.min.css">
   <link rel="stylesheet" href="<?= f_login() ?>css/style.css">

   <title>Login</title>
</head>

<body>
   <div class="d-lg-flex half">
      <div class="bg order-1 order-md-2" style="background-image: url(<?= f_images('welcome.jpg') ?>);"></div>
      <div class="contents order-2 order-md-1">
         <div class="container">
            <div class="row align-items-center justify-content-center">
               <div class="col-md-7">
                  <div class="d-flex align-items-center mt-4 mb-1">
                     <img src="<?= f_images('logo_jasinta.png') ?>" alt="Logo Kiri" class="mr-3" style="height: 80px;">
                     <h3 class="mb-0 ml-auto fw-bold" style="font-family: 'Times New Roman', Times, serif;">
                        <?= lang('App.name') ?></h3>
                     <img src="<?= f_images('logo_stmik.png') ?>" alt="Logo Kanan" class="ml-auto" style="height: 90px;">
                  </div>
                  <div class="mt-1 mb-1 text-center">
                     <h6 class="fw-bold" style="font-family: 'Times New Roman', Times, serif;">
                        <?= lang('App.JASINTA') ?></h6>
                  </div>
                  <div class="mb-1 mt-5 text-center">
                     <h6><?= lang('App.pleaseLogin') ?></h6>
                  </div>

                  <!-- Tampilkan notifikasi pesan sukses atau error -->
                  <?php if (session()->getFlashdata('error')): ?>
                     <div class="alert alert-danger"> <?= session()->getFlashdata('error'); ?> </div>
                  <?php endif; ?>
                  <?php if (session()->getFlashdata('success')): ?>
                     <div class="alert alert-success"> <?= session()->getFlashdata('success'); ?> </div>
                  <?php endif; ?>

                  <form action="<?= base_url('auth/login') ?>" method="post">
                     <div class="form-group first">
                        <label for="email"><?= lang('App.email') ?></label>
                        <input type="email" name="email" class="form-control" id="email" required>
                     </div>
                     <div class="form-group last mb-3">
                        <label for="password"><?= lang('App.password') ?></label>
                        <input type="password" name="password" class="form-control" id="password" required>
                     </div>
                     <div class="d-flex mb-5 align-items-center">
                        <span><a href="<?= base_url('auth/forgot-password') ?>"><?= lang('App.forgotPassword') ?></a></span>
                        <span class="ml-auto"><a href="<?= base_url('auth/register') ?>"><?= lang('App.newRegistration') ?></a></span>
                     </div>
                     <input type="submit" value="<?= lang('App.login') ?>" class="btn btn-block btn-primary">
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
   <script src="<?= f_login() ?>js/jquery-3.3.1.min.js"></script>
   <script src="<?= f_login() ?>js/popper.min.js"></script>
   <script src="<?= f_login() ?>js/bootstrap.min.js"></script>
   <script src="<?= f_login() ?>js/main.js"></script>
</body>

</html>