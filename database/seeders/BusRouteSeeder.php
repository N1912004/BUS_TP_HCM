<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BusRoute; // Import the BusRoute model

class BusRouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the bus routes data
        $routes = [
            [
                'route_number' => '01',
                'start_location' => 'Bến Thành',
                'end_location' => 'Bến Xe Miền Đông',
                'distance' => 15.5,
                'duration' => 45,
                'coords' => [[10.776889, 106.700806], [10.785, 106.71], [10.79, 106.72], [10.80, 106.73], [10.81, 106.74]]
            ],
            [
                'route_number' => '02',
                'start_location' => 'Bến Xe Quận 8',
                'end_location' => 'Đại học Quốc Gia',
                'distance' => 25.0,
                'duration' => 60,
                'coords' => [[10.776889, 106.700806], [10.77, 106.68], [10.76, 106.66], [10.75, 106.64], [10.74, 106.62]]
            ],
            [
                'route_number' => '03',
                'start_location' => 'BX An Sương',
                'end_location' => 'BX Miền Tây',
                'distance' => 20.0,
                'duration' => 50,
                'coords' => [[10.776889, 106.700806], [10.79, 106.65], [10.81, 106.60], [10.83, 106.55], [10.85, 106.50]]
            ],
            [
                'route_number' => '04',
                'start_location' => 'Chợ Lớn',
                'end_location' => 'Bến Xe An Sương',
                'distance' => 18.2,
                'duration' => 40,
                'coords' => [[10.776889, 106.700806], [10.76, 106.72], [10.75, 106.74], [10.74, 106.76], [10.73, 106.78]]
            ],
            [
                'route_number' => '05',
                'start_location' => 'Bến Xe Chợ Lớn',
                'end_location' => 'Đại học Nông Lâm',
                'distance' => 22.1,
                'duration' => 55,
                'coords' => [[10.776889, 106.700806], [10.78, 106.69], [10.79, 106.68], [10.80, 106.67], [10.81, 106.66]]
            ],
            [
                'route_number' => '06',
                'start_location' => 'Bến Xe Buýt Sài Gòn',
                'end_location' => 'Đại học Quốc Tế',
                'distance' => 30.0,
                'duration' => 70,
                'coords' => [[10.776889, 106.700806], [10.75, 106.75], [10.73, 106.80], [10.71, 106.85], [10.69, 106.90]]
            ],
            [
                'route_number' => '07',
                'start_location' => 'Công viên 23/9',
                'end_location' => 'Bến Xe Miền Đông Mới',
                'distance' => 17.8,
                'duration' => 48,
                'coords' => [[10.776889, 106.700806], [10.78, 106.72], [10.79, 106.74], [10.80, 106.76], [10.81, 106.78]]
            ],
            [
                'route_number' => '08',
                'start_location' => 'Đại học Bách Khoa',
                'end_location' => 'Khu Công nghệ Cao',
                'distance' => 12.3,
                'duration' => 35,
                'coords' => [[10.776889, 106.700806], [10.77, 106.69], [10.76, 106.68], [10.75, 106.67], [10.74, 106.66]]
            ],
            [
                'route_number' => '09',
                'start_location' => 'Bến Xe Miền Tây',
                'end_location' => 'Khu Chế Xuất Tân Thuận',
                'distance' => 28.7,
                'duration' => 65,
                'coords' => [[10.776889, 106.700806], [10.76, 106.65], [10.75, 106.60], [10.74, 106.55], [10.73, 106.50]]
            ],
            [
                'route_number' => '10',
                'start_location' => 'Suối Tiên',
                'end_location' => 'Bến Xe Miền Đông',
                'distance' => 10.0,
                'duration' => 30,
                'coords' => [[10.776889, 106.700806], [10.78, 106.71], [10.79, 106.72], [10.80, 106.73], [10.81, 106.74]]
            ],
            [
                'route_number' => '11',
                'start_location' => 'Thảo Cầm Viên',
                'end_location' => 'Bến Xe An Sương',
                'distance' => 23.5,
                'duration' => 58,
                'coords' => [[10.776889, 106.700806], [10.79, 106.68], [10.81, 106.66], [10.83, 106.64], [10.85, 106.62]]
            ],
            [
                'route_number' => '12',
                'start_location' => 'Sân bay Tân Sơn Nhất',
                'end_location' => 'Bến Thành',
                'distance' => 8.9,
                'duration' => 25,
                'coords' => [[10.776889, 106.700806], [10.78, 106.70], [10.79, 106.70], [10.80, 106.70], [10.81, 106.70]]
            ],
            [
                'route_number' => '13',
                'start_location' => 'Bến Xe Miền Đông',
                'end_location' => 'Bến Xe Miền Tây',
                'distance' => 35.0,
                'duration' => 80,
                'coords' => [[10.776889, 106.700806], [10.75, 106.60], [10.73, 106.50], [10.71, 106.40], [10.69, 106.30]]
            ],
            [
                'route_number' => '14',
                'start_location' => 'Đại học Sư Phạm Kỹ Thuật',
                'end_location' => 'Công viên Phần mềm Quang Trung',
                'distance' => 16.7,
                'duration' => 42,
                'coords' => [[10.776889, 106.700806], [10.78, 106.73], [10.79, 106.76], [10.80, 106.79], [10.81, 106.82]]
            ],
            [
                'route_number' => '15',
                'start_location' => 'Bến Xe Quận 8',
                'end_location' => 'Bến Xe Miền Đông Mới',
                'distance' => 29.3,
                'duration' => 75,
                'coords' => [[10.776889, 106.700806], [10.76, 106.67], [10.75, 106.64], [10.74, 106.61], [10.73, 106.58]]
            ],
            [
                'route_number' => '16',
                'start_location' => 'Khu đô thị Phú Mỹ Hưng',
                'end_location' => 'Chợ Bến Thành',
                'distance' => 11.2,
                'duration' => 33,
                'coords' => [[10.776889, 106.700806], [10.76, 106.71], [10.75, 106.72], [10.74, 106.73], [10.73, 106.74]]
            ],
            [
                'route_number' => '17',
                'start_location' => 'Bến Xe An Sương',
                'end_location' => 'Bến Xe Miền Đông Mới',
                'distance' => 26.8,
                'duration' => 62,
                'coords' => [[10.776889, 106.700806], [10.79, 106.67], [10.81, 106.64], [10.83, 106.61], [10.85, 106.58]]
            ],
            [
                'route_number' => '18',
                'start_location' => 'Đại học Quốc Gia',
                'end_location' => 'Bến Xe Miền Tây',
                'distance' => 32.4,
                'duration' => 78,
                'coords' => [[10.776889, 106.700806], [10.75, 106.65], [10.73, 106.60], [10.71, 106.55], [10.69, 106.50]]
            ],
            [
                'route_number' => '19',
                'start_location' => 'Bến Xe Miền Đông',
                'end_location' => 'Bến Xe An Sương',
                'distance' => 21.9,
                'duration' => 53,
                'coords' => [[10.776889, 106.700806], [10.78, 106.70], [10.79, 106.70], [10.80, 106.70], [10.81, 106.70]]
            ],
            [
                'route_number' => '20',
                'start_location' => 'Bến Thành',
                'end_location' => 'Chợ Bình Tây',
                'distance' => 7.5,
                'duration' => 20,
                'coords' => [[10.776889, 106.700806], [10.77, 106.71], [10.76, 106.72], [10.75, 106.73], [10.74, 106.74]]
            ],
        ];

        foreach ($routes as $routeData) {
            BusRoute::firstOrCreate(
                ['route_number' => $routeData['route_number']],
                $routeData
            );
        }
    }
}
