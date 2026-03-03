<?php

namespace App\Database\Seeders;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class LocationsSeeder extends Seeder
{
    public function run()
    {
        $locations = [
            [
                'name' => 'Head Office - Dar es Salaam',
                'code' => 'HO-DAR',
                'address' => 'Samora Avenue, Dar es Salaam',
                'country' => 'Tanzania',
                'timezone' => 'Africa/Dar_es_Salaam',
                'is_active' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
            [
                'name' => 'Branch - Arusha',
                'code' => 'BRN-ARU',
                'address' => 'Sokoine Road, Arusha',
                'country' => 'Tanzania',
                'timezone' => 'Africa/Dar_es_Salaam',
                'is_active' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
            [
                'name' => 'Branch - Mwanza',
                'code' => 'BRN-MWA',
                'address' => 'Nyerere Road, Mwanza',
                'country' => 'Tanzania',
                'timezone' => 'Africa/Dar_es_Salaam',
                'is_active' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
            [
                'name' => 'Remote - Zanzibar',
                'code' => 'RMT-ZNZ',
                'address' => 'Forodhani, Zanzibar',
                'country' => 'Tanzania',
                'timezone' => 'Africa/Dar_es_Salaam',
                'is_active' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
        ];

        $this->db->table('locations')->insertBatch($locations);
    }
}
