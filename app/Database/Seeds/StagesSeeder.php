<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StagesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['id' => 1, 'name' => 'PENDAFTARAN'],
            ['id' => 2, 'name' => 'SYARAT PROPOSAL'],
            ['id' => 3, 'name' => 'PROPOSAL'],
            ['id' => 4, 'name' => 'BAB 1'],
            ['id' => 5, 'name' => 'BAB 2'],
            ['id' => 6, 'name' => 'BAB 3'],
            ['id' => 7, 'name' => 'BAB 4'],
            ['id' => 8, 'name' => 'BAB 5'],
            ['id' => 9, 'name' => 'SYARAT SIDANG'],
            ['id' => 10, 'name' => 'SIDANG'],
            ['id' => 11, 'name' => 'REVISI'],
            ['id' => 12, 'name' => 'SKLS'],
        ];

        // Menyisipkan data ke dalam tabel 'stages'
        $this->db->table('stages')->insertBatch($data);
    }
}
