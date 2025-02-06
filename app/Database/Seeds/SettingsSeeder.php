<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['key' => 'app_name', 'value' => 'JASINTA - STMIK JAYAKARTA SISTEM INFORMASI MONITORING TUGAS AKHIR', 'created_at' => date('Y-m-d H:i:s')],
            ['key' => 'campuss_name', 'value' => 'STMIK JAYAKARTA', 'created_at' => date('Y-m-d H:i:s')],
            ['key' => 'copyright', 'value' => 'STMIK JAYAKARTA', 'created_at' => date('Y-m-d H:i:s')],
        ];

        $this->db->table('settings')->insertBatch($data);
    }
}
