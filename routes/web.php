<?php

use App\Http\Controllers\Backend\AuthController;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('backend.auth.Roles');
});
//đặt route nay có cái tên là auth.login
Route:: get('/roles', [AuthController::class, 'index'])->name('auth.roles');

Route::get('/user', [AuthController::class, 'dashboard_user'])->name('auth.dashboard_user');


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
