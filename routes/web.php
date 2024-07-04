<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{ProductController,CustomerController};
use App\Models\Product;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $products = Product::get();
    if(auth()->user()->role == 'admin'){
        return view('admin.dashboard');
    }
    return view('dashboard',compact('products'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::group(['middleware'=>['auth','role:admin']],function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('product', ProductController::class);
    Route::delete('/destroyfile', [ProductController::class,'destroyfile'])->name('product.destroyfile');
    Route::resource('customer', CustomerController::class);
    Route::post('/import', [CustomerController::class,'import'])->name('customer.import');
});

Route::group(['middleware'=>['auth','role:customer']],function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
