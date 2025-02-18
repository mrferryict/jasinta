<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
   protected $table = 'users';
   protected $primaryKey = 'id';
   protected $allowedFields = [
      'name',
      'email',
      'number',
      'password',
      'division',
      'major_id',
      'semester_id',
      'is_repeating',
      'token',
      'token_expired_at',
      'verified_at',
      'created_at',
      'updated_at',
      'status'
   ];
   protected $useTimestamps = true;
   protected $createdField = 'created_at';
   protected $updatedField = 'updated_at';

   /**
    * Ambil informasi user berdasarkan email.
    */
   public function getUserByEmail($email)
   {
      return $this->where('email', $email)->first();
   }

   /**
    * Ambil semua pengguna, dengan opsi filter berdasarkan status.
    */
   public function getAllUsers($status = null)
   {
      if (!is_null($status)) {
         return $this->where('status', $status)->findAll();
      }
      return $this->findAll();
   }

   /**
    * Ambil semua pengguna yang aktif.
    */
   public function getActiveUsers()
   {
      return $this->where('status', 1)->findAll();
   }

   /**
    * Ambil pengguna berdasarkan ID.
    */
   public function getUserById($userId)
   {
      return $this->find($userId);
   }

   /**
    * Ubah status pengguna (aktif/nonaktif).
    */
   public function changeUserStatus($userId, $newStatus)
   {
      return $this->update($userId, ['status' => $newStatus]);
   }

   /**
    * Hapus pengguna dengan soft delete.
    */
   public function deleteUser($userId)
   {
      return $this->delete($userId);
   }
}
