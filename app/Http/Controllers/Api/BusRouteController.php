<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusRoute; // Import the BusRoute model

class BusRouteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $busRoutes = BusRoute::all()->map(function ($route) {
            // Ensure coords is an array, even if null in DB
            $coords = $route->coords ?? [];

            // Log the coords for debugging
            \Log::info("BusRoute ID: {$route->id}, Route Number: {$route->route_number}, Coords: " . json_encode($coords));

            return [
                'id' => $route->id,
                'route_number' => $route->ma_tuyen,
                'origin' => __($route->diem_di),
                'destination' => __($route->diem_den),
                'name' => ($route->ma_tuyen == 'Metro 1') ? __('map_route.metro_line') . ' 1' . ' ' . __($route->diem_di) . ' - ' . __($route->diem_den) : ((string)$route->ma_tuyen === '0' ? __('map_route.route_number') . ' 0' : __('map_route.route_number') . ' ' . $route->ma_tuyen),
                'desc' => __($route->diem_di) . ' - ' . __($route->diem_den),
                'time' => '05:00 - 22:00', // Assuming time is not stored in DB yet
                'price' => '20,000 VNĐ', // Assuming price is not stored in DB yet
                'coords' => $coords, // Use actual coords from DB, defaulting to empty array if null
            ];
        });
        return response()->json($busRoutes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Get the stations for a specific bus route.
     */
    public function getStations(string $id)
    {
        \Log::info("Fetching stations for route ID: {$id}");

        $route = BusRoute::find($id);

        if (!$route) {
            \Log::warning("Bus route not found for ID: {$id}");
            return response()->json(['message' => 'Bus route not found'], 404);
        }

        // In a real application, you would fetch stations associated with this route from the database.
        // For now, we'll use a placeholder or a simplified approach.
        // Assuming a route might have a 'stations' relationship or a 'station_names' attribute.
        // If 'stations' is a relationship, you'd do $route->stations->pluck('name').
        // If 'station_names' is a JSON column, you'd do $route->station_names.

        $stations = [];
        $routeCoords = $route->coords ?? [];

        if ($route->ma_tuyen == 'Metro 1') { // Example for Metro Line 1
            $stations = [
                ['name' => __('map_route.station_ben_thanh'), 'latitude' => 10.775843, 'longitude' => 106.701755],
                ['name' => __('map_route.station_city_theater'), 'latitude' => 10.779483, 'longitude' => 106.703000],
                ['name' => __('map_route.station_ba_son'), 'latitude' => 10.787000, 'longitude' => 106.708000],
            ];
        } else if (!empty($routeCoords) && is_array($routeCoords)) {
            // If coords are available, use them to generate "stations"
            // For simplicity, we'll take a few points along the route as "stations"
            // In a real app, these would be actual bus stops from a related table
            $numCoords = count($routeCoords);
            if ($numCoords > 0) {
                $stations[] = ['name' => $route->diem_di . ' (' . __('map_route.start_point_label') . ')', 'latitude' => $routeCoords[0][0], 'longitude' => $routeCoords[0][1]];
                if ($numCoords > 1) {
                    // Add intermediate points as generic "stops"
                    for ($i = 1; $i < $numCoords - 1; $i += floor($numCoords / 3)) { // Take a few intermediate points
                        $stations[] = ['name' => __('map_route.intermediate_stop') . ' ' . ($i + 1), 'latitude' => $routeCoords[$i][0], 'longitude' => $routeCoords[$i][1]];
                    }
                    $stations[] = ['name' => $route->diem_den . ' (' . __('map_route.end_point_label') . ')', 'latitude' => $routeCoords[$numCoords - 1][0], 'longitude' => $routeCoords[$numCoords - 1][1]];
                }
            }
        } else {
            // Fallback if no coords are available
            $stations = [
                ['name' => $route->diem_di . ' (' . __('map_route.start_point_label') . ')', 'latitude' => null, 'longitude' => null],
                ['name' => $route->diem_den . ' (' . __('map_route.end_point_label') . ')', 'latitude' => null, 'longitude' => null],
            ];
        }


        $routeName = ($route->ma_tuyen == 'Metro 1') ? __('map_route.metro_line') . ' 1' : __('map_route.route_number') . ' ' . $route->ma_tuyen;

        return response()->json([
            'id' => $route->id,
            'name' => $routeName,
            'stations' => $stations,
            'from' => $route->diem_di,
            'to' => $route->diem_den,
        ]);
    }

    /**
     * Get the schedule for a specific bus route.
     */
    public function getSchedule(string $id)
    {
        $route = BusRoute::find($id);

        if (!$route) {
            return response()->json(['message' => 'Bus route not found'], 404);
        }

        // Placeholder for schedule data. In a real app, this would come from a DB.
        $schedule = [
            [
                'time' => '05:00',
                'description' => 'Chuyến đầu tiên từ Bến Thành',
            ],
            [
                'time' => '06:00',
                'description' => 'Chuyến tiếp theo',
            ],
            [
                'time' => '07:00',
                'description' => 'Giờ cao điểm buổi sáng',
            ],
            [
                'time' => '12:00',
                'description' => 'Giờ trưa',
            ],
            [
                'time' => '17:00',
                'description' => 'Giờ cao điểm buổi chiều',
            ],
            [
                'time' => '22:00',
                'description' => 'Chuyến cuối cùng đến Suối Tiên',
            ],
        ];

        return response()->json([
            'route_id' => $route->id,
            'route_name' => $route->ma_tuyen, // Assuming BusRoute model has a 'name' attribute
            'schedule' => $schedule,
        ]);
    }

    /**
     * Get bus routes near a specified latitude and longitude.
     */
    public function nearby(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lon' => 'required|numeric',
            'radius' => 'numeric|min:0.1|max:100', // Radius in kilometers
        ]);

        $userLat = $request->lat;
        $userLon = $request->lon;
        $radius = $request->input('radius', 5); // Default radius of 5 km

        $nearbyBusRoutes = [];
        $allBusRoutes = BusRoute::all();

        foreach ($allBusRoutes as $route) {
            $coords = $route->coords ?? [];
            $isNearby = false;

            foreach ($coords as $coord) {
                if (isset($coord[0]) && isset($coord[1])) {
                    $routeLat = $coord[0];
                    $routeLon = $coord[1];

                    $distance = $this->haversineGreatCircleDistance(
                        $userLat, $userLon, $routeLat, $routeLon
                    );

                    if ($distance <= $radius) {
                        $isNearby = true;
                        break;
                    }
                }
            }

            if ($isNearby) {
                $nearbyBusRoutes[] = [
                    'id' => $route->id,
                    'route_number' => $route->ma_tuyen,
                    'origin' => __($route->diem_di),
                    'destination' => __($route->diem_den),
                    'name' => ($route->ma_tuyen == 'Metro 1') ? __('map_route.metro_line') . ' 1' : ((string)$route->ma_tuyen === '0' ? __('map_route.route_number') . ' 0' : __('map_route.route_number') . ' ' . $route->ma_tuyen),
                    'desc' => __($route->diem_di) . ' - ' . __($route->diem_den),
                    'time' => '05:00 - 22:00',
                    'price' => '20,000 VNĐ',
                    'coords' => $coords,
                ];
            }
        }

        return response()->json($nearbyBusRoutes);
    }

    /**
     * Calculates the great-circle distance between two points, with
     * the Haversine formula.
     * @param float $latitudeFrom Latitude of start point in [deg decimal]
     * @param float $longitudeFrom Longitude of start point in [deg decimal]
     * @param float $latitudeTo Latitude of end point in [deg decimal]
     * @param float $longitudeTo Longitude of end point in [deg decimal]
     * @param int $earthRadius Mean earth radius in [m]
     * @return float Distance between points in [km] (or $earthRadius units)
     */
    private function haversineGreatCircleDistance(
        $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371
    ) {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius; // Distance in km
    }
}
