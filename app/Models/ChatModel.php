<?php

namespace App\Models;

use CodeIgniter\Model;

class ChatModel extends Model
{
   protected $table = 'chats';
   protected $primaryKey = 'id';
   protected $allowedFields = ['sender_id', 'receiver_id', 'message', 'file', 'read_at', 'created_at'];

   public function getAllMessagesForUser($userID)
   {
      return $this->where('sender_id', $userID)
         ->orWhere('receiver_id', $userID)
         ->orderBy('created_at', 'ASC')
         ->findAll();
   }

   public function getChatSendersWithUnreadCount(int $userID)
   {
      return $this->select('users.id, users.name, users.email,
                          COUNT(chats.id) AS total_messages,
                          SUM(CASE WHEN chats.read_at IS NULL AND chats.receiver_id = ' . $userID . ' THEN 1 ELSE 0 END) AS unread_messages,
                          MAX(chats.created_at) AS last_message_time')
         ->join('users', 'users.id = chats.sender_id')
         ->where('chats.receiver_id', $userID)
         ->groupBy('users.id, users.name, users.email')
         ->orderBy('last_message_time', 'DESC')
         ->findAll();
   }


   public function countUnreadMessages($receiverID)
   {
      return $this->where('receiver_id', $receiverID)
         ->where('read_at', null)
         ->countAllResults();
   }

   public function getChatUsers($userID)
   {
      return $this->select('users.id, users.name, users.division, MAX(chats.created_at) as last_message_time')
         ->join('users', 'users.id = chats.sender_id OR users.id = chats.receiver_id')
         ->where('(chats.sender_id = ' . $userID . ' OR chats.receiver_id = ' . $userID . ')')
         ->groupBy('users.id')
         ->orderBy('last_message_time', 'DESC')
         ->findAll();
   }

   public function getChatHistory($receiverID, $userID)
   {
      return $this->where('(sender_id = ' . $receiverID . ' AND receiver_id = ' . $userID . ')')
         ->orWhere('(sender_id = ' . $userID . ' AND receiver_id = ' . $receiverID . ')')
         ->orderBy('created_at', 'ASC')
         ->findAll();
   }

   public function getMessages($id1, $id2)
   {
      if ($id2) {
         return $this->where("(sender_id = $id1 AND receiver_id = $id2)")
            ->orWhere("(sender_id = $id2 AND receiver_id = $id1)")
            ->orderBy('created_at', 'ASC')
            ->findAll();
      }
      return $this->where("sender_id = $id1")
         ->orWhere("receiver_id = $id1")
         ->orderBy('created_at', 'ASC')
         ->findAll();
   }

   // ✅ Menandai pesan sebagai telah dibaca
   public function markMessagesAsRead($receiverID, $userID)
   {
      return $this->where('receiver_id', $receiverID)
         ->where('sender_id', $userID)
         ->where('read_at', null)
         ->set(['read_at' => date('Y-m-d H:i:s')])
         ->update();
   }


   // ✅ Ambil daftar kontak yang pernah mengirim pesan ke admin + hitung total chat & yang belum dibaca
   public function getChatContacts($currentUserID)
   {
      $sql = "
        SELECT
            users.id,
            users.name,
            MAX(chats.created_at) AS last_message_time,
            (SELECT message FROM chats AS sub
             WHERE sub.id = (SELECT MAX(id) FROM chats
                            WHERE (sender_id = users.id AND receiver_id = ?)
                               OR (sender_id = ? AND receiver_id = users.id))) AS last_message,
            COUNT(CASE WHEN chats.receiver_id = ? AND chats.read_at IS NULL THEN 1 END) AS unread_chats
        FROM chats
        JOIN users ON users.id = (
            CASE
                WHEN chats.sender_id = ? THEN chats.receiver_id
                ELSE chats.sender_id
            END
        )
        WHERE chats.sender_id = ? OR chats.receiver_id = ?
        GROUP BY users.id, users.name
        ORDER BY last_message_time DESC
    ";

      return $this->db->query($sql, [$currentUserID, $currentUserID, $currentUserID, $currentUserID, $currentUserID, $currentUserID])->getResultArray();
   }
}
