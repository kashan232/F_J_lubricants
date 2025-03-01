<?php

use App\Http\Controllers\Business_tpyeController;
use App\Http\Controllers\CategoryAndSubCategoryController;
use App\Http\Controllers\CityAndAreaController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DistributorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SalesmanController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\VendorController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// connected
Route::get('/home', [HomeController::class, 'index'])->middleware('auth')->name('home');

// Route::get('/adminpage', [HomeController::class, 'adminpage'])->middleware(['auth','admin'])->name('adminpage');

//city
Route::get('/city', [CityAndAreaController::class, 'city'])->middleware(['auth','admin'])->name('city');
Route::post('/store-city', [CityAndAreaController::class, 'store_city'])->name('store-city');
Route::post('/city/update', [CityAndAreaController::class, 'update'])->name('city.update');

Route::get('/Area', [CityAndAreaController::class, 'Area'])->middleware(['auth','admin'])->name('Area');
Route::post('/store-Area', [CityAndAreaController::class, 'store_Area'])->name('store-Area');
Route::post('/Area/update', [CityAndAreaController::class, 'update_area'])->name('Area.update');



Route::get('/Distributor', [DistributorController::class, 'Distributor'])->middleware(['auth','admin'])->name('Distributor');
Route::post('/store-Distributor', [DistributorController::class, 'store_Distributor'])->name('store-Distributor');
Route::put('/Distributor/update/{id}', [DistributorController::class, 'update_Distributor'])->name('Distributor.update');

// Route::post('/Distributor/update', [DistributorController::class, 'update_Distributor'])->name('Distributor.update');
Route::get('/Distributor', [DistributorController::class, 'Distributor'])->middleware(['auth','admin'])->name('Distributor');
Route::get('/get-areas', [DistributorController::class, 'get_areas'])->middleware(['auth','admin'])->name('get-areas');
Route::get('/Distributor-ledger', [DistributorController::class, 'Distributor_ledger'])->middleware(['auth','admin'])->name('Distributor-ledger');
Route::post('/recovery-store', [DistributorController::class, 'recovery_store'])->name('recovery-store');
Route::get('/Distributor-recovery', [DistributorController::class, 'Distributor_recovery'])->middleware(['auth','admin'])->name('Distributor-recovery');


Route::get('/category', [CategoryAndSubCategoryController::class, 'category'])->middleware(['auth','admin'])->name('category');
Route::post('/store-category', [CategoryAndSubCategoryController::class, 'store_category'])->name('store-category');
Route::post('/category/update', [CategoryAndSubCategoryController::class, 'update_category'])->name('category.update');

Route::get('/sub-category', [CategoryAndSubCategoryController::class, 'sub_category'])->middleware(['auth','admin'])->name('sub-category');
Route::post('/store-sub-category', [CategoryAndSubCategoryController::class, 'store_sub_category'])->name('store-sub-category');
Route::post('/sub-category/update', [CategoryAndSubCategoryController::class, 'update_sub_category'])->name('sub-category.update');

//size
Route::get('/size', [SizeController::class, 'size'])->middleware(['auth','admin'])->name('size');
Route::post('/store-size', [SizeController::class, 'store_size'])->name('store-size');
Route::post('/size/update', [SizeController::class, 'update'])->name('size.update');
//business_tpye
Route::get('/business-type', [Business_tpyeController::class, 'index'])->middleware(['auth','admin'])->name('business_type');
Route::post('/business-type/store', [Business_tpyeController::class, 'store'])->name('business_type.store');
Route::post('/business-type/update', [Business_tpyeController::class, 'update'])->name('business_type.update');


//expense
Route::get('/expense', [ExpenseController::class, 'expense'])->middleware(['auth','admin'])->name('expense');
Route::post('/store-expense-category', [ExpenseController::class, 'store_expense_category'])->name('store-expense-category');
Route::post('/expense/update', [ExpenseController::class, 'update'])->name('expense.update');
Route::delete('/delete-expense/{id}', [ExpenseController::class, 'delete_Add_ExpenseBtn'])->name('delete-expense');

// Expense Management Routes
Route::get('/expenses', [ExpenseController::class, 'expense'])->name('expenses.index'); // Expense list page
Route::get('/add-expenses', [ExpenseController::class, 'addExpenseScreen'])->name('add-expenses'); // Add expense screen
Route::post('/store-expense', [ExpenseController::class, 'store_addexpense'])->name('store-expense'); // Store new expense
Route::post('/update-expense', [ExpenseController::class, 'update_addexpense'])->name('update-expense'); // Update existing expense
Route::delete('/delete-expense/{id}', [ExpenseController::class, 'delete_add_expense'])->name('deleteAddExpenseBtn');







//Product
Route::get('/product', [ProductController::class, 'product'])->middleware(['auth','admin'])->name('product');
Route::post('/store-product', [ProductController::class, 'store_product'])->name('store-product');
Route::put('/product/update/{id}', [ProductController::class, 'update'])->name('product.update');
Route::get('/fetch-subcategories', [ProductController::class, 'fetchSubCategories'])->name('fetch-subcategories');



// purchase 
Route::get('/Purchase', [PurchaseController::class, 'Purchase'])->middleware(['auth','admin'])->name('Purchase');
Route::get('/get-subcategories/{categoryname}', [PurchaseController::class, 'getSubcategories'])->name('get.subcategories');
Route::get('/get-items', [PurchaseController::class, 'getItems'])->name('get.items');
Route::post('/store-Purchase', [PurchaseController::class, 'store_Purchase'])->name('store-Purchase');
Route::get('/all-Purchases', [PurchaseController::class, 'all_Purchases'])->middleware(['auth','admin'])->name('all-Purchases');
Route::get('/purchase/invoice/{id}', [PurchaseController::class, 'purchaseInvoice'])->name('purchase.invoice');

Route::get('/add-sale', [SaleController::class, 'add_sale'])->middleware(['auth','admin'])->name('add-sale');
Route::post('/store-sale', [SaleController::class, 'store_sale'])->name('store-sale');
Route::get('/all-sale', [SaleController::class, 'all_sale'])->middleware(['auth','admin'])->name('all-sale');
Route::get('/sale/{id}', [SaleController::class, 'show_sale'])->name('show_sale');
Route::get('/sale/invoice/{id}', [SaleController::class, 'saleInvoice'])->name('sale.invoice');

// sales men

// Salesmen Routes
Route::get('/salesmen', [SalesmanController::class, 'salesmen'])->middleware(['auth', 'admin'])->name('salesmen'); // Displays Salesmen List
Route::post('/store-salesman', [SalesmanController::class, 'store_salesman'])->name('store-salesman'); // Store a new Salesman
Route::post('/salesman/update', [SalesmanController::class, 'update_salesman'])->name('update-salesman'); // Update existing Salesman
Route::get('/fetch-cities', [SalesmanController::class, 'fetchCities'])->name('fetch-cities'); // Fetch list of cities (adjust method to actual logic)
Route::post('/salesman/toggle-status', [SalesmanController::class, 'toggleStatus'])->name('toggle-salesman-status');
Route::get('/fetch-areas', [CustomerController::class, 'fetchAreas'])->name('fetch-areas');
Route::get('/fetch-designation', [CustomerController::class, 'fetchdesignation'])->name('fetch-designation');


// designation
Route::get('/designation', [SalesmanController::class, 'designation'])->name('designation');
Route::post('/store-designation', [SalesmanController::class, 'store_designation'])->name('designation.store');
Route::post('/designation/update', [SalesmanController::class, 'update_designation'])->name('designation.update');
Route::delete('/designation/delete/{id}', [SalesmanController::class, 'destroy'])->name('designation.delete');

Route::get('/vendors', [VendorController::class, 'vendors'])->middleware(['auth','admin'])->name('vendors');
Route::post('/store-vendors', [VendorController::class, 'store_vendors'])->name('store-vendors');
Route::put('/vendors/update/{id}', [VendorController::class, 'update_vendors'])->name('vendors.update');


//Cutomer create 
Route::get('/customer', [CustomerController::class, 'index'])->name('customer');
    Route::post('/customer/store', [CustomerController::class, 'store'])->name('customer.store');
    Route::post('/customer/update', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customer/delete/{id}', [CustomerController::class, 'destroy'])->name('delete-customer');
    Route::get('/fetch-business-types', [CustomerController::class, 'fetchBusinessTypes'])->name('fetch-business-types');
    Route::get('/fetch-areas', [CustomerController::class, 'fetchAreas'])->name('fetch-areas');
   
   
   
    Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
