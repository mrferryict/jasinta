<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MajorsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['id' => 1, 'name' => 'S1/Teknik Informatika'],
            ['id' => 2, 'name' => 'S1/Sistem Informasi'],
            ['id' => 3, 'name' => 'D3/Manajemen Informatika'],
        ];

        $this->db->table('majors')->insertBatch($data);
    }
}
