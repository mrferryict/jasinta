<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;
use App\Models\SemesterModel;
use App\Models\MajorModel;
use App\Models\AnnouncementModel;
use App\Models\MessageModel;
use App\Models\ActivityLogModel;
use App\Models\StageModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use Config\Services;

class Admin extends Controller
{
   protected $settings;
   protected $session;
   protected $userModel;
   protected $announcementModel;
   protected $messageModel;
   protected $activityLogModel;
   protected $stageModel;
   protected $data;

   public function __construct()
   {
      $this->session = session();
      $this->settings = Services::settingsService()->getSettingsAsArray();
      $this->userModel = new UserModel();
      $this->announcementModel = new AnnouncementModel();
      $this->messageModel = new MessageModel();
      $this->activityLogModel = new ActivityLogModel();
      $this->stageModel       = new StageModel();

      $this->data = [
         'settings' => $this->settings,
         'sessions' => $this->session,
      ];
   }

   public function index(): string  // dashboard
   {

      $progressModel = new \App\Models\ProgressModel();
      $stageModel = new \App\Models\StageModel();

      // **1. TOTAL MAHASISWA PESERTA**
      $totalStudents = $progressModel->countAll();

      // **2. AMBIL DEADLINE TIAP TAHAPAN DARI TABEL STAGES**
      $stages = $stageModel->findAll(); // Ambil semua data stages
      $deadlines = [];
      foreach ($stages as $stage) {
         $deadlines[$stage['name']] = $stage['deadline_at']; // Simpan deadline_at dengan key nama stage
      }

      // **3. AMBIL DATA MAHASISWA & HITUNG MAHASISWA ON-TRACK**
      $students = $progressModel->getStudentProgress();
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
         'activeMenu' => 'people',
         'infoBoxes' => [
            'totalActiveStudents' => $totalStudents,
            'studentsOnTrack' => $studentsOnTrack,
            'remainingDaysLeft' => $remainingDays,
         ],
         'pageTitle' => 'DASHBOARD',
         'tableTitle' => lang('App.monitoringTheStagesOfStudents'),
         'students' => $progressModel->getStudentProgress(),
         'deadlines' => $deadlines, // Menggunakan deadlines dari stages
         'stages' => array_column($stageModel->orderBy('id', 'ASC')->findAll(), 'name'),
      ];
      return view('admin/dashboard', $dataView);
   }


   //  MASTER DATA: USERS

   public function users()
   {
      $userModel = new UserModel();

      $dataView = $this->data + [
         'pageTitle' => lang('App.users'),
         'activeMenu' => 'users',
         'users' => $userModel->getAllUsers()
      ];
      return view('admin/users', $dataView);
   }

   public function createUser()
   {
      $majorModel = new MajorModel();
      $semesterModel = new SemesterModel();


      $dataView = $this->data + [
         'pageTitle' => lang('App.createUser'),
         'activeMenu' => 'users',
         'majors' => $majorModel->getAllMajors(),
         'semesters' => $semesterModel->getActiveSemesters(),
      ];
      return view('admin/create_user', $dataView);
   }

   public function saveUser()
   {
      $userModel = new UserModel();
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
            $validation->setError('number', 'NIM tidak sesuai dengan jurusan yang dipilih.');
            return redirect()->back()->withInput()->with('validation', $validation);
         }
      }

      // Jika validasi lolos, simpan user
      // Hash password sebelum disimpan
      $postData['password'] = password_hash($postData['password'], PASSWORD_DEFAULT);
      $postData['verified_at'] = date('Y-m-d H:i:s');
      $userModel->insert($postData);
      return redirect()->to('/admin/users')->with('success', 'User berhasil ditambahkan.');
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



   // ===================== CHAT =====================
   public function chat($studentId)
   {
      $adminId = $this->session->get('user_id');
      $messages = $this->messageModel->getMessages($studentId, $adminId);

      $data = [
         'messages' => $messages,
         'studentId' => $studentId,
      ];

      return view('admin/chat', $data);
   }

   public function sendMessage()
   {
      $adminId = $this->session->get('user_id');
      $studentId = $this->request->getPost('student_id');
      $message = $this->request->getPost('message');

      $filePath = null;
      if ($file = $this->request->getFile('attachment')) {
         if ($file->isValid() && !$file->hasMoved()) {
            $filePath = $file->store();
         }
      }

      $this->messageModel->insert([
         'sender_id' => $adminId,
         'receiver_id' => $studentId,
         'content' => $message,
         'file_path' => $filePath,
         'created_at' => date('Y-m-d H:i:s')
      ]);

      return redirect()->back();
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
      $activityLogModel = new ActivityLogModel();

      $dataView = $this->data + [
         'pageTitle' => lang('App.activityLogs'),
         'activeMenu' => 'activityLogs',
         'logs' => $activityLogModel->getAllLogs()
      ];
      return view('admin/activity_logs', $dataView);
   }
}
