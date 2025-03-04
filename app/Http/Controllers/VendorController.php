<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Purchase;
use App\Models\Vendor;
use App\Models\VendorLedger;
use App\Models\VendorPayment;
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

            // Vendor Ledger Create (One-time Opening Balance)
            VendorLedger::create([
                'admin_or_user_id' => $userId,
                'vendor_id' => $Vendor->id,
                'previous_balance' => $request->opening_balance, // Pehli dafa opening balance = previous balance
                'closing_balance' => $request->opening_balance, // Closing balance bhi initially same hoga
                'created_at' => Carbon::now(),
            ]);


            return redirect()->back()->with('success', 'Vendor added successfully');
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

    public function vendors_ledger()
    {
        if (Auth::id()) {
            $userId = Auth::id();
            $VendorLedgers = VendorLedger::where('admin_or_user_id', $userId)->with('vendor')->get();
            return view('admin_panel.vendors.vendors_ledger', compact('VendorLedgers'));
        } else {
            return redirect()->back();
        }
    }

    public function vendors_payment(Request $request)
    {
        $vendor = Vendor::findOrFail($request->vendor_id);
        $purchase = Purchase::findOrFail($request->purchase_id);

        $amountPaid = $request->amount_paid;

        // Check if previous payments exist
        $previousPayments = VendorPayment::where('purchase_id', $purchase->id)->sum('amount_paid');

        // Calculate Remaining Amount
        $remainingAmount = $purchase->grand_total - ($previousPayments + $amountPaid);

        // Update Purchase Table with Remaining Amount & Status
        $purchase->remaining_amount = $remainingAmount;
        $purchase->status = ($remainingAmount <= 0) ? 'Paid' : 'Unpaid';
        $purchase->save();

        // Update Vendor Ledger (If Entry Exists, Update It)
        $vendorLedger = VendorLedger::where('vendor_id', $vendor->id)->first();
        if ($vendorLedger) {
            $vendorLedger->closing_balance -= $amountPaid;
            $vendorLedger->save();
        } else {
            VendorLedger::create([
                'vendor_id' => $vendor->id,
                'previous_balance' => 0,
                'closing_balance' => -$amountPaid,
            ]);
        }

        $userId = Auth::id();

        // Store Payment Record
        VendorPayment::create([
            'admin_or_user_id' => $userId,
            'vendor_id' => $vendor->id,
            'purchase_id' => $purchase->id,
            'amount_paid' => $amountPaid,
            'payment_date' => $request->payment_date,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Payment recorded successfully.');
    }

    public function amount_paid_vendors()
    {
        if (Auth::id()) {
            $userId = Auth::id();
            $VendorPayments = VendorPayment::where('admin_or_user_id', $userId)->with('vendor')->get();
            return view('admin_panel.vendors.vendor_recovery', compact('VendorPayments'));
        } else {
            return redirect()->back();
        }
    }
}
