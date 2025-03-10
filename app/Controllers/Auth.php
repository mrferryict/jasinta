<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\I18n\Time;

use App\Models\AssignmentModel;
use App\Models\LogModel;
use App\Models\MajorModel;
use App\Models\SemesterModel;
use App\Models\StudentModel;
use App\Models\TemporaryUserModel;
use App\Models\UserModel;


class Auth extends Controller
{
   protected $session;

   protected $assignmentModel;
   protected $logModel;
   protected $majorModel;
   protected $semesterModel;
   protected $studentModel;
   protected $temporaryUserModel;
   protected $userModel;

   public function __construct()
   {
      $this->session = session();

      $this->assignmentModel = new AssignmentModel();
      $this->logModel = new LogModel();
      $this->majorModel = new MajorModel();
      $this->semesterModel = new SemesterModel();
      $this->studentModel = new StudentModel();
      $this->temporaryUserModel = new TemporaryUserModel();
      $this->userModel = new UserModel();
   }

   public function login()
   {
      if (session()->has('isLoggedIn')) {
         // Cek peran pengguna dan arahkan sesuai
         if ('ADMIN' == session()->get('division')) {
            return redirect()->to('admin');
         } elseif ('LECTURER' == session()->get('division')) {
            return redirect()->to('lecturer');
         } elseif ('STUDENT' == session()->get('division')) {
            return redirect()->to('student');
         }
      }

      if (strtolower($this->request->getMethod()) === 'get') {
         helper('captcha');
         $captchaFileName = generate_captcha();
         $captchaURL = base_url('captcha/show/' . $captchaFileName);
         return view('auth/login', ['captcha_url' => $captchaURL]);
      }

      $email = $this->request->getPost('email');
      $password = $this->request->getPost('password');

      $validation = \Config\Services::validation();
      $validation->setRules([
         'email' => [
            'rules' => 'required|valid_email',
            'errors' => [
               'required'    => lang('App.emailMustNotEmpty'),
               'valid_email' => lang('App.emailFormatNotValid'),
            ],
         ],
         'captcha' => [
            'rules' => 'required',
            'errors' => [
               'required' => lang('App.captchaMustNotEmpty')
            ]
         ]
      ]);

      if (!$validation->withRequest($this->request)->run()) {
         return redirect()->back()->withInput()->with('error', $validation->getErrors());
      }

      // **Validasi CAPTCHA**
      $inputCaptcha = $this->request->getPost('captcha');
      $sessionCaptcha = session()->get('captcha_text');

      if ($inputCaptcha !== $sessionCaptcha) {
         return redirect()->back()->withInput()->with('error', lang('App.captchaMismatched'));
      }

      // Hapus file CAPTCHA setelah validasi
      clean_expired_captcha();

      // Cek apakah email terdaftar atas user tertentu
      $currentUser = $this->userModel
         ->select('*')
         ->where('email', $email)
         ->first();

      if (!$currentUser || !password_verify($password, $currentUser['password'])) {
         return redirect()->back()->with('error', 'Email atau password salah!');
      }

      if (!$currentUser['verified_at']) {
         return redirect()->back()->with('error', 'Akun belum aktif atau masih dalam status pending.');
      }

      // Jika currentUser adalah LECTURER, cek apakah dia punya SK aktif
      if ($currentUser['division'] == 'LECTURER') {
         $activeAssignments = $this->assignmentModel->getLecturerStudents($currentUser['id']);
         $this->session->set(['assignments' => $activeAssignments]);
      }

      // Jika current$currentUser adalah STUDENT, cek tahapan terakhirnya
      if ($currentUser['division'] == 'STUDENT') {
         $studentStage = $this->studentModel->getStudentStage($currentUser['id']);
         $this->session->set(['student_stage' => $studentStage['name'] ?? 'PENDAFTARAN']);
      }

      // Simpan data dalam session
      $this->session->set([
         'user_id'   => (int) $currentUser['id'],
         'name'      => $currentUser['name'],
         'division'  => $currentUser['division'],
         'isLoggedIn' => true
      ]);

      $this->logModel->logActivity(session()->get('user_id'), 'LOGIN', '');
      // **Redirect sesuai role utama**
      if ($currentUser['division'] == 'ADMIN') {
         return redirect()->to('admin');
      } elseif ($currentUser['division'] == 'LECTURER') {
         return redirect()->to('lecturer');
      } elseif ($currentUser['division'] == 'STUDENT') {
         return redirect()->to('student');
      }

      return redirect()->to('auth/login')->with('error', 'Role pengguna tidak dikenali!');
   }


   public function register()
   {
      if (strtolower($this->request->getMethod()) === 'get') {
         helper('captcha');
         $captchaImage = generate_captcha();
         $captchaURL = str_replace('/public', '', base_url()) . 'writable/captcha/' . $captchaImage;
         return view('auth/register', ['captcha_url' => $captchaURL]);
      }

      helper('app');

      $validation = \Config\Services::validation();
      $validation->setRules([
         'name' => [
            'rules' => 'required',
            'errors' => [
               'required' => lang('App.nameMustNotEmpty'),
            ],
         ],
         'email' => [
            'rules' => 'required|valid_email',
            'errors' => [
               'required'    => lang('App.emailMustNotEmpty'),
               'valid_email' => lang('App.emailFormatNotValid'),
            ],
         ],
         'nim' => [
            'rules' => 'required|numeric|exact_length[8]',
            'errors' => [
               'required'     => lang('App.nimMustNotEmpty'),
               'numeric'      => lang('App.nimMustBeNumbers'),
               'exact_length' => lang('App.nimMustBe8Digits'),
            ],
         ],
         'password' => [
            'label' => 'Password',
            'rules' => 'required|min_length[4]|matches[confirm_password]',
            'errors' => [
               'required' => lang('App.passwordMustNotEmpty'),
               'min_length' => lang('App.passwordMin4Digits'),
               'matches' => lang('App.passwordMismatch')
            ]
         ],
         'confirm_password' => [
            'label' => 'Konfirmasi Password',
            'rules' => 'required',
            'errors' => [
               'required' => lang('App.passwordConfirmationMustNotEmpty')
            ]
         ],
         'captcha' => [
            'rules' => 'required',
            'errors' => [
               'required' => lang('App.captchaMustNotEmpty')
            ]
         ]
      ]);

      if (!$validation->withRequest($this->request)->run()) {
         return redirect()->back()->withInput()->with('error', $validation->getErrors());
      }

      // Hapus file CAPTCHA setelah validasi
      $captchaPath = WRITEPATH . 'captcha/';
      $captchaFiles = glob($captchaPath . '*');
      foreach ($captchaFiles as $file) {
         if (is_file($file)) {
            unlink($file);
         }
      }

      $email = $this->request->getPost('email');
      $nim = $this->request->getPost('nim');
      $academicStatus = $this->request->getPost('academic_status');

      // 1. Validasi format email
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
         return redirect()->back()->withInput()->with('error', lang('App.invalidEmailFormat'));
      }

      // 2. Cek apakah email sudah ada di database
      $emailExistsInTemporaryUsers = $this->temporaryUserModel->where('email', $email)->first();
      $emailExistsInUsers = $this->userModel->where('email', $email)->first();

      if ($emailExistsInTemporaryUsers || $emailExistsInUsers) {
         return redirect()->back()->withInput()->with('error', lang('App.emailAlreadyExist'));
      }

      // 3. Tentukan apakah NIM valid?
      $majorCode = substr($nim, 2, 2);
      $major = null;
      if ($majorCode == '36') $major = 'D3/MANAJEMEN INFORMATIKA';
      elseif ($majorCode == '56') $major = 'S1/SISTEM INFORMASI';
      elseif ($majorCode == '57') $major = 'S1/TEKNIK INFORMATIKA';
      if (is_null($major)) {
         return redirect()->back()->withInput()->with('error', lang('App.invalidNIM'));
      } else {
         $majorID = (int) $this->majorModel->getMajorIDByName($major);
      }

      // 4. Cek apakah NIM sudah ada di database
      $nimExists = $this->temporaryUserModel->where('nim', $nim)->first() ||
         $this->userModel->where('number', $nim)->first();

      if ($nimExists) {
         return redirect()->back()->withInput()->with('error', lang('App.nimAlreadyExist'));
      }

      $pass = password_hash('1234', PASSWORD_DEFAULT);

      $newTempUser = [
         'name' => $this->request->getPost('name'), // Bersihkan input!
         'email' => $email,
         'nim' => $nim,
         'academic_status' => $academicStatus,
         'password' => $pass,
         'ip_address' => $this->request->getIPAddress(),
         'activation_token' => bin2hex(random_bytes(16)),
         'created_at' => Time::now()->format('Y-m-d H:i:s'),
         'expired_at' => Time::now()->addDays(1)->format('Y-m-d H:i:s')
      ];
      $newId = $this->temporaryUserModel->addTemporaryUser($newTempUser);

      if ($newId) {
         $this->logModel->logActivity(null, 'REGISTER', 'ID=' . $newId . ' (Nama:' . $this->request->getPost('name') . ', Email:' . $email . ')');
      }
      $pesan = lang('App.waitingEmailVerification');
      return redirect()->to('/auth/login')->with('success', $pesan);
   }

   /**
    * Simulasi aktivasi langsung tanpa email verification.
    */
   private function activateUserImmediately($data)
   {
      // Masukkan ke tabel users sebagai STUDENT
      return $this->userModel->insert([
         'name'         => $data['name'],
         'email'        => $data['email'],
         'password'     => $data['password'],
         'number'       => $data['nim'],
         'division'     => 'STUDENT',
         'major_id'     => $data['major_id'],
         'semester'     => $this->semesterModel->getActiveSemesterId(),
         'is_repeating' => $data['is_repeating'],
         'verified_at'  => date('Y-m-d H:i:s'),  // seolah langsung diverifikasi oleh ADMIN
      ]);
   }

   public function forgotPassword()
   {
      if (strtolower($this->request->getMethod()) === 'get') {
         return view('auth/forgot_password');
      }

      $email = $this->request->getPost('email');

      // Cari user berdasarkan email di tabel persons
      $user = $this->userModel->where('email', $email)->first();

      if (!$user) {
         return redirect()->back()->with('error', lang('App.emailNotFound'));
      }

      // Cari user yang terhubung dengan person_id
      $user = $this->userModel->where('id', $user['id'])->first();

      if (!$user) {
         return redirect()->back()->with('error', lang('App.accountNotFound'));
      }

      $token = bin2hex(random_bytes(32));
      $this->userModel->update($user['id'], [
         'token' => $token,
         'token_expired_at' => date('Y-m-d H:i:s', strtotime('+1 hour'))
      ]);
      // Kirim email reset password (dummy untuk sekarang)
      $this->logModel->logActivity(null, 'FORGET_PASSWORD', 'Email=' . $email);
      return redirect()->to('auth/login')->with('success', lang('App.checkEmailForResetPassword'));
   }


   public function resetPassword($token)
   {
      $user = $this->userModel->where('token', $token)->first();

      if (!$user || strtotime($user['token_expired_at']) < time()) {
         return redirect()->to('auth/forgot-password')->with('error', lang('App.errorToken'));
      }
      return view('auth/reset_password', ['token' => $token]);
   }

   public function updatePassword()
   {
      $token = $this->request->getPost('token');
      $newPassword = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);

      $user = $this->userModel->where('token', $token)->first();

      if (!$user) {
         return redirect()->to('auth/forgot-password')->with('error', lang('App.tokenNotValid'));
      }

      $this->userModel->update($user['id'], [
         'password' => $newPassword,
         'token' => null,
         'token_expired_at' => null
      ]);
      $this->logModel->logActivity($user['id'], 'UPDATE_PASSWORD', 'New Password:' . $newPassword);
      return redirect()->to('auth/login')->with('success', lang('App.passwordSuccessfullyChanged'));
   }

   public function verifyEmail($token)
   {
      $user = $this->userModel->where('token', $token)->first();

      if (!$user) {
         return redirect()->to('auth/login')->with('error', lang('App.tokenVerifyFailed'));
      }

      $this->userModel->update($user['id'], [
         'status' => 'active',
         'verified_at' => date('Y-m-d H:i:s'),
         'token' => null,
         'token_expired_at' => null
      ]);

      return redirect()->to('auth/login')->with('success', lang('App.accountVerified'));
   }

   public function logout()
   {
      $this->logModel->logActivity(session()->get('user_id'), 'LOGOUT', '');
      $this->session->destroy();
      return redirect()->to('auth/login')->with('success', lang('App.logoutSuccess'));
   }

   protected function sendEmail($to, $subject, $message)
   {
      $email = \Config\Services::email();
      $email->setTo($to);
      $email->setFrom('your-email@example.com', 'JASINTA');
      $email->setSubject($subject);
      $email->setMessage($message);

      return $email->send();
   }
}
