<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Keuangan\PembayaranJasaDokter;
use App\Models\SIMRS\Doctor;
use App\Models\Bank;

class PembayaranJasaDokterController extends Controller
{
    public function index(Request $request)
    {
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');
        $dokter_id = $request->input('dokter_id');
        $status = $request->input('status');

        $query = PembayaranJasaDokter::with(['dokter.employee', 'bank']);

        if ($tanggal_awal && $tanggal_akhir) {
            $query->whereBetween('tanggal_pembayaran', [$tanggal_awal, $tanggal_akhir]);
        }

        if ($dokter_id) {
            $query->where('dokter_id', $dokter_id);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $data = $query->orderBy('tanggal_pembayaran', 'desc')->get();
        $dokters = Doctor::with('employee')->get();

        return view('app-type.keuangan.pembayaran-dokter.index', compact('data', 'dokters', 'request'));
    }

    public function create()
    {
        $dokters = Doctor::with('employee')->get();
        $banks = Bank::all();

        return view('app-type.keuangan.pembayaran-dokter.create', compact('dokters', 'banks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'dokter_id' => 'required|exists:doctors,id',
            'tanggal_pembayaran' => 'required|date',
            'metode_pembayaran' => 'required|string',
            'kas_bank_id' => 'nullable|exists:banks,id',
            'pajak_persen' => 'nullable|numeric|min:0',
            'nominal' => 'required|numeric|min:0',
            'npwp' => 'nullable|string|max:255',
            'bank' => 'nullable|string|max:255',
            'nomor_rekening' => 'nullable|string|max:255',
            'tahun_pajak' => 'nullable|integer',
            'guarantee_fee' => 'nullable|string|max:255',
        ]);

        PembayaranJasaDokter::create([
            'dokter_id' => $request->dokter_id,
            'tanggal_pembayaran' => $request->tanggal_pembayaran,
            'metode_pembayaran' => $request->metode_pembayaran,
            'kas_bank_id' => $request->kas_bank_id,
            'pajak_persen' => $request->pajak_persen ?? 0,
            'nominal' => $request->nominal,
            'npwp' => $request->npwp,
            'bank' => $request->bank,
            'nomor_rekening' => $request->nomor_rekening,
            'tahun_pajak' => $request->tahun_pajak,
            'guarantee_fee' => $request->guarantee_fee,
            'status' => 'draft',
        ]);

        return redirect()->route('pembayaran-dokter.index')->with('success', 'Pembayaran berhasil disimpan.');
    }
}
