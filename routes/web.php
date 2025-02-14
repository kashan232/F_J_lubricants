<?php

use App\Http\Controllers\CategoryAndSubCategoryController;
use App\Http\Controllers\CityAndAreaController;
use App\Http\Controllers\DistributorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\ProductController;
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
Route::post('/Distributor/update', [DistributorController::class, 'update_Distributor'])->name('Distributor.update');
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

//Product
Route::get('/product', [ProductController::class, 'product'])->middleware(['auth','admin'])->name('product');
Route::post('/store-product', [ProductController::class, 'store_product'])->name('store-product');
Route::post('/product/update', [ProductController::class, 'update'])->name('product.update');
Route::get('/fetch-subcategories', [ProductController::class, 'fetchSubCategories'])->name('fetch-subcategories');



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
