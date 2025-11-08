<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Bus; // Import the Bus model

class BusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $buses = [
            [
                'bus_number' => 'B001',
                'capacity' => 40,
                'model' => 'Mercedes-Benz Citaro',
                'year' => 2018,
                'status' => 'active',
            ],
            [
                'bus_number' => 'B002',
                'capacity' => 35,
                'model' => 'Volvo 7900 Electric',
                'year' => 2020,
                'status' => 'active',
            ],
            [
                'bus_number' => 'B003',
                'capacity' => 50,
                'model' => 'Scania Citywide',
                'year' => 2015,
                'status' => 'maintenance',
            ],
            [
                'bus_number' => '01',
                'capacity' => 50,
                'model' => 'Samco Felix',
                'year' => 2022,
                'status' => 'active',
            ],
            [
                'bus_number' => '02',
                'capacity' => 45,
                'model' => 'Thaco Meadow 85S',
                'year' => 2023,
                'status' => 'active',
            ],
            [
                'bus_number' => '03',
                'capacity' => 40,
                'model' => 'Hyundai County',
                'year' => 2021,
                'status' => 'active',
            ],
        ];

        foreach ($buses as $busData) {
            Bus::firstOrCreate(
                ['bus_number' => $busData['bus_number']],
                $busData
            );
        }
    }
}
