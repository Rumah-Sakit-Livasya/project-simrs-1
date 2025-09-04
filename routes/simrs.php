<?php

use App\Http\Controllers\BilinganController;
use App\Http\Controllers\FarmasiPlasma;
use App\Http\Controllers\FarmasiReportDispensing;
use App\Http\Controllers\FarmasiReportEmbalase;
use App\Http\Controllers\FarmasiReportKartuStock;
use App\Http\Controllers\FarmasiReportKronis;
use App\Http\Controllers\FarmasiReportPenjualan;
use App\Http\Controllers\FarmasiReportRekapPenjualan;
use App\Http\Controllers\FarmasiReportStockDetail;
use App\Http\Controllers\FarmasiReportStockStatus;
use App\Http\Controllers\FarmasiResepController;
use App\Http\Controllers\FarmasiResepResponseController;
use App\Http\Controllers\FarmasiReturResepController;
use App\Http\Controllers\FarmasiSignaController;
use App\Http\Controllers\InterventionPageController;
use App\Http\Controllers\JamMakanGiziController;
use App\Http\Controllers\KategoriGiziController;
use App\Http\Controllers\MakananGiziController;
use App\Http\Controllers\MenuGiziController;
use App\Http\Controllers\NursingDiannosisPageController;
use App\Http\Controllers\OrderGiziController;
use App\Http\Controllers\PlasmaDisplayRawatJalanController;
use App\Http\Controllers\ProcurementPOApprovalCEO;
use App\Http\Controllers\ProcurementPOApprovalNonPharmacy;
use App\Http\Controllers\ProcurementPOApprovalPharmacy;
use App\Http\Controllers\ProcurementPRApprovalNonPharmacy;
use App\Http\Controllers\ProcurementPRApprovalPharmacy;
use App\Http\Controllers\ProcurementPurchaseOrderNonPharmacyController;
use App\Http\Controllers\ProcurementPurchaseOrderPharmacyController;
use App\Http\Controllers\ProcurementPurchaseRequestNonPharmacyController;
use App\Http\Controllers\ProcurementPurchaseRequestPharmacyController;
use App\Http\Controllers\ProcurementSetupSupplier;
use App\Http\Controllers\SIMRS\BedController;
use App\Http\Controllers\SIMRS\BPJS\BridgingVclaimController;
use App\Http\Controllers\SIMRS\DepartementController;
use App\Http\Controllers\SIMRS\Depo\StokRequestController;
use App\Http\Controllers\SIMRS\Depo\UnitCostController as DepoUnitCostController;
use App\Http\Controllers\SIMRS\Dokter\DokterController;
use App\Http\Controllers\SIMRS\ERMController;
use App\Http\Controllers\SIMRS\EthnicController;
use App\Http\Controllers\SIMRS\Gizi\GiziController;
use App\Http\Controllers\SIMRS\GrupParameterRadiologiController;
use App\Http\Controllers\SIMRS\GrupSuplier\GrupSuplierController;
use App\Http\Controllers\SIMRS\GrupTindakanMedisController;
use App\Http\Controllers\SIMRS\HargaJual\MarginHargaJualController;
use App\Http\Controllers\SIMRS\IGD\IGDController;
use App\Http\Controllers\SIMRS\Insiden\InsidenController;
use App\Http\Controllers\SIMRS\JadwalDokter\JadwalDokterController;
use App\Http\Controllers\SIMRS\KategoriRadiologiController;
use App\Http\Controllers\SIMRS\KelasRawatController;
use App\Http\Controllers\SIMRS\KepustakaanController;
use App\Http\Controllers\SIMRS\Laboratorium\GrupParameterLaboratoriumController;
use App\Http\Controllers\SIMRS\Laboratorium\KategoriLaboratorumController;
use App\Http\Controllers\SIMRS\Laboratorium\LaboratoriumController;
use App\Http\Controllers\SIMRS\Laboratorium\NilaiNormalLaboratoriumController;
use App\Http\Controllers\SIMRS\Laboratorium\ParameterLaboratoriumController;
use App\Http\Controllers\SIMRS\Laboratorium\TipeLaboratoriumController;
use App\Http\Controllers\SIMRS\Operasi\JenisOperasiController;
use App\Http\Controllers\SIMRS\Operasi\KategoriOperasiController;
use App\Http\Controllers\SIMRS\Operasi\OperasiController;
use App\Http\Controllers\SIMRS\Operasi\TindakanOperasiController;
use App\Http\Controllers\SIMRS\Operasi\TipeOperasiController;
use App\Http\Controllers\SIMRS\ParameterRadiologiController;
use App\Http\Controllers\SIMRS\PatientController;
use App\Http\Controllers\SIMRS\Pengkajian\FormBuilderController;
use App\Http\Controllers\SIMRS\Pengkajian\PengkajianController;
use App\Http\Controllers\SIMRS\Penjamin\PenjaminController;
use App\Http\Controllers\SIMRS\Peralatan\PeralatanController;
use App\Http\Controllers\SIMRS\Persalinan\BayiController;
use App\Http\Controllers\SIMRS\Persalinan\DaftarPersalinanController;
use App\Http\Controllers\SIMRS\Persalinan\KategoriPersalinanController;
use App\Http\Controllers\SIMRS\Persalinan\PersalinanController;
use App\Http\Controllers\SIMRS\Persalinan\TipePersalinanController;
use App\Http\Controllers\SIMRS\Poliklinik\ChecklistMonitoringController;
use App\Http\Controllers\SIMRS\Poliklinik\PoliklinikController;
use App\Http\Controllers\SIMRS\PatientMonitoringController;
use App\Http\Controllers\SIMRS\Procurement\ApprovalPOController;
use App\Http\Controllers\SIMRS\Procurement\ApprovalPRController;
use App\Http\Controllers\SIMRS\Procurement\PurchaseOrderController;
use App\Http\Controllers\SIMRS\Procurement\PurchaseRequestController as ProcurementPurchaseRequestController;
use App\Http\Controllers\SIMRS\Procurement\SetupController;
use App\Http\Controllers\SIMRS\Radiologi\RadiologiController;
use App\Http\Controllers\SIMRS\RegistrationController;
use App\Http\Controllers\SIMRS\RoomController;
use App\Http\Controllers\SIMRS\Setup\BiayaAdministrasiRawatInapController;
use App\Http\Controllers\SIMRS\Setup\BiayaMateraiController;
use App\Http\Controllers\SIMRS\Setup\TarifRegistrasiController;
use App\Http\Controllers\SIMRS\TagihanPasienController;
use App\Http\Controllers\SIMRS\TindakanMedisController;
use App\Http\Controllers\SIMRS\TriageController;
use App\Http\Controllers\SIMRS\UtilityController;
use App\Http\Controllers\SIMRS\Warehouse\DistribusiBarangController;
use App\Http\Controllers\SIMRS\Warehouse\MasterDataWarehouseController;
use App\Http\Controllers\SIMRS\Warehouse\PenerimaanBarangController;
use App\Http\Controllers\SIMRS\Warehouse\PurchaseRequestController;
use App\Http\Controllers\SIMRS\Warehouse\ReportWarehouseController;
use App\Http\Controllers\SIMRS\Warehouse\RevaluasiStokController;
use App\Http\Controllers\SIMRS\Warehouse\StockRequestController;
use App\Http\Controllers\SIMRS\Warehouse\UnitCostController;
use App\Http\Controllers\WarehouseBarangFarmasiController;
use App\Http\Controllers\WarehouseBarangNonFarmasiController;
use App\Http\Controllers\WarehouseDistribusiBarangFarmasiController;
use App\Http\Controllers\WarehouseDistribusiBarangNonFarmasiController;
use App\Http\Controllers\WarehouseDistribusiBarangReportController;
use App\Http\Controllers\WarehouseGolonganBarangController;
use App\Http\Controllers\WarehouseKategoriBarangController;
use App\Http\Controllers\WarehouseKelompokBarangController;
use App\Http\Controllers\WarehouseMasterGudangController;
use App\Http\Controllers\WarehousePabrikController;
use App\Http\Controllers\WarehousePenerimaanBarangFarmasiController;
use App\Http\Controllers\WarehousePenerimaanBarangNonFarmasiController;
use App\Http\Controllers\WarehousePenerimaanBarangReportController;
use App\Http\Controllers\WarehousePurchaseRequestNonPharmacy;
use App\Http\Controllers\WarehousePurchaseRequestPharmacy;
use App\Http\Controllers\WarehouseReportHistoriPerubahanMasterBarang;
use App\Http\Controllers\WarehouseReportKartuStock;
use App\Http\Controllers\WarehouseReportStockDetail;
use App\Http\Controllers\WarehouseReportStockStatus;
use App\Http\Controllers\WarehouseReturBarangController;
use App\Http\Controllers\WarehouseSatuanBarangController;
use App\Http\Controllers\WarehouseSetupMinMaxStockController;
use App\Http\Controllers\WarehouseStockAdjustmentController;
use App\Http\Controllers\WarehouseStockOpnameDraft;
use App\Http\Controllers\WarehouseStockOpnameFinal;
use App\Http\Controllers\WarehouseStockOpnameGudangController;
use App\Http\Controllers\WarehouseStockOpnameReport;
use App\Http\Controllers\WarehouseStockRequestNonPharmacyController;
use App\Http\Controllers\WarehouseStockRequestPharmacyController;
use App\Http\Controllers\WarehouseSupplierController;
use App\Http\Controllers\WarehouseZatAktifController;
use App\Models\SIMRS\Registration;
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
    /*
    |--------------------------------------------------------------------------
    | Patient Registration and Search Routes
    |--------------------------------------------------------------------------
    |
    | Routes for patient registration, medical record management and bed data.
    | Includes search functionality, new patient registration, and bed availability.
    | All routes require authentication.
    |
    */

    // Patient search endpoint
    Route::get('/search-patients', [PatientController::class, 'search'])
        ->name('patients.search');

    Route::get('/utility/signature-pad', [UtilityController::class, 'showSignaturePad'])->name('utility.signature.pad');

    // Get bed availability data
    Route::get('/beds/get-data', [RegistrationController::class, 'getDataBed'])
        ->name('beds.getData');

    // Medical records listing
    Route::get('/daftar-rekam-medis', [PatientController::class, 'daftar_rm'])
        ->name('pendaftaran.pasien.daftar_rm');

    // New patient registration form
    Route::get('/pendaftaran-pasien-baru', [PatientController::class, 'pendaftaran_pasien_baru'])
        ->name('pendaftaran.pasien.pendaftaran_pasien_baru');

    // Save new patient registration
    Route::post('/pendaftaran-pasien-baru', [PatientController::class, 'simpan_pendaftaran_pasien'])
        ->name('simpan.pendaftaran.pasien');

    // Get patient data
    Route::get('/data', [PatientController::class, 'getData'])
        ->name('data.route');

    /*
    |--------------------------------------------------------------------------
    | Patient Management Routes
    |--------------------------------------------------------------------------
    |
    | Routes for managing patient data including:
    | - Viewing patient details and history
    | - Editing patient information
    | - Printing patient documents and cards
    | - Patient registration for services
    |
    */
    Route::prefix('patients')->group(function () {
        // View patient details
        Route::get('/{patient}', [PatientController::class, 'detail_patient'])
            ->name('detail.pendaftaran.pasien');

        // Edit patient information
        Route::get('/{patient:id}/edit', [PatientController::class, 'edit_pendaftaran_pasien'])
            ->name('edit.pendaftaran.pasien');
        Route::post('/{patient:id}/', [PatientController::class, 'update_pendaftaran_pasien'])
            ->name('update.pendaftaran.pasien');

        // Print patient documents
        Route::get('/{patient:id}/print', [PatientController::class, 'print_identitas_pasien'])
            ->name('print.identitas.pasien');
        Route::get('/{patient:id}/print-kartu', [PatientController::class, 'print_kartu_pasien'])
            ->name('print.kartu.pasien');

        // View patient visit history
        Route::get('/{patient:id}/history', [PatientController::class, 'history_kunjungan_pasien'])
            ->name('history.kunjungan.pasien');

        // Patient registration routes
        Route::get('/{patient:id}/{registrasi}', [RegistrationController::class, 'create'])
            ->name('form.registrasi'); // Registration form for Outpatient/Inpatient/ER
        Route::post('/simpan/registrasi', [RegistrationController::class, 'store'])
            ->name('simpan.registrasi'); // Process registration
    });

    /*
    |--------------------------------------------------------------------------
    | Patient Registration Routes
    |--------------------------------------------------------------------------
    |
    | Routes for managing patient registrations including:
    | - Viewing registration list
    | - Registration details
    | - Canceling registrations
    | - Closing visits
    | - Changing primary doctor and diagnosis
    |
    */
    Route::prefix('daftar-registrasi-pasien')->group(function () {
        Route::get('/', [RegistrationController::class, 'index'])
            ->name('pendaftaran.daftar_registrasi_pasien');

        Route::get('/{registrations:id}', [RegistrationController::class, 'show'])
            ->name('detail.registrasi.pasien');

        Route::post('/{registrations:id}/batal-register', [RegistrationController::class, 'batal_register'])
            ->name('batal.register');

        Route::post('/{registrations:id}/batal-keluar', [RegistrationController::class, 'batal_keluar'])
            ->name('batal.keluar');

        Route::post('/{registrations:id}/tutup-kunjungan', [RegistrationController::class, 'tutup_kunjungan'])
            ->name('tutup.kunjungan');

        Route::post('/{registrations:id}/ganti-dpjp', [RegistrationController::class, 'ganti_dpjp'])
            ->name('ganti.dpjp');

        Route::post('/{registrations:id}/ganti-diagnosa', [RegistrationController::class, 'ganti_diagnosa'])
            ->name('ganti.diagnosa');
    });

    /*
    |--------------------------------------------------------------------------
    | SIMRS Routes
    |--------------------------------------------------------------------------
    |
    | Routes for the main SIMRS (Hospital Information System) functionality.
    | Contains routes for various hospital modules like:
    | - Dashboard
    | - Master Data Management
    | - Clinical Services (Outpatient, Inpatient, ER)
    | - Support Services (Radiology, Laboratory, Pharmacy)
    | - Administrative Functions
    |
    */
    Route::prefix('simrs')->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard Route
        |--------------------------------------------------------------------------
        |
        | Main dashboard route for the SIMRS application.
        | Displays overview and summary of hospital information system.
        |
        */
        Route::get('/dashboard', function () {
            return view('app-type.simrs.dashboard');
        })
            ->name('dashboard.simrs');

        /*
        |--------------------------------------------------------------------------
        | Master Data Routes
        |--------------------------------------------------------------------------
        |
        | Routes for managing master data including:
        | - Setup configurations (admin fees, stamps, wards, rooms, etc)
        | - Medical services (procedures, groups)
        | - Support services (radiology, laboratory)
        | - Equipment and facilities
        | - Maternity and surgery
        | - Suppliers and insurance
        | - Doctor schedules and pricing
        |
        */
        Route::prefix('/master-data')->group(function () {
            Route::prefix('interventions')->group(function () {
                Route::get('/', [InterventionPageController::class, 'index'])->name('master.interventions');
            });
            Route::prefix('diagnosa-keperawatan')->group(function () {
                Route::get('/categories', [NursingDiannosisPageController::class, 'viewCategories'])->name('master.categories');
                Route::get('/diagnoses', [NursingDiannosisPageController::class, 'viewDiagnoses'])->name('master.diagnoses');
            });
            // Setup and configuration routes
            Route::prefix('setup')->group(function () {
                // Inpatient administration fees
                Route::prefix('biaya-administrasi-ranap')->group(function () {
                    Route::get('/', [BiayaAdministrasiRawatInapController::class, 'index'])
                        ->name('master-data.setup.biaya-administrasi-ranap');
                });

                // Basic setup routes (stamp duty, wards, rooms, beds)
                Route::get('/biaya-materai', [BiayaMateraiController::class, 'index'])
                    ->name('master-data.setup.biaya-materai');
                Route::get('/kelas-rawat', [KelasRawatController::class, 'index'])
                    ->name('master-data.setup.kelas-rawat');
                Route::get('/rooms/{kelas:id}', [RoomController::class, 'index'])
                    ->name('master-data.setup.rooms');
                Route::get('/beds/{room:id}', [BedController::class, 'index'])
                    ->name('master-data.setup.beds');

                Route::prefix('departemen')
                    ->name('master-data.setup.departemen.')
                    ->group(function () {

                        Route::get('/', [DepartementController::class, 'index'])->name('index'); // URL: /departemen

                        Route::get('/tambah', [DepartementController::class, 'tambah'])->name('tambah'); // URL: /departemen/tambah

                        Route::post('/store', [DepartementController::class, 'store'])->name('store'); // URL: /departemen/store

                        // Rute untuk Import
                        // Route::get('/import', [DepartementController::class, 'showImportForm'])->name('import.form'); // URL: /departemen/import

                        Route::post('/import', [DepartementController::class, 'import'])->name('import'); // URL: /departemen/import (method POST)
                    });

                // Registration fee setup
                Route::get('/tarif-registrasi-layanan', [TarifRegistrasiController::class, 'index'])
                    ->name('master-data.setup.tarif-registrasi.index');
                Route::get('/tarif-registrasi-layanan/{id}/set-tarif', [TarifRegistrasiController::class, 'setTarif'])
                    ->name('master-data.setup.tarif-registrasi.set-tarif');
                Route::get('/tarif-registrasi-layanan/{id}/set-departement', [TarifRegistrasiController::class, 'setDepartement'])
                    ->name('master-data.setup.tarif-registrasi.set-departement');

                // Form builder routes
                Route::get('/form-builder', [FormBuilderController::class, 'index'])
                    ->name('master-data.setup.form-builder');
                Route::get('/form-builder/tambah', [FormBuilderController::class, 'create'])
                    ->name('master-data.setup.form-builder.tambah');

                // Route baru untuk menampilkan halaman edit
                Route::get('/form-builder/{id}/edit', [FormBuilderController::class, 'edit'])
                    ->name('master-data.setup.form-builder.edit');

                // Ethnicity management
                Route::prefix('ethnics')->group(function () {
                    Route::get('/', [EthnicController::class, 'index'])
                        ->name('master-data.ethnics');
                });
            });

            // Medical services routes
            Route::prefix('layanan-medis')->group(function () {
                Route::get('/tindakan-medis', [TindakanMedisController::class, 'index'])
                    ->name('master-data.layanan-medis.tindakan-medis');
                Route::get('/grup-tindakan-medis', [GrupTindakanMedisController::class, 'index'])
                    ->name('master-data.layanan-medis.grup-tindakan-medis');
            });

            // Medical support services routes
            Route::prefix('penunjang-medis')->group(function () {
                // Radiology routes
                Route::prefix('radiologi')->group(function () {
                    Route::get('/grup-parameter', [GrupParameterRadiologiController::class, 'index'])
                        ->name('master-data.penunjang-medis.radiologi.grup-parameter');
                    Route::get('/kategori', [KategoriRadiologiController::class, 'index'])
                        ->name('master-data.penunjang-medis.radiologi.kategori');
                    Route::get('/parameter', [ParameterRadiologiController::class, 'index'])
                        ->name('master-data.penunjang-medis.radiologi.parameter');
                    Route::get('/parameter/{id}/tarif', [ParameterRadiologiController::class, 'tarifParameter'])
                        ->name('master-data.penunjang-medis.radiologi.parameter.tarif');
                });

                // Laboratory routes
                Route::prefix('laboratorium')->group(function () {
                    Route::get('/grup-parameter', [GrupParameterLaboratoriumController::class, 'index'])
                        ->name('master-data.penunjang-medis.laboratorium.grup-parameter');
                    Route::get('/kategori', [KategoriLaboratorumController::class, 'index'])
                        ->name('master-data.penunjang-medis.laboratorium.kategori');
                    Route::get('/parameter', [ParameterLaboratoriumController::class, 'index'])
                        ->name('master-data.penunjang-medis.laboratorium.parameter');
                    Route::get('/parameter/{id}/tarif', [ParameterLaboratoriumController::class, 'tarifParameter'])
                        ->name('master-data.penunjang-medis.laboratorium.parameter.tarif');
                    Route::get('/nilai-normal', [NilaiNormalLaboratoriumController::class, 'index'])
                        ->name('master-data.penunjang-medis.laboratorium.nilai-parameter');
                    Route::get('/tipe', [TipeLaboratoriumController::class, 'index'])
                        ->name('master-data.penunjang-medis.laboratorium.tipe');
                });

                // Pharmacy routes
                Route::prefix('farmasi')->group(function () {
                    Route::get('/signa', [FarmasiSignaController::class, 'index'])->name('master-data.penunjang-medis.farmasi.signa');
                });
            });

            // Equipment management routes
            Route::prefix('peralatan')->group(function () {
                Route::get('/', [PeralatanController::class, 'index'])
                    ->name('master-data.peralatan');
                Route::get('{id}/tarif', [PeralatanController::class, 'tarifPeralatan'])
                    ->name('master-data.peralatan.tarif');
            });

            // Maternity routes
            Route::prefix('persalinan')->group(function () {
                Route::get('/kategori', [KategoriPersalinanController::class, 'index'])
                    ->name('master-data.persalinan.kategori.index');
                Route::get('/tipe', [TipePersalinanController::class, 'index'])
                    ->name('master-data.persalinan.tipe');
                Route::get('/daftar-persalinan', [DaftarPersalinanController::class, 'index'])
                    ->name('master-data.persalinan.daftar');
                Route::get('/persalinan/{id}/tarif', [DaftarPersalinanController::class, 'tarifPersalinan'])
                    ->name('master-data.persalinan.tarif.index');
            });

            // Surgery routes
            Route::prefix('operasi')->group(function () {
                Route::get('/kategori', [KategoriOperasiController::class, 'index'])
                    ->name('master-data.operasi.kategori.index');
                Route::get('/tipe', [TipeOperasiController::class, 'index'])
                    ->name('master-data.operasi.tipe');
                Route::get('/jenis', [JenisOperasiController::class, 'index'])
                    ->name('master-data.operasi.jenis');
                Route::get('/tindakan', [TindakanOperasiController::class, 'index'])
                    ->name('master-data.operasi.tindakan');
                Route::get('/tindakan/{id}/tarif', [TindakanOperasiController::class, 'tarifPersalinan'])
                    ->name('master-data.operasi.tarif');
            });

            // Supplier group routes
            Route::prefix('grup-suplier')->group(function () {
                Route::get('/', [GrupSuplierController::class, 'index'])
                    ->name('master-data.grup-suplier.index');
            });

            // Insurance provider routes
            Route::prefix('penjamin')->group(function () {
                Route::get('/', [PenjaminController::class, 'index'])
                    ->name('master-data.penjamin.index');
            });

            // Doctor schedule routes
            Route::prefix('jadwal-dokter')->group(function () {
                Route::get('setting', [JadwalDokterController::class, 'index'])
                    ->name('master-data.jadwal-dokter.index');
            });

            // Selling price routes
            Route::prefix('harga-jual')->group(function () {
                Route::get('margin', [MarginHargaJualController::class, 'index'])
                    ->name('master-date.setup.harga-jual.margin.index');
            });
        });

        Route::prefix('poliklinik')->name('poliklinik.')->group(function () {
            Route::prefix('antrian-poli')->name('antrian-poli.')->group(function () {
                Route::get('/setup-plasma', [PlasmaDisplayRawatJalanController::class, 'index'])->name('index');
                Route::get('/create', [PlasmaDisplayRawatJalanController::class, 'create'])->name('create');
                Route::post('/', [PlasmaDisplayRawatJalanController::class, 'store'])->name('store');
                Route::get('/setup-plasma/{id}/edit', [PlasmaDisplayRawatJalanController::class, 'edit'])->name('edit');
                Route::put('/setup-plasma/{id}', [PlasmaDisplayRawatJalanController::class, 'update'])->name('update');
                Route::get('/plasma/{id}', [PlasmaDisplayRawatJalanController::class, 'show'])->name('show');
            });
            // Rute utama untuk daftar pasien poliklinik
            Route::get('/daftar-pasien', [ERMController::class, 'catatanMedis'])
                ->name('daftar-pasien');

            // Route untuk rekap pasien per poliklinik
            Route::get('/rekap-pasien', [PoliklinikController::class, 'rekapPasienPerPoliklinik'])
                ->name('rekap-pasien');

            // AJAX routes untuk rekap pasien
            Route::post('/rekap-pasien/refresh', [PoliklinikController::class, 'refreshData'])
                ->name('rekap-pasien.refresh');
            Route::post('/rekap-pasien/patient-details', [PoliklinikController::class, 'getPatientDetails'])
                ->name('rekap-pasien.patient-details');
            Route::post('/rekap-pasien/export', [PoliklinikController::class, 'exportRekap'])
                ->name('rekap-pasien.export');

            // Route untuk checklist monitoring pasien
            Route::get('/checklist-monitoring', [ChecklistMonitoringController::class, 'index'])
                ->name('checklist-monitoring.index');

            // Route untuk form pencarian monitoring pasien
            Route::get('/patient-monitoring-search', function () {
                return view('pages.simrs.poliklinik.patient-monitoring-search');
            })->name('patient-monitoring-search.index');

            Route::post('/patient-monitoring-search', [PoliklinikController::class, 'searchMonitoring'])
                ->name('patient-monitoring-search');

            // Route untuk monitoring pasien
            Route::get('/patient-monitoring', [PatientMonitoringController::class, 'index'])
                ->name('monitoring.index');
            Route::get('/patient-monitoring/data', [PatientMonitoringController::class, 'getMonitoringData'])
                ->name('monitoring.data');
            Route::get('/patient-monitoring/detail', [PatientMonitoringController::class, 'getMonitoringData'])
                ->name('monitoring.detail');
            Route::get('/patient-monitoring/stats', [PatientMonitoringController::class, 'getMonitoringStats'])
                ->name('monitoring.stats');

            // API routes untuk checklist monitoring
            Route::prefix('api')->name('api.')->group(function () {
                Route::get('/patient', [ChecklistMonitoringController::class, 'getPatient'])
                    ->name('checklist-monitoring.get-patient');
                Route::post('/monitoring/start', [ChecklistMonitoringController::class, 'startMonitoring'])
                    ->name('checklist-monitoring.start-monitoring');
                Route::post('/checklist/save', [ChecklistMonitoringController::class, 'saveChecklist'])
                    ->name('checklist-monitoring.save-checklist');
                Route::get('/monitoring/history', [ChecklistMonitoringController::class, 'getMonitoringHistory'])
                    ->name('checklist-monitoring.monitoring-history');
                Route::get('/monitoring/stats', [ChecklistMonitoringController::class, 'getMonitoringStats'])
                    ->name('checklist-monitoring.monitoring-stats');
                Route::get('/patients/search', [ChecklistMonitoringController::class, 'searchPatients'])
                    ->name('checklist-monitoring.search-patients');
            });

            // === Grup Rute untuk Pengkajian Lanjutan ===
            // Mengelompokkan semua rute terkait pengkajian lanjutan agar lebih rapi.
            // Refactored: Gunakan Route::controller dan urutkan rute statis sebelum dinamis
            Route::prefix('pengkajian-lanjutan')->name('pengkajian-lanjutan.')->group(function () {
                // Form baru (CREATE)
                Route::get('/create/{registration_id}/{template_id}', [PoliklinikController::class, 'showForm'])
                    ->name('create');

                // Simpan form baru (STORE)
                Route::post('/', [PengkajianController::class, 'storeOrUpdatePengkajianLanjutan'])
                    ->name('store');

                // Edit form yang sudah diisi (EDIT)
                Route::get('/{pengkajianLanjutan}/edit', [PoliklinikController::class, 'editFilledForm'])
                    ->name('edit');

                // Update form yang sudah diisi (UPDATE)
                Route::put('/{pengkajianLanjutan}', [PengkajianController::class, 'storeOrUpdatePengkajianLanjutan'])
                    ->name('update');

                // Hapus data pengkajian (DESTROY)
                Route::delete('/{pengkajianLanjutan}', [PengkajianController::class, 'destroyPengkajianLanjutan'])
                    ->name('destroy');

                // Tampilkan form yang sudah diisi (SHOW) - letakkan paling bawah agar tidak bentrok dengan rute statis
                Route::get('/{pengkajianLanjutan}', [PoliklinikController::class, 'showFilledForm'])
                    ->name('show');
            });
        });

        Route::prefix('farmasi')->group(function () {
            Route::prefix('laporan')->group(function () {
                Route::get('/stock-status', [FarmasiReportStockStatus::class, 'index'])->name('farmasi.report.stock-status');
                Route::get('/stock-detail', [FarmasiReportStockDetail::class, 'index'])->name('farmasi.report.stock-detail');
                Route::get('/kartu-stok', [FarmasiReportKartuStock::class, 'index'])->name('farmasi.report.kartu-stock');
            });
        });

        Route::prefix('warehouse')->group(function () {
            Route::prefix('master-data')->group(function () {
                Route::get('zat-aktif', [WarehouseZatAktifController::class, 'index'])->name('warehouse.master-data.zat-aktif');
                Route::get('satuan-barang', [WarehouseSatuanBarangController::class, 'index'])->name('warehouse.master-data.satuan-barang');
                Route::get('kelompok-barang', [WarehouseKelompokBarangController::class, 'index'])->name('warehouse.master-data.kelompok-barang');
                Route::get('kategori-barang', [WarehouseKategoriBarangController::class, 'index'])->name('warehouse.master-data.kategori-barang');
                Route::get('golongan-barang', [WarehouseGolonganBarangController::class, 'index'])->name('warehouse.master-data.golongan-barang');
                Route::get('pabrik', [WarehousePabrikController::class, 'index'])->name('warehouse.master-data.pabrik');
                Route::get('supplier', [WarehouseSupplierController::class, 'index'])->name('warehouse.master-data.supplier');
                Route::get('master-gudang', [WarehouseMasterGudangController::class, 'index'])->name('warehouse.master-data.master-gudang');
                Route::prefix('barang-non-farmasi')->group(function () {
                    Route::get('/', [WarehouseBarangNonFarmasiController::class, 'index'])->name('warehouse.master-data.barang-non-farmasi');
                    Route::get('/create', [WarehouseBarangNonFarmasiController::class, 'create'])->name('warehouse.master-data.barang-non-farmasi.create');
                    Route::get('/edit/{id}', [WarehouseBarangNonFarmasiController::class, 'edit'])->name('warehouse.master-data.barang-non-farmasi.edit');
                });

                Route::prefix('barang-farmasi')->group(function () {
                    Route::get('/', [WarehouseBarangFarmasiController::class, 'index'])->name('warehouse.master-data.barang-farmasi');
                    Route::get('/create', [WarehouseBarangFarmasiController::class, 'create'])->name('warehouse.master-data.barang-farmasi.create');
                    Route::get('/edit/{id}', [WarehouseBarangFarmasiController::class, 'edit'])->name('warehouse.master-data.barang-farmasi.edit');
                });
                Route::get('setup-min-max-stock', [WarehouseSetupMinMaxStockController::class, 'index'])->name('warehouse.master-data.setup-min-max-stock');
                Route::get('setup-min-max-stock/setup', [WarehouseSetupMinMaxStockController::class, 'create'])->name('warehouse.master-data.setup-min-max-stock.create');
            });

            Route::prefix('purchase-request')->group(function () {
                Route::prefix('pharmacy')->group(function () {
                    Route::get('/', [WarehousePurchaseRequestPharmacy::class, 'index'])->name('warehouse.purchase-request.pharmacy');
                    Route::get('/create', [WarehousePurchaseRequestPharmacy::class, 'create'])->name('warehouse.purchase-request.pharmacy.create');
                    Route::get('/print/{id}', [WarehousePurchaseRequestPharmacy::class, 'print'])->name('warehouse.purchase-request.pharmacy.print');
                    Route::get('/edit/{id}', [WarehousePurchaseRequestPharmacy::class, 'edit'])->name('warehouse.purchase-request.pharmacy.edit');
                });

                Route::prefix('non-pharmacy')->group(function () {
                    Route::get('/', [WarehousePurchaseRequestNonPharmacy::class, 'index'])->name('warehouse.purchase-request.non-pharmacy');
                    Route::get('/create', [WarehousePurchaseRequestNonPharmacy::class, 'create'])->name('warehouse.purchase-request.non-pharmacy.create');
                    Route::get('/print/{id}', [WarehousePurchaseRequestNonPharmacy::class, 'print'])->name('warehouse.purchase-request.non-pharmacy.print');
                    Route::get('/edit/{id}', [WarehousePurchaseRequestNonPharmacy::class, 'edit'])->name('warehouse.purchase-request.non-pharmacy.edit');
                });
            });

            Route::prefix('penerimaan-barang')->group(function () {
                Route::prefix('pharmacy')->group(function () {
                    Route::get('/', [WarehousePenerimaanBarangFarmasiController::class, 'index'])->name('warehouse.penerimaan-barang.pharmacy');
                    Route::get('/create', [WarehousePenerimaanBarangFarmasiController::class, 'create'])->name('procurement.penerimaan-barang.pharmacy.create');
                    Route::get('/print/{id}', [WarehousePenerimaanBarangFarmasiController::class, 'print'])->name('warehouse.penerimaan-barang.pharmacy.print');
                    Route::get('/edit/{id}', [WarehousePenerimaanBarangFarmasiController::class, 'edit'])->name('warehouse.penerimaan-barang.pharmacy.edit');
                });

                Route::prefix('non-pharmacy')->group(function () {
                    Route::get('/', [WarehousePenerimaanBarangNonFarmasiController::class, 'index'])->name('warehouse.penerimaan-barang.non-pharmacy');
                    Route::get('/create', [WarehousePenerimaanBarangNonFarmasiController::class, 'create'])->name('procurement.penerimaan-barang.non-pharmacy.create');
                    Route::get('/print/{id}', [WarehousePenerimaanBarangNonFarmasiController::class, 'print'])->name('warehouse.penerimaan-barang.non-pharmacy.print');
                    Route::get('/edit/{id}', [WarehousePenerimaanBarangNonFarmasiController::class, 'edit'])->name('warehouse.penerimaan-barang.non-pharmacy.edit');
                });

                Route::prefix('retur-barang')->group(function () {
                    Route::get('/', [WarehouseReturBarangController::class, 'index'])->name('warehouse.penerimaan-barang.retur-barang');
                    Route::get('/create', [WarehouseReturBarangController::class, 'create'])->name('procurement.penerimaan-barang.retur-barang.create');
                    Route::get('/print/{id}', [WarehouseReturBarangController::class, 'print'])->name('warehouse.penerimaan-barang.retur-barang.print');
                });

                Route::prefix('report')->group(function () {
                    Route::get('/', [WarehousePenerimaanBarangReportController::class, 'index'])->name('warehouse.penerimaan-barang.report');
                    Route::get('show/{type}/{json}', [WarehousePenerimaanBarangReportController::class, 'show'])->name('warehouse.penerimaan-barang.report.show');
                });
            });

            Route::prefix('stock-request')->group(function () {
                Route::prefix('pharmacy')->group(function () {
                    Route::get('/', [WarehouseStockRequestPharmacyController::class, 'index'])->name('warehouse.stock-request.pharmacy');
                    Route::get('/create', [WarehouseStockRequestPharmacyController::class, 'create'])->name('warehouse.stock-request.pharmacy.create');
                    Route::get('/print/{id}', [WarehouseStockRequestPharmacyController::class, 'print'])->name('warehouse.stock-request.pharmacy.print');
                    Route::get('/edit/{id}', [WarehouseStockRequestPharmacyController::class, 'edit'])->name('warehouse.stock-request.pharmacy.edit');
                });

                Route::prefix('non-pharmacy')->group(function () {
                    Route::get('/', [WarehouseStockRequestNonPharmacyController::class, 'index'])->name('warehouse.stock-request.non-pharmacy');
                    Route::get('/create', [WarehouseStockRequestNonPharmacyController::class, 'create'])->name('warehouse.stock-request.non-pharmacy.create');
                    Route::get('/print/{id}', [WarehouseStockRequestNonPharmacyController::class, 'print'])->name('warehouse.stock-request.non-pharmacy.print');
                    Route::get('/edit/{id}', [WarehouseStockRequestNonPharmacyController::class, 'edit'])->name('warehouse.stock-request.non-pharmacy.edit');
                });
            });

            Route::prefix('distribusi-barang')->group(function () {
                Route::prefix('pharmacy')->group(function () {
                    Route::get('/', [WarehouseDistribusiBarangFarmasiController::class, 'index'])->name('warehouse.distribusi-barang.pharmacy');
                    Route::get('/create', [WarehouseDistribusiBarangFarmasiController::class, 'create'])->name('warehouse.distribusi-barang.pharmacy.create');
                    Route::get('/print/{id}', [WarehouseDistribusiBarangFarmasiController::class, 'print'])->name('warehouse.distribusi-barang.pharmacy.print');
                    Route::get('/edit/{id}', [WarehouseDistribusiBarangFarmasiController::class, 'edit'])->name('warehouse.distribusi-barang.pharmacy.edit');
                });

                Route::prefix('non-pharmacy')->group(function () {
                    Route::get('/', [WarehouseDistribusiBarangNonFarmasiController::class, 'index'])->name('warehouse.distribusi-barang.non-pharmacy');
                    Route::get('/create', [WarehouseDistribusiBarangNonFarmasiController::class, 'create'])->name('warehouse.distribusi-barang.non-pharmacy.create');
                    Route::get('/print/{id}', [WarehouseDistribusiBarangNonFarmasiController::class, 'print'])->name('warehouse.distribusi-barang.non-pharmacy.print');
                    Route::get('/edit/{id}', [WarehouseDistribusiBarangNonFarmasiController::class, 'edit'])->name('warehouse.distribusi-barang.non-pharmacy.edit');
                });

                Route::prefix('report')->group(function () {
                    Route::get('/', [WarehouseDistribusiBarangReportController::class, 'index'])->name('warehouse.distribusi-barang.report');
                    Route::get('show/{json}', [WarehouseDistribusiBarangReportController::class, 'show'])->name('warehouse.distribusi-barang.report.show');
                });
            });

            Route::prefix('revaluasi-stock')->group(function () {
                Route::prefix('stock-adjustment')->group(function () {
                    Route::get('/', [WarehouseStockAdjustmentController::class, 'index'])->name('warehouse.revaluasi-stock.stock-adjustment');
                    Route::get('/create/{token}', [WarehouseStockAdjustmentController::class, 'create'])->name('warehouse.revaluasi-stock.stock-adjustment.create');
                    Route::get('/edit/{gudang_id}/{barang_id}/{satuan_id}/{type}/{token}', [WarehouseStockAdjustmentController::class, 'edit'])->name('warehouse.revaluasi-stock.stock-adjustment.edit');
                });

                Route::prefix('stock-opname')->group(function () {
                    Route::prefix('gudang-opname')->group(function () {
                        Route::get('/', [WarehouseStockOpnameGudangController::class, 'index'])->name('warehouse.revaluasi-stock.stock-opname.gudang-opname');
                    });

                    Route::prefix('draft')->group(function () {
                        Route::get('/', [WarehouseStockOpnameDraft::class, 'index'])->name('warehouse.revaluasi-stock.stock-opname.draft');
                        Route::get('/print-selisih/{sog_id}', [WarehouseStockOpnameDraft::class, 'print_selisih'])->name('warehouse.revaluasi-stock.stock-opname.draft.print.selisih');
                        Route::get('/print-so/{sog_id}', [WarehouseStockOpnameDraft::class, 'print_so'])->name('warehouse.revaluasi-stock.stock-opname.draft.print.so');
                    });

                    Route::prefix('final')->group(function () {
                        Route::get('/', [WarehouseStockOpnameFinal::class, 'index'])->name('warehouse.revaluasi-stock.stock-opname.final');
                        Route::get('/print-selisih/{sog_id}', [WarehouseStockOpnameFinal::class, 'print_selisih'])->name('warehouse.revaluasi-stock.stock-opname.final.print.selisih');
                        Route::get('/print-so/{sog_id}', [WarehouseStockOpnameFinal::class, 'print_so'])->name('warehouse.revaluasi-stock.stock-opname.final.print.so');
                    });

                    Route::prefix('report')->group(function () {
                        Route::get('/', [WarehouseStockOpnameReport::class, 'index'])->name('warehouse.revaluasi-stock.stock-opname.report');
                        Route::get('/print-selisih/{sog_id}', [WarehouseStockOpnameReport::class, 'print_selisih'])->name('warehouse.revaluasi-stock.stock-opname.report.print.selisih');
                        Route::get('/print-so/{sog_id}', [WarehouseStockOpnameReport::class, 'print_so'])->name('warehouse.revaluasi-stock.stock-opname.report.print.detail');
                    });
                });
            });

            Route::prefix('report')->group(function () {
                Route::get('/stock-status', [WarehouseReportStockStatus::class, 'index'])->name('warehouse.report.stock-status');
                Route::get('/stock-detail', [WarehouseReportStockDetail::class, 'index'])->name('warehouse.report.stock-detail');
                Route::get('/kartu-stok', [WarehouseReportKartuStock::class, 'index'])->name('warehouse.report.kartu-stock');
                Route::get('/histori-perubahan-master-data', [WarehouseReportHistoriPerubahanMasterBarang::class, 'index'])->name('warehouse.report.histori-perubahan-master-data');
            });
        });

        Route::prefix('procurement')->group(function () {
            Route::prefix('purchase-request')->group(function () {
                Route::prefix('pharmacy')->group(function () {
                    Route::get('/', [ProcurementPurchaseRequestPharmacyController::class, 'index'])->name('procurement.purchase-request.pharmacy');
                    Route::get('/create', [ProcurementPurchaseRequestPharmacyController::class, 'create'])->name('procurement.purchase-request.pharmacy.create');
                    Route::get('/print/{id}', [ProcurementPurchaseRequestPharmacyController::class, 'print'])->name('procurement.purchase-request.pharmacy.print');
                    Route::get('/edit/{id}', [ProcurementPurchaseRequestPharmacyController::class, 'edit'])->name('procurement.purchase-request.pharmacy.edit');
                });

                Route::prefix('non-pharmacy')->group(function () {
                    Route::get('/', [ProcurementPurchaseRequestNonPharmacyController::class, 'index'])->name('procurement.purchase-request.non-pharmacy');
                    Route::get('/create', [ProcurementPurchaseRequestNonPharmacyController::class, 'create'])->name('procurement.purchase-request.non-pharmacy.create');
                    Route::get('/print/{id}', [ProcurementPurchaseRequestNonPharmacyController::class, 'print'])->name('procurement.purchase-request.non-pharmacy.print');
                    Route::get('/edit/{id}', [ProcurementPurchaseRequestNonPharmacyController::class, 'edit'])->name('procurement.purchase-request.non-pharmacy.edit');
                });
            });

            Route::prefix('approval-pr')->group(function () {
                Route::prefix('pharmacy')->group(function () {
                    Route::get('/', [ProcurementPRApprovalPharmacy::class, 'index'])->name('procurement.approval-pr.pharmacy');
                    Route::get('/print/{id}', [ProcurementPRApprovalPharmacy::class, 'print'])->name('procurement.approval-pr.pharmacy.print');
                    Route::get('/edit/{id}', [ProcurementPRApprovalPharmacy::class, 'edit'])->name('procurement.approval-pr.pharmacy.edit');
                });

                Route::prefix('non-pharmacy')->group(function () {
                    Route::get('/', [ProcurementPRApprovalNonPharmacy::class, 'index'])->name('procurement.approval-pr.non-pharmacy');
                    Route::get('/print/{id}', [ProcurementPRApprovalNonPharmacy::class, 'print'])->name('procurement.approval-pr.non-pharmacy.print');
                    Route::get('/edit/{id}', [ProcurementPRApprovalNonPharmacy::class, 'edit'])->name('procurement.approval-pr.non-pharmacy.edit');
                });
            });

            Route::prefix('purchase-order')->group(function () {
                Route::prefix('pharmacy')->group(function () {
                    Route::get('/', [ProcurementPurchaseOrderPharmacyController::class, 'index'])->name('procurement.purchase-order.pharmacy');
                    Route::get('/create', [ProcurementPurchaseOrderPharmacyController::class, 'create'])->name('procurement.purchase-order.pharmacy.create');
                    Route::get('/print/{id}', [ProcurementPurchaseOrderPharmacyController::class, 'print'])->name('procurement.purchase-order.pharmacy.print');
                    Route::get('/edit/{id}', [ProcurementPurchaseOrderPharmacyController::class, 'edit'])->name('procurement.purchase-order.pharmacy.edit');
                });

                Route::prefix('non-pharmacy')->group(function () {
                    Route::get('/', [ProcurementPurchaseOrderNonPharmacyController::class, 'index'])->name('procurement.purchase-order.non-pharmacy');
                    Route::get('/create', [ProcurementPurchaseOrderNonPharmacyController::class, 'create'])->name('procurement.purchase-order.non-pharmacy.create');
                    Route::get('/print/{id}', [ProcurementPurchaseOrderNonPharmacyController::class, 'print'])->name('procurement.purchase-order.non-pharmacy.print');
                    Route::get('/edit/{id}', [ProcurementPurchaseOrderNonPharmacyController::class, 'edit'])->name('procurement.purchase-order.non-pharmacy.edit');
                });
            });

            Route::prefix('approval-po')->group(function () {
                Route::prefix('pharmacy')->group(function () {
                    Route::get('/', [ProcurementPOApprovalPharmacy::class, 'index'])->name('procurement.approval-po.pharmacy');
                    Route::get('/edit/{id}', [ProcurementPOApprovalPharmacy::class, 'edit'])->name('procurement.approval-po.pharmacy.edit');
                });

                Route::prefix('non-pharmacy')->group(function () {
                    Route::get('/', [ProcurementPOApprovalNonPharmacy::class, 'index'])->name('procurement.approval-po.non-pharmacy');
                    Route::get('/edit/{id}', [ProcurementPOApprovalNonPharmacy::class, 'edit'])->name('procurement.approval-po.non-pharmacy.edit');
                });

                Route::prefix('ceo')->group(function () {
                    Route::get('/', [ProcurementPOApprovalCEO::class, 'index'])->name('procurement.approval-po.ceo');
                    Route::get('/edit/{type}/{id}', [ProcurementPOApprovalCEO::class, 'edit'])->name('procurement.approval-po.ceo.edit');
                });
            });

            Route::prefix('setup')->group(function () {
                Route::get('supplier', [ProcurementSetupSupplier::class, 'index'])->name('procurement.setup.supplier');
            });
        });

        Route::prefix('igd')->group(function () {
            Route::get('/daftar-pasien', [IGDController::class, 'index'])
                ->name('igd.daftar-pasien');

            Route::post('/daftar-pasien/filter', [IGDController::class, 'index'])
                ->name('igd.daftar-pasien.filter');

            Route::post('/daftar-pasien', [TriageController::class, 'store'])
                ->name('igd.triage.store');

            Route::get('/triage/{id}', [TriageController::class, 'get'])
                ->name('igd.triage.get');

            Route::get('/catatan-medis', [ERMController::class, 'catatanMedis'])
                ->name('igd.catatan-medis');

            Route::prefix('/laporan')->group(function () {
                Route::get('/', [IGDController::class, 'reprotIGD'])
                    ->name('igd.reports');

                Route::get('rekap-per-dokter', [IGDController::class, 'rekapPerDokter'])
                    ->name('igd.reports.rekap-per-dokter');
            });
        });

        Route::prefix('rawat-inap')->group(function () {
            Route::get('/daftar-pasien', [IGDController::class, 'index'])
                ->name('rawat-inap.daftar-pasien');

            Route::get('/catatan-medis', [ERMController::class, 'catatanMedis'])
                ->name('rawat-inap.catatan-medis');

            Route::prefix('/reports')->group(function () {
                Route::get('rawat-inap', [IGDController::class, 'reprotIGD'])
                    ->name('rawat-inap.reports');

                Route::get('laporan-per-tanggal', [IGDController::class, 'reportPerTanggal'])
                    ->name('rawat-inap.reports.per-tanggal');

                Route::get('transfer', [IGDController::class, 'reportTransfer'])
                    ->name('rawat-inap.reports.transfer');

                Route::get('pasien-aktif', [IGDController::class, 'reportPasienAktif'])
                    ->name('rawat-inap.reports.pasien-aktif');
            });
        });

        Route::prefix('vk')->name('vk.')->group(function () {
            // Main page
            Route::get('/daftar-pasien', [PersalinanController::class, 'index'])
                ->name('daftar-pasien');

            // DataTable data
            Route::get('/get-data', [PersalinanController::class, 'getData'])
                ->name('get-data');

            // Tambahkan ini di grup route Anda
            Route::get('data-bayi/{order_id}', [persalinanController::class, 'dataBayi'])->name('bayi.index');

            // Master data for dropdowns
            Route::get('/master/{registrationId}', [PersalinanController::class, 'getMasterData'])
                ->name('master.data');

            // Tindakan data
            Route::get('/tindakan/{registrationId}', [PersalinanController::class, 'getTindakanData'])
                ->name('tindakan.data');

            // Order CRUD operations
            Route::prefix('order')->name('order.')->group(function () {
                Route::post('/', [PersalinanController::class, 'store'])->name('store');
                Route::get('/{id}', [PersalinanController::class, 'show'])->name('show');
                Route::get('/{id}/edit', [PersalinanController::class, 'edit'])->name('edit');
                Route::put('/{id}', [PersalinanController::class, 'update'])->name('update');
                Route::delete('/{id}', [PersalinanController::class, 'deleteOrder'])->name('delete');
                Route::patch('/{id}/status', [PersalinanController::class, 'changeStatus'])->name('change-status');
                Route::get('/{registrationId}/data', [PersalinanController::class, 'getOrderData'])->name('data');
            });

            // Reports (if needed)
            Route::prefix('reports')->name('reports.')->group(function () {
                Route::get('/daily', [PersalinanController::class, 'dailyReport'])->name('daily');
                Route::get('/monthly', [PersalinanController::class, 'monthlyReport'])->name('monthly');
                Route::get('/export/{type}', [PersalinanController::class, 'exportReport'])->name('export');
            });

            // Master data management (optional)
            Route::prefix('master')->name('master.')->group(function () {
                // Kategori Persalinan
                Route::resource('kategori', 'KategoriPersalinanController');

                // Tipe Persalinan
                Route::resource('tipe', 'TipePersalinanController');

                // Persalinan/Tindakan
                Route::resource('persalinan', 'PersalinanMasterController');
            });
        });

        Route::prefix('vk/bayi')->name('bayi.')->group(function () {
            // Route untuk mengambil data bayi via AJAX untuk ditampilkan di tabel
            Route::get('data/{order_persalinan_id}', [BayiController::class, 'getDataForOrder'])->name('data');
            Route::get('get-doctors', [BayiController::class, 'getDoctors'])->name('get_doctors');
            // Route::get('print/{bayi}', [BayiController::class, 'printCertificate'])->name('print');
            Route::get('{bayi}/print', [BayiController::class, 'printCertificate'])->name('print_certificate');
            Route::get('/get-beds', [BayiController::class, 'getDataBed'])->name('get_beds');
            Route::get('/get-kelas-rawat', [BayiController::class, 'getKelasRawat'])->name('get_kelas_rawat');
            Route::get('/{order}/bayi-popup', [BayiController::class, 'showBayiPopup'])->name('popup');

            Route::resource('/', BayiController::class)->parameters(['' => 'bayi'])->except(['index', 'create', 'edit']);
        });

        Route::prefix('ok')->group(function () {
            Route::get('/daftar-pasien', [OperasiController::class, 'index'])->name('ok.daftar-pasien');
            Route::get('/prosedure/{orderId}', [OperasiController::class, 'prosedure'])->name('ok.prosedure');
            Route::get('/prosedur/{order}/create', [OperasiController::class, 'createProsedur'])->name('ok.prosedur.create');
            Route::post('/prosedur/store', [OperasiController::class, 'storeProsedur'])->name('ok.prosedur.store');
            Route::get('/prosedur/get-jenis-by-kategori/{kategoriId}', [OperasiController::class, 'getJenisByKategori'])->name('ok.prosedur.get-jenis-by-kategori');
            Route::get('/prosedur/get-tindakan-by-jenis/{jenisId}', [OperasiController::class, 'getTindakanByJenis'])->name('ok.prosedur.get-tindakan-by-jenis');
            // Tambahkan route ini di dalam group route operasi
            Route::delete('/prosedur/{prosedurId}', [OperasiController::class, 'deleteProsedur'])->name('ok.prosedur.delete');

            Route::prefix('reports')->group(function () {
                Route::get('order-pasien', [IGDController::class, 'orderPasien'])
                    ->name('ok.reports.order-pasien');

                Route::get('rekap-kunjungan', [IGDController::class, 'rekapKunjungan'])
                    ->name('ok.reports.rekap-kunjungan');

                Route::get('10-besar-tindakan', [IGDController::class, '10BesarTindakan'])
                    ->name('ok.reports.10-besar-tindakan');
            });
        });

        Route::prefix('radiologi')->group(function () {
            Route::get('list-order', [RadiologiController::class, 'index'])
                ->name('radiologi.list-order');

            Route::get('simulasi-harga', [RadiologiController::class, 'simulasiHarga'])
                ->name('radiologi.simulasi-harga');

            Route::get('template-hasil', [RadiologiController::class, 'templateHasil'])
                ->name('radiologi.template-hasil');

            Route::get('laporan', [RadiologiController::class, 'report'])
                ->name('radiologi.report');

            Route::get('laporan-view/{fromDate}/{endDate}/{tipe_rawat}/{group_parameter}/{penjamin}/{radiografer}', [RadiologiController::class, 'reportView'])
                ->name('radiologi.report.view');

            Route::get('nota-order/{id}', [RadiologiController::class, 'notaOrder'])
                ->name('radiologi.nota-order');

            Route::get('hasil-order/{id}', [RadiologiController::class, 'hasilOrder'])
                ->name('radiologi.hasil-order');

            Route::get('label-order/{id}', [RadiologiController::class, 'labelOrder'])
                ->name('radiologi.label-order');

            Route::get('edit-order/{id}', [RadiologiController::class, 'editOrder'])
                ->name('radiologi.edit-order');

            Route::get('edit-hasil-parameter/{id}', [RadiologiController::class, 'editHasilParameter'])
                ->name('radiologi.edit-hasil-parameter');

            Route::get('order', [RadiologiController::class, 'order'])
                ->name('radiologi.order');

            Route::get('popup/pilih-pasien/{poli}', [RadiologiController::class, 'popupPilihPasien'])
                ->name('radiologi.popup.pilih-pasien');
        });

        Route::prefix('laboratorium')->group(function () {
            Route::get('list-order', [LaboratoriumController::class, 'index'])->name('laboratorium.list-order');
            Route::get('label-order/{id}', [LaboratoriumController::class, 'labelOrder'])->name('laboratorium.label-order');
            Route::get('hasil-order/{id}', [LaboratoriumController::class, 'hasilOrder'])->name('laboratorium.hasil-order');
            Route::get('edit-order/{id}', [LaboratoriumController::class, 'editOrder'])->name('laboratorium.edit-order');
            Route::get('nota-order/{id}', [LaboratoriumController::class, 'notaOrder'])->name('laboratorium.nota-order');
            Route::get('simulasi-harga', [LaboratoriumController::class, 'simulasiHarga'])->name('laboratorium.simulasi-harga');
            Route::get('order', [LaboratoriumController::class, 'order'])->name('laboratorium.order');
            Route::get('laporan/parameter-pemeriksaan', [LaboratoriumController::class, 'reportParameter'])->name('laboratorium.report.parameter');
            Route::get('laporan/pasien-per-pemeriksaan', [LaboratoriumController::class, 'reportPatient'])->name('laboratorium.report.patient');
            Route::get('popup/pilih-pasien/{poli}', [LaboratoriumController::class, 'popupPilihPasien'])->name('laboratorium.popup.pilih-pasien');
            Route::get('laporan-parameter-view/{fromDate}/{endDate}/{tipe_rawat}/{penjamin}', [LaboratoriumController::class, 'reportParameterView'])->name('laboratorium.report-parameter.view');
            Route::get('laporan-pasien-view/{fromDate}/{endDate}/{tipe_rawat}/{penjamin}/{parameter}', [LaboratoriumController::class, 'reportPatientView'])->name('laboratorium.report-patient.view');
        });

        Route::prefix('dokter')->group(function () {
            Route::get('/daftar-pasien', [DokterController::class, 'index'])
                ->name('dokter.daftar-pasien');

            Route::get('/template-soap', [DokterController::class, 'templateSOAP'])
                ->name('dokter.template-soap');
        });

        Route::prefix('gizi')->group(function () {
            Route::prefix('daftar-pasien')->group(function () {
                Route::get('list-pasien', [GiziController::class, 'index'])
                    ->name('gizi.daftar-pasien.list-pasien');

                Route::get('list-order-gizi', [OrderGiziController::class, 'index'])
                    ->name('gizi.daftar-order.list-order-gizi');
            });

            Route::prefix('popup')->group(function () {
                Route::get('/order/{untuk}/{registration_id}', [OrderGiziController::class, 'create'])->name('gizi.popup.order');
                Route::get('/pilih-diet/{registration_id}', [JamMakanGiziController::class, 'create'])->name('gizi.popup.pilih-diet');
                Route::get('/label/{id_order}', [OrderGiziController::class, 'label'])->name('gizi.popup.label');
                Route::get('/bulk-label/{order_ids}', [OrderGiziController::class, 'bulk_label'])->name('gizi.popup.bulk-label');
                Route::get('/print-nota/{order_ids}', [OrderGiziController::class, 'print_nota'])->name('gizi.popup.print-nota');
                Route::get('/edit/{order_ids}', [OrderGiziController::class, 'edit'])->name('gizi.popup.edit-order');
            });

            Route::prefix('reports')->group(function () {
                Route::get('/', [GiziController::class, 'reports'])
                    ->name('gizi.reports');

                Route::get('/view/{startDate}/{endDate}/{kategori_id}/{food_id}/{status_payment}/{waktu_makan}/{untuk}', [GiziController::class, 'reports_view'])
                    ->name('gizi.reports.view');
            });

            Route::prefix('master-data')->group(function () {
                Route::get('kategori-menu', [KategoriGiziController::class, 'index'])->name('gizi.master-data.kategori-menu');
                Route::get('daftar-makanan', [MakananGiziController::class, 'index'])->name('gizi.master-data.daftar-makanan');
                Route::get('daftar-menu', [MenuGiziController::class, 'index'])->name('gizi.master-data.daftar-menu');
                Route::get('jam-makan', [JamMakanGiziController::class, 'index'])->name('gizi.master-data.jam-makan');
            });
        });

        Route::prefix('farmasi')->group(function () {
            Route::prefix('transaksi-resep')->group(function () {
                Route::get('/', [FarmasiResepController::class, 'index'])->name('farmasi.transaksi-resep');
                Route::get('/create', [FarmasiResepController::class, 'create'])->name('farmasi.transaksi-resep.create');
                Route::get('/edit/{id}', [FarmasiResepController::class, 'edit'])->name('farmasi.transaksi-resep.edit');
                Route::get('popup/pilih-pasien/{poli}', [FarmasiResepController::class, 'popupPilihPasien'])->name('farmasi.transaksi-resep.popup.pilih-pasien');
                Route::get('popup/pilih-dokter', [FarmasiResepController::class, 'popupPilihDokter'])->name('farmasi.transaksi-resep.popup.pilih-dokter');
                Route::get('popup/resep-elektronik', [FarmasiResepController::class, 'popupResepElektronik'])->name('farmasi.transaksi-resep.popup.resep-elektronik');
                Route::get('popup/resep-harian', [FarmasiResepController::class, 'popupResepHarian'])->name('farmasi.transaksi-resep.popup.resep-harian');
                Route::get('popup/telaah-resep-raw/{json}', [FarmasiResepController::class, 'telaahResepRaw'])->name('farmasi.transaksi-resep.popup.telaah-resep-raw');
                Route::get('popup/telaah-resep/{id}', [FarmasiResepController::class, 'telaahResep'])->name('farmasi.transaksi-resep.popup.telaah-resep');

                Route::prefix('print')->group(function () {
                    Route::get('/e-tiket/{id}', [FarmasiResepController::class, 'print_e_tiket'])->name('farmasi.transaksi-resep.print.e-tiket');
                    Route::get('/e-tiket-ranap/{id}', [FarmasiResepController::class, 'print_e_tiket_ranap'])->name('farmasi.transaksi-resep.print.e-tiket-ranap');
                    Route::get('/penjualan/{id}', [FarmasiResepController::class, 'print_penjualan'])->name('farmasi.transaksi-resep.print.penjualan');
                    Route::get('/resep/{id}', [FarmasiResepController::class, 'print_resep'])->name('farmasi.transaksi-resep.print.resep');
                });
            });

            Route::prefix('laporan')->group(function () {
                Route::prefix('embalase')->group(function () {
                    Route::get('/', [FarmasiReportEmbalase::class, 'index'])->name('farmasi.laporan.embalase');
                    Route::get('/view/{startDate}/{endDate}/{gudang_id}/{tipe}', [FarmasiReportEmbalase::class, 'show'])->name('farmasi.laporan.embalase.show');
                });

                Route::prefix('klaim-kronis')->group(function () {
                    Route::get('/', [FarmasiReportKronis::class, 'index'])->name('farmasi.laporan.kronis');
                    Route::get('/view/{startDate}/{endDate}/{tipe}/{doctor_id}/{departement_id}/{kelas_rawat_id}/{nama_obat}', [FarmasiReportKronis::class, 'show'])->name('farmasi.laporan.kronis.show');
                });

                Route::prefix('klaim-dispensing')->group(function () {
                    Route::get('/', [FarmasiReportDispensing::class, 'index'])->name('farmasi.laporan.dispensing');
                    Route::get('/view/{startDate}/{endDate}/{tipe}/{doctor_id}/{departement_id}/{kelas_rawat_id}/{nama_obat}', [FarmasiReportDispensing::class, 'show'])->name('farmasi.laporan.dispensing.show');
                });

                Route::prefix('penjualan')->group(function () {
                    Route::get('/', [FarmasiReportPenjualan::class, 'index'])->name('farmasi.laporan.penjualan');
                    Route::get('/view/{type}/{btoa}', [FarmasiReportPenjualan::class, 'show'])->name('farmasi.laporan.penjualan.show');
                });

                Route::prefix('rekap-penjualan')->group(function () {
                    Route::get('/', [FarmasiReportRekapPenjualan::class, 'index'])->name('farmasi.laporan.rekap-penjualan');
                    Route::get('/view/{tipe}/{btoa}', [FarmasiReportRekapPenjualan::class, 'show'])->name('farmasi.laporan.rekap-penjualan.show');
                    Route::get('/view-detail-date/{barang_id}/{date}/{doctor_id}', [FarmasiReportRekapPenjualan::class, 'showDetailDate'])->name('farmasi.laporan.rekap-penjualan.show-detail-date');
                    Route::get('/view-detail-month/{barang_id}/{month}/{year}/{doctor_id}', [FarmasiReportRekapPenjualan::class, 'showDetailMonth'])->name('farmasi.laporan.rekap-penjualan.show-detail-month');
                });
            });

            Route::prefix('retur-resep')->group(function () {
                Route::get('/', [FarmasiReturResepController::class, 'index'])->name('farmasi.retur-resep');
                Route::get('/create', [FarmasiReturResepController::class, 'create'])->name('farmasi.retur-resep.create');
                Route::get('/print/{id}', [FarmasiReturResepController::class, 'print'])->name('farmasi.retur-resep.print');
            });

            Route::prefix('response-time')->group(function () {
                Route::get('/', [FarmasiResepResponseController::class, 'index'])->name('farmasi.response-time');
                Route::prefix('popup')->group(function () {
                    Route::get('/report/{json}', [FarmasiResepResponseController::class, 'report'])->name('farmasi.response-time.print');
                    Route::get('/telaah-resep/{id}', [FarmasiResepResponseController::class, 'telaahResep'])->name('farmasi.response-time.telaah-resep');
                });
            });

            Route::prefix('antrian-farmasi')->group(function () {
                Route::get('/', [FarmasiPlasma::class, 'index'])->name('farmasi.antrian-farmasi.index');
                Route::get('/plasma', [FarmasiPlasma::class, 'plasma'])->name('farmasi.antrian-farmasi.plasma');
            });
        });

        // Route::prefix('warehouse')->group(function () {
        //     Route::prefix('stock-request')->group(function () {
        //         Route::get('farmasi', [StockRequestController::class, 'farmasi'])->name('warehouse.stock-request.farmasi');
        //         Route::get('non-farmasi', [StockRequestController::class, 'nonFarmasi'])->name('warehouse.stock-request.non-farmasi');
        //     });

        //     Route::prefix('purchase-request')->group(function () {
        //         Route::get('farmasi', [PurchaseRequestController::class, 'farmasi'])->name('warehouse.purchase-request.farmasi');
        //         Route::get('non-farmasi', [PurchaseRequestController::class, 'nonFarmasi'])->name('warehouse.purchase-request');
        //     });

        //     Route::prefix('penerimaan-barang')->group(function () {
        //         Route::get('farmasi', [PenerimaanBarangController::class, 'farmasi'])->name('warehouse.penerimaan-barang.farmasi');
        //         Route::get('non-farmasi', [PenerimaanBarangController::class, 'nonFarmasi'])->name('warehouse.penerimaan-barang.non-farmasi');
        //         Route::get('retur-barang', [PenerimaanBarangController::class, 'returBarang'])->name('warehouse.penerimaan-barang.retur-barang');
        //         Route::get('report', [PenerimaanBarangController::class, 'report'])->name('warehouse.penerimaan-barang.report');
        //     });

        //     Route::prefix('distribusi-barang')->group(function () {
        //         Route::get('farmasi', [DistribusiBarangController::class, 'farmasi'])->name('warehouse.distribusi-barang.farmasi');
        //         Route::get('nonFarmasi', [DistribusiBarangController::class, 'nonFarmasi'])->name('warehouse.distribusi-barang.non-farmasi');
        //         Route::get('report', [DistribusiBarangController::class, 'report'])->name('warehouse.distribusi-barang.report');
        //     });

        //     Route::prefix('unit-cost')->group(function () {
        //         Route::get('farmasi', [UnitCostController::class, 'farmasi'])->name('warehouse.unit-cost.farmasi');
        //         Route::get('non-farmasi', [UnitCostController::class, 'nonFarmasi'])->name('warehouse.unit-cost.non-farmasi');
        //         Route::get('report', [UnitCostController::class, 'report'])->name('warehouse.unit-cost.report');
        //     });

        //     Route::prefix('revaluasi-stok')->group(function () {
        //         Route::get('stok-adjustment', [RevaluasiStokController::class, 'stokAdjustment'])->name('warehouse.revaluasi-stok.stok-adjustment');
        //         Route::get('stok-opname', [RevaluasiStokController::class, 'stokOpname'])->name('warehouse.revaluasi-stok.stok-opname');
        //     });

        //     Route::prefix('reports')->group(function () {
        //         Route::get('stok-status', [ReportWarehouseController::class, 'stokStatus'])->name('warehouse.reports.stok-status');
        //         Route::get('stok-detail', [ReportWarehouseController::class, 'stokDetail'])->name('warehouse.reports.stok-detail');
        //         Route::get('kartu-stok', [ReportWarehouseController::class, 'kartuStok'])->name('warehouse.reports.kartu-stok');
        //         Route::get('report-slow-fast-moving', [ReportWarehouseController::class, 'reportSlowFastMoving'])->name('warehouse.reports.report-slow-fast-moving');
        //         Route::get('report-expire-date', [ReportWarehouseController::class, 'reportExpireDate'])->name('warehouse.reports.report-expire-date');
        //         Route::get('history-perubahan-master-data', [ReportWarehouseController::class, 'historyPerubahanMasterData'])->name('warehouse.reports.history-perubahan-master-data');
        //     });

        //     Route::prefix('master-data')->group(function () {
        //         Route::get('barang-farmasi', [MasterDataWarehouseController::class, 'barangFarmasi'])->name('warehouse.master-data.barang-farmasi');
        //         Route::get('barang-non-farmasi', [MasterDataWarehouseController::class, 'barangNonFarmasi'])->name('warehouse.master-data.barang-non-farmasi');
        //         Route::get('supplier', [MasterDataWarehouseController::class, 'supplier'])->name('warehouse.master-data.supplier');
        //         Route::get('master-gudang', [MasterDataWarehouseController::class, 'masterGudang'])->name('warehouse.master-data.master-gudang');
        //         Route::get('golongan-barang', [MasterDataWarehouseController::class, 'golonganBarang'])->name('warehouse.master-data.golongan-barang');
        //         Route::get('kategori-barang', [MasterDataWarehouseController::class, 'kategori-barang'])->name('warehouse.master-data.kategori-barang');
        //         Route::get('kelompok-barang', [MasterDataWarehouseController::class, 'kelompokBarang'])->name('warehouse.master-data.kelompok-barang');
        //         Route::get('satuan-barang', [MasterDataWarehouseController::class, 'satuanBarang'])->name('warehouse.master-data.satuan-barang');
        //         Route::get('pabrik', [MasterDataWarehouseController::class, 'pabrik'])->name('warehouse.master-data.pabrik');
        //         Route::get('setup-min-max-stok', [MasterDataWarehouseController::class, 'setupMinMaxStok'])->name('warehouse.master-data.setup-min-max-stok');
        //         Route::get('rak-penyimpanan', [MasterDataWarehouseController::class, 'rakPenyimpanan'])->name('warehouse.master-data.rak-penyimpanan');
        //         Route::get('barang-per-rak-gudang', [MasterDataWarehouseController::class, 'barangPerRakGudang'])->name('warehouse.master-data.barang-per-rak-gudang');
        //         Route::get('template-paket', [MasterDataWarehouseController::class, 'templatePaket'])->name('warehouse.master-data.template-paket');
        //     });
        // });

        Route::prefix('depo')->group(function () {
            Route::prefix('stok-request')->group(function () {
                Route::get('farmasi', [StokRequestController::class, 'farmasi'])
                    ->name('depo.stok-request.farmasi');

                Route::get('non-farmasi', [StokRequestController::class, 'nonFarmasi'])
                    ->name('depo.stok-request-non-farmasi');
            });

            Route::prefix('distribusi-barang')->group(function () {
                Route::get('farmasi', [StokRequestController::class, 'farmasi'])
                    ->name('depo.distribusi-barang.farmasi');

                Route::get('non-farmasi', [StokRequestController::class, 'nonFarmasi'])
                    ->name('depo.distribusi-barang.non-farmasi');
            });

            Route::prefix('unit-cost')->group(function () {
                Route::get('farmasi', [DepoUnitCostController::class, 'farmasi'])
                    ->name('depo.unit-cost.farmasi');

                Route::get('nonFarmasi', [DepoUnitCostController::class, 'farmasi'])
                    ->name('depo.unit-cost.non-farmasi');
            });
        });

        Route::prefix('insiden')->group(function () {
            Route::get('/', [InsidenController::class, 'index'])
                ->name('insiden');
        });

        // Route::prefix('procurement')->group(function () {
        //     Route::prefix('purchase-request')->group(function () {
        //         Route::get('farmasi', [ProcurementPurchaseRequestController::class, 'farmasi'])->name('procurement.purchase-request.farmasi');
        //         Route::get('non-farmasi', [ProcurementPurchaseRequestController::class, 'nonFarmasi'])->name('procurement.purchase-request.non-farmasi');
        //         Route::get('closed-farmasi', [ProcurementPurchaseRequestController::class, 'closedFarmasi'])->name('procurement.purchase-request.closed-farmasi');
        //         Route::get('closed-non-farmasi', [ProcurementPurchaseRequestController::class, 'closedNonFarmasi'])->name('procurement.purchase-request.closed-non-farmasi');
        //     });

        //     Route::prefix('approval-pr')->group(function () {
        //         Route::get('farmasi', [ApprovalPRController::class, 'farmasi'])->name('procurement.approval-pr.farmasi');
        //         Route::get('non-farmasi', [ApprovalPRController::class, 'nonFarmasi'])->name('procurement.approval-pr.non-farmasi');
        //     });

        //     Route::prefix('purchase-order')->group(function () {
        //         Route::get('farmasi', [PurchaseOrderController::class, 'farmasi'])->name('procurement.purchase-order.farmasi');
        //         Route::get('non-farmasi', [PurchaseOrderController::class, 'nonFarmasi'])->name('procurement.purchase-order.non-farmasi');
        //     });

        //     Route::prefix('approval-po')->group(function () {
        //         Route::get('farmasi', [ApprovalPOController::class, 'farmasi'])->name('procurement.approval-po.farmasi');
        //         Route::get('non-farmasi', [ApprovalPOController::class, 'nonFarmasi'])->name('procurement.approval-po.non-farmasi');
        //         Route::get('ceo', [ApprovalPOController::class, 'ceo'])->name('procurement.approval-po.ceo');
        //     });

        //     Route::prefix('pengajuan-cash-advance')->group(function () {
        //         Route::get('/', [ApprovalPOController::class, 'pengajuanCashAdvance'])->name('procurement.pengajuan-cash-advance');
        //     });

        //     Route::prefix('setup')->group(function () {
        //         Route::get('supplier', [SetupController::class, 'supplier'])->name('procurement.setup.supplier');
        //         Route::get('price-list-supplier', [SetupController::class, 'priceListSupplier'])->name('procurement.setup.price-list-supplier');
        //     });
        // });

        /*
        |--------------------------------------------------------------------------
        | BPJS Routes
        |--------------------------------------------------------------------------
        |
        | Group of routes for the "BPJS" section of the SIMRS application.
        | Includes bridging Vclaim, registration, and claim management.
        |
        */
        Route::prefix('bpjs')->group(function () {
            Route::prefix('bridging-vclaim')->group(function () {
                Route::get('list-registrasi-sep', [BridgingVclaimController::class, 'listRegistrasiSEP'])
                    ->name('bpjs.bridging-vclaim.list-registrasi-sep');

                Route::get('persetujuan-sep', [BridgingVclaimController::class, 'persetujuanSEP'])
                    ->name('bpjs.bridging-vclaim.persetujuan-sep');

                Route::get('rujukan', [BridgingVclaimController::class, 'rujukan'])
                    ->name('bpjs.bridging-vclaim.rujukan');

                Route::get('lembar-pengajuan-klaim', [BridgingVclaimController::class, 'lembarPengajuanKlaim'])
                    ->name('bpjs.bridging-vclaim.lembar-pengajuan-klaim');

                Route::get('detail-sep', [BridgingVclaimController::class, 'detailSEP'])
                    ->name('bpjs.bridging-vclaim.detail-sep');

                Route::get('detail-sep', [BridgingVclaimController::class, 'detailSEP'])
                    ->name('bpjs.bridging-vclaim.detail-sep');
            });
        });

        // Route::prefix('kasir')->group(function() {
        //     Route::get('tagihan-pasien', [KasirController::class, 'index'])->name('laboratorium.list-order');
        //     Route::get('transaksi-non-pasien', [KasirController::class, 'index'])->name('laboratorium.list-order');
        //     Route::get('setoran-kasir', [KasirController::class, 'index'])->name('laboratorium.list-order');
        //     Route::prefix('reports')->group(function() {
        //     Route::get('penerimaan-kasir', [KasirController::class, 'parametrPemeriksaan'])->name('laboratorium.parameter-pemeriksaan');
        //     Route::get('rekap-penerimaan-kasir', [KasirController::class, 'pasienPerPemeriksaan'])->name('laboratorium.psdirn-per-permintaan');
        //     Route::get('laboratorium', [KasirController::class, 'parametrPemeriksaan'])->name('laboratorium.parameter-pemeriksaan');
        //     });
        //     Route::get('simulasi-harga', [IGDController::class, 'simulasiHarga'])->name('laboratorium.simulasi-harga');
        // });

        Route::prefix('kepustakaan')->group(function () {
            Route::get('/list', [KepustakaanController::class, 'index'])->name('kepustakaan.index');
            Route::get('/{id}', [KepustakaanController::class, 'showFolder'])->name('kepustakaan.folder');
            Route::get('/download/{id}', [KepustakaanController::class, 'downloadFile'])->name('kepustakaan.download');
        });

        Route::prefix('kasir')->group(function () {
            Route::get('/tagihan-pasien', [TagihanPasienController::class, 'index'])->name('tagihan.pasien.index');
            Route::post('/tagihan-pasien/search', [TagihanPasienController::class, 'index'])->name('tagihan.pasien.search');
            Route::put('/tagihan-pasien/update-disc/{id}', [TagihanPasienController::class, 'updateDisc'])->name('tagihan.pasien.diskon');
            Route::post('/tagihan-pasien', [TagihanPasienController::class, 'store'])->name('tagihan.pasien.store');
            Route::get('/get-nominal-awal/{id}', [TagihanPasienController::class, 'getNominalAwal'])->name('tagihan.pasien.get.nominal');
            Route::get('/tagihan-pasien/{id}', [TagihanPasienController::class, 'detailTagihan'])->name('tagihan.pasien.detail');
            Route::delete('/tagihan-pasien/{id}', [TagihanPasienController::class, 'destroyTagihan'])->name('tagihan.pasien.destroy');
            Route::get('/tagihan-pasien/data/{id}', [TagihanPasienController::class, 'getData'])->name('tagihan.pasien.data');
            Route::put('/tagihan-pasien/update/{id}', [TagihanPasienController::class, 'updateTagihan'])->name('tagihan.pasien.update');
            Route::get('/bilingan/data/{id}/', [BilinganController::class, 'getData'])->name('bilingan.pasien.data');
            Route::put('/bilingan/update-status/{id}', [BilinganController::class, 'updateBilinganStatus'])->name('bilingan.update.status');
            Route::get('/down-payment/data/{id}', [BilinganController::class, 'getDownPaymentData'])->name('down.payment.data');
            Route::post('/down-payment', [BilinganController::class, 'storeDownPayment'])->name('down.payment.store');
            Route::delete('/down-payment/{id}', [BilinganController::class, 'destroyDownPayment'])->name('down.payment.destroy');
            Route::post('/pembayaran-tagihan', [BilinganController::class, 'storePembayaranTagihan'])->name('pembayaran.tagihan.store');
            Route::get('/print-bill/{id}', [BilinganController::class, 'printBill'])->name('print.bill');
            Route::get('/print-kwitansi/{id}', [BilinganController::class, 'printKwitansi'])->name('print.kwitansi');
            Route::get('/tagihan-pasien/{id}/tarif', [TagihanPasienController::class, 'getTarifShare']);
        });

        Route::prefix('operasi')->group(function () {
            // Menyimpan Order dari modal di halaman registrasi
            Route::post('order/store', [OperasiController::class, 'storeOrder'])->name('operasi.order.store');
            Route::get('/operasi/{orderId}/detail', [OperasiController::class, 'getOrderDetail']);

            // Halaman utama untuk melihat daftar semua order operasi
            Route::get('list-order', [OperasiController::class, 'listOrder'])->name('operasi.list-order');
            // +++ KODE BARU +++
            // Tambahkan {orderId} untuk menerima parameter dari URL

            // Halaman detail untuk satu order, di mana nanti ada manajemen prosedur, dll.
            Route::get('detail-order/{orderId}', [OperasiController::class, 'show'])->name('operasi.detail-order');

            // Route untuk mencetak dokumen (misal: nota, informed consent, dll)
            Route::get('nota-order/{orderId}', [OperasiController::class, 'notaOrder'])->name('operasi.nota-order');
            Route::get('/api/simrs/get-order-operasi/{registrationId}', [OperasiController::class, 'getOrderOperasi']);
            Route::get('/api/simrs/get-tindakan-operasi/{registrationId}', [OperasiController::class, 'getTindakanOperasi']);
            Route::get('/operasi/order/data/{registrationId}', [OperasiController::class, 'getOrderOperasi'])
                ->name('operasi.order.data');

            Route::get('operasi/prosedur/data/{registrationId}', [OperasiController::class, 'getProsedurData'])->name('operasi.prosedur.data');

            Route::get('/operasi/tindakan/data/{registrationId}', [OperasiController::class, 'getTindakanOperasi'])
                ->name('operasi.tindakan.data');

            Route::delete('/operasi/order/{order}', [OperasiController::class, 'deleteOrder'])->name('operasi.order.delete');
            Route::get('/plasma', [OperasiController::class, 'plasmaView'])->name('ok.plasma');
            // Route::get('data-order/{orderId}', [OperasiController::class, 'getOrderData'])->name('operasi.data-order');
        });

        // Routes untuk Persalinan (tambahkan ke web.php)
        Route::prefix('persalinan')->name('persalinan.')->group(function () {

            // Halaman daftar pasien
            Route::get('/daftar-pasien', [PersalinanController::class, 'index'])
                ->name('index');

            // Data master untuk form - URL diperbaiki
            Route::get('/master-data/{registrationId}', [PersalinanController::class, 'getMasterData'])
                ->name('master-data');

            // Data order berdasarkan registration ID
            Route::get('/order-data/{registrationId}', [PersalinanController::class, 'getOrderData'])
                ->name('order-data');

            // Simpan order baru
            Route::post('/store', [PersalinanController::class, 'store'])
                ->name('store');

            // Detail order
            Route::get('/order/{orderId}', [PersalinanController::class, 'show'])
                ->name('order.show');

            // Hapus order
            Route::delete('/destroy/{orderId}', [PersalinanController::class, 'destroy'])
                ->name('destroy');

            // Data tindakan berdasarkan registration ID
            Route::get('/tindakan-data/{registrationId}', [PersalinanController::class, 'getTindakanData'])
                ->name('tindakan-data');

            // CRUD detail persalinan

        });
    });
});
