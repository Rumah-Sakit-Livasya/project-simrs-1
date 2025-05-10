        <?php

        namespace App\Http\Controllers\Keuangan;



        use App\Http\Controllers\Controller;
        use App\Models\Keuangan\KonfirmasiAsuransi;
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

        class KonfirmasiAsuransiController extends Controller
        {
            public function index(Request $request)
            {
                $penjamins = Penjamin::all();
                $query = KonfirmasiAsuransi::with(['penjamin', 'registration', 'registration.patient']);

                // Initialize flag to check if any filter is applied
                $hasFilters = false;

                // Filter by date range
                if (!empty($request->tanggal_awal) || !empty($request->tanggal_akhir)) {
                    $hasFilters = true;

                    try {
                        // If only one date is provided, use it for both start and end
                        $startDate = !empty($request->tanggal_awal)
                            ? Carbon::createFromFormat('Y-m-d', $request->tanggal_awal)->startOfDay()
                            : Carbon::now()->subYears(10)->startOfDay(); // Default to very old date if not provided

                        $endDate = !empty($request->tanggal_akhir)
                            ? Carbon::createFromFormat('Y-m-d', $request->tanggal_akhir)->endOfDay()
                            : Carbon::now()->endOfDay(); // Default to now if not provided

                        $query->whereBetween('tanggal', [$startDate, $endDate]);
                    } catch (\Exception $e) {
                        return redirect()->back()->withErrors(['format_tanggal' => 'Format tanggal tidak valid. Harus dalam format Y-m-d.']);
                    }
                }

                // Filter by insurance provider
                if ($request->has('penjamin_id') && $request->penjamin_id != '') {
                    $hasFilters = true;
                    $query->where('penjamin_id', $request->penjamin_id);
                }

                // Filter by invoice number
                if ($request->has('invoice') && $request->invoice != '') {
                    $hasFilters = true;
                    $query->where('invoice', 'like', '%' . $request->invoice . '%');
                }

                // Filter by registration number
                if ($request->has('no_registrasi') && $request->no_registrasi != '') {
                    $hasFilters = true;
                    $query->whereHas('registration', function ($q) use ($request) {
                        $q->where('no_registrasi', 'like', '%' . $request->no_registrasi . '%');
                    });
                }

                // Only execute query if filters are applied
                $konfirmasiAsuransi = $hasFilters ? $query->paginate(20) : collect();

                return view('app-type.keuangan.konfirmasi-asuransi.index', [
                    'konfirmasiAsuransi' => $konfirmasiAsuransi,
                    'penjamins' => $penjamins,
                    'hasFilters' => $hasFilters
                ]);
            }

            public function create()
            {

                $query = KonfirmasiAsuransi::with(['penjamin', 'registration.patient'])
                    ->orderBy('tanggal', 'desc');
                $penjamins = Penjamin::all();
                return view('app-type.keuangan.konfirmasi-asuransi.partials.create-konfirmasi-asuransi', compact('penjamins', 'query'));
            }

            public function store(Request $request)
            {
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

                KonfirmasiAsuransi::create($validated);

                return redirect()->route('keuangan.konfirmasi-asuransi.index')
                    ->with('success', 'Konfirmasi asuransi berhasil ditambahkan');
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

                $konfirmasi->update($validated);

                return response()->json(['success' => 'Data berhasil diperbarui']);
            }

            public function destroy($id)
            {
                try {
                    DB::beginTransaction();

                    // Hanya hapus dari tabel konfirmasi_asuransi
                    $deleted = KonfirmasiAsuransi::where('id', $id)->delete();

                    DB::commit();

                    if ($deleted) {
                        return response()->json(['success' => 'Data berhasil dihapus']);
                    }

                    return response()->json(['error' => 'Data tidak ditemukan'], 404);
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
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

                $pdf = PDF::loadView('app-type.keuangan.konfirmasi-asuransi.cetak.cetak-klaim', compact('konfirmasi'));
                return $pdf->stream("klaim-{$konfirmasi->invoice}.pdf");
            }

            public function cetakKwitansi($id)
            {
                $konfirmasi = KonfirmasiAsuransi::with(['penjamin', 'registration.patient'])->findOrFail($id);

                $pdf = PDF::loadView('app-type.keuangan.konfirmasi-asuransi.cetak.cetak-klaim-kwitansi', compact('konfirmasi'));
                return $pdf->stream("kwitansi-klaim-{$konfirmasi->invoice}.pdf");
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

            public function createInvoice(Request $request)
            {
                $selected = $request->input('selected'); // array of selected ID
                $jatuhTempo = Carbon::now()->addDays($request->jatuh_tempo);
                $keterangan = $request->keterangan;

                foreach ($selected as $id) {
                    KonfirmasiAsuransi::where('id', $id)->update([
                        'jatuh_tempo' => $jatuhTempo,
                        'keterangan' => $keterangan,
                        'status_pembayaran' => 'Sudah Di Buat Tagihan',
                    ]);
                }

                return redirect()->back()->with('success', 'Tagihan berhasil dibuat!');
            }

            public function cetakRekapByid(Request $request, $id = null)
            {
                $query = KonfirmasiAsuransi::with(['penjamin', 'registration.patient']);

                // Jika ada ID yang diberikan, cetak hanya data dengan ID tersebut
                if ($id !== null) {
                    $query->where('id', $id);
                }
                // Jika tidak ada ID, gunakan filter tanggal seperti sebelumnya
                else if (!empty($request->tanggal_awal) && !empty($request->tanggal_akhir)) {
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
        }
