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
   // Salesmen List and Add Salesman
    public function salesmen()
    {
        if (Auth::id()) {
            $userId = Auth::id();
            $salesmen = Salesman::where('admin_or_user_id', $userId)
            ->where('status', 1)
            ->with(['city', 'area'])
            ->get();
            $cities = City::all(); // Fetch all cities
            $areas = Area::all(); // Fetch all areas
            return view('admin_panel.salesmen.add_salesmen', compact('salesmen', 'cities', 'areas'));
        } else {
            return redirect()->back();
        }
    }

// Store Salesman (already correctly handles adding new salesman)
public function store_salesman(Request $request)
{
    if (Auth::id()) {
        $userId = Auth::id();
        Salesman::create([
            'admin_or_user_id' => $userId,
            'name' => $request->name,
            'phone' => $request->phone,
            'city' => $request->city,
            'area' => $request->area,
            'address' => $request->address,
            'salary' => $request->salary,
            'status' => $request->status,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return redirect()->back()->with('success', 'Salesman added successfully');
    } else {
        return redirect()->back();
    }
}

    public function update_salesman(Request $request)
    {
        Salesman::where('id', $request->salesman_id)->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'city' => $request->city,
            'area' => $request->area,
            'address' => $request->address,
            'salary' => $request->salary,
            'status' => $request->status,
            'updated_at' => now(),
        ]);
        return redirect()->back()->with('success', 'Salesman updated successfully');
    }
    

    public function fetchCities()
    {
        $cities = City::all();
        return response()->json($cities);
    }
    

    // Fetch areas based on selected city
    public function fetchAreas(Request $request)
    {
        $areas = Area::where('city_name', $request->city_id)->get(); // city_id ko city_name karein
        return response()->json($areas);
        dd($areas());
    }
    


    public function toggleStatus(Request $request)
{
    $salesman = Salesman::find($request->salesman_id);
    if ($salesman) {
        $salesman->status = $request->status;
        $salesman->save();
        return response()->json(['success' => 'Status updated successfully!']);
    }
    return response()->json(['error' => 'Salesman not found!'], 404);
}

}
