<?php

namespace Database\Seeders;

use App\Models\Keuangan\RncCenter;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RncCenterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nonaktifkan pengecekan foreign key untuk sementara agar proses lebih cepat
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Kosongkan tabel sebelum mengisi data baru (opsional, tapi disarankan)
        RncCenter::truncate();

        // Data yang akan dimasukkan ke dalam tabel
        $centers = [
            ['kode_rnc' => '4001', 'nama_rnc' => 'PEMULASARAN JENAZAH'],
            ['kode_rnc' => '0000', 'nama_rnc' => 'UNCATEGORIZED'],
            ['kode_rnc' => '4010', 'nama_rnc' => 'KLINIK UMUM'],
            ['kode_rnc' => '4011', 'nama_rnc' => 'KLINIK OBGYN'],
            ['kode_rnc' => '4012', 'nama_rnc' => 'KLINIK ANAK'],
            ['kode_rnc' => '4013', 'nama_rnc' => 'IGD'],
            ['kode_rnc' => '4013-B', 'nama_rnc' => 'KLINIK GIGI'], // Kode diubah agar unik
            ['kode_rnc' => '4014', 'nama_rnc' => 'KLINIK REHAB MEDIK'],
            ['kode_rnc' => '4015', 'nama_rnc' => 'ODC'],
            ['kode_rnc' => '4016', 'nama_rnc' => 'HEMODIALISA'],
            ['kode_rnc' => '4017', 'nama_rnc' => 'PERINATOLOGI'],
            ['kode_rnc' => '4018', 'nama_rnc' => 'VIP I'],
            ['kode_rnc' => '4019', 'nama_rnc' => 'EKSEKUTIF'],
            ['kode_rnc' => '4020', 'nama_rnc' => 'DELUXE'],
            ['kode_rnc' => '4021', 'nama_rnc' => 'SUPERIOR'],
            ['kode_rnc' => '4022', 'nama_rnc' => 'ISOLASI ANAK'],
            ['kode_rnc' => '4023', 'nama_rnc' => 'PERINA'],
            ['kode_rnc' => '4024', 'nama_rnc' => 'ICU'],
            ['kode_rnc' => '4025', 'nama_rnc' => 'NICU'],
            ['kode_rnc' => '4026', 'nama_rnc' => 'MEDICAL CHECK UP'],
            ['kode_rnc' => '4027', 'nama_rnc' => 'REHABILITASI MEDIK'],
            ['kode_rnc' => '4028', 'nama_rnc' => 'UNIT GIZI'],
            ['kode_rnc' => '4029', 'nama_rnc' => 'KLINIK PENYAKIT DALAM'],
            ['kode_rnc' => '4030', 'nama_rnc' => 'KLINIK SARAF'],
            ['kode_rnc' => '4031', 'nama_rnc' => 'KLINIK BEDAH ANAK'],
            ['kode_rnc' => '4032', 'nama_rnc' => 'KLINIK BEDAH'],
            ['kode_rnc' => '4033', 'nama_rnc' => 'IBS'],
            ['kode_rnc' => '4034', 'nama_rnc' => 'KLINIK AKUPUNKTUR'],
            ['kode_rnc' => '4035', 'nama_rnc' => 'KLINIK APS'],
            ['kode_rnc' => '4036', 'nama_rnc' => 'KLINIK KULIT KELAMIN'],
            ['kode_rnc' => '4037', 'nama_rnc' => 'KLINIK JANTUNG'],
            ['kode_rnc' => '4038', 'nama_rnc' => 'KLINIK KEJIWAAN'],
            ['kode_rnc' => '4039', 'nama_rnc' => 'VAKSIN'],
            ['kode_rnc' => '4040', 'nama_rnc' => 'KLINIK KIA'],
            ['kode_rnc' => '4041', 'nama_rnc' => 'SPA BAYI'],
            ['kode_rnc' => '4042', 'nama_rnc' => 'SPA IBU'],
            ['kode_rnc' => '4043', 'nama_rnc' => 'VIP III'],
            ['kode_rnc' => '4044', 'nama_rnc' => 'TINDAKAN PERAWATAN'],
            ['kode_rnc' => '4045', 'nama_rnc' => 'KLINIK GIGI ANAK'],
            ['kode_rnc' => '4047', 'nama_rnc' => 'FACILITY'],
            ['kode_rnc' => '4048', 'nama_rnc' => 'APOTIK'],
            ['kode_rnc' => '4049', 'nama_rnc' => 'ANASTESI'],
            ['kode_rnc' => '4050', 'nama_rnc' => 'RAWAT INAP ANAK'],
            ['kode_rnc' => '4051', 'nama_rnc' => 'VIP II'],
            ['kode_rnc' => '4051-B', 'nama_rnc' => 'KESLING'], // Kode diubah agar unik
            ['kode_rnc' => '4052', 'nama_rnc' => 'DAPUR DAN GIZI'],
            ['kode_rnc' => '4053', 'nama_rnc' => 'DEPO OK'],
            ['kode_rnc' => '4054', 'nama_rnc' => 'DEPO VK'],
            ['kode_rnc' => '4055', 'nama_rnc' => 'DEPO KTD'],
            ['kode_rnc' => '4056', 'nama_rnc' => 'AMBULANCE'],
        ];

        // Loop melalui array dan buat record baru untuk setiap item
        foreach ($centers as $center) {
            RncCenter::create([
                'kode_rnc' => $center['kode_rnc'],
                'nama_rnc' => $center['nama_rnc'],
                'is_active' => true, // Semua data diset aktif secara default
            ]);
        }

        // Aktifkan kembali pengecekan foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
