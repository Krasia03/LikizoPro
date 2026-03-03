<?php

namespace App\Database\Seeders;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class WorkSchedulesSeeder extends Seeder
{
    public function run()
    {
        $schedules = [
            [
                'name' => 'Standard 5-Day',
                'code' => 'STD-5D',
                'monday_start' => '08:00:00',
                'monday_end' => '17:00:00',
                'tuesday_start' => '08:00:00',
                'tuesday_end' => '17:00:00',
                'wednesday_start' => '08:00:00',
                'wednesday_end' => '17:00:00',
                'thursday_start' => '08:00:00',
                'thursday_end' => '17:00:00',
                'friday_start' => '08:00:00',
                'friday_end' => '17:00:00',
                'saturday_start' => null,
                'saturday_end' => null,
                'sunday_start' => null,
                'sunday_end' => null,
                'is_default' => 1,
                'is_active' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
            [
                'name' => '6-Day Work Week',
                'code' => 'STD-6D',
                'monday_start' => '08:00:00',
                'monday_end' => '17:00:00',
                'tuesday_start' => '08:00:00',
                'tuesday_end' => '17:00:00',
                'wednesday_start' => '08:00:00',
                'wednesday_end' => '17:00:00',
                'thursday_start' => '08:00:00',
                'thursday_end' => '17:00:00',
                'friday_start' => '08:00:00',
                'friday_end' => '17:00:00',
                'saturday_start' => '09:00:00',
                'saturday_end' => '13:00:00',
                'sunday_start' => null,
                'sunday_end' => null,
                'is_default' => 0,
                'is_active' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
            [
                'name' => 'Shift - Morning',
                'code' => 'SHIFT-AM',
                'monday_start' => '06:00:00',
                'monday_end' => '14:00:00',
                'tuesday_start' => '06:00:00',
                'tuesday_end' => '14:00:00',
                'wednesday_start' => '06:00:00',
                'wednesday_end' => '14:00:00',
                'thursday_start' => '06:00:00',
                'thursday_end' => '14:00:00',
                'friday_start' => '06:00:00',
                'friday_end' => '14:00:00',
                'saturday_start' => null,
                'saturday_end' => null,
                'sunday_start' => null,
                'sunday_end' => null,
                'is_default' => 0,
                'is_active' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
            [
                'name' => 'Shift - Night',
                'code' => 'SHIFT-PM',
                'monday_start' => '14:00:00',
                'monday_end' => '22:00:00',
                'tuesday_start' => '14:00:00',
                'tuesday_end' => '22:00:00',
                'wednesday_start' => '14:00:00',
                'wednesday_end' => '22:00:00',
                'thursday_start' => '14:00:00',
                'thursday_end' => '22:00:00',
                'friday_start' => '14:00:00',
                'friday_end' => '22:00:00',
                'saturday_start' => null,
                'saturday_end' => null,
                'sunday_start' => null,
                'sunday_end' => null,
                'is_default' => 0,
                'is_active' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
        ];

        $this->db->table('work_schedules')->insertBatch($schedules);
    }
}
