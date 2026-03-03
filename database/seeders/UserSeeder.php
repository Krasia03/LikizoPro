<?php

namespace App\Database\Seeders;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Default Admin User
        $users = [
            [
                'employee_id' => 1,
                'email' => 'admin@likizopro.co.tz',
                'password' => password_hash('123456', PASSWORD_BCRYPT),
                'role_id' => 1, // Super Admin
                'is_active' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
            [
                'employee_id' => 2,
                'email' => 'sarah.mushi@likizopro.co.tz',
                'password' => password_hash('password123', PASSWORD_BCRYPT),
                'role_id' => 2, // HR Manager
                'is_active' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
            [
                'employee_id' => 3,
                'email' => 'michael.chande@likizopro.co.tz',
                'password' => password_hash('password123', PASSWORD_BCRYPT),
                'role_id' => 4, // Line Manager
                'is_active' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
            [
                'employee_id' => 4,
                'email' => 'grace.kassim@likizopro.co.tz',
                'password' => password_hash('password123', PASSWORD_BCRYPT),
                'role_id' => 3, // HR Officer
                'is_active' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
            [
                'employee_id' => 5,
                'email' => 'david.nyerere@likizopro.co.tz',
                'password' => password_hash('password123', PASSWORD_BCRYPT),
                'role_id' => 5, // Employee
                'is_active' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
            [
                'employee_id' => 6,
                'email' => 'amina.hassan@likizopro.co.tz',
                'password' => password_hash('password123', PASSWORD_BCRYPT),
                'role_id' => 5, // Employee
                'is_active' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
            [
                'employee_id' => 7,
                'email' => 'robert.kimambo@likizopro.co.tz',
                'password' => password_hash('password123', PASSWORD_BCRYPT),
                'role_id' => 5, // Employee
                'is_active' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
            [
                'employee_id' => 8,
                'email' => 'fatuma.abdalla@likizopro.co.tz',
                'password' => password_hash('password123', PASSWORD_BCRYPT),
                'role_id' => 5, // Employee
                'is_active' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
        ];

        $this->db->table('users')->insertBatch($users);
    }
}
