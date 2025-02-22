<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class All extends Seeder
{
    public function run()
    {
        // Panggil file seeder lainnya
        $this->call('MajorsSeeder');
        $this->call('SemestersSeeder');
        $this->call('StagesSeeder');
        $this->call('UserSeeder');
        $this->call('ThesesSeeder');
        $this->call('SettingsSeeder');
        $this->call('AnnouncementsSeeder');
        $this->call('TempUserSeeder');
        $this->call('LecturersSeeder');
        $this->call('CoordinatorsSeeder');
        $this->call('UserStagesSeeder');
        $this->call('AssignmentSeeder');
        $this->call('ExaminersSeeder');
    }
}
