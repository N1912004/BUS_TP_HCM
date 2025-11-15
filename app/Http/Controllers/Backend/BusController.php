<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bus; // Import the Bus model
use App\Models\User; // Import the User model
use App\Models\BusRoute; // Import the BusRoute model

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
        return view('backend.buses.index', compact('buses')); // Pass buses to the view
    }

    public function getCoordinates($bus)
    {
        $bus = Bus::findOrFail($bus);

        // Assuming there is a 'coordinates' column in the 'buses' table
        $coordinates = $bus->coordinates;

        return response()->json($coordinates);
    }

    public function getRoutesByDriver($driverId)
    {
        // Extract only the numeric part of driverId
        $numericDriverId = (int) $driverId;

        $driver = User::with('busRoute')->find($numericDriverId);

        if (!$driver) {
            // Return 404 if the driver is not found
            return response()->json(['message' => 'Driver not found'], 404);
        }

        // Assuming a driver has one busRoute
        $routes = $driver->busRoute ? [$driver->busRoute] : [];

        // Return 200 OK with routes (empty array if no route assigned)
        return response()->json(['routes' => $routes], 200);
    }
}
