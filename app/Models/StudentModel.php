<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
   protected $table = 'persons';
   protected $primaryKey = 'id';
   protected $allowedFields = ['name', 'email', 'number', 'major_id', 'division', 'semester_id', 'created_at', 'updated_at', 'deleted_at'];

   protected $useTimestamps = true;
   protected $useSoftDeletes = true;


   /**
    * Get student information by user ID
    */
   public function getStudentByUserId($userId)
   {
      return $this->where('id', $userId)
         ->where('division', 'MAHASISWA')
         ->first();
   }

   /**
    * Get the current stage of a student
    * @param int $studentId
    * @return string|null
    */
   public function getStudentStage($studentId)
   {
      $result = $this->select('stages.name')
         ->join('users', 'users.person_id = persons.id', 'left')
         ->join('progress', 'progress.thesis_id = thesis.id', 'left')
         ->join('stages', 'stages.id = progress.stage_id', 'left')
         ->join('thesis', 'thesis.student_id = persons.id', 'left')
         ->where('persons.id', $studentId)
         ->first();

      return $result ? $result['name'] : 'PENDAFTARAN'; // Default stage if none found
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
    * Get details of a student including their stage progress
    * @param int $studentId
    * @return array|null
    */
   public function getStudentDetails($studentId)
   {
      return $this->select('persons.*, users.status AS user_status, stages.name AS stage_name, thesis.title AS thesis_title')
         ->join('users', 'users.person_id = persons.id', 'left')
         ->join('thesis', 'thesis.student_id = persons.id', 'left')
         ->join('progress', 'progress.thesis_id = thesis.id', 'left')
         ->join('stages', 'stages.id = progress.stage_id', 'left')
         ->where('persons.id', $studentId)
         ->first();
   }

   /**
    * Get all students with their progress stage
    * @return array
    */
   public function getAllStudents()
   {
      return $this->select('persons.*, stages.name AS stage_name, thesis.title AS thesis_title')
         ->join('users', 'users.person_id = persons.id', 'left')
         ->join('progress', 'progress.thesis_id = thesis.id', 'left')
         ->join('stages', 'stages.id = progress.stage_id', 'left')
         ->join('thesis', 'thesis.student_id = persons.id', 'left')
         ->where('persons.division', 'STUDENT')
         ->findAll();
   }



   /**
    * Get all students who are currently active in the system
    * @return array
    */
   public function getActiveStudents()
   {
      return $this->select('persons.*, stages.name AS stage_name')
         ->join('users', 'users.person_id = persons.id', 'left')
         ->join('progress', 'progress.thesis_id = thesis.id', 'left')
         ->join('stages', 'stages.id = progress.stage_id', 'left')
         ->join('thesis', 'thesis.student_id = persons.id', 'left')
         ->where('users.status', 'ACTIVE')
         ->findAll();
   }

   /**
    * Count total active students
    * @return int
    */
   public function countActiveStudents()
   {
      return $this->join('users', 'users.person_id = persons.id', 'left')
         ->where('users.status', 'ACTIVE')
         ->countAllResults();
   }

   /**
    * Get students who are on track based on deadlines
    * @param array $deadlines
    * @return int
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
      return $this->db->table('users')
         ->select('users.status')
         ->join('persons', 'persons.id = users.person_id')
         ->where('persons.id', $studentId)
         ->orderBy('users.id', 'DESC')
         ->limit(1)
         ->get()
         ->getRowArray();
   }

   /**
    * Get prerequisite requirements for proposal stage
    */
   public function getPrerequisiteRequirements($studentId)
   {
      return $this->db->table('prasyarat')
         ->select('prasyarat.id, prasyarat.description, prasyarat.requirement_type, prasyarat_approvals.status as approval_status')
         ->join('prasyarat_approvals', 'prasyarat.id = prasyarat_approvals.prasyarat_id AND prasyarat_approvals.student_id = ' . $studentId, 'left')
         ->where('prasyarat.stage', 'SYARAT PROPOSAL')
         ->get()
         ->getResultArray();
   }

   /**
    * Save student's submission for a prerequisite requirement
    */
   public function savePrerequisiteSubmission($studentId, $requirementId, $data)
   {
      return $this->db->table('prasyarat_approvals')
         ->insert([
            'prasyarat_id' => $requirementId,
            'student_id' => $studentId,
            'answer' => $data['answer'] ?? null,
            'file_path' => $data['file_path'] ?? null,
            'submitted_at' => date('Y-m-d H:i:s')
         ]);
   }

   /**
    * Get student's thesis title
    */
   public function getStudentThesis($studentId)
   {
      return $this->db->table('thesis')
         ->select('id, title, created_at')
         ->where('student_id', $studentId)
         ->orderBy('created_at', 'DESC')
         ->limit(1)
         ->get()
         ->getRowArray();
   }

   /**
    * Get student's supervisors (Dosen Pembimbing)
    */
   public function getStudentSupervisors($studentId)
   {
      return $this->db->table('appointment_details')
         ->select('persons.id, persons.name, appointment_details.task_type')
         ->join('persons', 'appointment_details.person_id = persons.id')
         ->join('appointments', 'appointment_details.appointment_id = appointments.id')
         ->join('thesis', 'appointments.thesis_id = thesis.id')
         ->where('thesis.student_id', $studentId)
         ->get()
         ->getResultArray();
   }

   /**
    * Get student's evaluation scores
    */
   public function getStudentScores($studentId)
   {
      return $this->db->table('scores')
         ->select('scores.score, scores.comments, persons.name as evaluator')
         ->join('persons', 'scores.evaluator_id = persons.id')
         ->join('thesis', 'scores.thesis_id = thesis.id')
         ->where('thesis.student_id', $studentId)
         ->get()
         ->getResultArray();
   }

   /**
    * Get student's messages (chat history)
    */
   public function getStudentMessages($studentId)
   {
      return $this->db->table('messages')
         ->select('messages.content, messages.sender_id, messages.receiver_id, messages.read_at, messages.created_at')
         ->where('messages.sender_id', $studentId)
         ->orWhere('messages.receiver_id', $studentId)
         ->orderBy('messages.created_at', 'ASC')
         ->get()
         ->getResultArray();
   }

   /**
    * Mark messages as read
    */
   public function markMessagesAsRead($studentId)
   {
      return $this->db->table('messages')
         ->where('receiver_id', $studentId)
         ->where('read_at IS NULL')
         ->update(['read_at' => date('Y-m-d H:i:s')]);
   }
}
