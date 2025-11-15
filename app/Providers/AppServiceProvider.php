<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

   public function boot(): void
{
    Schema::defaultStringLength(191);

    View::composer('*', function ($view) {
        $totals = [
            'totalRoutes'  => 0,
            'totalBuses'   => 0,
            'totalUsers'   => 0,
            'totalDrivers' => 0,
        ];

        try {
            // Count routes
            if (Schema::hasTable('routes')) {
                $totals['totalRoutes'] = DB::table('bus_routes')->count();
            }

            // Count buses
            if (Schema::hasTable('buses')) {
                $totals['totalBuses'] = DB::table('buses')->count();
            }

            // Count users
            if (Schema::hasTable('users')) {
                $totals['totalUsers'] = DB::table('users')->count();

                // Count drivers = users.role = driver
                $totals['totalDrivers'] = DB::table('users')
                    ->where('role', 'driver')
                    ->count();
            }

        } catch (\Throwable $e) {
            Log::error("Dashboard Count Error: " . $e->getMessage());
        }

        $view->with($totals);
    });

    }
}
