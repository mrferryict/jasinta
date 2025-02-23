<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Captcha extends Controller
{
   public function show($filename)
   {
      $path = WRITEPATH . 'captcha/' . $filename;

      if (!file_exists($path)) {
         throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
      }

      // Set header agar tidak bisa di-cache
      $this->response->setHeader('Content-Type', mime_content_type($path));
      $this->response->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
      return $this->response->setBody(file_get_contents($path));
   }
}
