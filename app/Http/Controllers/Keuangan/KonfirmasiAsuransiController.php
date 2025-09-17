<?php

namespace App\Http\Controllers\Keuangan;



use App\Http\Controllers\Controller;
use App\Models\Keuangan\InvoiceCounter;
use App\Models\Keuangan\KonfirmasiAsuransi;
use App\Models\SIMRS\Bilingan;
use App\Models\SIMRS\Penjamin;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\Patient;
use App\models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class KonfirmasiAsuransiController extends Controller
{
    public function index(Request $request)
    {
        $penjamins = Penjamin::all();

        $query = KonfirmasiAsuransi::with(['penjamin', 'registration', 'registration.patient'])
            ->where('status', 'Sudah Di Buat Tagihan')
            ->whereNotNull('invoice')
            ->whereNotNull('jatuh_tempo')
            ->whereNotNull('keterangan');

        $hasFilters = false;

        // FILTER: Tanggal
        if (!empty($request->tanggal_awal) || !empty($request->tanggal_akhir)) {
            $hasFilters = true;

            try {
                $startDate = !empty($request->tanggal_awal)
                    ? Carbon::createFromFormat('Y-m-d', $request->tanggal_awal)->startOfDay()
                    : Carbon::now()->subYears(10)->startOfDay();

                $endDate = !empty($request->tanggal_akhir)
                    ? Carbon::createFromFormat('Y-m-d', $request->tanggal_akhir)->endOfDay()
                    : Carbon::now()->endOfDay();

                $query->whereBetween('tanggal', [$startDate, $endDate]);
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['format_tanggal' => 'Format tanggal tidak valid. Harus dalam format Y-m-d.']);
            }
        }


        if ($request->has('penjamin_id') && $request->penjamin_id != '') {
            $hasFilters = true;
            $query->where('penjamin_id', $request->penjamin_id);
        }

        // FILTER: Invoice
        if ($request->has('invoice') && $request->invoice != '') {
            $hasFilters = true;
            $query->where('invoice', 'like', '%' . $request->invoice . '%');
        }

        // FILTER: Nomor Registrasi
        if ($request->has('no_registrasi') && $request->no_registrasi != '') {
            $hasFilters = true;
            $query->whereHas('registration', function ($q) use ($request) {
                $q->where('registration_number', 'like', '%' . $request->no_registrasi . '%');
            });
        }

        // FILTER: Nama Pasien
        if ($request->has('nama_pasien') && $request->nama_pasien != '') {
            $hasFilters = true;
            $query->whereHas('registration.patient', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->nama_pasien . '%');
            });
        }

        // PAGINATE
        $konfirmasiAsuransi = $query->orderBy('created_at', 'desc')->paginate(20);
        $konfirmasiAsuransi->appends($request->all());

        return view('app-type.keuangan.konfirmasi-asuransi.index', [
            'konfirmasiAsuransi' => $konfirmasiAsuransi,
            'penjamins' => $penjamins,
            'hasFilters' => $hasFilters
        ]);
    }


    public function create(Request $request)
    {
        // 1. Ambil data master untuk dropdown filter
        $penjamins = Penjamin::all();

        // 2. Buat query builder untuk BILINGAN
        $queryBuilder = Bilingan::with([
            'registration.patient',
            'registration.penjamin'
        ])
            ->where('status', 'final') // Kriteria utama: ambil yang sudah final
            ->where('is_paid', 0)      // dan belum lunas
            ->whereDoesntHave('konfirmasiAsuransi') // dan belum pernah dibuatkan konfirmasi
            ->whereHas('registration.penjamin', function ($q) {
                $q->where('group_penjamin_id', 3); // hanya ambil penjamin yang termasuk grup asuransi
            })
            ->orderBy('created_at', 'desc');


        if ($request->filled('penjamin_id')) {
            $queryBuilder->whereHas('registration', function ($q) use ($request) {
                $q->where('penjamin_id', $request->penjamin_id);
            });
        }

        // FILTER: Tagihan Ke (sama dengan penjamin)
        if ($request->filled('tagihan_ke')) {
            $queryBuilder->whereHas('registration', function ($q) use ($request) {
                $q->where('penjamin_id', $request->tagihan_ke);
            });
        }

        // FILTER: Tanggal (kita filter berdasarkan created_at di bilingan)
        if ($request->filled('tanggal_awal') || $request->filled('tanggal_akhir')) {
            $startDate = $request->tanggal_awal ? Carbon::parse($request->tanggal_awal)->startOfDay() : now()->subYears(10);
            $endDate = $request->tanggal_akhir ? Carbon::parse($request->tanggal_akhir)->endOfDay() : now();
            $queryBuilder->whereBetween('created_at', [$startDate, $endDate]);
        }

        if ($request->input('status') == 'Sudah Di Buat Tagihan') {
            $queryBuilder->whereRaw('1 = 0'); // Trik untuk tidak menghasilkan apa-apa
        }

        $bilinganData = $queryBuilder->get();

        $hasilQuery = $bilinganData->map(function ($bilingan) {
            return (object) [
                'id' => $bilingan->id, // PENTING: ID ini adalah ID Bilingan
                'registration' => $bilingan->registration,
                'patient' => $bilingan->registration->patient ?? null, // Relasi ini tidak ada di model KonfirmasiAsuransi, tapi view Anda memanggilnya
                'bill' => $bilingan->id, // Tidak ada kolom 'bill' di bilingan, kita isi dengan ID
                'jumlah' => (float) str_replace(',', '', $bilingan->wajib_bayar), // Ini adalah wajib_bayar
                'diskon' => 0, // Bilingan tidak punya kolom diskon, kita set 0
                'isBilingan' => true, // Penanda bahwa ini data bilingan
            ];
        });

        if ($request->ajax()) {
            return response()->json($hasilQuery);
        }

        return view('app-type.keuangan.konfirmasi-asuransi.partials.create', [
            'query' => $hasilQuery,
            'penjamins' => $penjamins
        ]);
    }

    protected function generateInvoiceNumber(): string
    {
        $bulanRomawi = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII'
        ];

        $now = \Carbon\Carbon::now();
        $bulan = $now->month;
        $tahun = $now->year;
        $bulanTahunKey = $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT);

        // Gunakan transaksi DB untuk memastikan akurasi counter
        $counter = DB::transaction(function () use ($bulanTahunKey) {
            $invoiceCounter = \App\Models\Keuangan\InvoiceCounter::firstOrCreate(
                ['bulan_tahun' => $bulanTahunKey],
                ['counter' => 0]
            );

            $invoiceCounter->counter = $invoiceCounter->counter + 1;
            $invoiceCounter->save();

            return $invoiceCounter->counter;
        });

        // Format akhir: 005/INV/RSLV/AS//IV/2025
        return sprintf(
            '%03d/INV/RSLV/AS//%s/%d',
            $counter,
            $bulanRomawi[$bulan],
            $tahun
        );
    }


    /**
     * Menangani proses pembuatan invoice untuk data-data yang dipilih
     */
    public function createInvoice(Request $request)
    {
        $ids = $request->input('selected_ids', []);
        $hariJatuhTempo = (int)$request->input('jatuh_tempo', 30); // default 30 hari
        $keterangan = $request->input('keterangan');

        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data yang dipilih']);
        }

        try {
            DB::beginTransaction();

            $createdInvoices = []; // Untuk mencatat invoice yang dibuat

            foreach ($ids as $id) {
                $konfirmasi = KonfirmasiAsuransi::find($id);

                if (!$konfirmasi) continue; // skip jika data tidak ditemukan

                // Lewati jika sudah ada invoice
                if ($konfirmasi->invoice) {
                    // \Log::info("Skip data ID {$id} karena sudah memiliki invoice: {$konfirmasi->invoice}");
                    continue;
                }

                $invoice = $this->generateInvoiceNumber();
                $createdInvoices[] = $invoice;

                $konfirmasi->update([
                    'invoice' => $invoice,
                    'jatuh_tempo' => Carbon::now()->addDays($hariJatuhTempo),
                    'keterangan' => $keterangan,
                    'status' => 'Sudah Di Buat Tagihan',
                ]);
            }

            DB::commit();

            $message = count($createdInvoices) > 0
                ? 'Tagihan berhasil dibuat: ' . implode(', ', $createdInvoices)
                : 'Tidak ada tagihan baru yang dibuat';

            return response()->json(['success' => true, 'message' => $message]);
        } catch (\Exception $e) {
            DB::rollBack();
            // \Log::error("Gagal membuat invoice: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal membuat tagihan: ' . $e->getMessage()]);
        }
    }


    public function store(Request $request)
    {
        // 1. Validasi input lengkap
        $validated = $request->validate([
            'bilingan_ids'   => 'required|array|min:1',
            'bilingan_ids.*' => 'exists:bilingan,id',
            'jatuh_tempo'    => 'required|integer|min:1',
            'keterangan'     => 'nullable|string|max:500',
        ]);

        $bilinganIds = $validated['bilingan_ids'];
        $jatuhTempo = (int) $validated['jatuh_tempo'];

        $keterangan = $validated['keterangan'] ?? null;

        $berhasil = 0;
        $gagal = 0;

        // 2. Gunakan DB Transaction untuk keamanan data
        DB::beginTransaction();
        try {
            foreach ($bilinganIds as $id) {
                $bilingan = Bilingan::with('registration')->find($id);

                // Keamanan tambahan: lewati jika bilingan tidak valid atau sudah diproses
                if (
                    !$bilingan || !$bilingan->registration || !$bilingan->registration->penjamin_id ||
                    KonfirmasiAsuransi::where('registration_id', $bilingan->registration_id)->exists()
                ) {
                    $gagal++;
                    continue;
                }

                // 3. Hitung tanggal jatuh tempo
                $tanggalJatuhTempo = now()->addDays($jatuhTempo)->toDateString();

                // 4. Generate nomor invoice (opsional - sesuaikan dengan kebutuhan)
                $nomorInvoice = $this->generateInvoiceNumber($bilingan->registration->penjamin_id);

                // 5. Mapping data lengkap dari Bilingan ke Konfirmasi Asuransi
                KonfirmasiAsuransi::create([
                    'registration_id'   => $bilingan->registration_id,
                    'penjamin_id'       => $bilingan->registration->penjamin_id,
                    'tagihan_ke'        => $bilingan->registration->penjamin_id,
                    'invoice'           => $nomorInvoice,           // Simpan nomor invoice
                    'jumlah'            => (float) str_replace(',', '', $bilingan->wajib_bayar),
                    'diskon'            => 0,
                    'jatuh_tempo'       => $tanggalJatuhTempo,      // Simpan jatuh tempo
                    'tanggal'           => now(),
                    'keterangan'        => $keterangan,            // Simpan keterangan
                    'status'            => 'Sudah Di Buat Tagihan',
                    'status_pembayaran' => '-',
                    'created_by'        => auth()->id(),
                    'updated_by'        => auth()->id(),
                ]);

                $berhasil++;
            }

            DB::commit();

            // Mengembalikan respons JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => "Berhasil membuat {$berhasil} Konfirmasi Asuransi dengan jatuh tempo {$jatuhTempo} hari. Gagal/Dilewati: {$gagal}."
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal store Konfirmasi Asuransi: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat menyimpan data.'
            ], 500);
        }
    }


    public function update(Request $request, $id)
    {
        $konfirmasi = KonfirmasiAsuransi::findOrFail($id);

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'penjamin_id' => 'required|exists:penjamin,id',
            'registration_id' => 'nullable|exists:registrations,id',
            'invoice' => 'required|string|max:50',
            'jumlah' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'jatuh_tempo' => 'nullable|date',
            'keterangan' => 'nullable|string'
        ]);

        // Clean numeric values
        $validated['jumlah'] = str_replace(['.', ','], '', $validated['jumlah']);
        $validated['discount'] = $validated['discount'] ? str_replace(['.', ','], '', $validated['discount']) : 0;

        // Reset pelunasan bila jumlah diubah
        $validated['sisa_tagihan'] = $validated['jumlah'];
        $validated['total_dibayar'] = 0;
        $validated['is_lunas'] = false;

        $konfirmasi->update($validated);

        return response()->json(['success' => 'Data berhasil diperbarui']);
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            // Hapus dari tabel konfirmasi_asuransi
            $deleted = KonfirmasiAsuransi::where('id', $id)->delete();

            DB::commit();

            if ($deleted) {
                return redirect()->route('keuangan.konfirmasi-asuransi.index')
                    ->with('success', 'Data berhasil dihapus');
            }

            return redirect()->route('keuangan.konfirmasi-asuransi.index')
                ->with('error', 'Data tidak ditemukan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('keuangan.konfirmasi-asuransi.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }



    public function searchRegistration(Request $request)
    {
        $search = $request->get('search');

        $registrations = Registration::with(['patient', 'penjamin'])
            ->where('no_registrasi', 'like', '%' . $search . '%')
            ->orWhereHas('patient', function ($query) use ($search) {
                $query->where('nama_pasien', 'like', '%' . $search . '%')
                    ->orWhere('medical_record_number', 'like', '%' . $search . '%');
            })
            ->limit(10)
            ->get();

        return response()->json($registrations);
    }

    public function searchTambah(Request $request)
    {
        $search = $request->get('term');

        $registrations = Registration::with(['patient', 'penjamin'])
            ->where(function ($query) use ($search) {
                $query->where('no_registrasi', 'like', '%' . $search . '%')
                    ->orWhereHas('patient', function ($q) use ($search) {
                        $q->where('nama_pasien', 'like', '%' . $search . '%')
                            ->orWhere('medical_record_number', 'like', '%' . $search . '%');
                    });
            })
            ->whereNotNull('penjamin_id')
            ->limit(10)
            ->get();

        $results = [];
        foreach ($registrations as $reg) {
            $results[] = [
                'id' => $reg->id,
                'text' => $reg->no_registrasi . ' - ' . ($reg->patient->nama_pasien ?? '') .
                    ' (MR: ' . ($reg->patient->medical_record_number ?? '') . ')',
                'patient' => $reg->patient,
                'penjamin' => $reg->penjamin
            ];
        }

        return response()->json($results);
    }


    public function cetakKlaim($id)
    {
        $konfirmasi = KonfirmasiAsuransi::with(['penjamin', 'registration.patient'])->findOrFail($id);

        return view('app-type.keuangan.konfirmasi-asuransi.cetak.cetak-klaim', compact('konfirmasi'));
    }

    public function cetakKwitansi($id)
    {
        $konfirmasi = KonfirmasiAsuransi::with(['penjamin', 'registration.patient'])->findOrFail($id);

        // TIDAK lagi gunakan PDF
        return view('app-type.keuangan.konfirmasi-asuransi.cetak.cetak-klaim-kwitansi', compact('konfirmasi'));
    }


    public function cetakRekap(Request $request)
    {
        $query = KonfirmasiAsuransi::with(['penjamin', 'registration.patient']);

        if (!empty($request->tanggal_awal) && !empty($request->tanggal_akhir)) {
            $startDate = Carbon::parse($request->tanggal_awal)->startOfDay();
            $endDate = Carbon::parse($request->tanggal_akhir)->endOfDay();
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        }

        if ($request->has('penjamin_id') && $request->penjamin_id != '') {
            $query->where('penjamin_id', $request->penjamin_id);
            $penjamin = Penjamin::find($request->penjamin_id);
        } else {
            $penjamin = null;
        }

        $data = $query->get();

        $pdf = PDF::loadView('app-type.keuangan.konfirmasi-asuransi.cetak.cetak-rekap', compact('data', 'penjamin'));
        return $pdf->stream('rekap-konfirmasi-asuransi.pdf');
    }


    public function printPreview($id, $type)
    {
        $konfirmasi = KonfirmasiAsuransi::with(['registration.patient', 'penjamin'])->findOrFail($id);

        switch ($type) {
            case 'klaim':
                $view = 'keuangan.konfirmasi-asuransi.cetak.klaim';
                $title = 'Cetak Klaim Asuransi';
                break;
            case 'kwitansi':
                $view = 'keuangan.konfirmasi-asuransi.cetak.kwitansi';
                $title = 'Cetak Kwitansi Klaim';
                break;
            case 'rekap':
                $view = 'keuangan.konfirmasi-asuransi.cetak.rekap';
                $title = 'Cetak Rekap Klaim';
                break;
            default:
                abort(404);
        }

        return view($view, compact('konfirmasi', 'title'));
    }


    public function cetakRekapByid(Request $request, $id = null)
    {
        $query = KonfirmasiAsuransi::with([
            'penjamin',
            'registration.patient',
            'registration.doctor.employee', // relasi langsung doctor_id → employee → fullname
            'user', // admin/operator
            'tindakanMedis', // biaya tindakan
        ]);

        $period_start = null;
        $period_end = null;

        if (!is_null($id)) {
            $query->where('id', $id);
        } elseif (!empty($request->tanggal_awal) && !empty($request->tanggal_akhir)) {
            $period_start = $request->tanggal_awal;
            $period_end = $request->tanggal_akhir;

            $startDate = Carbon::parse($period_start)->startOfDay();
            $endDate = Carbon::parse($period_end)->endOfDay();

            $query->whereBetween('tanggal', [$startDate, $endDate]);
        }

        if ($request->has('penjamin_id') && $request->penjamin_id != '') {
            $query->where('penjamin_id', $request->penjamin_id);
            $penjamin = Penjamin::find($request->penjamin_id);
        } else {
            $penjamin = null;
        }

        $data = $query->get();

        return view('app-type.keuangan.konfirmasi-asuransi.cetak.cetak-rekap', compact('data', 'penjamin', 'period_start', 'period_end'));
    }
}
