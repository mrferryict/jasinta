<?php

if (!function_exists('lang')) {
   function lang(string $key)
   {
      $session = session();
      $locale = $session->get('language') ?? 'en'; // Default bahasa Inggris
      $langFile = APPPATH . "Language/$locale.php";

      if (file_exists($langFile)) {
         $lang = include($langFile);
         return $lang[$key] ?? $key; // Jika tidak ditemukan, tampilkan key-nya
      }

      return $key; // Jika file bahasa tidak ditemukan, kembalikan key asli
   }
}
