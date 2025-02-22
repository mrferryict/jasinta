<?php

namespace App\Models;

use CodeIgniter\Model;

class ThesisModel extends Model
{
   protected $table = 'theses';
   protected $primaryKey = 'id';
   protected $allowedFields = ['title', 'student_id', 'created_at', 'updated_at'];
   protected $useTimestamps = true;
   protected $createdField = 'created_at';
   protected $updatedField = 'updated_at';

   /**
    * Ambil semua data tugas akhir dengan informasi mahasiswa, jurusan, dan semester
    */
   public function getAllTheses()
   {
      return $this->select('
            thesis.id, thesis.title,
            users.name AS student_name, users.number AS student_number,
            majors.name AS major_name, semesters.name AS semester_name
        ')
         ->join('users', 'users.id = thesis.student_id', 'left')
         ->join('majors', 'majors.id = users.major_id', 'left')
         ->join('semesters', 'semesters.id = users.semester_id', 'left')
         ->where('users.division', 'STUDENT')
         ->orderBy('thesis.created_at', 'DESC')
         ->findAll();
   }

   /**
    * Ambil tugas akhir berdasarkan ID mahasiswa
    */
   public function getThesisByStudentId($studentId)
   {
      return $this->where('student_id', $studentId)->first();
   }

   /**
    * Ambil tahapan terbaru dari tugas akhir mahasiswa
    */
   public function getThesisStage($thesisId)
   {
      return $this->db->table('progress')
         ->select('stages.name AS stage_name, progress.created_at AS stage_date')
         ->join('stages', 'stages.id = progress.stage_id', 'left')
         ->where('progress.thesis_id', $thesisId)
         ->orderBy('progress.created_at', 'DESC')
         ->limit(1)
         ->get()
         ->getRowArray();
   }

   /**
    * Ambil daftar pembimbing dan penguji tugas akhir
    */
   public function getThesisSupervisors($thesisId)
   {
      return $this->db->table('appointment_details')
         ->select('users.name AS lecturer_name, appointment_details.task_type')
         ->join('users', 'users.id = appointment_details.user_id', 'left')
         ->join('appointments', 'appointments.id = appointment_details.appointment_id', 'left')
         ->where('appointments.thesis_id', $thesisId)
         ->get()
         ->getResultArray();
   }

   /**
    * Ambil daftar tugas akhir yang belum disetujui (belum ada progress)
    */
   public function getUnapprovedTheses()
   {
      return $this->select('thesis.*, users.name AS student_name, users.number AS student_number')
         ->join('users', 'users.id = thesis.student_id', 'left')
         ->whereNotIn('thesis.id', function ($builder) {
            return $builder->select('progress.thesis_id')->from('progress');
         })
         ->orderBy('thesis.created_at', 'DESC')
         ->findAll();
   }

   /**
    * Menyetujui tugas akhir (menambah progress pertama)
    */
   public function approveThesis($thesisId)
   {
      $data = [
         'thesis_id' => $thesisId,
         'stage_id'  => 1, // ID tahap pertama (PENDAFTARAN)
         'description' => 'Tugas Akhir Disetujui',
         'created_at' => date('Y-m-d H:i:s')
      ];
      return $this->db->table('progress')->insert($data);
   }

   /**
    * Hapus tugas akhir (soft delete)
    */
   public function deleteThesis($thesisId)
   {
      return $this->delete($thesisId);
   }
}
