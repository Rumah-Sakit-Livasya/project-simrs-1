<?php

namespace App\Http\Controllers\SIMRS\BPJS;

use App\Http\Controllers\Controller;
use App\Models\BPJS\BpjsPasienBaruMjkn;
use App\Models\SIMRS\Departement;
use App\Models\SIMRS\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MjknController extends Controller
{
    public function index()
    {
        // PENYESUAIAN: Ambil data dari model Departement dan Doctor dengan kolom yang benar
        $departements = Departement::whereNotNull('kode_poli')->orderBy('name', 'asc')->get();
        $doctors = Doctor::whereNotNull('kode_dpjp')
            ->with('employee')
            ->get()
            ->sortBy(fn($doctor) => optional($doctor->employee)->fullname)
            ->values();

        // PENYESUAIAN: Kirim variabel dengan nama yang baru ke view
        return view('app-type.simrs.bpjs.mjkn.dashboard', compact('departements', 'doctors'));
    }

    /**
     * Menampilkan halaman Pasien Baru MJKN.
     */
    public function pasienBaru()
    {
        return view('app-type.simrs.bpjs.mjkn.pasien-baru');
    }

    /**
     * Menyediakan data untuk DataTables Pasien Baru MJKN dari Webservice.
     */
    public function listPasienBaru(Request $request)
    {
        $filters = [
            'tgl_awal'  => Carbon::createFromFormat('d-m-Y', $request->input('tgl1'))->format('Y-m-d'),
            'tgl_akhir' => Carbon::createFromFormat('d-m-Y', $request->input('tgl2'))->format('Y-m-d'),
        ];

        $apiData = BpjsPasienBaruMjkn::fetchData($filters);

        $recordsTotal = count($apiData);
        $recordsFiltered = $recordsTotal;

        $paginatedData = array_slice($apiData, $request->start, $request->length);

        return response()->json([
            'draw'            => intval($request->draw),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $paginatedData,
        ]);
    }
}
