<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AppointmentModel;
use App\Models\AppointmentDetailModel;
use App\Models\UserModel;
use App\Models\ThesisModel;
use App\Models\StageModel;

class AppointmentsController extends BaseController
{
   protected $appointmentModel;
   protected $appointmentDetailModel;
   protected $userModel;
   protected $thesisModel;
   protected $stageModel;

   public function __construct()
   {
      $this->appointmentModel = new AppointmentModel();
      $this->appointmentDetailModel = new AppointmentDetailModel();
      $this->userModel = new UserModel();
      $this->thesisModel = new ThesisModel();
      $this->stageModel = new StageModel();
   }

   /**
    * Daftar semua penugasan
    */
   public function index()
   {
      $data = [
         'pageTitle'     => 'Manage Appointments',
         'appointments'  => $this->appointmentsModel->getAppointmentsByStage(9), // Contoh ID stage SIDANG
         'theses'        => $this->thesisModel->findAll(),
         'stages'        => $this->stageModel->findAll(),
         'lecturers'     => $this->userModel->where('division', 'LECTURER')->findAll()
      ];

      return view('admin/appointments/index', $data);
   }

   /**
    * Tambahkan penugasan baru
    */
   public function add()
   {
      $number   = $this->request->getPost('number');
      $stageId  = $this->request->getPost('stage_id');
      $thesisId = $this->request->getPost('thesis_id');

      if (!$number || !$stageId || !$thesisId) {
         return redirect()->back()->with('error', 'All fields are required!');
      }

      $this->appointmentsModel->addAppointment($number, $stageId, $thesisId);
      return redirect()->to('/admin/appointments')->with('success', 'Appointment added successfully.');
   }

   /**
    * Hapus penugasan
    */
   public function delete($id)
   {
      $this->appointmentsModel->deleteAppointment($id);
      return redirect()->to('/admin/appointments')->with('success', 'Appointment deleted successfully.');
   }
}
