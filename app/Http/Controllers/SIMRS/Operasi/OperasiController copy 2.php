<?php

namespace App\Http\Controllers\SIMRS\Operasi;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Bilingan;
use App\Models\SIMRS\BilinganTagihanPasien;
use App\Models\SIMRS\Operasi\OrderOperasi;
use App\Models\SIMRS\Operasi\ProsedurOperasi;
use App\Models\SIMRS\Operasi\TindakanOperasi;
use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\Operasi\JenisOperasi;
use App\Models\SIMRS\Operasi\KategoriOperasi;
use App\Models\SIMRS\Operasi\TarifOperasi;
use App\Models\SIMRS\Operasi\TipeOperasi;
use App\Models\SIMRS\Room;
use App\Models\SIMRS\TagihanPasien;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class OperasiController extends Controller
{
    // Menyimpan order operasi

    public function index(Request $request)
    {

        // Query with necessary relationships
        $query = OrderOperasi::with([
            'registration.patient',
            'registration.penjamin',
            'tipeOperasi',
            'kategoriOperasi',
            'ruangan',
            'doctor',
            'user'
        ]);

        // Apply filters
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tgl_operasi', [
                Carbon::parse($request->tanggal_awal)->startOfDay(),
                Carbon::parse($request->tanggal_akhir)->endOfDay()
            ]);
        }

        if ($request->filled('medical_record_number')) {
            $query->whereHas('registration.patient', function ($q) use ($request) {
                $q->where('medical_record_number', 'like', '%' . $request->medical_record_number . '%');
            });
        }

        if ($request->filled('nama_pasien')) {
            $query->whereHas('registration.patient', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->nama_pasien . '%');
            });
        }

        if ($request->filled('ruangan_id')) {
            $query->where('ruangan_id', $request->ruangan_id);
        }

        if ($request->filled('penjamin_id')) {
            $query->whereHas('registration', function ($q) use ($request) {
                $q->where('penjamin_id', $request->penjamin_id);
            });
        }

        if ($request->filled('status_registrasi')) {
            $query->whereHas('registration', function ($q) use ($request) {
                $q->where('status', $request->status_registrasi);
            });
        }

        $orders = $query->latest('tgl_operasi')->get();
        // $ruangans = \App\Models\SIMRS\Room::where('is_operasi', true)->get();
        $penjamins = \App\Models\SIMRS\Penjamin::all();

        return view('pages.simrs.operasi.index', [
            'orders' => $orders,
            // 'ruangans' => $ruangans,
            'penjamins' => $penjamins,
            'request' => $request
        ]);
    }
    public function storeOrder(Request $request)
    {
        try {
            // Validasi disesuaikan dengan form dan migration baru
            $validated = $request->validate([
                'registration_id' => 'required|exists:registrations,id',
                'ruangan_id' => 'nullable|exists:rooms,id',
                'tgl_operasi' => 'required|date_format:d-m-Y H:i',
                'tipe_operasi_id' => 'required|exists:tipe_operasi,id',
                'kategori_operasi_id' => 'required|exists:kategori_operasi,id',
                'kelas_rawat_id' => 'required|exists:kelas_rawat,id',
                'diagnosa_awal' => 'required|string'
            ]);

            // Konversi format tanggal sebelum disimpan
            $validated['tgl_operasi'] = \Carbon\Carbon::createFromFormat('d-m-Y H:i', $validated['tgl_operasi'])->toDateTimeString();


            // $validated['ruangan_id'] = null;

            // Tambahkan user_id dari user yang sedang login
            $validated['user_id'] = auth()->id(); // atau Auth::id()

            $order = OrderOperasi::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Order operasi berhasil dibuat.',
                'order_id' => $order->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan order: ' . $e->getMessage()
            ], 500);
        }
    }

    // API endpoint untuk mendapatkan data order operasi
    public function getOrderOperasi($registrationId)
    {
        try {
            $orders = OrderOperasi::with(['registration.patient', 'tipeOperasi', 'kategoriOperasi', 'kelasRawat.rooms'])
                ->where('registration_id', $registrationId)
                ->latest()
                ->get();

            $data = $orders->map(function ($order) {
                return [
                    'id' => $order->id,
                    'tgl_order_formatted' => $order->created_at->format('d-m-Y H:i'),
                    'kelas_name' => $order->kelasRawat ? $order->kelasRawat->kelas : 'N/A',
                    'ruangan_name' => $order->ruangan_id ?? 'N/A',
                    'kategori_operasi_name' =>  $order->kategoriOperasi ? $order->kategoriOperasi->nama_kategori : 'N/A',
                    'jenis_operasi_name' =>  $order->tipeOperasi ? $order->tipeOperasi->tipe : 'N/A',
                    'diagnosa' => $order->diagnosa_awal,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getProsedurData($registrationId)
    {
        try {
            $prosedurs = ProsedurOperasi::with([
                'tindakanOperasi',
                'dokterOperator.employee',
                'orderOperasi.tipeOperasi',
                'orderOperasi.kategoriOperasi',
                'createdByUser'
            ])
                ->whereHas('orderOperasi', function ($query) use ($registrationId) {
                    $query->where('registration_id', $registrationId);
                })
                ->latest()
                ->get();

            $data = $prosedurs->map(function ($prosedur) {
                // Helper function untuk format nama dokter
                $getDoctorName = function ($doctor) {
                    return $doctor ? $doctor->employee->fullname : null;
                };

                return [
                    'id' => $prosedur->id,
                    'tindakan_nama' => $prosedur->tindakanOperasi->nama_operasi ?? 'N/A',
                    'tipe_operasi' => $prosedur->orderOperasi->tipeOperasi->tipe ?? 'N/A',
                    'kategori_operasi' => $prosedur->orderOperasi->kategoriOperasi->nama_kategori ?? 'N/A',
                    'dokter_operator' => $getDoctorName($prosedur->dokterOperator) ?? 'N/A',
                    'tgl_tindakan' => $prosedur->created_at ? $prosedur->created_at->format('d-m-Y H:i') : 'N/A',
                    'user_create' => $prosedur->createdByUser->name ?? 'N/A',
                    'status' => ucfirst($prosedur->status ?? 'draft'),
                    'tim_dokter' => [
                        'operator' => $getDoctorName($prosedur->dokterOperator) ?? 'N/A',
                        'ass_operator_1' => $getDoctorName($prosedur->assDokterOperator1) ?? 'N/A',
                        'ass_operator_2' => $getDoctorName($prosedur->assDokterOperator2) ?? 'N/A',
                        'ass_operator_3' => $getDoctorName($prosedur->assDokterOperator3) ?? 'N/A',
                        'anestesi' => $getDoctorName($prosedur->dokterAnastesi) ?? 'N/A',
                        'ass_anestesi' => $getDoctorName($prosedur->assDokterAnastesi) ?? 'N/A'
                    ]
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data prosedur: ' . $e->getMessage()
            ], 500);
        }
    }

    // Tambahkan method ini di OperasiController

    // Tambahkan method ini di OperasiController
    // Jangan lupa import model yang diperlukan di atas class



    public function getJenisByKategori($kategoriId)
    {
        try {
            // Ambil semua tindakan yang memiliki kategori ini
            $jenis_operasi = TindakanOperasi::where('kategori_operasi_id', $kategoriId)
                ->with('jenisOperasi:id,jenis') // join ke jenis_operasi
                ->get()
                ->pluck('jenisOperasi') // ambil jenis_operasi-nya
                ->unique('id') // hilangkan duplikat
                ->values();

            return response()->json($jenis_operasi);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan pada server',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function getTindakanByJenis($jenisId)
    {
        try {
            $tindakan_operasi = TindakanOperasi::where('jenis_operasi_id', $jenisId)
                ->select('id', 'nama_operasi', 'jenis_operasi_id')
                ->get();

            return response()->json($tindakan_operasi, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan pada server',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    // API endpoint untuk mendapatkan data tindakan operasi
    public function getTindakanOperasi($registrationId)
    {
        try {
            // Sesuaikan dengan model dan relasi yang ada
            $tindakan = []; // Kosong dulu, sesuaikan dengan data real Anda

            return response()->json([
                'success' => true,
                'data' => $tindakan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data tindakan: ' . $e->getMessage()
            ], 500);
        }
    }

    // Method lainnya tetap sama...


    public function listOrder(Request $request)
    {
        $orders = OrderOperasi::with(['registration.patient', 'tipeOperasi', 'kategoriOperasi'])
            ->latest()
            ->get();

        return view('pages.simrs.operasi.list-order', [
            'orders' => $orders
        ]);
    }

    public function prosedure($orderId)
    {
        $order = OrderOperasi::with([
            'registration.patient',
            'prosedurOperasi' => function ($query) {
                $query->with([
                    'tindakanOperasi',
                    'dokterOperator.employee',
                    'assDokterOperator1.employee',
                    'assDokterOperator2.employee',
                    'assDokterOperator3.employee',
                    'dokterAnastesi.employee',
                    'assDokterAnastesi.employee',
                    'dokterResusitator.employee',
                    'dokterTambahan1.employee',
                    'dokterTambahan2.employee',
                    'dokterTambahan3.employee',
                    'dokterTambahan4.employee',
                    'dokterTambahan5.employee',
                    'createdByUser'
                ]);
            },
            'tipeOperasi',
            'kategoriOperasi',
            'jenisOperasi'
        ])->findOrFail($orderId);
        // dd($order);

        // Ambil data order dengan relasi yang diperlukan
        $order = OrderOperasi::with([
            'registration.patient',
            'prosedurOperasi' => function ($query) {
                $query->with([
                    'tindakanOperasi',
                    'dokterOperator.employee',
                    'createdByUser'
                ]);
            },
            'tipeOperasi',
            'kategoriOperasi',
            'jenisOperasi'
        ])->findOrFail($orderId);

        // Siapkan data prosedur untuk datatables
        $prosedurData = $order->prosedurOperasi->map(function ($prosedur) {
            // Helper function untuk mengambil nama dokter
            $getDoctorName = function ($doctorId) {
                if (!$doctorId) return null;
                $doctor = \App\Models\SIMRS\Doctor::with('employee')->find($doctorId);
                return $doctor ? $doctor->employee->fullname : null;
            };

            return [
                'id' => $prosedur->id,
                'tindakan_nama' => $prosedur->tindakanOperasi->nama_operasi ?? 'N/A',
                'tipe_operasi' => $prosedur->orderOperasi->tipeOperasi->tipe ?? 'N/A',
                'tipe_penggunaan' => $prosedur->orderOperasi->tipeOperasi->tipe ?? 'N/A',
                'kategori_operasi' => $prosedur->orderOperasi->kategoriOperasi->nama_kategori ?? 'N/A',
                'dokter_operator' => $prosedur->dokterOperator->employee->fullname ?? 'N/A',
                'tgl_tindakan' => $prosedur->created_at ? $prosedur->created_at->format('d-m-Y H:i') : 'N/A',
                'user_create' => $prosedur->createdByUser->name ?? 'N/A',
                'status' => ucfirst($prosedur->status ?? 'draft'),
                'waktu_mulai' => $prosedur->waktu_mulai ? \Carbon\Carbon::parse($prosedur->waktu_mulai)->format('d-m-Y H:i') : null,
                'waktu_selesai' => $prosedur->waktu_selesai ? \Carbon\Carbon::parse($prosedur->waktu_selesai)->format('d-m-Y H:i') : null,
                // Tim dokter untuk detail
                'tim_dokter' => [
                    'operator' => $prosedur->dokterOperator->employee->fullname ?? 'N/A',
                    'ass_operator_1' => $getDoctorName($prosedur->ass_dokter_operator_1_id),
                    'ass_operator_2' => $getDoctorName($prosedur->ass_dokter_operator_2_id),
                    'ass_operator_3' => $getDoctorName($prosedur->ass_dokter_operator_3_id),
                    'anestesi' => $getDoctorName($prosedur->dokter_anastesi_id),
                    'ass_anestesi' => $getDoctorName($prosedur->ass_dokter_anastesi_id),
                    'resusitator' => $getDoctorName($prosedur->dokter_resusitator_id),
                    'tambahan_1' => $getDoctorName($prosedur->dokter_tambahan_1_id),
                    'tambahan_2' => $getDoctorName($prosedur->dokter_tambahan_2_id),
                    'tambahan_3' => $getDoctorName($prosedur->dokter_tambahan_3_id),
                    'tambahan_4' => $getDoctorName($prosedur->dokter_tambahan_4_id),
                    'tambahan_5' => $getDoctorName($prosedur->dokter_tambahan_5_id),
                ]

            ];
        });

        return view('pages.simrs.operasi.treatment-list', [
            'order' => $order,
            'prosedurData' => $prosedurData
        ]);
    }


    public function editProsedur(OrderOperasi $order, ProsedurOperasi $prosedur)
    {
        // Validasi bahwa prosedur milik order yang benar
        if ($prosedur->order_operasi_id !== $order->id) {
            abort(404, 'Prosedur tidak ditemukan untuk order ini.');
        }

        // Hanya boleh edit jika status draft
        if ($prosedur->status === 'final') {
            return redirect()->back()->with('error', 'Prosedur dengan status final tidak dapat diedit.');
        }

        // Load data yang dibutuhkan
        $order->load('registration.patient', 'tipeOperasi', 'kelasRawat', 'kategoriOperasi', 'registration.penjamin');
        $prosedur->load([
            'tindakanOperasi',
            'dokterOperator.employee',
            'assDokterOperator1.employee',
            'assDokterOperator2.employee',
            'assDokterOperator3.employee',
            'dokterAnastesi.employee',
            'assDokterAnastesi.employee',
            'dokterResusitator.employee'
        ]);

        $jenis_operasi = JenisOperasi::orderBy('jenis', 'asc')->get();
        $ruangan_operasi = Room::all();
        $tindakan_operasi = TindakanOperasi::with(['kategoriOperasi', 'jenisOperasi'])->orderBy('nama_operasi', 'asc')->get();
        $kategori_operasi = KategoriOperasi::orderBy('nama_kategori', 'asc')->get();
        $tipe_operasi = TipeOperasi::orderBy('tipe', 'asc')->get();
        $kelas_rawat = KelasRawat::orderBy('kelas', 'asc')->get();

        $doctors = Doctor::with(['employee' => function ($query) {
            $query->where('is_active', true);
        }])->whereHas('employee', function ($query) {
            $query->where('is_active', true);
        })->get()->map(function ($doctor) {
            return [
                'id' => $doctor->id,
                'fullname' => $doctor->employee->fullname ?? 'Tidak Diketahui',
                'kode_dpjp' => $doctor->kode_dpjp,
                'departement' => $doctor->departement->name ?? 'Tidak Ada Departemen'
            ];
        })->sortBy('fullname');

        return view('pages.simrs.operasi.edit_tindakan_ok', [
            'order' => $order,
            'prosedur' => $prosedur,
            'doctors' => $doctors,
            'tindakan_operasi' => $tindakan_operasi,
            'ruangan_operasi' => $ruangan_operasi,
            'tipe_operasi' => $tipe_operasi,
            'jenis_operasi' => $jenis_operasi,
            'kategori_operasi' => $kategori_operasi,
            'kelas_rawat' => $kelas_rawat,
            'isEdit' => true
        ]);
    }

    /**
     * Update prosedur operasi
     */
    public function updateProsedur(Request $request, ProsedurOperasi $prosedur)
    {
        try {
            // Cek apakah prosedur masih berstatus draft
            if ($prosedur->status === 'final') {
                return response()->json([
                    'success' => false,
                    'message' => 'Prosedur dengan status final tidak dapat diedit.'
                ], 422);
            }

            // Validasi data yang masuk
            $validated = $request->validate([
                'order_operasi_id' => 'required|exists:order_operasi,id',
                'tgl_operasi' => 'required|date',
                // 'ruangan_id' => 'required|exists:rooms,id',
                // 'tipe_operasi_id' => 'required|exists:tipe_operasi,id',
                // 'tipe_penggunaan' => 'required|in:UMUM,ELEKTIF',
                'kelas_rawat_id' => 'required|exists:kelas_rawat,id',
                'kategori_operasi_id' => 'required|exists:kategori_operasi,id',
                // 'jenis_operasi_id' => 'required|exists:jenis_operasi,id',
                'tindakan_operasi_id' => 'required|exists:tindakan_operasi,id',
                'dokter_operator_id' => 'required|exists:doctors,id',
                'ass_dokter_operator_1_id' => 'nullable|exists:doctors,id|different:dokter_operator_id',
                'ass_dokter_operator_2_id' => 'nullable|exists:doctors,id|different:dokter_operator_id,ass_dokter_operator_1_id',
                'ass_dokter_operator_3_id' => 'nullable|exists:doctors,id|different:dokter_operator_id,ass_dokter_operator_1_id,ass_dokter_operator_2_id',
                'dokter_anastesi_id' => 'nullable|exists:doctors,id',
                'ass_dokter_anastesi_id' => 'nullable|exists:doctors,id|different:dokter_anastesi_id',
                'dokter_resusitator_id' => 'nullable|exists:doctors,id',
                'dokter_tambahan_1_id' => 'nullable|exists:doctors,id',
                'dokter_tambahan_2_id' => 'nullable|exists:doctors,id',
                'dokter_tambahan_3_id' => 'nullable|exists:doctors,id',
                'dokter_tambahan_4_id' => 'nullable|exists:doctors,id',
                'dokter_tambahan_5_id' => 'nullable|exists:doctors,id',
                'status' => 'required|in:draft,final',
            ], [
                'dokter_operator_id.required' => 'Dokter operator wajib dipilih.',
                'dokter_operator_id.exists' => 'Dokter operator yang dipilih tidak valid.',
                'ass_dokter_operator_1_id.different' => 'Asisten operator 1 harus berbeda dengan dokter operator.',
                'ass_dokter_operator_2_id.different' => 'Asisten operator 2 harus berbeda dengan dokter operator dan asisten operator 1.',
                'ass_dokter_operator_3_id.different' => 'Asisten operator 3 harus berbeda dengan dokter operator dan asisten operator lainnya.',
                'ass_dokter_anastesi_id.different' => 'Asisten anestesi harus berbeda dengan dokter anestesi.',
                'tindakan_operasi_id.required' => 'Tindakan operasi wajib dipilih.',
                'tindakan_operasi_id.exists' => 'Tindakan operasi yang dipilih tidak valid.',
                'status.required' => 'Status wajib dipilih.',
                'status.in' => 'Status harus draft atau final.',
            ]);

            // 1. Update data dasar pada tabel order_operasi
            $order = OrderOperasi::findOrFail($validated['order_operasi_id']);
            $order->update([
                'tgl_operasi' => $validated['tgl_operasi'],
                // 'ruangan_id' => $validated['ruangan_id'],
                // 'tipe_operasi_id' => $validated['tipe_operasi_id'],
                'kelas_rawat_id' => $validated['kelas_rawat_id'],
                'kategori_operasi_id' => $validated['kategori_operasi_id'],
                // 'jenis_operasi_id' => $validated['jenis_operasi_id'],
                'status' => $validated['status'],
            ]);

            // 2. Update prosedur operasi
            $prosedurData = [
                'order_operasi_id' => $validated['order_operasi_id'],
                // 'jenis_operasi_id' => $validated['jenis_operasi_id'],
                'tindakan_operasi_id' => $validated['tindakan_operasi_id'],
                // 'tipe_penggunaan' => $validated['tipe_penggunaan'],
                'dokter_operator_id' => $validated['dokter_operator_id'],
                'ass_dokter_operator_1_id' => $validated['ass_dokter_operator_1_id'] ?? null,
                'ass_dokter_operator_2_id' => $validated['ass_dokter_operator_2_id'] ?? null,
                'ass_dokter_operator_3_id' => $validated['ass_dokter_operator_3_id'] ?? null,
                'dokter_anastesi_id' => $validated['dokter_anastesi_id'] ?? null,
                'ass_dokter_anastesi_id' => $validated['ass_dokter_anastesi_id'] ?? null,
                'dokter_resusitator_id' => $validated['dokter_resusitator_id'] ?? null,
                'dokter_tambahan_1_id' => $validated['dokter_tambahan_1_id'] ?? null,
                'dokter_tambahan_2_id' => $validated['dokter_tambahan_2_id'] ?? null,
                'dokter_tambahan_3_id' => $validated['dokter_tambahan_3_id'] ?? null,
                'dokter_tambahan_4_id' => $validated['dokter_tambahan_4_id'] ?? null,
                'dokter_tambahan_5_id' => $validated['dokter_tambahan_5_id'] ?? null,
                'dokter_tambahan_ids' => json_decode($request->dokter_tambahan_ids, true) ?? [],
                'status' => $validated['status'],
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
                'waktu_mulai' => now(), // S
            ];

            // 3. Jika status berubah menjadi final, set waktu selesai
            if ($validated['status'] === 'final' && $prosedur->status !== 'final') {
                $prosedurData['waktu_selesai'] = now();
            }

            $prosedur->update($prosedurData);

            return response()->json([
                'success' => true,
                'message' => 'Tindakan operasi berhasil diperbarui sebagai ' . ucfirst($validated['status']) . '.',
                'data' => $prosedur
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Data yang dimasukkan tidak valid.'
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating prosedur operasi: ' . $e->getMessage(), [
                'prosedur_id' => $prosedur->id,
                'request_data' => $request->except('password', '_token'),
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server. Silakan coba lagi.',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function createProsedur(OrderOperasi $order)
    {
        // Load data yang dibutuhkan untuk ditampilkan di form (header)
        $order->load('registration.patient', 'tipeOperasi', 'kelasRawat', 'kategoriOperasi', 'registration.penjamin');

        // Ambil semua data master untuk dropdown dengan relasi yang tepat
        $jenis_operasi = JenisOperasi::orderBy('jenis', 'asc')->get();
        $ruangan_operasi = Room::all();
        $tindakan_operasi = TindakanOperasi::with(['kategoriOperasi', 'jenisOperasi'])->orderBy('nama_operasi', 'asc')->get();
        $kategori_operasi = KategoriOperasi::orderBy('nama_kategori', 'asc')->get();
        $tipe_operasi = TipeOperasi::orderBy('tipe', 'asc')->get();
        $kelas_rawat = KelasRawat::orderBy('kelas', 'asc')->get();

        // Ambil data doctors dengan relasi employee untuk mendapatkan fullname
        $doctors = Doctor::with(['employee' => function ($query) {
            $query->where('is_active', true);
        }])->whereHas('employee', function ($query) {
            $query->where('is_active', true);
        })->get()->map(function ($doctor) {
            return [
                'id' => $doctor->id,
                'fullname' => $doctor->employee->fullname ?? 'Tidak Diketahui',
                'kode_dpjp' => $doctor->kode_dpjp,
                'departement' => $doctor->departement->name ?? 'Tidak Ada Departemen'
            ];
        })->sortBy('fullname');

        return view('pages.simrs.operasi.input_tindakan_ok', [
            'order' => $order,
            'doctors' => $doctors,
            'tindakan_operasi' => $tindakan_operasi,
            'ruangan_operasi' => $ruangan_operasi,
            'tipe_operasi' => $tipe_operasi,
            'jenis_operasi' => $jenis_operasi,
            'kategori_operasi' => $kategori_operasi,
            'kelas_rawat' => $kelas_rawat,
        ]);
    }

    public function deleteProsedur($prosedurId)
    {
        try {
            $prosedur = ProsedurOperasi::findOrFail($prosedurId);



            // Cek apakah user yang login sama dengan yang membuat
            if ($prosedur->created_by !== auth()->id() && !auth()->user()->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk menghapus prosedur ini.'
                ], 403);
            }

            $prosedur->delete();

            return response()->json([
                'success' => true,
                'message' => 'Prosedur operasi berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting prosedur: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus prosedur.'
            ], 500);
        }
    }

    // Method untuk mendapatkan kategori berdasarkan jenis operasi
    public function getKategoriByJenis($jenisId)
    {
        try {
            $kategori = KategoriOperasi::where('jenis_operasi_id', $jenisId)
                ->select('id', 'nama_kategori')
                ->get();

            \Log::info('getKategoriByJenis - jenisId: ' . $jenisId . ', found: ' . $kategori->count());

            return response()->json([
                'success' => true,
                'data' => $kategori,
                'debug' => [
                    'jenis_id' => $jenisId,
                    'count' => $kategori->count()
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in getKategoriByJenis: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }




    public function storeProsedur(Request $request)
    {
        try {
            // Validate all input fields including additional doctors
            $validated = $request->validate([
                'order_operasi_id' => 'required|exists:order_operasi,id',
                'tgl_operasi' => 'required|date',
                'kelas_rawat_id' => 'required|exists:kelas_rawat,id',
                'kategori_operasi_id' => 'required|exists:kategori_operasi,id',
                'jenis_operasi_id' => 'required|exists:jenis_operasi,id',
                'tindakan_operasi_id' => 'required|exists:tindakan_operasi,id',
                'dokter_operator_id' => 'required|exists:doctors,id',

                // Assistant doctors with validation to prevent duplicates
                'ass_dokter_operator_1_id' => 'nullable|exists:doctors,id|different:dokter_operator_id',
                'ass_dokter_operator_2_id' => 'nullable|exists:doctors,id|different:dokter_operator_id,ass_dokter_operator_1_id',
                'ass_dokter_operator_3_id' => 'nullable|exists:doctors,id|different:dokter_operator_id,ass_dokter_operator_1_id,ass_dokter_operator_2_id',

                // Anesthesia team
                'dokter_anastesi_id' => 'nullable|exists:doctors,id',
                'ass_dokter_anastesi_id' => 'nullable|exists:doctors,id|different:dokter_anastesi_id',
                'dokter_resusitator_id' => 'nullable|exists:doctors,id',

                // Additional doctors (1-5)
                'dokter_tambahan_1_id' => 'nullable|exists:doctors,id',
                'dokter_tambahan_2_id' => 'nullable|exists:doctors,id',
                'dokter_tambahan_3_id' => 'nullable|exists:doctors,id',
                'dokter_tambahan_4_id' => 'nullable|exists:doctors,id',
                'dokter_tambahan_5_id' => 'nullable|exists:doctors,id',

                'status' => 'required|in:draft,final',
            ]);

            Log::debug('Validated data:', $validated);

            // 1. Update order operasi
            $order = OrderOperasi::findOrFail($validated['order_operasi_id']);
            $order->update([
                'tgl_operasi' => $validated['tgl_operasi'],
                'kelas_rawat_id' => $validated['kelas_rawat_id'],
                'kategori_operasi_id' => $validated['kategori_operasi_id'],
                'jenis_operasi_id' => $validated['jenis_operasi_id'],
            ]);

            // 2. Prepare procedure data with explicit mapping
            $prosedurData = [
                'order_operasi_id' => $validated['order_operasi_id'],
                'tgl_operasi' => $validated['tgl_operasi'],
                'kelas_rawat_id' => $validated['kelas_rawat_id'],
                'kategori_operasi_id' => $validated['kategori_operasi_id'],
                'jenis_operasi_id' => $validated['jenis_operasi_id'],
                'tindakan_operasi_id' => $validated['tindakan_operasi_id'],
                'dokter_operator_id' => $validated['dokter_operator_id'],

                // Assistant operators
                'ass_dokter_operator_1_id' => $validated['ass_dokter_operator_1_id'] ?? null,
                'ass_dokter_operator_2_id' => $validated['ass_dokter_operator_2_id'] ?? null,
                'ass_dokter_operator_3_id' => $validated['ass_dokter_operator_3_id'] ?? null,

                // Anesthesia team
                'dokter_anastesi_id' => $validated['dokter_anastesi_id'] ?? null,
                'ass_dokter_anastesi_id' => $validated['ass_dokter_anastesi_id'] ?? null,
                'dokter_resusitator_id' => $validated['dokter_resusitator_id'] ?? null,

                // Additional doctors - MAKE SURE THESE MATCH YOUR DB COLUMNS
                'dokter_tambahan_1_id' => $validated['dokter_tambahan_1_id'] ?? null,
                'dokter_tambahan_2_id' => $validated['dokter_tambahan_2_id'] ?? null,
                'dokter_tambahan_3_id' => $validated['dokter_tambahan_3_id'] ?? null,
                'dokter_tambahan_4_id' => $validated['dokter_tambahan_4_id'] ?? null,
                'dokter_tambahan_5_id' => $validated['dokter_tambahan_5_id'] ?? null,

                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
                'waktu_mulai' => now(),
                'status' => $validated['status'],
            ];
            // dd('RAW REQUEST DATA:', $request->all());

            \Log::debug('Procedure data prepared:', $prosedurData);

            // 3. Create procedure
            $prosedur = ProsedurOperasi::create($prosedurData);
            Log::info('Procedure created:', $prosedur->toArray());

            // 4. If final, complete procedure
            if ($validated['status'] === 'final') {
                $prosedur->update(['waktu_selesai' => now()]);
                $this->createOperationBilling($prosedur);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Tindakan operasi berhasil disimpan.',
                'data' => $prosedur
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::error('Validation error:', $e->errors());
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Validasi gagal'
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in storeProsedur: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }


    // public function storeProsedur(Request $request)
    // {
    //     try {
    //         // Sementara, validasi dikendurkan untuk testing
    //         $validated = $request->validate([
    //             'order_operasi_id' => 'required|exists:order_operasi,id',

    //             'tgl_operasi' => 'required|date',
    //             'ruangan_id' => 'nullable|exists:rooms,id',
    //             'tipe_operasi_id' => 'required|exists:tipe_operasi,id',
    //             'tipe_penggunaan' => 'required|in:UMUM,ELEKTIF',
    //             'kelas_rawat_id' => 'required|exists:kelas_rawat,id',
    //             'kategori_operasi_id' => 'required|exists:kategori_operasi,id',
    //             // 'tindakan_id' => 'nullable|exists:tindakan_operasi,id',

    //             'dokter_operator_id' => 'required|exists:doctors,id',
    //             'ass_dokter_operator_id' => 'nullable|exists:doctors,id',
    //             'dokter_anastesi_id' => 'nullable|exists:doctors,id',
    //             'ass_dokter_anastesi_id' => 'nullable|exists:doctors,id',
    //             'dokter_resusitator_id' => 'nullable|exists:doctors,id',
    //             'dokter_tambahan_id' => 'nullable|exists:doctors,id',

    //             'laporan_operasi' => 'nullable|string|min:0',
    //             'status' => 'required|in:rencana,selesai',
    //         ]);

    //         // Data dummy jika belum dikirim
    //         if (empty($validated['laporan_operasi'])) {
    //             $validated['laporan_operasi'] = 'Belum ada laporan (dummy data)';
    //         }

    //         if (empty($validated['tindakan_id'])) {
    //             $validated['tindakan_id'] = 1; // isi dengan ID tindakan dummy jika perlu
    //         }

    //         // Update order_operasi
    //         $orderData = [
    //             'tgl_operasi' => $validated['tgl_operasi'],
    //             'ruangan_id' => $validated['ruangan_id'],
    //             'tipe_operasi_id' => $validated['tipe_operasi_id'],
    //             'kelas_rawat_id' => $validated['kelas_rawat_id'],
    //             'kategori_operasi_id' => $validated['kategori_operasi_id'],
    //         ];

    //         $order = OrderOperasi::findOrFail($validated['order_operasi_id']);
    //         $order->update($orderData);

    //         // Ambil sisa data untuk prosedur operasi
    //         $prosedurData = collect($validated)->except([
    //             'tgl_operasi',
    //             'ruangan_id',
    //             'tipe_operasi_id',
    //             'kelas_rawat_id',
    //             'kategori_operasi_id'
    //         ])->toArray();

    //         $prosedurData['created_by'] = auth()->id();

    //         if ($prosedurData['status'] === 'selesai') {
    //             $prosedurData['waktu_mulai'] = now();
    //             $prosedurData['waktu_selesai'] = now();
    //         } else {
    //             $prosedurData['waktu_mulai'] = now();
    //         }

    //         $prosedur = ProsedurOperasi::create($prosedurData);

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Tindakan operasi (dummy) berhasil disimpan.',
    //             'data' => [
    //                 'id' => $prosedur->id,
    //                 'status' => $prosedur->status
    //             ]
    //         ]);
    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         return response()->json([
    //             'success' => false,
    //             'errors' => $e->errors(),
    //             'message' => 'Validasi gagal.'
    //         ], 422);
    //     } catch (\Exception $e) {
    //         \Log::error('Error dummy store prosedur: ' . $e->getMessage(), [
    //             'request_data' => $request->all(),
    //             'user_id' => auth()->id()
    //         ]);

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Server error: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function show($orderId)
    {
        $order = OrderOperasi::with(['prosedurOperasi', 'registration.patient'])
            ->findOrFail($orderId);

        return view('simrs.operasi.detail', compact('order'));
    }

    public function getOrderData($orderId)
    {
        $order = OrderOperasi::with(['prosedurOperasi', 'registration'])
            ->findOrFail($orderId);

        return response()->json($order);
    }


    public function deleteOrder(OrderOperasi $order) // Terima model OrderOperasi langsung
    {
        try {
            // Anda tidak perlu lagi mencari order, Laravel sudah melakukannya untuk Anda.
            // $orderId = $request->input('id');
            // $order = OrderOperasi::findOrFail($orderId);

            if ($order->prosedurOperasi()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order tidak dapat dihapus karena sudah memiliki tindakan.'
                ], 422);
            }

            $order->delete();

            return response()->json([
                'success' => true,
                'message' => 'Order operasi berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            // Catch block ini sekarang lebih untuk error tak terduga lainnya
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus order: ' . $e->getMessage()
            ], 500);
        }
    }


    public function getOrderDetail($orderId)
    {

        try {
            $order = OrderOperasi::with([
                'registration.patient',
                'registration.penjamin',
                'tipeOperasi',
                'kategoriOperasi',
                'prosedurOperasi.tindakanOperasi',
                'prosedurOperasi.dokterOperator',
                'prosedurOperasi.assDokterOperator',
                'prosedurOperasi.dokterAnestesi',
                'prosedurOperasi.assDokterAnestesi',
                'ruangan',
                'doctor',
                'user'
            ])->findOrFail($orderId);

            $data = [
                'registration_number' => $order->registration->registration_number ?? '-',
                'patient_name' => $order->registration->patient->name ?? '-',
                'medical_record_number' => $order->registration->patient->medical_record_number ?? '-',
                'penjamin' => $order->registration->penjamin->name ?? '-',
                'tgl_operasi' => $order->tgl_operasi ? Carbon::parse($order->tgl_operasi)->format('d-m-Y H:i') : '-',
                'jenis_operasi' => $order->tipeOperasi->tipe ?? '-',
                'kategori_operasi' => $order->kategoriOperasi->nama_kategori ?? '-',
                'diagnosa_awal' => $order->diagnosa_awal,
                'ruangan' => $order->ruangan->ruangan ?? '-',
                'dokter' => $order->doctor->name ?? '-',
                'user_entry' => $order->user->name ?? '-',
                'prosedur_operasi' => $order->prosedurOperasi->map(function ($prosedur) {
                    return [
                        'tindakan' => $prosedur->tindakanOperasi->nama_tindakan ?? '-',
                        'dokter_operator' => $prosedur->dokterOperator->name ?? '-',
                        'ass_dokter_operator' => $prosedur->assDokterOperator->name ?? '-',
                        'dokter_anestesi' => $prosedur->dokterAnestesi->name ?? '-',
                        'ass_dokter_anestesi' => $prosedur->assDokterAnestesi->name ?? '-'
                    ];
                })->toArray()
            ];

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data detail: ' . $e->getMessage()
            ], 500);
        }
    }

    private function createOperationBilling(ProsedurOperasi $prosedur)
    {
        Log::info("============== START OPERATION BILLING ==============");
        Log::info("Processing Procedure ID: {$prosedur->id}");

        // Prevent concurrent processing
        if (Cache::has('processing_prosedur_' . $prosedur->id)) {
            Log::warning("Procedure {$prosedur->id} is already being processed");
            return [
                'success' => false,
                'message' => 'Prosedur sedang diproses'
            ];
        }
        Cache::put('processing_prosedur_' . $prosedur->id, true, now()->addMinutes(5));

        DB::beginTransaction();
        try {
            // 1. Load all required relationships
            $prosedur->load([
                'orderOperasi.registration.patient',
                'orderOperasi.registration.penjamin.group_penjamin',
                'orderOperasi.kelasRawat',
                'orderOperasi.tipeOperasi',
                'tindakanOperasi.jenisOperasi',
                'dokterOperator.employee:id,fullname',
                'assDokterOperator1.employee:id,fullname',
                'assDokterOperator2.employee:id,fullname',
                'assDokterOperator3.employee:id,fullname',
                'dokterAnastesi.employee:id,fullname',
                'assDokterAnastesi.employee:id,fullname',
                'dokterResusitator.employee:id,fullname',
                'dokterTambahan1.employee:id,fullname',
                'dokterTambahan2.employee:id,fullname',
                'dokterTambahan3.employee:id,fullname',
                'dokterTambahan4.employee:id,fullname',
                'dokterTambahan5.employee:id,fullname'
            ]);

            $order = $prosedur->orderOperasi;
            $registration = $order->registration;

            // 2. Validate essential data
            if (!$order || !$registration || !$registration->penjamin || !$registration->penjamin->group_penjamin_id) {
                throw new \Exception("Data penting tidak lengkap (Order/Registration/Asuransi)");
            }

            // 3. Clean up any existing billing items for this procedure
            $existingBillingItems = TagihanPasien::where('deskripsi_sistem', 'like', 'prosedur_operasi_' . $prosedur->id . '%')
                ->get();

            if ($existingBillingItems->isNotEmpty()) {
                Log::info("Found existing billing items for procedure {$prosedur->id}, deleting them first");

                // Delete from pivot table first
                BilinganTagihanPasien::whereIn('tagihan_pasien_id', $existingBillingItems->pluck('id'))->delete();

                // Then delete the items
                TagihanPasien::whereIn('id', $existingBillingItems->pluck('id'))->delete();

                // Update billing total
                $totalToDeduct = $existingBillingItems->sum('wajib_bayar');
                $billing = Bilingan::where('registration_id', $registration->id)->first();
                if ($billing) {
                    $billing->decrement('wajib_bayar', $totalToDeduct);
                }
            }

            // 4. Find or create main billing record
            $billing = Bilingan::firstOrCreate(
                ['registration_id' => $registration->id],
                [
                    'patient_id' => $registration->patient_id,
                    'status' => 'belum final',
                    'wajib_bayar' => 0,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );

            // 5. Find operation tariff
            $tarif = TarifOperasi::where([
                'tindakan_operasi_id' => $prosedur->tindakan_operasi_id,
                'kelas_rawat_id' => $order->kelas_rawat_id,
                'group_penjamin_id' => $registration->penjamin->group_penjamin_id
            ])->firstOrFail();

            // 6. Prepare billing items
            $tindakanName = ($prosedur->tindakanOperasi?->jenisOperasi?->jenis ?? 'Jenis Operasi Tidak Diketahui') . ' - ' .
                ($prosedur->tindakanOperasi?->nama_operasi ?? 'Nama Operasi Tidak Diketahui');

            $tagihanItems = [];
            $totalAmount = 0;

            // Helper function to add billing items
            $addBillingItem = function ($description, $amount, $is_rs_fee = false, $role = null)
            use (&$tagihanItems, &$totalAmount, $registration, $prosedur, $billing) {
                if ($amount <= 0) return;

                $finalDescription = $is_rs_fee
                    ? "[Biaya OT] {$description} (Jasa RS)"
                    : "[Biaya OT] {$description}";

                // Generate unique descriptor
                $descriptor = 'prosedur_operasi_' . $prosedur->id;
                if ($role) {
                    $descriptor .= '_' . $role;
                }

                $tagihanItems[] = [
                    'bilingan_id' => $billing->id,
                    'user_id' => auth()->id(),
                    'registration_id' => $registration->id,
                    'date' => now()->toDateString(),
                    'tagihan' => $finalDescription,
                    'deskripsi_sistem' => $descriptor,
                    'quantity' => 1,
                    'nominal' => $amount,
                    'nominal_awal' => $amount,
                    'wajib_bayar' => $amount,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                $totalAmount += $amount;
            };

            // Add CITO tag based on tipe_operasi - DIPERBAIKI TAG
            $citoTag = '';
            if ($order->tipeOperasi) {
                $tipeTeks = strtolower($order->tipeOperasi->tipe);
                if (
                    stripos($tipeTeks, 'emergency') !== false ||
                    stripos($tipeTeks, 'cito') !== false ||
                    stripos($tipeTeks, 'urgent') !== false
                ) {
                    $citoTag = ' [CITO]';
                }
            }

            // 7. Add mandatory fees (RUANG OPERASI dan ALAT OPERASI)
            if ($tarif->ruang_operasi > 0) {
                $this->addOtherFee($addBillingItem, $tindakanName, 'RUANG OPERASI', $tarif->ruang_operasi, 'ruang_operasi');
            }

            if ($tarif->alat_rs > 0) {
                $this->addOtherFee($addBillingItem, $tindakanName, 'ALAT OPERASI', $tarif->alat_rs, 'alat_rs');
            }

            // 8. Main Doctor Fees
            $this->addDoctorFeeWithName(
                $addBillingItem,
                $prosedur->dokter_operator_id,
                $prosedur->dokterOperator,
                $tindakanName,
                'operator',
                $tarif->operator_dokter,
                $citoTag,
                'operator'
            );

            $this->addDoctorFeeWithName(
                $addBillingItem,
                $prosedur->dokter_anastesi_id,
                $prosedur->dokterAnastesi,
                $tindakanName,
                'anastesi',
                $tarif->operator_anastesi_dokter,
                $citoTag,
                'anestesi'
            );

            $this->addDoctorFeeWithName(
                $addBillingItem,
                $prosedur->dokter_resusitator_id,
                $prosedur->dokterResusitator,
                $tindakanName,
                'resusitator',
                $tarif->operator_resusitator_dokter,
                $citoTag,
                'resusitator'
            );

            // 9. Assistant Doctors (Asisten Operator) - DIPERBAIKI NAMA DOKTER
            if ($prosedur->ass_dokter_operator_1_id && $prosedur->assDokterOperator1) {
                $this->addDoctorFeeWithName(
                    $addBillingItem,
                    $prosedur->ass_dokter_operator_1_id,
                    $prosedur->assDokterOperator1,
                    $tindakanName,
                    'ass operator',
                    $tarif->asisten_operator_1_dokter,
                    $citoTag,
                    'ass_op1'
                );
            }

            if ($prosedur->ass_dokter_operator_2_id && $prosedur->assDokterOperator2) {
                $this->addDoctorFeeWithName(
                    $addBillingItem,
                    $prosedur->ass_dokter_operator_2_id,
                    $prosedur->assDokterOperator2,
                    $tindakanName,
                    'ass operator',
                    $tarif->asisten_operator_2_dokter,
                    $citoTag,
                    'ass_op2'
                );
            }

            if ($prosedur->ass_dokter_operator_3_id && $prosedur->assDokterOperator3) {
                $this->addDoctorFeeWithName(
                    $addBillingItem,
                    $prosedur->ass_dokter_operator_3_id,
                    $prosedur->assDokterOperator3,
                    $tindakanName,
                    'ass operator',
                    $tarif->asisten_operator_3_dokter,
                    $citoTag,
                    'ass_op3'
                );
            }

            // 10. Main Anesthesia Assistant - DIPERBAIKI NAMA DOKTER
            if ($prosedur->ass_dokter_anastesi_id && $prosedur->assDokterAnastesi) {
                $this->addDoctorFeeWithName(
                    $addBillingItem,
                    $prosedur->ass_dokter_anastesi_id,
                    $prosedur->assDokterAnastesi,
                    $tindakanName,
                    'ass anastesi',
                    $tarif->asisten_anastesi_1_dokter,
                    $citoTag,
                    'ass_anes'
                );
            }

            // 11. Additional Anesthesia Assistants (Dokter Tambahan 1-5 = Ass Anestesi Tambahan) - DIPERBAIKI
            if ($prosedur->dokter_tambahan_1_id && $prosedur->dokterTambahan1) {
                $this->addDoctorFeeWithName(
                    $addBillingItem,
                    $prosedur->dokter_tambahan_1_id,
                    $prosedur->dokterTambahan1,
                    $tindakanName,
                    'ass anastesi',
                    $tarif->asisten_anastesi_2_dokter ?? 0,
                    $citoTag,
                    'tambahan_1'
                );
            }

            if ($prosedur->dokter_tambahan_2_id && $prosedur->dokterTambahan2) {
                $this->addDoctorFeeWithName(
                    $addBillingItem,
                    $prosedur->dokter_tambahan_2_id,
                    $prosedur->dokterTambahan2,
                    $tindakanName,
                    'ass anastesi',
                    $tarif->dokter_tambahan_1_dokter ?? 0,
                    $citoTag,
                    'tambahan_2'
                );
            }

            if ($prosedur->dokter_tambahan_3_id && $prosedur->dokterTambahan3) {
                $this->addDoctorFeeWithName(
                    $addBillingItem,
                    $prosedur->dokter_tambahan_3_id,
                    $prosedur->dokterTambahan3,
                    $tindakanName,
                    'ass anastesi',
                    $tarif->dokter_tambahan_2_dokter ?? 0,
                    $citoTag,
                    'tambahan_3'
                );
            }

            if ($prosedur->dokter_tambahan_4_id && $prosedur->dokterTambahan4) {
                $this->addDoctorFeeWithName(
                    $addBillingItem,
                    $prosedur->dokter_tambahan_4_id,
                    $prosedur->dokterTambahan4,
                    $tindakanName,
                    'ass anastesi',
                    $tarif->dokter_tambahan_3_dokter ?? 0,
                    $citoTag,
                    'tambahan_4'
                );
            }

            if ($prosedur->dokter_tambahan_5_id && $prosedur->dokterTambahan5) {
                $this->addDoctorFeeWithName(
                    $addBillingItem,
                    $prosedur->dokter_tambahan_5_id,
                    $prosedur->dokterTambahan5,
                    $tindakanName,
                    'ass anastesi',
                    $tarif->dokter_tambahan_4_dokter ?? 0,
                    $citoTag,
                    'tambahan_5'
                );
            }

            // 12. Hospital Fees (Jasa RS)
            if ($tarif->operator_rs > 0 && $prosedur->dokter_operator_id) {
                $this->addHospitalFee(
                    $addBillingItem,
                    $tindakanName,
                    'Operator',
                    $tarif->operator_rs,
                    $citoTag,
                    'rs_operator'
                );
            }

            if ($tarif->asisten_operator_1_rs > 0 && $prosedur->ass_dokter_operator_1_id) {
                $this->addHospitalFee(
                    $addBillingItem,
                    $tindakanName,
                    'Ass Operator 1',
                    $tarif->asisten_operator_1_rs,
                    $citoTag,
                    'rs_ass_op1'
                );
            }

            if ($tarif->asisten_anastesi_1_rs > 0 && $prosedur->ass_dokter_anastesi_id) {
                $this->addHospitalFee(
                    $addBillingItem,
                    $tindakanName,
                    'Ass Anestesi',
                    $tarif->asisten_anastesi_1_rs,
                    $citoTag,
                    'rs_ass_anes'
                );
            }

            // 13. Other Fees
            if ($tarif->bmhp > 0) {
                $this->addOtherFee(
                    $addBillingItem,
                    $tindakanName,
                    'BMHP',
                    $tarif->bmhp,
                    'bmhp'
                );
            }

            if ($tarif->alat_dokter > 0) {
                $this->addOtherFee(
                    $addBillingItem,
                    $tindakanName,
                    'Jasa Alat (Dokter)',
                    $tarif->alat_dokter,
                    'alat_dokter'
                );
            }

            // 14. Validate billing items
            if (empty($tagihanItems)) {
                throw new \Exception("Tidak ada item tagihan yang dihasilkan");
            }

            // 15. Create billing records
            TagihanPasien::insert($tagihanItems);

            // Get inserted IDs
            $firstId = DB::getPdo()->lastInsertId();
            $insertedIds = range($firstId, $firstId + count($tagihanItems) - 1);

            // Create billing-item relationships
            $bilinganTagihanItems = array_map(function ($id) use ($billing) {
                return [
                    'tagihan_pasien_id' => $id,
                    'bilingan_id' => $billing->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }, $insertedIds);

            BilinganTagihanPasien::insert($bilinganTagihanItems);

            // Update billing total
            $billing->update([
                'wajib_bayar' => DB::raw("wajib_bayar + {$totalAmount}"),
                'updated_at' => now()
            ]);

            DB::commit();
            Cache::forget('processing_prosedur_' . $prosedur->id);

            Log::info("BILLING SUCCESSFUL - Procedure ID: {$prosedur->id}");
            Log::info("Total Amount: Rp " . number_format($totalAmount, 2));
            Log::info("Number of Items: " . count($tagihanItems));

            return [
                'success' => true,
                'message' => 'Tagihan berhasil dibuat',
                'data' => [
                    'billing_id' => $billing->id,
                    'total_amount' => $totalAmount,
                    'item_count' => count($tagihanItems)
                ]
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Cache::forget('processing_prosedur_' . $prosedur->id);
            Log::error("BILLING FAILED: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Gagal membuat tagihan: ' . $e->getMessage(),
                'error' => $e->getTraceAsString()
            ];
        } finally {
            Log::info("============== END BILLING PROCESS ==============");
        }
    }

    /**
     * Helper method to add doctor fee with name - DIPERBAIKI LOGGING
     */
    private function addDoctorFeeWithName($callback, $doctorId, $doctor, $procedureName, $role, $amount, $tags = '', $roleKey = null)
    {
        // Skip if no doctor ID or amount is invalid
        if (!$doctorId || $amount <= 0) {
            Log::info("Skipping doctor fee - ID: {$doctorId}, Amount: {$amount}, Role: {$role}");
            return;
        }

        // Validate doctor data exists
        if (!$doctor) {
            Log::warning("Doctor relation not found for ID: {$doctorId}, role: {$role}");
            return;
        }

        if (!$doctor->employee) {
            Log::warning("Employee relation not found for doctor ID: {$doctorId}, role: {$role}");
            return;
        }

        $doctorName = $doctor->employee->fullname;
        // Format: {procedureName},{role} ({doctorName}){tags}
        $description = "{$procedureName},{$role} ({$doctorName}){$tags}";

        Log::info("Adding doctor fee - Role: {$role}, Doctor: {$doctorName}, Amount: {$amount}");
        $callback($description, $amount, false, $roleKey);
    }

    /**
     * Helper method to add hospital fee (Jasa RS)
     */
    private function addHospitalFee($callback, $procedureName, $service, $amount, $tags = '', $roleKey = null)
    {
        if ($amount <= 0) return;
        $description = "{$procedureName}, {$service}{$tags}";
        $callback($description, $amount, true, $roleKey);
    }

    /**
     * Helper method to add other fees (RUANG OPERASI, ALAT OPERASI, BMHP, etc.)
     */
    private function addOtherFee($callback, $procedureName, $feeType, $amount, $roleKey = null)
    {
        if ($amount <= 0) return;
        $description = "{$procedureName}, {$feeType}";
        $callback($description, $amount, false, $roleKey);
    }

    public function plasmaView()
    {
        // Ambil prosedur operasi yang statusnya 'final' untuk hari ini
        $prosedurs = ProsedurOperasi::where('status', 'final')
            ->whereHas('orderOperasi', function ($query) {
                // Filter berdasarkan tanggal operasi di tabel order_operasi
                $query->whereDate('tgl_operasi', Carbon::today());
            })
            ->with([
                // Eager load semua relasi yang dibutuhkan untuk tampilan
                'orderOperasi:id,registration_id,tgl_operasi',
                'orderOperasi.registration:id,patient_id,penjamin_id',
                'orderOperasi.registration.patient:id,name,medical_record_number,date_of_birth',
                'orderOperasi.registration.penjamin:id,nama_perusahaan',
                'tindakanOperasi:id,nama_operasi',
                'dokterOperator.employee:id,fullname',
                'assDokterOperator1.employee:id,fullname',
                'dokterAnastesi.employee:id,fullname',
                'assDokterAnastesi.employee:id,fullname',
            ])
            ->get();

        // return view baru yang akan kita buat
        return view('pages.simrs.operasi.plasma', compact('prosedurs'));
    }
}
