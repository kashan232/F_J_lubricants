<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Distributor;
use App\Models\DistributorLedger;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Salesman;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function add_sale()
    {
        if (Auth::id()) {
            $userId = Auth::id();

            $Distributors = Distributor::where('admin_or_user_id', $userId)->get();
            $categories = Category::where('admin_or_user_id', $userId)->get();
            $Staffs = Salesman::where('admin_or_user_id', $userId)->get();

            return view('admin_panel.sale.add_sale', compact('Distributors', 'categories','Staffs'));
        } else {
            return redirect()->back();
        }
    }

    public function store_sale(Request $request)
    {
        if (Auth::id()) {
            $userId = Auth::id();
            $invoiceNo = Sale::generateSaleInvoiceNo();
            $request->validate([
                'Date' => 'required|date',
                'Booker' => 'required|string',
                'Saleman' => 'required|string',
                'grand_total' => 'required|numeric',
                'discount_value' => 'required|numeric',
                'scheme_value' => 'required|numeric',
                'net_amount' => 'required|numeric',
                'category' => 'required|array',
                'subcategory' => 'required|array',
                'code' => 'required|array',
                'item' => 'required|array',
                'size' => 'required|array',
                'pcs_carton' => 'required|array',
                'carton_qty' => 'required|array',
                'pcs' => 'required|array',
                'liter' => 'required|array',
                'rate' => 'required|array',
                'discount' => 'required|array',
                'amount' => 'required|array',
            ]);

            // Sale Data Save
            $sale = Sale::create([
                'admin_or_user_id' => $userId,
                'invoice_number' => $invoiceNo,
                'Date' => $request->Date,
                'distributor_id' => $request->distributor_id,
                'distributor_city' => $request->distributor_city,
                'distributor_area' => $request->distributor_area,
                'distributor_address' => $request->distributor_address,
                'distributor_phone' => $request->distributor_phone,
                'Booker' => $request->Booker,
                'Saleman' => $request->Saleman,
                'category' => json_encode($request->category),
                'subcategory' => json_encode($request->subcategory),
                'code' => json_encode($request->code),
                'item' => json_encode($request->item),
                'size' => json_encode($request->size),
                'pcs_carton' => json_encode($request->pcs_carton),
                'carton_qty' => json_encode($request->carton_qty),
                'pcs' => json_encode($request->pcs),
                'liter' => json_encode($request->liter),
                'rate' => json_encode($request->rate),
                'discount' => json_encode($request->discount),
                'amount' => json_encode($request->amount),
                'grand_total' => $request->grand_total,
                'discount_value' => $request->discount_value,
                'scheme_value' => $request->scheme_value,
                'net_amount' => $request->net_amount,
            ]);

            // Stock Update Logic
            foreach ($request->code as $index => $item_code) {
                $product = Product::where('item_code', $item_code)->first();
                if ($product) {
                    $cartonQty = (int) $request->carton_qty[$index];
                    $pcsSold = (int) $request->pcs[$index];

                    // Stock Calculation
                    $product->carton_quantity -= $cartonQty;
                    $product->initial_stock -= ($cartonQty * $product->pcs_in_carton) + $pcsSold;

                    // Ensure stock doesn't go negative
                    $product->carton_quantity = max($product->carton_quantity, 0);
                    $product->initial_stock = max($product->initial_stock, 0);

                    $product->save();
                }
            }

            // Fetch previous balance for distributor
            $previousBalance = DistributorLedger::where('distributor_id', $request->distributor_id)
                ->value('closing_balance') ?? 0; // If no previous balance, start from 0

            // Calculate new balances
            $newPreviousBalance = $request->net_amount;
            $newClosingBalance = $previousBalance + $request->net_amount;

            // Update or create distributor ledger
            DistributorLedger::updateOrCreate(
                ['distributor_id' => $request->distributor_id],
                [
                    'distributor_id' => $request->distributor_id,
                    'admin_or_user_id' => $userId,
                    'previous_balance' => $newPreviousBalance,
                    'closing_balance' => $newClosingBalance,
                    'updated_at'        => Carbon::now(),
                ]
            );

            return redirect()->back()->with('success', 'Sale recorded successfully and stock updated.');
        } else {
            return redirect()->back();
        }
    }




    public function all_sale()
    {
        if (Auth::id()) {
            $userId = Auth::id();
            $Sales = Sale::where('admin_or_user_id', $userId)->with('distributor')->get();
            // dd($Sales);
            return view('admin_panel.sale.all_sale', compact('Sales'));
        } else {
            return redirect()->back();
        }
    }

    public function show_sale($id)
    {
        if (Auth::id()) {
            $sale = Sale::findOrFail($id);

            return view('admin_panel.sale.show_sale', compact('sale'));
        } else {
            return redirect()->back();
        }
    }

    public function saleInvoice($id)
    {
        $sale = Sale::with('distributor')->findOrFail($id);
        return view('admin_panel.sale.invoice', compact('sale'));
    }
}
