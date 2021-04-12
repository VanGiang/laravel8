<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;

// Admin route
use App\Http\Controllers\Admins\AdminController;
use App\Http\Controllers\Admins\ProductController as AdminProductController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $user = Auth::user();

    return view('homepage', ['user' => $user]);
});

Route::get('/php2008', function () {
    return view('git_tutorial');
});
Route::get('/test', function () {
    return view('test.greeting', ['name' => 'Giang']);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::middleware(['auth', 'checkAdmin'])->group(function () {
    Route::get('/products/create', [ProductController::class, 'create']);
    Route::post('/products', [ProductController::class, 'store']);
});

Route::resource('products', ProductController::class)->except([
    'create', 'store'
]);

Route::middleware(['auth', 'checkAdmin'])->group(function () {
    Route::post('/orders/checkout', [OrderController::class, 'checkout']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::delete('/orders/{product_order_id}', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::put('/orders/{product_order_id}', [OrderController::class, 'update'])->name('orders.update');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/products/create', [AdminProductController::class, 'create'])->name('admin.products.create');
    Route::post('/admin/products', [AdminProductController::class, 'store'])->name('admin.products');
});
