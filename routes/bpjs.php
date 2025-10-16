<?php

use App\Http\Controllers\AppController\SettingController;
use App\Http\Controllers\SIMRS\BPJS\AplicareController;
use App\Http\Controllers\SIMRS\BPJS\BridgingEclaimController;
use App\Http\Controllers\SIMRS\BPJS\BridgingVclaimController;
use App\Http\Controllers\SIMRS\BPJS\LaporanController;
use App\Http\Controllers\SIMRS\BPJS\MjknController;
use App\Http\Controllers\SIMRS\BPJS\PrbController;
use App\Http\Controllers\SIMRS\BPJS\WsBPJSController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| BPJS Routes
|--------------------------------------------------------------------------
|
| Group of routes for the "BPJS" section of the SIMRS application.
| Includes bridging Vclaim, registration, and claim management.
|
*/

Route::group(['middleware' => ['auth']], function () {
    Route::prefix('bpjs')->group(function () {
        Route::prefix('prb')->name('prb.')->group(function () {
            Route::get('data-prb', [PrbController::class, 'index'])->name('data-prb');
            Route::post('list-data-prb', [PrbController::class, 'listData'])->name('list-data-prb');
            Route::get('detail-prb', [PrbController::class, 'detailPrb'])
                ->name('detail-prb');

            // Rute untuk mengambil data detail dari API VClaim (AJAX)
            Route::post('get-detail-prb-data', [PrbController::class, 'getDetailPrbData'])
                ->name('get-detail-prb-data');
        });

        Route::prefix('mjkn')->name('mjkn.')->group(function () {
            Route::get('/dashboard', [MjknController::class, 'index'])->name('mjkn.dashboard');

            Route::get('pasien-baru', [MjknController::class, 'pasienBaru'])
                ->name('pasien-baru');

            Route::post('list-pasien-baru', [MjknController::class, 'listPasienBaru'])
                ->name('list-pasien-baru');
        });

        Route::prefix('aplicares')->name('aplicares.')->group(function () {
            Route::get('/', [AplicareController::class, 'index'])->name('index');
            Route::get('/data', [AplicareController::class, 'getData'])->name('data');
            Route::get('/bpjs-data', [AplicareController::class, 'getDataFromBpjs'])->name('bpjs-data');

            // --- Rute Aksi API ---
            Route::post('/update/{roomId}', [AplicareController::class, 'updateRoom'])->name('update');
            Route::post('/delete/{roomId}', [AplicareController::class, 'deleteRoom'])->name('delete');
            Route::post('/toggle-mapping/{roomId}', [AplicareController::class, 'toggleMapping'])->name('toggle-mapping');
            Route::post('/save-kelas-mapping', [AplicareController::class, 'saveKelasMapping'])->name('save-kelas-mapping');
        });


        Route::prefix('bridging-vclaim')->group(function () {
            Route::get('list-registrasi-sep', [BridgingVclaimController::class, 'listRegistrasiSEP'])
                ->name('bpjs.bridging-vclaim.list-registrasi-sep');

            // Rute untuk mengambil data jqGrid (AJAX)
            Route::post('list-data-sep', [BridgingVclaimController::class, 'listDataSEP'])
                ->name('bpjs.bridging-vclaim.list-data-sep');

            // Rute untuk mengambil data DataTables (AJAX)
            Route::post('list-data-sep', [BridgingVclaimController::class, 'listDataSEP'])
                ->name('bpjs.bridging-vclaim.list-data-sep');

            // Rute untuk mengambil data detail untuk child row (AJAX)
            Route::get('detail-registrasi/{id}', [BridgingVclaimController::class, 'detailRegistrasi'])
                ->name('bpjs.bridging-vclaim.detail-registrasi');

            // Rute untuk menampilkan halaman (VIEW)
            Route::get('persetujuan-sep', [BridgingVclaimController::class, 'persetujuanSEP'])
                ->name('bpjs.bridging-vclaim.persetujuan-sep');

            // Rute untuk mengambil data DataTables (AJAX)
            Route::post('list-persetujuan-sep', [BridgingVclaimController::class, 'listPersetujuanSEP'])
                ->name('bpjs.bridging-vclaim.list-persetujuan-sep');

            // Rute untuk menghapus data persetujuan (AJAX)
            Route::delete('persetujuan-sep/{approval}', [BridgingVclaimController::class, 'destroyPersetujuan'])
                ->name('bpjs.bridging-vclaim.destroy-persetujuan');

            // Rute untuk menampilkan halaman (VIEW)
            Route::get('rujukan', [BridgingVclaimController::class, 'rujukan'])
                ->name('bpjs.bridging-vclaim.rujukan');

            // Rute untuk mengambil data DataTables (AJAX)
            Route::post('list-rujukan-data', [BridgingVclaimController::class, 'listRujukanData'])
                ->name('bpjs.bridging-vclaim.list-rujukan-data');

            // Rute untuk menampilkan halaman (VIEW) Rujukan Khusus
            Route::get('rujukan-khusus', [BridgingVclaimController::class, 'rujukanKhusus'])
                ->name('bpjs.bridging-vclaim.rujukan-khusus');

            // Rute untuk mengambil data DataTables (AJAX)
            Route::post('list-rujukan-khusus-data', [BridgingVclaimController::class, 'listRujukanKhususData'])
                ->name('bpjs.bridging-vclaim.list-rujukan-khusus-data');

            // Rute untuk menampilkan halaman (VIEW) LPK
            Route::get('lembar-pengajuan-klaim', [BridgingVclaimController::class, 'lembarPengajuanKlaim'])
                ->name('bpjs.bridging-vclaim.lembar-pengajuan-klaim');

            // Rute untuk mengambil data DataTables LPK (AJAX)
            Route::post('list-lpk-data', [BridgingVclaimController::class, 'listLpkData'])
                ->name('bpjs.bridging-vclaim.list-lpk-data');

            // Rute untuk menampilkan halaman (VIEW) Rencana Kontrol
            Route::get('rencana-kontrol', [BridgingVclaimController::class, 'rencanaKontrol'])
                ->name('bpjs.bridging-vclaim.rencana-kontrol');

            // Rute untuk mengambil data DataTables Rencana Kontrol (AJAX)
            Route::post('list-rencana-kontrol-data', [BridgingVclaimController::class, 'listRencanaKontrolData'])
                ->name('bpjs.bridging-vclaim.list-rencana-kontrol-data');

            // Rute untuk menampilkan halaman (VIEW) SPRI
            Route::get('spri', [BridgingVclaimController::class, 'spri'])
                ->name('bpjs.bridging-vclaim.spri');

            // Rute ini akan berada di dalam 'bridging-vclaim'
            Route::get('data-surat-kontrol', [BridgingVclaimController::class, 'dataSuratKontrol'])
                ->name('bpjs.bridging-vclaim.data-surat-kontrol');

            Route::post('list-data-surat-kontrol', [BridgingVclaimController::class, 'listDataSuratKontrol'])
                ->name('bpjs.bridging-vclaim.list-data-surat-kontrol');

            // Rute untuk menampilkan halaman (VIEW) Detail SEP
            Route::get('detail-sep', [BridgingVclaimController::class, 'detailSEP'])
                ->name('bpjs.bridging-vclaim.detail-sep');

            // Rute untuk mengambil data detail dari API VClaim (AJAX)
            Route::post('get-detail-sep-data', [BridgingVclaimController::class, 'getDetailSepData'])
                ->name('bpjs.bridging-vclaim.get-detail-sep-data');

            // Rute untuk menghapus SEP dari API VClaim (AJAX)
            Route::delete('delete-sep-data', [BridgingVclaimController::class, 'deleteSepData'])
                ->name('bpjs.bridging-vclaim.delete-sep-data');

            Route::get('data-sep-internal', [BridgingVclaimController::class, 'dataSepInternal'])
                ->name('bpjs.bridging-vclaim.data-sep-internal');

            Route::post('list-data-sep-internal', [BridgingVclaimController::class, 'listDataSepInternal'])
                ->name('bpjs.bridging-vclaim.list-data-sep-internal');

            Route::delete('delete-sep-internal', [BridgingVclaimController::class, 'deleteSepInternal'])
                ->name('bpjs.bridging-vclaim.delete-sep-internal');
        });

        Route::prefix('ws-bpjs')->name('ws-bpjs.')->group(function () {
            // Referensi
            Route::get('/referensi-poli', [WsBPJSController::class, 'referensiPoli'])->name('referensi-poli');
            Route::get('/referensi-dokter', [WsBpjsController::class, 'referensiDokter'])->name('referensi-dokter');

            // Monitoring & Dashboard
            Route::get('/monitoring-antrian', [WsBpjsController::class, 'monitoringAntrian'])->name('monitoring-antrian');
            Route::get('/dashboard-pertanggal', [WsBpjsController::class, 'dashboardPertanggal'])->name('dashboard-pertanggal');
            Route::get('/dashboard-perbulan', [WsBpjsController::class, 'dashboardPerbulan'])->name('dashboard-perbulan');

            // Antrian
            Route::get('/antrian-pertanggal', [WsBpjsController::class, 'antrianPertanggal'])->name('antrian-pertanggal');
            Route::get('/antrian-belum-dilayani', [WsBpjsController::class, 'antrianBelumDilayani'])->name('antrian-belum-dilayani');

            // Rute untuk menampilkan halaman (VIEW) Get Fingerprint
            Route::get('/get-fingerprint-peserta', [WsBpjsController::class, 'getFingerprintPeserta'])
                ->name('get-fingerprint-peserta');

            // Rute untuk mengambil data fingerprint dari API (AJAX)
            Route::post('/get-data-fingerprint', [WsBpjsController::class, 'getDataFingerprint'])
                ->name('get-data-fingerprint');

            // Rute untuk menampilkan halaman (VIEW) List Data Fingerprint
            Route::get('/list-data-fingerprint', [WsBpjsController::class, 'listFingerprint'])
                ->name('list-data-fingerprint');

            // Rute untuk mengambil data DataTables (AJAX)
            Route::post('/get-list-fingerprint-data', [WsBpjsController::class, 'getListFingerprintData'])
                ->name('get-list-fingerprint-data');
        });

        Route::prefix('bridging-eclaim')->name('bridging-eclaim.')->group(function () {
            Route::get('/setup-jaminan', [BridgingEclaimController::class, 'setupJaminan'])->name('setup-jaminan');
            Route::get('/setup-tarif', [BridgingEclaimController::class, 'setupTarif'])->name('setup-tarif');
            Route::get('/setup-cob', [BridgingEclaimController::class, 'setupCob'])->name('setup-cob');
            Route::get('/update-data-pasien', [BridgingEclaimController::class, 'updateDataPasien'])->name('update-data-pasien');
            Route::get('/grouping-eclaim', [BridgingEclaimController::class, 'groupingEclaim'])->name('grouping-eclaim');
        });

        Route::prefix('setting')->name('setting.')->group(function () {
            Route::get('/konfigurasi-sistem', [SettingController::class, 'konfigurasiSistem'])
                ->name('konfigurasi-sistem');
        });

        Route::prefix('laporan')->group(function () {
            Route::get('/hapus-sep', [LaporanController::class, 'hapusSep'])
                ->name('laporan.hapus-sep');

            Route::get('/akses-icare', [LaporanController::class, 'aksesICare'])
                ->name('laporan.akses-icare');

            Route::get('/print/hapus-sep', [LaporanController::class, 'printHapusSep'])
                ->name('laporan.print.hapus-sep');

            Route::get('/print/akses-icare', [LaporanController::class, 'printAksesICare'])
                ->name('laporan.print.akses-icare');
        });
    });
});
