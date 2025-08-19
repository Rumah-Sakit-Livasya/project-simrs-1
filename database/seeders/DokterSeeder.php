<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DokterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // 1. Definisikan data unik untuk setiap karyawan
        // Kita hanya perlu menulis kolom yang memiliki nilai spesifik.
        $employeesData = [
            ['identity_number' => null, 'fullname' => '-', 'residental_address' => '-'],
            ['identity_number' => '3210120504740020', 'fullname' => 'Bambang', 'residental_address' => '-'],
            ['identity_number' => '3278033101910003', 'fullname' => 'dr. Agri Mohammad Iqbal, Sp.KJ', 'residental_address' => 'Kesambi Cirebon'],
            ['identity_number' => '321', 'fullname' => 'dr. Alvita Vania Aryaputri', 'residental_address' => 'Majalengka'],
            ['identity_number' => '3526010809820005', 'fullname' => 'dr. Antonius Christy Djoko Atmodjo, Sp.Pd', 'residental_address' => 'JL. MASJID RAUDHATUL HIDAYAH'],
            ['identity_number' => '1604171008940002', 'fullname' => 'dr. Ardiansyah', 'residental_address' => 'Kampung Jambak Jorong Bunga Tanjung'],
            ['identity_number' => '3273284908960001', 'fullname' => 'dr. Arida Siti Agustin Izul Fatah', 'residental_address' => 'Bumi Panyileukan F2 No.2'],
            ['identity_number' => '3273031406900004', 'fullname' => 'dr. Dicky Andrie Cahyadi, Sp.An', 'residental_address' => 'Aspol Sukamiskin BB. 8 RT 003 RW 007 Desa/Kel. Sukamiskin Kecamatan Arcamanik Kota Bandung'],
            ['identity_number' => '3211181411780011', 'fullname' => 'dr. Dillar Gunalar Sp.Pk', 'residental_address' => 'Majalengka'],
            ['identity_number' => '1671123110890004', 'fullname' => 'dr. Dindadikusuma Sp.OG', 'residental_address' => 'Perum Bukit Sejahtera Blok BM No. 05'],
            ['identity_number' => '3210130809960000', 'fullname' => 'dr. Gavin Sava Livasya Sudjono', 'residental_address' => 'dawuan'],
            ['identity_number' => '3209292703960007', 'fullname' => 'drg. Viki Dwi Prananda', 'residental_address' => 'Jl. Waduk Kirota No.12, Desa Kalideres'],
            ['identity_number' => '3212202004920002', 'fullname' => 'dr. Harry Susanto', 'residental_address' => 'gang 5 utara'],
            ['identity_number' => '3210131104660041', 'fullname' => 'dr. H. Iing Syapei Sudjono Sp.OG', 'residental_address' => 'jalan raya Timur III no. 875 Dawuan'],
            ['identity_number' => '3578261506670003', 'fullname' => 'dr. H. Mohammad Taufiq Syafiie Sp.OG', 'residental_address' => 'Surabaya'],
            ['identity_number' => '3210110305710040', 'fullname' => 'dr. Iman Muhamad Yusup Mansur Sp.An', 'residental_address' => 'Blok Cikonde'],
            ['identity_number' => '3173022408931001', 'fullname' => 'dr. Jansen Budiono Sp.P.D.', 'residental_address' => 'Jl. Pulo Macam VIII/9 RT/RW 005/005 Kelurahan Tomang Kecamatan Grogol Petamburan Jakarta Barat'],
            ['identity_number' => '321', 'fullname' => 'dr. Lela Nurlela ', 'residental_address' => 'Dusun Sinduastra, Desa Pinangraja '],
            ['identity_number' => '3210072108490021', 'fullname' => 'dr. M. Nuruddin Zainuddin Sp. THT-KL', 'residental_address' => 'Jl. Siti Amilah No. 91'],
            ['identity_number' => '3209151007970009', 'fullname' => 'dr. Mohammad Yudhistira Surya N', 'residental_address' => 'Jl. Anggrek Raya No. 87 GSI RT/RW 002/005 Kelurahan Tukmudal, Kecamatan Sumber, Kabupaten Cirebon'],
            ['identity_number' => '3215102106950001', 'fullname' => 'dr. Muhammad Fatih Khoer', 'residental_address' => 'Desa Rangdu Mulya, Kec. Pedes Kab. Karawang'],
            ['identity_number' => '3173011911930007', 'fullname' => 'dr. Mukti Wisendha', 'residental_address' => '-'],
            ['identity_number' => '3210075302820041', 'fullname' => 'dr. Ratih Eka Pujasari Sp.A', 'residental_address' => '-'],
            ['identity_number' => '3212130105870003', 'fullname' => 'dr. Rizki Baihaqi SPB Mked Klin.', 'residental_address' => 'Perum Puri Cendrawasih A-2 Plemburan'],
            ['identity_number' => '3210075810850001', 'fullname' => 'dr. Tania Libristina Ambunsuri Afdi, Sp.P.', 'residental_address' => 'Jl. Pemuda Komplek Kamulyan Gang Ahim Afandi No. 5 RT/RW 003/011'],
            ['identity_number' => '3210074411820041', 'fullname' => 'dr. Tina Restu Awaliyah Sp.A.', 'residental_address' => '-'],
            ['identity_number' => '16171141307830006', 'fullname' => 'dr. Toripin Sp. Rad', 'residental_address' => '-'],
            ['identity_number' => '1371020112910004', 'fullname' => 'dr Willy Valerian, Sp.JP', 'residental_address' => 'Jl. Silungkang No. 6 A '],
            ['identity_number' => '3275051203890014', 'fullname' => 'dr. Zikry Aulia Hidayat Sp.PD', 'residental_address' => 'Jl. Pertanian No. 178'],
            ['identity_number' => '3210122311640000', 'fullname' => 'H. Wawan An', 'residental_address' => '-'],
            ['identity_number' => null, 'fullname' => 'THERAPIST IGD', 'residental_address' => '-'],
            ['identity_number' => null, 'fullname' => 'Wawan.An', 'residental_address' => '-'],
        ];

        // 2. Siapkan array untuk menampung data yang akan di-insert
        $dataToInsert = [];
        $now = Carbon::now();

        // 3. Looping melalui data unik dan gabungkan dengan data default
        foreach ($employeesData as $employee) {
            $defaultData = [
                'company_id' => null,
                'organization_id' => null,
                'job_position_id' => null,
                'job_level_id' => null,
                'approval_line' => null,
                'approval_line_parent' => null,
                'employee_code' => null,
                'title' => null,
                'degree' => null,
                'email' => null,
                'mobile_phone' => null,
                'place_of_birth' => null,
                'birthdate' => null,
                'gender' => null,
                'marital_status' => null,
                'blood_type' => null,
                'religion' => null,
                'last_education' => null,
                'identity_type' => 'KTP',
                'identity_expire_date' => null,
                'postal_code' => null,
                'citizen_id_address' => null,
                'barcode' => null,
                'employment_status' => null,
                'join_date' => null,
                'end_status_date' => null,
                'resign_date' => null,
                'basic_salary' => null,
                'salary_type' => null,
                'payment_schedule' => null,
                'protate_setting' => null,
                'allowed_for_overtime' => false,
                'npwp' => null,
                'ptkp_status' => null,
                'tax_methode' => null,
                'tax_salary' => null,
                'taxable_date' => null,
                'employment_tax_status' => null,
                'beginning_netto' => null,
                'pph21_paid' => null,
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
                'foto' => null,
                'is_active' => true,
                'ttd' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            // Gabungkan data default dengan data unik.
            // Nilai dari $employee akan menimpa nilai dari $defaultData jika kuncinya sama.
            $dataToInsert[] = array_merge($defaultData, $employee);
        }

        // 4. Insert semua data ke database dalam satu query
        // Ini jauh lebih efisien daripada melakukan insert di dalam loop.
        DB::table('employees')->insert($dataToInsert);
    }
}
