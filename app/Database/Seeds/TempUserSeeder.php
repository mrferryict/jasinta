<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TempUserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['name' => 'BAYU WIDODO', 'email' => 'bayu@stmik.com', 'nim' => '23362012', 'academic_status' => 'NEW', 'password' => password_hash('1234', PASSWORD_DEFAULT), 'ip_address' => '192.111.10.5', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'HENGKI SETIAWAN', 'email' => 'hengki@stmik.com', 'nim' => '22361005', 'academic_status' => 'NEW', 'password' => password_hash('1234', PASSWORD_DEFAULT), 'ip_address' => '192.111.10.5', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'ARI SETIASARI', 'email' => 'ari@stmik.com', 'nim' => '22561009', 'academic_status' => 'NEW', 'password' => password_hash('1234', PASSWORD_DEFAULT), 'ip_address' => '192.111.10.5', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'BAMBANG TEJO', 'email' => 'bambang@stmik.com', 'nim' => '23572035', 'academic_status' => 'NEW', 'password' => password_hash('1234', PASSWORD_DEFAULT), 'ip_address' => '192.111.10.5', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'RITA MAHENDRA', 'email' => 'rita@stmik.com', 'nim' => '23572022', 'academic_status' => 'NEW', 'password' => password_hash('1234', PASSWORD_DEFAULT), 'ip_address' => '192.111.10.5', 'created_at' => date('Y-m-d H:i:s')],
        ];
        $this->db->table('temporary_users')->insertBatch($data);
    }
}
