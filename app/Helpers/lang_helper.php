<?php

if (!function_exists('langUppercase')) {
   function langUppercase(string $line, array $args = [], ?string $locale = null)
   {
      /** @var \CodeIgniter\Language\Language $language */
      $language = service('language');

      // Simpan locale yang sedang aktif
      $activeLocale = $language->getLocale();

      // Jika locale berbeda, ubah sementara
      if (!empty($locale) && $locale !== $activeLocale) {
         $language->setLocale($locale);
      }

      // Ambil teks berdasarkan key
      $text = $language->getLine($line, $args);

      // Kembalikan locale ke semula jika diubah sementara
      if (!empty($locale) && $locale !== $activeLocale) {
         $language->setLocale($activeLocale);
      }

      return strtoupper($text);
   }
}
