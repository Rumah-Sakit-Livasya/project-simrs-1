<?php

use Illuminate\Support\Facades\Request;
use Carbon\Carbon;

function displayAge($birthdate)
{
    $now = Carbon::now();
    $age = Carbon::parse($birthdate)->diff($now);

    $years = $age->y;
    $months = $age->m;
    $days = $age->d;

    $result = '';

    if ($years > 0) {
        $result .= $years . 'thn';
    }

    if ($months > 0) {
        if ($result !== '') {
            $result .= ', ';
        }
        $result .= $months . 'bln';
    }

    if ($days > 0) {
        if ($result !== '') {
            $result .= ', ';
        }
        $result .= $days . 'hr';
    }

    return $result;
}

function getBreadcrumbs($folder)
{
    $breadcrumbs = [];

    while ($folder) {
        array_unshift($breadcrumbs, $folder); // Tambah folder di awal array
        $folder = $folder->parent; // Ambil parent folder
    }

    return $breadcrumbs;
}

function toHijriah($tanggal)
{
    $array_month = array("Muharram", "Safar", "Rabiul Awwal", "Rabiul Akhir", "Jumadil Awwal", "Jumadil Akhir", "Rajab", "Sya'ban", "Ramadhan", "Syawwal", "Zulqaidah", "Zulhijjah");

    $date = intval(substr($tanggal, 8, 2));
    $month = intval(substr($tanggal, 5, 2));
    $year = intval(substr($tanggal, 0, 4));

    if (($year > 1582) || (($year == "1582") && ($month > 10)) || (($year == "1582") && ($month == "10") && ($date > 14))) {
        $jd = intval((1461 * ($year + 4800 + intval(($month - 14) / 12))) / 4) +
            intval((367 * ($month - 2 - 12 * (intval(($month - 14) / 12)))) / 12) -
            intval((3 * (intval(($year + 4900 + intval(($month - 14) / 12)) / 100))) / 4) +
            $date - 32075;
    } else {
        $jd = 367 * $year - intval((7 * ($year + 5001 + intval(($month - 9) / 7))) / 4) +
            intval((275 * $month) / 9) + $date + 1729777;
    }

    $wd = $jd % 7;
    $l  = $jd - 1948440 + 10632;
    $n  = intval(($l - 1) / 10631);
    $l  = $l - 10631 * $n + 354;
    $z  = (intval((10985 - $l) / 5316)) * (intval((50 * $l) / 17719)) + (intval($l / 5670)) * (intval((43 * $l) / 15238));
    $l  = $l - (intval((30 - $z) / 15)) * (intval((17719 * $z) / 50)) - (intval($z / 16)) * (intval((15238 * $z) / 43)) + 29;
    $m  = intval((24 * $l) / 709);
    $d  = $l - intval((709 * $m) / 24);
    $y  = 30 * $n + $z - 30;
    $g  = $m - 1;

    $hijriah = "$d $array_month[$g] $y H";

    return $hijriah;
}

if (!function_exists('set_active')) {
    function set_active($paths, $class = 'active')
    {
        foreach ((array) $paths as $path) {
            if (Request::is(trim($path, '/'))) {
                return $class;
            }
        }
        return '';
    }
}

if (!function_exists('set_active_mainmenu')) {
    function set_active_mainmenu($paths, $class = 'active open')
    {
        foreach ((array) $paths as $path) {
            $trimmedPath = trim($path, '/');
            // Cek apakah path termasuk karakter *
            if (strpos($trimmedPath, '*') !== false) {
                $pattern = str_replace('*', '.*', $trimmedPath);
                if (preg_match("#^{$pattern}$#", request()->path())) {
                    return $class;
                }
            } else {
                if (Request::is($trimmedPath)) {
                    return $class;
                }
            }
        }
        return '';
    }
}

if (!function_exists('formatNomorIndo')) {
    function formatNomorIndo($nomor)
    {
        // Cek apakah nomor diawali dengan '62'
        if (substr($nomor, 0, 2) === '62') {
            // Ganti '62' dengan '0'
            $nomor = '0' . substr($nomor, 2);
        }

        return $nomor;
    }
}

if (!function_exists('tgl')) {
    function tgl($tanggal)
    {
        $bulan = array(
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );

        if ($tanggal !== null) {
            $pecahkan = explode('-', $tanggal);
            $format = $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
        } else {
            $format = "*belum disetting";
        }

        return $format;
    }
}

if (!function_exists('tgl_waktu')) {
    function tgl_waktu($tanggal)
    {
        // List of months in Indonesian
        $months = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];

        // Convert the datetime string to a timestamp
        $timestamp = strtotime($tanggal);

        // Extract the day, month, year, and time
        $day = date('d', $timestamp);
        $month = date('m', $timestamp);
        $year = date('Y', $timestamp);
        $time = date('H:i', $timestamp);

        // Return the formatted date string
        return $day . ' ' . $months[$month] . ' ' . $year . ' ' . $time;
    }
}

function greetings()
{
    date_default_timezone_set('Asia/Jakarta'); // Sesuaikan dengan zona waktu Anda

    $jam = date('H');
    $ucapan = '';

    if ($jam >= 5 && $jam < 12) {
        $ucapan = 'Selamat Pagi';
    } elseif ($jam >= 12 && $jam < 15) {
        $ucapan = 'Selamat Siang';
    } elseif ($jam >= 15 && $jam < 18) {
        $ucapan = 'Selamat Sore';
    } else {
        $ucapan = 'Selamat Malam';
    }

    return $ucapan;
}

function konversiTanggal($tanggal)
{
    // Array nama hari dalam Bahasa Indonesia
    $namaHari = array(
        'Minggu',
        'Senin',
        'Selasa',
        'Rabu',
        'Kamis',
        'Jumat',
        'Sabtu'
    );
    // Array nama bulan dalam Bahasa Indonesia
    $namaBulan = array(
        1 => 'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );

    // Mendapatkan nama hari berdasarkan nilai tanggal
    $hari = $namaHari[date('w', strtotime($tanggal))];
    // Mendapatkan tanggal
    $tanggal = date('j', strtotime($tanggal));
    // Mendapatkan nama bulan berdasarkan nilai tanggal
    $bulan = $namaBulan[date('n', strtotime($tanggal))];

    // Mengembalikan hasil dalam format yang diinginkan
    return $hari . ', ' . $tanggal . ' ' . $bulan;
}

//hitung radius jarak
function haversine($lat1, $lon1, $lat2, $lon2)
{
    $r = 6371; // Radius bumi dalam kilometer
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $d = $r * $c; // Jarak dalam kilometer
    return $d;
}

function hitungUmur($tanggal_lahir)
{
    // Ubah string tanggal lahir menjadi objek DateTime
    $tgl_lahir = new DateTime($tanggal_lahir);
    // Dapatkan tanggal hari ini
    $today = new DateTime();
    // Hitung selisih antara tanggal lahir dan tanggal hari ini
    $umur = $tgl_lahir->diff($today);
    // Ambil bagian umur dalam tahun
    $umur_tahun = $umur->y;
    // Kembalikan umur dalam tahun
    return $umur_tahun .  " Tahun";
}

function hitungHari($tanggal)
{
    // Ubah tanggal menjadi objek DateTime
    $tanggalAwal = new DateTime($tanggal);
    $tanggalAkhir = new DateTime(now());

    // Hitung perbedaan hari antara dua tanggal
    $selisih = $tanggalAwal->diff($tanggalAkhir);

    // Hitung jumlah tahun, bulan, dan hari
    $tahun = $selisih->y;
    $bulan = $selisih->m;
    $hari = $selisih->d;

    // Buat string hasil
    $hasil = '';
    if ($tahun > 0) {
        $hasil .= $tahun . ' Tahun ';
    }
    if ($bulan > 0) {
        $hasil .= $bulan . ' Bulan ';
    }
    if ($hari > 0) {
        $hasil .= $hari . ' Hari';
    }

    return $hasil;
}

function rp($amount)
{
    // Format angka menjadi mata uang Rupiah
    $formattedAmount = 'Rp ' . number_format($amount, 0, ',', '.');

    return $formattedAmount;
}

function rp2($amount)
{
    // Format angka menjadi mata uang Rupiah
    $formattedAmount = number_format($amount, 0, ',', '.');

    return $formattedAmount;
}

function convertPeriodePayroll($period)
{
    // Pisahkan periode menjadi dua bagian, yaitu bulan pertama dan bulan kedua
    $parts = explode(' - ', $period);

    // Ambil nama bulan dari bagian pertama dan konversi ke bahasa Indonesia
    $startMonth = date('F Y', strtotime($parts[0]));
    $startMonthIndonesian = date('F Y', strtotime($startMonth));

    // Ambil nama bulan dari bagian kedua dan konversi ke bahasa Indonesia
    $endMonth = date('F Y', strtotime($parts[1]));
    $endMonthIndonesian = date('F Y', strtotime($endMonth));

    // Gabungkan kembali periode dengan bulan dalam bahasa Indonesia
    return $startMonthIndonesian . ' - ' . $endMonthIndonesian;
}

// Check if function is already declared
if (!function_exists('isActiveMenu')) {
    function isActiveMenu($menu)
    {
        $urls = collect([$menu->url]);
        if ($menu->children->isNotEmpty()) {
            $urls = $urls->merge($menu->children->pluck('url'));
            foreach ($menu->children as $child) {
                $urls = $urls->merge(isActiveMenuUrls($child));
            }
        }
        return set_active_mainmenu($urls->toArray());
    }

    function isActiveMenuUrls($menu)
    {
        $urls = collect([$menu->url]);
        if ($menu->children->isNotEmpty()) {
            $urls = $urls->merge($menu->children->pluck('url'));
            foreach ($menu->children as $child) {
                $urls = $urls->merge(isActiveMenuUrls($child));
            }
        }
        return $urls;
    }
}

function phone($phone)
{
    // Remove any leading zero
    if (substr($phone, 0, 1) == '0') {
        $phone = '62' . substr($phone, 1);
    }
    return $phone;
}

function getIndonesianDateFormat($date)
{
    $carbonDate = Carbon::parse($date)->locale('id_ID');
    return $carbonDate->isoFormat('D MMMM YYYY');
}

function angkaKeBulan($angka)
{
    $bulan = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];

    // Periksa apakah angka valid antara 1 dan 12
    if (array_key_exists($angka, $bulan)) {
        return $bulan[$angka];
    } else {
        return "Bulan tidak valid";
    }
}

function formatTanggalDetail($tanggal)
{
    $tanggalFormat = date('d M Y', strtotime($tanggal));
    $tanggalLahir = new DateTime($tanggal);
    $sekarang = new DateTime();
    $umur = $sekarang->diff($tanggalLahir);

    $umurString = $umur->y . 'thn ' . $umur->m . 'bln ' . $umur->d . 'hr';

    return $tanggalFormat . ' (' . $umurString . ')';
}

function tglDefault($tanggal)
{
    // Ubah string ISO 8601 menjadi objek DateTime
    $date = new DateTime($tanggal);

    // Format tanggal menjadi 'd F Y' (contoh: 27 Desember 2024)
    $bulanIndonesia = [
        'January' => 'Januari',
        'February' => 'Februari',
        'March' => 'Maret',
        'April' => 'April',
        'May' => 'Mei',
        'June' => 'Juni',
        'July' => 'Juli',
        'August' => 'Agustus',
        'September' => 'September',
        'October' => 'Oktober',
        'November' => 'November',
        'December' => 'Desember',
    ];

    $tanggalFormat = $date->format('d');
    $bulan = $bulanIndonesia[$date->format('F')];
    $tahun = $date->format('Y');

    return "{$tanggalFormat} {$bulan} {$tahun}";
}

function waktuDefault($tanggalWaktu)
{
    // Ubah string ISO 8601 menjadi objek DateTime
    $date = new DateTime($tanggalWaktu);

    // Format waktu menjadi 'H:i WIB' (contoh: 15:11 WIB)
    return $date->format('H:i') . ' WIB';
}

function formatTanggalBulan($tanggal)
{
    // Ubah string ISO 8601 menjadi objek DateTime
    $date = new DateTime($tanggal);

    // Format bulan menjadi nama bulan dalam bahasa Indonesia
    $bulanIndonesia = [
        'January' => 'Januari',
        'February' => 'Februari',
        'March' => 'Maret',
        'April' => 'April',
        'May' => 'Mei',
        'June' => 'Juni',
        'July' => 'Juli',
        'August' => 'Agustus',
        'September' => 'September',
        'October' => 'Oktober',
        'November' => 'November',
        'December' => 'Desember',
    ];

    $tanggalFormat = $date->format('d');
    $bulan = $bulanIndonesia[$date->format('F')];

    return "{$tanggalFormat} {$bulan}";
}
