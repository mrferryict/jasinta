<?php

namespace App\Models;

use CodeIgniter\Model;

class AppointmentModel extends Model
{
   protected $table            = 'appointments';
   protected $primaryKey       = 'id';
   protected $allowedFields    = ['number', 'stage_id', 'thesis_id', 'created_at', 'updated_at', 'deleted_at'];
   protected $useTimestamps    = true;
   protected $createdField     = 'created_at';
   protected $updatedField     = 'updated_at';

   /**
    * Ambil semua penugasan
    */
   public function getAppointments()
   {
      return $this->db->table('appointments')
         ->select('appointments.*, users.name as lecturer_name')
         ->join('users', 'users.id = appointments.lecturer_id', 'left')
         ->orderBy('appointments.created_at', 'DESC')
         ->get()
         ->getResultArray();
   }


   /**
    * Ambil semua penugasan berdasarkan mahasiswa
    */
   public function getAppointmentsByStudent($studentId)
   {
      return $this->select('appointments.*, stages.name as stage_name, thesis.title as thesis_title')
         ->join('stages', 'stages.id = appointments.stage_id', 'left')
         ->join('thesis', 'thesis.id = appointments.thesis_id', 'left')
         ->where('thesis.student_id', $studentId)
         ->orderBy('appointments.created_at', 'DESC')
         ->findAll();
   }

   /**
    * Tambahkan penugasan baru
    */
   public function addAppointment($number, $stageId, $thesisId)
   {
      return $this->insert([
         'number'     => $number,
         'stage_id'   => $stageId,
         'thesis_id'  => $thesisId,
         'created_at' => date('Y-m-d H:i:s')
      ]);
   }

   /**
    * Hapus penugasan berdasarkan ID
    */
   public function deleteAppointment($appointmentId)
   {
      return $this->delete($appointmentId);
   }

   /**
    * Ambil detail penugasan berdasarkan ID
    */
   public function getAppointmentById($appointmentId)
   {
      return $this->select('appointments.*, stages.name as stage_name, thesis.title as thesis_title, users.name as student_name')
         ->join('stages', 'stages.id = appointments.stage_id', 'left')
         ->join('thesis', 'thesis.id = appointments.thesis_id', 'left')
         ->join('users', 'users.id = thesis.student_id', 'left')
         ->where('appointments.id', $appointmentId)
         ->first();
   }
}
