<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = [
            [
                'fullname' => "Dimas Candra Pebriyanto",
                'company_id' => 1,
                'email' => "dimas@livasya.com",
                'mobile_phone' => "6283865284307",
                'place_of_birth' => 'Majalengka',
                'birthdate' => "2003-02-18",
                'gender' => 'Laki-laki',
                'marital_status' => 'Lajang',
                'blood_type' => 'O',
                'religion' => "Islam",
                'identity_type' => 'KTP',
                'identity_number' => '3210030050012334',
                'identity_expire_date' => null,
                'postal_code' => '45458',
                'citizen_id_address' => 'Majalengka',
                'residental_address' => 'Jatitujuh',
                'barcode' => null,
                'employment_status' => 'Kontrak',
                'join_date' => '2022-09-27',
                'end_status_date' => '2024-12-30',
                'resign_date' => null,
                'organization_id' => 15,
                'job_position_id' => 25,
                'job_level_id' => 5,
                'shift_id' => null,
                'approval_line' => null,
                'approval_line_parent' => null,
                'basic_salary' => '5000000',
                'salary_type' => 'bulanan',
                'payment_schedule' => null,
                'protate_setting' => null,
                'allowed_for_overtime' => 0,
                'npwp' => null,
                'ptkp_status' => null,
                'tax_methode' => null,
                'tax_salary' => null,
                'taxable_date' => null,
                'employment_tax_status' => null,
                'beginning_netto' => 100000,
                'pph21_paid' => 100000,
                'bpjs_ker_number' => null,
                'npp_ker_bpjs' => null,
                'bpjs_ker_date' => null,
                'bpjs_kes_number' => null,
                'bpjs_kes_family' => null,
                'bpjs_kes_date' => null,
                'bpjs_kes_cost' => null,
                'jht_cost' => null,
                'jaminan_pensiun_cost' => null,
                'jaminan_pensiun_date' => null,
                'sip' => null,
                'expire_sip' => null,
            ],
            [
                'fullname' => "Fizar Rama Waluyo, S. Kom.",
                'company_id' => 1,
                'email' => "fizar@livasya.com",
                'mobile_phone' => "6283865284307",
                'place_of_birth' => 'Majalengka',
                'birthdate' => "2003-02-18",
                'gender' => 'Laki-laki',
                'marital_status' => 'Lajang',
                'blood_type' => 'O',
                'religion' => "Islam",
                'identity_type' => 'KTP',
                'identity_number' => '3210030050012334',
                'identity_expire_date' => null,
                'postal_code' => '45458',
                'citizen_id_address' => 'Majalengka',
                'residental_address' => 'Jatitujuh',
                'barcode' => null,
                'employment_status' => 'Kontrak',
                'join_date' => '2022-09-27',
                'end_status_date' => '2024-12-30',
                'resign_date' => null,
                'organization_id' => 15,
                'job_position_id' => 25,
                'job_level_id' => 5,
                'shift_id' => null,
                'approval_line' => null,
                'approval_line_parent' => null,
                'basic_salary' => '5000000',
                'salary_type' => 'bulanan',
                'payment_schedule' => null,
                'protate_setting' => null,
                'allowed_for_overtime' => 0,
                'npwp' => null,
                'ptkp_status' => null,
                'tax_methode' => null,
                'tax_salary' => null,
                'taxable_date' => null,
                'employment_tax_status' => null,
                'beginning_netto' => 100000,
                'pph21_paid' => 100000,
                'bpjs_ker_number' => null,
                'npp_ker_bpjs' => null,
                'bpjs_ker_date' => null,
                'bpjs_kes_number' => null,
                'bpjs_kes_family' => null,
                'bpjs_kes_date' => null,
                'bpjs_kes_cost' => null,
                'jht_cost' => null,
                'jaminan_pensiun_cost' => null,
                'jaminan_pensiun_date' => null,
                'sip' => null,
                'expire_sip' => null,
            ]
        ];

        foreach ($employees as $employee) {
            Employee::create($employee);
        }
    }
}
