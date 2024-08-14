<?php

use App\Http\Controllers\API\AttendanceController;
use App\Http\Controllers\API\AttendanceRequestController;
use App\Http\Controllers\API\DayOffRequestController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Pages\CompanyController;
use App\Http\Controllers\API\CompanyController as ApiCompanyController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Pages\DashboardController;
use App\Http\Controllers\Pages\UpdateProfileController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\WhatsappController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;


Route::get('/', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('user.login');
Route::post('/', [AuthenticatedSessionController::class, 'store']);

Route::middleware('auth')->group(function () {

    Route::get('/home', [ApplicationController::class, 'chooseApp'])->name('home');
    Route::post('/set-app', [ApplicationController::class, 'setApp'])->name('set-app');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('dashboard')->group(function () {
        Route::get("/", [DashboardController::class, 'index'])->name("dashboard");
        Route::get("/locations", [DashboardController::class, 'getDataLocations'])->name("locations");
        Route::get("/profile", [DashboardController::class, 'getDataUser'])->name("profile");
        Route::get("/attendances/employee/{id}/payroll", [DashboardController::class, 'getEmployeeAttendancePayroll'])->name("show.employee.attendance.payroll");
    });


    /*
    |--------------------------------------------------------------------------
    |  ABSENSI
    |--------------------------------------------------------------------------
    */
    Route::prefix('attendances')->group(function () {
        Route::get("/", [DashboardController::class, 'getAttendances'])->name("attendances");
        Route::get("/filter", [DashboardController::class, 'getAttendancesFilter'])->name('attendances.filter');
        Route::get("/attendance-requests", [DashboardController::class, 'attendanceRequest'])->name("attendance-requests");
        Route::get("/attendance-requests/{id}", [DashboardController::class, 'getAttendanceRequest'])->name("attendance-requests.get");

        Route::get("/day-off-requests", [DashboardController::class, 'dayOffRequest'])->name("day-off-requests");
        Route::get("/day-off-requests/{id}", [DashboardController::class, 'getDayOffRequest'])->name("day-off-requests.get");

        Route::post('/request/attendance', [AttendanceRequestController::class, 'store']);
        Route::put('/approve/attendance/{id}', [AttendanceRequestController::class, 'approve']);
        Route::put('/reject/attendance/{id}', [AttendanceRequestController::class, 'reject']);

        Route::post('/request/day-off', [DayOffRequestController::class, 'store']);
        Route::put('/approve/day-off/{id}', [DayOffRequestController::class, 'approve']);
        Route::put('/reject/day-off/{id}', [DayOffRequestController::class, 'reject']);

        Route::prefix('outsource')->group(function () {
            Route::get("/", [DashboardController::class, 'getAttendancesOutsourcing'])->name("monitoring.attendances.outsource");
            Route::post("/", [AttendanceController::class, 'clock_in_outsource'])->name("clockin.outsource");
            Route::post("/store", [AttendanceController::class, 'storeAttendanceOutsourcing'])->name("attendances.outsource.store");
            Route::post("/clock_out", [AttendanceController::class, 'clock_out_outsource'])->name("clockout.outsource");
        });

        Route::prefix('reports')->group(function () {
            Route::get('/', [ReportController::class, 'attendanceReports'])->name('attendances.reports');
            Route::post('/', [ReportController::class, 'filterAttendanceReports'])->name('attendances.reports.filter');
            Route::post('/unit', [ReportController::class, 'filterAttendanceReportPerUnit'])->name('attendances.reports.filter.per-unit');
        });
    });
    /* END ABSENSI ----------------------------------------------------------------------------*/


    /*
    |--------------------------------------------------------------------------
    |  MONITORING
    |--------------------------------------------------------------------------
    */
    Route::prefix('monitoring')->group(function () {
        Route::get("/attendances", [DashboardController::class, 'getAllAttendances'])->name("monitoring.attendances");
        Route::get("/attendances/employee/{id}", [DashboardController::class, 'getEmployeeAttendance'])->name("monitoring.attendances.show");
        Route::prefix('outsource')->group(function () {
            Route::get("/attendances/all", [DashboardController::class, 'getAttendancesOutsourcingAll'])->name("monitoring.attendances.outsource.all");
        });
        Route::get("/all-requests", [DashboardController::class, 'getDataRequests'])->name("monitoring.all.requests");
    });
    /* END MONITORING ----------------------------------------------------------------------------*/


    /*
    |--------------------------------------------------------------------------
    |  PEGAWAI
    |--------------------------------------------------------------------------
    */
    Route::prefix('employees')->group(function () {
        Route::get("/", [DashboardController::class, 'getDataEmployees'])->name("employees");
        Route::post('/', [DashboardController::class, 'pegawaiNonAktifList'])->name('get.non-aktif-pegawai');
        Route::get("/management-shift", [DashboardController::class, 'getManagementShift'])->name("management-shift");
        Route::get("/management-shift/edit/{id}", [DashboardController::class, 'editManagementShift'])->name("edit-management-shift");
        Route::get('/edit-profil/{id}', [UpdateProfileController::class, 'show'])->name('get.image');
        Route::post('/update-profil/{id}', [UpdateProfileController::class, 'update'])->name('update.image');
    });
    /* END PEGAWAI ----------------------------------------------------------------------------*/

    /*
    |--------------------------------------------------------------------------
    |  PENILAIAN
    |--------------------------------------------------------------------------
    */
    Route::prefix('penilaian')->group(function () {
        Route::get('/daftar-form', [DashboardController::class, 'getGroupPenilaian'])->name('kpi.get.form-penilaian');
        Route::get('/form-penilaian/{id}/edit', [DashboardController::class, 'editGroupPenilaian'])->name('kpi.edit.form-penilaian');
        Route::get('/reports', [DashboardController::class, 'rekapPenilaianBulanan'])->name('kpi.rekap.penilaian.bulanan');
        Route::get('/form-penilaian/tambah', [DashboardController::class, 'tbhGroupPenilaian'])->name('kpi.tbh.form-penilaian');
        Route::get('/form-penilaian/tambah/{id}', [DashboardController::class, 'tbhPenilaian'])->name('kpi.tbh.penilaian');
        Route::get('/form-penilaian/{id_form}/{id_pegawai}/{periode}/{tahun}/show', [DashboardController::class, 'showPenilaianBulanan'])->name('kpi.show.penilaian.bulanan');
        Route::get('/aspek-penilaian', [DashboardController::class, 'getAspekPenilaian'])->name('kpi.get.aspek-penilaian');
        Route::get('/aspek-penilaian/tambah', [DashboardController::class, 'tbhAspekPenilaian'])->name('kpi.tbh.aspek-penilaian');
        Route::get('/indikator-penilaian', [DashboardController::class, 'getIndikatorPenilaian'])->name('kpi.get.indikator-penilaian');


        Route::get("/ttd", function () {
            return view('pages.kpi.penilaian.partials.ttd');
        })->name('ttd');
    });
    /* END PENILAIAN --------------------------------------------------------*/


    /*
    |--------------------------------------------------------------------------
    |  PAYROLL
    |--------------------------------------------------------------------------
    */
    Route::prefix('payroll')->group(function () {
        Route::get("/allowance", [PayrollController::class, 'allowancePayroll'])->name("allowance.payroll");
        Route::get("/deduction", [PayrollController::class, 'deductionPayroll'])->name("deduction.payroll");
        Route::get("/run-payroll", [PayrollController::class, 'runPayroll'])->name("run.payroll");
        Route::get("/payroll-history", [PayrollController::class, 'payrollHistory'])->name("payroll.history");
        Route::get("/payslip", [PayrollController::class, 'payrollCetak'])->name('payroll.payslip');
        Route::post("/print", [PayrollController::class, 'payrollPrint'])->name('payroll.payslip.print');
        Route::get("/show", [PayrollController::class, 'showPayroll'])->name('payroll.slip-gaji.show');
        Route::get("/show/print", [PayrollController::class, 'printShowPayroll'])->name('payroll.slip-gaji.show.print');
    });
    /* END PAYROLL --------------------------------------------------------*/


    /*
    |--------------------------------------------------------------------------
    |  WHATSAPP
    |--------------------------------------------------------------------------
    */
    Route::prefix('whatsapp')->group(function () {
        Route::get('/', function () {
            return view('pages.whatsapp.index');
        })->name('whatsapp');
        Route::get('/broadcast', function () {
            return view('pages.whatsapp.index');
        })->name('broadcast');
        Route::get('/group_kontak', function () {
            return view('pages.whatsapp.index');
        })->name('group_kontak');
        Route::post('/send', [WhatsappController::class, 'sendMessage'])->name('whatsapp.send');
    });
    /* END PAYROLL --------------------------------------------------------*/

    /*
    |--------------------------------------------------------------------------
    |  Target (OKR)
    |--------------------------------------------------------------------------
    */
    Route::get("/targets", [DashboardController::class, 'getDataTargets'])->name("targets");
    Route::get("/targets/report", [DashboardController::class, 'getDataTargetReport'])->name("targets.report");
    /* END MASTER DATA --------------------------------------------------------*/

    /*
    |--------------------------------------------------------------------------
    |  MASTER DATA
    |--------------------------------------------------------------------------
    */
    Route::prefix('master-data')->group(function () {
        /*
        |--------------------------------------------------------------------------
        | PERUSAHAAN
        |--------------------------------------------------------------------------
        */
        Route::prefix('companies')->group(function () {
            Route::get("/", [CompanyController::class, 'index'])->name("companies");
            Route::get('/get/{id}', [ApiCompanyController::class, 'getCompany']);
            Route::post('/store', [ApiCompanyController::class, 'store']);
            Route::put('/update/{id}', [ApiCompanyController::class, 'update']);
            Route::put('/update-location/{id}', [ApiCompanyController::class, 'updateLocation']);
        });
        Route::get("/organizations", [DashboardController::class, 'getDataOrganizations'])->name("organization");
        Route::get("/structures", [DashboardController::class, 'getDataStructures'])->name("structures");
        Route::get("/job-level", [DashboardController::class, 'getDataJobLevels'])->name("job-level");
        Route::get("/job-position", [DashboardController::class, 'getDataJobPositions'])->name("job-position");
        /*END PERUSAHAAN ---------------------------------------------------------*/

        /*
        |--------------------------------------------------------------------------
        | MANAJEMEN WAKTU
        |--------------------------------------------------------------------------
        */
        Route::get("/day-off", [DashboardController::class, 'getDataHolidays'])->name("day-off");
        Route::get("/attendance-codes", [DashboardController::class, 'getDataAttendanceCodes'])->name("attendance-codes");
        Route::get("/shift-codes", [DashboardController::class, 'getDataShifts'])->name("shifts");
        /*END MANAJEMEN WAKTU ------------------------------------------------------*/

        /*
        |--------------------------------------------------------------------------
        | MASTER BANK
        |--------------------------------------------------------------------------
        */
        Route::get("/banks", [DashboardController::class, 'getDataBanks'])->name("banks");
        Route::get("/bank-employees", [DashboardController::class, 'getDataBankEmployees'])->name("bank-employees");
        /*END MASTER BANK---------------------------------------------------------*/

        /*
        |--------------------------------------------------------------------------
        | MASTER TARIF
        |--------------------------------------------------------------------------
        */

        /*--------------------------------------------------------------------------*/


        /*
        |--------------------------------------------------------------------------
        | MENU SIDEBAR
        |--------------------------------------------------------------------------
        */
        Route::prefix('daftar-menu')->group(function () {
            Route::get('/', [DashboardController::class, 'getDataMenus'])->name('master-data.menu');
        });
        /*--------------------------------------------------------------------------*/

        /*
        |--------------------------------------------------------------------------
        | USERS AKSES
        |--------------------------------------------------------------------------
        */
        Route::prefix('roles-permissions')->group(function () {
            Route::get('/permissions', [DashboardController::class, 'getDataPermissions'])->name('permissions');
            Route::get('/roles', [DashboardController::class, 'getDataRoles'])->name('roles');
        });

        Route::prefix('users')->group(function () {
            Route::get('/', [DashboardController::class, 'getDataUsers'])->name('users');
            // Route::get('/akses', [DashboardController::class, 'getDataUserAkses'])->name('users.akses');
            Route::get('/assign-permissions/{id}', [DashboardController::class, 'getDataAssignPermissions'])->name('users.assignPermissions');
        });
        /*END USERS AKSES --------------------------------------------------------*/
    });
    /* END MASTER DATA --------------------------------------------------------*/

    Route::get('/optimize', function () {
        Artisan::call('optimize');
        return 'optimize complete';
    });
});


require __DIR__ . '/auth.php';
require __DIR__ . '/simrs.php';
require __DIR__ . '/default-menu.php';
