<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SemestersSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['id' => 1, 'name' => '2023/2024-Semester Genap', 'is_active' => true, 'created_at' => date('Y-m-d H:i:s')],
        ];

        $this->db->table('semesters')->insertBatch($data);
    }
}
