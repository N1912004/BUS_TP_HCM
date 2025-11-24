<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BusRouteController;
use App\Http\Controllers\Api\BusStopController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\AdminStatsController; // Import the new controller
use App\Http\Controllers\Backend\BusController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Bus Routes API
Route::apiResource('bus-routes', BusRouteController::class);
Route::get('/bus-routes/{id}/stations', [BusRouteController::class, 'getStations']);

// Bus Stops API
Route::apiResource('busstops', BusStopController::class);

// Auth API
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

// Admin Stats API
Route::get('/admin/stats', [AdminStatsController::class, 'getStats'])->name('api.admin.stats');

// API lấy tuyến của tài xế
Route::get('/admin/routes/{driverId}', [BusController::class, 'getRoutesByDriver'])->name('api.admin.routes.byDriver');
