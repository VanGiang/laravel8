<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;

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

Route::middleware(['auth'])->group(function () {
    Route::post('/orders', [OrderController::class, 'store']);
});
