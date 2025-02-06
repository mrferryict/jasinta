<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SemestersSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['id' => 1, 'name' => '2023/2024-Semester Genap', 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 2, 'name' => '2024/2025-Semester Ganjil', 'created_at' => date('Y-m-d H:i:s')],
        ];

        $this->db->table('semesters')->insertBatch($data);
    }
}
