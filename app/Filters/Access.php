<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class Access implements FilterInterface
{
   public function before(RequestInterface $request, $arguments = null)
   {
      $session = Services::session();
      // Lanjutkan dengan logika filter
      if (!$session->get('isLoggedIn')) {
         return redirect()->to('auth/login')->with('error', 'You must log in first.');
      }

      $allowedDivision = $arguments[0] ?? null;
      $userDivision = $session->get('division');

      if ($allowedDivision != $userDivision) {
         return redirect()->to('auth/login')->with('error', lang('App.accessDenied'));
      }
   }

   public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
   {
      // Tidak perlu mengubah respons setelah request
   }
}
