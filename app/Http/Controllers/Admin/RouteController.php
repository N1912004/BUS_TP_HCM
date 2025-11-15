<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusRoute;
use App\Models\Bus; // Import the Bus model
use App\Models\User; // Import the User model
use App\Models\Admin; // Import the Admin model

class RouteController extends Controller
{
    public function index(Request $request)
    {
        $query = BusRoute::query();

        if ($request->has('bus_route_id')) {
            $query->where('id', $request->bus_route_id);
        }

        $routes = $query->get();
        $totalRoutes = BusRoute::count(); // Count all bus routes
        $totalBuses = Bus::count(); // Count all buses
        $totalUsers = User::count(); // Count all users
        $totalDrivers = Admin::where('role', 'driver')->count(); // Count all drivers
        return view('backend.admin.routes.index', compact('routes', 'totalRoutes', 'totalBuses', 'totalUsers', 'totalDrivers'));
    }

    public function create()
    {
        $totalRoutes = BusRoute::count();
        $totalBuses = Bus::count();
        $totalUsers = User::count();
        $totalDrivers = Admin::where('role', 'driver')->count();
        return view('backend.admin.routes.create', compact('totalRoutes', 'totalBuses', 'totalUsers', 'totalDrivers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ma_tuyen'          => 'required|unique:bus_routes,ma_tuyen|max:10',
            'diem_di'           => 'required|string|max:255',
            'diem_den'          => 'required|string|max:255',
            'thoi_gian_bat_dau' => 'required',
            'thoi_gian_ket_thuc' => 'required|after:thoi_gian_bat_dau',
        ]);

        $busRoute = BusRoute::create($validated);

        return redirect()->route('admin.routes.index')->with('success', 'Thêm tuyến xe ' . $busRoute->ma_tuyen . ' thành công!');
    }

    public function show(BusRoute $route)
    {
        return view('backend.admin.routes.show', compact('route'));
    }

    public function driversIndex()
    {
        $routes = BusRoute::all();
        return view('backend.admin.drivers.index', compact('routes'));
    }

    public function edit(BusRoute $route)
    {
        $totalRoutes = BusRoute::count();
        $totalBuses = Bus::count();
        $totalUsers = User::count();
        $totalDrivers = Admin::where('role', 'driver')->count();
        return view('backend.admin.routes.create', compact('route', 'totalRoutes', 'totalBuses', 'totalUsers', 'totalDrivers'));
    }

    public function update(Request $request, BusRoute $route)
    {
        $validated = $request->validate([
            'ma_tuyen'          => 'required|unique:bus_routes,ma_tuyen,' . $route->id . '|max:10',
            'diem_di'           => 'required|string|max:255',
            'diem_den'          => 'required|string|max:255',
            'thoi_gian_bat_dau' => 'required',
            'thoi_gian_ket_thuc' => 'required|after:thoi_gian_bat_dau',
        ]);

        $route->update($validated);

        return redirect()->route('admin.routes.index')->with('success', 'Cập nhật tuyến xe ' . $route->ma_tuyen . ' thành công!');
    }

    public function destroy(BusRoute $route)
    {
        $route->delete();
        return redirect()->route('admin.routes.index')->with('success', 'Xóa tuyến xe ' . $route->ma_tuyen . ' thành công!');
    }
}
