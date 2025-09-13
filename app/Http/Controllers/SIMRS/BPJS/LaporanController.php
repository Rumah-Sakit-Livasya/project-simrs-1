<?php

namespace App\Http\Controllers\SIMRS\BPJS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Halaman Laporan Hapus SEP
     */
    public function hapusSep()
    {
        return view('app-type.simrs.bpjs.laporan.hapus-sep');
    }

    /**
     * Halaman Laporan Akses ICare
     */
    public function aksesICare()
    {
        return view('app-type.simrs.bpjs.laporan.akses-icare');
    }

    public function printHapusSep(Request $request)
    {
        // 1. Ambil semua input dari form
        $awal_periode = $request->input('awal_periode');
        $akhir_periode = $request->input('akhir_periode');
        $tipe_rawat = $request->input('tipe_rawat', 'Semua');
        $no_rm_pasien = $request->input('no_rm_pasien');
        // ... ambil parameter lainnya jika ada

        // 2. Logika untuk mengambil data dari database atau API
        // Untuk sekarang, kita buat data kosong sebagai contoh
        $dataLaporan = []; // Harusnya berisi hasil query

        // Cek jika user meminta export XLS
        if ($request->has('export') && $request->input('export') === 'xls') {
            // Logika untuk ekspor ke Excel ada di sini
            // return Excel::download(new LaporanHapusSepExport($dataLaporan), 'laporan.xlsx');
            return "Fungsi export XLS akan berjalan di sini.";
        }

        // 3. Tampilkan view khusus untuk pop-up
        return view('app-type.simrs.bpjs.laporan.hapus-sep-print', [
            'dataLaporan' => $dataLaporan,
            'awal_periode' => $awal_periode,
            'akhir_periode' => $akhir_periode,
            'tipe_rawat' => $tipe_rawat,
            'no_rm_pasien' => $no_rm_pasien,
        ]);
    }

    /**
     * ====================================================================
     * Method BARU untuk menampilkan hasil Laporan Akses ICare di pop-up
     * ====================================================================
     */
    public function printAksesICare(Request $request)
    {
        $awal_periode = $request->input('awal_periode', date('m-Y'));
        $akhir_periode = $request->input('akhir_periode', date('m-Y'));

        // Parsing bulan dan tahun dari input
        list($bulan, $tahun) = explode('-', $awal_periode);

        // Mendapatkan nama bulan dalam bahasa Indonesia
        $nama_bulan = \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->locale('id')->monthName;

        // Mendapatkan jumlah hari dalam bulan tersebut
        $jumlah_hari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

        // Logika untuk mengambil data dari database
        // Untuk demo, kita buat mock data
        $dataLaporan = [
            ['nama_dokter' => 'dr. Dindadikusuma Sp.OG', 'total' => 0, 'harian' => array_fill(1, $jumlah_hari, 0)],
            ['nama_dokter' => 'dr. H. Iing Syapei Sudjono Sp.OG', 'total' => 5, 'harian' => array_merge(array_fill(1, 5, 0), [6 => 2, 7 => 3], array_fill(8, $jumlah_hari - 7, 0))],
            // ... data dokter lainnya
        ];

        // Cek jika user meminta export XLS
        if ($request->has('export') && $request->input('export') === 'xls') {
            // Logika untuk ekspor ke Excel
            return "Fungsi export XLS untuk Laporan ICare akan berjalan di sini.";
        }

        return view('app-type.simrs.bpjs.laporan.akses-icare-print', [
            'dataLaporan' => $dataLaporan,
            'awal_periode' => $awal_periode,
            'akhir_periode' => $akhir_periode,
            'nama_bulan' => $nama_bulan,
            'jumlah_hari' => $jumlah_hari,
        ]);
    }
}
