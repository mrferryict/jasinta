<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
   protected $table = 'users';
   protected $primaryKey = 'id';
   protected $allowedFields = [
      'person_id',
      'password',
      'status',
      'registered_ip',
      'token',
      'token_expired_at',
      'verified_at',
      'approved_at',
      'created_at',
      'updated_at'
   ];
   protected $useTimestamps = true;
   protected $createdField = 'created_at';
   protected $updatedField = 'updated_at';

   public function getUserByEmail($email)
   {
      return $this->select('users.*, persons.email')
         ->join('persons', 'persons.id = users.person_id')
         ->where('persons.email', $email)
         ->first();
   }
}
