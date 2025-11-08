<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log; // Import the Log facade

class AuthController extends Controller
{
    // Xử lý phần đăng nhập cho user
    public function __construct() {}
    public function index()
    {
        return view('backend.auth.roles');
    }
    public function  dashboard_user()
    {
        return view('backend.auth.login_user_bus');
    }
    public function username()
    {
        return 'username';
    }


    public function  login_user(AuthRequest $request)
    {

        //lấy dữ liệu từ form
        $credentials = [
            'username' => $request->input('username'),
            'password' => $request->input('password')


        ];

        Log::info('Attempting user login with credentials:', $credentials);

        $user = User::where('username', $credentials['username'])->first();

        if (!$user) {
            Log::warning('User not found for username: ' . $credentials['username']);
            return redirect()->route('auth.dashboard_user')->with('error', 'Tên đăng nhập hoặc mật khẩu không đúng');
        }

        if (Hash::check($credentials['password'], $user->password)) {
            Auth::login($user); // Manually log in the user
            Log::info('User authenticated successfully via manual hash check. User ID: ' . $user->id . ', is_verified: ' . $user->is_verified);
            if ($user->is_verified) {
                return redirect()->route('user.map_route');
            } else {
                Auth::logout();
                Log::warning('User not verified. Logging out.');
                return redirect()->route('auth.dashboard_user')->with('error', 'Tài khoản của bạn chưa được xác minh.');
            }
        } else {
            Log::warning('Password mismatch for username: ' . $credentials['username']);
            return redirect()->route('auth.dashboard_user')->with('error', 'Tên đăng nhập hoặc mật khẩu không đúng');
        }
    }


    // Xử lý phần đăng nhập cho admin
    public function  login_admin(AuthRequest $request)
    {

        //lấy dữ liệu từ form
        $credentials = [
            'username' => $request->input('username'),
            'password' => $request->input('password')
        ];

        if (Auth::guard('admin')->attempt($credentials)) {

            return view('backend.layouts.app');
        }
        return redirect()->route('auth.dashboard_admin')->with('error', 'Tên đăng nhập hoặc mật khẩu không đúng');
    }

    public function dashboard_admin()
    {
        return view('backend.auth.login_ad_bus');
       
    }



    //xử lý phần đăng ký cho subauser

    public function dashboard_sub()
    {
        return view('backend.auth.sub');
    }

    public function PostRegister(RegisterRequest $request)
    {
        // dd($request->all());

        User::create([
            'fullname' => $request->fullname,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_verified' => false, // Set to false by default
        ]);

        return redirect()->route('auth.dashboard_user')->with('success', 'Đăng ký thành công!');
    }

    // xử lý log out
    // public function logout(Request $request)
    // {
    //     Auth::logout(); 

    //     $request->session()->invalidate(); 

    //     $request->session()->regenerateToken(); 

    //     return redirect()->route('auth.dashboard_user'); 
    // }
    // xử lý quên mật khẩu email

    // public function sendResetLinkEmail(Request $request)
    // {
    //     $request->validate(['email' => 'required|email']);

    //     // Dùng Laravel built-in để gửi link reset
    //     $status = Password::sendResetLink(
    //         $request->only('email')
    //     );

    //     return $status === Password::RESET_LINK_SENT
    //         ? back()->with(['status' => __($status)])
    //         : back()->withErrors(['email' => __($status)]);
    // }

    public function dashboard_reset_pass()
    {
        return view('backend.auth.reset_pass');
    }
}
