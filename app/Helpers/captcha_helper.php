<?php


// Fungsi untuk membuat gambar captcha
function create_captcha_image($text)
{
   $width = 150;
   $height = 30;

   // Buat gambar
   $image = imagecreate($width, $height);

   // Warna background
   $background = imagecolorallocate($image, 255, 255, 255);

   // Warna teks
   $text_color = imagecolorallocate($image, 0, 0, 0);

   // Tambahkan noise (garis atau titik) untuk meningkatkan keamanan
   for ($i = 0; $i < 5; $i++) {
      $noise_color = imagecolorallocate($image, rand(100, 255), rand(100, 255), rand(100, 255));
      imageline($image, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $noise_color);
   }

   // Tambahkan teks captcha ke gambar
   imagestring($image, 5, 50, 10, $text, $text_color);

   // Simpan gambar ke file
   $filename = md5($text) . '.png';
   $filenameFullPath = WRITEPATH . 'captcha/' . $filename;
   imagepng($image, $filenameFullPath);
   imagedestroy($image);

   return $filename;
}

// Fungsi untuk membuat captcha
function generate_captcha()
{
   $text = rand(1000, 9999); // Teks captcha acak
   $expiration = time() + 300; // Expiration time: 5 menit dari sekarang

   // Buat gambar captcha
   $filename = create_captcha_image($text);

   // Simpan teks captcha dan expiration time di session
   session()->set([
      'captcha_text' => (string)$text,
      'captcha_expiration' => $expiration
   ]);

   // Kembalikan path gambar captcha
   return $filename;
}

function clean_expired_captcha()
{
   $files = glob(WRITEPATH . 'captcha/*.png');
   $now = time();

   foreach ($files as $file) {
      if (filemtime($file) < ($now - 300)) { // Hapus file yang lebih lama dari 5 menit
         unlink($file);
      }
   }
}
