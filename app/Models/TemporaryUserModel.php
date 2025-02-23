<?php

namespace App\Models;

use CodeIgniter\Model;

class TemporaryUserModel extends Model
{
   protected $table = 'temporary_users';
   protected $primaryKey = 'id';
   protected $allowedFields = [
      'name',
      'email',
      'nim',
      'academic_status',
      'password',
      'ip_address',
      'activation_token',
      'created_at',
      'expired_at'
   ];

   /**
    * Ambil semua pendaftar
    */
   public function getAllTemporaryUsers()
   {
      return $this->findAll();
   }

   public function getTemporaryUserById($id)
   {
      return $this->where('id', $id)->first();
   }

   public function getTemporaryUserByEmail($email)
   {
      return $this->where('email', $email)->first();
   }

   public function getEmailbyId($id)
   {
      $result = $this->select('email')->where('id', $id)->first();
      log_message('info', '***** $result = ' . $result);
      return $result ? $result['email'] : null;
   }

   public function getTemporaryUserByToken($token)
   {
      return $this->where('activation_token', $token)->first();
   }

   public function addTemporaryUser($data)
   {
      $result = $this->insert($data);
      return $result;
   }

   /**
    * Tentukan jurusan berdasarkan NIM
    */
   private function determineMajor($nim)
   {
      $majorCode = substr($nim, 2, 2); // Digit ke-3 dan ke-4 dari NIM

      switch ($majorCode) {
         case '36':
            return 3; // D3 Manajemen Informatika
         case '56':
            return 2; // S1 Sistem Informasi
         case '57':
            return 1; // S1 Teknik Informatika
         default:
            return null; // Jika tidak sesuai format
      }
   }

   /**
    * Ambil ID semester aktif
    */
   private function getActiveSemester()
   {
      $db = \Config\Database::connect();
      $semester = $db->table('semesters')->select('id')->where('status', 'ACTIVE')->get()->getRowArray();
      return $semester ? $semester['id'] : null;
   }

   /**
    * Hapus pendaftar yang sudah kadaluarsa (token expired)
    */
   public function deleteExpiredUsers()
   {
      return $this->where('expired_at <', date('Y-m-d H:i:s'))->delete();
   }

   public function deleteTemporaryUserById($id)
   {
      return $this->where('id', $id)->delete();
   }

   public function reject($temporaryUserId)
   {
      return $this->deleteTemporaryUserById($temporaryUserId);
   }

   public function approve($temporaryUserId)
   {
      return $this->activateUser($temporaryUserId);
   }

   /**
    * Pindahkan pengguna dari temporary_users ke users setelah aktivasi
    */
   public function activateUser($id)
   {
      $db = \Config\Database::connect();
      $builder = $db->table('users');

      $temporaryUser = $this->getTemporaryUserById($id);

      $majorId = $this->determineMajor($temporaryUser['nim']);
      $semesterId = $this->getActiveSemester();

      $userData = [
         'name'         => $temporaryUser['name'],
         'email'        => $temporaryUser['email'],
         'number'       => $temporaryUser['nim'],
         'password'     => $temporaryUser['password'],
         'division'     => 'STUDENT',
         'major_id'     => $majorId,
         'semester_id'  => $semesterId,
         'academic_status' => $temporaryUser['academic_status'],
         'token'        => null,
         'token_expired_at' => null,
         'verified_at'  => date('Y-m-d H:i:s'),
      ];

      // Insert data ke tabel users
      return $builder->insert($userData);
   }
}
