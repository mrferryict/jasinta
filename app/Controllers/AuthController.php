<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
   public function login()
   {
      return view('auth/login');
   }

   public function loginProcess()
   {
      $session = session();
      $userModel = new UserModel();
      $email = $this->request->getPost('email');
      $password = $this->request->getPost('password');

      $user = $userModel->where('email', $email)->first();
      if ($user) {
         if (password_verify($password, $user['password'])) {
            $session->set([
               'id' => $user['id'],
               'name' => $user['name'],
               'email' => $user['email'],
               'role' => $user['role'],
               'logged_in' => true
            ]);
            return redirect()->to('/dashboard');
         } else {
            return redirect()->to('/login')->with('error', 'Password salah!');
         }
      } else {
         return redirect()->to('/login')->with('error', 'Email tidak ditemukan!');
      }
   }

   public function register()
   {
      return view('auth/register');
   }

   public function registerProcess()
   {
      $userModel = new UserModel();
      $data = [
         'name' => $this->request->getPost('name'),
         'email' => $this->request->getPost('email'),
         'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
         'role' => 'mahasiswa'
      ];
      $userModel->insert($data);
      return redirect()->to('/login')->with('success', 'Registrasi berhasil, silakan login.');
   }

   public function logout()
   {
      session()->destroy();
      return redirect()->to('/login');
   }

   public function forgotPassword()
   {
      return view('auth/forgot_password');
   }

   public function sendResetLink()
   {
      $userModel = new UserModel();
      $email = $this->request->getPost('email');
      $user = $userModel->where('email', $email)->first();

      if ($user) {
         $token = bin2hex(random_bytes(50));
         $userModel->update($user['id'], [
            'token' => $token,
            'token_expired_at' => date('Y-m-d H:i:s', strtotime('+1 hour'))
         ]);

         // Simulasi Kirim Email (nanti bisa gunakan library email)
         session()->setFlashdata('success', "Link reset password: http://localhost/reset-password/$token");
         return redirect()->to('/forgot-password');
      } else {
         return redirect()->to('/forgot-password')->with('error', 'Email tidak ditemukan.');
      }
   }
}
