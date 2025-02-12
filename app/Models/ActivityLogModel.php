<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityLogModel extends Model
{
   protected $table = 'logs';
   protected $primaryKey = 'id';
   protected $allowedFields = [
      'actor_id',
      'thesis_id',
      'ip',
      'action',
      'description',
      'created_at'
   ];
   protected $useTimestamps = true;
   protected $createdField = 'created_at';

   // Tambahkan catatan log baru
   public function logActivity($actorId, $action, $description, $thesisId = null)
   {
      return $this->insert([
         'actor_id'   => $actorId,
         'thesis_id'  => $thesisId,
         'ip'         => $_SERVER['REMOTE_ADDR'],
         'action'     => $action,
         'description' => $description,
         'created_at' => date('Y-m-d H:i:s')
      ]);
   }

   // Ambil log aktivitas berdasarkan user
   public function getUserLogs($userId)
   {
      return $this->where('actor_id', $userId)->orderBy('created_at', 'DESC')->findAll();
   }
}
