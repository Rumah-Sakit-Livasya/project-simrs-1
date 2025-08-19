<?php

use App\Http\Controllers\SIMRS\KepustakaanController;
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
    Route::prefix('kepustakaan')->name('kepustakaan.')->group(function () {
        Route::get('/dashboard', function () {
            return view('app-type.kepustakaan.dashboard');
        })->name('');

        Route::get('/laporan-dashboard', [KepustakaanController::class, 'laporanDashboard'])
            ->name('laporan.dashboard');
    });
});
