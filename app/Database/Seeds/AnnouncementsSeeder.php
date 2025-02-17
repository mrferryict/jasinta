<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\AnnouncementModel;

class AnnouncementsSeeder extends Seeder
{
    public function run()
    {

        $data = [
            ['title' => 'JADWAL SIDANG', 'content' => 'Diumumkan bahwa Sidang akan diadakan mulai 10 Februari 2025', 'admin_id' => 5, 'created_at' => date('2025-01-10 12:10:33')],
            ['title' => 'DEADLINE PROPOSAL', 'content' => 'Proposal harus ditanda tangani dahulu oleh kaprodi sebelum dikumpulkan', 'admin_id' => 5, 'created_at' => date('2024-10-03 08:14:04')],
        ];

        // Insert batch data ke dalam tabel settings
        $this->db->table('announcements')->insertBatch($data);
    }
}
