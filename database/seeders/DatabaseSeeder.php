<?php

namespace Database\Seeders;

use App\Models\User;
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
        DB::table('users')->insert([
            'fullname' => 'Nguyen Van A',
            'username' => 'user123',
            'password' => Hash::make('123456'),
        ]);
        DB::table('admins')->insert([

            'username' => 'admin123',
            'password' => Hash::make('admin111'),
        ]);
    }
}
