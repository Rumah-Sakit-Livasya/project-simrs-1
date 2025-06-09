<?php

use App\Http\Controllers\keuangan\APNonGRController;
use App\Http\Controllers\keuangan\APSupplierController;
use App\Http\Controllers\keuangan\JasaDokterController;
use App\Http\Controllers\Keuangan\BankController;
use App\Http\Controllers\Keuangan\CategoryController;
use App\Http\Controllers\Keuangan\ChartOfAccountController;
use App\Http\Controllers\Keuangan\GroupChartOfAccountController;
use App\Http\Controllers\Keuangan\HutangController;
use App\Http\Controllers\Keuangan\KeuanganController;
use App\Http\Controllers\Keuangan\KonfirmasiAsuransiController;
use App\Http\Controllers\Keuangan\PiutangController;
use App\Http\Controllers\Keuangan\TransaksiController;
use App\Http\Controllers\Keuangan\LPembayaranAsuransiController;
use App\Http\Controllers\keuangan\PembayaranAPSupplierController;
use App\Http\Controllers\Keuangan\PembayaranAsuransiController;
use App\Http\Controllers\Keuangan\PembayaranJasaDokterController;
use App\Http\Controllers\keuangan\ReportAPDokterController;
use App\Http\Controllers\keuangan\ReportAPSupplierController;
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

            Route::get("/chart-of-account/{chartOfAccount:id}", [ChartOfAccountController::class, 'getById'])
                ->name("chart-of-account.get");
            // ->middleware('can:view keuangan laporan perbulan');
            Route::get("/chart-of-account", [ChartOfAccountController::class, 'index'])
                ->name("chart-of-account.index")
                ->middleware('can:view keuangan laporan perbulan');
            Route::post("/chart-of-account", [ChartOfAccountController::class, 'store'])
                ->name("chart-of-account.store");
            // ->middleware('can:tambah keuangan data kategori');
            Route::patch("/chart-of-account/{chartOfAccount:id}", [ChartOfAccountController::class, 'update'])
                ->name("chart-of-account.update");
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

            Route::get('/keuangan/konfirmasi-asuransi/search-create', [KonfirmasiAsuransiController::class, 'searchCreate'])
                ->name('keuangan.konfirmasi-asuransi.search-create');
        });


        Route::prefix('pembayaran-asuransi')->middleware(['can:view account receivable pembayaran asuransi'])->group(function () {
            // Basic CRUD routes
            Route::get('/', [PembayaranAsuransiController::class, 'index'])
                ->name('keuangan.pembayaran-asuransi.index');
            Route::get('/create', [PembayaranAsuransiController::class, 'create'])
                ->name('keuangan.pembayaran-asuransi.create');
            Route::post('/store', [PembayaranAsuransiController::class, 'store'])
                ->name('keuangan.pembayaran-asuransi.store');
            Route::get('/tagihan', [PembayaranAsuransiController::class, 'create'])->name('keuangan.pembayaran-asuransi.get-tagihan');
            Route::delete('/{id}', [PembayaranAsuransiController::class, 'destroy'])
                ->name('keuangan.pembayaran-asuransi.destroy');
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

        Route::prefix('jasa-dokter')->middleware(['can:view account payable jasa dokter'])->group(function () {
            Route::get('/', [JasaDokterController::class, 'index'])
                ->name('keuangan.jasa-dokter.index');
            Route::get('/create', [JasaDokterController::class, 'create'])
                ->name('keuangan.jasa-dokter.create');
            Route::post('/store', [JasaDokterController::class, 'store'])
                ->name('keuangan.jasa-dokter.store');
            Route::get('/edit/{id}', [JasaDokterController::class, 'edit'])
                ->name('keuangan.jasa-dokter.edit');
            Route::post('/update/{id}', [JasaDokterController::class, 'update'])
                ->name('keuangan.jasa-dokter.update');
            Route::get('/export-excel', [JasaDokterController::class, 'exportExcel'])
                ->name('keuangan.jasa-dokter.export-excel');
            Route::post('/store-selected', [JasaDokterController::class, 'storeSelected'])
                ->name('keuangan.jasa-dokter.store-selected');
            Route::post('/cancel-selected', [JasaDokterController::class, 'cancelSelected'])
                ->name('keuangan.jasa-dokter.cancel-selected');
            Route::get('/get-tagihan-data/{tagihanPasienId}', [JasaDokterController::class, 'getTagihanData'])
                ->name('keuangan.jasa-dokter.get-tagihan-data');
            Route::get('/get-tagihan-pasien', [JasaDokterController::class, 'getTagihanPasien'])
                ->name('keuangan.jasa-dokter.get-tagihan-pasien');;
            Route::get('/get-modal-data/{jasaDokterId}', [JasaDokterController::class, 'getModalData'])
                ->name('keuangan.jasa-dokter.get-modal-data');
            Route::get('/edit-popup/{jasaDokter}', [JasaDokterController::class, 'editPopup'])
                ->name('keuangan.jasa-dokter.edit-popup');


            // Route::get('/{jasaDokter}/edit-popup', [JasaDokterController::class, 'editPopup'])
            //     ->name('edit-popup'); // Akan menjadi keuangan.jasa-dokter.edit-popup

            // Route::put('/{jasaDokter}/update-popup', [JasaDokterController::class, 'updatePopup'])
            //     ->name('update-popup');
            Route::put('/{jasaDokter}/update-popup', [JasaDokterController::class, 'updatePopup'])
                ->name('update-popup');


            // Route::get('jasa-dokter/get-modal-data-for-edit/{jasaDokterId}', [JasaDokterController::class, 'getModalDataForEdit'])->name('keuangan.jasa-dokter.get-modal-data-for-edit');
        });
        Route::prefix('pembayaran-jasa-dokter')->middleware(['can:view account payable pembayaran jasa dokter'])->group(function () {
            Route::get('/', [PembayaranJasaDokterController::class, 'index'])
                ->name('keuangan.pembayaran-jasa-dokter.index');
            Route::get('/create', [PembayaranJasaDokterController::class, 'create'])
                ->name('keuangan.pembayaran-jasa-dokter.create');
            Route::post('/store', [PembayaranJasaDokterController::class, 'store'])
                ->name('keuangan.pembayaran-jasa-dokter.store');

            // Route::get('/edit/{id}', [JasaDokterController::class, 'edit'])
            //     ->name('keuangan.jasa-dokter.edit');

            // TAMBAHKAN: Route untuk get data TagihanPasien untuk modal create

        });

        Route::prefix('report-ap-dokter')->middleware(['can:view account payable report-ap-dokter'])->group(function () {
            Route::get('/jasa-dokter-belum-diproses', [ReportAPDokterController::class, 'indexBelumDiproses'])
                ->name('keuangan.report-ap-dokter.belum-diproses');

            Route::get('/jasa-dokter-belum-dibayarkan', [ReportAPDokterController::class, 'indexBelumDibayarkan'])
                ->name('keuangan.report-ap-dokter.belum-dibayarkan');
        });

        route::prefix('ap-supplier')->middleware(['can:view account payable ap-supplier'])->group(function () {
            Route::get('/', [APSupplierController::class, 'index'])
                ->name('keuangan.ap-supplier.index');
            Route::get('/create', [APSupplierController::class, 'create'])
                ->name('keuangan.ap-supplier.partials.create');
            Route::post('/store', [APSupplierController::class, 'store'])
                ->name('keuangan.ap-supplier.store');
            Route::post('/edit', [APSupplierController::class, 'edit'])
                ->name('keuangan.ap-supplier.partials.edit');
            Route::get('/pilih-po', [APSupplierController::class, 'pilihPo'])
                ->name('keuangan.ap-supplier.partials.pilih-po');
        });

        route::prefix('ap-non-gr')->middleware(['can:view account payable ap-non-gr'])->group(function () {
            Route::get('/', [APNonGRController::class, 'index'])
                ->name('keuangan.ap-non-gr.index');
            Route::get('/edit', [APNonGRController::class, 'edit'])
                ->name('keuangan.ap-non-gr.edit');
            Route::post('/store', [APNonGRController::class, 'store'])
                ->name('keuangan.ap-non-gr.store');
        });

        route::prefix('pembayaran-ap-supplier')->middleware(['can:view account payable pembayaran-ap-supplier'])->group(function () {
            Route::get('/', [PembayaranAPSupplierController::class, 'index'])
                ->name('keuangan.pembayaran-ap-supplier.index');
            Route::get('/create', [PembayaranAPSupplierController::class, 'create'])
                ->name('keuangan.pembayaran-ap-supplier.create');
            Route::get('/details', [PembayaranAPSupplierController::class, 'details'])
                ->name('keuangan.pembayaran-ap-supplier.details');
            Route::post('/store', [PembayaranAPSupplierController::class, 'store'])
                ->name('keuangan.pembayaran-ap-supplier.store');
            Route::post('/show', [PembayaranAPSupplierController::class, 'show'])
                ->name('keuangan.pembayaran-ap-supplier.show');
        });

        route::prefix('report-ap-supplier/')->middleware(['can:view account receivable konfirmasi asuransi'])->group(function () {
            Route::get('/belum-tukar-faktur', [ReportAPSupplierController::class, 'belumTukarFaktur'])
                ->name('keuangan.report-ap-supplier.belum-tukar-faktur');
            Route::get('/aging-ap-supplier', [ReportAPSupplierController::class, 'agingApSupplier'])
                ->name('keuangan.report-ap-supplier.aging-ap-supplier');
            Route::get('/laporan-jatuh-tempo', [ReportAPSupplierController::class, 'laporanJatuhTempo'])
                ->name('keuangan.report-ap-supplier.laporan-jatuh-tempo');
        });
    });

    Route::prefix('api')->group(function () {
        Route::get('/coa/group/{group_id}', [ChartOfAccountController::class, 'getByGroup'])->name('coa.byGroup');
        Route::get('/coa/{coa:id}', [ChartOfAccountController::class, 'show'])->name('coa.show');
    });
});
