<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Config\Services;

class AuthFilter implements FilterInterface
{
   public function before(RequestInterface $request, $arguments = null)
   {
      $session = Services::session();

      // Debug: Cetak argumen yang diterima
      echo 'Arguments received:';
      var_dump($arguments);
      echo '<br>';

      // Debug: Cetak session roles
      echo 'Session roles:';
      var_dump($session->get('roles'));
      echo '<br>';
      die;

      // Lanjutkan dengan logika filter
      if (!$session->get('isLoggedIn')) {
         return redirect()->to('auth/login')->with('error', 'You must log in first.');
      }

      $allowedRole = $arguments[0] ?? null;
      $userRoles = $session->get('roles');

      if (!in_array($allowedRole, $userRoles)) {
         return redirect()->to('auth/login')->with('error', 'Access denied.');
      }
   }

   public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
   {
      // Tidak perlu mengubah respons setelah request
   }
}
