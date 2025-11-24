<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BusRoute;
use App\Models\Bus; // Import the Bus model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{
    public function index()
    {
        $drivers = User::where('role', 'driver')->with('busRoute')->get();
        $routes = BusRoute::all();
        
        // Fetch statistics
        $totalRoutes = BusRoute::count();
        $totalBuses = Bus::count();
        $totalUsers = User::count();
        $totalDrivers = User::where('role', 'driver')->count();

        return view('backend.admin.drivers.index', compact(
            'drivers', 
            'routes', 
            'totalRoutes', 
            'totalBuses', 
            'totalUsers', 
            'totalDrivers'
        ));
    }

    public function create()
    {
        // Fetch statistics for the layout
        $totalRoutes = BusRoute::count();
        $totalBuses = Bus::count();
        $totalUsers = User::count();
        $totalDrivers = User::where('role', 'driver')->count();

        // Fetch all bus routes
        $busRoutes = \App\Models\BusRoute::all();

        return view('backend.admin.drivers.create', compact(
            'totalRoutes', 
            'totalBuses', 
            'totalUsers', 
            'totalDrivers',
            'busRoutes' // Pass bus routes to the view
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'birthday' => 'required|date',
            'gender' => 'required|string|in:Nam,Nữ',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'license_number' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'bus_route_id' => 'required|exists:bus_routes,id',
        ]);

        User::create([
            'fullname' => $request->fullname,
            'birthday' => $request->birthday,
            'gender' => $request->gender,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'license_number' => $request->license_number,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'driver',
            'is_verified' => true,
            'bus_route_id' => $request->bus_route_id,
        ]);

        return redirect()->route('admin.drivers.index')->with('success', 'Tài xế đã được thêm thành công!');
    }

    public function edit(User $driver)
    {
        // Fetch statistics for the layout
        $totalRoutes = BusRoute::count();
        $totalBuses = Bus::count();
        $totalUsers = User::count();
        $totalDrivers = User::where('role', 'driver')->count();
        $busRoutes = \App\Models\BusRoute::all();

        return view('backend.admin.drivers.edit', compact(
            'driver',
            'totalRoutes',
            'totalBuses',
            'totalUsers',
            'totalDrivers',
            'busRoutes'
        ));
    }

    public function update(Request $request, User $driver)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'birthday' => 'required|date',
            'gender' => 'required|string|in:Nam,Nữ',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'license_number' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $driver->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $driver->id,
            'bus_route_id' => 'required|exists:bus_routes,id',
        ]);

        $driver->update([
            'fullname' => $request->fullname,
            'birthday' => $request->birthday,
            'gender' => $request->gender,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'license_number' => $request->license_number,
            'username' => $request->username,
            'email' => $request->email,
            'bus_route_id' => $request->bus_route_id,
        ]);

        return redirect()->route('admin.drivers.index')->with('success', 'Thông tin tài xế đã được cập nhật thành công!');
    }

    public function destroy(User $driver)
    {
        $driver->delete();
        return redirect()->route('admin.drivers.index')->with('success', 'Tài xế đã được xóa thành công!');
    }

    public function show(User $driver)
    {
        // Fetch statistics for the layout
        $totalRoutes = BusRoute::count();
        $totalBuses = Bus::count();
        $totalUsers = User::count();
        $totalDrivers = User::where('role', 'driver')->count();

        return view('backend.admin.drivers.show', compact(
            'driver',
            'totalRoutes',
            'totalBuses',
            'totalUsers',
            'totalDrivers'
        ));
    }
}
