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
        $persons = [
            [
                'name'       => 'Ferry Hariyanto',
                'email'      => 'ferry@example.com',
                'number'     => '23566001', // NIM
                'major_id'   => 1, // Sesuaikan dengan major yang ada
                'division'   => 'MAHASISWA',
                'semester_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'Thomas Budiman, S.Kom, M.TI',
                'email'      => 'thomas@example.com',
                'number'     => '0312067703', // NIDN
                'major_id'   => null,
                'division'   => 'DOSEN',
                'semester_id' => null,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'Akmal Budi Yulianto, ST, MM',
                'email'      => 'akmal@example.com',
                'number'     => '0308078002', // NIDN
                'major_id'   => null,
                'division'   => 'DOSEN',
                'semester_id' => null,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'Ir. Ifan Junaedi, M.Kom',
                'email'      => 'ifan@example.com',
                'number'     => '0318126401', // NIDN
                'major_id'   => null,
                'division'   => 'DOSEN',
                'semester_id' => null,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'Asih',
                'email'      => 'asih@example.com',
                'number'     => 'A12345678', // ID Admin
                'major_id'   => null,
                'division'   => 'ADMINISTRATOR',
                'semester_id' => null,
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Insert ke tabel persons
        foreach ($persons as $person) {
            $db->table('persons')->insert($person);
        }

        // Ambil ID dari persons yang baru dimasukkan
        $personMahasiswa = $db->table('persons')->where('email', 'ferry@example.com')->get()->getRow();
        $personDosen1 = $db->table('persons')->where('email', 'thomas@example.com')->get()->getRow();
        $personDosen2 = $db->table('persons')->where('email', 'akmal@example.com')->get()->getRow();
        $personDosen3 = $db->table('persons')->where('email', 'ifan@example.com')->get()->getRow();
        $personAdmin = $db->table('persons')->where('email', 'asih@example.com')->get()->getRow();

        // Contoh Data Users
        $users = [
            [
                'person_id'        => $personMahasiswa->id,
                'password'         => password_hash('1234', PASSWORD_DEFAULT),
                'status'           => 'active',
                'registered_ip'    => '127.0.0.1',
                'verified_at'      => date('Y-m-d H:i:s'),
                'approved_at'      => date('Y-m-d H:i:s'),
                'created_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'person_id'        => $personDosen1->id,
                'password'         => password_hash('1234', PASSWORD_DEFAULT),
                'status'           => 'active',
                'registered_ip'    => '127.0.0.1',
                'verified_at'      => date('Y-m-d H:i:s'),
                'approved_at'      => date('Y-m-d H:i:s'),
                'created_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'person_id'        => $personDosen2->id,
                'password'         => password_hash('1234', PASSWORD_DEFAULT),
                'status'           => 'active',
                'registered_ip'    => '127.0.0.1',
                'verified_at'      => date('Y-m-d H:i:s'),
                'approved_at'      => date('Y-m-d H:i:s'),
                'created_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'person_id'        => $personDosen3->id,
                'password'         => password_hash('1234', PASSWORD_DEFAULT),
                'status'           => 'active',
                'registered_ip'    => '127.0.0.1',
                'verified_at'      => date('Y-m-d H:i:s'),
                'approved_at'      => date('Y-m-d H:i:s'),
                'created_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'person_id'        => $personAdmin->id,
                'password'         => password_hash('1234', PASSWORD_DEFAULT),
                'status'           => 'active',
                'registered_ip'    => '127.0.0.1',
                'verified_at'      => date('Y-m-d H:i:s'),
                'approved_at'      => date('Y-m-d H:i:s'),
                'created_at'       => date('Y-m-d H:i:s'),
            ],
        ];

        // Insert ke tabel users
        foreach ($users as $user) {
            $db->table('users')->insert($user);
        }
    }
}
