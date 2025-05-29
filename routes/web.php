<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StockController;
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

// Halaman utama, redirect ke daftar pembelian sebagai default
Route::get('/', function () {
    return redirect()->route('purchases.index');
});

// Manajemen Barang (Untuk menambahkan barang baru sebelum digunakan di transaksi)
Route::resource('items', ItemController::class)->only(['index', 'create', 'store']);

// Form Pembuatan Stok Baru 
Route::get('/stocks/create', [StockController::class, 'create'])->name('stocks.create');
Route::post('/stocks', [StockController::class, 'store'])->name('stocks.store');

// Pembelian Stok 
Route::get('/purchases/create', [PurchaseController::class, 'create'])->name('purchases.create');
Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');
Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index'); 

// Penjualan 
Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
Route::get('/sales', [SaleController::class, 'index'])->name('sales.index'); 

// Laporan 
Route::get('/reports/monthly-profit', [ReportController::class, 'monthlyProfit'])->name('reports.monthly_profit');
