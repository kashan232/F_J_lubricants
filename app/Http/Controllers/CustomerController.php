<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\City;
use App\Models\Area;
use App\Models\BusinessType;
use App\Models\CustomerLedger;
use App\Models\CustomerRecovery;
use App\Models\Salesman;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index()
    {
        if (Auth::id()) {
            $userId = Auth::id();
            $customers = Customer::where('admin_or_user_id', $userId)
                ->with(['city', 'area', 'businessType'])
                ->get();
            
            $city = City::all();
            return view('admin_panel.customer.customer', compact('customers', 'city'));
        } else {
            return redirect()->back();
        }
    }
    

    public function fetchAreas(Request $request)
    {
        $areas = Area::where('city_name', $request->city_id)->get();
        return response()->json($areas);
    }
    

    public function store(Request $request)
    {
        if (Auth::id()) {
            $userId = Auth::id();
            $customer = Customer::create([
                'admin_or_user_id' => $userId,
                'city' => $request->city,
                'area' => $request->area,
                'customer_name' => $request->customer_name,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'shop_name' => $request->shop_name,
                'business_type_name' => $request->business_type_id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // Distributor Ledger Create (One-time Opening Balance)
            CustomerLedger::create([
                'admin_or_user_id' => $userId,
                'customer_id' => $customer->id,
                'opening_balance' => $request->opening_balance, // Pehli dafa opening balance = previous balance
                'previous_balance' => $request->opening_balance, // Pehli dafa opening balance = previous balance
                'closing_balance' => $request->opening_balance, // Closing balance bhi initially same hoga
                'created_at' => Carbon::now(),
            ]);

            return redirect()->back()->with('success', 'Customer created successfully');
        } else {
            return redirect()->back();
        }
    }


    public function customer_ledger()
    {
        if (Auth::id()) {
            $userId = Auth::id();
            $CustomerLedgers = CustomerLedger::where('admin_or_user_id', $userId)->with('Customer')->get();
            $Salesmans = Salesman::where('admin_or_user_id', $userId)->where('designation', 'Saleman')->get();
            return view('admin_panel.customer.customer_ledger', compact('CustomerLedgers','Salesmans'));
        } else {
            return redirect()->back();
        }
    }

    public function customer_recovery_store(Request $request)
    {
        $ledger = CustomerLedger::find($request->ledger_id);
        $ledger->previous_balance -= $request->amount_paid;
        $ledger->closing_balance -= $request->amount_paid;
        $ledger->save();

        $userId = Auth::id();

        // Store recovery record (Optional)
        CustomerRecovery::create([
            'admin_or_user_id' => $userId,
            'customer_ledger_id' => $ledger->id,
            'amount_paid' => $request->amount_paid,
            'salesman' => $request->salesman,
            'date' => $request->date,
            'remarks' => $request->remarks,
        ]);

        return response()->json([
            'success' => true,
            'new_closing_balance' => number_format($ledger->closing_balance, 0)
        ]);
    }

    public function customer_recovery()
    {
        if (Auth::id()) {
            $userId = Auth::id();
            $Recoveries = CustomerRecovery::where('admin_or_user_id', $userId)->with('customer')->get();
            return view('admin_panel.customer.customer_recovery', compact('Recoveries'));
        } else {
            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        $customer = Customer::findOrFail($request->customer_id);
        $customer->update($request->all());
        return redirect()->back()->with('success', 'Customer updated successfully');
    }

    // public function destroy($id)
    // {
    //     Customer::findOrFail($id)->delete();
    //     return response()->json(['success' => 'Customer deleted successfully']);
    // }
    public function destroy($id)
{
    $customer = Customer::find($id);

    if (!$customer) {
        return response()->json(['status' => 'error', 'message' => 'Customer not found.'], 404);
    }

    $customer->delete();

    return response()->json(['status' => 'success', 'message' => 'Customer deleted successfully.']);
}


    public function fetchBusinessTypes()
    {
        return response()->json(BusinessType::all());
    }

    public function getCities()
    {
        $cities = City::select('id', 'city_name')->get();
        return response()->json($cities);
    }
    
    public function getAreas(Request $request)
    {
        $areas = Area::where('city_name', $request->city)
                    ->select('id', 'area_name')
                    ->get();
    
        return response()->json($areas);
    }
        

}
