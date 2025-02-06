<?= session()->getFlashdata('error') ? '<p style="color:red;">' . session()->getFlashdata('error') . '</p>' : '' ?>
<form action="<?= base_url('login-process') ?>" method="post">
  <input type="email" name="email" placeholder="Email" required>
  <input type="password" name="password" placeholder="Password" required>
  <button type="submit">Login</button>
  <a href="<?= base_url('forgot-password') ?>">Lupa Password?</a>
  <a href="<?= base_url('register') ?>">Daftar Mahasiswa</a>
</form>