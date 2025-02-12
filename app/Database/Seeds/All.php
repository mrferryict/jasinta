<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class All extends Seeder
{
    public function run()
    {
        // Panggil file seeder lainnya
        $this->call('MajorsSeeder');
        $this->call('RolesSeeder');
        $this->call('SemestersSeeder');
        $this->call('SettingsSeeder');
        $this->call('StagesSeeder');
        $this->call('UserSeeder');
        $this->call('UserRolesSeeder');
    }
}
