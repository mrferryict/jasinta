<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\I18n\Time;

use App\Models\AnnouncementModel;
use App\Models\AssignmentModel;
use App\Models\ChatModel;
use App\Models\LogModel;
use App\Models\MajorModel;
use App\Models\SemesterModel;
use App\Models\SettingModel;
use App\Models\StageModel;
use App\Models\StudentModel;
use App\Models\UserModel;

use Config\Services;

class Student extends Controller
{
   protected $session;
   protected $settings;

   protected $announcementModel;
   protected $assignmentModel;
   protected $chatModel;
   protected $logModel;
   protected $majorModel;
   protected $semesterModel;
   protected $settingModel;
   protected $stageModel;
   protected $studentModel;
   protected $userModel;

   protected $currentUserID;
   protected $data;

   public function __construct()
   {
      $this->session          = session();
      $this->settings         = Services::settingsService()->getSettingsAsArray();

      $this->announcementModel = new AnnouncementModel();
      $this->assignmentModel  = new AssignmentModel();
      $this->chatModel        = new ChatModel();
      $this->logModel         = new LogModel();
      $this->majorModel       = new MajorModel();
      $this->semesterModel    = new SemesterModel();
      $this->settingModel     = new SettingModel();
      $this->stageModel       = new StageModel();
      $this->studentModel     = new StudentModel();
      $this->userModel        = new UserModel();

      $this->currentUserID    = session()->get('user_id');
      $sendersList = $this->chatModel->getChatSendersWithUnreadCount($this->currentUserID);
      $this->data = [
         'settings'     => $this->settings,
         'sessions'     => $this->session,
         'sendersList'  => $sendersList,
         'student'      => $this->studentModel->getStudentDetails($this->session->get('user_id')),
      ];
   }
   //
   // FITUR DASHBOARD
   //
   public function index()
   {
      $currentStage = $this->studentModel->getStudentStage($this->session->get('user_id'));

      $stages = $this->stageModel->findAll(); // Ambil semua data stages
      $deadlines = [];
      foreach ($stages as $stage) {
         $deadlines[$stage['name']] = $stage['deadline_at']; // Simpan deadline_at dengan key nama stage
      }
      $deadlineSidang = strtotime($deadlines['SIDANG']); // Ambil deadline sidang dari stages
      $remainingDays = round(($deadlineSidang - time()) / 86400);

      // Get announcements
      $announcements = $this->announcementModel->orderBy('created_at', 'DESC')->findAll();

      $dataView = $this->data + [
         'announcements' => $announcements,
         'settings'     => $this->settings,
         'sessions'     => $this->session,
         'infoBoxes'    => [
            'currentStage'       => $currentStage ? $currentStage['name'] : 'PENDAFTARAN',
            'remainingDaysLeft'  => $remainingDays,
         ],
         'pageTitle' => 'DASHBOARD',
      ];
      $this->logModel->logActivity($this->currentUserID, 'VIEW', lang('App.dashboard'));
      return view('student/dashboard', $dataView);
   }

   //
   // REGISTRATION
   //
   public function registration()
   {
      // Ambil data mahasiswa yang sedang login
      $studentId = $this->currentUserID;

      if (!$studentId) {
         return redirect()->to('/auth/login')->with('error', 'Silakan login terlebih dahulu.');
      }

      // Ambil data mahasiswa dari database
      $student = $this->personModel
         ->select('persons.*, users.verified_at, majors.name AS major_name, semesters.name AS semester_name')
         ->join('users', 'users.person_id = persons.id', 'left')
         ->join('majors', 'majors.id = persons.major_id', 'left')
         ->join('semesters', 'semesters.id = persons.semester_id', 'left')
         ->where('persons.id', $studentId)
         ->first();

      if (!$student) {
         return redirect()->to('/auth/login')->with('error', 'Data mahasiswa tidak ditemukan.');
      }

      // Tentukan status mahasiswa berdasarkan verified_at
      $student['student_status'] = ($student['verified_at'] !== null) ? 'ACTIVE' : 'INACTIVE';

      // Ambil pengumuman untuk mahasiswa
      $announcements = $this->announcementModel->orderBy('created_at', 'DESC')->findAll();

      // Kirim data ke view
      $this->logModel->logActivity($this->currentUserID, 'VIEW', lang('App.registration'));
      return view('student/registration', [
         'pageTitle'     => 'Pendaftaran Mahasiswa',
         'student'       => $student,
         'announcements' => $announcements
      ]);
   }

   //
   // FITUR CHAT
   //
   public function chat($userId = null)
   {
      // ✅ Ambil daftar pengirim (semua yang pernah chat dengan admin)
      $chatModel = new ChatModel();
      $contacts = $chatModel->getChatContacts($this->currentUserID);

      // ✅ Jika tidak ada kontak sama sekali, cari ADMIN pertama
      if (empty($contacts)) {
         $admin = $this->userModel->where('division', 'ADMIN')->first();
         if ($admin) {
            $contacts[] = [
               'id' => $admin['id'],
               'name' => $admin['name'],
               'last_message' => 'Mulai chat dengan admin',
               'last_message_time' => null,
               'unread_chats' => 0
            ];
         }
      }

      // ✅ Jika tidak ada userId yang dipilih, gunakan user pertama dari daftar kontak
      if (!$userId && !empty($contacts)) {
         $userId = $contacts[0]['id'];
      }

      // ✅ Pastikan $userId valid sebelum mencari data pengguna
      $receiver = $this->userModel->find($userId);
      if (!$receiver) {
         return redirect()->back()->with('error', 'User tidak ditemukan.');
      }

      // ✅ Ambil isi percakapan antara admin dan user
      $messages = $chatModel->getMessages($this->currentUserID, $userId);

      // ✅ Tandai semua pesan sebagai telah dibaca
      $chatModel->markMessagesAsRead($this->currentUserID, $userId);

      $dataView = $this->data + [
         'pageTitle' => 'Chat dengan ' . esc($receiver['name']),
         'activeMenu' => '',
         'contacts' => $contacts,
         'receiver' => $receiver,
         'messages' => $messages,
         'activeChatId' => $userId,
         'currentUserID' => $this->currentUserID,
      ];

      return view('student/chat', $dataView);
   }


   public function sendMessage($to = '')
   {
      $admin = $this->userModel->where('division', 'ADMIN')->first(); // Cari ADMIN

      if (!$admin) {
         return $this->response->setJSON(['error' => 'Admin not found'])->setStatusCode(500);
      }

      $message = $this->request->getPost('message');
      $file = $this->request->getFile('file');
      $fileName = null;

      // ✅ Validasi apakah ada file yang diunggah
      if ($file && $file->isValid() && !$file->hasMoved()) {
         $validTypes = ['image/png', 'image/jpeg', 'application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];

         if (!in_array($file->getMimeType(), $validTypes)) {
            return $this->response->setJSON(['error' => 'Format file tidak diperbolehkan.'])->setStatusCode(400);
         }

         $fileName = $file->getRandomName();
         $file->move('uploads/chat/', $fileName);
      }

      // ✅ Pastikan minimal ada pesan atau file
      if (!$message && !$fileName) {
         return $this->response->setJSON(['error' => 'Pesan atau file harus diisi'])->setStatusCode(400);
      }

      // Simpan ke database
      $this->chatModel->insert([
         'sender_id' => $this->currentUserID,
         'receiver_id' => $admin['id'],
         'message' => $message,
         'file' => $fileName,
         'created_at' => Time::now()
      ]);

      return $this->response->setJSON(['success' => true]);
   }


   public function mentoringRegistration()
   {
      $studentId = $this->session->get('user_id');
      $registrationData = $this->studentModel->getStudentDetails($studentId);
      $currentStage = $this->progressModel->getStudentStage($studentId);

      $data = [
         'student' => $registrationData,
         'stage' => $currentStage['name'] ?? 'REGISTRATION'
      ];

      return view('student/mentoring_registration', $data);
   }

   public function proposalRequirements()
   {
      $studentId = $this->session->get('user_id');
      $requirements = $this->requirementModel->getStudentRequirements($studentId, 'PROPOSAL');

      $data = [
         'requirements' => $requirements
      ];

      return view('student/proposal_requirements', $data);
   }
}
