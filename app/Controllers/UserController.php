<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;

class UserController extends ResourceController
{
   protected $userModel;

   public function __construct()
   {
      $this->userModel = new UserModel();
   }

   /**
    * API: Toggle User Status (Activate/Deactivate)
    * URL: POST /users/toggle-status
    */
   public function toggleStatus()
   {
      $userId = $this->request->getPost('id');
      $newStatus = $this->request->getPost('status');

      log_message('debug', "Toggle Status API called with ID: {$userId}, Status: {$newStatus}");

      // Cek apakah `id` dan `status` tidak kosong
      if (empty($userId) || $newStatus === null) {
         log_message('error', "Invalid input received: ID={$userId}, Status={$newStatus}");
         return $this->response->setJSON(['success' => false, 'message' => 'Invalid input']);
      }

      // Cek apakah user dengan ID tersebut ada
      $user = $this->userModel->find($userId);
      if (!$user) {
         log_message('error', "User not found with ID: {$userId}");
         return $this->response->setJSON(['success' => false, 'message' => 'User not found']);
      }

      // Lakukan update status
      $updateResult = $this->userModel->update($userId, ['status' => $newStatus]);

      // Cek apakah update berhasil
      if ($updateResult) {
         log_message('debug', "User status updated successfully for ID: {$userId}");
         return $this->response->setJSON(['success' => true, 'message' => 'Status updated successfully']);
      } else {
         log_message('error', "Failed to update status for ID: {$userId}");
         return $this->response->setJSON(['success' => false, 'message' => 'Failed to update status']);
      }
   }

   /**
    * API: Delete User
    * URL: POST /users/delete
    */
   public function deleteUser()
   {
      $userId = $this->request->getPost('id');

      if (!$userId) {
         return $this->response->setJSON(['success' => false, 'message' => 'User ID is required']);
      }

      if ($this->userModel->delete($userId)) {
         return $this->response->setJSON(['success' => true, 'message' => 'User deleted successfully']);
      }

      return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete user']);
   }
}
