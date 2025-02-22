<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LecturersSeeder extends Seeder
{
   public function run()
   {
      $data = [
         [
            'name'  => 'Ito Risis Immasari',
            'email' => 'ito@stmik.com',
            'password' => password_hash('password123', PASSWORD_DEFAULT),
            'division' => 'LECTURER',
            'number' => '20000001',
            'verified_at' => date('Y-m-d')
         ],
         [
            'name'  => 'Zulhalim',
            'email' => 'zulhalim@stmik.com',
            'password' => password_hash('1234', PASSWORD_DEFAULT),
            'division' => 'LECTURER',
            'number' => '20000002',
            'verified_at' => date('Y-m-d')
         ],
         [
            'name'  => 'Thomas Budiman',
            'email' => 'thomas@stmik.com',
            'password' => password_hash('1234', PASSWORD_DEFAULT),
            'division' => 'LECTURER',
            'number' => '20000003',
            'verified_at' => date('Y-m-d')
         ],
         [
            'name'  => 'Ir. Ifan Junaedi',
            'email' => 'ifan@stmik.com',
            'password' => password_hash('1234', PASSWORD_DEFAULT),
            'division' => 'LECTURER',
            'number' => '20000004',
            'verified_at' => date('Y-m-d')
         ],
         [
            'name'  => 'Akmal Budi Yulianto',
            'email' => 'akmal@stmik.com',
            'password' => password_hash('1234', PASSWORD_DEFAULT),
            'division' => 'LECTURER',
            'number' => '20000005',
            'verified_at' => date('Y-m-d')
         ],
         [
            'name'  => 'Anton Zulkarnain Sianipar',
            'email' => 'anton@stmik.com',
            'password' => password_hash('password123', PASSWORD_DEFAULT),
            'division' => 'LECTURER',
            'number' => '20000006',
            'verified_at' => date('Y-m-d')
         ],
      ];

      // Insert ke tabel users
      $this->db->table('users')->insertBatch($data);
   }
}
