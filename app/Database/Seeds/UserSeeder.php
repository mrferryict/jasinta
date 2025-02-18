<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class UserSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        // Contoh Data Persons (Mahasiswa, Dosen, Administrator)
        $users = [
            ['STUDENT', 'student@student.com', '23560001', 'STUDENT', 2, 1],
            ['ADMIN', 'admin@admin.com', '00000000', 'ADMIN', null, null],
            ['LECTURER', 'lecturer@lecturer.com', '12345678', 'LECTURER', null, null],
            ['Thomas Budiman', 'thomas@stmik.com', '10000001', 'LECTURER', null, null],
            ['Akmal Budi Yulianto', 'akmal@stmik.com', '10000002', 'LECTURER', null, null],
            ['Chandra Setiawan', 'chandra@stmik.com', '23570001', 'STUDENT', 1, 1],
            ['Dede Yusuf', 'dede@stmik.com', '23560002', 'STUDENT', 2, 1],
            ['Eko Purnomo', 'eko@stmik.com', '23360003', 'STUDENT', 3, 1],
            ['Fajar Setiawan', 'fajar@stmik.com', '23360004', 'STUDENT', 3, 1],
            ['Handi Sukatama', 'handi@stmik.com', '23560005', 'STUDENT', 2, 1],
            ['John Sitanggang', 'john@stmik.com', '23570006', 'STUDENT', 1, 1],
            ['Marjono', 'marjono@stmik.com', '23570007', 'STUDENT', 1, 1],
            ['Nana Sujana', 'nana@stmik.com', '23560008', 'STUDENT', 2, 1],
            ['Opin Saputra', 'opin@stmik.com', '23560009', 'STUDENT', 2, 1],
            ['Riris Setyo', 'riris@stmik.com', '23570010', 'STUDENT', 1, 1],
        ];
        foreach ($users as $u) {
            $db->table('users')->insert([
                'name'          => $u[0],
                'email'         => $u[1],
                'password'      => password_hash('1234', PASSWORD_DEFAULT),
                'number'        => $u[2],
                'division'      => $u[3],
                'major_id'      => $u[4],
                'semester_id'   => $u[5],
                'verified_at'   => date('Y-m-d H:i:s'),
                'created_at'    => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
