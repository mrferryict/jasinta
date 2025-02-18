<?php

namespace App\Models;

use CodeIgniter\Model;

class RequirementSubmissionsModel extends Model
{
   protected $table            = 'requirement_submissions';
   protected $primaryKey       = 'id';
   protected $allowedFields    = ['requirement_id', 'student_id', 'file_path', 'file_type', 'submitted_at'];
   protected $useTimestamps    = false;

   /**
    * Ambil semua unggahan berdasarkan syarat tertentu
    */
   public function getSubmissionsByRequirement($requirementId)
   {
      return $this->where('requirement_id', $requirementId)->findAll();
   }

   /**
    * Simpan unggahan dari STUDENT
    */
   public function saveSubmission($requirementId, $studentId, $filePath, $fileType)
   {
      return $this->insert([
         'requirement_id' => $requirementId,
         'student_id'     => $studentId,
         'file_path'      => $filePath,
         'file_type'      => $fileType,
         'submitted_at'   => date('Y-m-d H:i:s')
      ]);
   }

   /**
    * Hapus unggahan berdasarkan ID
    */
   public function deleteSubmission($submissionId)
   {
      return $this->delete($submissionId);
   }
}
