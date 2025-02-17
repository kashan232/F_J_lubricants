<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
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

        $items = Product::where('category', $request->category_name)->where('sub_category', $request->sub_category_name)->pluck('item_name', 'id','pcs');

        return response()->json($items);
    }
}
