<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\SIMRS\Penjamin;
use App\Models\SIMRS\Patient;
use App\Models\SIMRS\Registration;
use App\Models\Keuangan\KonfirmasiAsuransi;

class AccountReceivableSeeder extends Seeder
{
    public function run(): void
    {
        // Buat penjamin dummy
        $penjamin = Penjamin::create([
            'group_penjamin_id' => 1,
            'mulai_kerjasama' => now(),
            'tipe_perusahaan' => '-',
            'kode_perusahaan' => '-',
            'nama_perusahaan' => 'Asuransi Dummy',
            'diskon' => '0',
            'termasuk_penjamin' => 1,
            'status' => 1,
            'jenis_kerjasama' => '-',
            'jenis_kontrak' => '-',
        ]);

        // Buat pasien dummy
        $pasien = Patient::create([
            'medical_record_number' => 'RM163456',
            'name' => 'Pasien Dummy',
            'place' => 'Majalengka',
            'date_of_birth' => '1995-01-01',
            'title' => 'Tn.',
            'gender' => 'Laki-laki',
            'religion' => 'Islam',
            'language' => 'Indonesia',
            'address' => 'Jl. Contoh No.1',
            'ward' => 'Ward A',
            'subdistrict' => 'Cigasong',
            'regency' => 'Majalengka',
            'mobile_phone_number' => '08123456789',
            'last_education' => 'S1',
            'ethnic' => 'Sunda',
            'job' => 'Karyawan'
        ]);

        // Buat registrasi dummy
        $registrasi = Registration::create([
            'date' => Carbon::now()->toDateString(),
            'patient_id' => $pasien->id,
            'user_id' => 1,
            'employee_id' => 1,
            'penjamin_id' => $penjamin->id,
            'doctor_id' => 1,
            'departement_id' => 1,
            'registration_type' => 'rawat-jalan',
            'registration_date' => Carbon::now()->toDateTimeString(),
            'registration_number' => 'REG20250509',
            'diagnosa_awal' => 'Batuk dan pilek',
            'kartu_pasien' => 0,
            'rujukan' => 'Mandiri'
        ]);

        // Buat konfirmasi asuransi dummy
        KonfirmasiAsuransi::create([
            'penjamin_id' => $penjamin->id,
            'registration_id' => $registrasi->id,
            'invoice' => 'INV-20250509-001',
            'jumlah' => 250000,
            'diskon' => 15000,
            'tanggal' => Carbon::now()->toDateString(),
            'jatuh_tempo' => Carbon::now()->addDays(14)->toDateString(),
            'keterangan' => 'Tagihan dummy untuk testing AR',
        ]);
    }
}
