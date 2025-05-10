<?php

use App\Http\Controllers\Keuangan\BankController;
use App\Http\Controllers\Keuangan\CategoryController;
use App\Http\Controllers\Keuangan\GroupChartOfAccountController;
use App\Http\Controllers\Keuangan\HutangController;
use App\Http\Controllers\Keuangan\KeuanganController;
use App\Http\Controllers\Keuangan\KonfirmasiAsuransiController;
use App\Http\Controllers\Keuangan\PiutangController;
use App\Http\Controllers\Keuangan\TransaksiController;
use App\Http\Controllers\Keuangan\LPembayaranAsuransiController;
use App\Http\Controllers\Keuangan\PembayaranAsuransiController;
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
        Route::get("/laporan-perkategori", [LPembayaranAsuransiController::class, 'perkategori'])
            ->name("laporan-perkategori.index")
            ->middleware('can:view keuangan laporan perkategori');
        Route::post("/laporan-perkategori", [LPembayaranAsuransiController::class, 'perkategori'])
            ->name("laporan-perkategori.store");
        // ->middleware('can:tambah keuangan laporan perkategori');

        // Laporan Perbulan
        Route::get("/laporan-perbulan", [LPembayaranAsuransiController::class, 'perbulan'])
            ->name("laporan-perbulan.index")
            ->middleware('can:view keuangan laporan perbulan');
        Route::post("/laporan-perbulan", [LPembayaranAsuransiController::class, 'perbulan'])
            ->name("laporan-perbulan.store");
        // ->middleware('can:tambah keuangan laporan perbulan');
        Route::prefix('setup')->group(function () {
            Route::get("/group-chart-of-account", [GroupChartOfAccountController::class, 'index'])
                ->name("group-chart-of-account.index")
                ->middleware('can:view keuangan laporan perbulan');
            Route::post("/group-chart-of-account", [GroupChartOfAccountController::class, 'store'])
                ->name("group-chart-of-account.store");
            // ->middleware('can:tambah keuangan data kategori');
            Route::put("/group-chart-of-account/{chartOfAccount:id}", [GroupChartOfAccountController::class, 'update'])
                ->name("group-chart-of-account.update");
            // ->middleware('can:edit keuangan data kategori');

        });
        Route::prefix('konfirmasi-asuransi')->middleware(['can:view account receivable konfirmasi asuransi'])->group(function () {
            // Basic CRUD
            Route::get('/', [KonfirmasiAsuransiController::class, 'index'])->name('keuangan.konfirmasi-asuransi.index');
            Route::get('/create', [KonfirmasiAsuransiController::class, 'create'])->name('keuangan.konfirmasi-asuransi.create');
            Route::post('/', [KonfirmasiAsuransiController::class, 'store'])->name('keuangan.konfirmasi-asuransi.store');
            Route::delete('/{id}', [KonfirmasiAsuransiController::class, 'destroy'])->name('keuangan.konfirmasi-asuransi.destroy');

            // Search
            Route::get('/search-registration', [KonfirmasiAsuransiController::class, 'searchRegistration'])->name('keuangan.konfirmasi-asuransi.search-registration');
            Route::get('/search-tambah', [KonfirmasiAsuransiController::class, 'searchTambah'])->name('keuangan.konfirmasi-asuransi.search-tambah');

            // Print
            Route::get('/cetak-klaim/{id}', [KonfirmasiAsuransiController::class, 'cetakKlaim'])->name('cetak-klaim');
            Route::get('/cetak-kwitansi/{id}', [KonfirmasiAsuransiController::class, 'cetakKwitansi'])->name('cetak-klaim-kwitansi');
            Route::get('/cetak-rekap', [KonfirmasiAsuransiController::class, 'cetakRekap'])->name('keuangan.konfirmasi-asuransi.print-recap');

            Route::get('/cetak-rekap/{id}', [KonfirmasiAsuransiController::class, 'cetakRekapById'])
                ->name('cetak-rekap');


            // Create Invoice
            Route::post('/create-invoice', [KonfirmasiAsuransiController::class, 'createInvoice'])->name('keuangan.konfirmasi-asuransi.create-invoice');
        });


        Route::prefix('pembayaran-asuransi')->middleware(['can:view account receivable pembayaran asuransi'])->group(function () {
            // Basic CRUD routes
            Route::get('/', [PembayaranAsuransiController::class, 'index'])
                ->name('keuangan.pembayaran-asuransi.index');
            Route::get('/create', [PembayaranAsuransiController::class, 'create'])
                ->name('keuangan.pembayaran-asuransi.create');
            Route::get('/store', [PembayaranAsuransiController::class, 'store'])
                ->name('keuangan.pembayaran-asuransi.store');
            Route::get('/keuangan/pembayaran-asuransi/tagihan', [PembayaranAsuransiController::class, 'getTagihan'])->name('keuangan.pembayaran-asuransi.getTagihan');
            Route::get('/tagihan', [PembayaranAsuransiController::class, 'getTagihan'])->name('keuangan.pembayaran-asuransi.getTagihan');
        });


        // laporan route
        Route::prefix('laporan')->group(function () {
            Route::get('laporan-belum-proses-invoice', [LPembayaranAsuransiController::class, 'belumProsesInvoice'])->name('laporan.l-belum-proses-invoice');
            Route::get('laporan-proses-invoice', [LPembayaranAsuransiController::class, 'prosesInvoice'])->name('laporan.proses_invoice');
            Route::get('laporan-umur-piutang-penjamin', [LPembayaranAsuransiController::class, 'umurPiutangPenjamin'])->name('laporan.umur_piutang_penjamin');
            Route::get('laporan-pembayaran-asuransi', [LPembayaranAsuransiController::class, 'pembayaranAsuransi'])->name('laporan.pembayaran_asuransi');
            Route::get('rekap-pembayaran-asuransi', [LPembayaranAsuransiController::class, 'rekapPembayaranAsuransi'])->name('laporan.rekap_pembayaran_asuransi');
            Route::get('rekap-laporan-piutang-penjamin', [LPembayaranAsuransiController::class, 'rekapLaporanPiutangPenjamin'])->name('laporan.rekap_laporan_piutang_penjamin');

            // print 
            Route::get('laporan-belum-proses-invoice/print', [LPembayaranAsuransiController::class, 'printBelumProsesInvoice'])->name('laporan.l-belum-proses-invoice.print');
            Route::get('laporan-proses-invoice/print', [LPembayaranAsuransiController::class, 'printProsesInvoice'])->name('laporan.l-proses-invoice.print');
            Route::get('/laporan/print-umur-piutang-penjamin', [LPembayaranAsuransiController::class, 'printUmurPiutangPenjamin'])->name('laporan.l-umur-piutang-penjamin.print');
            Route::get('/laporan/print-pembayaran-asuransi', [LPembayaranAsuransiController::class, 'printPembayaranAsuransi'])->name('laporan.l-pembayaran-asuransi.print');
            Route::get('/laporan/print-rekap-pembayaran-asuransi', [LPembayaranAsuransiController::class, 'printRekapPembayaranAsuransi'])->name('laporan.l-rekap-pembayaran-asuransi.print');
            Route::get('/laporan/print-rekap-piutang-penjamin', [LPembayaranAsuransiController::class, 'printRekapPiutangPenjamin'])->name('laporan.l-rekap-piutang-penjamin.print');
        });
    });
});
