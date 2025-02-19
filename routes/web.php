<?php

use App\Http\Controllers\CategoryAndSubCategoryController;
use App\Http\Controllers\CityAndAreaController;
use App\Http\Controllers\DistributorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SalesmanController;
use App\Http\Controllers\ExpenseController;
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


//expense
Route::get('/expense', [ExpenseController::class, 'expense'])->middleware(['auth','admin'])->name('expense');
Route::post('/store-expense-category', [ExpenseController::class, 'store_expense_category'])->name('store-expense-category');
Route::post('/expense/update', [ExpenseController::class, 'update'])->name('expense.update');
Route::post('/expense/delete', [ExpenseController::class, 'delete'])->name('expense.delete');
Route::delete('/delete-expense/{id}', [ExpenseController::class, 'delete_add_expense'])->name('delete-expense');

// deleteAddExpenseBtn

// Expense Management Routes
Route::get('/expenses', [ExpenseController::class, 'expense'])->name('expenses.index'); // Expense list page
Route::get('/add-expenses', [ExpenseController::class, 'addExpenseScreen'])->name('add-expenses'); // Add expense screen
Route::post('/store-expense', [ExpenseController::class, 'store_addexpense'])->name('store-expense'); // Store new expense
Route::post('/update-expense', [ExpenseController::class, 'update_addexpense'])->name('update-expense'); // Update existing expense
Route::delete('/delete-expense/{id}', [ExpenseController::class, 'delete_add_expense'])->name('delete-expense');







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


// sales men

// Salesmen Routes
Route::get('/salesmen', [SalesmanController::class, 'salesmen'])->middleware(['auth', 'admin'])->name('salesmen'); // Displays Salesmen List
Route::post('/store-salesman', [SalesmanController::class, 'store_salesman'])->name('store-salesman'); // Store a new Salesman
Route::post('/salesman/update', [SalesmanController::class, 'update_salesman'])->name('update-salesman'); // Update existing Salesman
Route::get('/fetch-cities', [SalesmanController::class, 'fetchCities'])->name('fetch-cities'); // Fetch list of cities (adjust method to actual logic)
Route::get('/fetch-areas', [SalesmanController::class, 'fetchAreas'])->name('fetch-areas');
Route::post('/salesman/toggle-status', [SalesmanController::class, 'toggleStatus'])->name('toggle-salesman-status');



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
