<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Company;
use App\Models\Employee;
use App\Models\JobLevel;
use App\Models\JobPosition;
use App\Models\Organization;
use App\Models\SIMRS\Departement;

class EmployeePageController extends Controller
{
    /**
     * Menampilkan halaman edit untuk pegawai.
     *
     * @param \App\Models\Employee $employee
     * @return \Illuminate\View\View
     */
    public function edit(Employee $employee)
    {
        // Ambil semua data yang diperlukan untuk form dropdown
        $organizations = Organization::orderBy('name')->get();
        $jobPositions = JobPosition::orderBy('name')->get();
        $jobLevels = JobLevel::orderBy('name')->get();
        $companies = Company::orderBy('name')->get();
        $allEmployees = Employee::where('is_active', 1)->orderBy('fullname')->get();
        $banks = Bank::orderBy('name')->get();
        $departments = Departement::orderBy('name')->get();

        // Load relasi yang dibutuhkan agar data terisi di form
        $employee->load('bank_employee', 'doctor');

        return view('pages.pegawai.daftar-pegawai.edit', compact(
            'employee',
            'organizations',
            'jobPositions',
            'jobLevels',
            'companies',
            'allEmployees',
            'banks',
            'departments'
        ));
    }
}
