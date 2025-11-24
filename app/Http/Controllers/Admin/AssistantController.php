<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Bus; // Import the Bus model for statistics
use App\Models\BusRoute; // Import the BusRoute model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AssistantController extends Controller
{
    public function index()
    {
        $assistants = User::where('role', 'assistant')->with('busRoute')->get();
        $routes = BusRoute::all(); // For general route information if needed in the index view

        // Fetch statistics for the layout
        $totalRoutes = BusRoute::count();
        $totalBuses = Bus::count();
        $totalUsers = User::count();
        $totalDrivers = User::where('role', 'driver')->count();
        $totalAssistants = User::where('role', 'assistant')->count();


        return view('backend.admin.assistants.index', compact(
            'assistants',
            'routes',
            'totalRoutes',
            'totalBuses',
            'totalUsers',
            'totalDrivers',
            'totalAssistants'
        ));
    }

    public function create()
    {
        // Fetch statistics for the layout
        $totalRoutes = BusRoute::count();
        $totalBuses = Bus::count();
        $totalUsers = User::count();
        $totalDrivers = User::where('role', 'driver')->count();
        $totalAssistants = User::where('role', 'assistant')->count();

        // Fetch all bus routes
        $busRoutes = BusRoute::all();

        return view('backend.admin.assistants.create', compact(
            'totalRoutes',
            'totalBuses',
            'totalUsers',
            'totalDrivers',
            'totalAssistants',
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
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'bus_route_id' => 'required|exists:bus_routes,id',
        ]);

        User::create([
            'fullname' => $request->fullname,
            'birthday' => $request->birthday,
            'gender' => $request->gender,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'assistant', // Set role to assistant
            'is_verified' => true,
            'bus_route_id' => $request->bus_route_id,
        ]);

        return redirect()->route('admin.assistants.index')->with('success', 'Phụ xe đã được thêm thành công!');
    }

    public function edit(User $assistant)
    {
        // Fetch statistics for the layout
        $totalRoutes = BusRoute::count();
        $totalBuses = Bus::count();
        $totalUsers = User::count();
        $totalDrivers = User::where('role', 'driver')->count();
        $totalAssistants = User::where('role', 'assistant')->count();
        $busRoutes = BusRoute::all();

        return view('backend.admin.assistants.edit', compact(
            'assistant',
            'totalRoutes',
            'totalBuses',
            'totalUsers',
            'totalDrivers',
            'totalAssistants',
            'busRoutes'
        ));
    }

    public function update(Request $request, User $assistant)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'birthday' => 'required|date',
            'gender' => 'required|string|in:Nam,Nữ',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'username' => 'required|string|max:255|unique:users,username,' . $assistant->id,
            'bus_route_id' => 'required|exists:bus_routes,id',
        ]);

        $assistant->update([
            'fullname' => $request->fullname,
            'birthday' => $request->birthday,
            'gender' => $request->gender,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'username' => $request->username,
            'bus_route_id' => $request->bus_route_id,
        ]);

        return redirect()->route('admin.assistants.index')->with('success', 'Thông tin phụ xe đã được cập nhật thành công!');
    }

    public function destroy(User $assistant)
    {
        $assistant->delete();
        return redirect()->route('admin.assistants.index')->with('success', 'Phụ xe đã được xóa thành công!');
    }

    public function show(User $assistant)
    {
        // Fetch statistics for the layout
        $totalRoutes = BusRoute::count();
        $totalBuses = Bus::count();
        $totalUsers = User::count();
        $totalDrivers = User::where('role', 'driver')->count();
        $totalAssistants = User::where('role', 'assistant')->count();

        return view('backend.admin.assistants.show', compact(
            'assistant',
            'totalRoutes',
            'totalBuses',
            'totalUsers',
            'totalDrivers',
            'totalAssistants'
        ));
    }
}
