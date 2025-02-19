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
                  <form action="<?= base_url('auth/register') ?>" method="post" id="mainForm">

                     <form action="<?= base_url('auth/register') ?>" method="post" id="mainForm">
                        <!-- ✅ FULL NAME -->
                        <div class="form-group form-control-md field--not-empty">
                           <label for="name"><?= lang('App.fullName') ?></label>
                           <input type="text" name="name" class="form-control" id="name" required autofocus
                              value="<?= old('name') ?>"
                              oninput="this.value = this.value.toUpperCase()">
                        </div>

                        <!-- ✅ EMAIL -->
                        <div class="form-group form-control-md field--not-empty">
                           <label for="email" style="display: flex; justify-content: space-between; align-items: center; gap: 10px;">
                              <span><?= lang('App.email') ?></span>
                              <span id="emailError" class="text-danger text-sm-center" style="display: none;">Format email tidak valid!</span>
                           </label>
                           <input type="email" name="email" class="form-control" id="email" required
                              value="<?= old('email') ?>"
                              oninput="this.value = this.value.toLowerCase(); validateEmail()">
                        </div>

                        <!-- ✅ NIM -->
                        <div class="form-group form-control-md field--not-empty">
                           <label for="nim" style="display: flex; justify-content: space-between; align-items: center; gap: 10px;">
                              <span>NIM | 8 digits</span>
                              <span id="nimError" class="text-danger text-sm-center" style="display: none;">Digit ke-3 & ke-4 harus: 36/56/57!</span>
                           </label>
                           <input type="text" name="nim" class="form-control" id="nim" required maxlength="8"
                              value="<?= old('nim') ?>"
                              oninput="this.value = this.value.replace(/[^0-9]/g, ''); validateNIM()">
                        </div>

                        <!-- ✅ REGISTRATION TYPE -->
                        <div class="form-group field--not-empty">
                           <label for="is_repeating"><?= lang('App.registrationType') ?></label>
                           <select name="is_repeating" class="form-control">
                              <option value="1" <?= old('is_repeating') == '1' ? 'selected' : '' ?>><?= lang('App.newRegistration') ?></option>
                              <option value="0" <?= old('is_repeating') == '0' ? 'selected' : '' ?>><?= lang('App.repeatRegistration') ?></option>
                           </select>
                        </div>

                        <!-- ✅ PASSWORD & CONFIRM PASSWORD -->
                        <div class="form-group form-control-md field--not-empty">
                           <label for="password" style="display: flex; justify-content: space-between; align-items: center; gap: 10px;">
                              <span><?= lang('App.password') ?></span>
                              <span id="passwordLengthError" class="text-danger text-sm-center" style="display: none;">Password minimal 4 karakter!</span>
                           </label>
                           <input type="password" name="password" class="form-control" id="password" required oninput="validatePassword()">
                        </div>

                        <div class="form-group form-control-md field--not-empty">
                           <label for="confirm_password" style="display: flex; justify-content: space-between; align-items: center; gap: 10px;">
                              <span><?= lang('App.confirmPassword') ?></span>
                              <span id="passwordError" class="text-danger text-sm-center" style="display: none;">Konfirmasi Password tidak cocok!</span>
                           </label>
                           <input type="password" name="confirm_password" class="form-control" id="confirm_password" required oninput="validatePassword()">
                        </div>

                        <!-- ✅ CAPTCHA -->
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

   <script>
      document.addEventListener('DOMContentLoaded', function() {
         let form = document.getElementById('mainForm');

         // ✅ Validasi Email
         function validateEmail() {
            let emailField = document.getElementById('email');
            let emailError = document.getElementById('emailError');
            let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

            if (!emailPattern.test(emailField.value)) {
               emailError.style.display = 'block';
            } else {
               emailError.style.display = 'none';
            }
         }
         document.getElementById('email').addEventListener('input', validateEmail);

         // ✅ Validasi NIM
         function validateNIM() {
            let nim = document.getElementById('nim').value;
            let nimError = document.getElementById('nimError');
            let validPrefixes = ["36", "56", "57"];
            let prefix = nim.substring(2, 4);

            if (nim.length >= 4 && !validPrefixes.includes(prefix)) {
               nimError.style.display = 'block';
            } else {
               nimError.style.display = 'none';
            }
         }
         document.getElementById('nim').addEventListener('input', validateNIM);

         // ✅ Validasi Password
         function validatePassword() {
            let password = document.getElementById('password').value;
            let confirmPassword = document.getElementById('confirm_password').value;
            let passwordError = document.getElementById('passwordError');
            let passwordLengthError = document.getElementById('passwordLengthError');

            if (password.length < 4) {
               passwordLengthError.style.display = 'block';
            } else {
               passwordLengthError.style.display = 'none';
            }

            if (confirmPassword !== password) {
               passwordError.style.display = 'block';
            } else {
               passwordError.style.display = 'none';
            }
         }
         document.getElementById('password').addEventListener('input', validatePassword);
         document.getElementById('confirm_password').addEventListener('input', validatePassword);

         // ✅ Validasi Saat Submit Form
         form.addEventListener('submit', function(event) {
            let emailField = document.getElementById('email');
            let nim = document.getElementById('nim').value;
            let prefix = nim.substring(2, 4);
            let password = document.getElementById('password').value;
            let confirmPassword = document.getElementById('confirm_password').value;
            let validPrefixes = ["36", "56", "57"];
            let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            let isValid = true;

            if (!emailPattern.test(emailField.value)) {
               document.getElementById('emailError').style.display = 'block';
               isValid = false;
            }

            if (!validPrefixes.includes(prefix)) {
               document.getElementById('nimError').style.display = 'block';
               isValid = false;
            }

            if (password.length < 4) {
               document.getElementById('passwordLengthError').style.display = 'block';
               isValid = false;
            }

            if (confirmPassword !== password) {
               document.getElementById('passwordError').style.display = 'block';
               isValid = false;
            }

            if (!isValid) {
               event.preventDefault(); // ❌ Cegah form dikirim jika ada error
            }
         });
      });
   </script>


</body>

</html>