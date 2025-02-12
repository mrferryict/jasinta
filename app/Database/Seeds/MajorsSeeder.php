<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MajorsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['id' => 1, 'name' => 'S1/TEKNIK INFORMATIKA'],
            ['id' => 2, 'name' => 'S1/SISTEM INFORMASI'],
            ['id' => 3, 'name' => 'D3/MANAJEMEN INFORMATIKA'],
        ];

        $this->db->table('majors')->insertBatch($data);
    }
}
