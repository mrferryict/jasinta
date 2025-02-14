<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PersonModel;
use App\Models\UserModel;
use App\Models\AnnouncementModel;
use App\Models\MessageModel;
use App\Models\ActivityLogModel;
use Config\Services;

class Admin extends Controller
{
   protected $settings;
   protected $session;
   protected $personModel;
   protected $userModel;
   protected $announcementModel;
   protected $messageModel;
   protected $activityLogModel;

   public function __construct()
   {
      $settingsService = Services::settingsService();
      $this->settings = $settingsService->getSettingsAsArray();
      $this->session = session();
      $this->personModel = new PersonModel();
      $this->userModel = new UserModel();
      $this->announcementModel = new AnnouncementModel();
      $this->messageModel = new MessageModel();
      $this->activityLogModel = new ActivityLogModel();
   }

   public function index()
   {
      $progressModel = new \App\Models\ProgressModel();
      $stageModel = new \App\Models\StageModel();
      $settingsModel = new \App\Models\SettingsModel();

      // **1. TOTAL MAHASISWA PESERTA**
      $totalStudents = $progressModel->countAll();

      // **2. AMBIL DEADLINE TIAP TAHAPAN**
      $deadlines = array_column($settingsModel->getAllDeadlines(), 'value', 'key');

      // **3. AMBIL DATA MAHASISWA & HITUNG MAHASISWA ON-TRACK**
      $students = $progressModel->getStudentProgress();
      $studentsOnTrack = 0;

      foreach ($students as $student) {
         $stageKey = 'deadline_' . strtoupper($student['stage']);
         $deadlineDate = isset($deadlines[$stageKey]) ? strtotime($deadlines[$stageKey]) : null;
         $stageDate = strtotime($student['stage_date'] ?? date('Y-m-d'));

         // Jika mahasiswa tidak melewati deadline, berarti on-track
         if ($deadlineDate && $stageDate <= $deadlineDate) {
            $studentsOnTrack++;
         }
      }

      // **4. WAKTU TERSISA SEBELUM DEADLINE SIDANG**
      $deadlineSidang = strtotime($settingsModel->getSetting('deadline_SIDANG', '2025-06-01'));
      $remainingDays = ($deadlineSidang - time()) / 86400;
      $remainingDays = max(0, round($remainingDays)); // Pastikan tidak negatif

      $data = [
         'settings' => $this->settings,
         'sessions' => $this->session,
         'infoBoxes' => [
            'totalActiveStudents' => $totalStudents,
            'studentsOnTrack' => $studentsOnTrack,
            'remainingDaysLeft' => $remainingDays,
         ],
         'pageTitle' => 'DASHBOARD',
         'tableTitle' => lang('App.monitoringTheStagesOfStudents'),
         'students' => $progressModel->getStudentProgress(),
         'deadlines' => array_column($progressModel->getStageDeadlines(), 'value', 'key'),
         'stages' => array_column($stageModel->orderBy('id', 'ASC')->findAll(), 'name'),
      ];
      return view('admin/dashboard', $data);
   }

   // ===================== MASTER DATA =====================
   public function mahasiswa()
   {
      $data['mahasiswa'] = $this->personModel->where('division', 'mahasiswa')->findAll();
      return view('admin/mahasiswa', $data);
   }

   public function dosen()
   {
      $data['dosen'] = $this->personModel->where('division', 'dosen')->findAll();
      return view('admin/dosen', $data);
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
   public function aktivitasPengguna()
   {
      $data['logs'] = $this->activityLogModel->orderBy('created_at', 'DESC')->findAll();
      return view('admin/aktivitas_pengguna', $data);
   }
}
