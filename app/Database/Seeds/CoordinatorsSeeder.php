<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CoordinatorsSeeder extends Seeder
{
   public function run()
   {
      $db = \Config\Database::connect();

      // Ambil ID user berdasarkan nama
      $usersTable = $db->table('users');
      $ito    = $usersTable->where('name', 'Ito Risis Immasari')->get()->getRow();
      $zulhalim = $usersTable->where('name', 'Zulhalim')->get()->getRow();
      $thomas   = $usersTable->where('name', 'Thomas Budiman')->get()->getRow();

      // Ambil ID major berdasarkan nama
      $majorsTable = $db->table('majors');
      $d3_mi  = $majorsTable->where('name', 'D3/MANAJEMEN INFORMATIKA')->get()->getRow();
      $s1_si  = $majorsTable->where('name', 'S1/SISTEM INFORMASI')->get()->getRow();
      $s1_ti  = $majorsTable->where('name', 'S1/TEKNIK INFORMATIKA')->get()->getRow();

      // Data untuk tabel coordinators
      $data = [
         [
            'major_id' => $d3_mi->id ?? null,
            'lecturer_id'  => $ito->id ?? null,
         ],
         [
            'major_id' => $s1_si->id ?? null,
            'lecturer_id'  => $zulhalim->id ?? null,
         ],
         [
            'major_id' => $s1_ti->id ?? null,
            'lecturer_id'  => $thomas->id ?? null,
         ],
      ];

      // Hanya insert jika major_id dan user_id tidak null
      $filteredData = array_filter($data, fn($row) => $row['major_id'] !== null && $row['lecturer_id'] !== null);

      // Insert ke tabel coordinators
      if (!empty($filteredData)) {
         $this->db->table('coordinators')->insertBatch($filteredData);
      }
   }
}
