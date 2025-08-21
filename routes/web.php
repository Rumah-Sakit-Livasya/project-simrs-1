<?php

use App\Http\Controllers\API\AttendanceController;
use App\Http\Controllers\API\AttendanceRequestController;
use App\Http\Controllers\API\DayOffRequestController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Pages\CompanyController;
use App\Http\Controllers\API\CompanyController as ApiCompanyController;
use App\Http\Controllers\API\TimeScheduleController;
use App\Http\Controllers\API\WasteTransportController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ChecklistHarianCategoryController;
use App\Http\Controllers\ChecklistHarianController;
use App\Http\Controllers\DailyWasteInputController;
use App\Http\Controllers\InternalVehiclePageController;
use App\Http\Controllers\KunjunganPageController;
use App\Http\Controllers\LaporanInternalController;
use App\Http\Controllers\Laundry\DailyLinenInputController;
use App\Http\Controllers\Laundry\LinenTypeController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\Pages\DashboardController;
use App\Http\Controllers\Pages\UpdateProfileController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SurveiKebersihanKamarController;
use App\Http\Controllers\SwitchUserController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TestingDataController;
use App\Http\Controllers\UrlShortenerController;
use App\Http\Controllers\WasteReportController;
use App\Http\Controllers\WhatsappController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ImpersonateUser;
use App\Http\Middleware\LastSeenUser;
use App\Models\ChecklistHarianCategory;
use App\Models\LaporanInternal;
use Illuminate\Support\Facades\File;
use Pusher\Pusher; // Jangan lupa tambahkan ini jika belum ada

// Route::get('/test', [TimeScheduleController::class, 'getEmployeesByOrganizationAndJobPosition']);

Route::get('/', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('user.login');
Route::post('/', [AuthenticatedSessionController::class, 'store']);

Route::middleware([LastSeenUser::class])->group(function () {
    Route::middleware('auth')->group(function () {
        Route::get('/signature-pad', function () {
            return view('pages.simrs.erm.partials.signature-pad');
        })->name('signature.pad');

        Route::get('/kunjungan', [KunjunganPageController::class, 'index'])->name('kunjungan.index');
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
            Route::get("/attendance-requests/form", [DashboardController::class, 'attendanceRequestForm'])->name("attendance-requests.form");
            Route::get("/attendance-requests/{id}", [DashboardController::class, 'getAttendanceRequest'])->name("attendance-requests.get");
            Route::get("/attendance-requests/download-lampiran", [DashboardController::class, 'attendanceRequestLamp'])->name("attendances-requests.download-lampiran");

            Route::post('/update/{user_id}/request', [DashboardController::class, 'updateStatusRequestAttendance'])->name("acc.update");
            Route::get("/settings", [DashboardController::class, 'getSettingAttendances'])->name('attendances.settings');

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
                Route::get('/daftar-dayoff-timeoff', [ReportController::class, 'dayOffReqReports'])->name('reports.dayOffReq');
                Route::get('/daftar-dayoff-timeoff/{id}/{tahun}/get', [ReportController::class, 'dayOffReqReportDetail'])->name('reports.dayOffReq.detail');
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
            // web.php
            Route::get('/team', [TeamController::class, 'index'])->name('team.index');
            Route::get('/team/search', [TeamController::class, 'search'])->name('team.search');
        });
        /* END PEGAWAI ----------------------------------------------------------------------------*/

        /*
    |--------------------------------------------------------------------------
    |  TIMESCHEDULE
    |--------------------------------------------------------------------------
    */
        Route::prefix('time-schedules')->group(function () {
            Route::prefix('rapat')->group(function () {
                Route::get("/", [DashboardController::class, 'getDataTimeScheduleRapat'])->name("time.schedule.rapat");
                Route::get("/report", [DashboardController::class, 'getDataTimeScheduleReportRapat'])->name("time.schedule.report.rapat");
            });
        });
        /* END TIMESCHEDULE ----------------------------------------------------------------------------*/

        /*
    |--------------------------------------------------------------------------
    |  PENDIDIKANPELATIHAN
    |--------------------------------------------------------------------------
    */
        Route::prefix('pendidikan-pelatihan')->group(function () {
            Route::get("/", [DashboardController::class, 'getDataPendidikanPelatihan'])->name("pendidikan.pelatihan");
            Route::get("/confirm/{id}", [DashboardController::class, 'getPendidikanPelatihan'])->name("pendidikan.pelatihan.get");
        });
        /* END PENDIDIKANPELATIHAN ----------------------------------------------------------------------------*/

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
            Route::get('/form-penilaian/{id}/{periode}/{encryptTahunDanEmployeeId}', [DashboardController::class, 'showPenilaian'])->name('kpi.show.form-penilaian.done');
            Route::get('/form-penilaian/{id_form}/{id_pegawai}/{tahun}/show', [DashboardController::class, 'showPenilaianBulanan'])->name('kpi.show.penilaian.bulanan');
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
        // routes/web.php

        // routes/web.php

        Route::prefix('whatsapp')->name('whatsapp.')->group(function () {

            // --- Rute untuk Halaman Chat dan Balasan Pesan ---

            // 1. Rute utama untuk menampilkan halaman chat.
            //    MENUNJUK KE METODE BARU: showChatPage
            //    Parameter {phoneNumber?} membuatnya bisa menangani:
            //    - /whatsapp (halaman utama)
            //    - /whatsapp/62812345 (halaman chat spesifik)
            Route::get('/{phoneNumber?}', [WhatsappController::class, 'showChatPage'])->name('chat');

            // 2. Rute untuk mengirim balasan dari form di halaman chat
            Route::post('/reply', [WhatsappController::class, 'reply'])->name('reply');

            // --- Rute Lain yang Sudah Ada (Tidak Perlu Diubah) ---

            // 3. Menampilkan halaman untuk kirim broadcast
            Route::get('/broadcast', function () {
                return view('pages.whatsapp.broadcast');
            })->name('broadcast');

            // 4. Mengirim pesan (dari form broadcast, dll.)
            //    Rute ini sudah benar, kita hanya perlu menambahkan metodenya di controller.
            Route::post('/send', [WhatsappController::class, 'sendMessage'])->name('send');

            // 5. Menampilkan halaman untuk manajemen grup kontak
            Route::get('/group_kontak', function () {
                return view('pages.whatsapp.group_kontak');
            })->name('group_kontak');

            Route::get('/message-status/{message}', [App\Http\Controllers\WhatsappController::class, 'checkStatus'])
                ->name('whatsapp.status');
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
    |  Survei
    |--------------------------------------------------------------------------
    */
        Route::get("/survei/kebersihan-kamar", [SurveiKebersihanKamarController::class, 'index'])->name('survei.kebersihan-kamar');
        Route::get("/survei/kebersihan-kamar/tambah", [SurveiKebersihanKamarController::class, 'create'])->name('tambah.survei.kebersihan-kamar');
        Route::post("/survei/kebersihan-kamar/store", [SurveiKebersihanKamarController::class, 'store'])->name('store.survei.kebersihan-kamar');
        Route::get("/survei/kebersihan-kamar/{id}/edit", [SurveiKebersihanKamarController::class, 'edit'])->name('edit.survei.kebersihan-kamar');
        Route::patch("/survei/kebersihan-kamar/{id}/edit", [SurveiKebersihanKamarController::class, 'update'])->name('update.survei.kebersihan-kamar');
        Route::delete("/survei/kebersihan-kamar/{id}/delete", [SurveiKebersihanKamarController::class, 'delete'])->name('delete.survei.kebersihan-kamar');
        Route::get('storage/private/survei/kebersihan_kamar/{filename}', function ($filename) {
            $path = storage_path('app/private/survei/kebersihan_kamar/' . $filename);

            if (!File::exists($path)) {
                abort(404);
            }

            return response()->file($path);
        });
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

        /*
    |--------------------------------------------------------------------------
    | USERS AKSES
    |--------------------------------------------------------------------------
    */
        Route::prefix('checklist-harian')->group(function () {
            Route::prefix('category')->group(function () {
                Route::get('/', [ChecklistHarianCategoryController::class, 'index'])->name('checklist.category.index');
            });
            Route::get('/', [ChecklistHarianController::class, 'index'])->name('checklist.index');
        });

        Route::middleware(['auth'])->group(function () {
            Route::get('/laporan-internal', [LaporanInternalController::class, 'index'])->name('laporan-internal')->middleware('can:view laporan internal');
            Route::get('/laporan-internal/get/{id}', [LaporanInternalController::class, 'getLaporan'])->name('laporan-internal')->middleware('can:view laporan internal');
            Route::post('/laporan-internal-filter', [LaporanInternalController::class, 'index'])->name('laporan-internal.filter');
            Route::post('/laporan-internal', [LaporanInternalController::class, 'store']);
            Route::post('/laporan-internal/import', [LaporanInternalController::class, 'i`mport'])->name('laporan-internal.import');
            Route::post('/laporan-internal/{id}', [LaporanInternalController::class, 'update']);
            Route::get('/laporan-internal-list', [LaporanInternalController::class, 'list']);
            Route::delete('/laporan-internal/{id}', [LaporanInternalController::class, 'destroy']);
            Route::post('/laporan-internal/complete/{id}', [LaporanInternalController::class, 'complete']);
            Route::get('/laporan-internal/export-harian', [LaporanInternalController::class, 'exportHarian'])
                ->name('laporan.internal.export.harian');
            Route::get('/laporan-internal/export-word-harian', [LaporanInternalController::class, 'exportWordHarian'])
                ->name('laporan.internal.export.word');
            Route::get('/laporan-internal/export-laporan-bulanan', [LaporanInternalController::class, 'exportPPTXHarian'])
                ->name('laporan.internal.export.pptx');
        });
        // routes/web.php
        Route::middleware(['auth'])->group(function () {
            Route::prefix('dashboard')->group(function () {
                Route::post('/url-shortener', [UrlShortenerController::class, 'store'])->name('dashboard.url_shortener.store');
                Route::delete('/url-shortener/{id}', [UrlShortenerController::class, 'destroy'])->name('dashboard.url_shortener.delete');
            });
        });
        Route::prefix('links')->group(function () {
            Route::get('/', [LinkController::class, 'index'])->name('links.index');
            Route::post('/shorten', [LinkController::class, 'shorten'])->name('shorten');
            Route::get('/{code}', [LinkController::class, 'redirect']);
            Route::delete('/links/{id}', [LinkController::class, 'destroy'])->name('links.destroy');
            // Tambahkan route untuk analytics
        });

        Route::get('/optimize', function () {
            Artisan::call('optimize');
            return 'optimize complete';
        });
    });
});

// routes/web.php

Route::middleware(['auth'])->group(function () {
    Route::get('/daily-waste', [DailyWasteInputController::class, 'index'])->name('daily-waste.index');
    Route::get('/waste-transports', [DailyWasteInputController::class, 'transport'])->name('daily-waste.transport');
    Route::post('/waste-transports/store-or-update-batch', [WasteTransportController::class, 'storeOrUpdateBatch'])->name('waste-transports.storeOrUpdateBatch');
    // Route untuk menampilkan halaman laporan
    Route::get('/waste-transports/reports', [WasteReportController::class, 'index'])->name('reports.waste.index');
    // Route untuk mengambil data laporan via AJAX
    Route::get('/waste-transports/getWasteData', [WasteReportController::class, 'getWasteData'])->name('reports.waste.data');

    Route::get('/daily-waste/reports', [WasteReportController::class, 'dailyIndex'])->name('reports.daily.index');
    // Route untuk mengambil data laporan via AJAX
    Route::get('/daily-waste/getDailyWasteData', [WasteReportController::class, 'getDailyWasteData'])->name('reports.daily.data');

    // Laundry
    Route::get('daily-linens', [DailyLinenInputController::class, 'index'])->name('daily-linens.index');
    Route::get('daily-linens/create', [DailyLinenInputController::class, 'create'])->name('daily-linens.create');
    Route::post('daily-linens', [DailyLinenInputController::class, 'store'])->name('daily-linens.store');
    Route::get('daily-linens/{daily_linen}/edit', [DailyLinenInputController::class, 'edit'])->name('daily-linens.edit');
    Route::put('daily-linens/{daily_linen}', [DailyLinenInputController::class, 'update'])->name('daily-linens.update');
    Route::delete('daily-linens/{daily_linen}', [DailyLinenInputController::class, 'destroy'])->name('daily-linens.destroy');

    Route::post('daily-linens-batch', [DailyLinenInputController::class, 'storeOrUpdateBatch'])->name('daily-linens.storeOrUpdateBatch');

    Route::get('/daily-linens/reports', [WasteReportController::class, 'laundryIndex'])->name('laundry.index');
    Route::get('/daily-linens/getLaundryData', [WasteReportController::class, 'getLaundryData'])->name('laundry.data');

    Route::resource('master/linen-types', LinenTypeController::class);

    // Manajemen Kendaraan
    Route::prefix('manajemen-kendaraan')->name('vehicles.')->group(function () {
        // Halaman daftar kendaraan
        Route::get('/', [InternalVehiclePageController::class, 'index'])->name('index');
        // Halaman drivers
        Route::get('/drivers', [InternalVehiclePageController::class, 'drivers'])->name('drivers');
        // Halaman dashboard manajemen kendaraan
        Route::get('/dashboard', [InternalVehiclePageController::class, 'dashboard'])->name('dashboard');
        // Halaman daftar inspection item kendaraan internal
        Route::get('/inspection-items', [InternalVehiclePageController::class, 'inspection_item'])->name('inspection_items');
        // Halaman daftar vendor kendaraan internal
        Route::get('/workshop-vendors', [InternalVehiclePageController::class, 'vendors'])->name('vendors');
        // Halaman riwayat penggunaan kendaraan
        Route::get('/vehicle-logs', [InternalVehiclePageController::class, 'vehicle_logs'])->name('vehicle_logs');

        // Halaman daftar inspeksi kendaraan internal
        Route::get('/inspections', [InternalVehiclePageController::class, 'inspections'])->name('inspections');
        // Halaman form input inspeksi kendaraan internal
        Route::get('/inspections/create', [InternalVehiclePageController::class, 'create_inspection'])->name('inspections.create');

        Route::get('/service-tickets', [InternalVehiclePageController::class, 'service_tickets'])->name('service_tickets');
    });
});

Route::middleware(['auth'])->group(function () {
    Route::post('/impersonate', [SwitchUserController::class, 'impersonate'])
        ->middleware(ImpersonateUser::class)
        ->name('impersonate');
    Route::post('/switchback', [SwitchUserController::class, 'switchBack'])->name('switchback');
});


// Testing Data
Route::prefix('testing-data')->middleware(['auth'])->group(function () {
    // Menampilkan view testing
    Route::get('/', [TestingDataController::class, 'index'])->name('testing.index');

    // Mengambil data testing untuk update
    Route::get('/{id}/get', [TestingDataController::class, 'getData'])->name('testing.getData');

    // Menyimpan data testing
    Route::post('/', [TestingDataController::class, 'store'])->name('testing.store');

    // Mengupdate data testing
    Route::put('/{id}', [TestingDataController::class, 'update'])->name('testing.update');

    // Menghapus data testing
    Route::delete('/{id}', [TestingDataController::class, 'destroy'])->name('testing.destroy');
});


Route::get('/test', function () {
    $laporanKendala = LaporanInternal::with(['organization', 'user'])
        ->whereMonth('created_at', 5) // 5 = Mei
        ->whereYear('created_at', 2025) // ganti tahun sesuai kebutuhan
        ->orderBy('created_at', 'desc')
        ->get()
        ->groupBy(fn($item) => $item->organization->name)
        ->reject(fn($group, $orgName) => in_array($orgName, ['Sanitasi', 'PSRS']))
        ->map(function ($orgGroup) {
            return $orgGroup->groupBy(fn($item) => $item->user->name)
                ->map(function ($userGroup) {
                    return $userGroup->groupBy(fn($item) => $item->jenis);
                });
        });

    return view('pages.testing', [
        'laporan' => $laporanKendala,
    ]);
});



Route::get('/test-pusher', function () {
    $options = [
        'cluster' => env('PUSHER_APP_CLUSTER'),
        'useTLS' => true,
        // --- TAMBAHKAN BARIS INI ---
        'curl_options' => [
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ]
    ];
    $pusher = new Pusher(
        env('PUSHER_APP_KEY'),
        env('PUSHER_APP_SECRET'),
        env('PUSHER_APP_ID'),
        $options
    );

    $data['message'] = 'Halo ini pesan dari Laravel!';
    $pusher->trigger('my-channel', 'my-event', $data);

    return 'Event telah dikirim (tanpa verifikasi SSL)!';
});

// routes/web.php
Route::get('/test-layout', function () {
    return view('test-layout');
});

require __DIR__ . '/auth.php';
require __DIR__ . '/simrs.php';
require __DIR__ . '/inventaris.php';
require __DIR__ . '/kepustakaan.php';
require __DIR__ . '/keuangan.php';
require __DIR__ . '/mutu.php';
require __DIR__ . '/default-menu.php';
