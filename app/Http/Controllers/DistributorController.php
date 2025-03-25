<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\City;
use App\Models\Distributor;
use App\Models\DistributorLedger;
use App\Models\Recovery;
use App\Models\Salesman;
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

            // Distributor Create
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

            // Distributor Ledger Create (One-time Opening Balance)
            DistributorLedger::create([
                'admin_or_user_id' => $userId,
                'distributor_id' => $distributor->id,
                'opening_balance' => $request->opening_balance, // Pehli dafa opening balance = previous balance
                'previous_balance' => $request->opening_balance, // Pehli dafa opening balance = previous balance
                'closing_balance' => $request->opening_balance, // Closing balance bhi initially same hoga
                'created_at' => Carbon::now(),
            ]);

            // Create Distributor User Account
            User::create([
                'user_id' => $distributor->id,
                'name' => $request->Customer,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'usertype' => 'distributor',
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

    public function Distributor_ledger()
    {
        if (Auth::id()) {
            $userId = Auth::id();
            $DistributorLedgers = DistributorLedger::where('admin_or_user_id', $userId)->with('distributor')->get();
            $Salesmans = Salesman::where('admin_or_user_id', $userId)->where('designation', 'Saleman')->get();
            return view('admin_panel.distributors.distributors_ledger', compact('DistributorLedgers', 'Salesmans'));
        } else {
            return redirect()->back();
        }
    }

    public function recovery_store(Request $request)
    {
        $ledger = DistributorLedger::find($request->ledger_id);

        // ❌ Previous balance ko nahi chhedna
        // $ledger->previous_balance -= $request->amount_paid;  ❌ Remove this line

        // ✅ Sirf closing_balance ko update karna hai
        $ledger->closing_balance -= $request->amount_paid;
        $ledger->save();

        $userId = Auth::id();

        // Recovery Record Save Karna Hai
        Recovery::create([
            'admin_or_user_id' => $userId,
            'distributor_ledger_id' => $ledger->id,
            'amount_paid' => $request->amount_paid,
            'salesman' => $request->salesman,
            'date' => $request->date,
        ]);

        return response()->json([
            'success' => true,
            'new_closing_balance' => number_format($ledger->closing_balance, 0)
        ]);
    }


    public function Distributor_recovery()
    {
        if (Auth::id()) {
            $userId = Auth::id();
            $Recoveries = Recovery::where('admin_or_user_id', $userId)->with('distributor')->get();
            return view('admin_panel.distributors.distributor_recovery', compact('Recoveries'));
        } else {
            return redirect()->back();
        }
    }
}
