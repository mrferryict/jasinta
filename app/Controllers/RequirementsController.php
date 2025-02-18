<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RequirementsModel;
use App\Models\StageModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class RequirementsController extends BaseController
{
   protected $requirementsModel;
   protected $stageModel;

   public function __construct()
   {
      $this->requirementsModel = new RequirementsModel();
      $this->stageModel       = new StageModel();
   }

   /**
    * Tampilkan daftar syarat pra-proposal
    */
   public function index()
   {
      $data = [
         'pageTitle'    => 'Manage Requirements',
         'requirements' => $this->requirementsModel->getAllRequirements(),
         'stages'       => $this->stageModel->findAll()
      ];

      return view('admin/requirements/index', $data);
   }

   /**
    * Tambah syarat baru
    */
   public function add()
   {
      $stageId     = $this->request->getPost('stage_id');
      $description = $this->request->getPost('description');
      $adminId     = session()->get('user_id'); // Ambil ID Admin

      if (!$stageId || !$description) {
         return redirect()->back()->with('error', 'All fields are required!');
      }

      $this->requirementsModel->addRequirement($stageId, $description, $adminId);
      return redirect()->to('/admin/requirements')->with('success', 'Requirement added successfully.');
   }

   /**
    * Hapus syarat berdasarkan ID
    */
   public function delete($id)
   {
      if (!$this->requirementsModel->find($id)) {
         throw new PageNotFoundException('Requirement not found');
      }

      $this->requirementsModel->deleteRequirement($id);
      return redirect()->to('/admin/requirements')->with('success', 'Requirement deleted successfully.');
   }
}
