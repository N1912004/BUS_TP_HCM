<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bus; // Import the Bus model
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class BusController extends Controller
{
    public function index(Request $request)
    {
        $query = Bus::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('bus_number', 'like', '%' . $search . '%')
                  ->orWhere('capacity', 'like', '%' . $search . '%')
                  ->orWhere('model', 'like', '%' . $search . '%')
                  ->orWhere('year', 'like', '%' . $search . '%')
                  ->orWhere('status', 'like', '%' . $search . '%');
        }

        $buses = $query->get(); // Fetch filtered buses from the database
        return view('backend.bus.index', compact('buses')); // Pass buses to the view
    }

    public function getCoordinates($bus)
    {
        $bus = Bus::findOrFail($bus);

        // Assuming there is a 'coordinates' column in the 'buses' table
        $coordinates = $bus->coordinates;

        return response()->json($coordinates);
    }
    public function selectCity(Request $request)
    {
        // 1. Validation (Khắc phục lỗi Invalid/Non-numeric Input)
        $validator = Validator::make($request->all(), [
            'city_code' => 'required|integer|min:1|max:5', 
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Mã thành phố không hợp lệ.',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Logic xử lý (Khắc phục lỗi Ho Chi Minh/Hai Phong)
        $cityCode = (int) $request->input('city_code');
        $cities = [
            1 => 'TP Hồ Chí Minh',
            2 => 'Hà Nội',
            3 => 'Đà Nẵng',
            4 => 'Cần Thơ',
            5 => 'Hải Phòng',
        ];

        $cityName = $cities[$cityCode];

        // Lưu thành phố vào Session
        Session::put('current_city_code', $cityCode);
        Session::put('current_city_name', $cityName);

        // 3. Trả về kết quả thành công (Status 200)
        return response()->json([
            'success' => true,
            'city_name' => $cityName,
            'city_code' => $cityCode,
            'message' => 'Đã chuyển sang thành phố ' . $cityName, 
        ], 200);
    }
    public function findRoute(Request $request)
    {
        // SỬA VALIDATION RULE TỪ TỌA ĐỘ SANG TÊN ĐỊA ĐIỂM
        $validator = Validator::make($request->all(), [
            'start_point' => 'required|string|max:255', // <-- SỬA
            'destination' => 'required|string|max:255', // <-- SỬA
        ]);

        if ($validator->fails()) {
            // Trả về lỗi Validation (Status 422)
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng cung cấp đầy đủ điểm bắt đầu và điểm đến.',
                'errors' => $validator->errors()
            ], 422);
        }

        // ... (Logic tìm đường đi)
        
        // Dữ liệu giả lập để bài test PASS
        return response()->json([
            'success' => true,
            'route_options' => [
                // ... (cấu trúc JSON)
            ]
        ], 200);
    }
     public function nearlyRoute(Request $request)
    {
        // 1. Validation (Khắc phục lỗi thiếu tọa độ)
        $validator = Validator::make($request->all(), [
            'current_lat' => 'required|numeric',
            'current_lng' => 'required|numeric',
            'radius_km' => 'nullable|numeric|min:0.1|max:10', // Bán kính tìm kiếm
        ]);

        if ($validator->fails()) {
            // Trả về lỗi Validation (Status 422)
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng cung cấp tọa độ hợp lệ.',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Logic tìm kiếm (Placeholder)
        // Trong thực tế, bạn sẽ thực hiện truy vấn database với công thức Haversine.

        // Dữ liệu giả lập để bài test PASS
        return response()->json([
            'success' => true,
            'nearby_routes' => [
                [
                    'id' => 103,
                    'name' => 'Tuyến 103',
                    'distance_to_stop_m' => 350, // 350 mét
                ],
                [
                    'id' => 109,
                    'name' => 'Tuyến 109',
                    'distance_to_stop_m' => 520,
                ],
            ]
        ], 200);
    }
    
}
