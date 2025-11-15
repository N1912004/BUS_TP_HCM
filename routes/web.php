<?php

use App\Http\Controllers\Backend\AuthController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;




use App\Http\Controllers\Backend\UserController; // Add this line

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
})->middleware('auth')->name('user.map_route');
//đặt route nay có cái tên là auth.login
Route:: get('/roles', [AuthController::class, 'index'])->name('auth.roles');

// 🔹 Login cho user (guard web)
Route::get('/loginuser', [AuthController::class, 'showLoginUserForm'])->name('auth.loginuser_get');
Route::post('/loginuser', [AuthController::class, 'login_user'])->name('auth.login_user');

// Default login route for unauthenticated users (redirects to admin login for admin guard)
Route::get('/login', function () {
    return redirect()->route('auth.loginuser_get');
})->name('login');



Route::get('/sub', [AuthController::class, 'dashboard_sub'])->name('auth.dashboard_sub');

// User Dashboard
Route::get('/dashboard/user', [UserController::class, 'dashboard_user'])->middleware('auth')->name('auth.dashboard_user');


Route::get('/reset_pass', [AuthController::class, 'dashboard_reset_pass'])->name('auth.dashboard_reset_pass');
//Trang admin
// 🔹 Login cho admin (guard admin)
Route::get('/admin/login', [AuthController::class, 'showLoginAdminForm'])->name('auth.loginadmin_get');
Route::post('/admin/login', [AuthController::class, 'login_admin'])->name('auth.login_admin');



//xử lý phần đăng ký cho subauser




Route::post('/register', [AuthController::class, 'PostRegister'])->name('auth.register');

Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');



//Xử lý quên mật khẩu email 
// web.php
Route::post('/password/email', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('/ping', function () {
    return response()->json(['message' => 'pong']);
});

use App\Http\Controllers\Backend\BusController;
use App\Http\Controllers\Backend\BusRouteController; // Import the BusRouteController

// Route::get('/user/map', [UserController::class, 'showUserMap'])->name('user.map');
//Route::get('/bus', [BusController::class, 'index'])->name('bus.index');
// Route::get('/bus-route-search', function () {
//     return view('backend.user.bus_route_search');
// })->name('user.bus_route_search');

// Route::get('/bus-dashboard', function () {
//     return view('backend.user.bus_dashboard');
// })->name('user.bus_dashboard');

Route::get('/bus-map', function () {
    return view('backend.user.user_map');
  })->name('user.bus_map');
  
  Route::get('/admin/buses/{bus}/coordinates', [\App\Http\Controllers\Backend\BusController::class, 'getCoordinates'])->name('admin.buses.coordinates');

// Admin routes for managing users, tickets, and routes
Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
use App\Http\Controllers\Admin\TicketController; // Import the TicketController

Route::get('/profile', [UserController::class, 'show'])->name('profile.show');
// routes/web.php
use App\Http\Controllers\Admin\RouteController;
use App\Http\Controllers\Admin\DriverController; // Import the DriverController
use App\Http\Controllers\Admin\AssistantController; // Import the AssistantController

Route::prefix('admin')->middleware('auth.admin:admin')->group(function () {
    // Admin Dashboard
    Route::get('/', [AuthController::class, 'dashboard_admin'])->name('auth.dashboard_admin');

    Route::resource('routes', RouteController::class)->names([
        'index' => 'admin.routes.index',
        'create' => 'admin.routes.create',
        'store' => 'admin.routes.store',
        'show' => 'admin.routes.show',
        'edit' => 'admin.routes.edit',
        'update' => 'admin.routes.update',
        'destroy' => 'admin.routes.destroy',
    ]);

    // Custom route for deleting a route via GET request as requested
    Route::get('routes/{route}/delete', [RouteController::class, 'destroy'])->name('admin.routes.delete');

    Route::resource('tickets', TicketController::class)->names([
        'index' => 'admin.tickets.index',
        'create' => 'admin.tickets.create',
        'store' => 'admin.tickets.store',
        'show' => 'admin.tickets.show',
        'edit' => 'admin.tickets.edit',
        'update' => 'admin.tickets.update',
        'destroy' => 'admin.tickets.destroy',
    ]);

    Route::get('/drivers', [DriverController::class, 'index'])->name('admin.drivers.index');
    Route::get('/drivers/create', [DriverController::class, 'create'])->name('admin.drivers.create');
    Route::post('/drivers', [DriverController::class, 'store'])->name('admin.drivers.store');
    Route::get('/drivers/{driver}/edit', [DriverController::class, 'edit'])->name('admin.drivers.edit');
    Route::put('/drivers/{driver}', [DriverController::class, 'update'])->name('admin.drivers.update');
    Route::delete('/drivers/{driver}', [DriverController::class, 'destroy'])->name('admin.drivers.destroy');
    Route::get('/drivers/{driver}', [DriverController::class, 'show'])->name('admin.drivers.show');

    // Assistant Routes
    Route::get('/assistants', [AssistantController::class, 'index'])->name('admin.assistants.index');
    Route::get('/assistants/create', [AssistantController::class, 'create'])->name('admin.assistants.create');
    Route::post('/assistants', [AssistantController::class, 'store'])->name('admin.assistants.store');
    Route::get('/assistants/{assistant}/edit', [AssistantController::class, 'edit'])->name('admin.assistants.edit');
    Route::put('/assistants/{assistant}', [AssistantController::class, 'update'])->name('admin.assistants.update');
    Route::delete('/assistants/{assistant}', [AssistantController::class, 'destroy'])->name('admin.assistants.destroy');
    Route::get('/assistants/{assistant}', [AssistantController::class, 'show'])->name('admin.assistants.show');

    // Resource routes for Bus Routes (Backend)
    Route::resource('busroutes', BusRouteController::class)->names([
        'index' => 'admin.busroutes.index',
        'create' => 'admin.busroutes.create',
        'store' => 'admin.busroutes.store',
        'show' => 'admin.busroutes.show',
        'edit' => 'admin.busroutes.edit',
        'update' => 'admin.busroutes.update',
        'destroy' => 'admin.busroutes.destroy',
    ]);

    // Route to toggle bus route status
    Route::put('busroutes/{busRoute}/toggle-status', [BusRouteController::class, 'toggleStatus'])->name('admin.busroutes.toggleStatus');

    // buses
    Route::resource('buses', App\Http\Controllers\Admin\BusController::class)->names([
        'index' => 'admin.buses.index',
        'create' => 'admin.buses.create',
        'store' => 'admin.buses.store',
        'show' => 'admin.buses.show',
        'edit' => 'admin.buses.edit',
        'update' => 'admin.buses.update',
        'destroy' => 'admin.buses.destroy',
    ]);
});
