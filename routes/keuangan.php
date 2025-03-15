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
        Route::get("/dashboard", [KeuanganController::class, 'dashboard'])
            ->name("keuangan")
            ->middleware('can:view keuangan dashboard');

        // Kategori
        Route::get("/categories", [CategoryController::class, 'index'])
            ->name("category.index")
            ->middleware('can:view keuangan data kategori');
        Route::post("/categories", [CategoryController::class, 'store'])
            ->name("category.store");
        // ->middleware('can:tambah keuangan data kategori');
        Route::put("/categories/{category:id}", [CategoryController::class, 'update'])
            ->name("category.update");
        // ->middleware('can:edit keuangan data kategori');

        // Bank
        Route::get("/banks", [BankController::class, 'index'])
            ->name("bank.index")
            ->middleware('can:view keuangan data rekening');
        Route::post("/banks", [BankController::class, 'store'])
            ->name("bank.store");
        // ->middleware('can:tambah keuangan data rekening');
        Route::put("/banks/{banks:id}", [BankController::class, 'update'])
            ->name("bank.update");
        // ->middleware('can:edit keuangan data rekening');

        // Transaksi
        Route::get("/transaksi", [TransaksiController::class, 'index'])
            ->name("transaksi.index")
            ->middleware('can:view keuangan transaksi');
        Route::post("/transaksi", [TransaksiController::class, 'store'])
            ->name("transaksi.store");
        // ->middleware('can:tambah keuangan transaksi');
        Route::put("/transaksi/{transaksi:id}", [TransaksiController::class, 'update'])
            ->name("transaksi.update");
        // ->middleware('can:edit keuangan transaksi');

        // Hutang
        Route::get("/hutang", [HutangController::class, 'index'])
            ->name("hutang.index")
            ->middleware('can:view keuangan hutang');
        Route::post("/hutang", [HutangController::class, 'store'])
            ->name("hutang.store");
        // ->middleware('can:tambah keuangan hutang');
        Route::put("/hutang/{hutang:id}", [HutangController::class, 'update'])
            ->name("hutang.update");
        // ->middleware('can:edit keuangan hutang');

        // Piutang
        Route::get("/piutang", [PiutangController::class, 'index'])
            ->name("piutang.index")
            ->middleware('can:view keuangan piutang');
        Route::post("/piutang", [PiutangController::class, 'store'])
            ->name("piutang.store");
        // ->middleware('can:tambah keuangan piutang');
        Route::put("/piutang/{piutang:id}", [PiutangController::class, 'update'])
            ->name("piutang.update");
        // ->middleware('can:edit keuangan piutang');

        // Laporan Perkategori
        Route::get("/laporan-perkategori", [LaporanController::class, 'perkategori'])
            ->name("laporan-perkategori.index")
            ->middleware('can:view keuangan laporan perkategori');
        Route::post("/laporan-perkategori", [LaporanController::class, 'perkategori'])
            ->name("laporan-perkategori.store");
        // ->middleware('can:tambah keuangan laporan perkategori');

        // Laporan Perbulan
        Route::get("/laporan-perbulan", [LaporanController::class, 'perbulan'])
            ->name("laporan-perbulan.index")
            ->middleware('can:view keuangan laporan perbulan');
        Route::post("/laporan-perbulan", [LaporanController::class, 'perbulan'])
            ->name("laporan-perbulan.store");
        // ->middleware('can:tambah keuangan laporan perbulan');
    });
});
