<?php

namespace App\Database\Seeders;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class LeaveTypesSeeder extends Seeder
{
    public function run()
    {
        $leaveTypes = [
            [
                'name' => 'Annual Leave',
                'code' => 'ANNUAL',
                'description' => 'Regular annual leave for rest and recreation',
                'category' => 'annual',
                'color' => '#3581B8',
                'icon' => 'bi-sun',
                'is_paid' => 1,
                'requires_attachment' => 0,
                'allow_half_day' => 1,
                'is_active' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
            [
                'name' => 'Sick Leave',
                'code' => 'SICK',
                'description' => 'Leave due to illness or medical condition',
                'category' => 'sick',
                'color' => '#E74C3C',
                'icon' => 'bi-thermometer-half',
                'is_paid' => 1,
                'requires_attachment' => 1,
                'allow_half_day' => 1,
                'is_active' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
            [
                'name' => 'Maternity Leave',
                'code' => 'MATERNITY',
                'description' => 'Leave for expecting mothers',
                'category' => 'maternity',
                'color' => '#E91E63',
                'icon' => 'bi-heart',
                'is_paid' => 1,
                'requires_attachment' => 1,
                'allow_half_day' => 0,
                'is_active' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
            [
                'name' => 'Paternity Leave',
                'code' => 'PATERNITY',
                'description' => 'Leave for new fathers',
                'category' => 'paternity',
                'color' => '#9C27B0',
                'icon' => 'bi-person-heart',
                'is_paid' => 1,
                'requires_attachment' => 1,
                'allow_half_day' => 0,
                'is_active' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
            [
                'name' => 'Compassionate Leave',
                'code' => 'COMPASSIONATE',
                'description' => 'Leave for family emergencies or bereavement',
                'category' => 'compassionate',
                'color' => '#607D8B',
                'icon' => 'bi-emoji-frown',
                'is_paid' => 1,
                'requires_attachment' => 1,
                'allow_half_day' => 0,
                'is_active' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
            [
                'name' => 'Study Leave',
                'code' => 'STUDY',
                'description' => 'Leave for educational purposes',
                'category' => 'study',
                'color' => '#FF9800',
                'icon' => 'bi-book',
                'is_paid' => 1,
                'requires_attachment' => 1,
                'allow_half_day' => 1,
                'is_active' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
            [
                'name' => 'Unpaid Leave',
                'code' => 'UNPAID',
                'description' => 'Leave without pay',
                'category' => 'unpaid',
                'color' => '#9E9E9E',
                'icon' => 'bi-currency-dollar',
                'is_paid' => 0,
                'requires_attachment' => 1,
                'allow_half_day' => 1,
                'is_active' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
            [
                'name' => 'Special Leave',
                'code' => 'SPECIAL',
                'description' => 'Special circumstances leave',
                'category' => 'special',
                'color' => '#00BCD4',
                'icon' => 'bi-star',
                'is_paid' => 1,
                'requires_attachment' => 1,
                'allow_half_day' => 1,
                'is_active' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
        ];

        $this->db->table('leave_types')->insertBatch($leaveTypes);
    }
}
