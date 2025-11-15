<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BusRoute;

class MetroBusRouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BusRoute::create([
            'ma_tuyen' => 'Metro 1',
            'diem_di' => 'Bến Thành',
            'diem_den' => 'Suối Tiên',
            'ngay' => '2025-11-09',
            'thoi_gian_bat_dau' => '05:00',
            'thoi_gian_ket_thuc' => '20:00',
            // 'coords' => [
            //     [10.7720, 106.6980], // Bến Thành Station
            //     [10.7750, 106.7050],
            //     [10.7800, 106.7150],
            //     [10.7850, 106.7250],
            //     [10.7900, 106.7350],
            //     [10.7950, 106.7450],
            //     [10.8000, 106.7550],
            //     [10.8050, 106.7650],
            //     [10.8100, 106.7750],
            //     [10.8150, 106.7850],
            //     [10.8200, 106.7950],
            //     [10.8250, 106.8050],
            //     [10.8300, 106.8150],
            //     [10.8350, 106.8250],
            //     [10.8400, 106.8350],
            //     [10.8450, 106.8450],
            //     [10.8500, 106.8550],
            //     [10.8550, 106.8650],
            //     [10.8600, 106.8750],
            //     [10.8650, 106.8850], // Suối Tiên Station
            // ],
        ]);
    }
}
