<?php

namespace App\Models;

use CodeIgniter\Model;

class AppointmentDetailModel extends Model
{
   protected $table            = 'appointment_details';
   protected $primaryKey       = 'id';
   protected $allowedFields    = ['appointment_id', 'user_id', 'task_type', 'created_at', 'updated_at', 'deleted_at'];

   /**
    * Ambil semua penugasan dosen berdasarkan appointment_id
    */
   public function getAppointmentsByAppointmentId($appointmentId)
   {
      return $this->select('appointment_details.*, users.name as lecturer_name')
         ->join('users', 'users.id = appointment_details.user_id', 'left')
         ->where('appointment_details.appointment_id', $appointmentId)
         ->orderBy('appointment_details.created_at', 'DESC')
         ->findAll();
   }

   /**
    * Tambah penugasan dosen (pembimbing atau penguji)
    */
   public function addAppointmentDetail($appointmentId, $userId, $taskType)
   {
      return $this->insert([
         'appointment_id' => $appointmentId,
         'user_id'        => $userId,
         'task_type'      => $taskType,
         'created_at'     => date('Y-m-d H:i:s')
      ]);
   }

   /**
    * Hapus penugasan berdasarkan ID
    */
   public function deleteAppointmentDetail($appointmentDetailId)
   {
      return $this->delete($appointmentDetailId);
   }
}
