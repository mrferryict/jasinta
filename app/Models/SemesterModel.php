<?php

namespace App\Models;

use CodeIgniter\Model;

class SemesterModel extends Model
{
   protected $table           = 'semesters';
   protected $primaryKey      = 'id';
   protected $allowedFields   = ['name', 'status', 'created_at', 'updated_at'];
   protected $useTimestamps   = true;
   protected $createdField    = 'created_at';
   protected $updatedField    = 'updated_at';

   /**
    * Ambil semester yang sedang aktif.
    */
   public function getActiveSemesters()
   {
      return $this->where('status', 1)->findAll();
   }

   // ambil ID dari semester yang sedang aktif
   public function getActiveSemesterId()
   {
      $activeSemester = $this->where('status', 1)->first();
      if ($activeSemester) {
         return $activeSemester['id'];
      }
      return null;
   }

   /**
    * Set semester aktif, dan menonaktifkan semester lainnya.
    */
   public function setActiveSemester($semesterId)
   {
      // Nonaktifkan semua semester
      $this->update(null, ['is_active' => 0]);

      // Aktifkan semester yang dipilih
      return $this->update($semesterId, ['is_active' => 1]);
   }

   /**
    * Tambah semester baru jika belum ada.
    */
   public function createSemester($name)
   {
      // Cek apakah semester dengan nama yang sama sudah ada
      $existing = $this->where('name', $name)->withDeleted()->first();

      if ($existing) {
         return false; // Mencegah duplikasi
      }

      return $this->insert([
         'name'       => $name,
         'is_active'  => 0, // Default tidak aktif
         'created_at' => date('Y-m-d H:i:s')
      ]);
   }

   /**
    * Hapus semester dengan soft delete.
    */
   public function deleteSemester($semesterId)
   {
      return $this->delete($semesterId);
   }

   /**
    * Pulihkan semester yang telah dihapus (soft delete).
    */
   public function restoreSemester($semesterId)
   {
      return $this->where('id', $semesterId)->set(['deleted_at' => null])->update();
   }

   /**
    * Hapus semester secara permanen dari database.
    */
   public function forceDeleteSemester($semesterId)
   {
      return $this->where('id', $semesterId)->purgeDeleted();
   }
}
