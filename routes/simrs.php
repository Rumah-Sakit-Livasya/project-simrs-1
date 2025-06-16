<?php

use App\Http\Controllers\JamMakanGiziController;
use App\Http\Controllers\BilinganController;
use App\Http\Controllers\KategoriGiziController;
use App\Http\Controllers\MakananGiziController;
use App\Http\Controllers\MenuGiziController;
use App\Http\Controllers\OrderGiziController;
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
use App\Http\Controllers\SIMRS\Penjamin\PenjaminController;
use App\Http\Controllers\SIMRS\RoomController;
use App\Http\Controllers\SIMRS\BedController;
use App\Http\Controllers\SIMRS\BPJS\BridgingVclaimController;
use App\Http\Controllers\SIMRS\DepartementController;
use App\Http\Controllers\SIMRS\Depo\StokRequestController;
use App\Http\Controllers\SIMRS\Depo\UnitCostController as DepoUnitCostController;
use App\Http\Controllers\SIMRS\Dokter\DokterController;
use App\Http\Controllers\SIMRS\EthnicController;
use App\Http\Controllers\SIMRS\Farmasi\FarmasiController;
use App\Http\Controllers\SIMRS\Gizi\GiziController;
use App\Http\Controllers\SIMRS\GrupParameterRadiologiController;
use App\Http\Controllers\SIMRS\GrupSuplier\GrupSuplierController;
use App\Http\Controllers\SIMRS\KategoriRadiologiController;
use App\Http\Controllers\SIMRS\ParameterRadiologiController;
use App\Http\Controllers\SIMRS\GrupTindakanMedisController;
use App\Http\Controllers\SIMRS\HargaJual\MarginHargaJualController;
use App\Http\Controllers\SIMRS\IGD\IGDController;
use App\Http\Controllers\SIMRS\Insiden\InsidenController;
use App\Http\Controllers\SIMRS\JadwalDokter\JadwalDokterController;
use App\Http\Controllers\SIMRS\Kasir\KasirController;
use App\Http\Controllers\SIMRS\KelasRawatController;
use App\Http\Controllers\SIMRS\KepustakaanController;
use App\Http\Controllers\SIMRS\Laboratorium\GrupParameterLaboratoriumController;
use App\Http\Controllers\SIMRS\Laboratorium\KategoriLaboratorumController;
use App\Http\Controllers\SIMRS\Laboratorium\LaboratoriumController;
use App\Http\Controllers\SIMRS\Laboratorium\NilaiNormalLaboratoriumController;
use App\Http\Controllers\SIMRS\Laboratorium\TipeLaboratoriumController;
use App\Http\Controllers\SIMRS\Laboratorium\ParameterLaboratoriumController;
use App\Http\Controllers\SIMRS\Operasi\JenisOperasiController;
use App\Http\Controllers\SIMRS\Operasi\KategoriOperasiController;
use App\Http\Controllers\SIMRS\Operasi\TindakanOperasiController;
use App\Http\Controllers\SIMRS\Operasi\TipeOperasiController;
use App\Http\Controllers\SIMRS\RegistrationController;
use App\Http\Controllers\SIMRS\PatientController;
use App\Http\Controllers\SIMRS\Pengkajian\FormBuilderController;
use App\Http\Controllers\SIMRS\Peralatan\PeralatanController;
use App\Http\Controllers\SIMRS\Persalinan\DaftarPersalinanController;
use App\Http\Controllers\SIMRS\Persalinan\KategoriPersalinanController;
use App\Http\Controllers\SIMRS\Persalinan\TipePersalinanController;
use App\Http\Controllers\SIMRS\Poliklinik\PoliklinikController;
use App\Http\Controllers\SIMRS\Procurement\ApprovalPOController;
use App\Http\Controllers\SIMRS\Procurement\ApprovalPRController;
use App\Http\Controllers\SIMRS\Procurement\PurchaseOrderController;
use App\Http\Controllers\SIMRS\Procurement\PurchaseRequestController as ProcurementPurchaseRequestController;
use App\Http\Controllers\SIMRS\Procurement\SetupController;
use App\Http\Controllers\SIMRS\Radiologi\RadiologiController;
use App\Http\Controllers\SIMRS\Setup\BiayaAdministrasiRawatInapController;
use App\Http\Controllers\SIMRS\Setup\BiayaMateraiController;
use App\Http\Controllers\SIMRS\Setup\TarifRegistrasiController;
use App\Http\Controllers\SIMRS\TagihanPasienController;
use App\Http\Controllers\SIMRS\TindakanMedisController;
use App\Http\Controllers\SIMRS\Warehouse\DistribusiBarangController;
use App\Http\Controllers\SIMRS\Warehouse\MasterDataWarehouseController;
use App\Http\Controllers\SIMRS\Warehouse\PenerimaanBarangController;
use App\Http\Controllers\SIMRS\Warehouse\PurchaseRequestController;
use App\Http\Controllers\SIMRS\Warehouse\ReportWarehouseController;
use App\Http\Controllers\SIMRS\Warehouse\RevaluasiStokController;
use App\Http\Controllers\SIMRS\Warehouse\StockRequestController;
use App\Http\Controllers\SIMRS\Warehouse\UnitCostController;
use App\Http\Controllers\SIMRS\Warehouse\WarehouseController;
use App\Http\Controllers\WarehouseBarangFarmasiController;
use App\Http\Controllers\WarehouseBarangNonFarmasiController;
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
use App\Http\Controllers\WarehouseReturBarangController;
use App\Http\Controllers\WarehouseSatuanBarangController;
use App\Http\Controllers\WarehouseSetupMinMaxStockController;
use App\Http\Controllers\WarehouseSupplierController;
use App\Http\Controllers\WarehouseZatAktifController;
use App\Models\ProcurementPurchaseRequestPharmacy;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\TagihanPasien;
use App\Models\WarehouseKategoriBarang;
use App\Models\WarehouseZatAktif;
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
    Route::get('/search-patients', [PatientController::class, 'search'])->name('patients.search');

    Route::get('/daftar-rekam-medis', [PatientController::class, 'daftar_rm'])->name('pendaftaran.pasien.daftar_rm');
    Route::get('/pendaftaran-pasien-baru', [PatientController::class, 'pendaftaran_pasien_baru'])->name('pendaftaran.pasien.pendaftaran_pasien_baru');
    Route::post('/pendaftaran-pasien-baru', [PatientController::class, 'simpan_pendaftaran_pasien'])->name('simpan.pendaftaran.pasien');
    Route::get('/patients/{patient}', [PatientController::class, 'detail_patient'])->name('detail.pendaftaran.pasien');
    Route::get('/patients/{patient:id}/edit', [PatientController::class, 'edit_pendaftaran_pasien'])->name('edit.pendaftaran.pasien');
    Route::post('/patients/{patient:id}/', [PatientController::class, 'update_pendaftaran_pasien'])->name('update.pendaftaran.pasien');
    Route::get('/patients/{patient:id}/print', [PatientController::class, 'print_identitas_pasien'])->name('print.identitas.pasien');
    Route::get('/patients/{patient:id}/print-kartu', [PatientController::class, 'print_kartu_pasien'])->name('print.kartu.pasien');
    Route::get('/patients/{patient:id}/history', [PatientController::class, 'history_kunjungan_pasien'])->name('history.kunjungan.pasien');
    Route::get('/data', [PatientController::class, 'getData'])->name('data.route');
    Route::get('/beds/get-data', [RegistrationController::class, 'getDataBed'])->name('beds.getData');

    Route::get('/daftar-registrasi-pasien', [RegistrationController::class, 'index'])->name('pendaftaran.daftar_registrasi_pasien');
    Route::get('/daftar-registrasi-pasien/{registrations:id}', [RegistrationController::class, 'show'])->name('detail.registrasi.pasien');
    Route::post('/daftar-registrasi-pasien/{registrations:id}/batal-register', [RegistrationController::class, 'batal_register'])->name('batal.register');
    Route::post('/daftar-registrasi-pasien/{registrations:id}/batal-keluar', [RegistrationController::class, 'batal_keluar'])->name('batal.keluar');
    Route::post('/daftar-registrasi-pasien/{registrations:id}/tutup-kunjungan', [RegistrationController::class, 'tutup_kunjungan'])->name('tutup.kunjungan');
    Route::post('/daftar-registrasi-pasien/{registrations:id}/ganti-dpjp', [RegistrationController::class, 'ganti_dpjp'])->name('ganti.dpjp');
    Route::post('/daftar-registrasi-pasien/{registrations:id}/ganti-diagnosa', [RegistrationController::class, 'ganti_diagnosa'])->name('ganti.diagnosa');

    Route::get('/patients/{patient:id}/{registrasi}', [RegistrationController::class, 'create'])->name('form.registrasi'); // Pendaftaran Poli / Ranap / IGD
    Route::post('/patients/simpan/registrasi', [RegistrationController::class, 'store'])->name('simpan.registrasi'); // Aksi Pendaftaran Poli / Ranap / IGD
    // Route::post('/patients/{id}/{registrasi}', [RegistrationController::class, 'store'])->name('simpan.registrasi.rajal');
    // Route::post('/patients/{patient:id}/{registrasi}', [PatientController::class, 'store_registrasi'])->name('simpan.registrasi.rawat.jalan');

    //Master Data
    // //Pegawai
    // Route::get('/tipe-pegawai', [TipePegawaiController::class, 'index'])->name('master.data.pegawai.tipe-pegawai');
    // Route::post('/tipe-pegawai/tambah', [TipePegawaiController::class, 'store'])->name('master.data.pegawai.tipe-pegawai.tambah');
    // Route::put('/tipe-pegawai/update/{tipe_pegawai:id}', [TipePegawaiController::class, 'update'])->name('master.data.pegawai.tipe-pegawai.update');
    // Route::get('/tipe-pegawai/delete/{tipe_pegawai:id}', [TipePegawaiController::class, 'destroy'])->name('master.data.pegawai.tipe-pegawai.destroy');

    // Route::get('/pegawai', [PegawaiController::class, 'index'])->name('master.data.pegawai');
    // Route::post('/pegawai/tambah', [PegawaiController::class, 'store'])->name('master.data.pegawai.tambah');
    // Route::put('/pegawai/edit/{pegawai:id}', [PegawaiController::class, 'update'])->name('master.data.pegawai.update');
    // Route::get('/pegawai/delete/{pegawai:id}', [PegawaiController::class, 'destroy'])->name('master.data.pegawai.destroy');

    // //Users
    // Route::get('/users', [UserController::class, 'list_data_user'])->name('master.data.user.akses.list.data.user');
    // Route::post('/user/tambah', [UserController::class, 'store'])->name('master.data.user.akses.list.data.user.tambah');
    // Route::put('/user/edit/{user:id}', [UserController::class, 'update'])->name('master.data.user.akses.list.data.user.update');
    // Route::get('/user/delete/{user:id}', [UserController::class, 'destroy'])->name('master.data.user.akses.list.data.user.destroy');

    // //Role
    // Route::get('/role', [RoleController::class, 'index'])->name('master.data.user.akses.role');
    // Route::post('/role', [RoleController::class, 'store'])->name('master.data.user.akses.role.tambah');
    // Route::put('/role/edit/{role:id}', [RoleController::class, 'update'])->name('master.data.user.akses.role.update');
    // Route::get('/role/delete/{role:id}', [RoleController::class, 'destroy'])->name('master.data.user.akses.role.destroy');

    // Route::get('/departements', [DepartementController::class, 'index'])->name('master.data.setup.departement.index');
    // Route::get('/tambah-departement', [DepartementController::class, 'create'])->name('master.data.setup.tambah.departement');
    // Route::post('/tambah-departement', [DepartementController::class, 'store'])->name('master.data.setup.simpan.tambah.departement');

    Route::prefix('simrs')->group(function () {
        Route::get('/dashboard', function () {
            return view('app-type.simrs.dashboard');
        })->name('dashboard.simrs');

        Route::prefix('/master-data')->group(function () {
            Route::prefix('setup')->group(function () {
                Route::prefix('biaya-administrasi-ranap')->group(function () {
                    Route::get('/', [BiayaAdministrasiRawatInapController::class, 'index'])->name('master-data.setup.biaya-administrasi-ranap');
                });

                Route::get('/biaya-materai', [BiayaMateraiController::class, 'index'])->name('master-data.setup.biaya-materai');
                Route::get('/kelas-rawat', [KelasRawatController::class, 'index'])->name('master-data.setup.kelas-rawat');
                Route::get('/rooms/{kelas:id}', [RoomController::class, 'index'])->name('master-data.setup.rooms');
                Route::get('/beds/{room:id}', [BedController::class, 'index'])->name('master-data.setup.beds');

                Route::get('departemen', [DepartementController::class, 'index'])->name('master-data.setup.departemen.index');
                Route::get('departemen/tambah', [DepartementController::class, 'tambah'])->name('master-data.setup.departemen.tambah');

                Route::get('/tarif-registrasi-layanan', [TarifRegistrasiController::class, 'index'])->name('master-data.setup.tarif-registrasi.index');
                Route::get('/tarif-registrasi-layanan/{id}/set-tarif', [TarifRegistrasiController::class, 'setTarif'])->name('master-data.setup.tarif-registrasi.set-tarif');
                Route::get('/tarif-registrasi-layanan/{id}/set-departement', [TarifRegistrasiController::class, 'setDepartement'])->name('master-data.setup.tarif-registrasi.set-departement');

                Route::get('/form-builder', [FormBuilderController::class, 'index'])->name('master-data.setup.form-builder');
                Route::get('/form-builder/tambah', [FormBuilderController::class, 'create'])->name('master-data.setup.form-builder.tambah');

                Route::prefix('ethnics')->group(function () {
                    Route::get('/', [EthnicController::class, 'index'])->name('master-data.ethnics');
                });
            });
            Route::prefix('layanan-medis')->group(function () {
                Route::get('/tindakan-medis', [TindakanMedisController::class, 'index'])->name('master-data.layanan-medis.tindakan-medis');
                Route::get('/grup-tindakan-medis', [GrupTindakanMedisController::class, 'index'])->name('master-data.layanan-medis.grup-tindakan-medis');
            });

            Route::prefix('penunjang-medis')->group(function () {
                Route::prefix('radiologi')->group(function () {
                    Route::get('/grup-parameter', [GrupParameterRadiologiController::class, 'index'])->name('master-data.penunjang-medis.radiologi.grup-parameter');
                    Route::get('/kategori', [KategoriRadiologiController::class, 'index'])->name('master-data.penunjang-medis.radiologi.kategori');
                    Route::get('/parameter', [ParameterRadiologiController::class, 'index'])->name('master-data.penunjang-medis.radiologi.parameter');
                    Route::get('/parameter/{id}/tarif', [ParameterRadiologiController::class, 'tarifParameter'])->name('master-data.penunjang-medis.radiologi.parameter.tarif');
                });
                Route::prefix('laboratorium')->group(function () {
                    Route::get('/grup-parameter', [GrupParameterLaboratoriumController::class, 'index'])->name('master-data.penunjang-medis.laboratorium.grup-parameter');
                    Route::get('/kategori', [KategoriLaboratorumController::class, 'index'])->name('master-data.penunjang-medis.laboratorium.kategori');
                    Route::get('/parameter', [ParameterLaboratoriumController::class, 'index'])->name('master-data.penunjang-medis.laboratorium.parameter');
                    Route::get('/parameter/{id}/tarif', [ParameterLaboratoriumController::class, 'tarifParameter'])->name('master-data.penunjang-medis.laboratorium.parameter.tarif');
                    Route::get('/nilai-normal', [NilaiNormalLaboratoriumController::class, 'index'])->name('master-data.penunjang-medis.laboratorium.nilai-parameter');
                    Route::get('/tipe', [TipeLaboratoriumController::class, 'index'])->name('master-data.penunjang-medis.laboratorium.tipe');
                });
            });

            Route::prefix('peralatan')->group(function () {
                Route::get('/', [PeralatanController::class, 'index'])->name('master-data.peralatan');
                Route::get('{id}/tarif', [PeralatanController::class, 'tarifPeralatan'])->name('master-data.peralatan.tarif');
            });

            Route::prefix('persalinan')->group(function () {
                Route::get('/kategori', [KategoriPersalinanController::class, 'index'])->name('master-data.persalinan.kategori.index');
                Route::get('/tipe', [TipePersalinanController::class, 'index'])->name('master-data.persalinan.tipe');
                Route::get('/daftar-persalinan', [DaftarPersalinanController::class, 'index'])->name('master-data.persalinan.daftar');
            });

            Route::prefix('operasi')->group(function () {
                Route::get('/kategori', [KategoriOperasiController::class, 'index'])->name('master-data.operasi.kategori.index');
                Route::get('/tipe', [TipeOperasiController::class, 'index'])->name('master-data.operasi.tipe');
                Route::get('/jenis', [JenisOperasiController::class, 'index'])->name('master-data.operasi.jenis');
                Route::get('/tindakan', [TindakanOperasiController::class, 'index'])->name('master-data.operasi.tindakan');
            });

            Route::prefix('grup-suplier')->group(function () {
                Route::get('/', [GrupSuplierController::class, 'index'])->name('master-data.grup-suplier.index');
            });

            Route::prefix('penjamin')->group(function () {
                Route::get('/', [PenjaminController::class, 'index'])->name('master-data.penjamin.index');
            });

            Route::prefix('jadwal-dokter')->group(function () {
                Route::get('setting', [JadwalDokterController::class, 'index'])->name('master-data.jadwal-dokter.index');
            });

            Route::prefix('harga-jual')->group(function () {
                Route::get('margin', [MarginHargaJualController::class, 'index'])->name('master-date.setup.harga-jual.margin.index');
            });
        });

        Route::prefix('poliklinik')->group(function () {
            Route::get('/daftar-pasien', [PoliklinikController::class, 'index'])->name('poliklinik.daftar-pasien');
            Route::get('/pengkajian-lanjutan/{registration_id}/{encryptedID}', [PoliklinikController::class, 'showForm'])->name('poliklinik.pengkajian-lanjutan.show');
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

            Route::prefix("purchase-request")->group(function () {
                Route::prefix("pharmacy")->group(function () {
                    Route::get("/", [WarehousePurchaseRequestPharmacy::class, "index"])->name("warehouse.purchase-request.pharmacy");
                    Route::get("/create", [WarehousePurchaseRequestPharmacy::class, "create"])->name("warehouse.purchase-request.pharmacy.create");
                    Route::get("/print/{id}", [WarehousePurchaseRequestPharmacy::class, "print"])->name("warehouse.purchase-request.pharmacy.print");
                    Route::get("/edit/{id}", [WarehousePurchaseRequestPharmacy::class, "edit"])->name("warehouse.purchase-request.pharmacy.edit");
                });

                Route::prefix("non-pharmacy")->group(function () {
                    Route::get("/", [WarehousePurchaseRequestNonPharmacy::class, "index"])->name("warehouse.purchase-request.non-pharmacy");
                    Route::get("/create", [WarehousePurchaseRequestNonPharmacy::class, "create"])->name("warehouse.purchase-request.non-pharmacy.create");
                    Route::get("/print/{id}", [WarehousePurchaseRequestNonPharmacy::class, "print"])->name("warehouse.purchase-request.non-pharmacy.print");
                    Route::get("/edit/{id}", [WarehousePurchaseRequestNonPharmacy::class, "edit"])->name("warehouse.purchase-request.non-pharmacy.edit");
                });
            });

            Route::prefix("penerimaan-barang")->group(function () {
                Route::prefix("pharmacy")->group(function () {
                    Route::get("/", [WarehousePenerimaanBarangFarmasiController::class, "index"])->name("warehouse.penerimaan-barang.pharmacy");
                    Route::get("/create", [WarehousePenerimaanBarangFarmasiController::class, "create"])->name("procurement.penerimaan-barang.pharmacy.create");
                    Route::get("/print/{id}", [WarehousePenerimaanBarangFarmasiController::class, "print"])->name("warehouse.penerimaan-barang.pharmacy.print");
                    Route::get("/edit/{id}", [WarehousePenerimaanBarangFarmasiController::class, "edit"])->name("warehouse.penerimaan-barang.pharmacy.edit");
                });

                Route::prefix("non-pharmacy")->group(function () {
                    Route::get("/", [WarehousePenerimaanBarangNonFarmasiController::class, "index"])->name("warehouse.penerimaan-barang.non-pharmacy");
                    Route::get("/create", [WarehousePenerimaanBarangNonFarmasiController::class, "create"])->name("procurement.penerimaan-barang.non-pharmacy.create");
                    Route::get("/print/{id}", [WarehousePenerimaanBarangNonFarmasiController::class, "print"])->name("warehouse.penerimaan-barang.non-pharmacy.print");
                    Route::get("/edit/{id}", [WarehousePenerimaanBarangNonFarmasiController::class, "edit"])->name("warehouse.penerimaan-barang.non-pharmacy.edit");
                });

                Route::prefix("retur-barang")->group(function () {
                    Route::get("/", [WarehouseReturBarangController::class, "index"])->name("warehouse.penerimaan-barang.retur-barang");
                    Route::get("/create", [WarehouseReturBarangController::class, "create"])->name("procurement.penerimaan-barang.retur-barang.create");
                    Route::get("/print/{id}", [WarehouseReturBarangController::class, "print"])->name("warehouse.penerimaan-barang.retur-barang.print");
                });

                Route::prefix("report")->group(function(){
                    Route::get("/", [WarehousePenerimaanBarangReportController::class, "index"])->name("warehouse.penerimaan-barang.report");
                    Route::get("rekap", [WarehousePenerimaanBarangReportController::class, "rekap"])->name("warehouse.penerimaan-barang.report.rekap");
                });
            });
        });

        Route::prefix("procurement")->group(function () {
            Route::prefix("purchase-request")->group(function () {
                Route::prefix("pharmacy")->group(function () {
                    Route::get("/", [ProcurementPurchaseRequestPharmacyController::class, "index"])->name("procurement.purchase-request.pharmacy");
                    Route::get("/create", [ProcurementPurchaseRequestPharmacyController::class, "create"])->name("procurement.purchase-request.pharmacy.create");
                    Route::get("/print/{id}", [ProcurementPurchaseRequestPharmacyController::class, "print"])->name("procurement.purchase-request.pharmacy.print");
                    Route::get("/edit/{id}", [ProcurementPurchaseRequestPharmacyController::class, "edit"])->name("procurement.purchase-request.pharmacy.edit");
                });

                Route::prefix("non-pharmacy")->group(function () {
                    Route::get("/", [ProcurementPurchaseRequestNonPharmacyController::class, "index"])->name("procurement.purchase-request.non-pharmacy");
                    Route::get("/create", [ProcurementPurchaseRequestNonPharmacyController::class, "create"])->name("procurement.purchase-request.non-pharmacy.create");
                    Route::get("/print/{id}", [ProcurementPurchaseRequestNonPharmacyController::class, "print"])->name("procurement.purchase-request.non-pharmacy.print");
                    Route::get("/edit/{id}", [ProcurementPurchaseRequestNonPharmacyController::class, "edit"])->name("procurement.purchase-request.non-pharmacy.edit");
                });
            });

            Route::prefix("approval-pr")->group(function () {
                Route::prefix("pharmacy")->group(function () {
                    Route::get("/", [ProcurementPRApprovalPharmacy::class, "index"])->name("procurement.approval-pr.pharmacy");
                    Route::get("/print/{id}", [ProcurementPRApprovalPharmacy::class, "print"])->name("procurement.approval-pr.pharmacy.print");
                    Route::get("/edit/{id}", [ProcurementPRApprovalPharmacy::class, "edit"])->name("procurement.approval-pr.pharmacy.edit");
                });

                Route::prefix("non-pharmacy")->group(function () {
                    Route::get("/", [ProcurementPRApprovalNonPharmacy::class, "index"])->name("procurement.approval-pr.non-pharmacy");
                    Route::get("/print/{id}", [ProcurementPRApprovalNonPharmacy::class, "print"])->name("procurement.approval-pr.non-pharmacy.print");
                    Route::get("/edit/{id}", [ProcurementPRApprovalNonPharmacy::class, "edit"])->name("procurement.approval-pr.non-pharmacy.edit");
                });
            });

            Route::prefix("purchase-order")->group(function () {
                Route::prefix("pharmacy")->group(function () {
                    Route::get("/", [ProcurementPurchaseOrderPharmacyController::class, "index"])->name("procurement.purchase-order.pharmacy");
                    Route::get("/create", [ProcurementPurchaseOrderPharmacyController::class, "create"])->name("procurement.purchase-order.pharmacy.create");
                    Route::get("/print/{id}", [ProcurementPurchaseOrderPharmacyController::class, "print"])->name("procurement.purchase-order.pharmacy.print");
                    Route::get("/edit/{id}", [ProcurementPurchaseOrderPharmacyController::class, "edit"])->name("procurement.purchase-order.pharmacy.edit");
                });

                Route::prefix("non-pharmacy")->group(function () {
                    Route::get("/", [ProcurementPurchaseOrderNonPharmacyController::class, "index"])->name("procurement.purchase-order.non-pharmacy");
                    Route::get("/create", [ProcurementPurchaseOrderNonPharmacyController::class, "create"])->name("procurement.purchase-order.non-pharmacy.create");
                    Route::get("/print/{id}", [ProcurementPurchaseOrderNonPharmacyController::class, "print"])->name("procurement.purchase-order.non-pharmacy.print");
                    Route::get("/edit/{id}", [ProcurementPurchaseOrderNonPharmacyController::class, "edit"])->name("procurement.purchase-order.non-pharmacy.edit");
                });
            });

            Route::prefix("approval-po")->group(function () {
                Route::prefix("pharmacy")->group(function () {
                    Route::get("/", [ProcurementPOApprovalPharmacy::class, "index"])->name("procurement.approval-po.pharmacy");
                    Route::get("/edit/{id}", [ProcurementPOApprovalPharmacy::class, "edit"])->name("procurement.approval-po.pharmacy.edit");
                });

                Route::prefix("non-pharmacy")->group(function () {
                    Route::get("/", [ProcurementPOApprovalNonPharmacy::class, "index"])->name("procurement.approval-po.non-pharmacy");
                    Route::get("/edit/{id}", [ProcurementPOApprovalNonPharmacy::class, "edit"])->name("procurement.approval-po.non-pharmacy.edit");
                });

                Route::prefix("ceo")->group(function () {
                    Route::get("/", [ProcurementPOApprovalCEO::class, "index"])->name("procurement.approval-po.ceo");
                    Route::get("/edit/{type}/{id}", [ProcurementPOApprovalCEO::class, "edit"])->name("procurement.approval-po.ceo.edit");
                });
            });

            Route::prefix("setup")->group(function () {
                Route::get("supplier", [ProcurementSetupSupplier::class, "index"])->name("procurement.setup.supplier");
            });
        });

        Route::prefix('igd')->group(function () {
            Route::get('/daftar-pasien', [IGDController::class, 'index'])->name('igd.daftar-pasien');
            Route::get('/catatan-medis', [IGDController::class, 'catatanMedis'])->name('igd.catatan-medis');
            Route::prefix('/reports')->group(function () {
                Route::get('igd', [IGDController::class, 'reprotIGD'])->name('igd.reports');
                Route::get('rekap-per-dokter', [IGDController::class, 'rekapPerDokter'])->name('igd.reports.rekap-per-dokter');
            });
        });

        Route::prefix('rawat-inap')->group(function () {
            Route::get('/daftar-pasien', [IGDController::class, 'index'])->name('rawat-inap.daftar-pasien');
            Route::get('/catatan-medis', [IGDController::class, 'catatanMedis'])->name('rawat-inap.catatan-medis');
            Route::prefix('/reports')->group(function () {
                Route::get('rawat-inap', [IGDController::class, 'reprotIGD'])->name('rawat-inap.reports');
                Route::get('laporan-per-tanggal', [IGDController::class, 'reportPerTanggal'])->name('rawat-inap.reports.per-tanggal');
                Route::get('transfer', [IGDController::class, 'reportTransfer'])->name('rawat-inap.reports.transfer');
                Route::get('pasien-aktif', [IGDController::class, 'reportPasienAktif'])->name('rawat-inap.reports.pasien-aktif');
            });
        });

        Route::prefix('vk')->group(function () {
            Route::get('/daftar-pasien', [IGDController::class, 'index'])->name('vk.daftar-pasien');
            Route::prefix('reports')->group(function () {
                Route::get('order-pasien', [IGDController::class, 'orderPasien'])->name('vk.reports.order-pasien');
                Route::get('rekap-kunjungan', [IGDController::class, 'rekapKunjungan'])->name('vk.reports.rekap-kunjungan');
                Route::get('10-besar-tindakan', [IGDController::class, '10BesarTindakan'])->name('vk.reports.10-besar-tindakan');
            });
        });

        Route::prefix('ok')->group(function () {
            Route::get('/daftar-pasien', [IGDController::class, 'index'])->name('ok.daftar-pasien');
            Route::prefix('reports')->group(function () {
                Route::get('order-pasien', [IGDController::class, 'orderPasien'])->name('ok.reports.order-pasien');
                Route::get('rekap-kunjungan', [IGDController::class, 'rekapKunjungan'])->name('ok.reports.rekap-kunjungan');
                Route::get('10-besar-tindakan', [IGDController::class, '10BesarTindakan'])->name('ok.reports.10-besar-tindakan');
            });
        });

        Route::prefix('radiologi')->group(function () {
            Route::get('list-order', [RadiologiController::class, 'index'])->name('radiologi.list-order');
            Route::get('simulasi-harga', [RadiologiController::class, 'simulasiHarga'])->name('radiologi.simulasi-harga');
            Route::get('template-hasil', [RadiologiController::class, 'templateHasil'])->name('radiologi.template-hasil');
            Route::get('laporan', [RadiologiController::class, 'report'])->name('radiologi.report');
            Route::get('laporan-view/{fromDate}/{endDate}/{tipe_rawat}/{group_parameter}/{penjamin}/{radiografer}', [RadiologiController::class, 'reportView'])->name('radiologi.report.view');
            Route::get('nota-order/{id}', [RadiologiController::class, 'notaOrder'])->name('radiologi.nota-order');
            Route::get('hasil-order/{id}', [RadiologiController::class, 'hasilOrder'])->name('radiologi.hasil-order');
            Route::get('label-order/{id}', [RadiologiController::class, 'labelOrder'])->name('radiologi.label-order');
            Route::get('edit-order/{id}', [RadiologiController::class, 'editOrder'])->name('radiologi.edit-order');
            Route::get('edit-hasil-parameter/{id}', [RadiologiController::class, 'editHasilParameter'])->name('radiologi.edit-hasil-parameter');
            Route::get("order", [RadiologiController::class, 'order'])->name('radiologi.order');
            Route::get("popup/pilih-pasien/{poli}", [RadiologiController::class, 'popupPilihPasien'])->name('radiologi.popup.pilih-pasien');
        });

        Route::prefix('laboratorium')->group(function () {
            Route::get('list-order', [LaboratoriumController::class, 'index'])->name('laboratorium.list-order');
            Route::get('label-order/{id}', [LaboratoriumController::class, 'labelOrder'])->name('laboratorium.label-order');
            Route::get('hasil-order/{id}', [LaboratoriumController::class, 'hasilOrder'])->name('laboratorium.hasil-order');
            Route::get('edit-order/{id}', [LaboratoriumController::class, 'editOrder'])->name('laboratorium.edit-order');
            Route::get('nota-order/{id}', [LaboratoriumController::class, 'notaOrder'])->name('laboratorium.nota-order');
            Route::get('simulasi-harga', [LaboratoriumController::class, 'simulasiHarga'])->name('laboratorium.simulasi-harga');
            Route::get("order", [LaboratoriumController::class, 'order'])->name('laboratorium.order');
            Route::get('laporan/parameter-pemeriksaan', [LaboratoriumController::class, 'reportParameter'])->name('laboratorium.report.parameter');
            Route::get('laporan/pasien-per-pemeriksaan', [LaboratoriumController::class, 'reportPatient'])->name('laboratorium.report.patient');
            Route::get("popup/pilih-pasien/{poli}", [LaboratoriumController::class, 'popupPilihPasien'])->name('laboratorium.popup.pilih-pasien');
            Route::get('laporan-parameter-view/{fromDate}/{endDate}/{tipe_rawat}/{penjamin}', [LaboratoriumController::class, 'reportParameterView'])->name('laboratorium.report-parameter.view');
            Route::get('laporan-pasien-view/{fromDate}/{endDate}/{tipe_rawat}/{penjamin}/{parameter}', [LaboratoriumController::class, 'reportPatientView'])->name('laboratorium.report-patient.view');
        });

        Route::prefix('dokter')->group(function () {
            Route::get('/daftar-pasien', [DokterController::class, 'index'])->name('dokter.daftar-pasien');
            Route::get('/template-soap', [DokterController::class, 'templateSOAP'])->name('dokter.template-soap');
        });

        Route::prefix('gizi')->group(function () {
            Route::prefix('daftar-pasien')->group(function () {
                Route::get('list-pasien', [GiziController::class, 'index'])->name('gizi.daftar-pasien.list-pasien');
                Route::get('list-order-gizi', [OrderGiziController::class, 'index'])->name('gizi.daftar-order.list-order-gizi');
            });

            Route::prefix("popup")->group(function () {
                Route::get("/order/{untuk}/{registration_id}", [OrderGiziController::class, 'create'])->name('gizi.popup.order');
                Route::get("/pilih-diet/{registration_id}", [JamMakanGiziController::class, 'create'])->name('gizi.popup.pilih-diet');
                Route::get("/label/{id_order}", [OrderGiziController::class, 'label'])->name('gizi.popup.label');
                Route::get("/bulk-label/{order_ids}", [OrderGiziController::class, 'bulk_label'])->name('gizi.popup.bulk-label');
                Route::get("/print-nota/{order_ids}", [OrderGiziController::class, 'print_nota'])->name('gizi.popup.print-nota');
                Route::get("/edit/{order_ids}", [OrderGiziController::class, 'edit'])->name('gizi.popup.edit-order');
            });

            Route::prefix('reports')->group(function () {
                Route::get('/', [GiziController::class, 'reports'])->name('gizi.reports');
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
            Route::get('transaksi-resep', [FarmasiController::class, 'transaksiResep'])->name('farmasi.transaksi-resep');
            Route::get('retur-resep', [FarmasiController::class, 'returResep'])->name('farmasi.retur-resep');
            Route::get('reponse-time', [FarmasiController::class, 'responseTime'])->name('farmasi.reponse-time');

            Route::prefix('reports')->group(function () {
                Route::get('stock-status', [FarmasiController::class, 'stokStatus'])->name('farmasi.reports.stock-status');
                Route::get('stock-detail', [FarmasiController::class, 'stockDetail'])->name('farmasi.reports.stock-detail');
                Route::get('kartu-stok', [FarmasiController::class, 'kartu-stok'])->name('farmasi.reports.kartu-stok');
                Route::get('penjualan', [FarmasiController::class, 'reportPenjualan'])->name('farmasi.reports.penjualan');
                Route::get('rekap-penjualan', [FarmasiController::class, 'reportRekapPenjualan'])->name('farmasi.reports.rekap-penjualan');
                Route::get('embalase', [FarmasiController::class, 'embalase'])->name('farmasi.reports.embalase');
            });

            Route::get('antrian', [FarmasiController::class, 'antrian'])->name('farmasi.antrian');
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
                Route::get('farmasi', [StokRequestController::class, 'farmasi'])->name('depo.stok-request.farmasi');
                Route::get('non-farmasi', [StokRequestController::class, 'nonFarmasi'])->name('depo.stok-request-non-farmasi');
            });

            Route::prefix('distribusi-barang')->group(function () {
                Route::get('farmasi', [StokRequestController::class, 'farmasi'])->name('depo.distribusi-barang.farmasi');
                Route::get('non-farmasi', [StokRequestController::class, 'nonFarmasi'])->name('depo.distribusi-barang.non-farmasi');
            });

            Route::prefix('unit-cost')->group(function () {
                Route::get('farmasi', [DepoUnitCostController::class, 'farmasi'])->name('depo.unit-cost.farmasi');
                Route::get('nonFarmasi', [DepoUnitCostController::class, 'farmasi'])->name('depo.unit-cost.non-farmasi');
            });
        });

        Route::prefix('insiden')->group(function () {
            Route::get('/', [InsidenController::class, 'index'])->name('insiden');
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

        Route::prefix('bpjs')->group(function () {
            Route::prefix('bridging-vclaim')->group(function () {
                Route::get('list-registrasi-sep', [BridgingVclaimController::class, 'listRegistrasiSEP'])->name('bpjs.bridging-vclaim.list-registrasi-sep');
                Route::get('persetujuan-sep', [BridgingVclaimController::class, 'persetujuanSEP'])->name('bpjs.bridging-vclaim.persetujuan-sep');
                Route::get('rujukan', [BridgingVclaimController::class, 'rujukan'])->name('bpjs.bridging-vclaim.rujukan');
                Route::get('lembar-pengajuan-klaim', [BridgingVclaimController::class, 'lembarPengajuanKlaim'])->name('bpjs.bridging-vclaim.lembar-pengajuan-klaim');
                Route::get('detail-sep', [BridgingVclaimController::class, 'detailSEP'])->name('bpjs.bridging-vclaim.detail-sep');
                Route::get('detail-sep', [BridgingVclaimController::class, 'detailSEP'])->name('bpjs.bridging-vclaim.detail-sep');
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
        });
    });
    // Route::get('/rnc', [RevenueAndCostCenterController::class, 'index'])->name('master.data.setup.rnc.index');
    // Route::post('/rnc', [RevenueAndCostCenterController::class, 'store'])->name('master.data.setup.rnc.store');
    // Route::put('/rnc', [RevenueAndCostCenterController::class, 'update'])->name('master.data.setup.rnc.update');

    // Route::get('/master-rl', [MasterRLController::class, 'index'])->name('master.data.setup.rl');
    // Route::post('/master-rl/tambah', [MasterRLController::class, 'store'])->name('master.data.setup.rl.tambah');
    // Route::put('/master-rl/edit/{master_r_l:id}', [MasterRLController::class, 'update'])->name('master.data.setup.rl.update');
    // Route::get('/master-rl/delete/{master_r_l:id}', [MasterRLController::class, 'destroy'])->name('master.data.setup.rl.delete');

    // Route::get('/master-layanan-rl', [MasterLayananRLController::class, 'index'])->name('master.data.setup.layanan.rl');
    // Route::post('/master-layanan-rl/tambah', [MasterLayananRLController::class, 'store'])->name('master.data.setup.layanan.rl.tambah');
    // Route::put('/master-layanan-rl/edit/{master_layanan_r_l:id}', [MasterLayananRLController::class, 'update'])->name('master.data.setup.layanan.rl.update');
    // Route::get('/master-layanan-rl/delete/{master_layanan_r_l:id}', [MasterLayananRLController::class, 'destroy'])->name('master.data.setup.layanan.rl.delete');
});
