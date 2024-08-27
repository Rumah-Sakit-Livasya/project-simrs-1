<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRequest;
use App\Models\Bank;
use App\Models\DayOffRequest;
use App\Models\Employee;
use App\Models\JobLevel;
use App\Models\JobPosition;
use App\Models\Location;
use App\Models\Organization;
use App\Models\Payroll;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class PayrollController extends Controller
{
    public function allowancePayroll()
    {
        $employees = Employee::where('is_active', 1)->get();
        if (auth()->user()->hasRole('hr')) {
            $employees = Employee::where('company_id', auth()->user()->employee->company_id)->get();
        }
        $jobLevel = JobLevel::all();
        $organizations = Organization::all();
        $jobPosition = JobPosition::all();
        $locations = Location::all();
        $getNotify = $this->getNotify();

        return view('pages.pegawai.gaji-pegawai.allowance', compact('employees', 'jobLevel', 'organizations', 'jobPosition', 'locations', 'getNotify'));
    }

    public function deductionPayroll()
    {
        $employees = Employee::where('is_active', 1)->get();
        if (auth()->user()->hasRole('hr')) {
            $employees = Employee::where('company_id', auth()->user()->employee->company_id)->get();
        }
        $jobLevel = JobLevel::all();
        $organizations = Organization::all();
        $jobPosition = JobPosition::all();
        $locations = Location::all();
        $getNotify = $this->getNotify();

        return view('pages.pegawai.gaji-pegawai.deduction', compact('employees', 'jobLevel', 'organizations', 'jobPosition', 'locations', 'getNotify'));
    }

    public function printPayroll()
    {
        $employees = Employee::where('is_active', 1)->get();
        if (auth()->user()->hasRole('hr')) {
            $employees = Employee::where('company_id', auth()->user()->employee->company_id)->get();
        }
        $getNotify = $this->getNotify();

        return view('pages.pegawai.gaji-pegawai.print', compact('employees', 'getNotify'));
    }

    public function runPayroll()
    {
        $organizations = Organization::all();
        $employees = Employee::where('is_active', 1)->get();
        if (auth()->user()->hasRole('hr')) {
            $employees = Employee::where('company_id', auth()->user()->employee->company_id)->where('is_active', 1)->get();
        }

        $jobLevel = JobLevel::all();
        $organizations = Organization::all();
        $jobPosition = JobPosition::all();
        $bank = JobPosition::all();
        $locations = Bank::all();
        $payrolls = Payroll::where('is_review', 0)->get();
        $getNotify = $this->getNotify();
        // dd($payrolls[0]);

        return view('pages.pegawai.gaji-pegawai.run-payroll', compact('employees', 'bank', 'jobLevel', 'organizations', 'jobPosition', 'locations', 'payrolls', 'getNotify'));
    }

    public function payrollHistory(Request $request)
    {
        $organizations = Organization::all();
        $employees = Employee::where('is_active', 1)->get();
        if (auth()->user()->hasRole('hr')) {
            $employees = Employee::where('company_id', auth()->user()->employee->company_id)->get();
        }
        $jobLevel = JobLevel::all();
        $organizations = Organization::all();
        $jobPosition = JobPosition::all();
        $bank = JobPosition::all();
        $locations = Bank::all();
        $payrolls = []; // Inisialisasi array kosong untuk payrolls

        // Check if filter period is applied
        if ($request->filled('periode')) {
            // Jika filter periode sudah diaplikasikan, ambil data payroll sesuai periode
            $periode = $request->input('periode');
            $payrolls = Payroll::where('is_review', 1)
                ->where('periode', $periode)
                ->get();
        }

        $getNotify = $this->getNotify();

        return view('pages.pegawai.gaji-pegawai.payroll-history', compact('employees', 'bank', 'jobLevel', 'organizations', 'jobPosition', 'locations', 'payrolls', 'getNotify'));
    }

    public function payrollCetak(Request $request)
    {
        $organizations = Organization::all();
        $employees = Employee::where('is_active', 1)->get();
        if (auth()->user()->hasRole('hr')) {
            $employees = Employee::where('company_id', auth()->user()->employee->company_id)->get();
        }
        $jobLevel = JobLevel::all();
        $organizations = Organization::all();
        $jobPosition = JobPosition::all();
        $bank = JobPosition::all();
        $locations = Bank::all();
        $payrolls = []; // Inisialisasi array kosong untuk payrolls

        // Check if filter period is applied
        if ($request->filled('periode')) {
            // Jika filter periode sudah diaplikasikan, ambil data payroll sesuai periode
            $periode = $request->input('periode');
            $payrolls = Payroll::where('is_review', 1)
                ->where('periode', $periode)
                ->get();
        }

        $getNotify = $this->getNotify();

        return view('pages.pegawai.gaji-pegawai.cetak-payroll', compact('employees', 'bank', 'jobLevel', 'organizations', 'jobPosition', 'locations', 'payrolls', 'getNotify'));
    }

    public function payrollPrint(Request $request)
    {
        $payrolls = []; // Inisialisasi array kosong untuk payrolls

        // Check if filter period is applied
        if ($request->filled('periode')) {
            // Jika filter periode sudah diaplikasikan, ambil data payroll sesuai periode
            $periode = $request->periode;

            // Ambil employee_id dari request
            $employeeIds = $request->employee_id;

            // Jika employee_id tidak kosong, ambil data payroll sesuai dengan employee_id yang diberikan
            if (!empty($employeeIds)) {
                $payrolls = Payroll::where('is_review', 1)
                    ->where('periode', $periode)
                    ->whereIn('employee_id', $employeeIds)
                    ->get();
            } else {
                // Jika employee_id kosong, ambil semua data payroll untuk periode yang diberikan
                $payrolls = Payroll::where('is_review', 1)
                    ->where('periode', $periode)
                    ->get();
            }
        }

        $getNotify = $this->getNotify();

        return view('pages.pegawai.gaji-pegawai.print', compact('payrolls', 'getNotify'));
    }

    public function showPayroll(Request $request)
    {
        $employeeId = auth()->user()->employee->id;
        $not = false;
        $payroll = []; // Inisialisasi array kosong untuk payroll

        // Check if filter period is applied
        if ($request->filled('periode')) {
            // Jika filter periode sudah diaplikasikan, ambil data payroll sesuai periode
            $periode = $request->periode;

            // Ambil employee_id dari request

            // Jika employee_id tidak kosong, ambil data payroll sesuai dengan employee_id yang diberikan
            $payroll = Payroll::where('is_review', 1)
                ->where('periode', $periode)
                ->where('employee_id', $employeeId)
                ->get();

            if (!$payroll) {
                $payroll = [];
                $not = true;
            }
        }

        $getNotify = $this->getNotify();

        return view('pages.pegawai.gaji-pegawai.slip-gaji', compact('payroll', 'not', 'getNotify'));
    }

    public function printShowPayroll(Request $request)
    {
        $payrolls = []; // Inisialisasi array kosong untuk payrolls
        $not = false;

        // Check if filter period is applied
        if ($request->filled('periode')) {
            // Jika filter periode sudah diaplikasikan, ambil data payroll sesuai periode
            $periode = $request->periode;

            // Jika employee_id tidak kosong, ambil data payroll sesuai dengan employee_id yang diberikan
            $payrolls = Payroll::where('is_review', 1)
                ->where('periode', $periode)
                ->where('employee_id', auth()->user()->employee->id)
                ->get();
            // dd($payrolls);

            if (!$payrolls) {
                $payrolls = [];
                $not = true;
            }
        }

        $pdf = Pdf::loadView('pages.pegawai.gaji-pegawai.print', compact('payrolls'));
        $nama = auth()->user()->employee->fullname . " " . $periode . ".pdf";
        $namaFile = str_replace(' ', '_', $nama);
        return $pdf->download($namaFile);
        // return view('pages.pegawai.gaji-pegawai.print', compact('payrolls'));
    }


    protected function getNotify()
    {
        $day_off_notify = DayOffRequest::where('approved_line_child', auth()->user()->employee->id)->orWhere('approved_line_parent', auth()->user()->employee->id)->latest()->get();
        $attendance_notify = AttendanceRequest::where('approved_line_child', auth()->user()->employee->id)->orWhere('approved_line_parent', auth()->user()->employee->id)->get();
        $day_off_count_child = DayOffRequest::where('approved_line_child', auth()->user()->employee->id)->where('is_approved', 'Pending')->count();
        $day_off_count_parent = DayOffRequest::where('approved_line_parent', auth()->user()->employee->id)->where('is_approved', 'Verifikasi')->count();
        $attendance_count_child = AttendanceRequest::where('approved_line_child', auth()->user()->employee->id)->where('is_approved', 'Pending')->count();
        $attendance_count_parent = AttendanceRequest::where('approved_line_parent', auth()->user()->employee->id)->where('is_approved', 'Verifikasi')->count();

        return [
            'day_off_notify' => $day_off_notify,
            'attendance_notify' => $attendance_notify,
            'day_off_count_child' => $day_off_count_child,
            'day_off_count_parent' => $day_off_count_parent,
            'attendance_count_parent' => $attendance_count_parent,
            'attendance_count_child' => $attendance_count_child,
        ];
    }
}
