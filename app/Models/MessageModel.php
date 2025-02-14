<?php

namespace App\Models;

use CodeIgniter\Model;

class MessageModel extends Model
{
   protected $table = 'messages';
   protected $primaryKey = 'id';
   protected $allowedFields = [
      'content',
      'sender_id',
      'receiver_id',
      'read_at',
      'created_at',
      'updated_at',
      'deleted_at'
   ];
   protected $useTimestamps = true;
   protected $useSoftDeletes = true;
   protected $createdField = 'created_at';
   protected $updatedField = 'updated_at';
   protected $deletedField = 'deleted_at';

   // Ambil pesan berdasarkan pengirim dan penerima
   public function getMessages($senderId, $receiverId)
   {
      return $this->where('sender_id', $senderId)
         ->where('receiver_id', $receiverId)
         ->orWhere('sender_id', $receiverId)
         ->where('receiver_id', $senderId)
         ->orderBy('created_at', 'ASC')
         ->findAll();
   }

   // Tandai pesan sebagai sudah dibaca
   public function markAsRead($senderId, $receiverId)
   {
      return $this->where('sender_id', $senderId)
         ->where('receiver_id', $receiverId)
         ->where('read_at IS NULL')
         ->set(['read_at' => date('Y-m-d H:i:s')])
         ->update();
   }
}
