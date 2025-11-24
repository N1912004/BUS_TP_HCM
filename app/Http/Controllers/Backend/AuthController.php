<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\BusRoute; // Import the BusRoute model
use App\Models\Bus; // Import the Bus model
use App\Models\Admin; // Import the Admin model
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log; // Import the Log facade
use Illuminate\Support\Facades\Password; // Import the Password facade
use Illuminate\Auth\Events\PasswordReset; // Import the PasswordReset event
use Illuminate\Validation\ValidationException; // Import ValidationException

class AuthController extends Controller
{
    // Xử lý phần đăng nhập cho user
    public function __construct() {}

    public function showForgotPasswordForm()
    {
        return view('backend.auth.forgot_password');
    }
    public function index()
    {
        return view('backend.auth.roles');
    }
    public function  showLoginUserForm()
    {
        return view('backend.auth.login_user_bus');
    }
    public function username()
    {
        return 'username';
    }


   public function login_user(AuthRequest $request)
{
    $credentials = $request->only('username', 'password');

    Log::info('Attempting user login with credentials:', ['username' => $credentials['username']]);

    $user = User::where('username', $credentials['username'])->first();
    if (!$user) {
        Log::warning('User not found for username: ' . $credentials['username']);
        return redirect()->route('auth.dashboard_user')->with('error', 'Tên đăng nhập hoặc mật khẩu không đúng');
    }

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate(); // ✅ quan trọng
        $user = Auth::user();
        Log::info('User authenticated successfully. User ID: ' . $user->id . ', is_verified: ' . $user->is_verified);

        if ($user->is_verified) {
            return redirect()->route('user.map_route');
        } else {
            Auth::logout();
            $request->session()->invalidate(); 
            $request->session()->regenerateToken();
            Log::warning('User not verified. Logging out.');
            return redirect()->route('auth.dashboard_user')->with('error', 'Tài khoản của bạn chưa được xác minh.');
        }
    } else {
        Log::warning('Password mismatch for username: ' . $credentials['username']);
        return redirect()->route('auth.dashboard_user')->with('error', 'Tên đăng nhập hoặc mật khẩu không đúng');
    }
}



    // Xử lý phần đăng nhập cho admin
    public function showLoginAdminForm()
    {
        return view('backend.auth.login_ad_bus');
    }

    public function login_admin(AuthRequest $request)
    {
        //lấy dữ liệu từ form
        $credentials = [
            'username' => $request->input('username'),
            'password' => $request->input('password')
        ];

        if (Auth::guard('admin')->attempt($credentials)) {
            $routes = BusRoute::paginate(10); // Fetch bus routes with pagination
            return redirect()->route('auth.dashboard_admin'); // Redirect to admin dashboard after successful login
        }
        return redirect()->route('auth.loginadmin_get')->with('error', 'Tên đăng nhập hoặc mật khẩu không đúng');
    }

    public function dashboard_admin()
    {
        $totalRoutes = BusRoute::count();
        $totalBuses = Bus::count();
        $totalUsers = User::count();
        $totalDrivers = Admin::count(); // Assuming 'Admin' model represents drivers for now

        return view('backend.admin.index_admin', compact('totalRoutes', 'totalBuses', 'totalUsers', 'totalDrivers'));
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
    public function logout(Request $request)
    {
        Auth::logout(); 

        $request->session()->invalidate(); 

        $request->session()->regenerateToken(); 

        return redirect()->route('auth.dashboard_user'); 
    }
    // xử lý quên mật khẩu email
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        Log::info('Attempting to send password reset link for email: ' . $request->email);

        // Dùng Laravel built-in để gửi link reset
        $status = Password::broker()->sendResetLink(
            $request->only('email')
        );

        Log::info('Password reset link sent status: ' . $status);

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetPasswordForm(Request $request, $token = null)
    {
        return view('backend.auth.reset_pass')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        Log::info('Attempting to reset password. Email: ' . $request->email . ', Token: ' . $request->token);

        $status = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                // Log the user object immediately upon entering the callback
                Log::info('Inside resetPassword callback. User object:', ['user' => $user, 'is_instance_of_User' => ($user instanceof User)]);

                // Ensure $user is not null and is an instance of User before proceeding
                if (!$user instanceof User) {
                    Log::error('User not found or invalid in resetPassword callback for email: ' . $request->email . '. User value: ' . json_encode($user));
                    return; // Exit the callback if user is not valid
                }

                // Break down chained calls to isolate the cause of "save() on null"
                $user->password = Hash::make($request->password);
                $user->setRememberToken(null);
                $user->save();

                event(new PasswordReset($user));
                Log::info('Password successfully reset for user ID: ' . $user->id);
            }
        );

        Log::info('Password reset status: ' . $status);

        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('auth.loginuser_get')->with('status', __($status));
        }

        return back()->withErrors(['email' => __($status)]);
    }
}
