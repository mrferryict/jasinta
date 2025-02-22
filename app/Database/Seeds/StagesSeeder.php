<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StagesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['order' => 1, 'name' => 'PENDAFTARAN', 'deadline_at' => date('2024-10-10')],
            ['order' => 2, 'name' => 'SYARAT PROPOSAL', 'deadline_at' => date('2024-10-20')],
            ['order' => 3, 'name' => 'PROPOSAL', 'deadline_at' => date('2024-10-30')],
            ['order' => 4, 'name' => 'BAB 1', 'deadline_at' => date('2024-11-10')],
            ['order' => 5, 'name' => 'BAB 2', 'deadline_at' => date('2024-11-20')],
            ['order' => 6, 'name' => 'BAB 3', 'deadline_at' => date('2024-12-20')],
            ['order' => 7, 'name' => 'BAB 4', 'deadline_at' => date('2025-01-20')],
            ['order' => 8, 'name' => 'BAB 5', 'deadline_at' => date('2025-01-30')],
            ['order' => 9, 'name' => 'SYARAT PRASIDANG', 'deadline_at' => date('2025-02-10')],
            ['order' => 10, 'name' => 'SIDANG', 'deadline_at' => date('2025-02-15')],
            ['order' => 11, 'name' => 'REVISI', 'deadline_at' => date('2025-03-01')],
            ['order' => 12, 'name' => 'SYARAT AKHIR', 'deadline_at' => date('2025-03-15')],
            ['order' => 13, 'name' => 'SKLS', 'deadline_at' => date('2025-03-30')],
        ];
        // Menyisipkan data ke dalam tabel 'stages'
        $this->db->table('stages')->insertBatch($data);
    }
}
