<?php

namespace App\Http\Controllers;

use App\Models\Salesman;
use App\Models\City;
use App\Models\Area;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesmanController extends Controller
{
    // Display salesmen list
    public function salesmen()
    {
        if (Auth::id()) {
            $userId = Auth::id();
            $salesmen = Salesman::where('admin_or_user_id', $userId)->get(); // Fetch salesmen
            $cities = City::all(); // Fetch all cities
            $areas = Area::all(); // Fetch all areas

            return view('salesmen.add_salesmen', compact('salesmen', 'cities', 'areas')); // Ensure your view is correctly named
        } else {
            return redirect()->back();
        }
    }

    // Store a new salesman
    public function store_salesman(Request $request)
    {
        if (Auth::id()) {
            $userId = Auth::id();
            Salesman::create([
                'admin_or_user_id' => $userId, // Store the user's ID (ensure you have this field in your salesman table)
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number, // Add phone number
                'address' => $request->address, // Add address
                'status' => $request->status,
                'city_id' => $request->city_id,
                'area_id' => $request->area_id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            return redirect()->back()->with('success', 'Salesman added successfully');
        } else {
            return redirect()->back();
        }
    }

    // Update an existing salesman
    public function update_salesman(Request $request)
    {
        $salesman_id = $request->input('salesman_id');
        Salesman::where('id', $salesman_id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number, // Add phone number
            'address' => $request->address, // Add address
            'status' => $request->status, // Update status
            'city_id' => $request->city_id,
            'area_id' => $request->area_id,
            'updated_at' => Carbon::now(),
        ]);
        return redirect()->back()->with('success', 'Salesman updated successfully');
    }

    // Fetch all cities
    public function fetchCities()
    {
        $cities = City::all(); // Fetch all cities
        return response()->json($cities); // Return cities as JSON
    }

    // Fetch areas based on selected city
    public function fetchAreas(Request $request)
    {
        $validated = $request->validate([
            'city_id' => 'required|exists:cities,id', // Ensure the city exists
        ]);

        $areas = Area::where('city_id', $validated['city_id'])->get(); // Fetch areas for the selected city
        return response()->json($areas); // Return areas as JSON
    }
}
