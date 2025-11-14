<?php

use App\Http\Controllers\Backend\AuthController;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Backend\BusController;
Route::get('/', function () {
    return view('welcome');
});

Route::get('/lang/{locale}', function ($locale) {
    if (! in_array($locale, ['en', 'vi'])) {
        abort(400);
    }
    App::setLocale($locale);
    session()->put('locale', $locale);
    return redirect()->back();
})->name('lang');

Route::get('/map-route', function () {
    return view('backend.user.map_route');
})->name('user.map_route');
//đặt route nay có cái tên là auth.login
Route:: get('/roles', [AuthController::class, 'index'])->name('auth.roles');

Route::get('/user', [AuthController::class, 'dashboard_user'])->name('auth.dashboard_user');
Route::get('/loginuser', [AuthController::class, 'dashboard_user'])->name('auth.loginuser_get');


Route::get('/admin', [AuthController::class, 'dashboard_admin'])->name('auth.dashboard_admin');


Route::get('/sub', [AuthController::class, 'dashboard_sub'])->name('auth.dashboard_sub');


Route::get('/reset_pass', [AuthController::class, 'dashboard_reset_pass'])->name('auth.dashboard_reset_pass');


//xử lý phần đăng ký cho subauser


Route::post('/loginuser', [AuthController::class, 'login_user'])->name('auth.login_user'); 


Route::post('/loginadmin', [AuthController::class, 'login_admin'])->name('auth.login_admin');
Route::post('/register', [AuthController::class, 'PostRegister'])->name('auth.register');

Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');



//Xử lý quên mật khẩu email 
// web.php
Route::post('/password/email', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('/ping', function () {
    return response()->json(['message' => 'pong']);
});

use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\BusRouteController; // Import the BusRouteController

// Route::get('/user/map', [UserController::class, 'showUserMap'])->name('user.map');
//Route::get('/bus', [BusController::class, 'index'])->name('bus.index');
Route::get('/busroutes', [BusRouteController::class, 'index'])->name('admin.busroutes.index');
// Route::get('/bus-route-search', function () {
//     return view('backend.user.bus_route_search');
// })->name('user.bus_route_search');

// Route::get('/bus-dashboard', function () {
//     return view('backend.user.bus_dashboard');
// })->name('user.bus_dashboard');

Route::post('/api/select-city', [BusController::class, 'selectCity'])
    ->name('api.select_city')
    ->middleware('auth'); // Thêm middleware auth vì đây là chức năng của người dùng đã đăng nhập
Route::get('/bus-map', function () {
    return view('backend.user.user_map');
  })->name('user.bus_map');
Route::post('/api/find-route', [BusController::class, 'findRoute'])
    ->name('api.find_route')
    ->middleware('auth');
Route::post('/api/nearly-route', [BusController::class, 'nearlyRoute'])
    ->name('api.nearly_route')
    ->middleware('auth');
  Route::get('/admin/buses/{bus}/coordinates', [App\Http\Controllers\Backend\BusController::class, 'getCoordinates'])->name('admin.buses.coordinates');
