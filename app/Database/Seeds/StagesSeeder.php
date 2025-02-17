<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StagesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['id' => 1, 'name' => 'PENDAFTARAN', 'deadline_at' => date('2024-10-10'), 'route' => 'registration'],
            ['id' => 2, 'name' => 'SYARAT PROPOSAL', 'deadline_at' => date('2024-10-20'), 'route' => 'requirement/prapoposal'],
            ['id' => 3, 'name' => 'PROPOSAL', 'deadline_at' => date('2024-10-30'), 'route' => 'proposal'],
            ['id' => 4, 'name' => 'BAB 1', 'deadline_at' => date('2024-11-10'), 'route' => 'supervision/bab1'],
            ['id' => 5, 'name' => 'BAB 2', 'deadline_at' => date('2024-11-20'), 'route' => 'supervision/bab2'],
            ['id' => 6, 'name' => 'BAB 3', 'deadline_at' => date('2024-12-20'), 'route' => 'supervision/bab3'],
            ['id' => 7, 'name' => 'BAB 4', 'deadline_at' => date('2025-01-20'), 'route' => 'supervision/bab4'],
            ['id' => 8, 'name' => 'BAB 5', 'deadline_at' => date('2025-01-30'), 'route' => 'supervision/bab5'],
            ['id' => 9, 'name' => 'SYARAT SIDANG', 'deadline_at' => date('2025-02-10'), 'route' => 'requirement/prasidang'],
            ['id' => 10, 'name' => 'SIDANG', 'deadline_at' => date('2025-02-15'), 'route' => 'defense'],
            ['id' => 11, 'name' => 'REVISI', 'deadline_at' => date('2025-03-01'), 'route' => 'revision'],
            ['id' => 12, 'name' => 'SYARAT AKHIR', 'deadline_at' => date('2025-03-15'), 'route' => 'requirement/pascasidang'],
            ['id' => 13, 'name' => 'SKLS', 'deadline_at' => date('2025-03-30'), 'route' => 'skls'],
        ];

        // Menyisipkan data ke dalam tabel 'stages'
        $this->db->table('stages')->insertBatch($data);
    }
}
