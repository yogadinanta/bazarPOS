<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PosController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\VoucherController;


Route::get('/', function () {
    return view('home');
});

Route::get('/pos', [PosController::class, 'index'])
    ->name('pos.index');


// ================= ADMIN =================

Route::prefix('admin')->group(function () {

    Route::resource('categories', CategoryController::class);

    Route::resource('products', ProductController::class);
    Route::resource('admin/products', ProductController::class)
    ->names('admin.products');
});


Route::prefix('admin')->name('admin.')->group(function () {

    Route::resource('products', ProductController::class);

});

Route::prefix('admin')->name('admin.')->group(function () {

    Route::resource('vouchers', VoucherController::class);

});

// Ganti PosController.php menjadi PosController::class
Route::post('/admin/pos/store', [PosController::class, 'store'])->name('pos.store');

Route::post('/admin/pos/store', [PosController::class, 'store'])->name('pos.store');
Route::get('/admin/pos/nota/{id}', [PosController::class, 'nota'])->name('pos.nota');

// Tambahkan baris route baru ini
Route::get('/admin/pos/history', [PosController::class, 'history'])->name('pos.history');