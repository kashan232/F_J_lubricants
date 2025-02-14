<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\City;
use App\Models\Distributor;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DistributorController extends Controller
{

    public function Distributor()
    {
        if (Auth::id()) {
            $userId = Auth::id();
            $distributors = Distributor::where('admin_or_user_id', $userId)->get();
            $cities = City::where('admin_or_user_id', $userId)->get();
            return view('admin_panel.distributors.distributors', compact('distributors', 'cities'));
        } else {
            return redirect()->back();
        }
    }

    public function store_Distributor(Request $request)
    {

        if (Auth::id()) {
            $userId = Auth::id();
            $distributor = Distributor::create([
                'admin_or_user_id' => $userId,
                'Customer' => $request->Customer,
                'Owner' => $request->owner,
                'Address' => $request->address,
                'Contact' => $request->contact,
                'City' => $request->city,
                'Area' => $request->area,
                'Email' => $request->email,
                'Password' => Hash::make($request->password),
                'created_at' => Carbon::now(),
            ]);

            User::create([
                'user_id' => $distributor->id,
                'name' => $request->Customer,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'usertype' => 'distributor', // Agar database column ka naam "user_type" hai
            ]);

            return redirect()->back()->with('success', 'Distributor added successfully');
        } else {
            return redirect()->back();
        }
    }

    public function update_Distributor(Request $request, $id)
{
    $request->validate([
        'Customer' => 'required',
        'owner' => 'required',
        'address' => 'required',
        'contact' => 'required',
        'city' => 'required',
        'area' => 'required',
        'email' => 'required|email',
    ]);

    $distributor = Distributor::find($id);
    if (!$distributor) {
        return redirect()->back()->with('error', 'Distributor not found.');
    }

    $distributor->update([
        'Customer' => $request->Customer,
        'Owner' => $request->owner,
        'Address' => $request->address,
        'Contact' => $request->contact,
        'City' => $request->city,
        'Area' => $request->area,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'updated_at' => now(),
    ]);

    return redirect()->back()->with('success', 'Distributor updated successfully.');
}


    public function destroy($id)
    {
        Distributor::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Distributor deleted successfully');
    }

    public function get_areas(Request $request)
    {
        if (Auth::id()) {
            // Fetch the areas based on the selected city
            $areas = Area::where('city_name', $request->city_id)
                ->pluck('area_name', 'id');  // Ensure you fetch area names and IDs

            // Return the areas in JSON format
            return response()->json($areas);
        } else {
            return redirect()->back();
        }
    }
}
