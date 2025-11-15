<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function __construct()
    {
        
    }

    public function showUserMap()
    {
        return view('backend.user.user_map');
    }

    public function show()
    {
        return view('backend.user.profile');
    }

    public function dashboard_user()
    {
        return view('backend.user.bus_dashboard');
    }
}
