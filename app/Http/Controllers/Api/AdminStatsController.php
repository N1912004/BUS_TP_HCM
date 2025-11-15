<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusRoute;
use App\Models\Bus;
use App\Models\User;
use App\Models\Admin; // Assuming Admin model represents drivers

class AdminStatsController extends Controller
{
    public function getStats()
    {
        $totalRoutes = BusRoute::count();
        $totalBuses = Bus::count();
        $totalUsers = User::count();
        $totalDrivers = Admin::count(); // Assuming 'Admin' model represents drivers

        return response()->json([
            'totalRoutes' => $totalRoutes,
            'totalBuses' => $totalBuses,
            'totalUsers' => $totalUsers,
            'totalDrivers' => $totalDrivers,
        ]);
    }
}
