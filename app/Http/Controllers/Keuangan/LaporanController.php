<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Keuangan\Category;
use App\Models\Keuangan\Transaksi;
use App\Models\Keuangan\Type;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\PDF as DomPDF;

class LaporanController extends Controller
{

    public function perkategori(Request $request)
    {
        $startDate = Carbon::parse($request->tanggal_awal)->toDateString();
        $endDate = Carbon::parse($request->tanggal_akhir);
        $tampil = false;
        $categoryReq = 0;
        $typeReq = 0;

        if ($request->tanggal_awal && $request->tanggal_akhir && $request->type_id && $request->category_id) {
            if ($endDate->greaterThan($startDate)) {
                $transaksi = Transaksi::whereBetween('tanggal', [$startDate, $endDate])->where('type_id', $request->type_id)->where('category_id', $request->category_id)->get();
                $tampil = true;
                $typeReq = Type::where('id', $request->type_id)->first();
                $categoryReq = Category::where('id', $request->category_id)->first();
            }
        } else if ($request->tanggal_awal && $request->tanggal_akhir && $request->type_id) {
            if ($endDate->greaterThan($startDate)) {
                $transaksi = Transaksi::whereBetween('tanggal', [$startDate, $endDate])->where('type_id', $request->type_id)->get();
                $tampil = true;
                $typeReq = Type::where('id', $request->type_id)->first();
                $categoryReq = Category::where('id', $request->category_id)->first();
            }
        } else if ($request->tanggal_awal && $request->tanggal_akhir && $request->category_id) {
            if ($endDate->greaterThan($startDate)) {
                $transaksi = Transaksi::whereBetween('tanggal', [$startDate, $endDate])->where('category_id', $request->category_id)->get();
                $tampil = true;
                $typeReq = Type::where('id', $request->type_id)->first();
                $categoryReq = Category::where('id', $request->category_id)->first();
            }
        } else if ($request->tanggal_awal && $request->tanggal_akhir) {
            $transaksi = Transaksi::whereBetween('tanggal', [$startDate, $endDate])->get();
            $tampil = true;
            $typeReq = Type::where('id', $request->type_id)->first();
            $categoryReq = Category::where('id', $request->category_id)->first();
        } else {
            $transaksi = 0;
        }

        $catregory = Category::all();
        return view('app-type.keuangan.laporan-perkategori.index', [
            'transaksi' => Transaksi::all(),
            'categories' => $catregory,
            'types' => Type::all(),
            'tampil' => $tampil,
            'transaksi' => $transaksi,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'categoryReq' => $categoryReq,
            'typeReq' => $typeReq,
        ]);
    }

    public function perbulan(Request $request)
    {
        $transaksi = Transaksi::first();
        // $tahunInput = Carbon::parse($transaksi->tanggal)->year;
        $tahunInput = 2019;

        $startDate = Carbon::parse($request->tanggal_awal)->toDateString();
        $endDate = Carbon::parse($request->tanggal_akhir);
        $tampil = false;
        $bulan = Carbon::createFromDate(null, $request->bulan, null)->translatedFormat('F');;
        $tahun = $request->tahun;

        // Other variables you may need
        if ($request->tanggal_awal && $request->tanggal_akhir && $request->bulan && $request->tahun) {
            $transaksi = Transaksi::whereBetween('tanggal', [$startDate, $endDate])
                ->whereMonth('tanggal', Carbon::createFromDate(null, $request->bulan, null))
                ->whereYear('tanggal', $request->tahun)
                ->get();
            $tampil = true;
        } else if ($request->tanggal_awal && $request->tanggal_akhir && Carbon::createFromDate(null, $request->bulan, null)) {
            $transaksi = Transaksi::whereBetween('tanggal', [$startDate, $endDate])
                ->whereMonth('tanggal', Carbon::createFromDate(null, $request->bulan, null))
                ->get();
            $tampil = true;
        } else if ($request->tanggal_awal && $request->tanggal_akhir && $request->tahun) {
            $transaksi = Transaksi::whereBetween('tanggal', [$startDate, $endDate])
                ->whereYear('tanggal', $request->tahun)
                ->get();
            $tampil = true;
        } else if ($request->tanggal_awal && $request->tanggal_akhir) {
            $transaksi = Transaksi::whereBetween('tanggal', [$startDate, $endDate])->get();
            $tampil = true;
        } else if (Carbon::createFromDate(null, $request->bulan, null)) {
            $transaksi = Transaksi::whereMonth('tanggal', Carbon::createFromDate(null, $request->bulan, null))->get();
            $tampil = true;
        } else if ($request->tahun) {
            $transaksi = Transaksi::whereYear('tanggal', $request->tahun)->get();
            $tampil = true;
        } else {
            $transaksi = 0;
        }

        return view('app-type.keuangan.laporan-perbulan.index', [
            'transaksi' => Transaksi::all(),
            'tampil' => $tampil,
            'transaksi' => $transaksi,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'tahunInput' => $tahunInput,
            'bulan' => $bulan,
            'tahun' => $tahun,
        ]);
    }
}
