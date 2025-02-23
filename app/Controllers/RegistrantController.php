<?php

namespace App\Controllers;

use App\Models\TemporaryUserModel;
use App\Models\LogModel;
use CodeIgniter\RESTful\ResourceController;

class RegistrantController extends ResourceController
{
   protected $temporaryUserModel;
   protected $logModel;
   protected $currentUserId;

   public function __construct()
   {
      $this->temporaryUserModel = new TemporaryUserModel();
      $this->logModel = new LogModel();
      $this->currentUserId    = (int) session()->get('user_id');
   }

   /**
    * API: Approve registrant
    * URL: POST /registrant/approve
    */
   public function approve()
   {
      $userId = $this->request->getPost('id');

      if (!$userId) {
         return $this->response->setJSON(['success' => false, 'message' => 'User ID is required']);
      }

      if ($this->temporaryUserModel->approve($userId)) {
         $this->temporaryUserModel->deleteTemporaryUserById($userId);
         $this->logModel->logActivity($this->currentUserId, 'APPROVE_REGISTRANT', $this->temporaryUserModel->getEmailbyId($userId));
         return $this->response->setJSON(['success' => true, 'message' => 'User approved successfully']);
      }

      return $this->response->setJSON(['success' => false, 'message' => 'Failed to approve registrant']);
   }

   /**
    * API: Reject registrant
    * URL: POST /registrant/reject
    */
   public function reject()
   {
      $userId = $this->request->getPost('id');

      if (!$userId) {
         return $this->response->setJSON(['success' => false, 'message' => 'User ID is required']);
      }

      $this->logModel->logActivity($this->currentUserId, 'REJECT_REGISTRANT', $this->temporaryUserModel->getEmailbyId($userId));
      if ($this->temporaryUserModel->reject($userId)) {
         return $this->response->setJSON(['success' => true, 'message' => 'User rejected successfully']);
      }

      return $this->response->setJSON(['success' => false, 'message' => 'Failed to reject registrant']);
   }
}
