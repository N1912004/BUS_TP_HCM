<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\BusController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\BusRouteController;
use App\Http\Controllers\Admin\BusController as AdminBusController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\RouteController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\AssistantController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Welcome page
Route::get('/', function () {
    return view('welcome');
});

// Language switch
Route::get('/lang/{locale}', function ($locale) {
    if (! in_array($locale, ['en', 'vi'])) {
        abort(400);
    }
    App::setLocale($locale);
    session()->put('locale', $locale);
    return redirect()->back();
})->name('lang');

// User routes (require auth)
Route::middleware('auth')->group(function () {
    Route::get('/map-route', function () {
        return view('backend.user.map_route');
    })->name('user.map_route');

    Route::get('/bus-map', function () {
        return view('backend.user.user_map');
    })->name('user.bus_map');

    Route::post('/api/select-city', [BusController::class, 'selectCity'])->name('api.select_city');
    Route::post('/api/find-route', [BusController::class, 'findRoute'])->name('api.find_route');
    Route::post('/api/nearly-route', [BusController::class, 'nearlyRoute'])->name('api.nearly_route');

    // User dashboard
    Route::get('/dashboard/user', [UserController::class, 'dashboard_user'])->name('auth.dashboard_user');
    Route::get('/profile', [UserController::class, 'show'])->name('profile.show');
});

// Login & Auth routes
Route::get('/roles', [AuthController::class, 'index'])->name('auth.roles');
Route::get('/loginuser', [AuthController::class, 'showLoginUserForm'])->name('auth.loginuser_get');
Route::post('/loginuser', [AuthController::class, 'login_user'])->name('auth.login_user');

Route::get('/login', function () {
    return redirect()->route('auth.loginuser_get');
})->name('login');

Route::get('/sub', [AuthController::class, 'dashboard_sub'])->name('auth.dashboard_sub');
Route::get('/reset_pass', [AuthController::class, 'dashboard_reset_pass'])->name('auth.dashboard_reset_pass');

// Admin login
Route::get('/admin/login', [AuthController::class, 'showLoginAdminForm'])->name('auth.loginadmin_get');
Route::post('/admin/login', [AuthController::class, 'login_admin'])->name('auth.login_admin');

// Register & logout
Route::post('/register', [AuthController::class, 'PostRegister'])->name('auth.register');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

// Password email reset
Route::post('/password/email', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');

// Ping test
Route::get('/ping', function () {
    return response()->json(['message' => 'pong']);
});

Route::prefix('admin')->name('admin.')->group(function() {
    Route::resource('buses', BusController::class);
});

// Admin routes
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('auth.logout_admin');
Route::prefix('admin')->middleware('auth.admin:admin')->group(function () {
    Route::get('/', [AuthController::class, 'dashboard_admin'])->name('auth.dashboard_admin');

    // Resource routes
    Route::resource('routes', RouteController::class)->names([
        'index' => 'admin.routes.index',
        'create' => 'admin.routes.create',
        'store' => 'admin.routes.store',
        'show' => 'admin.routes.show',
        'edit' => 'admin.routes.edit',
        'update' => 'admin.routes.update',
        'destroy' => 'admin.routes.destroy',
    ]);

    // Custom delete route (GET) không trùng resource destroy
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

    // Drivers
    Route::resource('drivers', DriverController::class)->names([
        'index' => 'admin.drivers.index',
        'create' => 'admin.drivers.create',
        'store' => 'admin.drivers.store',
        'show' => 'admin.drivers.show',
        'edit' => 'admin.drivers.edit',
        'update' => 'admin.drivers.update',
        'destroy' => 'admin.drivers.destroy',
    ]);

    // Assistants
    Route::resource('assistants', AssistantController::class)->names([
        'index' => 'admin.assistants.index',
        'create' => 'admin.assistants.create',
        'store' => 'admin.assistants.store',
        'show' => 'admin.assistants.show',
        'edit' => 'admin.assistants.edit',
        'update' => 'admin.assistants.update',
        'destroy' => 'admin.assistants.destroy',
    ]);

    // BusRoutes
    Route::resource('busroutes', BusRouteController::class)->names([
        'index' => 'admin.busroutes.index',
        'create' => 'admin.busroutes.create',
        'store' => 'admin.busroutes.store',
        'show' => 'admin.busroutes.show',
        'edit' => 'admin.busroutes.edit',
        'update' => 'admin.busroutes.update',
        'destroy' => 'admin.busroutes.destroy',
    ]);

    Route::put('busroutes/{busRoute}/toggle-status', [BusRouteController::class, 'toggleStatus'])->name('admin.busroutes.toggleStatus');

    // Buses
    Route::resource('buses', AdminBusController::class)->names([
        'index' => 'admin.buses.index',
        'create' => 'admin.buses.create',
        'store' => 'admin.buses.store',
        'show' => 'admin.buses.show',
        'edit' => 'admin.buses.edit',
        'update' => 'admin.buses.update',
        'destroy' => 'admin.buses.destroy',
    ]);

    // Fixed duplicate coordinates route
    Route::get('buses/{bus}/coordinates', [BusController::class, 'getCoordinates'])->name('admin.buses.coordinates');
});
