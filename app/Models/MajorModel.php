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
