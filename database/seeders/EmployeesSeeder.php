<?php

namespace App\Database\Seeders;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;
use Illuminate\Support\Facades\Hash;

class EmployeesSeeder extends Seeder
{
    public function run()
    {
        $employees = [
            [
                'employee_number' => 'EMP001',
                'first_name' => 'John',
                'last_name' => 'Mwaisekwa',
                'email' => 'john.mwaisekwa@likizopro.co.tz',
                'phone' => '+255 712 345 678',
                'department_id' => 1, // HR
                'location_id' => 1, // Head Office
                'job_grade_id' => 7, // Senior Manager
                'supervisor_id' => null,
                'work_schedule_id' => 1, // Standard 5-Day
                'employment_type' => 'full_time',
                'employment_status' => 'active',
                'hire_date' => '2020-01-15',
                'probation_end_date' => '2020-04-15',
                'gender' => 'male',
                'date_of_birth' => '1985-06-20',
                'address' => 'Masaki, Dar es Salaam',
                'is_active' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
            [
                'employee_number' => 'EMP002',
                'first_name' => 'Sarah',
                'last_name' => 'Mushi',
                'email' => 'sarah.mushi@likizopro.co.tz',
                'phone' => '+255 713 456 789',
                'department_id' => 1, // HR
                'location_id' => 1,
                'job_grade_id' => 5, // Lead
                'supervisor_id' => 1,
                'work_schedule_id' => 1,
                'employment_type' => 'full_time',
                'employment_status' => 'active',
                'hire_date' => '2021-03-01',
                'probation_end_date' => '2021-06-01',
                'gender' => 'female',
                'date_of_birth' => '1990-08-15',
                'address' => 'Oysterbay, Dar es Salaam',
                'is_active' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
            [
                'employee_number' => 'EMP003',
                'first_name' => 'Michael',
                'last_name' => 'Chande',
                'email' => 'michael.chande@likizopro.co.tz',
                'phone' => '+255 714 567 890',
                'department_id' => 2, // IT
                'location_id' => 1,
                'job_grade_id' => 6, // Manager
                'supervisor_id' => null,
                'work_schedule_id' => 1,
                'employment_type' => 'full_time',
                'employment_status' => 'active',
                'hire_date' => '2019-06-01',
                'probation_end_date' => '2019-09-01',
                'gender' => 'male',
                'date_of_birth' => '1982-03-10',
                'address' => 'Mikocheni, Dar es Salaam',
                'is_active' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
            [
                'employee_number' => 'EMP004',
                'first_name' => 'Grace',
                'last_name' => 'Kassim',
                'email' => 'grace.kassim@likizopro.co.tz',
                'phone' => '+255 715 678 901',
                'department_id' => 3, // Finance
                'location_id' => 1,
                'job_grade_id' => 4, // Senior
                'supervisor_id' => null,
                'work_schedule_id' => 1,
                'employment_type' => 'full_time',
                'employment_status' => 'active',
                'hire_date' => '2022-01-10',
                'probation_end_date' => '2022-04-10',
                'gender' => 'female',
                'date_of_birth' => '1992-11-25',
                'address' => 'Kibaha, Coast Region',
                'is_active' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
            [
                'employee_number' => 'EMP005',
                'first_name' => 'David',
                'last_name' => 'Nyerere',
                'email' => 'david.nyerere@likizopro.co.tz',
                'phone' => '+255 716 789 012',
                'department_id' => 4, // Marketing
                'location_id' => 2, // Arusha
                'job_grade_id' => 3, // Associate
                'supervisor_id' => null,
                'work_schedule_id' => 1,
                'employment_type' => 'full_time',
                'employment_status' => 'active',
                'hire_date' => '2023-07-01',
                'probation_end_date' => '2023-10-01',
                'gender' => 'male',
                'date_of_birth' => '1995-04-18',
                'address' => 'Arusha City Centre',
                'is_active' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
            [
                'employee_number' => 'EMP006',
                'first_name' => 'Amina',
                'last_name' => 'Hassan',
                'email' => 'amina.hassan@likizopro.co.tz',
                'phone' => '+255 717 890 123',
                'department_id' => 5, // Operations
                'location_id' => 3, // Mwanza
                'job_grade_id' => 4, // Senior
                'supervisor_id' => null,
                'work_schedule_id' => 1,
                'employment_type' => 'full_time',
                'employment_status' => 'active',
                'hire_date' => '2021-09-15',
                'probation_end_date' => '2021-12-15',
                'gender' => 'female',
                'date_of_birth' => '1988-12-05',
                'address' => 'Mwanza City',
                'is_active' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
            [
                'employee_number' => 'EMP007',
                'first_name' => 'Robert',
                'last_name' => 'Kimambo',
                'email' => 'robert.kimambo@likizopro.co.tz',
                'phone' => '+255 718 901 234',
                'department_id' => 6, // Sales
                'location_id' => 1,
                'job_grade_id' => 2, // Junior
                'supervisor_id' => null,
                'work_schedule_id' => 1,
                'employment_type' => 'full_time',
                'employment_status' => 'probation',
                'hire_date' => '2026-01-02',
                'probation_end_date' => '2026-04-02',
                'gender' => 'male',
                'date_of_birth' => '1998-07-22',
                'address' => 'Sinza, Dar es Salaam',
                'is_active' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
            [
                'employee_number' => 'EMP008',
                'first_name' => 'Fatuma',
                'last_name' => 'Abdalla',
                'email' => 'fatuma.abdalla@likizopro.co.tz',
                'phone' => '+255 719 012 345',
                'department_id' => 7, // Customer Service
                'location_id' => 4, // Zanzibar
                'job_grade_id' => 3, // Associate
                'supervisor_id' => null,
                'work_schedule_id' => 2, // 6-Day
                'employment_type' => 'full_time',
                'employment_status' => 'active',
                'hire_date' => '2024-03-01',
                'probation_end_date' => '2024-06-01',
                'gender' => 'female',
                'date_of_birth' => '1993-09-30',
                'address' => 'Stone Town, Zanzibar',
                'is_active' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
        ];

        $this->db->table('employees')->insertBatch($employees);
    }
}
