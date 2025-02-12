<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
   public function before(RequestInterface $request, $arguments = null)
   {
      $session = session();
      if (!$session->has('user_id')) {
         return redirect()->to('/auth/login')->with('error', 'Silakan login terlebih dahulu.');
      }
   }

   public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
   {
      // Tidak ada tindakan setelah request diproses
   }
}
