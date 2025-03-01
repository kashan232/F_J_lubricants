<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{
    
    public function vendors()
    {
        if (Auth::id()) {
            $userId = Auth::id();
            $Vendors = Vendor::where('admin_or_user_id', $userId)->get();
            $cities = City::where('admin_or_user_id', $userId)->get();
            return view('admin_panel.vendors.vendors', compact('Vendors', 'cities'));
        } else {
            return redirect()->back();
        }
    }

    public function store_vendors(Request $request)
    {

        if (Auth::id()) {
            $userId = Auth::id();
            $Vendor = Vendor::create([
                'admin_or_user_id' => $userId,
                'Party_code' => $request->Party_code,
                'Party_name' => $request->Party_name,
                'Party_address' => $request->Party_address,
                'Party_phone' => $request->Party_phone,
                'City' => $request->city,
                'Area' => $request->area,
                'created_at' => Carbon::now(),
            ]);

            return redirect()->back()->with('success', 'Distributor added successfully');
        } else {
            return redirect()->back();
        }
    }

    public function update_vendors(Request $request, $id)
    {

        $Vendors = Vendor::find($id);
        if (!$Vendors) {
            return redirect()->back()->with('error', 'Vendor not found.');
        }

        $Vendors->update([
            'Party_code' => $request->Party_code,
            'Party_name' => $request->Party_name,
            'Party_address' => $request->Party_address,
            'Party_phone' => $request->Party_phone,
            'City' => $request->city,
            'Area' => $request->area,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Vendor updated successfully.');
    }


}
