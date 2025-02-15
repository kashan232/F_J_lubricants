<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Size;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function product()
    {
        if (Auth::id()) {
            $userId = Auth::id();
            $products = Product::where('admin_or_user_id', $userId)->get();
            $categories = Category::all();
            $sizes = Size::all(); // Size table se sab sizes le rahe hain
    
            return view('admin_panel.product.add_product', compact('products', 'categories', 'sizes'));
        } else {
            return redirect()->back();
        }
    }
    


 

    public function fetchSubCategories(Request $request)
    {
        $subCategories = SubCategory::where('category_name', $request->category_id)->get();
        return response()->json($subCategories);
    }


    public function store_product(Request $request)
    {
        if (Auth::id()) {
            $userId = Auth::id();
            Product::create([
                'admin_or_user_id' => $userId,
                'category' => $request->category,
                'sub_category' => $request->sub_category,
                'item_code' => $request->item_code,
                'item_name' => $request->item_name,
                'size' => $request->size,
                'pcs' => $request->pcs,
                'wholesale_price' => $request->wholesale_price,
                'retail_price' => $request->retail_price,
                'initial_stock' => $request->initial_stock,
                'alert_quantity' => $request->alert_quantity,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            return redirect()->back()->with('success', 'Product created successfully');
        } else {
            return redirect()->back();
        }
    }
    
    public function update(Request $request)
    {
        $product_id = $request->input('product_id');
        Product::where('id', $product_id)->update([
            'category' => $request->category,
            'sub_category' => $request->sub_category,
            'item_code' => $request->item_code,
            'item_name' => $request->item_name,
            'size' => Size::where('id', $request->size_id)->value('size_name'),
            'pcs' => $request->pcs,
            'wholesale_price' => $request->wholesale_price,
            'retail_price' => $request->retail_price,
            'initial_stock' => $request->initial_stock,
            'alert_quantity' => $request->alert_quantity,
        ]);
        return redirect()->back()->with('success', 'Product updated successfully');
    }
    
   


    
}
