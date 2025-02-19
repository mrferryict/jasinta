<?php

namespace App\Controllers;

use App\Models\LogModel;

class LogController extends BaseController
{
   public function exportToExcel()
   {
      // Ambil data dari request
      $userId = $this->request->getPost('user_id');
      $action = $this->request->getPost('action');
      $description = $this->request->getPost('description');

      // Simpan log
      $logModel = new LogModel();
      $logModel->logActivity($userId, $action, $description);

      // Kirim respons sukses
      return $this->response->setJSON(['status' => 'success']);
   }
}
