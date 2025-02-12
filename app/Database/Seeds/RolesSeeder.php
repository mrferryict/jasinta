<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RolesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['id' => 1, 'name' => 'ADMINISTRATOR'],
            ['id' => 2, 'name' => 'MAHASISWA'],
            ['id' => 3, 'name' => 'DOSEN PEMBIMBING TEKNIS'],
            ['id' => 4, 'name' => 'DOSEN PEMBIMBING MATERI'],
            ['id' => 5, 'name' => 'DOSEN PENGUJI'],
        ];

        // Insert batch data ke tabel roles
        $this->db->table('roles')->insertBatch($data);
    }
}
