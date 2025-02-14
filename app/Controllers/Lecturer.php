<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PersonModel;
use App\Models\ThesisModel;
use App\Models\AppointmentModel;
use App\Models\MessageModel;
use App\Models\ProgressModel;
use App\Models\StageModel;
use App\Models\UserModel;
use App\Models\AnnouncementModel;
use App\Models\ActivityLogModel;
use Config\Services;

class Lecturer extends Controller
{
   protected $settings;
   protected $session;
   protected $personModel;
   protected $thesisModel;
   protected $appointmentModel;
   protected $messageModel;
   protected $progressModel;
   protected $stageModel;

   public function __construct()
   {
      $settingsService = Services::settingsService();
      $this->settings = $settingsService->getSettingsAsArray();
      $this->session = session();
      $this->personModel = new PersonModel();
      $this->thesisModel = new ThesisModel();
      $this->appointmentModel = new AppointmentModel();
      $this->messageModel = new MessageModel();
      $this->progressModel = new ProgressModel();
      $this->stageModel = new StageModel();
   }

   /**
    * Dashboard Lecturer - Overview of supervised students and assigned examinations
    */
   public function index()
   {
      $lecturerId = $this->session->get('user_id');

      // Get supervised students
      $supervisedStudents = $this->appointmentModel->getSupervisedStudents($lecturerId);

      // Get assigned examinations
      $examinedStudents = $this->appointmentModel->getExaminedStudents($lecturerId);

      $data = [
         'supervisedStudents' => $supervisedStudents,
         'examinedStudents' => $examinedStudents,
         'settings' => $this->settings,
         'sessions' => $this->session,
         'infoBoxes' => [
            'currentStage' => 'kosong',
            'remainingDaysLeft' => 'kosong',
         ],
         'pageTitle' => 'DASHBOARD DOSEN',
      ];

      return view('lecturer/dashboard', $data);
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

   /**
    * Chat with a student
    */
   public function chat($studentId)
   {
      $lecturerId = $this->session->get('user_id');

      // Fetch chat messages between lecturer and student
      $messages = $this->messageModel->getChatMessages($lecturerId, $studentId);

      $data = [
         'messages' => $messages,
         'studentId' => $studentId,
         'pageTitle' => 'Chat with Student',
      ];

      return view('lecturer/chat', $data);
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
