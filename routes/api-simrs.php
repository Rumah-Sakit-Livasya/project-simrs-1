<?php

use App\Http\Controllers\API\AntrianController;
use App\Http\Controllers\API\DiagnosisCategoryController;
use App\Http\Controllers\API\EmployeeController;
use App\Http\Controllers\API\InfusionMonitorController;
use App\Http\Controllers\API\InterventionController;
use App\Http\Controllers\API\NursingDiagnosisController;
use App\Http\Controllers\API\SbarController;
use App\Http\Controllers\API\TtsController;
use App\Http\Controllers\DietGiziController;
use App\Http\Controllers\FarmasiPlasma;
use App\Http\Controllers\FarmasiResepController;
use App\Http\Controllers\FarmasiResepHarianController;
use App\Http\Controllers\FarmasiResepResponseController;
use App\Http\Controllers\FarmasiReturResepController;
use App\Http\Controllers\FarmasiSignaController;
use App\Http\Controllers\JamMakanGiziController;
use App\Http\Controllers\KategoriGiziController;
use App\Http\Controllers\MakananGiziController;
use App\Http\Controllers\MenuGiziController;
use App\Http\Controllers\OrderGiziController;
use App\Http\Controllers\OrderLaboratoriumController;
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
use App\Http\Controllers\SIMRS\AssesmentGadarController;
use App\Http\Controllers\SIMRS\BedController;
use App\Http\Controllers\SIMRS\BPJS\WsBPJSController;
use App\Http\Controllers\SIMRS\CPPT\CPPTController;
use App\Http\Controllers\SIMRS\DepartementController;
use App\Http\Controllers\SIMRS\DoctorVisitController;
use App\Http\Controllers\SIMRS\ERMController;
use App\Http\Controllers\SIMRS\EthnicController;
use App\Http\Controllers\SIMRS\EWSAnakController;
use App\Http\Controllers\SIMRS\EWSDewasaController;
use App\Http\Controllers\SIMRS\EWSObstetriController;
use App\Http\Controllers\SIMRS\Gizi\GiziController;
use App\Http\Controllers\SIMRS\GrupParameterRadiologiController;
use App\Http\Controllers\SIMRS\GrupSuplier\GrupSuplierController;
use App\Http\Controllers\SIMRS\GrupTindakanMedisController;
use App\Http\Controllers\SIMRS\HargaJual\MarginHargaJualController;
use App\Http\Controllers\SIMRS\IGD\IGDController;
use App\Http\Controllers\SIMRS\Icd10Controller;
use App\Http\Controllers\SIMRS\Icd9Controller;
use App\Http\Controllers\SIMRS\JadwalDokter\JadwalDokterController;
use App\Http\Controllers\SIMRS\KategoriRadiologiController;
use App\Http\Controllers\SIMRS\KelasRawatController;
use App\Http\Controllers\SIMRS\KepustakaanController;
use App\Http\Controllers\SIMRS\Laboratorium\GrupParameterLaboratoriumController;
use App\Http\Controllers\SIMRS\Laboratorium\KategoriLaboratorumController;
use App\Http\Controllers\SIMRS\Laboratorium\NilaiNormalLaboratoriumController;
use App\Http\Controllers\SIMRS\Laboratorium\ParameterLaboratoriumController;
use App\Http\Controllers\SIMRS\Laboratorium\TarifParameterLaboratoriumController;
use App\Http\Controllers\SIMRS\Laboratorium\TipeLaboratoriumController;
use App\Http\Controllers\SIMRS\LocationController;
use App\Http\Controllers\SIMRS\Operasi\JenisOperasiController;
use App\Http\Controllers\SIMRS\Operasi\KategoriOperasiController;
use App\Http\Controllers\SIMRS\Operasi\TindakanOperasiController;
use App\Http\Controllers\SIMRS\Operasi\TipeOperasiController;
use App\Http\Controllers\SIMRS\OrderRadiologiController;
use App\Http\Controllers\SIMRS\OrderTindakanMedisController;
use App\Http\Controllers\SIMRS\ParameterRadiologiController;
use App\Http\Controllers\SIMRS\Pengkajian\FormBuilderController;
use App\Http\Controllers\SIMRS\Pengkajian\PengkajianController;
use App\Http\Controllers\SIMRS\Pengkajian\PengkajianDokterRajalController;
use App\Http\Controllers\SIMRS\PengkajianDokterIgdController;
use App\Http\Controllers\SIMRS\Penjamin\GroupPenjaminController;
use App\Http\Controllers\SIMRS\Penjamin\PenjaminController;
use App\Http\Controllers\SIMRS\Peralatan\PeralatanController;
use App\Http\Controllers\SIMRS\Persalinan\DaftarPersalinanController;
use App\Http\Controllers\SIMRS\Persalinan\KategoriPersalinanController;
use App\Http\Controllers\SIMRS\Persalinan\TarifPersalinanController;
use App\Http\Controllers\SIMRS\Persalinan\TipePersalinanController;
use App\Http\Controllers\SIMRS\Poliklinik\LayananController;
use App\Http\Controllers\SIMRS\Radiologi\RadiologiController;
use App\Http\Controllers\SIMRS\Radiologi\TarifParameterRadiologiController;
use App\Http\Controllers\SIMRS\RegistrationController;
use App\Http\Controllers\SIMRS\ResumeMedisRajal\ResumeMedisRajalController;
use App\Http\Controllers\SIMRS\RoomController;
use App\Http\Controllers\SIMRS\RujukAntarRSController;
use App\Http\Controllers\SIMRS\Setup\BiayaAdministrasiRawatInapController;
use App\Http\Controllers\SIMRS\Setup\BiayaMateraiController;
use App\Http\Controllers\SIMRS\Setup\TarifRegistrasiController;
use App\Http\Controllers\SIMRS\TarifKelasRawatController;
use App\Http\Controllers\SIMRS\TindakanMedisController;
use App\Http\Controllers\SIMRS\WhoIcdController;
use App\Http\Controllers\TarifOperasiController;
use App\Http\Controllers\WarehouseBarangFarmasiController;
use App\Http\Controllers\WarehouseBarangNonFarmasiController;
use App\Http\Controllers\WarehouseDistribusiBarangFarmasiController;
use App\Http\Controllers\WarehouseDistribusiBarangNonFarmasiController;
use App\Http\Controllers\WarehouseGolonganBarangController;
use App\Http\Controllers\WarehouseKategoriBarangController;
use App\Http\Controllers\WarehouseKelompokBarangController;
use App\Http\Controllers\WarehouseMasterGudangController;
use App\Http\Controllers\WarehousePabrikController;
use App\Http\Controllers\WarehousePenerimaanBarangFarmasiController;
use App\Http\Controllers\WarehousePenerimaanBarangNonFarmasiController;
use App\Http\Controllers\WarehousePurchaseRequestNonPharmacy;
use App\Http\Controllers\WarehousePurchaseRequestPharmacy;
use App\Http\Controllers\WarehouseReportStockDetail;
use App\Http\Controllers\WarehouseReturBarangController;
use App\Http\Controllers\WarehouseSatuanBarangController;
use App\Http\Controllers\WarehouseSetupMinMaxStockController;
use App\Http\Controllers\WarehouseStockAdjustmentController;
use App\Http\Controllers\WarehouseStockOpnameDraft;
use App\Http\Controllers\WarehouseStockOpnameFinal;
use App\Http\Controllers\WarehouseStockOpnameGudangController;
use App\Http\Controllers\WarehouseStockRequestNonPharmacyController;
use App\Http\Controllers\WarehouseStockRequestPharmacyController;
use App\Http\Controllers\WarehouseSupplierController;
use App\Http\Controllers\WarehouseZatAktifController;
use App\Models\SIMRS\OrderTindakanMedis;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage; // Pastikan path ini benar

Route::middleware(['web', 'auth'])->prefix('simrs')->group(function () {
    Route::get('/signature/{filename}', function ($filename) {
        // Pastikan user terautentikasi memiliki akses untuk file ini
        $path = 'employee/ttd/' . $filename;

        if (Storage::disk('private')->exists($path)) {
            return Storage::disk('private')->response($path);
        }

        abort(404, 'File not found');
    })->name('signature.show');

    // Route::get('/api/registration/{id}', [RegistrationController::class, 'getRegistrationData']);
    Route::get('get-registrasi-data/{registrasiId}', [RegistrationController::class, 'getRegistrationData'])->name('registration.get');
    Route::get('get-medical-actions/{registrationId}', [OrderTindakanMedisController::class, 'getMedicalActions'])->name('medical.action.get');
    Route::delete('delete-medical-action/{id}', [OrderTindakanMedis::class, 'destroy'])->name('medical.action.destroy');
    Route::post('order-tindakan-medis/', [OrderTindakanMedisController::class, 'store'])->name('tindakan.medis.store');

    Route::post('order-radiologi/', [OrderRadiologiController::class, 'store'])->name('order.radiologi.store');
    Route::post('order-radiologi-otc/', [OrderRadiologiController::class, 'storeOTC'])->name('order.radiologi.store-otc');
    Route::post('edit-order-radiologi/', [OrderRadiologiController::class, 'editOrderRadiologi'])->name('order.radiologi.edit-order');
    Route::post('konfirmasi-tagihan-order-radiologi/', [OrderRadiologiController::class, 'confirmPayment'])->name('order.radiologi.confirm-payment');
    Route::post('verifikasi-order-parameter-radiologi/', [OrderRadiologiController::class, 'verificate'])->name('order.radiologi.verificate');
    Route::post('update-pemeriksaan-parameter-radiologi/', [OrderRadiologiController::class, 'parameterCheckUpdate'])->name('order.radiologi.parameter-check-update');
    Route::post('upload-photo-parameter-radiologi/', [OrderRadiologiController::class, 'uploadPhotoParameter'])->name('order.radiologi.upload-photo-parameter');

    Route::post('simpan-template-radiologi/{id}', [RadiologiController::class, 'simpanTemplateHasil'])->name('radiologi.template.simpan');
    Route::post('delete-template-radiologi/{id}', [RadiologiController::class, 'deleteTemplate'])->name('radiologi.template.delete');

    Route::prefix('laboratorium')->group(function () {
        Route::post('/order', [OrderLaboratoriumController::class, 'store'])->name('order.laboratorium.store');
        Route::post('/pay', [OrderLaboratoriumController::class, 'confirmPayment'])->name('order.laboratorium.confirm-payment');
        Route::post('/edit-order', [OrderLaboratoriumController::class, 'editOrderLaboratorium'])->name('order.laboratorium.edit-order');
        Route::post('/parameter-verify', [OrderLaboratoriumController::class, 'verificate'])->name('order.laboratorium.verificate');
        Route::post('/parameter-delete', [OrderLaboratoriumController::class, 'deleteParameter'])->name('order.laboratorium.delete-parameter');
    });

    Route::prefix('gizi')->name('gizi.')->group(function () {

        Route::prefix('order')->name('order.')->group(function () {
            // Rute untuk Order Gizi
            Route::get('/', [OrderGiziController::class, 'index'])->name('index');
            Route::get('/datatable', [OrderGiziController::class, 'datatable'])->name('datatable');
            Route::put('/status/{id}', [OrderGiziController::class, 'updateStatus'])->name('update_status');
            Route::post('/bulk-status', [OrderGiziController::class, 'bulkUpdateStatus'])->name('bulk_update_status');

            Route::post('/store', [OrderGiziController::class, 'store'])->name('store');
            Route::post('/update/status', [OrderGiziController::class, 'update_status'])->name('update.status');
            Route::post('/update', [OrderGiziController::class, 'update'])->name('update');
        });

        // Rute untuk Daftar Pasien Gizi
        Route::prefix('pasien')->name('pasien.')->group(function () {
            Route::get('/', [GiziController::class, 'index'])->name('index');
            Route::get('/datatable', [GiziController::class, 'datatable'])->name('datatable');
        });

        Route::prefix('kategori')->name('kategori.')->group(function () {
            // Rute untuk Kategori Gizi
            Route::get('/', [KategoriGiziController::class, 'index'])->name('index');
            Route::get('/datatable', [KategoriGiziController::class, 'datatable'])->name('datatable');
            Route::post('/', [KategoriGiziController::class, 'store'])->name('store');
            Route::put('/{id}', [KategoriGiziController::class, 'update'])->name('update');
            Route::delete('/{id}', [KategoriGiziController::class, 'destroy'])->name('destroy');

            // Route::post('/store', [KategoriGiziController::class, 'store'])->name('kategori.gizi.store');
            // Route::put('/update/{id}/', [KategoriGiziController::class, 'update'])->name('kategori.gizi.update');
            // Route::delete('/destroy/{id}/', [KategoriGiziController::class, 'destroy'])->name('kategori.gizi.destroy');
        });

        // Route::prefix('makanan')->group(function () {
        //     Route::post('/store', [MakananGiziController::class, 'store'])->name('makanan.gizi.store');
        //     Route::put('/update/{id}/', [MakananGiziController::class, 'update'])->name('makanan.gizi.update');
        //     Route::delete('/destroy/{id}/', [MakananGiziController::class, 'destroy'])->name('makanan.gizi.destroy');
        // });

        Route::prefix('makanan')->name('makanan.')->group(function () {
            Route::get('/', [MakananGiziController::class, 'index'])->name('index');
            Route::get('/datatable', [MakananGiziController::class, 'datatable'])->name('datatable');
            Route::post('/', [MakananGiziController::class, 'store'])->name('store');
            Route::put('/{id}', [MakananGiziController::class, 'update'])->name('update');
            Route::delete('/{id}', [MakananGiziController::class, 'destroy'])->name('destroy');
        });

        // Route::prefix('menu')->name('menu.')->group(function () {
        //     Route::post('/store', [MenuGiziController::class, 'store'])->name('store');
        //     Route::put('/update/{id}/', [MenuGiziController::class, 'update'])->name('update');
        //     Route::delete('/destroy/{id}/', [MenuGiziController::class, 'destroy'])->name('destroy');
        // });

        // Rute untuk Menu Gizi
        Route::prefix('menu')->name('menu.')->group(function () {
            Route::get('/', [MenuGiziController::class, 'index'])->name('index');
            Route::get('/datatable', [MenuGiziController::class, 'datatable'])->name('datatable');
            Route::post('/', [MenuGiziController::class, 'store'])->name('store');
            Route::put('/{id}', [MenuGiziController::class, 'update'])->name('update');
            Route::delete('/{id}', [MenuGiziController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('jam-makan')->group(function () {
            Route::post('/store', [JamMakanGiziController::class, 'store'])->name('jam-makan.gizi.store');
            Route::put('/update/{id}/', [JamMakanGiziController::class, 'update'])->name('jam-makan.gizi.update');
            Route::delete('/destroy/{id}/', [JamMakanGiziController::class, 'destroy'])->name('jam-makan.gizi.destroy');
        });

        Route::prefix('auto-diet')->name('diet.')->group(function () {
            Route::post('/store', [DietGiziController::class, 'store'])->name('store');
            Route::put('/update/{id}/', [DietGiziController::class, 'update'])->name('update');
            Route::delete('/destroy/{id}/', [DietGiziController::class, 'destroy'])->name('destroy');
        });

        // Route untuk Skrining Gizi Dewasa
        // Route::get('/erm/skrining-gizi-dewasa/{registrasi_id}', [ERMController::class, 'createSkriningGiziDewasa'])->name('erm.skrining-gizi-dewasa.create');
        Route::post('/erm/skrining-gizi-dewasa/store', [ERMController::class, 'storeSkriningGiziDewasa'])->name('erm.skrining-gizi-dewasa.store');
        Route::get('/erm/cppt/gizi/get', [ERMController::class, 'GetCpptGizi'])->name('cppt.gizi.get');
    });

    Route::prefix('farmasi')->group(function () {
        Route::prefix('transaksi-resep')->group(function () {
            Route::post('/store', [FarmasiResepController::class, 'store'])->name('farmasi.transaksi-resep.store');
            Route::get('/gudang-default-ranap', [FarmasiResepController::class, 'gudang_default_ranap'])->name('farmasi.transaksi-resep.gudang-default-ranap');
            Route::get('/gudang-default-rajal', [FarmasiResepController::class, 'gudang_default_rajal'])->name('farmasi.transaksi-resep.gudang-default-rajal');
            Route::get('/obat/{gudang_id}', [FarmasiResepController::class, 'get_obat'])->name('farmasi.get-obat');
            Route::get('/batch/{gudang_id}/{barang_id}', [FarmasiResepController::class, 'get_batch'])->name('farmasi.get-batch');
            Route::put('/update/telaah/{id}', [FarmasiResepController::class, 'update_telaah'])->name('farmasi.update.telaah');
            Route::put('/update/resep/{id}', [FarmasiResepController::class, 'update'])->name('farmasi.transaksi-resep.update');
            Route::delete('/destroy/{id}', [FarmasiResepController::class, 'destroy'])->name('farmasi.transaksi-resep.delete');
        });

        Route::prefix('resep-harian')->group(function () {
            Route::post('/store', [FarmasiResepHarianController::class, 'store'])->name('farmasi.resep-harian.store');
        });

        Route::prefix('response-time')->group(function () {
            Route::put('/update/{id}/{btoa}', [FarmasiResepResponseController::class, 'update'])->name('farmasi.response-time.update');
            Route::put('/update-keterangan/{id}/{btoa}', [FarmasiResepResponseController::class, 'updateKeterangan'])->name('farmasi.response-time.update-keterangan');
        });

        Route::prefix('retur-resep')->group(function () {
            Route::post('/store', [FarmasiReturResepController::class, 'store'])->name('farmasi.retur-barang.store');
            Route::delete('/destroy/{id}', [FarmasiReturResepController::class, 'destroy'])->name('farmasi.retur-barang.destroy');
            Route::get('/get/item-registration/{id}', [FarmasiReturResepController::class, 'getItemRegistration'])->name('farmasi.retur-barang.get.item-registration');
            Route::get('/get/registrations/{patient_id}', [FarmasiReturResepController::class, 'getRegistrations'])->name('farmasi.retur-barang.get.registrations');
        });

        Route::prefix('antrian-farmasi')->group(function () {
            Route::get('/get-antrian/{letter}', [FarmasiPlasma::class, 'getAntrian'])->name('farmasi.antrian-farmasi.get-antrian');
            Route::put('/update-call-status/{id}', [FarmasiPlasma::class, 'updateCallStatus'])->name('farmasi.antrian-farmasi.update-call-status');
            Route::put('/update-give-status/{id}', [FarmasiPlasma::class, 'updateGiveStatus'])->name('farmasi.antrian-farmasi.update-give-status');
        });
    });

    Route::prefix('warehouse')->group(function () {
        Route::prefix('master-data')->group(function () {
            Route::prefix('zat-aktif')->group(function () {
                Route::post('/store', [WarehouseZatAktifController::class, 'store'])->name('warehouse.master-data.zat-aktif.store');
                Route::put('/update/{id}/', [WarehouseZatAktifController::class, 'update'])->name('warehouse.master-data.zat-aktif.update');
                Route::delete('/destroy/{id}/', [WarehouseZatAktifController::class, 'destroy'])->name('warehouse.master-data.zat-aktif.destroy');
            });

            Route::prefix('satuan-barang')->group(function () {
                Route::post('/store', [WarehouseSatuanBarangController::class, 'store'])->name('warehouse.master-data.satuan-barang.store');
                Route::put('/update/{id}/', [WarehouseSatuanBarangController::class, 'update'])->name('warehouse.master-data.satuan-barang.update');
                Route::delete('/destroy/{id}/', [WarehouseSatuanBarangController::class, 'destroy'])->name('warehouse.master-data.satuan-barang.destroy');
            });

            Route::prefix('kelompok-barang')->group(function () {
                Route::post('/store', [WarehouseKelompokBarangController::class, 'store'])->name('warehouse.master-data.kelompok-barang.store');
                Route::put('/update/{id}/', [WarehouseKelompokBarangController::class, 'update'])->name('warehouse.master-data.kelompok-barang.update');
                Route::delete('/destroy/{id}/', [WarehouseKelompokBarangController::class, 'destroy'])->name('warehouse.master-data.kelompok-barang.destroy');
            });

            Route::prefix('kategori-barang')->group(function () {
                Route::post('/store', [WarehouseKategoriBarangController::class, 'store'])->name('warehouse.master-data.kategori-barang.store');
                Route::put('/update/{id}/', [WarehouseKategoriBarangController::class, 'update'])->name('warehouse.master-data.kategori-barang.update');
                Route::delete('/destroy/{id}/', [WarehouseKategoriBarangController::class, 'destroy'])->name('warehouse.master-data.kategori-barang.destroy');
            });

            Route::prefix('golongan-barang')->group(function () {
                Route::post('/store', [WarehouseGolonganBarangController::class, 'store'])->name('warehouse.master-data.golongan-barang.store');
                Route::put('/update/{id}/', [WarehouseGolonganBarangController::class, 'update'])->name('warehouse.master-data.golongan-barang.update');
                Route::delete('/destroy/{id}/', [WarehouseGolonganBarangController::class, 'destroy'])->name('warehouse.master-data.golongan-barang.destroy');
            });

            Route::prefix('master-gudang')->group(function () {
                Route::post('/store', [WarehouseMasterGudangController::class, 'store'])->name('warehouse.master-data.master-gudang.store');
                Route::put('/update/{id}/', [WarehouseMasterGudangController::class, 'update'])->name('warehouse.master-data.master-gudang.update');
                Route::delete('/destroy/{id}/', [WarehouseMasterGudangController::class, 'destroy'])->name('warehouse.master-data.master-gudang.destroy');
            });

            Route::prefix('pabrik')->group(function () {
                Route::post('/store', [WarehousePabrikController::class, 'store'])->name('warehouse.master-data.pabrik.store');
                Route::put('/update/{id}/', [WarehousePabrikController::class, 'update'])->name('warehouse.master-data.pabrik.update');
                Route::delete('/destroy/{id}/', [WarehousePabrikController::class, 'destroy'])->name('warehouse.master-data.pabrik.destroy');
            });

            Route::prefix('supplier')->group(function () {
                Route::post('/store', [WarehouseSupplierController::class, 'store'])->name('warehouse.master-data.supplier.store');
                Route::put('/update/{id}/', [WarehouseSupplierController::class, 'update'])->name('warehouse.master-data.supplier.update');
                Route::delete('/destroy/{id}/', [WarehouseSupplierController::class, 'destroy'])->name('warehouse.master-data.supplier.destroy');
            });

            Route::prefix('barang-non-farmasi')->group(function () {
                Route::post('/store', [WarehouseBarangNonFarmasiController::class, 'store'])->name('warehouse.master-data.barang-non-farmasi.store');
                Route::put('/update/{id}/', [WarehouseBarangNonFarmasiController::class, 'update'])->name('warehouse.master-data.barang-non-farmasi.update');
                Route::delete('/destroy/{id}/', [WarehouseBarangNonFarmasiController::class, 'destroy'])->name('warehouse.master-data.barang-non-farmasi.destroy');
            });

            Route::prefix('barang-farmasi')->group(function () {
                Route::post('/store', [WarehouseBarangFarmasiController::class, 'store'])->name('warehouse.master-data.barang-farmasi.store');
                Route::put('/update/{id}/', [WarehouseBarangFarmasiController::class, 'update'])->name('warehouse.master-data.barang-farmasi.update');
                Route::delete('/destroy/{id}/', [WarehouseBarangFarmasiController::class, 'destroy'])->name('warehouse.master-data.barang-farmasi.destroy');
            });

            Route::prefix('setup-min-max-stock')->group(function () {
                Route::post('/store', [WarehouseSetupMinMaxStockController::class, 'store'])->name('warehouse.master-data.setup-min-max-stock.store');
                // Route::put('/update/{id}/', [WarehouseSetupMinMaxStockController::class, 'update'])->name('warehouse.master-data.setup-min-max-stock.update');
                // Route::delete('/destroy/{id}/', [WarehouseSetupMinMaxStockController::class, 'destroy'])->name('warehouse.master-data.setup-min-max-stock.destroy');

                Route::prefix('get')->group(function () {
                    Route::get('/gudang/{id}/', [WarehouseSetupMinMaxStockController::class, 'get_gudang'])->name('warehouse.master-data.setup-min-max-stock.get.gudang');
                });
            });
        });

        Route::prefix('purchase-request')->group(function () {
            Route::prefix('pharmacy')->group(function () {
                Route::post('/store', [WarehousePurchaseRequestPharmacy::class, 'store'])->name('warehouse.purchase-request.pharmacy.store');
                Route::put('/update/{id}', [WarehousePurchaseRequestPharmacy::class, 'update'])->name('warehouse.purchase-request.pharmacy.update');
                Route::delete('/destroy/{id}', [WarehousePurchaseRequestPharmacy::class, 'destroy'])->name('warehouse.purchase-request.pharmacy.delete');
                Route::get('/get/item-gudang/{gudang_id}', [WarehousePurchaseRequestPharmacy::class, 'get_item_gudang'])->name('warehouse.purchase-request.pharmacy.get.item-gudang');
            });

            Route::prefix('non-pharmacy')->group(function () {
                Route::post('/store', [WarehousePurchaseRequestNonPharmacy::class, 'store'])->name('warehouse.purchase-request.non-pharmacy.store');
                Route::put('/update/{id}', [WarehousePurchaseRequestNonPharmacy::class, 'update'])->name('warehouse.purchase-request.non-pharmacy.update');
                Route::delete('/destroy/{id}', [WarehousePurchaseRequestNonPharmacy::class, 'destroy'])->name('warehouse.purchase-request.non-pharmacy.delete');
                Route::get('/get/item-gudang/{gudang_id}', [WarehousePurchaseRequestNonPharmacy::class, 'get_item_gudang'])->name('warehouse.purchase-request.non-pharmacy.get.item-gudang');
            });
        });

        Route::prefix('penerimaan-barang')->group(function () {
            Route::prefix('pharmacy')->group(function () {
                Route::post('/store', [WarehousePenerimaanBarangFarmasiController::class, 'store'])->name('warehouse.penerimaan-barang.pharmacy.store');
                Route::put('/update/{id}', [WarehousePenerimaanBarangFarmasiController::class, 'update'])->name('warehouse.penerimaan-barang.pharmacy.update');
                Route::delete('/destroy/{id}', [WarehousePenerimaanBarangFarmasiController::class, 'destroy'])->name('warehouse.penerimaan-barang.pharmacy.delete');
                Route::get('/{pb_id}/details', [WarehousePenerimaanBarangFarmasiController::class, 'details'])->name('warehouse.penerimaan-barang.pharmacy.details');
            });

            Route::prefix('non-pharmacy')->group(function () {
                Route::post('/store', [WarehousePenerimaanBarangNonFarmasiController::class, 'store'])->name('warehouse.penerimaan-barang.non-pharmacy.store');
                Route::put('/update/{id}', [WarehousePenerimaanBarangNonFarmasiController::class, 'update'])->name('warehouse.penerimaan-barang.non-pharmacy.update');
                Route::delete('/destroy/{id}', [WarehousePenerimaanBarangNonFarmasiController::class, 'destroy'])->name('warehouse.penerimaan-barang.non-pharmacy.delete');
            });

            Route::prefix('retur-barang')->group(function () {
                Route::post('/store', [WarehouseReturBarangController::class, 'store'])->name('warehouse.penerimaan-barang.retur-barang.store');
                Route::delete('/destroy/{id}', [WarehouseReturBarangController::class, 'destroy'])->name('warehouse.penerimaan-barang.retur-barang.delete');
                Route::get('/get/item-supplier/{supplier_id}', [WarehouseReturBarangController::class, 'get_items'])->name('warehouse.penerimaan-barang.retur-barang.get.items');
            });
        });

        Route::prefix('stock-request')->group(function () {
            // prefix "pharmacy"
            Route::prefix('pharmacy')->group(function () {
                Route::post('/store', [WarehouseStockRequestPharmacyController::class, 'store'])->name('warehouse.stock-request.pharmacy.store');
                Route::put('/update/{id}', [WarehouseStockRequestPharmacyController::class, 'update'])->name('warehouse.stock-request.pharmacy.update');
                Route::delete('/destroy/{id}', [WarehouseStockRequestPharmacyController::class, 'destroy'])->name('warehouse.stock-request.pharmacy.delete');
                Route::get('/get/item-gudang/{asal_gudang_id}/{tujuan_gudang_id}', [WarehouseStockRequestPharmacyController::class, 'get_item_gudang'])->name('warehouse.stock-request.pharmacy.get.item-gudang');
            });

            Route::prefix('non-pharmacy')->group(function () {
                Route::post('/store', [WarehouseStockRequestNonPharmacyController::class, 'store'])->name('warehouse.stock-request.non-pharmacy.store');
                Route::put('/update/{id}', [WarehouseStockRequestNonPharmacyController::class, 'update'])->name('warehouse.stock-request.non-pharmacy.update');
                Route::delete('/destroy/{id}', [WarehouseStockRequestNonPharmacyController::class, 'destroy'])->name('warehouse.stock-request.non-pharmacy.delete');
                Route::get('/get/item-gudang/{asal_gudang_id}/{tujuan_gudang_id}', [WarehouseStockRequestNonPharmacyController::class, 'get_item_gudang'])->name('warehouse.stock-request.non-pharmacy.get.item-gudang');
            });
        });

        Route::prefix('distribusi-barang')->group(function () {
            // prefix "pharmacy"
            Route::prefix('pharmacy')->group(function () {
                Route::post('/store', [WarehouseDistribusiBarangFarmasiController::class, 'store'])->name('warehouse.distribusi-barang.pharmacy.store');
                Route::put('/update/{id}', [WarehouseDistribusiBarangFarmasiController::class, 'update'])->name('warehouse.distribusi-barang.pharmacy.update');
                Route::delete('/destroy/{id}', [WarehouseDistribusiBarangFarmasiController::class, 'destroy'])->name('warehouse.distribusi-barang.pharmacy.delete');
                Route::get('/get/item-gudang/{asal_gudang_id}/{tujuan_gudang_id}', [WarehouseDistribusiBarangFarmasiController::class, 'get_item_gudang'])->name('warehouse.distribusi-barang.pharmacy.get.item-gudang');
                Route::get('/get/stock/{gudang_id}/{barang_id}/{satuan_id}', [WarehouseDistribusiBarangFarmasiController::class, 'get_stock'])->name('warehouse.distribusi-barang.pharmacy.get.stock');
            });

            Route::prefix('non-pharmacy')->group(function () {
                Route::post('/store', [WarehouseDistribusiBarangNonFarmasiController::class, 'store'])->name('warehouse.distribusi-barang.non-pharmacy.store');
                Route::put('/update/{id}', [WarehouseDistribusiBarangNonFarmasiController::class, 'update'])->name('warehouse.distribusi-barang.non-pharmacy.update');
                Route::delete('/destroy/{id}', [WarehouseDistribusiBarangNonFarmasiController::class, 'destroy'])->name('warehouse.distribusi-barang.non-pharmacy.delete');
                Route::get('/get/item-gudang/{asal_gudang_id}/{tujuan_gudang_id}', [WarehouseDistribusiBarangNonFarmasiController::class, 'get_item_gudang'])->name('warehouse.distribusi-barang.non-pharmacy.get.item-gudang');
                Route::get('/get/stock/{gudang_id}/{barang_id}/{satuan_id}', [WarehouseDistribusiBarangNonFarmasiController::class, 'get_stock'])->name('warehouse.distribusi-barang.non-pharmacy.get.stock');
            });
        });

        Route::prefix('revaluasi-stock')->group(function () {
            Route::prefix('stock-adjustment')->group(function () {
                Route::post('/login', [WarehouseStockAdjustmentController::class, 'login'])->name('warehouse.revaluasi-stock.stock-adjustment.login');
                Route::get('/get/item-gudang/{token}/{gudang_id}', [WarehouseStockAdjustmentController::class, 'get_items'])->name('warehouse.revaluasi-stock.stock-adjustment.get-items');
                Route::put('/update', [WarehouseStockAdjustmentController::class, 'update'])->name('warehouse.revaluasi-stock.stock-adjustment.update');
            });

            Route::prefix('stock-opname')->group(function () {
                Route::prefix('gudang-opname')->group(function () {
                    Route::put('/update', [WarehouseStockOpnameGudangController::class, 'update'])->name('warehouse.revaluasi-stock.stock-opname.gudang-opname.update');
                });

                Route::prefix('draft')->group(function () {
                    Route::get('/get/opname-items/{id}', [WarehouseStockOpnameDraft::class, 'get_opname_items'])->name('warehouse.revaluasi-stock.stock-opname.draft.get.opname-items');
                    Route::get('/get/opname-item-movement/{type}/{opname_id}/{si_id}', [WarehouseStockOpnameDraft::class, 'get_opname_item_movement'])->name('warehouse.revaluasi-stock.stock-opname.draft.get.opname-item-movement');
                    Route::post('/store', [WarehouseStockOpnameDraft::class, 'store'])->name('warehouse.revaluasi-stock.stock-opname.draft.store');
                });

                Route::prefix('final')->group(function () {
                    Route::get('/get/opname-items/{id}', [WarehouseStockOpnameFinal::class, 'get_opname_items'])->name('warehouse.revaluasi-stock.stock-opname.final.get.opname-items');
                    Route::post('/store', [WarehouseStockOpnameFinal::class, 'store'])->name('warehouse.revaluasi-stock.stock-opname.final.store');
                });
            });
        });

        Route::prefix('report')->group(function () {
            Route::prefix('stock-detail')->group(function () {
                Route::post('/get-items', [WarehouseReportStockDetail::class, 'get_items'])->name('api.warehouse.report.stock-detail.get-items');
                Route::post('/get-print-template', [WarehouseReportStockDetail::class, 'get_print_template'])->name('api.warehouse.report.stock-detail.get-print-template');
            });
        });
    });

    Route::prefix('farmasi')->group(function () {
        Route::prefix('laporan')->group(function () {
            Route::prefix('stock-detail')->group(function () {
                Route::post('/get-items', [WarehouseReportStockDetail::class, 'get_items'])->name('api.farmasi.report.stock-detail.get-items');
                Route::post('/get-print-template', [WarehouseReportStockDetail::class, 'get_print_template'])->name('api.farmasi.report.stock-detail.get-print-template');
            });
        });
    });

    Route::prefix('procurement')->group(function () {
        Route::prefix('purchase-request')->group(function () {
            Route::prefix('pharmacy')->group(function () {
                Route::post('/store', [ProcurementPurchaseRequestPharmacyController::class, 'store'])->name('procurement.purchase-request.pharmacy.store');
                Route::put('/update/{id}', [ProcurementPurchaseRequestPharmacyController::class, 'update'])->name('procurement.purchase-request.pharmacy.update');
                Route::delete('/destroy/{id}', [ProcurementPurchaseRequestPharmacyController::class, 'destroy'])->name('procurement.purchase-request.pharmacy.delete');
                Route::get('/get/item-gudang/{gudang_id}', [ProcurementPurchaseRequestPharmacyController::class, 'get_item_gudang'])->name('procurement.purchase-request.pharmacy.get.item-gudang');
            });

            Route::prefix('non-pharmacy')->group(function () {
                Route::post('/store', [ProcurementPurchaseRequestNonPharmacyController::class, 'store'])->name('procurement.purchase-request.non-pharmacy.store');
                Route::put('/update/{id}', [ProcurementPurchaseRequestNonPharmacyController::class, 'update'])->name('procurement.purchase-request.non-pharmacy.update');
                Route::delete('/destroy/{id}', [ProcurementPurchaseRequestNonPharmacyController::class, 'destroy'])->name('procurement.purchase-request.non-pharmacy.delete');
                Route::get('/get/item-gudang/{gudang_id}', [ProcurementPurchaseRequestNonPharmacyController::class, 'get_item_gudang'])->name('procurement.purchase-request.non-pharmacy.get.item-gudang');
            });
        });

        Route::prefix('approval-pr')->group(function () {
            Route::prefix('pharmacy')->group(function () {
                Route::put('/update/{id}', [ProcurementPRApprovalPharmacy::class, 'update'])->name('procurement.approval-pr.pharmacy.update');
            });

            Route::prefix('non-pharmacy')->group(callback: function () {
                Route::put('/update/{id}', [ProcurementPRApprovalNonPharmacy::class, 'update'])->name('procurement.approval-pr.non-pharmacy.update');
            });
        });

        Route::prefix('purchase-order')->group(function () {
            Route::prefix('pharmacy')->group(function () {
                Route::post('/store', [ProcurementPurchaseOrderPharmacyController::class, 'store'])->name('procurement.purchase-order.pharmacy.store');
                Route::put('/update/{id}', [ProcurementPurchaseOrderPharmacyController::class, 'update'])->name('procurement.purchase-order.pharmacy.update');
                Route::delete('/destroy/{id}', [ProcurementPurchaseOrderPharmacyController::class, 'destroy'])->name('procurement.purchase-order.pharmacy.delete');
                Route::patch('/get/items/', [ProcurementPurchaseOrderPharmacyController::class, 'get_items'])->name('procurement.purchase-order.pharmacy.get.items');
            });

            Route::prefix('non-pharmacy')->group(function () {
                Route::post('/store', [ProcurementPurchaseOrderNonPharmacyController::class, 'store'])->name('procurement.purchase-order.non-pharmacy.store');
                Route::put('/update/{id}', [ProcurementPurchaseOrderNonPharmacyController::class, 'update'])->name('procurement.purchase-order.non-pharmacy.update');
                Route::delete('/destroy/{id}', [ProcurementPurchaseOrderNonPharmacyController::class, 'destroy'])->name('procurement.purchase-order.non-pharmacy.delete');
                Route::patch('/get/items/', [ProcurementPurchaseOrderNonPharmacyController::class, 'get_items'])->name('procurement.purchase-order.non-pharmacy.get.items');
            });
        });

        Route::prefix('approval-po')->group(function () {
            Route::prefix('pharmacy')->group(function () {
                Route::put('/update/{id}', [ProcurementPOApprovalPharmacy::class, 'update'])->name('procurement.approval-po.pharmacy.update');
            });

            Route::prefix('non-pharmacy')->group(callback: function () {
                Route::put('/update/{id}', [ProcurementPOApprovalNonPharmacy::class, 'update'])->name('procurement.approval-po.non-pharmacy.update');
            });

            Route::prefix('ceo')->group(callback: function () {
                Route::put('/update/{id}', [ProcurementPOApprovalCEO::class, 'update'])->name('procurement.approval-po.ceo.update');
            });
        });

        Route::prefix('setup')->group(function () {
            Route::prefix('supplier')->group(function () {
                Route::post('/store', [ProcurementSetupSupplier::class, 'store'])->name('procurement.setup.supplier.store');
                Route::put('/update/{id}/', [ProcurementSetupSupplier::class, 'update'])->name('procurement.setup.supplier.update');
                Route::delete('/destroy/{id}/', [ProcurementSetupSupplier::class, 'destroy'])->name('procurement.setup.supplier.destroy');
            });
        });
    });

    Route::prefix('pengkajian')->group(function () {
        Route::prefix('rawat-jalan')->group(function () {
            Route::prefix('perawat')->group(function () {
                Route::post('/store', [PengkajianController::class, 'storeOrUpdatePengkajianRajal'])->name('pengkajian.nurse-rajal.store');
            });
            Route::prefix('dokter')->group(function () {
                Route::post('/store', [PengkajianDokterRajalController::class, 'store'])->name('pengkajian.dokter-rajal.store');
                Route::post('/igd/store', [PengkajianDokterIgdController::class, 'store'])->name('pengkajian.dokter-igd.store');
            });
        });

        Route::prefix('lanjutan')->group(function () {
            Route::post('/store', [PengkajianController::class, 'storeOrUpdatePengkajianLanjutan'])->name('pengkajian.lanjutan.store');
        });
    });

    Route::prefix('transfer-pasien-antar-ruangan')->group(function () {
        Route::post('/store', [PengkajianController::class, 'storeOrUpdateTransferPasienAntarRuangan'])->name('transfer-pasien-antar-ruangan.store');
    });

    Route::prefix('cppt')->group(function () {
        Route::prefix('rawat-jalan')->group(function () {
            Route::prefix('perawat')->group(function () {
                Route::post('/store', [CPPTController::class, 'store'])->name('cppt.rajal.perawat.store');
            });
        });
    });

    Route::prefix('layanan')->group(function () {
        Route::prefix('rawat-jalan')->group(function () {
            Route::prefix('pemakaian_alat')->group(function () {
                Route::post('/store', [LayananController::class, 'storePemakaianAlat'])->name('layanan.rajal.pemakaian_alat.store');
                Route::delete('/{id}', [LayananController::class, 'destroyPemakaianAlat'])->name('layanan.rajal.pemakaian_alat.destroy');
            });
        });
    });

    Route::prefix('igd')->group(function () {
        Route::post('/filter-pasien', [IGDController::class, 'index'])->name('igd.filter-pasien');
        Route::prefix('laporan')->group(function () {
            Route::match(['get', 'post'], '/', [IGDController::class, 'showLaporan'])->name('igd.laporan.show');
            Route::post('get-data', [IGDController::class, 'getDataLaporan'])->name('igd.laporan.get-data');
        });
    });

    Route::prefix('erm')->group(function () {
        Route::post('/filter-pasien/{path}', [ERMController::class, 'filterPasien']);
        Route::post('/save-signature/{id}', [ERMController::class, 'saveSignature'])->name('erm.save-signature');

        Route::prefix('ews-anak')->group(function () {
            Route::post('/', [EWSAnakController::class, 'store'])->name('api.erm.ews-anak.store');
            Route::get('/{id}', [EWSAnakController::class, 'getData'])->name('api.erm.ews-anak.get');
        });

        Route::prefix('ews-dewasa')->group(function () {
            Route::post('/', [EWSDewasaController::class, 'store'])->name('api.erm.ews-dewasa.store');
            Route::get('/{id}', [EWSDewasaController::class, 'getData'])->name('api.erm.ews-dewasa.get');
        });

        Route::prefix('ews-obstetri')->group(function () {
            Route::post('/', [EWSObstetriController::class, 'store'])->name('api.erm.ews-obstetri.store');
            Route::get('/{id}', [EWSObstetriController::class, 'getData'])->name('api.erm.ews-obstetri.get');
        });

        Route::prefix('assesment-keperawatan-gadar')->group(function () {
            Route::post('/', [AssesmentGadarController::class, 'store'])->name('api.erm.assesment-keperawatan-gadar.store');
            Route::get('/{id}', [AssesmentGadarController::class, 'getData'])->name('api.erm.assesment-keperawatan-gadar.get');
        });

        Route::prefix('rujuk-antar-rs')->group(function () {
            Route::post('/', [RujukAntarRSController::class, 'store'])->name('api.erm.rujuk-antar-rs.store');
            Route::get('/{id}', [RujukAntarRSController::class, 'getData'])->name('api.erm.rujuk-antar-rs.get');
        });

        Route::prefix('infusion-monitors')->name('api.infusion.')->group(function () {
            Route::get('/{registration}', [InfusionMonitorController::class, 'index'])->name('index');
            Route::post('/', [InfusionMonitorController::class, 'store'])->name('store');
            Route::get('/{monitor}/edit', [InfusionMonitorController::class, 'edit'])->name('edit');
            Route::put('/{monitor}/update', [InfusionMonitorController::class, 'update'])->name('update');
            Route::delete('/{monitor}', [InfusionMonitorController::class, 'destroy'])->name('destroy');
        });
    });
    Route::prefix('poliklinik')->group(function () {
        Route::post('/filter-pasien', [ERMController::class, 'filterPasien'])->name('poliklinik.filter-pasien');
        Route::get('/obat/{gudang_id}', [ERMController::class, 'get_obat'])->name('poliklinik.get-obat');
    });

    Route::prefix('erm')->group(function () {
        Route::post('/surveilans-infeksi/store', [ERMController::class, 'storeSurveilansInfeksi'])->name('erm.surveilans-infeksi.store');
        Route::post('/erm/discharge-planning/store', [ERMController::class, 'storeDischargePlanning'])->name('erm.discharge-planning.store');
        Route::post('/erm/checklist-keperawatan/store', [ERMController::class, 'storeChecklistKeperawatan'])->name('erm.checklist-keperawatan.store');
        Route::post('/erm/asesmen-awal-ranap/store', [ERMController::class, 'storeAsesmenAwalRanap'])->name('erm.asesmen-awal-ranap.store');
        Route::post('/erm/asesmen-awal-ranap-anak/store', [ERMController::class, 'storeAsesmenAwalRanapAnak'])->name('erm.asesmen-awal-ranap-anak.store');
        Route::post('/erm/asesmen-awal-ranap-lansia/store', [ERMController::class, 'storeAsesmenAwalRanapLansia'])->name('erm.asesmen-awal-ranap-lansia.store');
        Route::post('/erm/asesmen-awal-ranap-neonatus/store', [ERMController::class, 'storeAsesmenAwalRanapNeonatus'])->name('erm.asesmen-awal-ranap-neonatus.store');
        Route::post('/erm/asesmen-awal-kebidanan/store', [ERMController::class, 'storeAsesmenAwalKebidanan'])->name('erm.asesmen-awal-kebidanan.store');
        Route::post('/erm/pengkajian-awal-neonatus', [ERMController::class, 'storeNeonatusInitialAssesmentDoctor'])->name('erm.pengkajian-awal-neonatus.store');
        Route::post('/simrs/erm/store-asesmen-awal-dokter', [ERMController::class, 'storeAsesmenAwalDokter'])->name('erm.store.asesmen-awal-dokter');
        Route::post('/simrs/erm/store-echocardiography', [ERMController::class, 'storeEchocardiography'])->name('erm.store.echocardiography');
        Route::post('/simrs/erm/store-pemeriksaan-awal-ranap', [ERMController::class, 'storeInpatientInitialExamination'])->name('erm.store.pemeriksaan-awal-ranap');
        Route::get('/plasma-status/{id}', [PlasmaDisplayRawatJalanController::class, 'getStatus']);

        // Upload Dokumen
        Route::get('/dokumen/data/{registration}', [ERMController::class, 'getUploadedDocuments'])->name('erm.dokumen.data');
        Route::post('/dokumen/store', [ERMController::class, 'storeUploadedDocument'])->name('erm.dokumen.store');
        Route::get('/dokumen/view/{document}', [ERMController::class, 'viewUploadedDocument'])->name('erm.dokumen.view');
        Route::delete('/dokumen/destroy/{document}', [ERMController::class, 'destroyUploadedDocument'])->name('erm.dokumen.destroy');
        Route::get('/dokumen/view-file/{document}', [ERMController::class, 'streamUploadedDocument'])->name('erm.dokumen.view');


        // ==========================================================
        // DATA UMUM & PENDUKUNG
        // ==========================================================
        Route::get('/get-jadwal-dokter/{departement_id}', [CPPTController::class, 'getJadwalDokter']);

        // ==========================================================
        // PENGKAJIAN
        // ==========================================================
        Route::get('/dokter-pengkajian/{type}/{registration_number}/get', [PengkajianDokterRajalController::class, 'getPengkajian'])->name('pengkajian.dokter-rajal.get');
        Route::get('/perawat-pengkajian/{type}/{registration_number}/get', [PengkajianController::class, 'getPengkajianRajal'])->name('pengkajian.perawat-rajal.get');

        // ==========================================================
        // CPPT (CATATAN PERKEMBANGAN PASIEN TERINTEGRASI)
        // ==========================================================

        // --- CREATE ---
        // Endpoint ini menangani penyimpanan CPPT baru (bisa untuk dokter/perawat tergantung logika di controller)
        Route::post('/cppt/{type}/{registration_number}/store', [CPPTController::class, 'store'])->name('cppt.store');

        // --- READ (GET DATA) ---
        // Endpoint spesifik untuk mengambil data CPPT Dokter
        Route::get('/cppt/dokter/get', [CPPTController::class, 'getCPPTDokter'])->name('cppt.dokter.get');
        // Endpoint spesifik untuk mengambil data CPPT Perawat/Lainnya
        Route::get('/cppt/perawat/get', [CPPTController::class, 'getCPPT'])->name('cppt.perawat.get');

        // --- UPDATE ---
        // Endpoint untuk mengambil data satu CPPT spesifik yang akan diedit
        Route::get('/cppt/{cppt}/edit', [CPPTController::class, 'edit'])->name('cppt.edit');
        // Endpoint untuk menyimpan perubahan setelah diedit
        Route::put('/cppt/{cppt}/update', [CPPTController::class, 'update'])->name('cppt.update');

        // --- DELETE ---
        Route::delete('/cppt/{cppt}/destroy', [CPPTController::class, 'destroy'])->name('cppt.destroy');

        // --- AKSI LAINNYA ---
        Route::get('/cppt/{cpptId}/sbar/get', [CPPTController::class, 'getSbar'])->name('cppt.sbar.get');
        // Endpoint untuk menyimpan data dari form SBAR
        Route::post('/cppt/{cpptId}/sbar', [SbarController::class, 'storeSbar'])->name('cppt.sbar.store');
        // Endpoint untuk verifikasi CPPT
        Route::post('/cppt/{cppt}/verify', [CPPTController::class, 'verify'])->name('cppt.verify');

        // ==========================================================
        // RESUME MEDIS
        // ==========================================================
        Route::post('/dokter-resume-medis/store', [ResumeMedisRajalController::class, 'store'])->name('resume-medis.dokter-rajal.store');
        Route::get('/dokter-resume-medis-rajal/{type}/{registration_number}/get', [ResumeMedisRajalController::class, 'getResumeMedis'])->name('resume-medis.dokter-rajal.get');

        // ICD
        Route::get('/icd10/search', [Icd10Controller::class, 'search'])->name('api.local.icd10.search');
        Route::get('/icd9/search', [Icd9Controller::class, 'search'])->name('api.local.icd9.search');
    });

    Route::prefix('master-data')->group(function () {

        Route::prefix('interventions')->group(function () {
            // Routes untuk Master Intervensi (bukan apiResource)
            Route::get('/', [InterventionController::class, 'index']);
            Route::post('/', [InterventionController::class, 'store']);
            Route::get('/{id}/edit', [InterventionController::class, 'edit']);
            Route::put('/{id}', [InterventionController::class, 'update']);
            Route::patch('/{id}', [InterventionController::class, 'update']);
            Route::delete('/{id}', [InterventionController::class, 'destroy']);
        });

        Route::prefix('diagnosa-keperawatan')->group(function () {
            Route::get('diagnosis-categories/select-all', [DiagnosisCategoryController::class, 'selectAll']);
            Route::get('diagnosis-categories', [DiagnosisCategoryController::class, 'index']);
            Route::post('diagnosis-categories', [DiagnosisCategoryController::class, 'store']);
            Route::get('diagnosis-categories/{diagnosis_category}', [DiagnosisCategoryController::class, 'show']);
            Route::get('diagnosis-categories/{diagnosis_category}/edit', [DiagnosisCategoryController::class, 'edit']);
            Route::put('diagnosis-categories/{diagnosis_category}', [DiagnosisCategoryController::class, 'update']);
            Route::patch('diagnosis-categories/{diagnosis_category}', [DiagnosisCategoryController::class, 'update']);
            Route::delete('diagnosis-categories/{diagnosis_category}', [DiagnosisCategoryController::class, 'destroy']);

            // Routes untuk Diagnosa Keperawatan
            Route::get('nursing-diagnoses', [NursingDiagnosisController::class, 'index']);
            Route::post('nursing-diagnoses', [NursingDiagnosisController::class, 'store']);
            Route::get('nursing-diagnoses/{nursing_diagnosis}', [NursingDiagnosisController::class, 'show']);
            Route::get('nursing-diagnoses/{nursing_diagnosis}/edit', [NursingDiagnosisController::class, 'edit']);
            Route::put('nursing-diagnoses/{nursing_diagnosis}', [NursingDiagnosisController::class, 'update']);
            Route::patch('nursing-diagnoses/{nursing_diagnosis}', [NursingDiagnosisController::class, 'update']);
            Route::delete('nursing-diagnoses/{nursing_diagnosis}', [NursingDiagnosisController::class, 'destroy']);
        });

        Route::prefix('employee')->group(function () {
            Route::get('/doctors', [EmployeeController::class, 'getDoctors']);
        });
        Route::prefix('penjamin')->group(function () {
            Route::post('/', [PenjaminController::class, 'store'])->name('master-data.penjamin.store');
        });

        Route::get('/group-penjamin', [GroupPenjaminController::class, 'index']);
        Route::prefix('layanan-medis')->group(function () {
            Route::get('/tindakan-medis', [TindakanMedisController::class, 'getTindakanByDepartementAndKelas']);
            Route::get('/tarif-tindakan', [TindakanMedisController::class, 'getTarifTindakan']);
            Route::get('/tindakan-medis/{id}', [TindakanMedisController::class, 'getTindakan'])->name('master-data.layanan-medis.tindakan-medis.get');
            Route::post('/tindakan-medis', [TindakanMedisController::class, 'store'])->name('master-data.layanan-medis.tindakan-medis.store');
            Route::get('/tindakan-medis/tarif/{id}', [TindakanMedisController::class, 'getTarif'])->name('master-data.layanan-medis.tindakan-medis.getTarif');
            Route::get('/tindakan-medis/tarif/{tindakanId}/{groupId}', [TindakanMedisController::class, 'getTarifByGroup'])->name('master-data.layanan-medis.tindakan-medis.getTarifByGroup');
            Route::patch('/tindakan-medis/update/{id}/tarif', [TindakanMedisController::class, 'updateTarif'])->name('master-data.layanan-medis.tindakan-medis.updateTarif');
            Route::patch('/tindakan-medis/{id}/update', [TindakanMedisController::class, 'update'])->name('master-data.layanan-medis.tindakan-medis.update');
            Route::delete('/tindakan-medis/{id}/delete', [TindakanMedisController::class, 'delete'])->name('master-data.layanan-medis.tindakan-medis.delete');

            Route::post('/grup-tindakan-medis', [GrupTindakanMedisController::class, 'store'])->name('master-data.grup-tindakan-medis.store');
            Route::get('/grup-tindakan-medis/{id}', [GrupTindakanMedisController::class, 'getGrupTindakan'])->name('master-data.layanan-medis.grup-tindakan-medis.get');
            Route::patch('/grup-tindakan-medis/{id}/update', [GrupTindakanMedisController::class, 'update'])->name('master-data.layanan-medis.grup-tindakan-medis.update');
            Route::delete('/grup-tindakan-medis/{id}/delete', [GrupTindakanMedisController::class, 'delete'])->name('master-data.layanan-medis.grup-tindakan-medis.delete');

            Route::post('/grup-rehab-medik', [GrupTindakanMedisController::class, 'store'])->name('master-data.grup-rehab-medik.store');
            Route::patch('/grup-rehab-medik/{id}/update', [GrupTindakanMedisController::class, 'update'])->name('master-data.layanan-medis.grup-rehab-medik.update');
            Route::delete('/grup-rehab-medik/{id}/delete', [GrupTindakanMedisController::class, 'delete'])->name('master-data.layanan-medis.grup-rehab-medik.delete');
        });

        Route::prefix('jadwal-dokter')->group(function () {
            Route::post('/tambah-jadwal-dokter', [JadwalDokterController::class, 'store']);
            Route::get('/{jadwal}', [JadwalDokterController::class, 'show']);
            Route::put('/{jadwal}', [JadwalDokterController::class, 'update']);
        });

        Route::prefix('setup')->group(function () {
            Route::get('/kelas-rawat', [KelasRawatController::class, 'getKelasRawat']);
            Route::get('/kelas-rawat/{id}', [KelasRawatController::class, 'getKelas'])->name('master-data.setup.kelas-rawat.get');
            Route::post('/kelas-rawat', [KelasRawatController::class, 'store'])->name('master-data.setup.kelas-rawat.store');
            Route::patch('/kelas-rawat/{id}/update', [KelasRawatController::class, 'update'])->name('master-data.setup.kelas-rawat.update');
            Route::delete('/kelas-rawat/{id}/delete', [KelasRawatController::class, 'delete'])->name('master-data.setup.kelas-rawat.delete');

            Route::get('/tarif/{id}', [TarifKelasRawatController::class, 'getTarif'])->name('master-data.setup.tarif.get');
            // Route::post('/tarif', [TarifKelasRawatController::class, 'store'])->name('master-data.setup.tarif.store');
            Route::patch('/tarif', [TarifKelasRawatController::class, 'update'])->name('master-data.setup.tarif.update');
            // Route::delete('/tarif/{id}/delete', [TarifKelasRawatController::class, 'delete'])->name('master-data.setup.tarif.delete');

            Route::get('/room/{id}', [RoomController::class, 'getRoom'])->name('master-data.setup.room.get');
            Route::post('/room', [RoomController::class, 'store'])->name('master-data.setup.room.store');
            Route::patch('/room/{id}/update', [RoomController::class, 'update'])->name('master-data.setup.room.update');
            Route::delete('/room/{id}/delete', [RoomController::class, 'delete'])->name('master-data.setup.room.delete');

            Route::get('/bed/{id}', [BedController::class, 'getBed'])->name('master-data.setup.bed.get');
            Route::post('/bed', [BedController::class, 'store'])->name('master-data.setup.bed.store');
            Route::patch('/bed/{id}/update', [BedController::class, 'update'])->name('master-data.setup.bed.update');
            Route::delete('/bed/{id}/delete', [BedController::class, 'delete'])->name('master-data.setup.bed.delete');

            Route::prefix('departemen')->group(function () {
                Route::get('/', [DepartementController::class, 'getDepartements']);
                Route::post('/', [DepartementController::class, 'store'])->name('master-data.setup.departemen.store');
                Route::patch('/{id}/update', [DepartementController::class, 'update'])->name('master-data.setup.departemen.update');
            });

            Route::prefix('tarif-registrasi-layanan')->group(function () {
                Route::get('/{id}', [TarifRegistrasiController::class, 'getTarif'])->name('master-data.setup.tarif-registrasi.get');
                Route::post('/', [TarifRegistrasiController::class, 'store'])->name('master-data.setup.tarif-registrasi.store');
                Route::patch('/{id}/update', [TarifRegistrasiController::class, 'update'])->name('master-data.setup.tarif-registrasi.update');
                Route::delete('/{id}/delete', [TarifRegistrasiController::class, 'delete'])->name('master-data.setup.tarif-registrasi.delete');

                Route::get('/{tarifRegistId}/tarif/{grupPenjaminId}', [TarifRegistrasiController::class, 'getTarif'])->name('master-data.setup.tarif-registrasi.tarif.get');
                Route::post('/{tarifRegistId}/tarif/{grupPenjaminId}', [TarifRegistrasiController::class, 'storeTarif'])->name('master-data.setup.tarif-registrasi.tarif.store');
                Route::post('/{tarifRegistId}/departments', [TarifRegistrasiController::class, 'storeDepartments'])
                    ->name('master-data.setup.tarif-registrasi.departements.store');
            });

            Route::prefix('biaya-administrasi-ranap')->group(function () {
                Route::patch('/update', [BiayaAdministrasiRawatInapController::class, 'update']);
            });

            Route::prefix('biaya-materai')->group(function () {
                Route::post('/', [BiayaMateraiController::class, 'store']);
                Route::get('/{biayaMateraiId}/get', [BiayaMateraiController::class, 'getBiayaMaterai']);
                Route::patch('/{biayaMateraiId}/update', [BiayaMateraiController::class, 'update']);
                Route::delete('/{biayaMateraiId}/delete', [BiayaMateraiController::class, 'destroy']);
            });

            Route::prefix('form-builder')->group(function () {
                // Beri nama pada rute store agar bisa dipanggil dengan route()
                Route::post('store', [FormBuilderController::class, 'store'])->name('api.form-builder.store');

                // Sebaiknya beri nama juga pada rute lainnya untuk konsistensi
                Route::post('{id}/update', [FormBuilderController::class, 'update'])->name('api.form-builder.update');
                Route::delete('{id}/delete', [FormBuilderController::class, 'destroy'])->name('api.form-builder.destroy');
            });

            Route::prefix('ethnics')->group(function () {
                Route::post('create', [EthnicController::class, 'create'])->name('master-data.ethnics');
            });

            Route::prefix('ethnics')->group(function () {
                Route::post('create', [EthnicController::class, 'create'])->name('master-data.ethnics');
            });
        });

        Route::prefix('penunjang-medis')->group(function () {
            Route::get('/radiologi/grup-parameter-radiologi/{id}', [GrupParameterRadiologiController::class, 'getGrupParameter'])->name('master-data.penunjang-medis.radiologi.grup-parameter.get');
            Route::post('/radiologi/grup-parameter-radiologi', [GrupParameterRadiologiController::class, 'store'])->name('master-data.penunjang-medis.radiologi.grup-parameter.store');
            Route::patch('/radiologi/grup-parameter-radiologi/{id}/update', [GrupParameterRadiologiController::class, 'update'])->name('master-data.penunjang-medis.radiologi.grup-parameter.update');
            Route::delete('/radiologi/grup-parameter-radiologi/{id}/delete', [GrupParameterRadiologiController::class, 'delete'])->name('master-data.penunjang-medis.radiologi.grup-parameter.delete');

            Route::get('/radiologi/kategori/{id}', [KategoriRadiologiController::class, 'getGrupParameter'])->name('master-data.penunjang-medis.radiologi.kategori.get');
            Route::post('/radiologi/kategori', [KategoriRadiologiController::class, 'store'])->name('master-data.penunjang-medis.radiologi.kategori.store');
            Route::patch('/radiologi/kategori/{id}/update', [KategoriRadiologiController::class, 'update'])->name('master-data.penunjang-medis.radiologi.kategori.update');
            Route::delete('/radiologi/kategori/{id}/delete', [KategoriRadiologiController::class, 'delete'])->name('master-data.penunjang-medis.radiologi.kategori.delete');

            Route::get('/radiologi/parameter/{id}', [ParameterRadiologiController::class, 'getParameter'])->name('master-data.penunjang-medis.radiologi.parameter.get');
            Route::post('/radiologi/parameter', [ParameterRadiologiController::class, 'store'])->name('master-data.penunjang-medis.radiologi.parameter.store');
            Route::patch('/radiologi/parameter/{id}/update', [ParameterRadiologiController::class, 'update'])->name('master-data.penunjang-medis.radiologi.parameter.update');
            Route::delete('/radiologi/parameter/{id}/delete', [ParameterRadiologiController::class, 'delete'])->name('master-data.penunjang-medis.radiologi.parameter.delete');

            Route::prefix('radiologi')->group(function () {
                Route::get('/grup-parameter-radiologi/{id}', [GrupParameterRadiologiController::class, 'getGrupParameter'])->name('master-data.penunjang-medis.radiologi.grup-parameter.get');
                Route::post('/grup-parameter-radiologi', [GrupParameterRadiologiController::class, 'store'])->name('master-data.penunjang-medis.radiologi.grup-parameter.store');
                Route::patch('/grup-parameter-radiologi/{id}/update', [GrupParameterRadiologiController::class, 'update'])->name('master-data.penunjang-medis.radiologi.grup-parameter.update');
                Route::delete('/grup-parameter-radiologi/{id}/delete', [GrupParameterRadiologiController::class, 'delete'])->name('master-data.penunjang-medis.radiologi.grup-parameter.delete');

                Route::get('/kategori/{id}', [KategoriRadiologiController::class, 'getGrupParameter'])->name('master-data.penunjang-medis.radiologi.kategori.get');
                Route::post('/kategori', [KategoriRadiologiController::class, 'store'])->name('master-data.penunjang-medis.radiologi.kategori.store');
                Route::patch('/kategori/{id}/update', [KategoriRadiologiController::class, 'update'])->name('master-data.penunjang-medis.radiologi.kategori.update');
                Route::delete('/kategori/{id}/delete', [KategoriRadiologiController::class, 'delete'])->name('master-data.penunjang-medis.radiologi.kategori.delete');

                Route::get('/parameter/{id}', [ParameterRadiologiController::class, 'getParameter'])->name('master-data.penunjang-medis.radiologi.parameter.get');
                Route::post('/parameter', [ParameterRadiologiController::class, 'store'])->name('master-data.penunjang-medis.radiologi.parameter.store');
                Route::patch('/parameter/{id}/update', [ParameterRadiologiController::class, 'update'])->name('master-data.penunjang-medis.radiologi.parameter.update');
                Route::delete('/parameter/{id}/delete', [ParameterRadiologiController::class, 'delete'])->name('master-data.penunjang-medis.radiologi.parameter.delete');

                Route::get('/parameter/{parameterId}/tarif/{grupPenjaminId}', [TarifParameterRadiologiController::class, 'getTarifParameter'])->name('master-data.penunjang-medis.radiologi.parameter.tarif.get');
                Route::post('/parameter/{parameterId}/tarif/{grupPenjaminId}', [TarifParameterRadiologiController::class, 'store'])->name('master-data.penunjang-medis.radiologi.parameter.tarif.store');
            });

            Route::prefix('laboratorium')->group(function () {
                Route::get('/grup-parameter/{id}', [GrupParameterLaboratoriumController::class, 'getGrupParameter'])->name('master-data.penunjang-medis.laboratorium.grup-parameter.get');
                Route::post('/grup-parameter', [GrupParameterLaboratoriumController::class, 'store'])->name('master-data.penunjang-medis.laboratorium.grup-parameter.store');
                Route::patch('/grup-parameter/{id}/update', [GrupParameterLaboratoriumController::class, 'update'])->name('master-data.penunjang-medis.laboratorium.grup-parameter.update');
                Route::delete('/grup-parameter/{id}/delete', [GrupParameterLaboratoriumController::class, 'delete'])->name('master-data.penunjang-medis.laboratorium.grup-parameter.delete');

                Route::get('/parameter/{id}', [ParameterLaboratoriumController::class, 'getParameter'])->name('master-data.penunjang-medis.laboratorium.parameter.get');
                Route::post('/parameter', [ParameterLaboratoriumController::class, 'store'])->name('master-data.penunjang-medis.laboratorium.parameter.store');
                Route::patch('/parameter/{id}/update', [ParameterLaboratoriumController::class, 'update'])->name('master-data.penunjang-medis.laboratorium.parameter.update');
                Route::delete('/parameter/{id}/delete', [ParameterLaboratoriumController::class, 'delete'])->name('master-data.penunjang-medis.laboratorium.parameter.delete');
                Route::get('/parameter/{parameterId}/tarif/{grupPenjaminId}', [TarifParameterLaboratoriumController::class, 'getTarifParameter'])->name('master-data.penunjang-medis.laboratorium.parameter.tarif.get');
                Route::post('/parameter/{parameterId}/tarif/{grupPenjaminId}', [TarifParameterLaboratoriumController::class, 'store'])->name('master-data.penunjang-medis.laboratorium.parameter.tarif.store');

                Route::get('/kategori/{id}', [KategoriLaboratorumController::class, 'getKategori'])->name('master-data.penunjang-medis.laboratorium.kategori.get');
                Route::post('/kategori', [KategoriLaboratorumController::class, 'store'])->name('master-data.penunjang-medis.laboratorium.kategori.store');
                Route::patch('/kategori/{id}/update', [KategoriLaboratorumController::class, 'update'])->name('master-data.penunjang-medis.laboratorium.kategori.update');
                Route::delete('/kategori/{id}/delete', [KategoriLaboratorumController::class, 'delete'])->name('master-data.penunjang-medis.laboratorium.kategori.delete');

                Route::get('/tipe/{id}', [TipeLaboratoriumController::class, 'getTipe'])->name('master-data.penunjang-medis.laboratorium.tipe.get');
                Route::post('/tipe', [TipeLaboratoriumController::class, 'store'])->name('master-data.penunjang-medis.laboratorium.tipe.store');
                Route::patch('/tipe/{id}/update', [TipeLaboratoriumController::class, 'update'])->name('master-data.penunjang-medis.laboratorium.tipe.update');
                Route::delete('/tipe/{id}/delete', [TipeLaboratoriumController::class, 'delete'])->name('master-data.penunjang-medis.laboratorium.tipe.delete');

                Route::get('/nilai-normal/{parameterId}/get', [NilaiNormalLaboratoriumController::class, 'getNilaiParameter'])->name('master-data.penunjang-medis.laboratorium.nilai-normal');

                Route::post('/nilai-normal-parameter', [NilaiNormalLaboratoriumController::class, 'store'])->name('master-data.penunjang-medis.laboratorium.nilai-normal-parameter.store');
                Route::get('/nilai-normal-parameter/{id}', [NilaiNormalLaboratoriumController::class, 'getNilaiNormal'])->name('master-data.penunjang-medis.laboratorium.nilai-normal-parameter.get');
                Route::patch('/nilai-normal-parameter/{id}', [NilaiNormalLaboratoriumController::class, 'update'])->name('master-data.penunjang-medis.laboratorium.nilai-normal-parameter.update');
                Route::delete('/nilai-normal-parameter/{id}', [NilaiNormalLaboratoriumController::class, 'delete'])->name('master-data.penunjang-medis.laboratorium.nilai-normal-parameter.delete');
            });

            Route::prefix('farmasi')->group(function () {
                Route::prefix('signa')->group(function () {
                    Route::post('/create', [FarmasiSignaController::class, 'store'])->name('master-data.penunjang-medis.farmasi.signa.store');
                    Route::delete('/delete/{id}', [FarmasiSignaController::class, 'destroy'])->name('master-data.penunjang-medis.farmasi.signa.delete');
                });
            });
        });

        Route::prefix('peralatan')->group(function () {
            Route::post('/', [PeralatanController::class, 'store'])->name('master-data.peralatan.store');
            Route::get('/{id}', [PeralatanController::class, 'getPeralatan'])->name('master-data.peralatan.get');
            Route::patch('/{id}/update', [PeralatanController::class, 'update'])->name('master-data.peralatan.update');
            Route::delete('/{id}/delete', [PeralatanController::class, 'delete'])->name('master-data.peralatan.delete');

            Route::get('/{peralatanId}/tarif/{grupPenjaminId}', [PeralatanController::class, 'getTarifPeralatan'])->name('master-data.peralatan.tarif.get');
            Route::post('/{peralatanId}/tarif/{grupPenjaminId}', [PeralatanController::class, 'storeTarif'])->name('master-data.peralatan.tarif.store');
        });

        Route::prefix('persalinan')->group(function () {
            Route::prefix('kategori')->group(function () {
                Route::post('/', [KategoriPersalinanController::class, 'store'])->name('master-data.persalinan.kategori.store');
                Route::get('/{id}', [KategoriPersalinanController::class, 'getKategori'])->name('master-data.persalinan.kategori.get');
                Route::patch('/{id}/update', [KategoriPersalinanController::class, 'update'])->name('master-data.persalinan.kategori.update');
                Route::delete('/{id}/delete', [KategoriPersalinanController::class, 'delete'])->name('master-data.persalinan.kategori.delete');
            });

            Route::prefix('tipe')->group(function () {
                Route::post('/', [TipePersalinanController::class, 'store'])->name('master-data.persalinan.tipe.store');
                Route::patch('/{id}/update', [TipePersalinanController::class, 'update'])->name('master-data.persalinan.tipe.update');
            });

            Route::prefix('daftar-persalinan')->group(function () {
                Route::post('/', [DaftarPersalinanController::class, 'store'])->name('master-data.persalinan.daftar-persalinan.store');
                Route::get('/{id}', [DaftarPersalinanController::class, 'getPersalinan'])->name('master-data.persalinan.daftar-persalinan.get');
                Route::patch('/{id}/update', [DaftarPersalinanController::class, 'update'])->name('master-data.persalinan.daftar-persalinan.update');
                Route::delete('/{id}/delete', [DaftarPersalinanController::class, 'delete'])->name('master-data.persalinan.daftar-persalinan.delete');
            });

            Route::prefix('tarif')->group(function () {
                Route::get('/persalinan/{persalinanId}/tarif/{grupPenjaminId}', [TarifPersalinanController::class, 'getTarifPersalinan'])->name('master-data.persalinan.tarif.get');
                Route::post('/persalinan/{persalinanId}/tarif/{grupPenjaminId}', [TarifPersalinanController::class, 'store'])->name('master-data.persalinan.tarif.store');
            });
        });

        Route::prefix('operasi')->group(function () {
            Route::prefix('kategori')->group(function () {
                Route::post('/', [KategoriOperasiController::class, 'store'])->name('master-data.operasi.kategori.store');
                Route::get('/{id}', [KategoriOperasiController::class, 'getKategori'])->name('master-data.operasi.kategori.get');
                Route::patch('/{id}/update', [KategoriOperasiController::class, 'update'])->name('master-data.operasi.kategori.update');
                Route::delete('/{id}/delete', [KategoriOperasiController::class, 'delete'])->name('master-data.operasi.kategori.delete');
            });

            Route::prefix('tipe')->group(function () {
                Route::post('/', [TipeOperasiController::class, 'store'])->name('master-data.operasi.tipe.store');
                Route::get('/{id}', [TipeOperasiController::class, 'getTipe'])->name('master-data.operasi.tipe.get');
                Route::patch('/{id}/update', [TipeOperasiController::class, 'update'])->name('master-data.operasi.tipe.update');
                Route::delete('/{id}/delete', [TipeOperasiController::class, 'delete'])->name('master-data.operasi.tipe.delete');
                Route::post('/{tipeId}/update-per-column', [TipeOperasiController::class, 'updatePerColumn'])->name('master-data.operasi.tipe.update.column');
            });

            Route::prefix('jenis')->group(function () {
                Route::post('/', [JenisOperasiController::class, 'store'])->name('master-data.operasi.jenis.store');
                Route::get('/{id}', [JenisOperasiController::class, 'getJenis'])->name('master-data.operasi.jenis.get');
                Route::patch('/{id}/update', [JenisOperasiController::class, 'update'])->name('master-data.operasi.jenis.update');
                Route::delete('/{id}/delete', [JenisOperasiController::class, 'delete'])->name('master-data.operasi.jenis.delete');
            });

            Route::prefix('tindakan')->group(function () {
                Route::post('/', [TindakanOperasiController::class, 'store'])->name('master-data.operasi.tindakan.store');
                Route::get('/{id}', [TindakanOperasiController::class, 'getTindakan'])->name('master-data.operasi.tindakan.get');
                Route::patch('/{id}/update', [TindakanOperasiController::class, 'update'])->name('master-data.operasi.tindakan.update');
                Route::delete('/{id}/delete', [TindakanOperasiController::class, 'delete'])->name('master-data.operasi.tindakan.delete');
            });

            Route::prefix('tarif')->group(function () {
                Route::get('/operasi/{operasiId}/tarif/{grupPenjaminId}', [TarifOperasiController::class, 'getTarifOperasi'])->name('master-data.operasi.tarif.get');
                Route::post('/operasi/{operasiId}/tarif/{grupPenjaminId}', [TarifOperasiController::class, 'store'])->name('master-data.tindakan_operasi.tarif.store');
            });
        });

        Route::prefix('grup-suplier')->group(function () {
            Route::post('/', [GrupSuplierController::class, 'store'])->name('master-data.grup-suplier.store');
            Route::get('/{id}', [GrupSuplierController::class, 'getGrup'])->name('master-data.grup-suplier.get');
            Route::patch('/{id}/update', [GrupSuplierController::class, 'update'])->name('master-data.grup-suplier.update');
            Route::delete('/{id}/delete', [GrupSuplierController::class, 'delete'])->name('master-data.grup-suplier.delete');
        });

        Route::prefix('harga-jual')->group(function () {
            Route::post('/', [MarginHargaJualController::class, 'storeTarif'])->name('master-data.harga-jual.margin.store');
            Route::get('/getTarif/{grupPenjaminId}', [MarginHargaJualController::class, 'getTarif'])->name('master-data.harga-jual.margin.getTarif');
        });
    });

    Route::prefix('kepustakaan')->group(function () {
        Route::post('/tambah', [KepustakaanController::class, 'store'])->name('kepustakaan.store');
        Route::get('/edit/{encryptedId}', [KepustakaanController::class, 'getKepustakaan'])->name('kepustakaan.get');
        Route::patch('/update/{encryptedId}', [KepustakaanController::class, 'update'])->name('kepustakaan.update');
        Route::delete('/delete/{encryptedId}', [KepustakaanController::class, 'delete'])->name('kepustakaan.delete');
    });

    Route::prefix('pemakaian-alat')->name('pemakaian-alat.')->group(function () {
        Route::get('/data/{registration}', [LayananController::class, 'getDataPemakaianAlat'])->name('getData');
    });

    Route::prefix('visite')->name('visite.')->group(function () {
        // URL untuk mengambil data Server-Side DataTables
        Route::get('/get-data/{registration}', [DoctorVisitController::class, 'getData'])->name('getData');
        // URL untuk menyimpan data visite baru
        Route::post('/store/{registration}', [DoctorVisitController::class, 'store'])->name('store');
        // URL untuk menghapus visite (menggunakan metode DELETE)
        Route::delete('/destroy/{registration}/{visit}', [DoctorVisitController::class, 'destroy'])->name('destroy');
        // Rute baru untuk mengambil konten modal
        Route::get('/create/{registration}', [DoctorVisitController::class, 'create'])->name('create');
    });

    Route::get('/getKabupaten', [LocationController::class, 'getKabupaten'])->name('getKabupaten');
    Route::get('/getKecamatan', [LocationController::class, 'getKecamatan'])->name('getKecamatan');
    Route::get('/getKelurahan', [LocationController::class, 'getKelurahan'])->name('getKelurahan');
    Route::get('/get-kecamatan-by-kelurahan', [LocationController::class, 'getKecamatanByKelurahan'])->name('getKecamatanByKelurahan');
});

Route::get('/tts', [TtsController::class, 'generateSpeech']);
Route::post('/antrian/panggil', [AntrianController::class, 'panggilPasien'])->name('api.antrian.panggil');
Route::get('/plasma-status/rawat-jalan/{plasmaDisplayRawatJalan}', [PlasmaDisplayRawatJalanController::class, 'getStatus']);
