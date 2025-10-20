<?php

use App\Http\Controllers\BilinganController;
use App\Http\Controllers\SIMRS\BPJS\WsBPJSController;
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
use App\Http\Controllers\ImportExportController;
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
use App\Http\Controllers\SatuSehat\DashboardSatuSehatController;
use App\Http\Controllers\SatuSehat\DepartmentLocationController;
use App\Http\Controllers\SatuSehat\GeolocationController;
use App\Http\Controllers\SatuSehat\LaporanSummaryController;
use App\Http\Controllers\SatuSehat\PractitionerController;
use App\Http\Controllers\SatuSehat\SatuSehatOrganizationController;
use App\Http\Controllers\SIMRS\BedController;
use App\Http\Controllers\SIMRS\BPJS\BridgingEclaimController;
use App\Http\Controllers\SIMRS\ControlPanelController;
use App\Http\Controllers\SIMRS\BPJS\BridgingVclaimController;
use App\Http\Controllers\SIMRS\BPJS\LaporanController;
use App\Http\Controllers\SIMRS\BPJS\SettingController;
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
use App\Http\Controllers\SIMRS\MergeRMController;
use App\Http\Controllers\SIMRS\Obat\OrderObatController;
use App\Http\Controllers\SIMRS\Operasi\JenisOperasiController;
use App\Http\Controllers\SIMRS\Operasi\KategoriOperasiController;
use App\Http\Controllers\SIMRS\Operasi\OperasiController;
use App\Http\Controllers\SIMRS\Operasi\TindakanOperasiController;
use App\Http\Controllers\SIMRS\Operasi\TipeOperasiController;
use App\Http\Controllers\SIMRS\OtorisasiUserController;
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
use App\Http\Controllers\SIMRS\TarifVisiteDokterController;
use App\Http\Controllers\SIMRS\TindakanMedisController;
use App\Http\Controllers\SIMRS\TipeTransaksiController;
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
    Route::prefix('pendaftaran/{registration_id}')->group(function () {
        Route::get('detail', [RegistrationController::class, 'show'])->name('pendaftaran.detail');
        Route::get('layanan/{layanan}', [RegistrationController::class, 'layanan'])->name('pendaftaran.layanan');

        Route::prefix('order-obat')->name('order-obat.')->group(function () {
            Route::post('/', [OrderObatController::class, 'store'])->name('store');
            Route::put('/{order_obat}', [OrderObatController::class, 'update'])->name('update');
            Route::delete('/{order_obat}', [OrderObatController::class, 'destroy'])->name('destroy');
            Route::get('/fetch-create-form', [OrderObatController::class, 'fetchCreateForm'])->name('fetch-create-form');
            Route::get('/{order_obat}/fetch-edit-form', [OrderObatController::class, 'fetchEditForm'])->name('fetch-edit-form');
        });
    });

    Route::prefix('api')->name('api.')->group(function () {
        Route::get('order-obat-data/{registration_id}', [OrderObatController::class, 'data'])->name('order-obat.data');
        Route::get('search-obat', [OrderObatController::class, 'searchObat'])->name('search-obat');
    });

    Route::get('order-obat/print/{order_obat}', [OrderObatController::class, 'print'])->name('order-obat.print');

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
        Route::put('/{patient:id}/', [PatientController::class, 'update_pendaftaran_pasien'])
            ->name('update.pendaftaran.pasien');

        // Print patient documents
        Route::get('/{patient:id}/print', [PatientController::class, 'print_identitas_pasien'])
            ->name('print.identitas.pasien');
        Route::get('/{patient:id}/print-kartu', [PatientController::class, 'print_kartu_pasien'])
            ->name('print.kartu.pasien');
        Route::get('/{patient:id}/label-rm-pdf', [PatientController::class, 'print_label_rm_pdf'])
            ->name('print.label.rm.pdf');
        Route::get('/{patient:id}/label-rm', [PatientController::class, 'print_label_rm'])
            ->name('print.label.rm');
        Route::get('/{patient:id}/label-gelang-anak', [PatientController::class, 'print_label_gelang_anak'])
            ->name('print.label.gelang.anak');
        Route::get('/{patient:id}/label-gelang-dewasa', [PatientController::class, 'print_label_gelang_dewasa'])
            ->name('print.label.gelang.dewasa');
        Route::get('/{patient:id}/tracer/{registration?}', [PatientController::class, 'print_tracer'])
            ->name('print.tracer');
        Route::get('/{patient:id}/charges-slip/{registration?}', [PatientController::class, 'print_charges_slip'])
            ->name('print.charges.slip');
        Route::get('/{patient:id}/surat-keterangan-lahir/{registration?}', [PatientController::class, 'print_surat_keterangan_lahir'])
            ->name('print.surat.keterangan.lahir');
        Route::get('/{patient:id}/general-consent/{registration?}', [PatientController::class, 'print_general_consent'])
            ->name('print.general.consent');

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

        Route::get('/{registrations:id}/layanan/{layanan}', [RegistrationController::class, 'layanan'])
            ->name('detail.registrasi.pasien.layanan');


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

        // Route untuk menampilkan dan memproses form ubah penjamin
        Route::get('/{registration}/ubah-penjamin', [RegistrationController::class, 'ubahPenjaminView'])->name('registration.ubah-penjamin.view');
        Route::post('/{registration}/ubah-penjamin', [RegistrationController::class, 'ubahPenjaminAction'])->name('registration.ubah-penjamin.action');
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
        })->name('dashboard.simrs');

        Route::prefix('/control-panel')->group(function () {
            Route::prefix('/laboratorium')->group(function () {
                Route::get('/migrasi', [ControlPanelController::class, 'laboratorium'])->name('laboratorium.index');
                Route::post('/export', [LaboratoriumController::class, 'export'])->name('laboratorium.export');
                Route::post('/import', [LaboratoriumController::class, 'import'])->name('laboratorium.import');
            });

            Route::prefix('/nilai-normal')->group(function () {
                Route::get('/migrasi', [ControlPanelController::class, 'nilai_normal'])->name('nilai.normal.index');
                Route::get('/export', [NilaiNormalLaboratoriumController::class, 'export'])->name('nilai.normal.export');
                Route::post('/import', [NilaiNormalLaboratoriumController::class, 'import'])->name('nilai.normal.import');
            });


            Route::prefix('/radiologi')->group(function () {
                Route::get('/migrasi', [ControlPanelController::class, 'radiologi'])->name('radiologi.index');
                Route::post('/export', [RadiologiController::class, 'export'])->name('radiologi.export');
                Route::post('/import', [RadiologiController::class, 'import'])->name('radiologi.import');
            });

            Route::get('/tindakan-rajal', [ControlPanelController::class, 'tindakan_rajal'])->name('control-panel.tindakan-rajal');

            Route::get('/migrasi-tindakan', [TindakanMedisController::class, 'index'])->name('tindakan.migrasi');
            Route::post('/migrasi-tindakan/download', [TindakanMedisController::class, 'export'])->name('tindakan.export');
            Route::post('/migrasi-tindakan/upload', [TindakanMedisController::class, 'import'])->name('tindakan.import');

            Route::prefix('peralatan')->group(function () {
                Route::get('/migrasi', [ControlPanelController::class, 'peralatan'])->name('peralatan.index');
                Route::post('/export', [PeralatanController::class, 'export'])->name('peralatan.export');
                Route::post('/import', [PeralatanController::class, 'import'])->name('peralatan.import');
            });

            Route::prefix('barang-farmasi')->group(function () {
                Route::get('/migrasi', [ControlPanelController::class, 'barangFarmasi'])->name('barang-farmasi.index'); // Asumsi ada method ini di ControlPanelController
                Route::post('/export', [WarehouseBarangFarmasiController::class, 'export'])->name('barang-farmasi.export');
                Route::post('/import', [WarehouseBarangFarmasiController::class, 'import'])->name('barang-farmasi.import');
            });

            Route::prefix('warehouse-pabrik')->group(function () {
                Route::get('/migrasi', [ControlPanelController::class, 'warehousePabrik'])->name('warehouse-pabrik.index');
                Route::post('/export', [WarehousePabrikController::class, 'export'])->name('warehouse-pabrik.export');
                Route::post('/import', [WarehousePabrikController::class, 'import'])->name('warehouse-pabrik.import');
            });

            Route::prefix('manajemen-data')->group(function () {
                Route::get('/import-export', [ImportExportController::class, 'index'])->name('import-export.index');

                // Export Routes
                Route::get('/export/kelas-rawat', [ImportExportController::class, 'exportKelasRawat'])->name('export.kelas-rawat');
                Route::get('/export/rooms', [ImportExportController::class, 'exportRooms'])->name('export.rooms');
                Route::get('/export/beds', [ImportExportController::class, 'exportBeds'])->name('export.beds');

                // Import Routes
                Route::post('/import/kelas-rawat', [ImportExportController::class, 'importKelasRawat'])->name('import.kelas-rawat');
                Route::post('/import/rooms', [ImportExportController::class, 'importRooms'])->name('import.rooms');
                Route::post('/import/beds', [ImportExportController::class, 'importBeds'])->name('import.beds');
            });
        });

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

                // Rute untuk menampilkan halaman utama dan data untuk DataTables
                Route::get('tipe-transaksi', [TipeTransaksiController::class, 'index'])->name('tipe-transaksi.index');
                Route::get('tipe-transaksi/data', [TipeTransaksiController::class, 'data'])->name('tipe-transaksi.data');

                // Rute untuk proses CRUD (API-like)
                Route::post('tipe-transaksi', [TipeTransaksiController::class, 'store'])->name('tipe-transaksi.store');
                Route::get('tipe-transaksi/{id}/edit', [TipeTransaksiController::class, 'edit'])->name('tipe-transaksi.edit');
                Route::put('tipe-transaksi/{id}', [TipeTransaksiController::class, 'update'])->name('tipe-transaksi.update');
                Route::delete('tipe-transaksi/{id}', [TipeTransaksiController::class, 'destroy'])->name('tipe-transaksi.destroy');

                // Otorisasi User Routes
                Route::prefix('otorisasi-user')->name('otorisasi-user.')->group(function () {
                    Route::get('/', [OtorisasiUserController::class, 'index'])->name('index');
                    Route::get('/data', [OtorisasiUserController::class, 'data'])->name('data');
                    Route::post('/', [OtorisasiUserController::class, 'store'])->name('store');
                    Route::get('/{id}/edit', [OtorisasiUserController::class, 'edit'])->name('edit');
                    Route::put('/{id}', [OtorisasiUserController::class, 'update'])->name('update');
                    Route::delete('/{id}', [OtorisasiUserController::class, 'destroy'])->name('destroy');
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

                    Route::get('/nilai-normal-parameter/export', [NilaiNormalLaboratoriumController::class, 'export'])->name('simrs.laboratorium.nilai-normal.export');
                    Route::post('/nilai-normal-parameter/import', [NilaiNormalLaboratoriumController::class, 'import'])->name('simrs.laboratorium.nilai-normal.import');
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

        Route::prefix('warehouse')->name('warehouse.')->group(function () {
            Route::prefix('master-data')->name('master-data.')->group(function () {
                Route::prefix('zat-aktif')->name('zat-aktif.')->group(function () {
                    Route::get('/', [WarehouseZatAktifController::class, 'index'])->name('index');
                    Route::get('/data', [WarehouseZatAktifController::class, 'data'])->name('data');
                    Route::post('/', [WarehouseZatAktifController::class, 'store'])->name('store');
                    Route::get('/{id}', [WarehouseZatAktifController::class, 'show'])->name('show');
                    Route::put('/{id}', [WarehouseZatAktifController::class, 'update'])->name('update');
                    Route::delete('/{id}', [WarehouseZatAktifController::class, 'destroy'])->name('destroy');
                });

                Route::prefix('satuan-barang')->name('satuan-barang.')->group(function () {
                    Route::get('/', [WarehouseSatuanBarangController::class, 'index'])->name('index');
                    Route::get('/data', [WarehouseSatuanBarangController::class, 'data'])->name('data');
                    Route::post('/', [WarehouseSatuanBarangController::class, 'store'])->name('store');
                    Route::get('/{id}', [WarehouseSatuanBarangController::class, 'show'])->name('show');
                    Route::put('/{id}', [WarehouseSatuanBarangController::class, 'update'])->name('update');
                    Route::delete('/{id}', [WarehouseSatuanBarangController::class, 'destroy'])->name('destroy');
                });

                Route::prefix('kelompok-barang')->name('kelompok-barang.')->group(function () {
                    Route::get('/', [WarehouseKelompokBarangController::class, 'index'])->name('index');
                    Route::get('/data', [WarehouseKelompokBarangController::class, 'data'])->name('data');
                    Route::post('/', [WarehouseKelompokBarangController::class, 'store'])->name('store');
                    Route::get('/{id}', [WarehouseKelompokBarangController::class, 'show'])->name('show');
                    Route::put('/{id}', [WarehouseKelompokBarangController::class, 'update'])->name('update');
                    Route::delete('/{id}', [WarehouseKelompokBarangController::class, 'destroy'])->name('destroy');
                });

                Route::prefix('kategori-barang')->name('kategori-barang.')->group(function () {
                    Route::get('/', [WarehouseKategoriBarangController::class, 'index'])->name('index');
                    Route::get('/data', [WarehouseKategoriBarangController::class, 'data'])->name('data');
                    Route::post('/', [WarehouseKategoriBarangController::class, 'store'])->name('store');
                    Route::get('/{id}', [WarehouseKategoriBarangController::class, 'show'])->name('show');
                    Route::put('/{id}', [WarehouseKategoriBarangController::class, 'update'])->name('update');
                    Route::delete('/{id}', [WarehouseKategoriBarangController::class, 'destroy'])->name('destroy');
                });

                Route::prefix('golongan-barang')->name('golongan-barang.')->group(function () {
                    Route::get('/', [WarehouseGolonganBarangController::class, 'index'])->name('index');
                    Route::get('/data', [WarehouseGolonganBarangController::class, 'data'])->name('data');
                    Route::post('/', [WarehouseGolonganBarangController::class, 'store'])->name('store');
                    Route::get('/{id}', [WarehouseGolonganBarangController::class, 'show'])->name('show');
                    Route::put('/{id}', [WarehouseGolonganBarangController::class, 'update'])->name('update');
                    Route::delete('/{id}', [WarehouseGolonganBarangController::class, 'destroy'])->name('destroy');
                });

                Route::prefix('pabrik')->group(function () {
                    Route::get('/', [WarehousePabrikController::class, 'index'])->name('pabrik');
                    Route::resource('pabrik', WarehousePabrikController::class);
                    Route::get('pabrik-data', [WarehousePabrikController::class, 'data'])->name('pabrik.data');
                });

                Route::prefix('supplier')->name('supplier.')->group(function () {
                    Route::get('/', [WarehouseSupplierController::class, 'index'])->name('index');
                    Route::get('/data', [WarehouseSupplierController::class, 'data'])->name('data');
                    Route::post('/', [WarehouseSupplierController::class, 'store'])->name('store');
                    Route::get('/{id}', [WarehouseSupplierController::class, 'show'])->name('show');
                    Route::put('/{id}', [WarehouseSupplierController::class, 'update'])->name('update');
                    Route::delete('/{id}', [WarehouseSupplierController::class, 'destroy'])->name('destroy');
                });

                Route::resource('master-gudang', WarehouseMasterGudangController::class)->except(['create', 'edit', 'index']);
                Route::get('master-gudang', [WarehouseMasterGudangController::class, 'index'])->name('master-gudang.index');
                Route::prefix('barang-non-farmasi')->group(function () {
                    Route::get('/', [WarehouseBarangNonFarmasiController::class, 'index'])->name('barang-non-farmasi');
                    Route::get('/create', [WarehouseBarangNonFarmasiController::class, 'create'])->name('barang-non-farmasi.create');
                    Route::get('/edit/{id}', [WarehouseBarangNonFarmasiController::class, 'edit'])->name('barang-non-farmasi.edit');
                });

                Route::prefix('barang-farmasi')->name('barang-farmasi.')->group(function () {
                    Route::get('/', [WarehouseBarangFarmasiController::class, 'index'])->name('index');
                    Route::get('/data', [WarehouseBarangFarmasiController::class, 'data'])->name('data');
                    Route::get('/create', [WarehouseBarangFarmasiController::class, 'create'])->name('create');
                    Route::post('/', [WarehouseBarangFarmasiController::class, 'store'])->name('store');
                    Route::get('/{id}/edit', [WarehouseBarangFarmasiController::class, 'edit'])->name('edit');
                    Route::put('/{id}', [WarehouseBarangFarmasiController::class, 'update'])->name('update');
                    Route::delete('/{id}', [WarehouseBarangFarmasiController::class, 'destroy'])->name('destroy');
                    // Export & Import Routes
                    Route::post('/export', [WarehouseBarangFarmasiController::class, 'export'])->name('export');
                    Route::post('/import', [WarehouseBarangFarmasiController::class, 'import'])->name('import');
                });

                Route::prefix('barang-non-farmasi')->name('barang-non-farmasi.')->group(function () {
                    Route::get('/', [WarehouseBarangNonFarmasiController::class, 'index'])->name('index');
                    Route::get('/data', [WarehouseBarangNonFarmasiController::class, 'data'])->name('data');
                    Route::get('/create', [WarehouseBarangNonFarmasiController::class, 'create'])->name('create');
                    Route::post('/', [WarehouseBarangNonFarmasiController::class, 'store'])->name('store');
                    Route::get('/{id}/edit', [WarehouseBarangNonFarmasiController::class, 'edit'])->name('edit');
                    Route::put('/{id}', [WarehouseBarangNonFarmasiController::class, 'update'])->name('update');
                    Route::delete('/{id}', [WarehouseBarangNonFarmasiController::class, 'destroy'])->name('destroy');
                    // Export & Import Routes
                    Route::post('/export', [WarehouseBarangNonFarmasiController::class, 'export'])->name('export');
                    Route::post('/import', [WarehouseBarangNonFarmasiController::class, 'import'])->name('import');
                });

                Route::get('setup-min-max-stock', [WarehouseSetupMinMaxStockController::class, 'index'])->name('setup-min-max-stock');
                Route::get('setup-min-max-stock/setup', [WarehouseSetupMinMaxStockController::class, 'create'])->name('setup-min-max-stock.create');
            });

            Route::prefix('purchase-request')->group(function () {
                Route::prefix('pharmacy')->group(function () {
                    Route::get('/', [WarehousePurchaseRequestPharmacy::class, 'index'])->name('purchase-request.pharmacy');
                    Route::get('/create', [WarehousePurchaseRequestPharmacy::class, 'create'])->name('purchase-request.pharmacy.create');
                    Route::get('/print/{id}', [WarehousePurchaseRequestPharmacy::class, 'print'])->name('purchase-request.pharmacy.print');
                    Route::get('/edit/{id}', [WarehousePurchaseRequestPharmacy::class, 'edit'])->name('purchase-request.pharmacy.edit');
                });

                Route::prefix('non-pharmacy')->group(function () {
                    Route::get('/', [WarehousePurchaseRequestNonPharmacy::class, 'index'])->name('purchase-request.non-pharmacy');
                    Route::get('/create', [WarehousePurchaseRequestNonPharmacy::class, 'create'])->name('purchase-request.non-pharmacy.create');
                    Route::get('/print/{id}', [WarehousePurchaseRequestNonPharmacy::class, 'print'])->name('purchase-request.non-pharmacy.print');
                    Route::get('/edit/{id}', [WarehousePurchaseRequestNonPharmacy::class, 'edit'])->name('purchase-request.non-pharmacy.edit');
                });
            });

            Route::prefix('penerimaan-barang')->group(function () {
                Route::prefix('pharmacy')->group(function () {
                    Route::get('/', [WarehousePenerimaanBarangFarmasiController::class, 'index'])->name('penerimaan-barang.pharmacy.index');
                    Route::get('/create', [WarehousePenerimaanBarangFarmasiController::class, 'create'])->name('procurement.penerimaan-barang.pharmacy.create');
                    Route::get('/print/{id}', [WarehousePenerimaanBarangFarmasiController::class, 'print'])->name('penerimaan-barang.pharmacy.print');
                    Route::get('/edit/{id}', [WarehousePenerimaanBarangFarmasiController::class, 'edit'])->name('penerimaan-barang.pharmacy.edit');
                });

                Route::prefix('non-pharmacy')->group(function () {
                    Route::get('/', [WarehousePenerimaanBarangNonFarmasiController::class, 'index'])->name('penerimaan-barang.non-pharmacy');
                    Route::get('/create', [WarehousePenerimaanBarangNonFarmasiController::class, 'create'])->name('procurement.penerimaan-barang.non-pharmacy.create');
                    Route::get('/print/{id}', [WarehousePenerimaanBarangNonFarmasiController::class, 'print'])->name('penerimaan-barang.non-pharmacy.print');
                    Route::get('/edit/{id}', [WarehousePenerimaanBarangNonFarmasiController::class, 'edit'])->name('penerimaan-barang.non-pharmacy.edit');
                });

                Route::prefix('retur-barang')->group(function () {
                    Route::get('/', [WarehouseReturBarangController::class, 'index'])->name('penerimaan-barang.retur-barang');
                    Route::get('/create', [WarehouseReturBarangController::class, 'create'])->name('procurement.penerimaan-barang.retur-barang.create');
                    Route::get('/print/{id}', [WarehouseReturBarangController::class, 'print'])->name('penerimaan-barang.retur-barang.print');

                    // Route baru untuk child row
                    Route::get('/{id}/details', [WarehouseReturBarangController::class, 'details'])->name('retur-barang.details');

                    // Route untuk mengambil item berdasarkan supplier
                    Route::get('/get-items/{supplier_id}', [WarehouseReturBarangController::class, 'get_items'])->name('retur-barang.get-items');

                    Route::get('/popup-items/{supplier_id}/{gudang_id}', [WarehouseReturBarangController::class, 'popupItems'])->name('retur-barang.popup-items');
                });

                Route::prefix('report')->group(function () {
                    Route::get('/', [WarehousePenerimaanBarangReportController::class, 'index'])->name('penerimaan-barang.report');
                    Route::get('show/{type}/{json}', [WarehousePenerimaanBarangReportController::class, 'show'])->name('penerimaan-barang.report.show');
                });
            });

            Route::prefix('stock-request')->name('stock-request.')->group(function () {
                Route::prefix('pharmacy')->name('pharmacy.')->group(function () {
                    // Rute yang sudah ada (disesuaikan penamaannya)
                    Route::get('/', [WarehouseStockRequestPharmacyController::class, 'index'])->name('index');
                    Route::get('/create', [WarehouseStockRequestPharmacyController::class, 'create'])->name('create');
                    Route::get('/print/{id}', [WarehouseStockRequestPharmacyController::class, 'print'])->name('print');
                    Route::get('/edit/{id}', [WarehouseStockRequestPharmacyController::class, 'edit'])->name('edit');

                    // Rute yang ditambahkan untuk fungsionalitas penuh
                    Route::post('/', [WarehouseStockRequestPharmacyController::class, 'store'])->name('store');
                    Route::put('/{id}', [WarehouseStockRequestPharmacyController::class, 'update'])->name('update');
                    Route::delete('/{id}', [WarehouseStockRequestPharmacyController::class, 'destroy'])->name('destroy');
                    Route::get('/{id}/details', [WarehouseStockRequestPharmacyController::class, 'getDetailItems'])->name('details'); // Untuk Child Row
                    // [FIX] Tambahkan rute ini untuk mengambil data item di modal
                    Route::get('/get/item-gudang/{asal_gudang_id}/{tujuan_gudang_id}', [WarehouseStockRequestPharmacyController::class, 'get_item_gudang'])
                        ->name('get.item-gudang');
                });

                Route::prefix('non-pharmacy')->group(function () {
                    Route::get('/', [WarehouseStockRequestNonPharmacyController::class, 'index'])->name('stock-request.non-pharmacy');
                    Route::get('/create', [WarehouseStockRequestNonPharmacyController::class, 'create'])->name('stock-request.non-pharmacy.create');
                    Route::get('/print/{id}', [WarehouseStockRequestNonPharmacyController::class, 'print'])->name('stock-request.non-pharmacy.print');
                    Route::get('/edit/{id}', [WarehouseStockRequestNonPharmacyController::class, 'edit'])->name('stock-request.non-pharmacy.edit');
                });
            });

            Route::prefix('distribusi-barang')->group(function () {
                Route::prefix('pharmacy')->name('distribusi-barang.pharmacy.')->group(function () {
                    // Rute yang sudah ada
                    Route::get('/', [WarehouseDistribusiBarangFarmasiController::class, 'index'])->name('index');
                    Route::get('/create', [WarehouseDistribusiBarangFarmasiController::class, 'create'])->name('create');
                    Route::get('/print/{id}', [WarehouseDistribusiBarangFarmasiController::class, 'print'])->name('print');
                    Route::get('/edit/{id}', [WarehouseDistribusiBarangFarmasiController::class, 'edit'])->name('edit');

                    // Rute yang ditambahkan untuk fungsionalitas penuh
                    Route::post('/', [WarehouseDistribusiBarangFarmasiController::class, 'store'])->name('store');
                    Route::put('/{id}', [WarehouseDistribusiBarangFarmasiController::class, 'update'])->name('update');
                    Route::delete('/{id}', [WarehouseDistribusiBarangFarmasiController::class, 'destroy'])->name('destroy');
                    Route::get('/{id}/details', [WarehouseDistribusiBarangFarmasiController::class, 'getDetailItems'])->name('details');
                    Route::get('/get-items-modal/{asal_gudang_id}/{tujuan_gudang_id}', [WarehouseDistribusiBarangFarmasiController::class, 'getItemGudangForModal'])->name('get-items-modal');
                    Route::get('/get/stock/{gudang_id}/{barang_id}/{satuan_id}', [WarehouseDistribusiBarangFarmasiController::class, 'get_stock'])
                        ->name('get-stock');
                    // Route::get('/get/stock/{asal_gudang_id}/{tujuan_gudang_id}/{barang_id}', [WarehouseDistribusiBarangFarmasiController::class, 'getStock'])->name('get-stock');
                });

                Route::prefix('non-pharmacy')->group(function () {
                    Route::get('/', [WarehouseDistribusiBarangNonFarmasiController::class, 'index'])->name('distribusi-barang.non-pharmacy');
                    Route::get('/create', [WarehouseDistribusiBarangNonFarmasiController::class, 'create'])->name('distribusi-barang.non-pharmacy.create');
                    Route::get('/print/{id}', [WarehouseDistribusiBarangNonFarmasiController::class, 'print'])->name('distribusi-barang.non-pharmacy.print');
                    Route::get('/edit/{id}', [WarehouseDistribusiBarangNonFarmasiController::class, 'edit'])->name('distribusi-barang.non-pharmacy.edit');
                });

                Route::prefix('report')->group(function () {
                    Route::get('/', [WarehouseDistribusiBarangReportController::class, 'index'])->name('distribusi-barang.report');
                    Route::get('show/{json}', [WarehouseDistribusiBarangReportController::class, 'show'])->name('distribusi-barang.report.show');
                });
            });

            Route::prefix('revaluasi-stock')->group(function () {
                Route::prefix('stock-adjustment')->group(function () {
                    Route::get('/', [WarehouseStockAdjustmentController::class, 'index'])->name('revaluasi-stock.stock-adjustment');
                    Route::get('/create/{token}', [WarehouseStockAdjustmentController::class, 'create'])->name('revaluasi-stock.stock-adjustment.create');
                    Route::get('/edit/{gudang_id}/{barang_id}/{satuan_id}/{type}/{token}', [WarehouseStockAdjustmentController::class, 'edit'])->name('revaluasi-stock.stock-adjustment.edit');
                });

                Route::prefix('stock-opname')->group(function () {
                    Route::prefix('gudang-opname')->group(function () {
                        Route::get('/', [WarehouseStockOpnameGudangController::class, 'index'])->name('revaluasi-stock.stock-opname.gudang-opname');
                    });

                    Route::prefix('draft')->group(function () {
                        Route::get('/', [WarehouseStockOpnameDraft::class, 'index'])->name('revaluasi-stock.stock-opname.draft');
                        Route::get('/print-selisih/{sog_id}', [WarehouseStockOpnameDraft::class, 'print_selisih'])->name('revaluasi-stock.stock-opname.draft.print.selisih');
                        Route::get('/print-so/{sog_id}', [WarehouseStockOpnameDraft::class, 'print_so'])->name('revaluasi-stock.stock-opname.draft.print.so');
                    });

                    Route::prefix('final')->group(function () {
                        Route::get('/', [WarehouseStockOpnameFinal::class, 'index'])->name('revaluasi-stock.stock-opname.final');
                        Route::get('/print-selisih/{sog_id}', [WarehouseStockOpnameFinal::class, 'print_selisih'])->name('revaluasi-stock.stock-opname.final.print.selisih');
                        Route::get('/print-so/{sog_id}', [WarehouseStockOpnameFinal::class, 'print_so'])->name('revaluasi-stock.stock-opname.final.print.so');
                    });

                    Route::prefix('report')->group(function () {
                        Route::get('/', [WarehouseStockOpnameReport::class, 'index'])->name('revaluasi-stock.stock-opname.report');
                        Route::get('/print-selisih/{sog_id}', [WarehouseStockOpnameReport::class, 'print_selisih'])->name('revaluasi-stock.stock-opname.report.print.selisih');
                        Route::get('/print-so/{sog_id}', [WarehouseStockOpnameReport::class, 'print_so'])->name('revaluasi-stock.stock-opname.report.print.detail');
                    });
                });
            });

            Route::prefix('report')->group(function () {
                Route::get('/stock-status', [WarehouseReportStockStatus::class, 'index'])->name('report.stock-status');
                Route::get('/stock-detail', [WarehouseReportStockDetail::class, 'index'])->name('report.stock-detail');
                Route::get('/kartu-stok', [WarehouseReportKartuStock::class, 'index'])->name('report.kartu-stock');
                Route::get('/histori-perubahan-master-data', [WarehouseReportHistoriPerubahanMasterBarang::class, 'index'])->name('report.histori-perubahan-master-data');
            });
        });

        Route::prefix('procurement')->name('procurement.')->group(function () {
            Route::prefix('purchase-request')->name('purchase-request.')->group(function () {
                Route::prefix('pharmacy')->name('pharmacy')->group(function () {
                    Route::get('/', [WarehousePurchaseRequestPharmacy::class, 'index']);
                    Route::get('/create', [WarehousePurchaseRequestPharmacy::class, 'create'])->name('.create');
                    Route::post('/store', [WarehousePurchaseRequestPharmacy::class, 'store'])->name('.store');
                    Route::get('/{id}/edit', [WarehousePurchaseRequestPharmacy::class, 'edit'])->name('.edit');
                    Route::put('/{id}', [WarehousePurchaseRequestPharmacy::class, 'update'])->name('.update');
                    Route::delete('/{id}', [WarehousePurchaseRequestPharmacy::class, 'destroy'])->name('.destroy');
                    Route::get('/{id}/details', [WarehousePurchaseRequestPharmacy::class, 'details'])->name('.details');
                    Route::get('/print/{id}', [WarehousePurchaseRequestPharmacy::class, 'print'])->name('.print');
                    Route::get('/popup-items', [WarehousePurchaseRequestPharmacy::class, 'popupItems'])->name('.popup-items');
                });

                Route::prefix('non-pharmacy')->group(function () {
                    Route::get('/', [ProcurementPurchaseRequestNonPharmacyController::class, 'index'])->name('purchase-request.non-pharmacy');
                    Route::get('/create', [ProcurementPurchaseRequestNonPharmacyController::class, 'create'])->name('purchase-request.non-pharmacy.create');
                    Route::get('/print/{id}', [ProcurementPurchaseRequestNonPharmacyController::class, 'print'])->name('purchase-request.non-pharmacy.print');
                    Route::get('/edit/{id}', [ProcurementPurchaseRequestNonPharmacyController::class, 'edit'])->name('purchase-request.non-pharmacy.edit');
                });
            });

            Route::prefix('approval-pr')->group(function () {
                Route::prefix('pharmacy')->group(function () {
                    Route::get('/', [ProcurementPRApprovalPharmacy::class, 'index'])->name('approval-pr.pharmacy');
                    Route::get('/print/{id}', [ProcurementPRApprovalPharmacy::class, 'print'])->name('approval-pr.pharmacy.print');
                    Route::get('/edit/{id}', [ProcurementPRApprovalPharmacy::class, 'edit'])->name('approval-pr.pharmacy.edit');
                });

                Route::prefix('non-pharmacy')->group(function () {
                    Route::get('/', [ProcurementPRApprovalNonPharmacy::class, 'index'])->name('approval-pr.non-pharmacy');
                    Route::get('/print/{id}', [ProcurementPRApprovalNonPharmacy::class, 'print'])->name('approval-pr.non-pharmacy.print');
                    Route::get('/edit/{id}', [ProcurementPRApprovalNonPharmacy::class, 'edit'])->name('approval-pr.non-pharmacy.edit');
                });
            });

            Route::prefix('purchase-order')->group(function () {
                Route::prefix('pharmacy')->group(function () {
                    Route::get('/', [ProcurementPurchaseOrderPharmacyController::class, 'index'])->name('purchase-order.pharmacy');
                    Route::get('/create', [ProcurementPurchaseOrderPharmacyController::class, 'create'])->name('purchase-order.pharmacy.create');
                    Route::get('/print/{id}', [ProcurementPurchaseOrderPharmacyController::class, 'print'])->name('purchase-order.pharmacy.print');
                    Route::get('/edit/{id}', [ProcurementPurchaseOrderPharmacyController::class, 'edit'])->name('purchase-order.pharmacy.edit');
                    Route::get('/detail/{id}', [ProcurementPurchaseOrderPharmacyController::class, 'getDetail'])->name('purchase-order.pharmacy.detail');
                    Route::delete('/destroy/{id}', [ProcurementPurchaseOrderPharmacyController::class, 'destroy'])->name('purchase-order.pharmacy.destroy');
                });

                Route::prefix('non-pharmacy')->group(function () {
                    Route::get('/', [ProcurementPurchaseOrderNonPharmacyController::class, 'index'])->name('purchase-order.non-pharmacy');
                    Route::get('/create', [ProcurementPurchaseOrderNonPharmacyController::class, 'create'])->name('purchase-order.non-pharmacy.create');
                    Route::get('/print/{id}', [ProcurementPurchaseOrderNonPharmacyController::class, 'print'])->name('purchase-order.non-pharmacy.print');
                    Route::get('/edit/{id}', [ProcurementPurchaseOrderNonPharmacyController::class, 'edit'])->name('purchase-order.non-pharmacy.edit');
                });
            });

            Route::prefix('approval-po')->group(function () {
                Route::prefix('pharmacy')->group(function () {
                    Route::get('/', [ProcurementPOApprovalPharmacy::class, 'index'])->name('approval-po.pharmacy');
                    Route::get('/edit/{id}', [ProcurementPOApprovalPharmacy::class, 'edit'])->name('approval-po.pharmacy.edit');
                });

                Route::prefix('non-pharmacy')->group(function () {
                    Route::get('/', [ProcurementPOApprovalNonPharmacy::class, 'index'])->name('approval-po.non-pharmacy');
                    Route::get('/edit/{id}', [ProcurementPOApprovalNonPharmacy::class, 'edit'])->name('approval-po.non-pharmacy.edit');
                });

                Route::prefix('ceo')->group(function () {
                    Route::get('/', [ProcurementPOApprovalCEO::class, 'index'])->name('approval-po.ceo');
                    Route::get('/edit/{type}/{id}', [ProcurementPOApprovalCEO::class, 'edit'])->name('approval-po.ceo.edit');
                });
            });

            Route::prefix('setup')->group(function () {
                Route::get('supplier', [ProcurementSetupSupplier::class, 'index'])->name('setup.supplier');
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
            Route::prefix('laporan')->name('laporan.')->group(function () {

                Route::get('order', [PersalinanController::class, 'laporanOrderPasien'])->name('order');

                // URL: /simrs/vk/laporan/order/print
                Route::get('order/print', [PersalinanController::class, 'printLaporanOrderPasien'])->name('order.print');

                // 2. Laporan Rekap Kunjungan per Tindakan
                // URL: /simrs/vk/laporan/rekap-per-tindakan
                Route::get('rekap-per-tindakan', [PersalinanController::class, 'laporanRekapTindakan'])->name('rekap-per-tindakan');

                // URL: /simrs/vk/laporan/rekap-per-tindakan/print
                Route::get('rekap-per-tindakan/print', [PersalinanController::class, 'printLaporanRekapTindakan'])->name('rekap-per-tindakan.print');
            });
        });

        // Route::prefix('vk/bayi')->name('bayi.')->group(function () {
        //     // Route untuk mengambil data bayi via AJAX untuk ditampilkan di tabel
        //     Route::get('data/{order_persalinan_id}', [BayiController::class, 'getDataForOrder'])->name('data');
        //     Route::get('get-doctors', [BayiController::class, 'getDoctors'])->name('get_doctors');
        //     // Route::get('print/{bayi}', [BayiController::class, 'printCertificate'])->name('print');
        //     Route::get('{bayi}/print', [BayiController::class, 'printCertificate'])->name('print_certificate');
        //     Route::get('/get-beds', [BayiController::class, 'getDataBed'])->name('get_beds');
        //     Route::get('/get-kelas-rawat', [BayiController::class, 'getKelasRawat'])->name('get_kelas_rawat');
        //     Route::get('/{order}/bayi-popup', [BayiController::class, 'showBayiPopup'])->name('popup');
        //     Route::resource('/', BayiController::class)->parameters(['' => 'bayi'])->except(['index', 'create', 'edit']);
        // });
        Route::prefix('bayi')->name('bayi.')->group(function () {
            // Rute statis (harus didefinisikan lebih dulu)
            Route::get('/get-doctors', [BayiController::class, 'getDoctors'])->name('get_doctors');
            Route::get('/get-beds', [BayiController::class, 'getDataBed'])->name('get_beds');
            Route::get('/get-kelas-rawat', [BayiController::class, 'getKelasRawat'])->name('get_kelas_rawat');

            // Rute dinamis
            Route::get('/{order}/popup/{type?}', [BayiController::class, 'showBayiPopup'])->name('popup');
            Route::get('/data/{order}/{type?}', [BayiController::class, 'getDataForOrder'])->name('data');
            Route::post('/store', [BayiController::class, 'store'])->name('store');
            Route::get('/{bayi}', [BayiController::class, 'show'])->name('show');
            Route::delete('/{bayi}', [BayiController::class, 'destroy'])->name('destroy');
            Route::get('/{bayi}/print', [BayiController::class, 'printCertificate'])->name('print_certificate');
        });

        Route::prefix('ok')->group(function () {
            Route::get('/daftar-pasien', [OperasiController::class, 'index'])->name('ok.daftar-pasien');
            Route::get('/prosedur/{orderId}', [OperasiController::class, 'prosedure'])->name('ok.prosedur');
            Route::get('/edit/{orderId}/{prosedurId}', [OperasiController::class, 'editProsedure'])->name('ok.prosedure.edit');
            Route::get('/prosedur/{order}/create', [OperasiController::class, 'createProsedur'])->name('ok.prosedur.create');
            Route::post('/prosedur/store', [OperasiController::class, 'storeProsedur'])->name('ok.prosedur.store');
            Route::put('/prosedur/update', [OperasiController::class, 'updateProsedur'])->name('ok.prosedur.update');
            Route::get('/prosedur/get-jenis-by-kategori/{kategoriId}', [OperasiController::class, 'getJenisByKategori'])->name('ok.prosedur.get-jenis-by-kategori');
            Route::get('/prosedur/get-tindakan-by-jenis/{jenisId}', [OperasiController::class, 'getTindakanByJenis'])->name('ok.prosedur.get-tindakan-by-jenis');
            Route::delete('/prosedur/{prosedurId}', [OperasiController::class, 'deleteProsedur'])->name('ok.prosedur.delete');
            Route::prefix('laporan')->group(function () {
                Route::get('order-pasien', [OperasiController::class, 'orderPasienReport'])->name('ok.laporan.order-pasien');
                Route::get('rekap-kunjungan', [OperasiController::class, 'rekapKunjungan'])->name('ok.laporan.rekap-kunjungan');
                Route::get('10-besar-tindakan', [OperasiController::class, '10BesarTindakan'])->name('ok.laporan.10-besar-tindakan');
                // New route for laporan/order
                Route::get('rekap-per-tindakan', [OperasiController::class, 'rekapKunjungan'])->name('rekap-per-tindakan');

                // URL: /simrs/ok/laporan/rekap-per-tindakan/print
                Route::get('rekap-per-tindakan/print', [OperasiController::class, 'printRekapKunjungan'])->name('ok.laporan.rekap-kunjungan.print');
                Route::get('order', [OperasiController::class, 'orderPasienReport'])->name('ok.laporan.order');
                Route::get('order-data', [OperasiController::class, 'getOrderPasienData'])->name('ok.laporan.order-data');
                Route::get('order-pasien/print', [OperasiController::class, 'printOrderPasienReport'])->name('ok.laporan.order.print');
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

            Route::delete('order/{id}', [RadiologiController::class, 'destroy'])->name('radiologi.order.destroy');
        });

        Route::prefix('laboratorium')->group(function () {
            Route::get('list-order', [LaboratoriumController::class, 'index'])->name('laboratorium.list-order');
            Route::get('label-order/{id}', [LaboratoriumController::class, 'labelOrder'])->name('laboratorium.label-order');
            Route::get('hasil-order/{id}', [LaboratoriumController::class, 'hasilOrder'])->name('laboratorium.hasil-order');
            Route::get('edit-order/{id}', [LaboratoriumController::class, 'editOrder'])->name('laboratorium.edit-order');
            // Route untuk menangani penambahan data (tetap POST)
            Route::post('/order/add-tindakan', [LaboratoriumController::class, 'addTindakan'])->name('order.laboratorium.add-tindakan');
            // Route BARU untuk menampilkan halaman popup
            Route::get('/order/{order_id}/add-tindakan-popup', [LaboratoriumController::class, 'addTindakanPopup'])->name('order.laboratorium.add-tindakan-popup');
            Route::get('nota-order/{id}', [LaboratoriumController::class, 'notaOrder'])->name('laboratorium.nota-order');
            Route::get('simulasi-harga', [LaboratoriumController::class, 'simulasiHarga'])->name('laboratorium.simulasi-harga');
            Route::get('order', [LaboratoriumController::class, 'order'])->name('laboratorium.order');
            Route::get('laporan/parameter-pemeriksaan', [LaboratoriumController::class, 'reportParameter'])->name('laboratorium.report.parameter');
            Route::get('laporan/pasien-per-pemeriksaan', [LaboratoriumController::class, 'reportPatient'])->name('laboratorium.report.patient');
            Route::get('popup/pilih-pasien/{poli}', [LaboratoriumController::class, 'popupPilihPasien'])->name('laboratorium.popup.pilih-pasien');
            Route::get('laporan-parameter-view/{fromDate}/{endDate}/{tipe_rawat}/{penjamin}', [LaboratoriumController::class, 'reportParameterView'])->name('laboratorium.report-parameter.view');
            Route::get('laporan-pasien-view/{fromDate}/{endDate}/{tipe_rawat}/{penjamin}/{parameter}', [LaboratoriumController::class, 'reportPatientView'])->name('laboratorium.report-patient.view');

            Route::delete('order/{id}', [LaboratoriumController::class, 'destroy'])->name('laboratorium.order.destroy');
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

        Route::resource('tarif-visite-dokter', TarifVisiteDokterController::class)->except(['create', 'show']);
        // Route untuk menampilkan halaman popup
        Route::get('set-tarif-visite/{doctor}', [TarifVisiteDokterController::class, 'setTariffForDoctor'])->name('set.tarif.dokter');
        // Route API untuk mengambil data tarif per dokter untuk DataTables
        Route::get('get-tarif-by-doctor/{doctor}', [TarifVisiteDokterController::class, 'getTariffByDoctor'])->name('get.tarif.by.doctor');

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
        | Satu Sehat Routes
        |--------------------------------------------------------------------------
        |
        | Group of routes for the "BPJS" section of the SIMRS application.
        | Includes bridging Vclaim, registration, and claim management.
        |
        */

        // Rute untuk Fitur Geolocation Satu Sehat
        Route::prefix('satu-sehat')->name('satu-sehat.')->group(function () {
            // Menampilkan halaman geolocation
            Route::get('/geolocation', [GeolocationController::class, 'index'])->name('geolocation');

            // Menangani request mapping lokasi RS (untuk AJAX)
            Route::post('/mapping-lokasi-rs', [GeolocationController::class, 'mapLocation'])->name('mapping-lokasi');

            // Ganti {organization} menjadi {departement} agar cocok dengan model
            Route::get('/departments/{category?}', [SatuSehatOrganizationController::class, 'index'])->name('departments');
            Route::post('/departments/{departement}/map', [SatuSehatOrganizationController::class, 'map'])->name('departments.map');

            // Rute untuk Mapping Department ke Location
            Route::get('/department-locations/{category?}', [DepartmentLocationController::class, 'index'])->name('department-locations');
            Route::post('/department-locations/{departement}/map', [DepartmentLocationController::class, 'map'])->name('department-locations.map');

            // Rute untuk Halaman Tenaga Medis (Practitioner)
            Route::get('/practitioners/{category?}', [PractitionerController::class, 'index'])->name('practitioners');
            Route::post('/practitioners/{employee}/map', [PractitionerController::class, 'map'])->name('practitioners.map');

            // Rute untuk Dashboard
            Route::get('/dashboard', [DashboardSatuSehatController::class, 'index'])->name('dashboard');

            // Rute API untuk data dinamis dashboard
            Route::post('/dashboard/summary-cards', [DashboardSatuSehatController::class, 'getSummaryCards'])->name('dashboard.summary-cards');
            Route::post('/dashboard/encounter-chart', [DashboardSatuSehatController::class, 'getEncounterChart'])->name('dashboard.encounter-chart');
            Route::post('/dashboard/fhir-summary', [DashboardSatuSehatController::class, 'getFhirResourceSummary'])->name('dashboard.fhir-summary');
            Route::post('/dashboard/master-data-chart', [DashboardSatuSehatController::class, 'getMasterDataChart'])->name('dashboard.master-data-chart');
            Route::post('/dashboard/mapping-log-table', [DashboardSatuSehatController::class, 'getMappingLogTable'])->name('dashboard.mapping-log-table');

            // Rute untuk Halaman Laporan Summary
            Route::get('/laporan-summary', [LaporanSummaryController::class, 'index'])->name('laporan.summary');
            Route::post('/laporan-summary/data', [LaporanSummaryController::class, 'getData'])->name('laporan.summary.data');
            Route::post('/laporan-summary/{registration}/resend', [LaporanSummaryController::class, 'resendEncounter'])->name('laporan.summary.resend');
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
            Route::put('/bilingan/cancel-payment/{id}', [BilinganController::class, 'cancelPayment'])->name('bilingan.cancel-payment');
            Route::put('/bilingan/cancel-bill/{id}', [BilinganController::class, 'cancelBill'])->name('bilingan.cancel-bill');
            Route::get('/down-payment/data/{id}', [BilinganController::class, 'getDownPaymentData'])->name('down.payment.data');
            Route::post('/down-payment', [BilinganController::class, 'storeDownPayment'])->name('down.payment.store');
            Route::delete('/down-payment/{id}', [BilinganController::class, 'destroyDownPayment'])->name('down.payment.destroy');
            Route::post('/pembayaran-tagihan', [BilinganController::class, 'storePembayaranTagihan'])->name('pembayaran.tagihan.store');
            Route::get('/print-bill/{id}', [BilinganController::class, 'printBill'])->name('print.bill');
            Route::get('/print-kwitansi/{id}', [BilinganController::class, 'printKwitansi'])->name('print.kwitansi');
            Route::get('/tagihan-pasien/{id}/tarif', [TagihanPasienController::class, 'getTarifShare']);

            Route::get('/order-notifications/{registration_id}', [BilinganController::class, 'getOrderNotifications'])->name('kasir.order-notifications');
            Route::post('/process-order', [BilinganController::class, 'processOrderIntoBill'])->name('kasir.process-order');

            // Di dalam grup route /simrs/kasir/bilingan atau yang sesuai
            Route::put('/authorize-and-cancel-bill/{id}', [BilinganController::class, 'authorizeAndCancelBill'])->name('bilingan.authorize-cancel');

            Route::post('/tagihan/pasien/merge', [TagihanPasienController::class, 'merge'])->name('tagihan.pasien.merge');
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

        // Route untuk Modul Penggabungan Rekam Medis
        Route::prefix('rekam-medis')->name('rekam-medis.')->group(function () {
            Route::get('merge', [MergeRMController::class, 'index'])->name('merge.form');
            Route::post('merge', [MergeRMController::class, 'mergeAction'])->name('merge.action');
        });
    });
});
