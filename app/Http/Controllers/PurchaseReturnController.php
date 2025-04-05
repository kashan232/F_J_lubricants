<?php

namespace App\Http\Controllers;

use App\Models\Product;
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

        // Save the return record
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
            'measurement' => json_encode($request->size),
            'return_amount' => json_encode($request->return_amount),
            'return_liters' => json_encode($request->return_liters),
        ]);

        // Update return status in purchase
        Purchase::where('id', $purchaseId)->update(['return_status' => 1]);

        // Step 2: Adjust stock in products table
        for ($i = 0; $i < count($request->item); $i++) {
            $category = $request->category[$i];
            $subcategory = $request->subcategory[$i];
            $item_name = $request->item[$i];

            $returnQty = (int)$request->return_qty[$i]; // cartons returned
            $pcsPerCarton = (int)$request->pcs_carton[$i]; // pieces per carton
            $totalReturnedPcs = $returnQty * $pcsPerCarton;

            // Fetch the product record
            $product = Product::where('category', $category)
                ->where('sub_category', $subcategory)
                ->where('item_name', $item_name)
                ->first();

            if ($product) {
                $product->carton_quantity = max(0, $product->carton_quantity - $returnQty);
                $product->initial_stock = max(0, $product->initial_stock - $totalReturnedPcs);
                $product->save();
            }
        }

        return redirect()->back()->with('success', 'Purchase return saved and stock updated successfully.');
    }


    public function all_purchase_return()
    {
        if (Auth::id()) {
            $userId = Auth::id();
            $Purchases = PurchaseReturn::with('purchase')->where('admin_or_user_id', $userId)->get();
            return view('admin_panel.purchase_return.all_purchase_return', compact('Purchases'));
        } else {
            return redirect()->back();
        }
    }
}
