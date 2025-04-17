<?php

use App\Http\Controllers\OrderLaboratoriumController;
use App\Models\OrderParameterLaboratorium;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SIMRS\BedController;
use App\Http\Controllers\SIMRS\CPPT\CPPTController;
use App\Http\Controllers\SIMRS\DepartementController;
use App\Http\Controllers\SIMRS\EthnicController;
use App\Http\Controllers\SIMRS\GrupParameterRadiologiController;
use App\Http\Controllers\SIMRS\GrupSuplier\GrupSuplierController;
use App\Http\Controllers\SIMRS\GrupTindakanMedisController;
use App\Http\Controllers\SIMRS\HargaJual\MarginHargaJualController;
use App\Http\Controllers\SIMRS\JadwalDokter\JadwalDokterController;
use App\Http\Controllers\SIMRS\KategoriRadiologiController;
use App\Http\Controllers\SIMRS\Laboratorium\GrupParameterLaboratoriumController;
use App\Http\Controllers\SIMRS\Laboratorium\KategoriLaboratorumController;
use App\Http\Controllers\SIMRS\Laboratorium\TipeLaboratoriumController;
use App\Http\Controllers\SIMRS\Laboratorium\ParameterLaboratoriumController;
use App\Http\Controllers\SIMRS\KelasRawatController;
use App\Http\Controllers\SIMRS\KepustakaanController;
use App\Http\Controllers\SIMRS\Laboratorium\NilaiNormalLaboratoriumController;
use App\Http\Controllers\SIMRS\Laboratorium\TarifParameterLaboratoriumController;
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
use App\Http\Controllers\SIMRS\Penjamin\GroupPenjaminController;
use App\Http\Controllers\SIMRS\Penjamin\PenjaminController;
use App\Http\Controllers\SIMRS\Peralatan\PeralatanController;
use App\Http\Controllers\SIMRS\Persalinan\DaftarPersalinanController;
use App\Http\Controllers\SIMRS\Persalinan\KategoriPersalinanController;
use App\Http\Controllers\SIMRS\Persalinan\TipePersalinanController;
use App\Http\Controllers\SIMRS\Poliklinik\LayananController;
use App\Http\Controllers\SIMRS\Poliklinik\PoliklinikController;
use App\Http\Controllers\SIMRS\Radiologi\RadiologiController;
use App\Http\Controllers\SIMRS\Radiologi\TarifParameterRadiologiController;
use App\Http\Controllers\SIMRS\RegistrationController;
use App\Http\Controllers\SIMRS\ResumeMedisRajal\ResumeMedisRajalController;
use App\Http\Controllers\SIMRS\RoomController;
use App\Http\Controllers\SIMRS\Setup\BiayaAdministrasiRawatInapController;
use App\Http\Controllers\SIMRS\Setup\BiayaMateraiController;
use App\Http\Controllers\SIMRS\Setup\TarifRegistrasiController;
use App\Http\Controllers\SIMRS\TarifKelasRawatController;
use App\Http\Controllers\SIMRS\TindakanMedisController;
use App\Models\SIMRS\Laboratorium\OrderLaboratorium;
use App\Models\SIMRS\OrderTindakanMedis;
use Illuminate\Support\Facades\Storage;

Route::middleware(['web', 'auth'])->prefix('simrs')->group(function () {
    Route::get('/signature/{filename}', function ($filename) {
        // Pastikan user terautentikasi memiliki akses untuk file ini
        $path = 'employee/ttd/' . $filename;

        if (Storage::disk('private')->exists($path)) {
            return Storage::disk('private')->response($path);
        }

        abort(404, 'File not found');
    })->name('signature.show');

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


    Route::prefix('pengkajian')->group(function () {
        Route::prefix('rawat-jalan')->group(function () {
            Route::prefix('perawat')->group(function () {
                Route::post('/store', [PengkajianController::class, 'storeOrUpdatePengkajianRajal'])->name('pengkajian.nurse-rajal.store');
            });
            Route::prefix('dokter')->group(function () {
                Route::post('/store', [PengkajianDokterRajalController::class, 'store'])->name('pengkajian.dokter-rajal.store');
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
            });
        });
    });


    Route::prefix('poliklinik')->group(function () {
        Route::post('/filter-pasien', [PoliklinikController::class, 'filterPasien'])->name('poliklinik.filter-pasien');
    });

    Route::prefix('erm')->group(function () {
        Route::get('/dokter-pengkajian/{type}/{registration_number}/get', [PengkajianDokterRajalController::class, 'getPengkajian'])->name('pengkajian.dokter-rajal.get');
        Route::get('/perawat-pengkajian/{type}/{registration_number}/get', [PengkajianController::class, 'getPengkajianRajal'])->name('pengkajian.perawat-rajal.get');
        Route::get('/dokter-cppt/{type}/{registration_number}/get', [CPPTController::class, 'getCPPT'])->name('cppt.dokter-rajal.get');
        Route::get('/dokter-cppt/get', [CPPTController::class, 'getCPPT'])->name('cppt.get');
        Route::post('/dokter-cppt/{type}/{registration_number}/store', [CPPTController::class, 'store'])->name('cppt.dokter-rajal.store');
        Route::get('/dokter-cppt/{type}/{registration_number}/get', [CPPTController::class, 'getCPPT'])->name('cppt.dokter-rajal.get');
        Route::post('/dokter-resume-medis/store', [ResumeMedisRajalController::class, 'store'])->name('resume-medis.dokter-rajal.store');
        Route::get('/dokter-resume-medis-rajal/{type}/{registration_number}/get', [ResumeMedisRajalController::class, 'getResumeMedis'])->name('resume-medis.dokter-rajal.get');
        // Route::post('/transfer/store', [CPPTController::class, 'getCPPT'])->name('pengkajian.transfer-pasien-antar-ruangan.store');
    });

    Route::prefix('master-data')->group(function () {
        Route::prefix('penjamin')->group(function () {
            Route::post('/', [PenjaminController::class, 'store'])->name('master-data.penjamin.store');
        });
        Route::get('/group-penjamin', [GroupPenjaminController::class, 'index']);
        Route::prefix('layanan-medis')->group(function () {
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
        });

        Route::prefix('setup')->group(function () {
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
                Route::post('store', [FormBuilderController::class, 'store']);
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

    Route::get('/getKabupaten', [LocationController::class, 'getKabupaten'])->name('getKabupaten');
    Route::get('/getKecamatan', [LocationController::class, 'getKecamatan'])->name('getKecamatan');
    Route::get('/getKelurahan', [LocationController::class, 'getKelurahan'])->name('getKelurahan');
    Route::get('/get-kecamatan-by-kelurahan', [LocationController::class, 'getKecamatanByKelurahan'])->name('getKecamatanByKelurahan');
});
