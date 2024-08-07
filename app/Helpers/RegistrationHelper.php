<?php
// app/Helpers/RegistrationHelper.php

use App\Models\SIMRS\Registration;
use Carbon\Carbon;

if (!function_exists('generate_registration_number')) {
    function generate_registration_number()
    {
        $date = Carbon::now();
        $year = $date->format('y');
        $month = $date->format('m');
        $day = $date->format('d');

        $count = Registration::whereDate('created_at', $date->toDateString())->count() + 1;
        $count = str_pad($count, 4, '0', STR_PAD_LEFT);

        return $year . $month . $day . $count;
    }
}

if (!function_exists('generateDoctorSequenceNumber')) {
    function generateDoctorSequenceNumber($doctorId, $date)
    {
        // Ubah format tanggal ke Carbon instance
        $date = Carbon::createFromFormat('d-m-Y', $date);

        // Hitung jumlah registrasi pada hari tersebut untuk dokter yang diberikan
        $count = Registration::where('doctor_id', $doctorId)
            ->whereDate('registration_date', $date)
            ->count();

        // return dd($date);

        // Tambahkan 1 untuk nomor urut baru
        $sequenceNumber = $count + 1;

        // Kembalikan nomor urut sebagai string dengan leading zeros jika diperlukan
        return str_pad($sequenceNumber, 2, '0', STR_PAD_LEFT);
    }
}
