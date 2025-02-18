<?php

namespace App\Models;

use CodeIgniter\Model;

class MajorModel extends Model
{
   protected $table            = 'majors';
   protected $primaryKey       = 'id';
   protected $allowedFields    = ['name'];

   /**
    * Get all majors ordered by name.
    */
   public function getAllMajors()
   {
      return $this->orderBy('majors.name', 'ASC')->findAll();
   }

   /**
    * Get major by ID with coordinator details (if available).
    */
   public function getMajorById($id)
   {
      return $this->select('majors.*, users.name AS coordinator_name')
         ->join('coordinators', 'coordinators.major_id = majors.id', 'left')
         ->join('users', 'users.id = coordinators.user_id', 'left')
         ->where('majors.id', $id)
         ->first();
   }

   /**
    * Get major ID by name.
    *
    * @param string $name Nama major yang dicari.
    * @return int|null ID major atau null jika tidak ditemukan.
    */
   public function getMajorIdByName($name)
   {
      $result = $this->select('id')
         ->where('LOWER(name)', strtolower($name)) // Case-insensitive comparison
         ->first();

      return $result ? $result['id'] : null;
   }
}
