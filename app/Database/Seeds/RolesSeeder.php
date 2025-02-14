<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RolesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['id' => 1, 'name' => 'ADMIN'],
            ['id' => 2, 'name' => 'STUDENT'],
            ['id' => 3, 'name' => 'LECTURER'],
        ];

        // Insert batch data ke tabel roles
        $this->db->table('roles')->insertBatch($data);
    }
}
