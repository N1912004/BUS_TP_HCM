<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bus;
use App\Models\User;
use App\Models\BusRoute;

class BusController extends Controller
{
    public function index()
    {
        $buses = Bus::with(['driver', 'busRoute'])->orderBy('id')->get();
        return view('backend.admin.buses.index', compact('buses'));
    }

    public function create()
    {
        $drivers = User::where('role', 'driver')->get(['id', 'fullname']);
        $routes = BusRoute::all(['id', 'ma_tuyen', 'diem_di', 'diem_den']);
        return view('backend.admin.buses.create', compact('drivers', 'routes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bus_number' => 'required|string|max:50|unique:buses',
            'capacity' => 'required|integer',
            'model' => 'nullable|string',
            'year' => 'nullable|integer',
            'status' => 'required|string',
            'driver_id' => 'nullable|exists:users,id',
            'bus_route_id' => 'nullable|exists:bus_routes,id',
        ]);

        Bus::create($request->all());

        return redirect()->route('admin.buses.index')
            ->with('success', 'Thêm xe thành công.');
    }

    public function edit($id)
    {
        // Tìm bus theo id, nếu không tìm thấy sẽ báo lỗi 404
        $bus = Bus::findOrFail($id);

        // Lấy danh sách tài xế
        $drivers = User::where('role', 'driver')
            ->get(['id', 'fullname']);

        // Lấy tất cả các route với các cột: id, ma_tuyen, diem_di, diem_den
        $routes = BusRoute::all(['id', 'ma_tuyen', 'diem_di', 'diem_den']);

        // Trả về view với dữ liệu
        return view('backend.admin.buses.edit', compact('bus', 'drivers', 'routes'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'bus_number' => 'required|string|max:50|unique:buses,bus_number,' . $id,
            'capacity' => 'required|integer',
            'model' => 'nullable|string',
            'year' => 'nullable|integer',
            'status' => 'required|string',
            'driver_id' => 'nullable|exists:users,id',
            'bus_route_id' => 'nullable|exists:bus_routes,id',
        ]);

        Bus::findOrFail($id)->update($request->all());

        return redirect()->route('admin.buses.index')
            ->with('success', 'Cập nhật xe thành công.');
    }

    public function destroy($id)
    {
        Bus::findOrFail($id)->delete();
        return redirect()->route('admin.buses.index')->with('success', 'Xóa xe thành công.');
    }
    public function getRoutesByDriver($driverId)
    {
        // Kiểm tra tài xế tồn tại
        $driver = User::find($driverId);

        if (!$driver) {
            return response()->json(['routes' => []]);
        }

        // Removed driver_id filter as 'bus_routes' table does not have 'driver_id'
        $routes = BusRoute::all(['id', 'ma_tuyen', 'diem_di', 'diem_den']);

        return response()->json([
            'routes' => $routes
        ]);
    }


}
