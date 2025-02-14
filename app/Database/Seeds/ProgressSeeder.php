<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProgressSeeder extends Seeder
{
   public function run()
   {
      $stageModel = new \App\Models\StageModel();

      $progressData = [];

      // Daftar tahapan (stages) yang tersedia
      $stages = array_column($stageModel->orderBy('id', 'ASC')->findAll(), 'name');

      // Loop untuk 10 mahasiswa (student_id 1-10)
      for ($studentId = 6; $studentId <= 15; $studentId++) {
         $stage = rand(1, 12);
         $progressData[] = [
            'thesis_id'   => $studentId - 5, // thesis_id sama dengan student_id
            'stage_id'    => $stage,
            'description' => 'Progress untuk tahap ' . $stages[$stage - 1],
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => null,
            'deleted_at'  => null,
         ];
      }

      // Insert data ke tabel progress
      $this->db->table('progress')->insertBatch($progressData);
   }
}
