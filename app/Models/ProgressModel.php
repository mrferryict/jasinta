<?php

namespace App\Models;

use CodeIgniter\Model;

class ProgressModel extends Model
{
   protected $table = 'progress';
   protected $primaryKey = 'id';
   protected $allowedFields = ['thesis_id', 'stage_id', 'description', 'created_at', 'updated_at', 'deleted_at'];
   protected $useTimestamps = true;

   public function getStudentProgress()
   {
      return $this->db->table('persons')
         ->select('persons.id, persons.name, persons.number, stages.name as stage, progress.created_at as stage_date')
         ->join('thesis', 'thesis.student_id = persons.id', 'left')
         ->join('progress', 'progress.thesis_id = thesis.id', 'left')
         ->join('stages', 'stages.id = progress.stage_id', 'left')
         ->where('persons.division', 'STUDENT')
         ->orderBy('persons.name', 'ASC')
         ->get()
         ->getResultArray();
   }

   public function getStageDeadlines()
   {
      return $this->db->table('settings')
         ->select('key, value')
         ->like('key', 'deadline_')
         ->get()
         ->getResultArray();
   }

   // Fungsi untuk mendapatkan tahapan terakhir mahasiswa
   public function getStudentStage($studentId)
   {
      return $this->db->table('progress')
         ->select('stages.name')
         ->join('stages', 'stages.id = progress.stage_id')
         ->join('thesis', 'stages.id = stages.id')
         ->where('thesis.student_id', $studentId)
         ->orderBy('progress.created_at', 'DESC')
         ->limit(1)
         ->get()
         ->getRowArray();
   }
}
