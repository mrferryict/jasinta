<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserStagesSeeder extends Seeder
{
   public function run()
   {
      $db = \Config\Database::connect();

      // Ambil semua student aktif
      $students = $db->table('users')
         ->where('division', 'STUDENT')
         ->where('status', 'ACTIVE')
         ->get()
         ->getResultArray();

      $data = [];
      $minDate = strtotime('2024-10-01');

      foreach ($students as $student) {
         $userId = $student['id'];
         $selectedStage = rand(1, 13);
         $startedAt = $minDate; // Mulai dari tanggal paling awal

         for ($i = 1; $i <= $selectedStage; $i++) {
            $nextStartedAt = ($i < $selectedStage) ? strtotime("+" . rand(3, 15) . " days", $startedAt) : null;

            $data[] = [
               'user_id'    => $userId,
               'stage_id'   => $i,
               'started_at' => date('Y-m-d', $startedAt),
               'passed_at'  => $nextStartedAt ? date('Y-m-d', $nextStartedAt) : null,
            ];

            $startedAt = $nextStartedAt ?? $startedAt; // Simpan tanggal untuk tahap berikutnya
         }
      }

      if (!empty($data)) {
         $db->table('user_stages')->insertBatch($data);
      }
   }
}
