<?= session()->getFlashdata('success') ? '<p style="color:green;">' . session()->getFlashdata('success') . '</p>' : '' ?>
<form action="<?= base_url('send-reset-link') ?>" method="post">
   <input type="email" name="email" placeholder="Masukkan Email Anda" required>
   <button type="submit">Kirim Link Reset</button>
</form>