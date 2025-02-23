<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\I18n\Time;

use App\Models\AnnouncementModel;
use App\Models\ChatModel;
use App\Models\LogModel;
use App\Models\MajorModel;
use App\Models\SemesterModel;
use App\Models\SettingModel;
use App\Models\StageModel;
use App\Models\StudentModel;
use App\Models\TemporaryUserModel;
use App\Models\UserModel;

use Config\Services;

class Admin extends Controller
{
   protected $settings;

   protected $announcementModel;
   protected $chatModel;
   protected $logModel;
   protected $majorModel;
   protected $semesterModel;
   protected $settingModel;
   protected $stageModel;
   protected $studentModel;
   protected $temporaryUserModel;
   protected $userModel;

   protected $currentUserId;
   protected $data;

   public function __construct()
   {
      $this->settings         = Services::settingsService()->getSettingsAsArray();

      $this->announcementModel = new AnnouncementModel();
      $this->chatModel        = new ChatModel();
      $this->logModel         = new LogModel();
      $this->majorModel       = new MajorModel();
      $this->semesterModel    = new SemesterModel();
      $this->settingModel     = new SettingModel();
      $this->stageModel       = new StageModel();
      $this->studentModel     = new StudentModel();
      $this->temporaryUserModel = new TemporaryUserModel();
      $this->userModel        = new UserModel();

      $this->currentUserId    = (int) session()->get('user_id');
      $sendersList = $this->chatModel->getChatSendersWithUnreadCount($this->currentUserId);
      $this->data = [
         'settings'     => $this->settings,
         'sessions'     => session(),
         'sendersList'  => $sendersList,
      ];
   }

   //
   // DASHBOARD
   //
   public function index(): string  // dashboard
   {

      // **1. TOTAL MAHASISWA PESERTA**
      $totalStudents = $this->studentModel->countActiveStudents();

      // **2. AMBIL DEADLINE TIAP TAHAPAN DARI TABEL STAGES**
      $stages = $this->stageModel->findAll(); // Ambil semua data stages
      $deadlines = [];
      foreach ($stages as $stage) {
         $deadlines[$stage['name']] = $stage['deadline_at']; // Simpan deadline_at dengan key nama stage
      }

      // **3. AMBIL DATA MAHASISWA & HITUNG MAHASISWA ON-TRACK**
      $students = $this->studentModel->getStudentProgress();
      $studentsOnTrack = 0;

      foreach ($students as $student) {
         $stageName = strtoupper($student['stage']); // Nama stage dalam format uppercase
         $deadlineDate = isset($deadlines[$stageName]) ? strtotime($deadlines[$stageName]) : null; // Ambil deadline dari array deadlines
         $stageDate = strtotime($student['stage_date'] ?? date('Y-m-d'));

         // Jika mahasiswa tidak melewati deadline, berarti on-track
         if ($deadlineDate && $stageDate <= $deadlineDate) {
            $studentsOnTrack++;
         }
      }

      // **4. WAKTU TERSISA SEBELUM DEADLINE SIDANG**
      $deadlineSidang = strtotime($deadlines['SIDANG']); // Ambil deadline sidang dari stages
      $remainingDays = round(($deadlineSidang - time()) / 86400);

      $dataView = $this->data + [
         'activeMenu' => '',
         'infoBoxes' => [
            'totalActiveStudents' => $totalStudents,
            'studentsOnTrack' => $studentsOnTrack,
            'remainingDaysLeft' => $remainingDays,
         ],
         'pageTitle' => 'DASHBOARD',
         'tableTitle' => lang('App.monitoringTheStagesOfStudents'),
         'students' => $students,
         'deadlines' => $deadlines,
         'stages' => $this->stageModel->getStageNames(),
      ];
      $this->logModel->logActivity($this->currentUserId, 'VIEW', lang('App.dashboard'));
      return view('admin/dashboard', $dataView);
   }
   //
   //  MASTER DATA: REGISTRANT
   //
   public function registrants()
   {
      $dataView = $this->data + [
         'pageTitle' => lang('App.registrants'),
         'activeMenu' => 'registrants',
         'registrants' => $this->temporaryUserModel->getAllTemporaryUsers()
      ];
      $this->logModel->logActivity($this->currentUserId, 'VIEW', lang('App.registrants'));
      return view('admin/registrants', $dataView);
   }

   //
   //  MASTER DATA: USERS
   //
   public function users()
   {
      $dataView = $this->data + [
         'pageTitle' => lang('App.users'),
         'activeMenu' => 'users',
         'users' => $this->userModel->getAllUsers()
      ];
      $this->logModel->logActivity($this->currentUserId, 'VIEW', lang('App.users'));
      return view('admin/users', $dataView);
   }

   public function createUser()
   {
      $dataView = $this->data + [
         'pageTitle' => lang('App.createUser'),
         'activeMenu' => 'users',
         'majors' => $this->majorModel->getAllMajors(),
         'semesters' => $this->semesterModel->getActiveSemesters(),
      ];
      $this->logModel->logActivity($this->currentUserId, 'VIEW', lang('App.createUser'));
      return view('admin/create_user', $dataView);
   }

   public function saveUser()
   {
      $postData = $this->request->getPost();

      // Validasi dasar
      $validationRules = [
         'division' => 'required|in_list[STUDENT,LECTURER,ADMIN]',
         'name' => 'required',
         'email' => 'required|valid_email|is_unique[users.email]',
         'password' => 'required|min_length[8]',
         'number' => 'required|numeric',
         'major_id' => 'permit_empty|is_not_unique[majors.id]',
         'semester_id' => 'permit_empty|is_not_unique[semesters.id]',
      ];

      if (!$this->validate($validationRules)) {
         return redirect()->back()->withInput()->with('validation', \Config\Services::validation());
      }

      // Validasi tambahan untuk STUDENT
      if ($postData['division'] === 'STUDENT') {
         $nim = $postData['number'];
         $major_id = $postData['major_id'];

         // Pastikan NIM 8 digit dan digit ke-3 & ke-4 sesuai jurusan
         if (strlen($nim) !== 8 || !ctype_digit($nim)) {
            $validation = \Config\Services::validation();
            $validation->setError('number', 'NIM harus 8 angka');
            return redirect()->back()->withInput()->with('validation', $validation);
         }

         $prefix = substr($nim, 2, 2);
         $allowedMajors = [
            '36' => 3, // D3 Manajemen Informatika
            '56' => 2, // S1 Sistem Informasi
            '57' => 1, // S1 Teknik Informatika
         ];

         if (!array_key_exists($prefix, $allowedMajors) || $major_id != $allowedMajors[$prefix]) {
            $validation = \Config\Services::validation();
            $validation->setError('number', lang('App.nimAndMajorMismatch'));
            return redirect()->back()->withInput()->with('validation', $validation);
         }
      }

      // Jika validasi lolos, simpan user
      // Hash password sebelum disimpan
      $postData['password'] = password_hash($postData['password'], PASSWORD_DEFAULT);
      $postData['verified_at'] = date('Y-m-d H:i:s');
      $newId = $this->userModel->insert($postData);
      $this->logModel->logActivity($this->currentUserId, 'CREATE', lang('App.saveNewUser') . ' ID=' . $newId);
      return redirect()->to('/admin/users')->with('success', lang('App.newUserSuccessfullyAdded'));
   }

   //
   //  MASTER DATA: PROGRAM STUDI
   //
   public function majors()
   {
      $dataView = $this->data + [
         'pageTitle' => lang('App.majors'),
         'activeMenu' => 'majors',
         'majors' => $this->majorModel->getAllMajors(),
      ];
      $this->logModel->logActivity($this->currentUserId, 'VIEW', lang('App.majors'));
      return view('admin/majors', $dataView);
   }

   //
   //  MASTER DATA: SETTINGS
   //
   public function settings()
   {
      $dataView = $this->data + [
         'pageTitle' => lang('App.settings'),
         'activeMenu' => 'settings',
         'settings' => $this->settingModel->getAllSettings(),
      ];
      $this->logModel->logActivity($this->currentUserId, 'VIEW', lang('App.settings'));
      return view('admin/settings', $dataView);
   }

   public function checkNIM(string $nim, string $major_id, array $data): bool
   {
      // Pastikan NIM terdiri dari 8 digit
      if (strlen($nim) !== 8 || !is_numeric($nim)) {
         return false;
      }

      // Ambil dua digit ke-3 dan ke-4
      $prefix = substr($nim, 2, 2);

      // Daftar jurusan sesuai dengan kode
      $allowedMajors = [
         '36' => 3, // ID untuk D3 Manajemen Informatika
         '56' => 2, // ID untuk S1 Sistem Informasi
         '57' => 1, // ID untuk S1 Teknik Informatika
      ];

      // Pastikan kode ada dalam daftar
      if (!array_key_exists($prefix, $allowedMajors)) {
         return false;
      }

      // Pastikan major_id sesuai dengan kode
      if ($major_id != $allowedMajors[$prefix]) {
         return false;
      }
      return true;
   }

   //
   // FITUR CHAT
   //

   public function chat($userId = null)
   {
      // ✅ Ambil daftar pengirim (semua yang pernah chat dengan admin)
      $contacts = $this->chatModel->getChatContacts($this->currentUserId);

      // ✅ Jika tidak ada userId yang dipilih, tampilkan user pertama dari daftar kontak
      if (!$userId && !empty($contacts)) {
         $userId = $contacts[0]['id'];
      }

      // ✅ Ambil data user yang sedang di-chat
      $receiver = $this->userModel->find($userId);

      if (!$receiver) {
         return redirect()->back()->with('error', 'User tidak ditemukan.');
      }

      // ✅ Ambil isi percakapan antara admin dan user
      $messages = $this->chatModel->getMessages($this->currentUserId, $userId);

      // ✅ Tandai semua pesan sebagai telah dibaca
      $this->chatModel->markMessagesAsRead($this->currentUserId, $userId);

      // ✅ Simpan userId terakhir yang di-chat di session
      $lastOpenedChat = session()->get('lastOpenedChat');
      if ($lastOpenedChat != $userId) {
         $this->logModel->logActivity($this->currentUserId, 'OPEN_CHATS', 'to: ' . $receiver['name']);
         session()->set('lastOpenedChat', $userId); // Simpan ID user terakhir
      }
      $dataView = $this->data + [
         'pageTitle' => 'Chat',
         'activeMenu' => '',
         'contacts' => $contacts,
         'receiver' => $receiver,
         'messages' => $messages,
         'activeChatId' => $userId,
         'currentUserId' => $this->currentUserId,
      ];
      return view('admin/chat', $dataView);
   }


   // ✅ Fitur Kirim Pesan dari Admin ke Mahasiswa/Dosen
   public function sendMessage($userId)
   {
      $adminId = session()->get('user_id'); // Admin yang sedang login

      $message = $this->request->getPost('message');
      $file = $this->request->getFile('file');
      $fileName = null;

      // ✅ Validasi file
      if ($file && $file->isValid() && !$file->hasMoved()) {
         $validTypes = ['image/png', 'image/jpeg', 'application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];

         if (!in_array($file->getMimeType(), $validTypes)) {
            return $this->response->setJSON(['error' => 'Format file tidak diperbolehkan.'])->setStatusCode(400);
         }

         $fileName = $file->getRandomName();
         $file->move('uploads/chat/', $fileName);
      }

      if (!$message && !$fileName) {
         return $this->response->setJSON(['error' => 'Pesan atau file harus diisi'])->setStatusCode(400);
      }

      $this->chatModel->insert([
         'sender_id' => $adminId,
         'receiver_id' => $userId,
         'message' => $message,
         'file' => $fileName,
         'created_at' => date('Y-m-d H:i:s')
      ]);
      $this->logModel->logActivity($this->currentUserId, 'SEND_CHAT', 'to: ' . $this->userModel->getUserNameById($userId)['name']);
      return $this->response->setJSON(['success' => true]);
   }



   // ===================== ANNOUNCEMENTS =====================
   public function announcement()
   {
      $data = [
         'announcements' => $this->announcementModel->getAllAnnouncements(),
      ];

      return view('admin/announcement', $data);
   }

   public function addAnnouncement()
   {
      return view('admin/add_announcement');
   }

   public function saveAnnouncement()
   {
      $this->announcementModel->insert([
         'title' => $this->request->getPost('title'),
         'message' => $this->request->getPost('message'),
         'created_at' => date('Y-m-d H:i:s'),
      ]);

      return redirect()->to('admin/announcement')->with('success', 'Pengumuman berhasil ditambahkan.');
   }



   // ===================== PENDAFTARAN MAHASISWA =====================
   public function daftarPendaftaran()
   {
      $data['mahasiswa_pending'] = $this->personModel
         ->select('persons.*, thesis.stage')
         ->join('thesis', 'thesis.student_id = persons.id', 'left')
         ->where('persons.division', 'mahasiswa')
         ->where('thesis.stage', 'PENDAFTARAN')
         ->findAll();

      return view('admin/daftar_pendaftaran', $data);
   }

   public function approvePendaftaran($studentId)
   {
      // Cek apakah mahasiswa sudah memiliki data thesis
      $thesis = $this->thesisModel->where('student_id', $studentId)->first();

      if (!$thesis) {
         // Buat data thesis baru dengan tahap awal
         $this->thesisModel->insert([
            'student_id' => $studentId,
            'title' => null,
            'stage' => 'SYARAT PROPOSAL', // Setelah pendaftaran disetujui, lanjut ke tahap berikutnya
            'created_at' => date('Y-m-d H:i:s'),
         ]);
      } else {
         // Update stage jika thesis sudah ada
         $this->thesisModel->update($thesis['id'], ['stage' => 'SYARAT PROPOSAL']);
      }

      return redirect()->to('/admin/pendaftaran')->with('success', 'Pendaftaran mahasiswa telah disetujui.');
   }

   // ===================== MANAJEMEN SYARAT PROPOSAL =====================
   public function daftarSyaratProposal()
   {
      // Ambil semua syarat proposal
      $data['requirements'] = $this->requirementModel->where('stage', 'SYARAT PROPOSAL')->findAll();

      return view('admin/daftar_syarat_proposal', $data);
   }

   public function buatSyaratProposal()
   {
      if ($this->request->getMethod() === 'post') {
         $syarat = $this->request->getPost('syarat');
         $tipeJawaban = $this->request->getPost('tipe_jawaban');

         // Simpan syarat ke database
         $this->requirementModel->insert([
            'stage' => 'SYARAT PROPOSAL',
            'description' => $syarat,
            'answer_type' => $tipeJawaban, // 'text', 'pdf', atau 'image'
            'created_at' => date('Y-m-d H:i:s'),
         ]);

         return redirect()->to('/admin/syarat_proposal')->with('success', 'Syarat baru berhasil ditambahkan.');
      }

      return view('admin/buat_syarat_proposal');
   }

   public function approveSyaratProposal($requirementId)
   {
      // Approve syarat proposal
      $this->requirementModel->update($requirementId, ['approved_at' => date('Y-m-d H:i:s')]);

      return redirect()->to('/admin/syarat_proposal')->with('success', 'Syarat telah disetujui.');
   }


   // ===================== AKTIVITAS PENGGUNA (AUDIT TRAILS) =====================
   public function activityLogs(): string
   {
      $dataView = $this->data + [
         'pageTitle' => lang('App.activityLogs'),
         'activeMenu' => 'activityLogs',
         'logs' => $this->logModel->getAllLogs(),
      ];
      $this->logModel->logActivity($this->currentUserId, 'VIEW', lang('App.activityLogs'));
      return view('admin/activity_logs', $dataView);
   }
}
