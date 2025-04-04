<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseReturnController extends Controller
{

    public function showReturnForm($id)
    {
        $purchase = Purchase::with('vendor')->findOrFail($id);
        $purchase->category = json_decode($purchase->category, true);
        $purchase->subcategory = json_decode($purchase->subcategory, true);
        $purchase->item = json_decode($purchase->item, true);
        $purchase->size = json_decode($purchase->size, true);
        $purchase->rate = json_decode($purchase->rate, true);
        $purchase->carton_qty = json_decode($purchase->carton_qty, true);
        $purchase->pcs = json_decode($purchase->pcs, true);
        $purchase->pcs_carton = json_decode($purchase->pcs_carton, true);
        $purchase->measurement_size = json_decode($purchase->measurement_size, true); // <-- ADD THIS
        $purchase->gross_total = json_decode($purchase->gross_total, true);
        $purchase->discount = json_decode($purchase->discount, true);
        $purchase->amount = json_decode($purchase->amount, true);

        return view('admin_panel.purchase_return.purcahse_return', compact('purchase'));
    }

    public function store(Request $request)
    {
        $purchaseId = $request->purchase_id;
        $userId = Auth::id();
        // Just save all arrays as JSON
        PurchaseReturn::create([
            'admin_or_user_id' => $userId,
            'purchase_id' => $purchaseId,
            'category' => json_encode($request->category),
            'subcategory' => json_encode($request->subcategory),
            'item' => json_encode($request->item),
            'rate' => json_encode($request->rate),
            'carton_qty' => json_encode($request->carton_qty),
            'return_qty' => json_encode($request->return_qty),
            'pcs_carton' => json_encode($request->pcs_carton),
            'measurement' => json_encode($request->size), // measurement is size[]
            'return_amount' => json_encode($request->return_amount),
            'return_liters' => json_encode($request->return_liters),
        ]);

        // Update return status in purchase
        Purchase::where('id', $purchaseId)->update(['return_status' => 1]);

        return redirect()->back()->with('success', 'Purchase return saved successfully.');
    }

    public function all_purchase_return()
    {
        if (Auth::id()) {
            $userId = Auth::id();
            $Purchases = PurchaseReturn::where('admin_or_user_id', $userId)->get();
            return view('admin_panel.purchase_return.all_purchase_return', compact('Purchases'));
        } else {
            return redirect()->back();
        }
    }
}
