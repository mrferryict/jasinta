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
      $session = session();

      // Jika user tidak login, redirect ke login
      if (!$session->get('isLoggedIn')) {
         return redirect()->to('auth/login')->with('error', 'You must log in first.');
      }

      // Periksa apakah role user sesuai dengan yang diizinkan
      $allowedRole = $arguments[0] ?? null;
      $userRoles = $session->get('roles'); // User bisa memiliki lebih dari satu role

      if (!in_array($allowedRole, $userRoles)) {
         return redirect()->to('auth/login')->with('error', 'Access denied.');
      }
   }

   public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
   {
      // Tidak perlu mengubah respons setelah request
   }
}
