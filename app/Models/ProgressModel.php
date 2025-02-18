<?php

namespace App\Models;

use CodeIgniter\Model;

class ProgressModel extends Model
{
   protected $table = 'progress';
   protected $primaryKey = 'id';
   protected $allowedFields = ['thesis_id', 'stage_id', 'description', 'created_at', 'updated_at'];
   protected $useTimestamps = true;
   protected $createdField = 'created_at';
   protected $updatedField = 'updated_at';

   /**
    * Get student progress including stage and thesis title
    */
   public function getStudentProgress()
   {
      return $this->db->table('users')
         ->select('users.id, users.name, users.number, stages.name as stage, progress.created_at as stage_date')
         ->join('thesis', 'thesis.student_id = users.id', 'left')
         ->join('progress', 'progress.thesis_id = thesis.id', 'left')
         ->join('stages', 'stages.id = progress.stage_id', 'left')
         ->where('users.division', 'STUDENT')
         ->orderBy('users.name', 'ASC')
         ->get()
         ->getResultArray();
   }


   /**
    * Get all stage deadlines from settings
    */
   public function getStageDeadlines()
   {
      return $this->db->table('settings')
         ->select('key, value')
         ->like('key', 'deadline_')
         ->get()
         ->getResultArray();
   }

   /**
    * Get the latest stage of a student
    */
   public function getStudentStage($studentId)
   {
      return $this->db->table('progress')
         ->select('stages.name')
         ->join('stages', 'stages.id = progress.stage_id', 'left')
         ->join('thesis', 'thesis.id = progress.thesis_id', 'left')
         ->where('thesis.student_id', $studentId)
         ->orderBy('progress.created_at', 'DESC')
         ->limit(1)
         ->get()
         ->getRowArray();
   }

   /**
    * Insert new progress for a thesis
    */
   public function addProgress($thesisId, $stageId, $description = null)
   {
      return $this->insert([
         'thesis_id'   => $thesisId,
         'stage_id'    => $stageId,
         'description' => $description,
         'created_at'  => date('Y-m-d H:i:s'),
         'updated_at'  => date('Y-m-d H:i:s')
      ]);
   }

   /**
    * Delete progress record (soft delete)
    */
   public function deleteProgress($progressId)
   {
      return $this->where('id', $progressId)->delete();
   }
}
