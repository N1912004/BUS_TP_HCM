<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin; // Import the Admin model
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * */
        public function run(): void
    {
        $this->call([
            BusSeeder::class,
            BusRouteSeeder::class,
            MetroBusRouteSeeder::class,
        ]);

        // Create a default user if one doesn't exist
        User::firstOrCreate(
            ['username' => 'user123'],
            [
                'fullname' => 'Nguyen Van A',
                'password' => Hash::make('123456'),
                'is_verified' => true, // Set the user as verified
            ]
        );

        // Create a default admin if one doesn't exist
        Admin::firstOrCreate(
            ['username' => 'admin123'],
            [
                'password' => Hash::make('admin111'),
            ]
        );
    }
}
?>
