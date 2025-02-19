<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Size;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function Purchase()
    {
        if (Auth::id()) {
            $userId = Auth::id();
            $categories = Category::all();
            return view('admin_panel.purchase.add_purchase', compact('categories'));
        } else {
            return redirect()->back();
        }
    }

    public function getSubcategories($categoryname)
    {
        $subcategories = SubCategory::where('category_name', $categoryname)
            ->pluck('sub_category_name', 'id'); // Fetch subcategory names with their IDs

        return response()->json($subcategories);
    }

    public function getItems(Request $request)
    {
        $items = Product::where('category', $request->category_name)
            ->where('sub_category', $request->sub_category_name)
            ->get(['id', 'item_name', 'pcs_in_carton']); // Fetch all required fields

        return response()->json($items);
    }

    public function store_Purchase(Request $request)
    {
        $request->validate([
            'invoice_number' => 'required|unique:purchases,invoice_number',
            'purchase_date' => 'required|date',
            'party_code' => 'required',
            'party_name' => 'required',
            'category' => 'required|array',
            'subcategory' => 'required|array',
            'item' => 'required|array',
            'rate' => 'required|array',
            'carton_qty' => 'required|array',
            'pcs' => 'required|array',
            'gross_total' => 'required|array',
            'discount' => 'nullable|array',
            'amount' => 'required|array',
            'pcs_carton' => 'required|array',
            'grand_total' => 'required|numeric',
        ]);
        $userId = Auth::id();
        // JSON encode data to store in a single row
        $purchaseData = [
            'admin_or_user_id' => $userId,
            'invoice_number' => $request->invoice_number,
            'purchase_date' => $request->purchase_date,
            'party_code' => $request->party_code,
            'party_name' => $request->party_name,
            'category' => json_encode($request->category),
            'subcategory' => json_encode($request->subcategory),
            'item' => json_encode($request->item),
            'rate' => json_encode($request->rate),
            'carton_qty' => json_encode($request->carton_qty),
            'pcs' => json_encode($request->pcs),
            'gross_total' => json_encode($request->gross_total),
            'discount' => json_encode($request->discount ?? []),
            'amount' => json_encode($request->amount),
            'pcs_carton' => json_encode($request->pcs_carton),
            'grand_total' => $request->grand_total,
        ];

        // Save purchase data in a single row
        $purchase = Purchase::create($purchaseData);

        // Step 2: Update Product Stock and Wholesale Price
        foreach ($request->item as $key => $item_name) {
            $category = $request->category[$key];
            $subcategory = $request->subcategory[$key];
            $carton_qty = $request->carton_qty[$key]; // Purchased cartons
            $pcs = $request->pcs[$key]; // Purchased loose pieces
            $rate = $request->rate[$key];

            // Find the product
            $product = Product::where('item_name', $item_name)
                ->where('category', $category)
                ->where('sub_category', $subcategory) // Adjusted column name
                ->first();

            if ($product) {
                // Pehle ka stock
                $previous_cartons = $product->carton_quantity;
                $previous_pieces = $product->loose_pieces;
                $pcs_in_carton = $product->pcs_in_carton; // Carton ke andar kitne pcs hain
                $previous_stock = $product->initial_stock;

                // Naye stock ki calculation
                $new_carton_quantity = $previous_cartons + $carton_qty;
                $new_loose_pieces = $previous_pieces + $pcs;

                // Initial stock update
                $new_initial_stock = $previous_stock + ($carton_qty * $pcs_in_carton) + $pcs;

                // Stock update
                $product->carton_quantity = $new_carton_quantity;
                $product->loose_pieces = $new_loose_pieces;
                $product->initial_stock = $new_initial_stock;
                $product->wholesale_price = $rate;

                $product->save();
            }
        }


        return redirect()->back()->with('success', 'Purchase saved successfully and stock updated!');
    }

    public function all_Purchases()
    {
        if (Auth::id()) {
            $userId = Auth::id();
            $Purchases = Purchase::where('admin_or_user_id', $userId)->get();

            return view('admin_panel.purchase.all_purchase', compact('Purchases'));
        } else {
            return redirect()->back();
        }
    }

    public function purchaseInvoice($id)
    {
        $purchase = Purchase::findOrFail($id);
        return view('admin_panel.purchase.invoice', compact('purchase'));
    }
}
