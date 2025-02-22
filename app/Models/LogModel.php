<?php

namespace App\Models;

use CodeIgniter\Model;

class LogModel extends Model
{
   protected $table            = 'logs';
   protected $primaryKey       = 'id';
   protected $allowedFields    = ['user_id', 'action', 'description', 'ip_address', 'user_agent', 'created_at'];

   /**
    * Fungsi untuk menyimpan log aktivitas user.
    */
   public function logActivity($userId, $action, $description)
   {
      if (!$userId) {
         $this->insert([
            'user_id'     => $userId,
            'action'      => $action,
            'description' => $description,
            'ip_address'  => $_SERVER['REMOTE_ADDR'],
            'user_agent'  => $_SERVER['HTTP_USER_AGENT'],
            'created_at'  => date('Y-m-d H:i:s')
         ]);
      }
      return null;
   }

   public function getAllLogs()
   {
      return $this
         ->select('logs.*, users.email')
         ->join('users', 'users.id = logs.user_id ', 'left')
         ->orderBy('created_at', 'DESC')->findAll();
   }
}
