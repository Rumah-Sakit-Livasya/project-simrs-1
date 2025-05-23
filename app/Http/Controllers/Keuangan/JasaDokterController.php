<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\keuangan\JasaDokter;
use App\Models\SIMRS\Doctor;
use Illuminate\Http\Request;

class JasaDokterController extends Controller
{
    public function index(Request $request)
    {
        // Ambil parameter filter
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');
        $tanggal_ap = $request->input('tanggal_ap');
        $status_ap = $request->input('status_ap');
        $tipe_registrasi = $request->input('tipe_registrasi');
        $tagihan_pasien = $request->input('tagihan_pasien');
        $dokter_id = $request->input('dokter_id');

        // Query builder awal
        $query = JasaDokter::with(['registration.patient', 'registration.penjamin', 'dokter.employee']);

        // Filter tanggal dari kolom registration_date (varchar, idealnya ubah ke DATE)
        if ($tanggal_awal && $tanggal_akhir) {
            $query->whereHas('registration', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                $q->whereBetween('registration_date', [$tanggal_awal, $tanggal_akhir]);
            });
        }

        // Filter tanggal AP dari created_at JasaDokter
        if ($tanggal_ap) {
            $query->whereDate('created_at', $tanggal_ap);
        }

        // Filter status AP
        if ($status_ap) {
            $query->where('status', $status_ap);
        }

        // Filter tipe registrasi (rawat jalan/inap)
        if ($tipe_registrasi) {
            $query->whereHas('registration', function ($q) use ($tipe_registrasi) {
                $q->where('registration_type', $tipe_registrasi);
            });
        }

        // Filter jenis tagihan pasien, misal 'umum' atau 'asuransi'
        if ($tagihan_pasien) {
            $query->whereHas('registration.penjamin', function ($q) use ($tagihan_pasien) {
                $q->where('tipe_penjamin', $tagihan_pasien); // Pastikan kolom ini tersedia di tabel penjamin
            });
        }

        // Filter berdasarkan dokter
        if ($dokter_id) {
            $query->where('dokter_id', $dokter_id);
        }

        // Eksekusi dan ambil hasil
        $data = $query->orderBy('created_at', 'desc')->get();

        // Ambil list dokter aktif
        $dokters = Doctor::with('employee')->orderBy('id', 'asc')->get();

        return view('app-type.keuangan.jasa-dokter.index', [
            'data' => $data,
            'dokters' => $dokters,
            'request' => $request,
        ]);
    }
}
