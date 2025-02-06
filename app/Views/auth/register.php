<?= session()->getFlashdata('success') ? '<p style="color:green;">' . session()->getFlashdata('success') . '</p>' : '' ?>
<form action="<?= base_url('register-process') ?>" method="post">
  <input type="text" name="name" placeholder="Nama Lengkap" required>
  <input type="email" name="email" placeholder="Email" required>
  <input type="password" name="password" placeholder="Password" required>
  <button type="submit">Daftar</button>
</form>