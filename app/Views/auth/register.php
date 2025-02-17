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

   <title><?= lang('App.newRegistration') ?></title>
</head>

<body>
   <div class="d-lg-flex half">
      <div class="bg order-1 order-md-2" style="background-image: url(<?= f_images('welcome.jpg') ?>);"></div>
      <div class="contents order-2 order-md-1">
         <div class="container">
            <div class="row align-items-center justify-content-center">
               <div class="col-md-7">
                  <!-- Bagian Judul dengan Logo -->
                  <div class="d-flex align-items-center mt-4 mb-1">
                     <img src="<?= f_images('logo_jasinta.png') ?>" alt="Logo Kiri" class="mr-3" style="height: 80px;">
                     <h3 class="mb-0 ml-auto fw-bold" style="font-family: 'Times New Roman', Times, serif;">
                        <?= lang('App.name') ?>
                     </h3>
                     <img src="<?= f_images('logo_stmik.png') ?>" alt="Logo Kanan" class="ml-auto" style="height: 90px;">
                  </div>
                  <div class="mt-1 mb-1 text-center">
                     <h6 class="fw-bold" style="font-family: 'Times New Roman', Times, serif;">
                        <?= lang('App.JASINTA') ?>
                     </h6>
                  </div>
                  <div class="mb-1 mt-5 text-center">
                     <h6><?= lang('App.newRegistration') ?></h6>
                  </div>

                  <!-- Tampilkan notifikasi pesan sukses atau error -->
                  <?php if (session()->getFlashdata('error')): ?>
                     <?php if (session()->getFlashdata('error')) : ?>
                        <div class="alert alert-danger">
                           <?= esc(implode('<br>', (array) session()->getFlashdata('error'))); ?>
                        </div>
                     <?php endif; ?>

                  <?php endif; ?>
                  <?php if (session()->getFlashdata('success')): ?>
                     <div class="alert alert-success"> <?= session()->getFlashdata('success'); ?> </div>
                  <?php endif; ?>

                  <!-- Form Pendaftaran Mahasiswa Baru -->
                  <form action="<?= base_url('auth/register') ?>" method="post">
                     <div class="form-group form-control-md">
                        <label for="name"><?= lang('App.fullName') ?></label>
                        <input type="text" name="name" class="form-control" id="name" required autofocus>
                     </div>

                     <div class="form-group form-control-md">
                        <label for="email"><?= lang('App.email') ?></label>
                        <input type="email" name="email" class="form-control" id="email" required>
                     </div>

                     <div class="form-group form-control-md">
                        <label for="nim"><?= lang('App.nim') ?></label>
                        <input type="text" name="nim" class="form-control" id="nim" required>
                     </div>

                     <div class="form-group field--not-empty">
                        <label for="is_repeating"><?= lang('App.registrationType') ?></label>
                        <select name="is_repeating" class="form-control">
                           <option value="1" <?= set_select('is_repeating', '0', true) ?>><?= lang('App.newRegistration') ?></option>
                           <option value="0" <?= set_select('is_repeating', '1') ?>><?= lang('App.repeatRegistration') ?></option>
                        </select>
                     </div>

                     <div class="form-group form-control-md">
                        <label for="password"><?= lang('App.password') ?></label>
                        <input type="password" name="password" class="form-control" id="password" required>
                     </div>

                     <div class="form-group form-control-md">
                        <label for="confirm_password"><?= lang('App.confirmPassword') ?></label>
                        <input type="password" name="confirm_password" class="form-control" id="confirm_password" required>
                     </div>
                     <div class="form-group mb-3 last form-control-md">
                        <img src="<?= esc($captcha['image_url']) ?>" alt="CAPTCHA Image" class="mb-3">
                        <label class="mt-2"><?= lang('App.enterCaptcha'); ?></label>
                        <input type="text" name="captcha" class="form-control" required>
                     </div>

                     <input type="submit" value="<?= lang('App.registerNow') ?>" class="btn btn-block btn-primary">
                  </form>
                  <div>
                     <p class="mt-2 text-center"><a href="<?= base_url('auth/login') ?>"><?= lang('App.backToLogin') ?></a></p>
                  </div>
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