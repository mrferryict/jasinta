<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StagesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['id' => 1, 'name' => 'DAFTAR PROPOSAL'],
            ['id' => 2, 'name' => 'PROPOSAL'],
            ['id' => 3, 'name' => 'BIMBINGAN BAB-1'],
            ['id' => 4, 'name' => 'BIMBINGAN BAB-2'],
            ['id' => 5, 'name' => 'BIMBINGAN BAB-3'],
            ['id' => 6, 'name' => 'BIMBINGAN BAB-4'],
            ['id' => 7, 'name' => 'BIMBINGAN BAB-5'],
            ['id' => 8, 'name' => 'DAFTAR SIDANG'],
            ['id' => 9, 'name' => 'SIDANG'],
            ['id' => 10, 'name' => 'REVISI AKHIR'],
            ['id' => 11, 'name' => 'SELESAI'],
        ];

        $this->db->table('stages')->insertBatch($data);
    }
}
