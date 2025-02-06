<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
   protected $table = 'users';
   protected $primaryKey = 'id';
   protected $allowedFields = ['name', 'email', 'password', 'role', 'token', 'token_expired_at', 'verified_at', 'created_at'];
   protected $useTimestamps = true;
}
