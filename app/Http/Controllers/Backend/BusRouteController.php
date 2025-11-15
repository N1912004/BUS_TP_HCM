<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusRoute; // Import the BusRoute model
use Illuminate\Database\UniqueConstraintViolationException; // Import the exception

class BusRouteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $busRoutes = BusRoute::all();
        return view('backend.busroute.index')->with('busRoutes', $busRoutes);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.busroute.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ma_tuyen' => 'required|string|max:255|unique:bus_routes,ma_tuyen', // Add unique validation rule
            'diem_di' => 'required|string|max:255',
            'diem_den' => 'required|string|max:255',
            'ngay' => 'required|date',
            'thoi_gian_bat_dau' => 'required|date_format:H:i',
            'thoi_gian_ket_thuc' => 'required|date_format:H:i|after:thoi_gian_bat_dau',
        ]);

        try {
            BusRoute::create([
                'ma_tuyen' => $request->ma_tuyen,
                'diem_di' => $request->diem_di,
                'diem_den' => $request->diem_den,
                'ngay' => $request->ngay,
                'thoi_gian_bat_dau' => $request->thoi_gian_bat_dau,
                'thoi_gian_ket_thuc' => $request->thoi_gian_ket_thuc,
            ]);

            return redirect()->route('admin.busroutes.index')->with('success', 'Tuyến xe đã được thêm thành công!');
        } catch (UniqueConstraintViolationException $e) {
            return redirect()->back()->withInput()->withErrors(['ma_tuyen' => __('validation.custom.ma_tuyen.unique')]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(BusRoute $busRoute)
    {
        // For now, redirect to the edit page as the user's focus is on editing.
        return redirect()->route('admin.busroutes.edit', $busRoute);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BusRoute $busRoute)
    {
        return view('backend.busroute.create', compact('busRoute'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BusRoute $busRoute)
    {
        $request->validate([
            'ma_tuyen' => 'required|string|max:255|unique:bus_routes,ma_tuyen,' . $busRoute->id,
            'diem_di' => 'required|string|max:255',
            'diem_den' => 'required|string|max:255',
            'ngay' => 'required|date',
            'thoi_gian_bat_dau' => 'required|date_format:H:i',
            'thoi_gian_ket_thuc' => 'required|date_format:H:i|after:thoi_gian_bat_dau',
        ]);

        $busRoute->update([
            'ma_tuyen' => $request->ma_tuyen,
            'diem_di' => $request->diem_di,
            'diem_den' => $request->diem_den,
            'ngay' => $request->ngay,
            'thoi_gian_bat_dau' => $request->thoi_gian_bat_dau,
            'thoi_gian_ket_thuc' => $request->thoi_gian_ket_thuc,
        ]);

        return redirect()->route('admin.busroutes.index')->with('success', 'Tuyến xe đã được cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BusRoute $busRoute)
    {
        $busRoute->delete();
        return redirect()->route('admin.busroutes.index')->with('success', 'Tuyến xe đã được xóa thành công!');
    }

    /**
     * Toggle the active status of the specified resource.
     */
    public function toggleStatus(BusRoute $busRoute)
    {
        $busRoute->is_active = !$busRoute->is_active;
        $busRoute->save();

        return response()->json([
            'success' => true,
            'is_active' => $busRoute->is_active,
            'ma_tuyen' => $busRoute->ma_tuyen,
            'message' => 'Trạng thái tuyến xe đã được cập nhật thành công!'
        ]);
    }
}
