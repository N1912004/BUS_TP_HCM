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
        // Define the bus routes data
        $routes = [
            [
                'ma_tuyen' => '01',
                'diem_di' => 'Bến Thành',
                'diem_den' => 'Bến Xe Miền Đông',
                'ngay' => '2025-11-09',
                'thoi_gian_bat_dau' => '05:00',
                'thoi_gian_ket_thuc' => '20:00',
                'coords' => [[10.776889, 106.700806], [10.785, 106.71], [10.79, 106.72], [10.80, 106.73], [10.81, 106.74]]
            ],
            [
                'ma_tuyen' => '02',
                'diem_di' => 'Bến Xe Quận 8',
                'diem_den' => 'Đại học Quốc Gia',
                'ngay' => '2025-11-09',
                'thoi_gian_bat_dau' => '05:00',
                'thoi_gian_ket_thuc' => '20:00',
                'coords' => [[10.776889, 106.700806], [10.77, 106.68], [10.76, 106.66], [10.75, 106.64], [10.74, 106.62]]
            ],
            [
                'ma_tuyen' => '03',
                'diem_di' => 'BX An Sương',
                'diem_den' => 'BX Miền Tây',
                'ngay' => '2025-11-09',
                'thoi_gian_bat_dau' => '05:00',
                'thoi_gian_ket_thuc' => '20:00',
                'coords' => [[10.776889, 106.700806], [10.79, 106.65], [10.81, 106.60], [10.83, 106.55], [10.85, 106.50]]
            ],
            [
                'ma_tuyen' => '04',
                'diem_di' => 'Chợ Lớn',
                'diem_den' => 'Bến Xe An Sương',
                'ngay' => '2025-11-09',
                'thoi_gian_bat_dau' => '05:00',
                'thoi_gian_ket_thuc' => '20:00',
                'coords' => [[10.776889, 106.700806], [10.76, 106.72], [10.75, 106.74], [10.74, 106.76], [10.73, 106.78]]
            ],
            [
                'ma_tuyen' => '05',
                'diem_di' => 'Bến Xe Chợ Lớn',
                'diem_den' => 'Đại học Nông Lâm',
                'ngay' => '2025-11-09',
                'thoi_gian_bat_dau' => '05:00',
                'thoi_gian_ket_thuc' => '20:00',
                'coords' => [[10.776889, 106.700806], [10.78, 106.69], [10.79, 106.68], [10.80, 106.67], [10.81, 106.66]]
            ],
            [
                'ma_tuyen' => '06',
                'diem_di' => 'Bến Xe Buýt Sài Gòn',
                'diem_den' => 'Đại học Quốc Tế',
                'ngay' => '2025-11-09',
                'thoi_gian_bat_dau' => '05:00',
                'thoi_gian_ket_thuc' => '20:00',
                'coords' => [[10.776889, 106.700806], [10.75, 106.75], [10.73, 106.80], [10.71, 106.85], [10.69, 106.90]]
            ],
            [
                'ma_tuyen' => '07',
                'diem_di' => 'Công viên 23/9',
                'diem_den' => 'Bến Xe Miền Đông Mới',
                'ngay' => '2025-11-09',
                'thoi_gian_bat_dau' => '05:00',
                'thoi_gian_ket_thuc' => '20:00',
                'coords' => [[10.776889, 106.700806], [10.78, 106.72], [10.79, 106.74], [10.80, 106.76], [10.81, 106.78]]
            ],
            [
                'ma_tuyen' => '08',
                'diem_di' => 'Đại học Bách Khoa',
                'diem_den' => 'Khu Công nghệ Cao',
                'ngay' => '2025-11-09',
                'thoi_gian_bat_dau' => '05:00',
                'thoi_gian_ket_thuc' => '20:00',
                'coords' => [[10.776889, 106.700806], [10.77, 106.69], [10.76, 106.68], [10.75, 106.67], [10.74, 106.66]]
            ],
            [
                'ma_tuyen' => '09',
                'diem_di' => 'Bến Xe Miền Tây',
                'diem_den' => 'Khu Chế Xuất Tân Thuận',
                'ngay' => '2025-11-09',
                'thoi_gian_bat_dau' => '05:00',
                'thoi_gian_ket_thuc' => '20:00',
                'coords' => [[10.776889, 106.700806], [10.76, 106.65], [10.75, 106.60], [10.74, 106.55], [10.73, 106.50]]
            ],
            [
                'ma_tuyen' => '10',
                'diem_di' => 'Suối Tiên',
                'diem_den' => 'Bến Xe Miền Đông',
                'ngay' => '2025-11-09',
                'thoi_gian_bat_dau' => '05:00',
                'thoi_gian_ket_thuc' => '20:00',
                'coords' => [[10.776889, 106.700806], [10.78, 106.71], [10.79, 106.72], [10.80, 106.73], [10.81, 106.74]]
            ],
            [
                'ma_tuyen' => '11',
                'diem_di' => 'Thảo Cầm Viên',
                'diem_den' => 'Bến Xe An Sương',
                'ngay' => '2025-11-09',
                'thoi_gian_bat_dau' => '05:00',
                'thoi_gian_ket_thuc' => '20:00',
                'coords' => [[10.776889, 106.700806], [10.79, 106.68], [10.81, 106.66], [10.83, 106.64], [10.85, 106.62]]
            ],
            [
                'ma_tuyen' => '12',
                'diem_di' => 'Sân bay Tân Sơn Nhất',
                'diem_den' => 'Bến Thành',
                'ngay' => '2025-11-09',
                'thoi_gian_bat_dau' => '05:00',
                'thoi_gian_ket_thuc' => '20:00',
                'coords' => [[10.776889, 106.700806], [10.78, 106.70], [10.79, 106.70], [10.80, 106.70], [10.81, 106.70]]
            ],
            [
                'ma_tuyen' => '13',
                'diem_di' => 'Bến Xe Miền Đông',
                'diem_den' => 'Bến Xe Miền Tây',
                'ngay' => '2025-11-09',
                'thoi_gian_bat_dau' => '05:00',
                'thoi_gian_ket_thuc' => '20:00',
                'coords' => [[10.776889, 106.700806], [10.75, 106.60], [10.73, 106.50], [10.71, 106.40], [10.69, 106.30]]
            ],
            [
                'ma_tuyen' => '14',
                'diem_di' => 'Đại học Sư Phạm Kỹ Thuật',
                'diem_den' => 'Công viên Phần mềm Quang Trung',
                'ngay' => '2025-11-09',
                'thoi_gian_bat_dau' => '05:00',
                'thoi_gian_ket_thuc' => '20:00',
                'coords' => [[10.776889, 106.700806], [10.78, 106.73], [10.79, 106.76], [10.80, 106.79], [10.81, 106.82]]
            ],
            [
                'ma_tuyen' => '15',
                'diem_di' => 'Bến Xe Quận 8',
                'diem_den' => 'Bến Xe Miền Đông Mới',
                'ngay' => '2025-11-09',
                'thoi_gian_bat_dau' => '05:00',
                'thoi_gian_ket_thuc' => '20:00',
                'coords' => [[10.776889, 106.700806], [10.76, 106.67], [10.75, 106.64], [10.74, 106.61], [10.73, 106.58]]
            ],
            [
                'ma_tuyen' => '16',
                'diem_di' => 'Khu đô thị Phú Mỹ Hưng',
                'diem_den' => 'Chợ Bến Thành',
                'ngay' => '2025-11-09',
                'thoi_gian_bat_dau' => '05:00',
                'thoi_gian_ket_thuc' => '20:00',
                'coords' => [[10.776889, 106.700806], [10.76, 106.71], [10.75, 106.72], [10.74, 106.73], [10.73, 106.74]]
            ],
            [
                'ma_tuyen' => '17',
                'diem_di' => 'Bến Xe An Sương',
                'diem_den' => 'Bến Xe Miền Đông Mới',
                'ngay' => '2025-11-09',
                'thoi_gian_bat_dau' => '05:00',
                'thoi_gian_ket_thuc' => '20:00',
                'coords' => [[10.776889, 106.700806], [10.79, 106.67], [10.81, 106.64], [10.83, 106.61], [10.85, 106.58]]
            ],
            [
                'ma_tuyen' => '18',
                'diem_di' => 'Đại học Quốc Gia',
                'diem_den' => 'Bến Xe Miền Tây',
                'ngay' => '2025-11-09',
                'thoi_gian_bat_dau' => '05:00',
                'thoi_gian_ket_thuc' => '20:00',
                'coords' => [[10.776889, 106.700806], [10.75, 106.65], [10.73, 106.60], [10.71, 106.55], [10.69, 106.50]]
            ],
            [
                'ma_tuyen' => '19',
                'diem_di' => 'Bến Xe Miền Đông',
                'diem_den' => 'Bến Xe An Sương',
                'ngay' => '2025-11-09',
                'thoi_gian_bat_dau' => '05:00',
                'thoi_gian_ket_thuc' => '20:00',
                'coords' => [[10.776889, 106.700806], [10.78, 106.70], [10.79, 106.70], [10.80, 106.70], [10.81, 106.70]]
            ],
            [
                'ma_tuyen' => '20',
                'diem_di' => 'Bến Thành',
                'diem_den' => 'Chợ Bình Tây',
                'ngay' => '2025-11-09',
                'thoi_gian_bat_dau' => '05:00',
                'thoi_gian_ket_thuc' => '20:00',
                'coords' => [[10.776889, 106.700806], [10.77, 106.71], [10.76, 106.72], [10.75, 106.73], [10.74, 106.74]]
            ],
        ];

        foreach ($routes as $routeData) {
            BusRoute::firstOrCreate(
                ['ma_tuyen' => $routeData['ma_tuyen']],
                $routeData
            );
        }
    }
}
