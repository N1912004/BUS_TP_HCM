<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bus; // Import the Bus model

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
}
