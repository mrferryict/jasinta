<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PersonModel;
use App\Models\TemporaryUserModel;
use App\Models\MajorModel;

use CodeIgniter\Controller;
use CodeIgniter\I18n\Time;

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
         helper('captcha');
         // **Generate CAPTCHA**
         $captchaConfig = [
            'word'       => substr(bin2hex(random_bytes(3)), 0, 6), // String acak aman
            'img_path'   => WRITEPATH . 'captcha/',
            'img_url'    => base_url('writable/captcha/'),
            'font_path'  => '',
            'img_width'  => 150,
            'img_height' => 50,
            'expiration' => 300,
            'word_length' => 6,
            'pool'       => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'colors'     => [
               'background' => [255, 255, 255],
               'border' => [255, 255, 255],
               'text' => [0, 0, 0],
               'grid' => [255, 40, 40]
            ]
         ];

         $captcha = generate_captcha($captchaConfig);
         session()->set('captcha_text', $captcha['word']);

         return view('auth/login', ['captcha' => $captcha]);
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

      // Join users dengan persons untuk mendapatkan informasi lengkap
      $user = $this->userModel
         ->select('users.*, persons.name, persons.division')
         ->join('persons', 'persons.id = users.person_id')
         ->where('persons.email', $email)
         ->first();

      if (!$user || !password_verify($password, $user['password'])) {
         return redirect()->back()->with('error', 'Email atau password salah!');
      }

      if (!$user['verified_at']) {
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
         helper('captcha');
         // **Generate CAPTCHA**
         $captchaConfig = [
            'word'       => bin2hex(random_bytes(3)), // Alternatif untuk random_string()
            'img_path'   => FCPATH . 'captcha/',  // Simpan di folder 'public/captcha/'
            'img_url'    => base_url('captcha/'), // URL yang bisa diakses oleh browser
            'font_path'  => '',
            'img_width'  => 150,
            'img_height' => 50,
            'expiration' => 300
         ];
         $captcha = generate_captcha($captchaConfig);
         session()->set('captcha_text', $captcha['word']);

         return view('auth/register', ['captcha' => $captcha]);
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

      $temporaryUserModel = new TemporaryUserModel();
      $personModel = new PersonModel();

      $email         = $this->request->getPost('email');
      $nim           = $this->request->getPost('nim');
      $is_repeating  = (int)$this->request->getPost('is_repeating');

      // 1. Validasi format email
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
         return redirect()->back()->withInput()->with('error', lang('App.invalidEmailFormat'));
      }

      // 2. Cek apakah email sudah ada di database
      $emailExistsInTemporaryUsers = $temporaryUserModel->where('email', $email)->first();
      $emailExistsInPersons = $personModel->where('email', $email)->first();

      if ($emailExistsInTemporaryUsers || $emailExistsInPersons) {
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
         $majorModel = new MajorModel();
         $majorID = (int) $majorModel->getMajorIDByName($major);
      }

      // 4. Cek apakah NIM sudah ada di database
      $nimExists = $temporaryUserModel->where('nim', $nim)->first() ||
         $personModel->where('number', $nim)->first();

      if ($nimExists) {
         return redirect()->back()->withInput()->with('error', lang('App.nimAlreadyExist'));
      }

      $pass = password_hash('1234', PASSWORD_DEFAULT);

      $newTempUser = [
         'name' => $this->request->getPost('name'), // Bersihkan input!
         'email' => $email,
         'nim' => $nim,
         'is_repeating' => $is_repeating,
         'password' => $pass,
         'ip_address' => $this->request->getIPAddress(),
         'activation_token' => bin2hex(random_bytes(16)),
         'created_at' => Time::now()->format('Y-m-d H:i:s'),
         'expired_at' => Time::now()->addDays(1)->format('Y-m-d H:i:s')
      ];
      $temporaryUserModel->insert($newTempUser);

      // Mahasiswa Langsung Terverifikasi (langsung simpan ke person dan user)
      $dataPersonUser = [
         'name'         => $this->request->getPost('name'),
         'email'        => $email,
         'nim'          => $nim,
         'major_id'     => $majorID,
         'password'     => $pass,
         'ip_address'   => getUserIpAddress(),
         'is_repeating' => $is_repeating
      ];
      $this->activateUserImmediately($dataPersonUser);
      $pesan = lang('App.registrationSucceed') . '. ' . lang('App.waitingAdminApproval');
      return redirect()->to('/auth/login')->with('success', $pesan);
   }

   /**
    * Simulasi aktivasi langsung tanpa email verification.
    */
   private function activateUserImmediately($data)
   {
      $personModel = new PersonModel();

      // Masukkan ke tabel persons sebagai STUDENT
      $newInsertID = $personModel->insert([
         'name'       => $data['name'],
         'email'      => $data['email'],
         'number'     => $data['nim'],
         'role'       => 'STUDENT',
         'major_id'   => $data['major_id'],
         'semester'   => $this->getActiveSemester(),
         'is_repeating' => $data['is_repeating']
      ]);

      $userModel = new UserModel();

      // Masukan ke tabel user dengan status menunggu persetujuan ADMIN
      $userModel->insert([
         'person_id'  => $newInsertID,
         'password'   => $data['password'],
         'number'     => $data['nim'],
         'role'       => 'STUDENT',
         'major_id'   => $data['major_id'],
         'semester'   => $this->getActiveSemester(),
         'is_repeating' => $data['is_repeating'],
         'verified_at' => date('Y-m-d H:i:s'),  // seolah langsung disetujui oleh ADMIN
      ]);
   }

   private function getActiveSemester()
   {
      $db = \Config\Database::connect();
      return $db->table('semesters')->where('is_active', 1)->get()->getRow()->id;
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
         return redirect()->back()->with('error', lang('App.emailNotFound'));
      }

      // Cari user yang terhubung dengan person_id
      $user = $this->userModel->where('person_id', $person['id'])->first();

      if (!$user) {
         return redirect()->back()->with('error', lang('App.accountNotFound'));
      }

      $token = bin2hex(random_bytes(32));
      $this->userModel->update($user['id'], [
         'token' => $token,
         'token_expired_at' => date('Y-m-d H:i:s', strtotime('+1 hour'))
      ]);
      // Kirim email reset password (dummy untuk sekarang)
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
