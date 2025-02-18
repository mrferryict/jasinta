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
      'is_repeating',
      'password',
      'ip_address',
      'activation_token',
      'created_at',
      'expired_at'
   ];
   protected $useTimestamps = true;

   /**
    * Tambahkan pendaftar baru ke tabel temporary_users
    */
   public function addTemporaryUser($data)
   {
      return $this->insert($data);
   }

   /**
    * Ambil pendaftar berdasarkan email
    */
   public function getTemporaryUserByEmail($email)
   {
      return $this->where('email', $email)->first();
   }

   /**
    * Ambil pendaftar berdasarkan token aktivasi
    */
   public function getTemporaryUserByToken($token)
   {
      return $this->where('activation_token', $token)->first();
   }

   /**
    * Pindahkan pengguna dari temporary_users ke users setelah aktivasi
    */
   public function activateUser($temporaryUser)
   {
      $db = \Config\Database::connect();
      $builder = $db->table('users');

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
         'is_repeating' => $temporaryUser['is_repeating'],
         'token'        => null,
         'token_expired_at' => null,
         'verified_at'  => date('Y-m-d H:i:s'),
         'created_at'   => date('Y-m-d H:i:s'),
         'updated_at'   => date('Y-m-d H:i:s')
      ];

      // Insert data ke tabel users
      $builder->insert($userData);
      $userId = $db->insertID();

      // Hapus dari temporary_users setelah berhasil dipindahkan
      return $this->delete($temporaryUser['id']);
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
      $semester = $db->table('semesters')->select('id')->where('is_active', 1)->get()->getRowArray();
      return $semester ? $semester['id'] : null;
   }

   /**
    * Hapus pendaftar yang sudah kadaluarsa (token expired)
    */
   public function deleteExpiredUsers()
   {
      return $this->where('expired_at <', date('Y-m-d H:i:s'))->delete();
   }

   /**
    * Hapus pendaftar dari temporary_users secara manual
    */
   public function deleteTemporaryUser($email)
   {
      return $this->where('email', $email)->delete();
   }
}
