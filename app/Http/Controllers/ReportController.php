<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\CustomerRecovery;
use App\Models\Distributor;
use App\Models\LocalSale;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Recovery;
use App\Models\Sale;
use App\Models\Vendor;
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
        $startDate = $request->input('start_date'); // User selected Start Date
        $endDate = $request->input('end_date'); // User selected End Date
        // Get distributor ledger record
        $ledger = DB::table('distributor_ledgers')
            ->where('distributor_id', $distributorId)
            ->select('opening_balance', 'previous_balance', 'closing_balance')
            ->first();

        // Filter Recoveries in Date Range
        $recoveries = DB::table('recoveries')
            ->where('distributor_ledger_id', $distributorId)
            ->whereBetween('date', [$startDate, $endDate]) // ✅ Date Range Apply
            ->select('id', 'amount_paid', 'salesman', 'date')
            ->get();

        // Filter Sales in Date Range
        $sales = DB::table('sales')
            ->where('distributor_id', $distributorId)
            ->whereBetween('Date', [$startDate, $endDate]) // ✅ Date Range Apply
            ->select('invoice_number', 'Date', 'Booker', 'Saleman', 'grand_total', 'discount_value', 'scheme_value', 'net_amount')
            ->get();

        // Fetch sale returns
        $saleReturns = DB::table('sale_returns')
            ->where('sale_type', 'distributor')
            ->where('party_id', $distributorId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('invoice_number', 'created_at', 'total_return_amount')
            ->get();

        return response()->json([
            'opening_balance' => $ledger->opening_balance ?? 0,
            'previous_balance' => $ledger->previous_balance ?? 0,
            'closing_balance' => $ledger->closing_balance ?? 0,
            'recoveries' => $recoveries,
            'sales' => $sales,
            'sale_returns' => $saleReturns,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    public function vendor_Ledger_Record()
    {
        if (Auth::id()) {
            $userId = Auth::id();
            $Vendors = Vendor::where('admin_or_user_id', $userId)->get(); // Adjust according to your database structure
            return view('admin_panel.reports.vendor_ledger_record', [
                'Vendors' => $Vendors,
            ]);
        } else {
            return redirect()->back();
        }
    }

    public function fetchvendorLedger(Request $request)
    {
        $vendorId = $request->input('Vendor_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Ledger record including balances
        $ledger = DB::table('vendor_ledgers')
            ->where('vendor_id', $vendorId)
            ->select('opening_balance', 'previous_balance', 'closing_balance')
            ->first();

        $opening_balance = $ledger->opening_balance ?? 0;
        $previous_balance = $ledger->previous_balance ?? 0;
        $closing_balance = $ledger->closing_balance ?? 0;

        // ✅ Fetch Purchases
        $purchases = DB::table('purchases')
            ->where('party_name', $vendorId)
            ->whereBetween('purchase_date', [$startDate, $endDate])
            ->select('id', 'invoice_number', 'purchase_date', 'grand_total')
            ->get()
            ->map(function ($purchase) {
                return [
                    'invoice_number' => $purchase->invoice_number,
                    'date' => $purchase->purchase_date,
                    'grand_total' => $purchase->grand_total,
                    'net_amount' => $purchase->grand_total, // assuming no discounts/scheme
                    'salesman' => null, // optional
                ];
            });

        // ✅ Fetch Returns
        $returnsRaw = DB::table('purchase_returns')
            ->where('party_name', $vendorId)
            ->whereBetween('return_date', [$startDate, $endDate])
            ->select('id', 'invoice_number','purchase_id', 'return_date', 'return_amount')
            ->get();

        $returns = [];
        foreach ($returnsRaw as $return) {
            $amountArray = json_decode($return->return_amount, true);
            $amountSum = collect($amountArray)->sum();

            $returns[] = [
                'id' => $return->id,
                'invoice_number' => $return->invoice_number,
                'date' => $return->return_date,
                'net_amount' => $amountSum,
            ];
        }

        return response()->json([
            'opening_balance' => $opening_balance,
            'previous_balance' => $previous_balance,
            'closing_balance' => $closing_balance,
            'purchases' => $purchases,
            'returns' => $returns,
            'startDate' => $startDate,
            'endDate' => $endDate,
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
        $startDate = $request->input('start_date'); // User selected Start Date
        $endDate = $request->input('end_date');

        // Get ledger record
        $ledger = DB::table('customer_ledgers')
            ->where('customer_id', $CustomerId)
            ->select('opening_balance', 'previous_balance', 'closing_balance')
            ->first();

        // Get recoveries
        $recoveries = DB::table('customer_recoveries')
            ->where('customer_ledger_id', $CustomerId)
            ->whereBetween('date', [$startDate, $endDate])
            ->select('id', 'amount_paid', 'salesman', 'date')
            ->get();


        // Get Local Sales
        $localSales = DB::table('local_sales')
            ->where('customer_id', $CustomerId)
            ->whereBetween('Date', [$startDate, $endDate])
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


        $saleReturns = DB::table('sale_returns')
            ->where('sale_type', 'customer')
            ->where('party_id', $CustomerId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('invoice_number', 'total_return_amount', 'created_at')
            ->get();

        return response()->json([
            'opening_balance' => $ledger->opening_balance ?? 0,
            'previous_balance' => $ledger->previous_balance ?? 0,
            'closing_balance' => $ledger->closing_balance ?? 0,
            'recoveries' => $recoveries,
            'local_sales' => $localSales, // Local Sales Data
            'sale_returns' => $saleReturns,
            'startDate' => $startDate, // Local Sales Data
            'endDate' => $endDate, // Local Sales Data
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

            // 4️⃣ **Total Purchase Return Quantity**
            $returnData = DB::table('purchase_returns')->whereJsonContains('item', $item->item_name)->get();
            $totalPurchaseReturnQty = 0;

            foreach ($returnData as $return) {
                $itemNames = json_decode($return->item, true);
                $returnQtyArray = json_decode($return->return_qty, true);

                if (is_array($itemNames) && is_array($returnQtyArray)) {
                    foreach ($itemNames as $index => $returnItem) {
                        if ($returnItem === $item->item_name) {
                            $totalPurchaseReturnQty += isset($returnQtyArray[$index]) ? intval($returnQtyArray[$index]) : 0;
                        }
                    }
                }
            }


            // ✅ Assign the Correct Values (Separate Counts)
            $item->total_purchased = $totalPurchasedQty;
            $item->total_purchase_return = $totalPurchaseReturnQty; // New Line
            $item->total_distributor_sold = $totalDistributorSoldQty;
            $item->total_local_sold = $totalLocalSoldQty;
        }

        return response()->json($items);
    }

    public function date_wise_recovery_report()
    {
        if (Auth::id()) {
            $userId = Auth::id();
            $Customers = Customer::where('admin_or_user_id', $userId)->get(); // Adjust according to your database structure
            return view('admin_panel.reports.date_wise_recovery_report', [
                'Customers' => $Customers,
            ]);
        } else {
            return redirect()->back();
        }
    }

    public function getRecoveryReport(Request $request)
    {
        $type = $request->type;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $recoveries = [];

        if ($type == 'all' || $type == 'distributor') {
            $distributorRecoveries = Recovery::whereBetween('date', [$startDate, $endDate])->get();
            foreach ($distributorRecoveries as $recovery) {
                $distributor = Distributor::find($recovery->distributor_ledger_id);
                $recoveries[] = [
                    'date' => $recovery->date,
                    'party_name' => $distributor->Customer ?? 'N/A',
                    'area' => $distributor->Area ?? 'N/A',
                    'remarks' => $recovery->remarks,
                    'amount_paid' => number_format($recovery->amount_paid)
                ];
            }
        }

        if ($type == 'all' || $type == 'customer') {
            $customerRecoveries = CustomerRecovery::whereBetween('date', [$startDate, $endDate])->get();
            foreach ($customerRecoveries as $recovery) {
                $customer = Customer::find($recovery->customer_ledger_id);
                $recoveries[] = [
                    'date' => $recovery->date,
                    'party_name' => $customer->customer_name ?? 'N/A',
                    'area' => $customer->area ?? 'N/A',
                    'remarks' => $recovery->remarks,
                    'amount_paid' => number_format($recovery->amount_paid)
                ];
            }
        }

        return response()->json($recoveries);
    }
}
