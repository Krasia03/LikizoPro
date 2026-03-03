<?php

namespace App\Database\Seeders;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class RolesSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'name' => 'Super Admin',
                'slug' => 'super_admin',
                'description' => 'Full system access with all privileges',
                'is_system' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
            [
                'name' => 'HR Manager',
                'slug' => 'hr_manager',
                'description' => 'HR management with full employee and leave management access',
                'is_system' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
            [
                'name' => 'HR Officer',
                'slug' => 'hr_officer',
                'description' => 'HR staff with leave management and reporting access',
                'is_system' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
            [
                'name' => 'Line Manager',
                'slug' => 'line_manager',
                'description' => 'Team lead with approval authority for direct reports',
                'is_system' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
            [
                'name' => 'Employee',
                'slug' => 'employee',
                'description' => 'Standard employee with self-service leave access',
                'is_system' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
            [
                'name' => 'Payroll',
                'slug' => 'payroll',
                'description' => 'Payroll staff with read-only access to leave data',
                'is_system' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
            [
                'name' => 'Auditor',
                'slug' => 'auditor',
                'description' => 'Read-only access for audit purposes',
                'is_system' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
        ];

        $this->db->table('roles')->insertBatch($roles);
    }
}
