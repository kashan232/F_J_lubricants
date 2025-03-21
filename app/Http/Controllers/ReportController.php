<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Distributor;
use App\Models\LocalSale;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function Distributor_Ledger_Record()
    {
        if (Auth::id()) {
            $userId = Auth::id();
            $Distributors = Distributor::where('admin_or_user_id', $userId)->get(); // Adjust according to your database structure
            return view('admin_panel.reports.distributor_ledger_record', [
                'Distributors' => $Distributors,
            ]);
        } else {
            return redirect()->back();
        }
    }

    public function fetchDistributorLedger(Request $request)
    {
        $distributorId = $request->input('distributor_id');

        // Get distributor ledger record
        $ledger = DB::table('distributor_ledgers')
            ->where('distributor_id', $distributorId)
            ->select('previous_balance', 'closing_balance')
            ->first();

        // Get recoveries
        $recoveries = DB::table('recoveries')
            ->where('distributor_ledger_id', $distributorId)
            ->select('id', 'amount_paid', 'salesman', 'date')
            ->get();

        // Get sales data for distributor
        $sales = DB::table('sales')
            ->where('distributor_id', $distributorId)
            ->select('invoice_number', 'Date', 'Booker', 'Saleman', 'grand_total', 'discount_value', 'scheme_value', 'net_amount')
            ->get();

        return response()->json([
            'previous_balance' => $ledger->previous_balance ?? 0,
            'closing_balance' => $ledger->closing_balance ?? 0,
            'recoveries' => $recoveries,
            'sales' => $sales,
        ]);
    }


    public function Customer_Ledger_Record()
    {
        if (Auth::id()) {
            $userId = Auth::id();
            $Customers = Customer::where('admin_or_user_id', $userId)->get(); // Adjust according to your database structure
            return view('admin_panel.reports.customer_ledger_record', [
                'Customers' => $Customers,
            ]);
        } else {
            return redirect()->back();
        }
    }


    public function fetchCustomerledger(Request $request)
    {
        $CustomerId = $request->input('Customer_id');

        // Get ledger record
        $ledger = DB::table('customer_ledgers')
            ->where('customer_id', $CustomerId)
            ->select('previous_balance', 'closing_balance')
            ->first();

        // Get recoveries
        $recoveries = DB::table('customer_recoveries')
            ->where('customer_ledger_id', $CustomerId)
            ->select('id', 'amount_paid', 'salesman', 'date')
            ->get();

        // Get Local Sales
        $localSales = DB::table('local_sales')
            ->where('customer_id', $CustomerId)
            ->select(
                'invoice_number',
                'Date',
                'customer_shopname',
                'grand_total',
                'discount_value',
                'scheme_value',
                'net_amount',
                'Saleman'
            )
            ->get();

        return response()->json([
            'previous_balance' => $ledger->previous_balance ?? 0,
            'closing_balance' => $ledger->closing_balance ?? 0,
            'recoveries' => $recoveries,
            'local_sales' => $localSales, // Local Sales Data
        ]);
    }


    public function stock_Record()
    {
        if (Auth::id()) {
            $userId = Auth::id();
            $categories = Category::where('admin_or_user_id', $userId)->get();
            return view('admin_panel.reports.stock_Record', [
                'categories' => $categories,
            ]);
        } else {
            return redirect()->back();
        }
    }

    public function getItems($subcategory)
    {
        $items = Product::where('sub_category', $subcategory)->select('item_code', 'item_name')->get();
        return response()->json($items);
    }

    public function getItemDetails(Request $request)
    {
        $query = Product::query();

        if ($request->category !== 'all') {
            $query->where('category', $request->category);
        }
        if ($request->subcategory !== 'all') {
            $query->where('sub_category', $request->subcategory);
        }
        if ($request->itemCode !== 'all') {
            $query->where('item_code', $request->itemCode);
        }

        $items = $query->get();

        foreach ($items as $item) {
            // 1️⃣ **Total Purchased Quantity**
            $purchaseData = Purchase::whereJsonContains('item', $item->item_name)->get();
            $totalPurchasedQty = 0;

            foreach ($purchaseData as $purchase) {
                $itemNames = json_decode($purchase->item, true);
                $cartonQtyArray = json_decode($purchase->carton_qty, true);

                if (is_array($itemNames) && is_array($cartonQtyArray)) {
                    foreach ($itemNames as $index => $purchasedItem) {
                        if ($purchasedItem === $item->item_name) {
                            $totalPurchasedQty += isset($cartonQtyArray[$index]) ? intval($cartonQtyArray[$index]) : 0;
                        }
                    }
                }
            }

            // 2️⃣ **Total Distributor Sale Quantity**
            $salesData = Sale::whereJsonContains('item', $item->item_name)->get();
            $totalDistributorSoldQty = 0;

            foreach ($salesData as $sale) {
                $itemNames = json_decode($sale->item, true);
                $cartonQtyArray = json_decode($sale->carton_qty, true);

                if (is_array($itemNames) && is_array($cartonQtyArray)) {
                    foreach ($itemNames as $index => $soldItem) {
                        if ($soldItem === $item->item_name) {
                            $totalDistributorSoldQty += isset($cartonQtyArray[$index]) ? intval($cartonQtyArray[$index]) : 0;
                        }
                    }
                }
            }

            // 3️⃣ **Total Local Sale Quantity**
            $localSalesData = LocalSale::whereJsonContains('item', $item->item_name)->get();
            $totalLocalSoldQty = 0;

            foreach ($localSalesData as $localSale) {
                $itemNames = json_decode($localSale->item, true);
                $cartonQtyArray = json_decode($localSale->carton_qty, true);

                if (is_array($itemNames) && is_array($cartonQtyArray)) {
                    foreach ($itemNames as $index => $soldItem) {
                        if ($soldItem === $item->item_name) {
                            $totalLocalSoldQty += isset($cartonQtyArray[$index]) ? intval($cartonQtyArray[$index]) : 0;
                        }
                    }
                }
            }

            // ✅ Assign the Correct Values (Separate Counts)
            $item->total_purchased = $totalPurchasedQty;
            $item->total_distributor_sold = $totalDistributorSoldQty;
            $item->total_local_sold = $totalLocalSoldQty;
        }

        return response()->json($items);
    }
}
