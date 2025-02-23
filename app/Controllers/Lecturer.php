<?php

namespace App\Controllers;

use CodeIgniter\Controller;

use App\Models\AnnouncementModel;
use App\Models\AssignmentModel;
use App\Models\ChatModel;
use App\Models\LogModel;
use App\Models\ThesisModel;
use App\Models\StageModel;
use App\Models\UserModel;

use Config\Services;

class Lecturer extends Controller
{
   protected $session;
   protected $settings;

   protected $announcementModel;
   protected $assignmentModel;
   protected $chatModel;
   protected $thesisModel;
   protected $logModel;
   protected $stageModel;
   protected $userModel;

   protected $data;

   protected $currentUserId;

   public function __construct()
   {
      $this->session = session();
      $this->settings = Services::settingsService()->getSettingsAsArray();

      $this->announcementModel = new AnnouncementModel();
      $this->assignmentModel = new AssignmentModel();
      $this->chatModel = new ChatModel();
      $this->thesisModel = new LogModel();
      $this->thesisModel = new ThesisModel();
      $this->stageModel = new StageModel();
      $this->userModel = new UserModel();

      $this->currentUserId    = (int)session()->get('user_id');
      $sendersList = $this->chatModel->getChatSendersWithUnreadCount($this->currentUserId);
      $this->data = [
         'settings'     => $this->settings,
         'sessions'     => $this->session,
         'sendersList'  => $sendersList,
      ];
   }

   /**
    * Dashboard Lecturer - Overview of supervised students and assigned examinations
    */
   public function index()
   {
      $lecturerId = session()->get('user_id');

      // Get supervised students
      $supervisedStudents = $this->assignmentModel->getSupervisedStudents($lecturerId);

      // Get assigned examinations
      $examinedStudents = $this->assignmentModel->getExaminedStudents($lecturerId);

      $dataView = $this->data + [
         'pageTitle' => 'DASBOR DOSEN',
         'supervisedStudents' => $supervisedStudents,
         'examinedStudents' => $examinedStudents,
         'infoBoxes' => [
            'currentStage' => 'kosong',
            'remainingDaysLeft' => 'kosong',
         ],
      ];

      return view('lecturer/dashboard', $dataView);
   }

   /**
    * List of students supervised by the lecturer
    */
   public function supervision()
   {
      $lecturerId = $this->session->get('user_id');
      $students = $this->appointmentModel->getSupervisedStudents($lecturerId);

      $data = [
         'students' => $students,
         'pageTitle' => 'Supervised Students',
      ];

      return view('lecturer/supervision', $data);
   }

   /**
    * List of students assigned for examination
    */
   public function examination()
   {
      $lecturerId = $this->session->get('user_id');
      $students = $this->appointmentModel->getExaminedStudents($lecturerId);

      $data = [
         'students' => $students,
         'pageTitle' => 'Assigned Examinations',
      ];

      return view('lecturer/examination', $data);
   }

   //
   // FITUR CHAT
   //

   public function chat($userId = null)
   {
      $chatModel = new ChatModel();
      $session = session();

      // ✅ Ambil daftar pengirim (semua yang pernah chat dengan admin)
      $contacts = $chatModel->getChatContacts($this->currentUserId);

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
      $messages = $chatModel->getMessages($this->currentUserId, $userId);

      // ✅ Tandai semua pesan sebagai telah dibaca
      $chatModel->markMessagesAsRead($this->currentUserId, $userId);

      // ✅ Simpan userId terakhir yang di-chat di session
      $lastOpenedChat = $session->get('lastOpenedChat');
      if ($lastOpenedChat != $userId) {
         $this->logModel->logActivity($this->currentUserId, 'OPEN_CHATS', 'to: ' . $receiver['name']);
         $session->set('lastOpenedChat', $userId); // Simpan ID user terakhir
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

   /**
    * Send chat message
    */
   public function sendMessage()
   {
      $senderId = $this->session->get('user_id');
      $receiverId = $this->request->getPost('receiver_id');
      $content = $this->request->getPost('content');

      if (!$receiverId || !$content) {
         return redirect()->back()->with('error', 'Message cannot be empty.');
      }

      $this->messageModel->insert([
         'sender_id' => $senderId,
         'receiver_id' => $receiverId,
         'content' => $content,
         'created_at' => date('Y-m-d H:i:s'),
      ]);

      return redirect()->back()->with('success', 'Message sent.');
   }

   /**
    * Approve or reject thesis supervision
    */
   public function approveSupervision($studentId)
   {
      $lecturerId = $this->session->get('user_id');

      // Update thesis status
      $this->thesisModel->updateSupervisionStatus($studentId, 'approved');

      // Move student to next stage
      $this->progressModel->moveToNextStage($studentId);

      return redirect()->to('/lecturer/supervision')->with('success', 'Supervision approved.');
   }

   /**
    * View student thesis progress
    */
   public function progress($studentId)
   {
      $studentProgress = $this->progressModel->getStudentProgress($studentId);

      $data = [
         'studentProgress' => $studentProgress,
         'pageTitle' => 'Student Progress',
      ];

      return view('lecturer/progress', $data);
   }
}
