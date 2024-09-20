<?php

use App\Http\Controllers\SIMRS\Penjamin\PenjaminController;
use App\Http\Controllers\SIMRS\RoomController;
use App\Http\Controllers\SIMRS\BedController;
use App\Http\Controllers\SIMRS\DepartementController;
use App\Http\Controllers\SIMRS\GrupParameterRadiologiController;
use App\Http\Controllers\SIMRS\GrupSuplier\GrupSuplierController;
use App\Http\Controllers\SIMRS\KategoriRadiologiController;
use App\Http\Controllers\SIMRS\ParameterRadiologiController;
use App\Http\Controllers\SIMRS\GrupTindakanMedisController;
use App\Http\Controllers\SIMRS\KelasRawatController;
use App\Http\Controllers\SIMRS\Laboratorium\GrupParameterLaboratoriumController;
use App\Http\Controllers\SIMRS\Laboratorium\KategoriLaboratorumController;
use App\Http\Controllers\SIMRS\Laboratorium\NilaiNormalLaboratoriumController;
use App\Http\Controllers\SIMRS\Laboratorium\NilaiParameterLaboratoriumController;
use App\Http\Controllers\SIMRS\Laboratorium\TipeLaboratoriumController;
use App\Http\Controllers\SIMRS\Laboratorium\ParameterLaboratoriumController;
use App\Http\Controllers\SIMRS\Operasi\JenisOperasiController;
use App\Http\Controllers\SIMRS\Operasi\KategoriOperasiController;
use App\Http\Controllers\SIMRS\Operasi\TindakanOperasiController;
use App\Http\Controllers\SIMRS\Operasi\TipeOperasiController;
use App\Http\Controllers\SIMRS\RegistrationController;
use App\Http\Controllers\SIMRS\PatientController;
use App\Http\Controllers\SIMRS\Penjamin\PenjaminController as PenjaminPenjaminController;
use App\Http\Controllers\SIMRS\Peralatan\PeralatanController;
use App\Http\Controllers\SIMRS\Persalinan\DaftarPersalinanController;
use App\Http\Controllers\SIMRS\Persalinan\KategoriPersalinanController;
use App\Http\Controllers\SIMRS\Persalinan\TipePersalinanController;
use App\Http\Controllers\SIMRS\Setup\TarifRegistrasiController;
use App\Http\Controllers\SIMRS\TindakanMedisController;
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

    Route::get('/patients/{patient:id}/{registrasi}', [RegistrationController::class, 'create'])->name('form.registrasi');
    Route::post('/patients/simpan/registrasi', [RegistrationController::class, 'store'])->name('simpan.registrasi');
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
            return view('simrs.dashboard');
        })->name('dashboard.simrs');

        Route::prefix('/master-data')->group(function () {
            Route::prefix('setup')->group(function () {
                Route::get('/kelas-rawat', [KelasRawatController::class, 'index'])->name('master-data.setup.kelas-rawat');
                Route::get('/rooms/{kelas:id}', [RoomController::class, 'index'])->name('master-data.setup.rooms');
                Route::get('/beds/{room:id}', [BedController::class, 'index'])->name('master-data.setup.beds');

                Route::get('departemen', [DepartementController::class, 'index'])->name('master-data.setup.departemen.index');
                Route::get('departemen/tambah', [DepartementController::class, 'tambah'])->name('master-data.setup.departemen.tambah');

                Route::get('/tarif-registrasi-layanan', [TarifRegistrasiController::class, 'index'])->name('master-data.setup.tarif-registrasi.index');
                Route::get('/tarif-registrasi-layanan/{id}/set-tarif', [TarifRegistrasiController::class, 'setTarif'])->name('master-data.setup.tarif-registrasi.set-tarif');
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
