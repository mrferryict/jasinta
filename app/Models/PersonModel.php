<?php

namespace App\Models;

use CodeIgniter\Model;

class PersonModel extends Model
{
   protected $table = 'persons';
   protected $primaryKey = 'id';
   protected $allowedFields = [
      'name',
      'email',
      'number',
      'major_id',
      'division',
      'semester_id',
      'created_at',
      'deleted_at'
   ];
   protected $useTimestamps = true;
   protected $createdField = 'created_at';
   protected $updatedField = 'updated_at';
   protected $deletedField = 'deleted_at';
   protected $useSoftDeletes = true;

   public function getPersonByEmail($email)
   {
      return $this->where('email', $email)->first();
   }
}
