<?php

namespace App\Imports;

use App\Models\Bank;
use App\Models\BankEmployee;
use App\Models\Employee;
use App\Models\JobLevel;
use App\Models\JobPosition;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Validators\ValidationException;

class EmployeeImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {

        foreach ($rows as $row) {

            $organization = Organization::where('name', $row['organization_id'])->first();
            $job_level = JobLevel::where('name', $row['job_level_id'])->first();
            $job_position = JobPosition::where('name', $row['job_position_id'])->first();
            $employee = Employee::where('email', $row['email'])->first();

            if ($employee === null) {
                if ($organization !== null && $job_level !== null && $job_position !== null) {
                    $employee = Employee::create([
                        'id' => $row["id"],
                        'company_id' => $row["company"],
                        'fullname' => $row["fullname"],
                        'email' => $row["email"],
                        'mobile_phone' => $row["mobile_phone"],
                        'place_of_birth' => $row["place_of_birth"],
                        'birthdate' => $row["birthdate"],
                        'gender' => $row["gender"],
                        'marital_status' => $row["marital_status"],
                        'blood_type' => $row['blood_type'],
                        'religion' => $row['religion'],
                        'identity_type' => $row['identity_type'],
                        'identity_number' => $row['identity_number'],
                        'identity_expire_date' => $row['identity_expire_date'],
                        'postal_code' => $row['postal_code'],
                        'citizen_id_address' => $row['citizen_id_address'],
                        'residental_address' => $row['residental_address'],
                        'employee_code' => $row['employee_code'],
                        'employment_status' => $row['employment_status'],
                        'join_date' => $row['join_date'],
                        'end_status_date' => $row['end_status_date'],
                        'resign_date' => $row['resign_date'],
                        'organization_id' => $organization->id,
                        'job_position_id' => $job_position->id,
                        'job_level_id' => $job_level->id,
                        // 'approval_line' => $row['approval_line'],
                        // 'approval_line_parent' => $row['approval_line_parent'],
                        'basic_salary' => $row['basic_salary'],
                        'payment_schedule' => $row['payment_schedule'],
                        'protate_setting' => $row['protate_setting'],
                        'allowed_for_overtime' => $row['allowed_for_overtime'],
                        'npwp' => $row['npwp'],
                        'ptkp_status' => $row['ptkp_status'],
                        'tax_methode' => $row['tax_methode'],
                        'tax_salary' => $row['tax_salary'],
                        'taxable_date' => $row['taxable_date'],
                        'employment_tax_status' => $row['employment_tax_status'],
                        'beginning_netto' => $row['beginning_netto'],
                        'pph21_paid' => $row['pph21_paid'],
                        'bpjs_ker_number' => $row['bpjs_ker_number'],
                        'npp_ker_bpjs' => $row['npp_ker_bpjs'],
                        'bpjs_ker_date' => $row['bpjs_ker_date'],
                        'bpjs_kes_number' => $row['bpjs_kes_number'],
                        'bpjs_kes_family' => $row['bpjs_kes_family'],
                        'bpjs_kes_date' => $row['bpjs_kes_date'],
                        'bpjs_kes_cost' => $row['bpjs_kes_cost'],
                        'jht_cost' => $row['jht_cost'],
                        'jaminan_pensiun_cost' => $row['jaminan_pensiun_cost'],
                        'jaminan_pensiun_date' => $row['jaminan_pensiun_date'],
                        'sip' => $row['sip'],
                        'expire_sip' => $row['expire_sip']
                    ]);

                    $bank_id = Bank::where('name', $row['bank_name'])->value('id');
                    $bank_employee = BankEmployee::create([
                        'employee_id' => $employee->id,
                        'bank_id' => $bank_id,
                        'account_number' => $row['account_number'],
                        'account_holder_name' => $row['account_holder_name'],
                        'status' => 1,
                    ]);
                    $user = User::create([
                        'employee_id' => $employee->id,
                        'name' => $row['fullname'],
                        'email' => $row['email'],
                    ]);
                    if ($organization->name == "Unit SDM") {
                        $user->assignRole('hr');
                    } else if ($organization->name == "Direksi") {
                        $user->assignRole('manager');
                    } else {
                        $user->assignRole('employee');
                    }
                } else {
                    throw new \Exception("Organization, job level, atau job position ada kesalahan pada " . $row['fullname']);
                }
            } else {
                continue;
            }
        }

        foreach ($rows as $row) {
            $child = Employee::where('fullname', $row['approval_line'])->value('id');
            $parent = Employee::where('fullname', $row['approval_line_parent'])->value('id');
            $pegawai = Employee::where('email', $row['email'])->first();
            if ($pegawai !== null) {
                $pegawai->update([
                    'approval_line' => $child,
                    'approval_line_parent' => $parent,
                ]);
            } else {
                throw new \Exception("Pegawai " . $row['fullname'] . " tidak ditemukan!");
            }
        }
    }
}
