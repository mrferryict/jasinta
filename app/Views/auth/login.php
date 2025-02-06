<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="login-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <h1 class="mb-0"><?= $setting['app_name'] ?></h1>
    </div>
    <div class="card-body login-card-body">
      <p class="login-box-msg"><?= lang('App.pleaseLogin') ?></p>
      <form action="<?= base_url('login-process') ?>" method="post">
        <div class="input-group mb-1">
          <div class="form-floating">
            <input id="loginEmail" type="email" class="form-control" value="" placeholder="" />
            <label for="loginEmail"><?= lang('App.email') ?></label>
          </div>
          <div class="input-group-text"><span class="bi bi-envelope"></span></div>
        </div>
        <div class="input-group mb-1">
          <div class="form-floating">
            <input id="loginPassword" type="password" class="form-control" placeholder="" />
            <label for="loginPassword"><?= lang('App.password') ?></label>
          </div>
          <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
        </div>
        <!--begin::Row-->
        <div class="row justify-content-center">
          <div class="col-4 text-center">
            <button type="submit" class="btn btn-primary"><?= lang('App.login') ?></button>
          </div>
        </div>

        <!--end::Row-->
      </form>

      <p class="mb-1"><a href="forgot-password.html"><?= lang('App.forgotPassword') ?></a></p>
      <p class="mb-0">
        <a href="register.html" class="text-center"><?= lang('App.newRegister') ?></a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->
<?= $this->endSection() ?>