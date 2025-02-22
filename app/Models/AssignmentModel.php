<?php

namespace App\Models;

use CodeIgniter\Model;

class AssignmentModel extends Model
{
   protected $table = 'assignments';
   protected $primaryKey = 'id';
   protected $allowedFields = [
      'thesis_id',
      'assignment_type',
      'description',
      'created_at',
      'updated_at'
   ];

   // Get all assignments with related thesis data
   public function getAllAssignments()
   {
      return $this->select('assignments.*, theses.title as thesis_title, users.name as student_name')
         ->join('theses', 'theses.id = assignments.thesis_id', 'left')
         ->join('users', 'users.id = theses.student_id', 'left')
         ->orderBy('assignments.created_at', 'DESC')
         ->findAll();
   }

   // Get assignment details by ID
   public function getAssignmentById($id)
   {
      return $this->select('assignments.*, theses.title as thesis_title, users.name as student_name')
         ->join('theses', 'theses.id = assignments.thesis_id', 'left')
         ->join('users', 'users.id = theses.student_id', 'left')
         ->where('assignments.id', $id)
         ->first();
   }

   // Get assignments by thesis ID
   public function getAssignmentsByThesis($thesisId)
   {
      return $this->where('thesis_id', $thesisId)->findAll();
   }

   /**
    * Mendapatkan daftar mahasiswa yang dibimbing oleh seorang dosen tertentu.
    * @param int $lecturerId ID dosen
    * @return array
    */
   public function getSupervisedStudents($lecturerId)
   {
      return $this->db->table('assignment_lecturers al')
         ->select('u.id AS student_id, u.name AS student_name, u.number AS nim, t.title, al.role')
         ->join('assignments a', 'a.id = al.assignment_id')
         ->join('theses t', 't.id = a.thesis_id')
         ->join('users u', 'u.id = t.student_id')
         ->where('al.lecturer_id', $lecturerId)
         ->whereIn('al.role', ['MATERIAL_SUPERVISOR', 'TECHNICAL_SUPERVISOR']) // Hanya dosen pembimbing
         ->orderBy('u.name', 'ASC')
         ->get()
         ->getResultArray();
   }

   /**
    * Mendapatkan daftar mahasiswa yang diuji oleh seorang dosen tertentu.
    * @param int $lecturerId ID dosen
    * @return array
    */
   public function getExaminedStudents($lecturerId)
   {
      return $this->db->table('assignment_lecturers al')
         ->select('u.id AS student_id, u.name AS student_name,  u.number AS nim, t.title, al.role, al.lecturer_id')
         ->join('assignments a', 'a.id = al.assignment_id')
         ->join('theses t', 't.id = a.thesis_id')
         ->join('users u', 'u.id = t.student_id')
         ->where('al.lecturer_id', $lecturerId)
         ->groupStart()
         ->where('al.role', 'EXAMINER_CHIEF') // Hanya dosen penguji
         ->orWhere('al.role', 'EXAMINER_MEMBER') // Hanya dosen penguji
         ->groupEnd()
         ->orderBy('u.name', 'ASC')
         ->get()
         ->getResultArray();
   }

   /**
    * mencari apakah seorang LECTURER memiliki STUDENT untuk dibimbing, serta perannya sebagai Dosen Pembimbing Materi, Dosen Pembimbing Teknik, atau Dosen Penguji..
    * @param int $lecturerId ID dosen
    * @return array
    */
   public function getLecturerStudents($lecturerId)
   {
      return $this->db->table('assignment_lecturers al')
         ->select('u.id as student_id, u.name as student_name, u.number, t.title as thesis_title, al.role')
         ->join('assignments a', 'a.id = al.assignment_id', 'left')
         ->join('theses t', 't.id = a.thesis_id', 'left')
         ->join('users u', 'u.id = t.student_id', 'left')
         ->where('al.lecturer_id', $lecturerId)
         ->get()
         ->getResultArray();
   }
}
