<?php

use App\Http\Controllers\Inventaris\BarangController;
use App\Http\Controllers\Inventaris\CategoryBarangController;
use App\Http\Controllers\Inventaris\MaintenanceBarangController;
use App\Http\Controllers\Inventaris\RoomMaintenanceController;
use App\Http\Controllers\Inventaris\TemplateBarangController;
use App\Http\Controllers\Inventaris\ReportBarangController;
use App\Models\Inventaris\Barang;
use App\Models\Inventaris\CategoryBarang;
use App\Models\Inventaris\ReportBarang;
use App\Models\Inventaris\RoomMaintenance;
use App\Models\Inventaris\TemplateBarang;
use App\Models\User;
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
    Route::prefix('inventaris')->group(function () {
        Route::get('/dashboard', function () {
            return view('app-type.logistik.dashboard', [
                'rooms' => RoomMaintenance::select('id')->count(),
                'categories' => CategoryBarang::select('id')->count(),
                'template' => TemplateBarang::select('id')->count(),
                'barang' => Barang::select('id')->count(),
                'users' => User::select('id')->count(),
                'reports' => ReportBarang::latest()->orderBy('created_at', 'desc')->take(100)->get(),
            ]);
        })->name('logistik');

        Route::get('/room-maintenance', [RoomMaintenanceController::class, 'index'])
            ->middleware('can:view inventaris ruangan')
            ->name('inventaris.rooms.index');
        Route::get('/room-maintenance/{id}', [RoomMaintenanceController::class, 'show'])
            // ->middleware('can:view room maintenance details')
            ->name('inventaris.rooms.show');
        Route::post('/room-maintenance/print', [RoomMaintenanceController::class, 'printLabel']);

        Route::get('/category-barang', [CategoryBarangController::class, 'index'])
            ->middleware('can:view inventaris kategori')
            ->name('inventaris.category.index');
        Route::get('/category-barang/{id}', [CategoryBarangController::class, 'show'])
            // ->middleware('can:view category barang details')
            ->name('inventaris.category.show');

        Route::get('/template-barang', [TemplateBarangController::class, 'index'])
            ->middleware('can:view inventaris template')
            ->name('inventaris.template.index');
        Route::get('/template-barang/{id}', [TemplateBarangController::class, 'show'])
            // ->middleware('can:view template barang details')
            ->name('inventaris.template.show');

        Route::get('/barang', [BarangController::class, 'index'])
            ->middleware('can:view inventaris barang')
            ->name('inventaris.barang.index');
        Route::post('/barang', [BarangController::class, 'index'])
            // ->middleware('can:search barang')
            ->name('inventaris.barang.search');

        Route::get('/maintenances/{barang:id}', [MaintenanceBarangController::class, 'index'])
            // ->middleware('can:view maintenance barang')
            ->name("inventaris.maintenance.index");
        Route::get('/report-barang', [ReportBarangController::class, 'index'])
            ->middleware('can:view inventaris report')
            ->name('inventaris.report.index');

        Route::get('/report-bulanan', [ReportBarangController::class, 'laporanBulanan'])
            ->middleware('can:view report bulanan')
            ->name('inventaris.report.bulanan');
        Route::post('/report-bulanan', [ReportBarangController::class, 'laporanBulanan'])
            ->middleware('can:store report bulanan')
            ->name('inventaris.report.bulanan.store');
        Route::get('/report/maintenance', [ReportBarangController::class, 'getMaintenanceData'])
            ->middleware('can:view report maintenance')
            ->name('inventaris.report.maintenance');
    });
});
