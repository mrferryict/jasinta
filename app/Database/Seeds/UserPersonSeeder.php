<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class UserPersonSeeder extends Seeder
{
   public function run()
   {
      $faker = Factory::create('id_ID'); // Menggunakan Faker untuk data dummy

      // Data untuk tabel `persons` dan `users`
      $persons = [];
      $users = [];

      for ($i = 6; $i <= 15; $i++) {
         // Data untuk tabel `persons`
         $prefix = ['2256', '2356', '2257', '2357', '2236', '2336'];
         $persons[] = [
            'name'       => $faker->name,
            'email'      => $faker->unique()->email,
            'number'     => $prefix[rand(0, 5)] . str_pad($i, 4, '0', STR_PAD_LEFT), // Contoh NIM: MHS00001
            'major_id'   => $faker->randomElement([1, 2, 3]), // Random major_id antara 1, 2, atau 3
            'division'   => 'STUDENT',
            'semester_id' => 1, // Semester aktif
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null,
            'deleted_at' => null,
         ];

         // Data untuk tabel `users`
         $users[] = [
            'person_id'        => $i, // ID person sesuai dengan iterasi
            'password'         => password_hash('1234', PASSWORD_DEFAULT), // Password default
            'token'            => null,
            'token_expired_at' => null,
            'verified_at'      => date('Y-m-d H:i:s'),
            'created_at'       => date('Y-m-d H:i:s'),
            'updated_at'       => null,
         ];
      }

      // Insert data ke tabel `persons`
      $this->db->table('persons')->insertBatch($persons);

      // Insert data ke tabel `users`
      $this->db->table('users')->insertBatch($users);
   }
}
