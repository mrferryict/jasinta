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
        $this->call('StagesSeeder');
        $this->call('UserSeeder');
        $this->call('UserPersonSeeder');
        $this->call('UserRolesSeeder');
        $this->call('ThesisSeeder');
        $this->call('ProgressSeeder');
        $this->call('SettingsSeeder');
    }
}
