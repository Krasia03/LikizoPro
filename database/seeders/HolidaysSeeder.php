<?php

namespace App\Database\Seeders;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class HolidaysSeeder extends Seeder
{
    public function run()
    {
        $holidays = [
            ['name' => 'New Year\'s Day', 'date' => '2026-01-01', 'location_id' => null, 'is_recurring' => 1, 'is_active' => 1, 'created_at' => Time::now()->toDateTimeString(), 'updated_at' => Time::now()->toDateTimeString()],
            ['name' => 'Revolution Day', 'date' => '2026-01-12', 'location_id' => 1, 'is_recurring' => 1, 'is_active' => 1, 'created_at' => Time::now()->toDateTimeString(), 'updated_at' => Time::now()->toDateTimeString()],
            ['name' => 'Good Friday', 'date' => '2026-04-03', 'location_id' => null, 'is_recurring' => 1, 'is_active' => 1, 'created_at' => Time::now()->toDateTimeString(), 'updated_at' => Time::now()->toDateTimeString()],
            ['name' => 'Easter Monday', 'date' => '2026-04-06', 'location_id' => null, 'is_recurring' => 1, 'is_active' => 1, 'created_at' => Time::now()->toDateTimeString(), 'updated_at' => Time::now()->toDateTimeString()],
            ['name' => 'Workers\' Day', 'date' => '2026-05-01', 'location_id' => null, 'is_recurring' => 1, 'is_active' => 1, 'created_at' => Time::now()->toDateTimeString(), 'updated_at' => Time::now()->toDateTimeString()],
            ['name' => 'Saba Day', 'date' => '2026-04-07', 'location_id' => 1, 'is_recurring' => 1, 'is_active' => 1, 'created_at' => Time::now()->toDateTimeString(), 'updated_at' => Time::now()->toDateTimeString()],
            ['name' => 'Union Day', 'date' => '2026-04-26', 'location_id' => null, 'is_recurring' => 1, 'is_active' => 1, 'created_at' => Time::now()->toDateTimeString(), 'updated_at' => Time::now()->toDateTimeString()],
            ['name' => 'Idd-ul-Fitr', 'date' => '2026-03-20', 'location_id' => null, 'is_recurring' => 0, 'is_active' => 1, 'created_at' => Time::now()->toDateTimeString(), 'updated_at' => Time::now()->toDateTimeString()],
            ['name' => 'Idd-ul-Azha', 'date' => '2026-07-09', 'location_id' => null, 'is_recurring' => 0, 'is_active' => 1, 'created_at' => Time::now()->toDateTimeString(), 'updated_at' => Time::now()->toDateTimeString()],
            ['name' => 'Independence Day', 'date' => '2026-12-09', 'location_id' => null, 'is_recurring' => 1, 'is_active' => 1, 'created_at' => Time::now()->toDateTimeString(), 'updated_at' => Time::now()->toDateTimeString()],
            ['name' => 'Christmas Day', 'date' => '2026-12-25', 'location_id' => null, 'is_recurring' => 1, 'is_active' => 1, 'created_at' => Time::now()->toDateTimeString(), 'updated_at' => Time::now()->toDateTimeString()],
            ['name' => 'Boxing Day', 'date' => '2026-12-26', 'location_id' => null, 'is_recurring' => 1, 'is_active' => 1, 'created_at' => Time::now()->toDateTimeString(), 'updated_at' => Time::now()->toDateTimeString()],
        ];

        $this->db->table('holidays')->insertBatch($holidays);
    }
}
