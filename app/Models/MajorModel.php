<?php

namespace App\Models;

use CodeIgniter\Model;

class MajorModel extends Model
{
   protected $table            = 'majors';
   protected $primaryKey       = 'id';
   protected $allowedFields    = ['name', 'coordinator_id'];

   /**
    * Get all majors with optional coordinator details.
    */
   public function getAllMajors()
   {
      return $this->select('majors.*, persons.name AS coordinator_name')
         ->join('persons', 'persons.id = majors.coordinator_id', 'left')
         ->orderBy('majors.name', 'ASC')
         ->findAll();
   }

   /**
    * Get major by ID with coordinator details.
    */
   public function getMajorById($id)
   {
      return $this->select('majors.*, persons.name AS coordinator_name')
         ->join('persons', 'persons.id = majors.coordinator_id', 'left')
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

   /**
    * Check if a person is a coordinator for any major.
    */
   public function isCoordinator($personId)
   {
      return $this->where('coordinator_id', $personId)->countAllResults() > 0;
   }

   /**
    * Assign a person as a coordinator for a major.
    */
   public function assignCoordinator($majorId, $personId)
   {
      return $this->update($majorId, ['coordinator_id' => $personId]);
   }
}
