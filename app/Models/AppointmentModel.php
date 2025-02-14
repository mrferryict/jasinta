<?php

namespace App\Models;

use CodeIgniter\Model;

class AppointmentModel extends Model
{
   protected $table = 'appointments';
   protected $primaryKey = 'id';
   protected $allowedFields = ['number', 'stage_id', 'thesis_id', 'created_at', 'updated_at', 'deleted_at'];
   protected $useTimestamps = true;
   protected $useSoftDeletes = true;

   /**
    * Get active appointments for a lecturer.
    *
    * @param int $lecturerId
    * @return array
    */
   public function getActiveAppointments($lecturerId)
   {
      return $this->db->table('appointments')
         ->select('appointments.id, appointments.number, appointments.stage_id, appointments.thesis_id, thesis.title, appointment_details.task_type')
         ->join('appointment_details', 'appointment_details.appointment_id = appointments.id', 'inner')
         ->join('thesis', 'thesis.id = appointments.thesis_id', 'inner')
         ->where('appointment_details.person_id', $lecturerId)
         ->where('appointments.deleted_at IS NULL')
         ->get()
         ->getResultArray();
   }

   /**
    * Get appointment details by appointment ID.
    *
    * @param int $appointmentId
    * @return array
    */
   public function getAppointmentDetails($appointmentId)
   {
      return $this->db->table('appointment_details')
         ->select('appointment_details.id, appointment_details.task_type, persons.name, persons.email, persons.number')
         ->join('persons', 'persons.id = appointment_details.person_id', 'inner')
         ->where('appointment_details.appointment_id', $appointmentId)
         ->where('appointment_details.deleted_at IS NULL')
         ->get()
         ->getResultArray();
   }

   /**
    * Assign a lecturer to an appointment.
    *
    * @param int $appointmentId
    * @param int $lecturerId
    * @param string $taskType
    * @return bool
    */
   public function assignLecturer($appointmentId, $lecturerId, $taskType)
   {
      return $this->db->table('appointment_details')->insert([
         'appointment_id' => $appointmentId,
         'person_id' => $lecturerId,
         'task_type' => $taskType,
         'created_at' => date('Y-m-d H:i:s')
      ]);
   }

   /**
    * Get thesis appointments by student ID.
    *
    * @param int $studentId
    * @return array
    */
   public function getStudentAppointments($studentId)
   {
      return $this->db->table('appointments')
         ->select('appointments.id, appointments.number, stages.name AS stage_name, thesis.title')
         ->join('thesis', 'thesis.id = appointments.thesis_id', 'inner')
         ->join('stages', 'stages.id = appointments.stage_id', 'inner')
         ->where('thesis.student_id', $studentId)
         ->where('appointments.deleted_at IS NULL')
         ->get()
         ->getResultArray();
   }

   /**
    * Get students supervised or examined by a lecturer.
    *
    * @param int $lecturerId
    * @return array
    */
   public function getSupervisedStudents($lecturerId)
   {
      return $this->db->table('appointment_details')
         ->select('persons.id AS student_id, persons.name AS student_name, persons.number AS student_nim, thesis.title AS thesis_title, appointment_details.task_type')
         ->join('appointments', 'appointments.id = appointment_details.appointment_id', 'inner')
         ->join('thesis', 'thesis.id = appointments.thesis_id', 'inner')
         ->join('persons', 'persons.id = thesis.student_id', 'inner')
         ->where('appointment_details.person_id', $lecturerId)
         ->where('appointments.deleted_at IS NULL')
         ->where('appointment_details.deleted_at IS NULL')
         ->get()
         ->getResultArray();
   }

   /**
    * Get students examined by a lecturer (Dosen Penguji).
    *
    * @param int $lecturerId
    * @return array
    */
   public function getExaminedStudents($lecturerId)
   {
      return $this->db->table('appointment_details')
         ->select('persons.id AS student_id, persons.name AS student_name, persons.number AS student_nim, thesis.title AS thesis_title, appointment_details.task_type')
         ->join('appointments', 'appointments.id = appointment_details.appointment_id', 'inner')
         ->join('thesis', 'thesis.id = appointments.thesis_id', 'inner')
         ->join('persons', 'persons.id = thesis.student_id', 'inner')
         ->where('appointment_details.person_id', $lecturerId)
         ->where('appointment_details.task_type', 'dosen penguji') // Hanya mengambil peran sebagai dosen penguji
         ->where('appointments.deleted_at IS NULL')
         ->where('appointment_details.deleted_at IS NULL')
         ->get()
         ->getResultArray();
   }
}
