<?php

namespace App\Models;

use CodeIgniter\Model;

class RequirementsModel extends Model
{
   protected $table            = 'requirements';
   protected $primaryKey       = 'id';
   protected $allowedFields    = ['stage_id', 'description', 'created_by', 'created_at', 'updated_at', 'deleted_at'];
   protected $useTimestamps    = true;

   /**
    * Ambil semua syarat berdasarkan tahap tertentu
    */
   public function getRequirementsByStage($stageId)
   {
      return $this->where('stage_id', $stageId)->findAll();
   }

   /**
    * Tambah syarat baru oleh ADMIN
    */
   public function addRequirement($stageId, $description, $adminId)
   {
      return $this->insert([
         'stage_id'   => $stageId,
         'description' => $description,
         'created_by' => $adminId,
         'created_at' => date('Y-m-d H:i:s'),
      ]);
   }

   /**
    * Hapus syarat berdasarkan ID
    */
   public function deleteRequirement($requirementId)
   {
      return $this->delete($requirementId);
   }

   /**
    * Ambil semua syarat yang dibuat oleh ADMIN
    */
   public function getAllRequirements()
   {
      return $this->select('requirements.*, users.name as admin_name')
         ->join('users', 'users.id = requirements.created_by', 'left')
         ->orderBy('requirements.created_at', 'DESC')
         ->findAll();
   }
}
