<?php

use App\Http\Controllers\API\AttendanceCodeController;
use App\Http\Controllers\API\AttendanceController;
use App\Http\Controllers\API\AttendanceRequestController;
use App\Http\Controllers\API\BankController;
use App\Http\Controllers\API\DayOffController;
use App\Http\Controllers\API\EmployeeController;
use App\Http\Controllers\API\JobLevelController;
use App\Http\Controllers\API\JobPositionController;
use App\Http\Controllers\API\OrganizationController;
use App\Http\Controllers\API\ShiftController;
use App\Http\Controllers\API\BankEmployeeController;
use App\Http\Controllers\API\DayOffRequestController;
use App\Http\Controllers\API\KPIController;
use App\Http\Controllers\API\StructureController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\LocationController;
use App\Http\Controllers\API\MenuController;
use App\Http\Controllers\API\PayrollApiController;
use App\Http\Controllers\API\PendidikanPelatihanController;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\SalaryController;
use App\Http\Controllers\API\TargetController;
use App\Http\Controllers\BotMessageController;
use App\Http\Controllers\DeductionController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\Inventaris\BarangController;
use App\Http\Controllers\Inventaris\CategoryBarangController;
use App\Http\Controllers\Inventaris\MaintenanceBarangController;
use App\Http\Controllers\Inventaris\RoomMaintenanceController;
use App\Http\Controllers\Inventaris\TemplateBarangController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\API\TimeScheduleController;
use App\Http\Middleware\CheckAuthorizationBot;
use App\Models\AttendanceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['web', 'auth'])->prefix('inventaris')->group(function () {
    Route::prefix('room-maintenance')->group(function () {
        Route::get('/{id}', [RoomMaintenanceController::class, 'getRoom'])->name('inventaris.room.get');
        Route::post('/', [RoomMaintenanceController::class, 'store'])->name('inventaris.room.store');
        Route::patch('/{id}/update', [RoomMaintenanceController::class, 'update'])->name('inventaris.room.update');
        Route::delete('/{id}/delete', [RoomMaintenanceController::class, 'delete'])->name('inventaris.room.delete');
    });
    Route::prefix('category-barang')->group(function () {
        Route::get('/{id}', [CategoryBarangController::class, 'getCategory'])->name('inventaris.category.get');
        Route::post('/', [CategoryBarangController::class, 'store'])->name('inventaris.category.store');
        Route::patch('/{id}/update', [CategoryBarangController::class, 'update'])->name('inventaris.category.update');
        Route::delete('/{id}/delete', [CategoryBarangController::class, 'delete'])->name('inventaris.category.delete');
    });
    Route::prefix('template-barang')->group(function () {
        Route::get('/{id}', [TemplateBarangController::class, 'getTemplate'])->name('inventaris.template.get');
        Route::post('/', [TemplateBarangController::class, 'store'])->name('inventaris.template.store');
        Route::patch('/{id}/update', [TemplateBarangController::class, 'update'])->name('inventaris.template.update');
        Route::delete('/{id}/delete', [TemplateBarangController::class, 'delete'])->name('inventaris.template.delete');
    });
    Route::prefix('barang')->group(function () {
        Route::get('/{id}', [BarangController::class, 'getBarang'])->name('inventaris.barang.get');
        Route::post('/', [BarangController::class, 'store'])->name('inventaris.barang.store');
        Route::patch('/move', [BarangController::class, 'move'])->name('inventaris.barang.move');
        Route::patch('/pinjam', [BarangController::class, 'pinjam'])->name('inventaris.barang.pinjam');
        Route::patch('/back', [BarangController::class, 'back'])->name('inventaris.barang.back');
        Route::patch('/{id}/update', [BarangController::class, 'update'])->name('inventaris.barang.update');
        Route::delete('/{id}/delete', [BarangController::class, 'delete'])->name('inventaris.barang.delete');
    });
    Route::prefix('maintenance')->group(function () {
        Route::get('/{id}', [MaintenanceBarangController::class, 'getMaintenance'])->name('inventaris.maintenance.get');
        Route::get('/{id}/dokumentasi', [MaintenanceBarangController::class, 'getFoto'])->name('inventaris.maintenance.view');
        Route::post('/', [MaintenanceBarangController::class, 'store'])->name('inventaris.maintenance.store');
        Route::patch('/{id}/update', [MaintenanceBarangController::class, 'update'])->name('inventaris.maintenance.update');
        Route::delete('/{id}/delete', [MaintenanceBarangController::class, 'delete'])->name('inventaris.maintenance.delete');
    });
});


Route::middleware(['web', 'auth'])->prefix('dashboard')->group(function () {
    Route::post('/clock-in', [AttendanceController::class, 'clock_in']);
    Route::post('/clock-out', [AttendanceController::class, 'clock_out'])->name('employee.attendance.clock-out');
    // Route::put('/clock-in', [AttendanceController::class, 'clock_in']);
    // Route::put('/clock-out', [AttendanceController::class, 'clock_out']);
    Route::post('/management-shift/store', [AttendanceController::class, 'import']);

    Route::put('/management-shift/update', [AttendanceController::class, 'updateManagementShift']);


    Route::get('/attendances/{id}', [AttendanceController::class, 'getAttendance']);
    Route::put('/attendances/update/{id}', [AttendanceController::class, 'updateAttendance'])->name('update-absensi');
    Route::post('/attendances/update/{id}/ontime', [AttendanceRequestController::class, 'ontime'])->name('ontime');
    Route::post('/attendances/update/{id}/alfa', [AttendanceRequestController::class, 'alfa'])->name('alfa');
    Route::post('/attendances/update/ontimeAll', [AttendanceRequestController::class, 'ontimeAll'])->name('attendance.ontimeAll');

    Route::get('location/get/{id}', [LocationController::class, 'getLocation']);
    Route::post('location/store', [LocationController::class, 'store']);
    Route::put('location/update/{id}', [LocationController::class, 'update']);
    Route::get('location/delete/{id}', [LocationController::class, 'destroy']);

    Route::prefix('requests')->group(function () {
        Route::get('/day-off/{id}', [DayOffRequestController::class, 'getDayOffRequest']);
        Route::post('/day-off/update/{id}', [DayOffRequestController::class, 'update']);
        Route::get('/day-off/delete/{id}', [DayOffRequestController::class, 'destroy']);

        Route::get('/attendance/{id}', [AttendanceRequestController::class, 'getAttendanceRequest']);
        Route::post('/attendance/update/{id}', [AttendanceRequestController::class, 'update']);
        Route::get('/attendance/delete/{id}', [AttendanceRequestController::class, 'destroy']);
    });

    Route::prefix('files')->group(function () {
        Route::post('store', [FileUploadController::class, 'storeKepegawaian']);
        Route::get('/download-document/{id}', [FileUploadController::class, 'downloadDocument'])->name('download.document');
        Route::get('/delete/{id}', [FileUploadController::class, 'destroy'])->name('files.delete');
    });

    //organization
    Route::prefix('organization')->group(function () {
        Route::post('/store', [OrganizationController::class, 'store']);
        Route::put('/update/{id}', [OrganizationController::class, 'update']);
        Route::get('/get/{id}', [OrganizationController::class, 'getOrganization']);
        Route::get('/delete/{id}', [OrganizationController::class, 'destroy']);
    });

    Route::prefix('kpi')->group(function () {
        Route::post('/save-signature/{id}', [KPIController::class, 'saveSignature']);
        Route::post('/store', [KPIController::class, 'storeGroupForm']);
        Route::get('/group_penilaian/{id}/delete', [KPIController::class, 'destroyForm']);
        Route::put('/group_penilaian/{id}/update', [KPIController::class, 'updateGroupForm']);
        Route::post('/{id_group_penilaian}/{id_pegawai}/store', [KPIController::class, 'storePenilaianPegawai']);
        Route::get('/employee/{id_pegawai}/get', [KPIController::class, 'getPegawai']);
        Route::post('/aspek-penilaian/{id}/destroy', [KPIController::class, 'destroyAspek']);
        Route::post('/indikator-penilaian/{id}/destroy', [KPIController::class, 'destroyIndikator']);
        // Route::put('/update/{id}', [OrganizationController::class, 'update']);
        // Route::get('/get/{id}', [OrganizationController::class, 'getOrganization']);
        // Route::get('/delete/{id}', [OrganizationController::class, 'destroy']);
    });

    Route::prefix('permissions')->group(function () {
        Route::post('/store', [PermissionController::class, 'store'])->name('permissions.store');
        Route::get('/get/{id}', [PermissionController::class, 'getPermission'])->name('permissions.get');
        Route::put('/get/{id}', [PermissionController::class, 'update'])->name('permissions.update');
        Route::get('/delete/{id}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
    });

    Route::prefix('role')->group(function () {
        Route::get('/get/{id}', [RoleController::class, 'getRole']);
        Route::post('/store', [RoleController::class, 'store']);
        Route::put('/update/{id}', [RoleController::class, 'update']);
        Route::get('/delete/{id}', [RoleController::class, 'destroy']);
        Route::post('/assignPermissions/{roleName}', [RoleController::class, 'assignPermissions']);
    });
    Route::prefix('user')->group(function () {
        Route::post('/store', [UserController::class, 'store']);
        Route::put('/update/{id}', [UserController::class, 'update']);
        Route::put('/update-akses/{user:id}', [UserController::class, 'updateRole']);
        Route::get('/get/{id}', [UserController::class, 'getUser']);
        Route::get('/delete/{id}', [UserController::class, 'destroy']);
        Route::post('/assign-permissions', [UserController::class, 'storePermissions']);
    });
    Route::prefix('job-level')->group(function () {
        Route::post('/store', [JobLevelController::class, 'store']);
        Route::put('/update/{id}', [JobLevelController::class, 'update']);
        Route::get('/get/{id}', [JobLevelController::class, 'getJobLevel']);
        Route::get('/delete/{id}', [JobLevelController::class, 'destroy']);
    });
    Route::prefix('job-position')->group(function () {
        Route::post('/store', [JobPositionController::class, 'store']);
        Route::put('/update/{id}', [JobPositionController::class, 'update']);
        Route::get('/get/{id}', [JobPositionController::class, 'getJobPosition']);
        Route::get('/delete/{id}', [JobPositionController::class, 'destroy']);
    });
    Route::prefix('employee')->group(function () {
        Route::get('/pegawai/{id}', [EmployeeController::class, 'pegawai']);
        Route::post('/store', [EmployeeController::class, 'store']);
        Route::post('/non-aktif/{id}', [EmployeeController::class, 'nonAktifPegawai']);
        Route::put('/update-personal/{id}', [EmployeeController::class, 'updatePersonal']);
        Route::put('/update-identitas/{id}', [EmployeeController::class, 'updateIdentitas']);
        Route::get('/get/{id}', [EmployeeController::class, 'edit']);
        Route::get('/lokasi/{id}', [EmployeeController::class, 'editLokasi']);
        Route::get('/delete/{id}', [EmployeeController::class, 'destroy']);
        Route::post('/import', [EmployeeController::class, 'import']);
        Route::get('/get/{id}', [EmployeeController::class, 'edit']);
        Route::put('/approval_line/{id}', [EmployeeController::class, 'updateApprovalLine']);
        Route::post('/location/store', [EmployeeController::class, 'storeLocation']);
        Route::get('/organization/{id}', [EmployeeController::class, 'editOrganisasi']);
        Route::put('/organization/{id}', [EmployeeController::class, 'updateOrganization']);
        Route::post('/salary/export', [EmployeeController::class, 'exportSalary'])->name('salary.export');
        Route::post('/salary/export/deductions', [PayrollApiController::class, 'exportPayrollDeductions']);
        Route::post('/salary/import/deductions', [PayrollApiController::class, 'importPayrollDeductions']);
        Route::post('/salary/import', [EmployeeController::class, 'importSalary'])->name('salary.import');
        Route::post('/deduction/export', [EmployeeController::class, 'exportDeduction'])->name('deduction.export');
        Route::post('/deduction/import', [EmployeeController::class, 'importDeduction'])->name('deduction.import');
        Route::get('/getByOrganization', [EmployeeController::class, 'getEmployeesByOrganization'])->name('getEmployeesByOrganization');
    });

    Route::prefix('attendances')->group(function () {
        Route::get('/report/employee/{employee_id}/{periode}/{tahun}', [ReportController::class, 'getReportAttendancesEmployee']);
        Route::post('/detail', [AttendanceController::class, 'getDetailAttendance']);
        Route::post('/request/form', [AttendanceRequestController::class, 'submitFormReqAttendance'])->name('attendances.form.submit');
    });

    Route::prefix('day-off')->group(function () {
        Route::post('/store', [DayOffController::class, 'store']);
        Route::put('/update/{id}', [DayOffController::class, 'update']);
        Route::get('/get/{id}', [DayOffController::class, 'getHoliday']);
        Route::get('/delete/{id}', [DayOffController::class, 'destroy']);
    });

    Route::prefix('attendance-codes')->group(function () {
        Route::post('/store', [AttendanceCodeController::class, 'store']);
        Route::put('/update/{id}', [AttendanceCodeController::class, 'update']);
        Route::get('/get/{id}', [AttendanceCodeController::class, 'getAttendanceCode']);
        Route::get('/delete/{id}', [AttendanceCodeController::class, 'destroy']);
    });
    Route::prefix('shifts')->group(function () {
        Route::post('/store', [ShiftController::class, 'store']);
        Route::put('/update/{id}', [ShiftController::class, 'update']);
        Route::get('/get/{id}', [ShiftController::class, 'getShift']);
        Route::get('/delete/{id}', [ShiftController::class, 'destroy']);
        Route::get('/export/{organization:id}', [ShiftController::class, 'export'])->name('shift.export');
    });
    Route::prefix('banks')->group(function () {
        Route::post('/store', [BankController::class, 'store']);
        Route::put('/update/{id}', [BankController::class, 'update']);
        Route::get('/get/{id}', [BankController::class, 'getBank']);
        Route::get('/delete/{id}', [BankController::class, 'destroy']);
    });
    Route::prefix('bank-employees')->group(function () {
        Route::post('/store', [BankEmployeeController::class, 'store']);
        Route::put('/update/{id}', [BankEmployeeController::class, 'update']);
        Route::get('/get/{id}', [BankEmployeeController::class, 'getBankEmployee']);
        Route::get('/delete/{id}', [BankEmployeeController::class, 'destroy']);
    });
    Route::prefix('structures')->group(function () {
        Route::post('/store', [StructureController::class, 'store']);
        Route::put('/update/{id}', [StructureController::class, 'update']);
        Route::get('/get/{id}', [StructureController::class, 'getStructure']);
        Route::get('/delete/{id}', [StructureController::class, 'destroy']);
        Route::get('/hierarchy', [StructureController::class, 'getHierarchy']);
    });
    Route::prefix('targets')->group(function () {
        Route::post('/store', [TargetController::class, 'store']);
        Route::put('/update/{id}', [TargetController::class, 'update']);
        Route::put('/update-hasil/{id}', [TargetController::class, 'updateHasil']);
        Route::get('/get/{id}', [TargetController::class, 'getTarget']);
        Route::delete('/delete/{id}', [TargetController::class, 'destroy']);
    });
    Route::prefix('payroll')->group(function () {
        Route::prefix('salary')->group(function () {
            Route::post('/store', [SalaryController::class, 'store']);
            Route::get('/get/{id}', [SalaryController::class, 'getSalary']);
            Route::put('/update/{id}', [SalaryController::class, 'update']);
        });

        Route::prefix('deduction')->group(function () {
            Route::post('/store', [DeductionController::class, 'store']);
            Route::get('/get/{id}', [DeductionController::class, 'getDeduction']);
            Route::put('/update/{id}', [DeductionController::class, 'update']);
        });

        Route::get('/get/{id}', [PayrollApiController::class, 'get'])->name("api.get.payroll");
        Route::get('/getDeduction/{id}', [PayrollApiController::class, 'getDeduction'])->name("api.get.deduction");
        Route::get('/getAllowance/{id}', [PayrollApiController::class, 'getAllowance'])->name("api.get.allowance");
        Route::get('/getTotalPayroll/{id}', [PayrollApiController::class, 'getTotalPayroll'])->name("api.get.total.payroll");
        Route::post('/get', [PayrollApiController::class, 'getAll'])->name("get.all.payroll");
        Route::put('/update/{id}', [PayrollApiController::class, 'update'])->name("api.update.payroll");
        Route::post('/run', [PayrollApiController::class, 'store'])->name("api.run.payroll");
        Route::post('/run-payroll', [PayrollApiController::class, 'runPayroll'])->name("api.run");
        Route::delete('/delete/{id}', [PayrollApiController::class, 'destroy'])->name("api.delete.payroll");
    });

    Route::prefix('menu')->group(function () {
        Route::get('/get/{id}', [MenuController::class, 'getDataMenu'])->name('master-data.menu.get');
        Route::post('/store', [MenuController::class, 'store'])->name('master-data.menu.store');
        Route::post('/update/{id}', [MenuController::class, 'update'])->name('master-data.menu.update');
        Route::delete('/delete/{id}', [MenuController::class, 'destroy'])->middleware('check.api.credentials')->name('master-data.menu.delete');
    });

    Route::prefix('time-schedules')->group(function () {
        Route::prefix('rapat')->group(function () {
            Route::post("/store", [TimeScheduleController::class, 'store'])->name("time.schedule.rapat.store");
            Route::get("/get-peserta/{rapatId}", [TimeScheduleController::class, 'getPeserta'])->name("time.schedule.rapat.get.peserta");
            // Rute untuk upload file
            Route::post('/time/schedule/rapat/upload', [TimeScheduleController::class, 'uploadFile'])->name('time.schedule.rapat.upload');
            Route::get('/download/{id}/{type}', [TimeScheduleController::class, 'download'])->name('time.schedule.rapat.download');
            // Rute untuk verifikasi kehadiran peserta
            Route::post('/verifikasi', [TimeScheduleController::class, 'verifikasiKehadiran'])->name('time.schedule.rapat.verifikasi');
        });
    });
    Route::prefix('pendidikan-pelatihan')->group(function () {
        Route::post("/store", [PendidikanPelatihanController::class, 'store'])->name("pendidikan.pelatihan.store");
        Route::get("/get-peserta/{pendidikanPelatihanId}", [PendidikanPelatihanController::class, 'getPeserta'])->name("pendidikan.pelatihan.get.peserta");
        Route::get("/get-konfirmasi-peserta/{pendidikanPelatihanId}", [PendidikanPelatihanController::class, 'getKonfirmasiPeserta'])->name("pendidikan.pelatihan.get.konfirmasi.peserta");
        Route::put("/confirm/{id}", [PendidikanPelatihanController::class, 'confirmKehadiran'])->name("pendidikan.pelatihan.put");
    });
    Route::get('user/getByName', [UserController::class, 'getByName'])->name('user.getByName');
});


Route::post('process-message', [BotMessageController::class, 'processMessage'])->middleware(CheckAuthorizationBot::class)->name('bot.kirim-pesan');
Route::post('notify-contract', [BotMessageController::class, 'notifyExpiryContract'])->middleware(CheckAuthorizationBot::class);
// Route::get('notify-contract', [BotMessageController::class, 'notifyExpiryContract']);



require __DIR__ . '/api-simrs.php';
