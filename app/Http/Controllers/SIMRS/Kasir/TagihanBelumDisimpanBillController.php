<?php

namespace App\Http\Controllers\SIMRS\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TagihanBelumDisimpanBillController extends Controller
{
    public function index(Request $request)
    {
        // Data for form dropdowns
        $tipeKunjungan = ['All', 'RAWAT JALAN', 'RAWAT INAP', 'IGD'];
        $statusKunjungan = ['All', 'Aktif', 'Tutup Kunjungan']; // Example statuses

        // Initialize query results as null
        $hasilLaporan = null;

        // Set default date range for the form


        $periodeAwalInput = $request->input('periode_awal', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $periodeAkhirInput = $request->input('periode_akhir', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Only run the query if the form was submitted
        if ($request->has('action')) {
            $query = DB::table('tagihan_pasien as tp')
                ->join('registrations as reg', 'tp.registration_id', '=', 'reg.id')
                ->join('patients as px', 'reg.patient_id', '=', 'px.id')
                ->join('bilingan as bil', 'tp.bilingan_id', '=', 'bil.id')
                ->leftJoin('departements as poli', 'reg.departement_id', '=', 'poli.id')
                ->leftJoin('penjamins as pen', 'reg.penjamin_id', '=', 'pen.id')
                // CORE LOGIC: Find bills that are part of a 'bilingan' that is NOT yet final.
                ->where('bil.status', '!=', 'final')
                ->select(
                    'reg.created_at as tgl_registrasi',
                    'reg.registration_number as no_registrasi',
                    'px.medical_record_number as no_rm',
                    'px.name as nama_pasien',
                    'poli.name as ruangan',
                    'tp.tagihan',
                    'pen.nama_perusahaan as penjamin',
                    'tp.nominal'
                );

            // Apply filters from the request
            $start = Carbon::parse($request->periode_awal)->startOfDay();
            $end = Carbon::parse($request->periode_akhir)->endOfDay();
            $query->whereBetween('reg.created_at', [$start, $end]);

            if ($request->filled('nama_pasien')) {
                $query->where('px.name', 'LIKE', '%' . $request->nama_pasien . '%');
            }
            if ($request->filled('no_rm')) {
                $query->where('px.medical_record_number', $request->no_rm);
            }
            if ($request->filled('no_registrasi')) {
                $query->where('reg.registration_number', $request->no_registrasi);
            }
            if ($request->filled('tipe_kunjungan') && $request->tipe_kunjungan != 'All') {
                $query->where('reg.type', $request->tipe_kunjungan);
            }
            if ($request->filled('status_kunjungan') && $request->status_kunjungan != 'All') {
                $query->where('reg.status', $request->status_kunjungan);
            }

            $hasilLaporan = $query->orderBy('reg.created_at', 'desc')->get();
        }

        return view('pages.simrs.keuangan.kasir.laporan.tagihan-belum-simpan-bill.index', compact(
            'tipeKunjungan',
            'statusKunjungan',
            'periodeAwalInput',
            'periodeAkhirInput',
            'hasilLaporan'
        ));
    }
}
