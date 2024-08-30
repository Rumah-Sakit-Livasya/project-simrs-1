<?php

use App\Http\Controllers\Inventaris\BarangController;
use App\Http\Controllers\Inventaris\CategoryBarangController;
use App\Http\Controllers\Inventaris\MaintenanceBarangController;
use App\Http\Controllers\Inventaris\RoomMaintenanceController;
use App\Http\Controllers\Inventaris\TemplateBarangController;
use App\Http\Controllers\Inventaris\ReportBarangController;
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
        Route::get('/room-maintenance', [RoomMaintenanceController::class, 'index'])->name('inventaris.rooms.index');
        Route::get('/room-maintenance/{id}', [RoomMaintenanceController::class, 'show'])->name('inventaris.rooms.show');
        Route::get('/category-barang', [CategoryBarangController::class, 'index'])->name('inventaris.category.index');
        Route::get('/category-barang/{id}', [CategoryBarangController::class, 'show'])->name('inventaris.category.show');
        Route::get('/template-barang', [TemplateBarangController::class, 'index'])->name('inventaris.template.index');
        Route::get('/template-barang/{id}', [TemplateBarangController::class, 'show'])->name('inventaris.template.show');
        Route::get('/barang', [BarangController::class, 'index'])->name('inventaris.barang.index');
        Route::post('/barang', [BarangController::class, 'index'])->name('inventaris.barang.search');
        Route::get('/maintenances/{barang:id}', [MaintenanceBarangController::class, 'index'])->name("inventaris.maintenance.index");
        Route::get('/report-barang', [ReportBarangController::class, 'index'])->name('inventaris.report.index');
    });
});
