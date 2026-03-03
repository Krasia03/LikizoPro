<?php

namespace App\Database\Seeders;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RolesSeeder::class,
            PermissionsSeeder::class,
            LocationsSeeder::class,
            DepartmentsSeeder::class,
            JobGradesSeeder::class,
            WorkSchedulesSeeder::class,
            HolidaysSeeder::class,
            LeaveTypesSeeder::class,
            LeavePoliciesSeeder::class,
            EmployeesSeeder::class,
            UserSeeder::class,
            RolePermissionsSeeder::class,
        ]);
    }
}
