<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\StudentModel;
use App\Models\ProgressModel;
use App\Models\AnnouncementModel;
use App\Models\MessageModel;
use App\Models\RequirementModel;
use App\Models\StageModel;
use App\Models\PersonModel;
use App\Models\UserModel;
use App\Models\ActivityLogModel;
use Config\Services;

class Student extends Controller
{
   protected $settings;
   protected $session;
   protected $studentModel;
   protected $progressModel;
   protected $announcementModel;
   protected $messageModel;
   protected $requirementModel;
   protected $stageModel;

   public function __construct()
   {
      $settingsService = Services::settingsService();
      $this->settings = $settingsService->getSettingsAsArray();
      $this->session = session();
      $this->studentModel = new StudentModel();
      $this->progressModel = new ProgressModel();
      $this->announcementModel = new AnnouncementModel();
      $this->messageModel = new MessageModel();
      $this->requirementModel = new RequirementModel();
      $this->stageModel = new StageModel();
   }

   public function index()
   {
      $stageModel = new \App\Models\StageModel();

      $studentId = $this->session->get('user_id');

      // Get student data
      $studentData = $this->studentModel->getStudentDetails($studentId);
      $currentStage = $this->progressModel->getStudentStage($studentId);


      $stages = $stageModel->findAll(); // Ambil semua data stages
      $deadlines = [];
      foreach ($stages as $stage) {
         $deadlines[$stage['name']] = $stage['deadline_at']; // Simpan deadline_at dengan key nama stage
      }
      $deadlineSidang = strtotime($deadlines['SIDANG']); // Ambil deadline sidang dari stages
      $remainingDays = round(($deadlineSidang - time()) / 86400);

      // Get announcements
      $announcements = $this->announcementModel->orderBy('created_at', 'DESC')->findAll();

      $data = [
         'student' => $studentData,
         'announcements' => $announcements,
         'settings' => $this->settings,
         'sessions' => $this->session,
         'infoBoxes' => [
            'currentStage' => $currentStage['name'] ?? 'PENDAFTARAN',
            'remainingDaysLeft' => $remainingDays,
         ],
         'pageTitle' => 'DASHBOARD',

      ];

      return view('student/dashboard', $data);
   }

   public function registration()
   {
      // Ambil data mahasiswa yang sedang login
      $studentId = session()->get('user_id');

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
      $announcements = $this->announcementModel
         ->orderBy('created_at', 'DESC')
         ->findAll();

      // Kirim data ke view
      return view('student/registration', [
         'pageTitle'     => 'Pendaftaran Mahasiswa',
         'student'       => $student,
         'announcements' => $announcements
      ]);
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

   public function sendMessage()
   {
      $senderId = $this->session->get('user_id');
      $receiverId = $this->request->getPost('receiver_id');
      $content = $this->request->getPost('content');

      if (!$receiverId || empty($content)) {
         return redirect()->back()->with('error', 'Message cannot be empty');
      }

      $this->messageModel->insert([
         'sender_id' => $senderId,
         'receiver_id' => $receiverId,
         'content' => $content,
         'created_at' => date('Y-m-d H:i:s'),
         'read_at' => null // Pesan baru, jadi belum terbaca
      ]);

      return redirect()->to("student/chat/$receiverId");
   }


   public function chat($withWho)
   {
      $messageModel = new \App\Models\MessageModel();
      $personModel = new \App\Models\PersonModel();

      // Pastikan user sudah login
      if (!$this->session->has('user_id')) {
         return redirect()->to('/login')->with('error', 'You need to login first!');
      }

      // Ambil ID user yang sedang login
      $userId = $this->session->get('user_id');

      // Tentukan receiver_id berdasarkan $withWho
      if ($withWho === 'admin') {
         // Cari administrator yang aktif (misalnya dengan ID 1 atau kriteria tertentu)
         $receiver = $personModel->where('division', 'ADMINISTRATOR')->first();
      } elseif ($withWho === 'lecturer') {
         // Ambil dosen pembimbing mahasiswa ini dari appointment
         $appointmentModel = new \App\Models\AppointmentModel();
         $receiver = $appointmentModel->getLecturerByStudent($userId);
      } else {
         return redirect()->back()->with('error', 'Invalid chat recipient!');
      }

      if (!$receiver) {
         return redirect()->back()->with('error', 'Recipient not found!');
      }

      // Ambil pesan antara mahasiswa dan penerima
      $messages = $messageModel
         ->where('(sender_id = ' . $userId . ' AND receiver_id = ' . $receiver['id'] . ')')
         ->orWhere('(sender_id = ' . $receiver['id'] . ' AND receiver_id = ' . $userId . ')')
         ->orderBy('created_at', 'ASC')
         ->findAll();

      // Tandai pesan yang diterima sebagai sudah dibaca
      $messageModel->where('receiver_id', $userId)
         ->where('sender_id', $receiver['id'])
         ->where('read_at', null)
         ->set(['read_at' => date('Y-m-d H:i:s')])
         ->update();

      // Load view chat
      return view('student/chat', [
         'receiver' => $receiver,
         'messages' => $messages,
         'withWho' => $withWho
      ]);
   }
}
