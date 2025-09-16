<?php

namespace App\Http\Controllers\keuangan;

use App\Http\Controllers\Controller;
use App\Models\Keuangan\JasaDokter;
use App\Models\SIMRS\Doctor;
use Illuminate\Http\Request;

class ReportAPDokterController extends Controller
{
    public function indexBelumDiproses(Request $request)
    {
        $dokters = Doctor::with('employee')->get();

        // Query dasar: HANYA yang statusnya 'draft'
        $query = JasaDokter::query()
            ->where('status', 'draft')
            ->with([
                'tagihanPasien.registration.patient',
                'tagihanPasien.registration.penjamin',
                'tagihanPasien.bilinganSatu'
            ]);

        // Terapkan filter tanggal (berdasarkan tanggal bill/bilingan)
        if ($request->filled('tanggal_awal')) {
            $query->whereHas('tagihanPasien.bilinganSatu', function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->tanggal_awal);
            });
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereHas('tagihanPasien.bilinganSatu', function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->tanggal_akhir);
            });
        }

        // Terapkan filter dokter
        if ($request->filled('dokter_id')) {
            $query->where('dokter_id', $request->dokter_id);
        }

        // Ambil data dan urutkan
        $data = $query->get()->sortByDesc(function ($item) {
            return optional($item->tagihanPasien->bilinganSatu)->created_at;
        });

        return view('app-type.keuangan.report-ap-dokter.jasa-dokter-belum-diproses', compact('data', 'dokters'));
    }
    public function indexBelumDibayarkan(Request $request)
    {
        $tanggal_awal = $request->input('tanggal_awal') ?? date('Y-m-01');
        $tanggal_akhir = $request->input('tanggal_akhir') ?? date('Y-m-d');
        $dokter_id = $request->input('dokter_id');

        $query = JasaDokter::with(['registration.patient', 'registration.penjamin', 'dokter.employee'])
            ->where('status', 'belum_diproses');

        if ($tanggal_awal && $tanggal_akhir) {
            $query->whereHas('registration', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                $q->whereBetween('registration_date', [$tanggal_awal, $tanggal_akhir]);
            });
        }

        if ($dokter_id) {
            $query->where('dokter_id', $dokter_id);
        }

        $data = $query->orderBy('created_at', 'desc')->get();
        $dokters = Doctor::with('employee')->orderBy('id', 'asc')->get();
        return view('app-type.keuangan.report-ap-dokter.jasa-dokter-belum-dibayarkan', compact('data', 'dokters', 'request'));
    }
}
