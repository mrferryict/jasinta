<?php

namespace App\Models;

use CodeIgniter\Model;

class TemporaryUserModel extends Model
{
   protected $table = 'temporary_users';
   protected $primaryKey = 'id';
   protected $allowedFields = [
      'name',
      'email',
      'nim',
      'is_repeating',
      'password',
      'ip_address',
      'activation_token',
      'created_at',
      'expired_at'
   ];
   protected $useTimestamps = false;
}
