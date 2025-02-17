<?php

use CodeIgniter\I18n\Time;

/**
 * Generate custom CAPTCHA
 */
function generate_captcha($config = [])
{
   $word = isset($config['word'])
      ? $config['word']
      : substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 6);

   $imgWidth = $config['img_width'] ?? 150;
   $imgHeight = $config['img_height'] ?? 50;

   // Pastikan folder ada di public/captcha/
   $captchaPath = FCPATH . 'captcha/';
   if (!is_dir($captchaPath)) {
      mkdir($captchaPath, 0777, true);
   }

   // Nama file captcha
   $filename = sha1($word . Time::now()) . '.png';
   $filepath = $captchaPath . $filename;

   // Buat gambar dengan GD Library
   // Buat gambar dengan GD Library
   $image = imagecreate($imgWidth, $imgHeight);

   // Tentukan warna background dan teks
   $bgColor = imagecolorallocate($image, 235, 235, 235); // Background putih
   $textColor = imagecolorallocate($image, 0, 0, 0); // Teks hitam

   // **Pastikan background diisi dengan warna putih**
   imagefilledrectangle($image, 0, 0, $imgWidth, $imgHeight, $bgColor);

   // Tambahkan teks ke gambar
   imagestring($image, 5, rand(10, 40), rand(10, 30), $word, $textColor);

   // Simpan gambar
   imagepng($image, $filepath);
   imagedestroy($image);

   return [
      'word' => $word,
      'image_url' => base_url('captcha/' . $filename), // URL bisa diakses di browser
      'filename' => $filename,
      'path' => $filepath
   ];
}
