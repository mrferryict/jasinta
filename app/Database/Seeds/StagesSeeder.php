<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StagesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['id' => 1, 'name' => 'PENDAFTARAN PROPOSAL'],
            ['id' => 2, 'name' => 'PROPOSAL'],
            ['id' => 3, 'name' => 'BIMBINGAN'],
            ['id' => 4, 'name' => 'PENDAFTARAN SIDANG'],
            ['id' => 5, 'name' => 'SIDANG'],
            ['id' => 6, 'name' => 'REVISI AKHIR'],
            ['id' => 7, 'name' => 'SELESAI'],
            ['id' => 8, 'name' => 'GAGAL'],
        ];

        $this->db->table('stages')->insertBatch($data);
    }
}
