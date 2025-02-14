<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PersonModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
   protected $userModel;
   protected $personModel;
   protected $session;

   public function __construct()
   {
      $this->userModel = new UserModel();
      $this->personModel = new PersonModel();
      $this->session = session();
   }

   public function login()
   {
      if (session()->has('isLoggedIn')) {
         // Cek peran pengguna dan arahkan sesuai
         if (in_array('ADMINISTRATOR', session()->get('roles'))) {
            return redirect()->to('admin');
         } elseif (in_array('LECTURER', session()->get('roles'))) {
            return redirect()->to('lecturer');
         } elseif (in_array('STUDENT', session()->get('roles'))) {
            return redirect()->to('student');
         }
      }

      if (strtolower($this->request->getMethod()) === 'get') {
         return view('auth/login');
      }

      $email = $this->request->getPost('email');
      $password = $this->request->getPost('password');

      // Join users dengan persons untuk mendapatkan informasi lengkap
      $user = $this->userModel
         ->select('users.*, persons.name, persons.division')
         ->join('persons', 'persons.id = users.person_id')
         ->where('persons.email', $email)
         ->first();

      if (!$user || !password_verify($password, $user['password'])) {
         return redirect()->back()->with('error', 'Email atau password salah!');
      }

      if ($user['status'] !== 'ACTIVE') {
         return redirect()->back()->with('error', 'Akun belum aktif atau masih dalam status pending.');
      }

      // Ambil semua role user dari tabel user_roles
      $roles = $this->userModel
         ->select('roles.name')
         ->join('user_roles', 'user_roles.user_id = users.id')
         ->join('roles', 'roles.id = user_roles.role_id')
         ->where('users.id', $user['id'])
         ->findAll();

      // Konversi hasil roles menjadi array
      $userRoles = array_column($roles, 'name');

      // Jika user adalah dosen, cek apakah dia punya SK aktif
      if (in_array('LECTURER', $userRoles)) {
         $appointmentModel = new \App\Models\AppointmentModel();
         $activeAppointments = $appointmentModel->getActiveAppointments($user['id']);

         $this->session->set(['appointments' => $activeAppointments]);
      }

      // Jika user adalah mahasiswa, cek tahapan terakhirnya
      if (in_array('STUDENT', $userRoles)) {
         $progressModel = new \App\Models\ProgressModel();
         $studentStage = $progressModel->getStudentStage($user['id']);

         $this->session->set(['student_stage' => $studentStage['name'] ?? 'PENDAFTARAN']);
      }

      // Simpan data dalam session
      $this->session->set([
         'user_id'   => $user['id'],
         'name'      => $user['name'],
         'division'  => $user['division'],
         'roles'     => $userRoles, // Bisa memiliki lebih dari satu role
         'isLoggedIn' => true
      ]);

      // **Redirect sesuai role utama**
      if (in_array('ADMIN', $userRoles)) {
         return redirect()->to('admin');
      } elseif (in_array('LECTURER', $userRoles)) {
         return redirect()->to('lecturer');
      } elseif (in_array('STUDENT', $userRoles)) {
         return redirect()->to('student');
      }

      return redirect()->to('auth/login')->with('error', 'Role pengguna tidak dikenali!');
   }


   public function register()
   {
      if (strtolower($this->request->getMethod()) === 'get') {
         return view('auth/register');
      }

      $email = $this->request->getPost('email');
      $nim = $this->request->getPost('number');

      // Cek apakah email sudah ada di tabel persons
      $existingPerson = $this->personModel->where('email', $email)->first();
      if ($existingPerson) {
         return redirect()->back()->withInput()->with('error', 'Email sudah terdaftar. Silakan gunakan email lain.');
      }

      // Cek apakah NIM sudah ada di tabel persons
      $existingNim = $this->personModel->where('number', $nim)->first();
      if ($existingNim) {
         return redirect()->back()->withInput()->with('error', 'NIM sudah terdaftar. Silakan gunakan NIM lain.');
      }

      // Data yang akan dimasukkan ke tabel persons
      $data = [
         'name' => $this->request->getPost('name'),
         'email' => $email,
         'number' => $nim, // NIM
         'major_id' => $this->request->getPost('major_id'),
         'division' => 'mahasiswa',
         'semester_id' => $this->request->getPost('semester_id'),
         'created_at' => date('Y-m-d H:i:s')
      ];

      $personId = $this->personModel->insert($data);

      // Data yang akan dimasukkan ke tabel users
      $userData = [
         'person_id' => $personId,
         'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
         'status' => 'pending',
         'registered_ip' => $this->request->getIPAddress(),
         'token' => bin2hex(random_bytes(32)),
         'token_expired_at' => date('Y-m-d H:i:s', strtotime('+1 day'))
      ];

      $this->userModel->insert($userData);

      return redirect()->to('auth/login')->with('success', 'Registrasi berhasil! Silakan cek email untuk verifikasi.');
   }


   public function forgotPassword()
   {
      if (strtolower($this->request->getMethod()) === 'get') {
         return view('auth/forgot_password');
      }

      $email = $this->request->getPost('email');

      // Cari user berdasarkan email di tabel persons
      $person = $this->personModel->where('email', $email)->first();

      if (!$person) {
         return redirect()->back()->with('error', 'Email tidak ditemukan!');
      }

      // Cari user yang terhubung dengan person_id
      $user = $this->userModel->where('person_id', $person['id'])->first();

      if (!$user) {
         return redirect()->back()->with('error', 'Akun tidak ditemukan atau belum terdaftar.');
      }

      $token = bin2hex(random_bytes(32));
      $this->userModel->update($user['id'], [
         'token' => $token,
         'token_expired_at' => date('Y-m-d H:i:s', strtotime('+1 hour'))
      ]);
      // Kirim email reset password (dummy untuk sekarang)
      return redirect()->to('auth/login')->with('success', 'Silakan cek email untuk reset password.');
   }


   public function resetPassword($token)
   {
      $user = $this->userModel->where('token', $token)->first();

      if (!$user || strtotime($user['token_expired_at']) < time()) {
         return redirect()->to('auth/forgot-password')->with('error', 'Token tidak valid atau sudah kedaluwarsa!');
      }
      return view('auth/reset_password', ['token' => $token]);
   }

   public function updatePassword()
   {
      $token = $this->request->getPost('token');
      $newPassword = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);

      $user = $this->userModel->where('token', $token)->first();

      if (!$user) {
         return redirect()->to('auth/forgot-password')->with('error', 'Token tidak valid!');
      }

      $this->userModel->update($user['id'], [
         'password' => $newPassword,
         'token' => null,
         'token_expired_at' => null
      ]);

      return redirect()->to('auth/login')->with('success', 'Password berhasil diubah! Silakan login kembali.');
   }

   public function verifyEmail($token)
   {
      $user = $this->userModel->where('token', $token)->first();

      if (!$user) {
         return redirect()->to('auth/login')->with('error', 'Token verifikasi tidak valid!');
      }

      $this->userModel->update($user['id'], [
         'status' => 'active',
         'verified_at' => date('Y-m-d H:i:s'),
         'token' => null,
         'token_expired_at' => null
      ]);

      return redirect()->to('auth/login')->with('success', 'Akun berhasil diverifikasi! Silakan login.');
   }

   public function logout()
   {
      $this->session->destroy();
      return redirect()->to('auth/login')->with('success', 'Anda telah logout.');
   }

   protected function sendEmail($to, $subject, $message)
   {
      $email = \Config\Services::email();
      $email->setTo($to);
      $email->setFrom('your-email@example.com', 'Sistem Tugas Akhir');
      $email->setSubject($subject);
      $email->setMessage($message);

      return $email->send();
   }
}
