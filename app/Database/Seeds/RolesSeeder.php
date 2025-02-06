<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RolesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['id' => 1, 'name' => 'Mahasiswa'],
            ['id' => 2, 'name' => 'Dosen Pembimbing Materi'],
            ['id' => 3, 'name' => 'Dosen Pembimbing Teknis'],
            ['id' => 4, 'name' => 'Dosen Penguji'],
            ['id' => 5, 'name' => 'Administrator']
        ];

        // Insert batch data ke tabel roles
        $this->db->table('roles')->insertBatch($data);
    }
}
