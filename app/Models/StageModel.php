<?php

namespace App\Models;

use CodeIgniter\Model;

class StageModel extends Model
{
   protected $table = 'stages';
   protected $primaryKey = 'id';
   protected $allowedFields = ['name', 'route', 'deadline_at'];

   /**
    * Ambil semua tahapan
    */
   public function getAllStages()
   {
      return $this->orderBy('id', 'ASC')->findAll();
   }

   /**
    * Ambil daftar nama semua tahapan dalam sistem
    * @return array
    */
   public function getStageNames()
   {
      return $this->select('name')->orderBy('id', 'ASC')->findAll();
   }

   /**
    * Ambil informasi tahapan berdasarkan ID
    */
   public function getStageById($stageId)
   {
      return $this->where('id', $stageId)->first();
   }

   /**
    * Ambil informasi tahapan berdasarkan nama
    */
   public function getStageByName($stageName)
   {
      return $this->where('name', strtoupper($stageName))->first();
   }

   /**
    * Ambil batas waktu tahap berdasarkan ID
    */
   public function getDeadlineByStageId($stageId)
   {
      $stage = $this->select('deadline_at')->where('id', $stageId)->first();
      return $stage ? $stage['deadline_at'] : null;
   }

   /**
    * Perbarui batas waktu tahapan
    */
   public function updateDeadline($stageId, $newDeadline)
   {
      return $this->update($stageId, ['deadline_at' => $newDeadline]);
   }

   /**
    * Ambil tahapan terakhir yang dicapai mahasiswa berdasarkan ID mahasiswa
    */
   public function getStudentCurrentStage($studentId)
   {
      return $this->db->table('progress')
         ->select('stages.id, stages.name, progress.created_at')
         ->join('stages', 'stages.id = progress.stage_id')
         ->join('thesis', 'thesis.id = progress.thesis_id')
         ->join('users', 'users.id = thesis.student_id')
         ->where('users.id', $studentId)
         ->orderBy('progress.created_at', 'DESC')
         ->limit(1)
         ->get()
         ->getRowArray();
   }


   /**
    * Ambil semua tahapan yang telah dicapai mahasiswa dalam skripsinya
    */
   public function getStageProgress($thesisId)
   {
      return $this->db->table('progress')
         ->select('stages.id, stages.name, progress.description, progress.created_at')
         ->join('stages', 'stages.id = progress.stage_id')
         ->where('progress.thesis_id', $thesisId)
         ->orderBy('progress.created_at', 'ASC')
         ->get()
         ->getResultArray();
   }

   /**
    * Tambahkan progress baru ke mahasiswa
    */
   public function addStageProgress($thesisId, $stageId, $description = null)
   {
      $db = \Config\Database::connect();
      return $db->table('progress')->insert([
         'thesis_id'   => $thesisId,
         'stage_id'    => $stageId,
         'description' => $description,
         'created_at'  => date('Y-m-d H:i:s'),
      ]);
   }
}
