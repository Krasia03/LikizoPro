<?php

namespace App\Database\Seeders;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class JobGradesSeeder extends Seeder
{
    public function run()
    {
        $grades = [
            ['name' => 'Intern', 'code' => 'INT', 'level' => 1, 'description' => 'Internship position', 'is_active' => 1, 'created_at' => Time::now()->toDateTimeString(), 'updated_at' => Time::now()->toDateTimeString()],
            ['name' => 'Junior', 'code' => 'JNR', 'level' => 2, 'description' => 'Junior level', 'is_active' => 1, 'created_at' => Time::now()->toDateTimeString(), 'updated_at' => Time::now()->toDateTimeString()],
            ['name' => 'Associate', 'code' => 'ASSO', 'level' => 3, 'description' => 'Associate level', 'is_active' => 1, 'created_at' => Time::now()->toDateTimeString(), 'updated_at' => Time::now()->toDateTimeString()],
            ['name' => 'Senior', 'code' => 'SNR', 'level' => 4, 'description' => 'Senior level', 'is_active' => 1, 'created_at' => Time::now()->toDateTimeString(), 'updated_at' => Time::now()->toDateTimeString()],
            ['name' => 'Lead', 'code' => 'LD', 'level' => 5, 'description' => 'Team lead', 'is_active' => 1, 'created_at' => Time::now()->toDateTimeString(), 'updated_at' => Time::now()->toDateTimeString()],
            ['name' => 'Manager', 'code' => 'MGR', 'level' => 6, 'description' => 'Manager level', 'is_active' => 1, 'created_at' => Time::now()->toDateTimeString(), 'updated_at' => Time::now()->toDateTimeString()],
            ['name' => 'Senior Manager', 'code' => 'SNR-MGR', 'level' => 7, 'description' => 'Senior Manager', 'is_active' => 1, 'created_at' => Time::now()->toDateTimeString(), 'updated_at' => Time::now()->toDateTimeString()],
            ['name' => 'Director', 'code' => 'DIR', 'level' => 8, 'description' => 'Director level', 'is_active' => 1, 'created_at' => Time::now()->toDateTimeString(), 'updated_at' => Time::now()->toDateTimeString()],
            ['name' => 'Executive', 'code' => 'EXE', 'level' => 9, 'description' => 'Executive level', 'is_active' => 1, 'created_at' => Time::now()->toDateTimeString(), 'updated_at' => Time::now()->toDateTimeString()],
        ];

        $this->db->table('job_grades')->insertBatch($grades);
    }
}
