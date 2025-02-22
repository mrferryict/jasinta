<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
   protected $table = 'users';
   protected $primaryKey = 'id';
   protected $allowedFields = [
      'name',
      'email',
      'number',
      'major_id',
      'division',
      'semester_id',
      'is_repeating',
      'verified_at',
      'created_at',
      'updated_at',
   ];
   protected $useTimestamps = true;
   protected $createdField = 'created_at';
   protected $updatedField = 'updated_at';

   /**
    * Get student information by user ID
    */
   public function getStudentByUserId($userId)
   {
      return $this->where('id', $userId)
         ->where('division', 'STUDENT')
         ->first();
   }

   /**
    * Get the current stage of a student
    */
   public function getStudentStage($student_id)
   {
      return $this->db->table('user_stages')
         ->select('stages.id, stages.name, stages.order, user_stages.started_at, user_stages.passed_at')
         ->join('stages', 'stages.id = user_stages.stage_id', 'left')
         ->where('user_stages.user_id', $student_id)
         ->orderBy('stages.order', 'DESC')
         ->limit(1)
         ->get()
         ->getRowArray(); // Mengembalikan satu baris sebagai array
   }


   /**
    * Get all active students
    */
   public function getAllActiveStudents()
   {
      return $this->where('division', 'STUDENT')
         ->where('deleted_at IS NULL')
         ->findAll();
   }

   /**
    * Get student details including their progress stage
    */
   public function getStudentDetails($studentId)
   {
      return $this->db->table('users')
         ->select('
            users.*,
            COALESCE(stages.name, "") AS stage_name,
            COALESCE(theses.title, "") AS thesis_title,
            (CASE
                WHEN users.verified_at IS NULL THEN "INACTIVE"
                ELSE "ACTIVE"
            END) AS student_status
        ')
         ->join('theses', 'theses.student_id = users.id', 'left') // Menghubungkan ke tugas akhir
         ->join('user_stages', 'user_stages.user_id = users.id', 'left') // Menghubungkan ke tahapan mahasiswa
         ->join('stages', 'stages.id = user_stages.stage_id', 'left') // Ambil nama tahap
         ->where('users.id', $studentId)
         ->orderBy('stages.order', 'DESC') // Ambil tahapan tertinggi
         ->limit(1) // Hanya ambil satu hasil
         ->get()
         ->getRowArray(); // Mengembalikan hasil sebagai array
   }


   /**
    * Get all students with their progress stage
    */
   public function getAllStudents()
   {
      return $this->select('users.*, stages.name AS stage_name, thesis.title AS thesis_title')
         ->join('thesis', 'thesis.student_id = users.id', 'left')
         ->join('progress', 'progress.thesis_id = thesis.id', 'left')
         ->join('stages', 'stages.id = progress.stage_id', 'left')
         ->where('users.division', 'STUDENT')
         ->findAll();
   }

   /**
    * Get students who are on track based on deadlines
    */
   public function countStudentsOnTrack($deadlines)
   {
      $students = $this->getAllStudents();
      $onTrackCount = 0;

      foreach ($students as $student) {
         $stageKey = 'deadline_' . strtoupper($student['stage_name']);
         $deadlineDate = isset($deadlines[$stageKey]) ? strtotime($deadlines[$stageKey]) : null;
         $stageDate = strtotime($student['updated_at'] ?? date('Y-m-d'));

         if ($deadlineDate && $stageDate <= $deadlineDate) {
            $onTrackCount++;
         }
      }

      return $onTrackCount;
   }

   /**
    * Check student's registration status (Pending / Approved)
    */
   public function getStudentRegistrationStatus($studentId)
   {
      return $this->select('verified_at')
         ->where('id', $studentId)
         ->first();
   }

   // STUDENT PROGRESS
   public function getStudentProgress()
   {
      return $this->select('users.id, users.name, users.number, stages.order AS `order`, stages.name AS stage, user_stages.started_at, user_stages.passed_at')
         ->join('user_stages', 'user_stages.user_id = users.id', 'left')
         ->join('stages', 'stages.id = user_stages.stage_id', 'left')
         ->where('users.division', 'STUDENT')
         ->where('user_stages.passed_at IS NULL') // Hanya ambil yang belum lulus tahapannya
         ->orderBy('stages.order', 'DESC')
         ->get()
         ->getResultArray();
   }


   // Menghitung total STUDENT yang sedang aktif
   public function countActiveStudents()
   {
      return $this->db->table('users')
         ->join('theses', 'theses.student_id = users.id', 'inner')
         ->where('users.division', 'STUDENT')
         ->where('users.status', 'ACTIVE')
         ->countAllResults();
   }
}
