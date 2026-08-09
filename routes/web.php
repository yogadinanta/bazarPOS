<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PosController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Admin\AdminHistoryController;


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


// Route POS Kasir
Route::get('/', [PosController::class, 'index']);
Route::post('/admin/pos/store', [PosController::class, 'store'])->name('pos.store');
Route::get('/admin/pos/nota/{id}', [PosController::class, 'nota']);
Route::get('/admin/pos/history', [PosController::class, 'history']); // Untuk JSON modal POS

// Route Riwayat Transaksi Admin (Controller Terpisah)
Route::get('/admin/history', [AdminHistoryController::class, 'index']);

// Route POS Kasir
Route::get('/', [PosController::class, 'index']);
Route::post('/admin/pos/store', [PosController::class, 'store'])->name('pos.store');
Route::get('/admin/pos/nota/{id}', [PosController::class, 'nota']);

// Tambahkan ->name('pos.history') di sini agar dikenali oleh script POS
Route::get('/admin/pos/history', [PosController::class, 'history'])->name('pos.history');

// Route Admin Riwayat Transaksi (Controller Terpisah)
Route::get('/admin/history', [AdminHistoryController::class, 'index']);