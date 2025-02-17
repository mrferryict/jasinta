<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\StageModel;

class SettingsSeeder extends Seeder
{
    public function run()
    {
        $stageModel = new StageModel();
        $stages = array_column($stageModel->orderBy('id', 'ASC')->findAll(), 'name');

        $data = [
            ['key' => 'app_name', 'value' => 'JASINTA', 'created_at' => date('Y-m-d H:i:s')],
            ['key' => 'campuss_name', 'value' => 'STMIK JAYAKARTA', 'created_at' => date('Y-m-d H:i:s')],
            ['key' => 'copyright', 'value' => ' - &copy;2025 JASINTA - STMIK JAYAKARTA SISTEM INFORMASI MONITORING TUGAS AKHIR', 'created_at' => date('Y-m-d H:i:s')],
        ];

        // Insert batch data ke dalam tabel settings
        $this->db->table('settings')->insertBatch($data);
    }
}
