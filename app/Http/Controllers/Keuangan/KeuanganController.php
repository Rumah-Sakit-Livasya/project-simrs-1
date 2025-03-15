<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Keuangan\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KeuanganController extends Controller
{
    public function dashboard()
    {
        // PEMASUKAN HARI INI
        $dataPemasukanHariIni = Transaksi::where('type_id', 1)->whereDate('tanggal', Carbon::today())->get();
        $tampilPemasukanHariIni = 0;
        foreach ($dataPemasukanHariIni as $pemasukanHariIni) {
            $tampilPemasukanHariIni += $pemasukanHariIni->nominal;
        }

        // PEMASUKAN HARI INI
        $dataPengeluaranHariIni = Transaksi::where('type_id', 2)->whereDate('tanggal', Carbon::today())->get();
        $tampilPengeluaranHariIni = 0;
        foreach ($dataPengeluaranHariIni as $pengeluaranHariIni) {
            $tampilPengeluaranHariIni += $pengeluaranHariIni->nominal;
        }

        // PEMASUKAN BULAN INI
        $dataPemasukanBulanIni = Transaksi::where('type_id', 1)
            ->whereYear('tanggal', Carbon::now()->year)
            ->whereMonth('tanggal', Carbon::now()->month)
            ->get();

        $tampilPemasukanBulanIni = 0;

        foreach ($dataPemasukanBulanIni as $pemasukanBulanIni) {
            $tampilPemasukanBulanIni += $pemasukanBulanIni->nominal;
        }

        // PENGELUARAN BULAN INI
        $dataPengeluaranBulanIni = Transaksi::where('type_id', 2)
            ->whereYear('tanggal', Carbon::now()->year)
            ->whereMonth('tanggal', Carbon::now()->month)
            ->get();

        $tampilPengeluaranBulanIni = 0;

        foreach ($dataPengeluaranBulanIni as $pengeluaranBulanIni) {
            $tampilPengeluaranBulanIni += $pengeluaranBulanIni->nominal;
        }

        // PEMASUKAN TAHUN INI
        $dataPemasukanTahunIni = Transaksi::where('type_id', 1)
            ->whereYear('tanggal', Carbon::now()->year)
            ->get();

        $tampilPemasukanTahunIni = 0;

        foreach ($dataPemasukanTahunIni as $pemasukanTahunIni) {
            $tampilPemasukanTahunIni += $pemasukanTahunIni->nominal;
        }

        // PENGELUARAN TAHUN INI
        $dataPengeluaranTahunIni = Transaksi::where('type_id', 2)
            ->whereYear('tanggal', Carbon::now()->year)
            ->get();

        $tampilPengeluaranTahunIni = 0;

        foreach ($dataPengeluaranTahunIni as $pengeluaranTahunIni) {
            $tampilPengeluaranTahunIni += $pengeluaranTahunIni->nominal;
        }

        // SELURUH PEMASUKAN 
        $dataSeluruhPemasukan = Transaksi::where('type_id', 1)->get();

        $tampilSeluruhPemasukan = 0;

        foreach ($dataSeluruhPemasukan as $SeluruhPemasukan) {
            $tampilSeluruhPemasukan += $SeluruhPemasukan->nominal;
        }

        // SELURUH PENGELUARAN 
        $dataSeluruhPengeluaran = Transaksi::where('type_id', 2)->get();

        $tampilSeluruhPengeluaran = 0;

        foreach ($dataSeluruhPengeluaran as $SeluruhPengeluaran) {
            $tampilSeluruhPengeluaran += $SeluruhPengeluaran->nominal;
        }

        return view('app-type.keuangan.dashboard', [
            'tampilSeluruhPemasukan' => $tampilSeluruhPemasukan,
            'tampilSeluruhPengeluaran' => $tampilSeluruhPengeluaran,
            'tampilPemasukanTahunIni' => $tampilPemasukanTahunIni,
            'tampilPengeluaranTahunIni' => $tampilPengeluaranTahunIni,
            'tampilPemasukanBulanIni' => $tampilPemasukanBulanIni,
            'tampilPengeluaranBulanIni' => $tampilPengeluaranBulanIni,
            'tampilPemasukanHariIni' => $tampilPemasukanHariIni,
            'tampilPengeluaranHariIni' => $tampilPengeluaranHariIni,
        ]);
    }
}
