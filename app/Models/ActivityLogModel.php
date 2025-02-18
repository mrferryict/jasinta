<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityLogModel extends Model
{
   protected $table = 'logs';
   protected $primaryKey = 'id';
   protected $allowedFields = [
      'user_id',
      'thesis_id',
      'ip_address',
      'action',
      'description',
      'created_at'
   ];
   protected $useTimestamps = true;
   protected $createdField = 'created_at';

   /**
    * Tambahkan catatan log baru
    */
   public function logActivity($userId, $action, $description, $thesisId = null)
   {
      return $this->insert([
         'user_id'    => $userId,
         'thesis_id'  => $thesisId,
         'ip_address' => $_SERVER['REMOTE_ADDR'],
         'action'     => $action,
         'description' => $description,
         'created_at' => date('Y-m-d H:i:s')
      ]);
   }

   /**
    * Ambil log aktivitas berdasarkan user
    */
   public function getUserLogs($userId)
   {
      return $this->where('user_id', $userId)
         ->orderBy('created_at', 'DESC')
         ->findAll();
   }

   /**
    * Ambil semua log aktivitas dengan informasi pengguna
    */
   public function getAllLogs()
   {
      return $this->select('logs.*, users.name as actor_name')
         ->join('users', 'users.id = logs.user_id', 'left')
         ->orderBy('logs.created_at', 'DESC')
         ->findAll();
   }
}
