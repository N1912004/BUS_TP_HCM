<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiAgentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/agent', [ApiAgentController::class, 'handleApiRequest']);
Route::resource('bus-stops', \App\Http\Controllers\Api\BusStopController::class);
Route::resource('bus-routes', \App\Http\Controllers\Api\BusRouteController::class);
Route::get('bus-routes/{id}/stations', [\App\Http\Controllers\Api\BusRouteController::class, 'getStations']);
Route::get('bus-routes/{id}/schedule', [\App\Http\Controllers\Api\BusRouteController::class, 'getSchedule']);
Route::get('bus-routes/nearby', [\App\Http\Controllers\Api\BusRouteController::class, 'nearby']);
Route::get('bus-stops/nearby', [\App\Http\Controllers\Api\BusStopController::class, 'nearby']);
