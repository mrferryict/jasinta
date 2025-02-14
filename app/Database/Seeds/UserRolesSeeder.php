<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserRolesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['user_id' => 1, 'role_id' => 2],
            ['user_id' => 2, 'role_id' => 3],
            ['user_id' => 3, 'role_id' => 3],
            ['user_id' => 4, 'role_id' => 3],
            ['user_id' => 5, 'role_id' => 1],
        ];
        for ($i = 6; $i <= 15; $i++) {
            $data[] = ['user_id' => $i, 'role_id' => 2];
        }

        // Insert batch data ke tabel roles
        $this->db->table('user_roles')->insertBatch($data);
    }
}
