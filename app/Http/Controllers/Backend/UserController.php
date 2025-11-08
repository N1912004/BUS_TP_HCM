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
}
