<?php

use App\Http\Controllers\Keuangan\BankController;
use App\Http\Controllers\Keuangan\CategoryController;
use App\Http\Controllers\Keuangan\HutangController;
use App\Http\Controllers\Keuangan\KeuanganController;
use App\Http\Controllers\Keuangan\LaporanController;
use App\Http\Controllers\Keuangan\PiutangController;
use App\Http\Controllers\Keuangan\TransaksiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something
*/

Route::group(['middleware' => ['auth']], function () {
    Route::prefix('keuangan')->group(function () {
        Route::get("/dashboard", [KeuanganController::class, 'dashboard'])->name("keuangan");

        // Kategori
        Route::get("/categories", [CategoryController::class, 'index'])->name("category.index");
        Route::post("/categories", [CategoryController::class, 'store'])->name("category.store");
        Route::put("/categories/{category:id}", [CategoryController::class, 'update'])->name("category.update");

        // Bank
        Route::get("/banks", [BankController::class, 'index'])->name("bank.index");
        Route::post("/banks", [BankController::class, 'store'])->name("bank.store");
        Route::put("/banks/{banks:id}", [BankController::class, 'update'])->name("bank.update");

        // Transaksi
        Route::get("/transaksi", [TransaksiController::class, 'index'])->name("transaksi.index");
        Route::post("/transaksi", [TransaksiController::class, 'store'])->name("transaksi.store");
        Route::put("/transaksi/{transaksi:id}", [TransaksiController::class, 'update'])->name("transaksi.update");

        // Hutang
        Route::get("/hutang", [HutangController::class, 'index'])->name("hutang.index");
        Route::post("/hutang", [HutangController::class, 'store'])->name("hutang.store");
        Route::put("/hutang/{hutang:id}", [HutangController::class, 'update'])->name("hutang.update");

        // Piutang
        Route::get("/piutang", [PiutangController::class, 'index'])->name("piutang.index");
        Route::post("/piutang", [PiutangController::class, 'store'])->name("piutang.store");
        Route::put("/piutang/{piutang:id}", [PiutangController::class, 'update'])->name("piutang.update");

        // Laporan Perkategori
        Route::get("/laporan-perkategori", [LaporanController::class, 'perkategori'])->name("laporan-perkategori.index");
        Route::post("/laporan-perkategori", [LaporanController::class, 'perkategori'])->name("laporan-perkategori.store");

        // Laporan Perbulan
        Route::get("/laporan-perbulan", [LaporanController::class, 'perbulan'])->name("laporan-perbulan.index");
        Route::post("/laporan-perbulan", [LaporanController::class, 'perbulan'])->name("laporan-perbulan.store");
    });
});
