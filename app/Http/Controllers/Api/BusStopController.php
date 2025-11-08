<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BusStopController extends Controller
{
    public function index()
    {
        $busStops = \App\Models\BusStop::all();
        return response()->json($busStops);
    }

    public function store(Request $request)
    {
        $busStop = \App\Models\BusStop::create($request->all());
        return response()->json($busStop, 201);
    }

    public function nearby(Request $request)
    {
        $latitude = $request->query('lat');
        $longitude = $request->query('lon');
        $radius = $request->query('radius', 5); // Default radius of 5 km

        if (!$latitude || !$longitude) {
            return response()->json(['error' => 'Latitude and longitude are required.'], 400);
        }

        // Haversine formula to find nearby bus stops
        // Haversine formula to find nearby bus stops
        // Using a more robust formula for broader SQL compatibility
        $busStops = \App\Models\BusStop::selectRaw("
            id, name, address, latitude, longitude,
            (6371 * ACOS(
                COS(RADIANS(?)) * COS(RADIANS(latitude)) *
                COS(RADIANS(longitude) - RADIANS(?)) +
                SIN(RADIANS(?)) * SIN(RADIANS(latitude))
            )) AS distance
        ", [$latitude, $longitude, $latitude])
        ->having('distance', '<', $radius)
        ->orderBy('distance')
        ->get();

        return response()->json($busStops);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $busStop = \App\Models\BusStop::find($id);

        if (!$busStop) {
            return response()->json(['message' => 'Bus stop not found'], 404);
        }

        return response()->json($busStop);
    }
}
