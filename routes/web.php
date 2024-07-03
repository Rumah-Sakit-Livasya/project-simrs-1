<?php

use App\Http\Controllers\API\AttendanceController;
use App\Http\Controllers\API\AttendanceRequestController;
use App\Http\Controllers\API\DayOffRequestController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Pages\CompanyController;
use App\Http\Controllers\API\CompanyController as ApiCompanyController;
use App\Http\Controllers\BotMessageController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\Pages\DashboardController;
use App\Http\Controllers\Pages\UpdateProfileController;
use App\Http\Controllers\Pages\UserController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\WhatsappController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('user.login');
// Route::post('/', [AuthenticatedSessionController::class, 'store']);
Route::post('/', [AuthenticatedSessionController::class, 'store']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard
    Route::get("/dashboard", [DashboardController::class, 'index'])->name("dashboard");

    Route::prefix('dashboard')->group(function () {
        Route::get("/ttd", function () {
            return view('pages.kpi.penilaian.partials.ttd');
        })->name('ttd');
        Route::get("/locations", [DashboardController::class, 'getDataLocations'])->name("locations");
        Route::get("/profile", [DashboardController::class, 'getDataUser'])->name("profile");
        // Route::get("/users", [DashboardController::class, 'getDataUsers'])->name("users");
        Route::get("/roles", [DashboardController::class, 'getDataRoles'])->name("roles");
        Route::get("/all-requests", [DashboardController::class, 'getDataRequests'])->name("admin.requests");
        Route::get("/company", [CompanyController::class, 'index'])->name("company");
        Route::prefix('company')->group(function () {
            Route::get('/get/{id}', [ApiCompanyController::class, 'getCompany']);
            Route::post('/store', [ApiCompanyController::class, 'store']);
            Route::put('/update/{id}', [ApiCompanyController::class, 'update']);
            Route::put('/update-location/{id}', [ApiCompanyController::class, 'updateLocation']);
        });
        Route::prefix('reports')->group(function () {
            Route::get('/attendances', [ReportController::class, 'attendanceReports'])->name('reports.attendance');
            Route::post('/attendances', [ReportController::class, 'filterAttendanceReports'])->name('reports.filter.attendance');
        });
        Route::get("/attendances", [DashboardController::class, 'getAllAttendances'])->name("admin.attendances");
        Route::get("/attendances/employee/{id}", [DashboardController::class, 'getEmployeeAttendance'])->name("show.employee.attendance");
        Route::get("/attendances/employee/{id}/payroll", [DashboardController::class, 'getEmployeeAttendancePayroll'])->name("show.employee.attendance.payroll");
        Route::get("/organizations", [DashboardController::class, 'getDataOrganizations'])->name("organization");
        Route::get("/job-level", [DashboardController::class, 'getDataJobLevels'])->name("job-level");
        Route::get("/job-position", [DashboardController::class, 'getDataJobPositions'])->name("job-position");
        Route::get("/employees", [DashboardController::class, 'getDataEmployees'])->name("employees");
        Route::post('/employees', [DashboardController::class, 'pegawaiNonAktifList'])->name('get.non-aktif-pegawai');
        Route::get("/day-off", [DashboardController::class, 'getDataHolidays'])->name("day-off");
        Route::get("/attendance-codes", [DashboardController::class, 'getDataAttendanceCodes'])->name("attendance-codes");
        Route::get("/shifts", [DashboardController::class, 'getDataShifts'])->name("shifts");
        Route::get("/banks", [DashboardController::class, 'getDataBanks'])->name("banks");
        Route::get("/bank-employees", [DashboardController::class, 'getDataBankEmployees'])->name("bank-employees");
        Route::get("/structures", [DashboardController::class, 'getDataStructures'])->name("structures");
        Route::get("/management-shift", [DashboardController::class, 'getManagementShift'])->name("management-shift");
        Route::get("/management-shift/edit/{id}", [DashboardController::class, 'editManagementShift'])->name("edit-management-shift");
    });

    Route::prefix('users')->group(function () {
        Route::get('/', [DashboardController::class, 'getDataUsers'])->name('users');
        Route::get('/akses', [DashboardController::class, 'getDataUserAkses'])->name('users.akses');
    });

    Route::prefix('outsource')->group(function () {
        Route::get("/attendances", [DashboardController::class, 'getAttendancesOutsourcing'])->name("attendances.outsource");
        Route::post("/attendances", [AttendanceController::class, 'clock_in_outsource'])->name("clockin.outsource");
        Route::post("/attendances/store", [AttendanceController::class, 'storeAttendanceOutsourcing'])->name("attendances.outsource.store");
        Route::get("/attendances/all", [DashboardController::class, 'getAttendancesOutsourcingAll'])->name("attendances.outsource.all");
        Route::post("/attendances/clock_out", [AttendanceController::class, 'clock_out_outsource'])->name("clockout.outsource");
    });

    Route::prefix('kpi')->group(function () {
        Route::prefix('master-data')->group(function () {
            Route::get('/group-penilaian/bulanan', [DashboardController::class, 'getGroupPenilaian'])->name('kpi.get.group-penilaian');
            Route::get('/group-penilaian/bulanan/{id}/edit', [DashboardController::class, 'editGroupPenilaian'])->name('kpi.edit.group-penilaian');
            Route::get('/group-penilaian/rekap/bulanan', [DashboardController::class, 'rekapPenilaianBulanan'])->name('kpi.rekap.penilaian.bulanan');
            Route::get('/group-penilaian/bulanan/tambah', [DashboardController::class, 'tbhGroupPenilaian'])->name('kpi.tbh.group-penilaian');
            Route::get('/group-penilaian/bulanan/tambah/{id}', [DashboardController::class, 'tbhPenilaian'])->name('kpi.tbh.penilaian');
            Route::get('/group-penilaian/bulanan/{id_form}/{id_pegawai}/{periode}/{tahun}/show', [DashboardController::class, 'showPenilaianBulanan'])->name('kpi.show.penilaian.bulanan');
            Route::get('/aspek-penilaian', [DashboardController::class, 'getAspekPenilaian'])->name('kpi.get.aspek-penilaian');
            Route::get('/aspek-penilaian/tambah', [DashboardController::class, 'tbhAspekPenilaian'])->name('kpi.tbh.aspek-penilaian');
            Route::get('/indikator-penilaian', [DashboardController::class, 'getIndikatorPenilaian'])->name('kpi.get.indikator-penilaian');
        });
    });

    Route::prefix('monitoring')->group(function () {
        Route::get("/", [MonitoringController::class, 'index'])->name("monitoring");
    });

    Route::prefix('employee')->group(function () {
        Route::get("/attendances", [DashboardController::class, 'getAttendances'])->name("attendances");
        Route::get("/attendances/filter/", [DashboardController::class, 'getAttendancesFilter'])->name('attendances.filter');
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

        Route::get('/edit-profil/{id}', [UpdateProfileController::class, 'show'])->name('get.image');
        Route::post('/update-profil/{id}', [UpdateProfileController::class, 'update'])->name('update.image');
    });

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

    Route::get('/optimize', function () {
        Artisan::call('optimize');
        return 'optimize complete';
    });

    Route::get('/whatsapp', function () {
        return view('pages.whatsapp.index');
    })->name('whatsapp');
    Route::get('/whatsapp/broadcast', function () {
        return view('pages.whatsapp.index');
    })->name('broadcast');
    Route::get('/whatsapp/group_kontak', function () {
        return view('pages.whatsapp.index');
    })->name('group_kontak');
    Route::post('/whatsapp/send', [WhatsappController::class, 'sendMessage'])->name('whatsapp.send');
});


require __DIR__ . '/auth.php';
require __DIR__ . '/default-menu.php';
