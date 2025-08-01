<?php

namespace App\Http\Controllers\SIMRS\Operasi;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Operasi\OrderOperasi;
use App\Models\SIMRS\Operasi\ProsedurOperasi;
use App\Models\SIMRS\Operasi\TindakanOperasi;
use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\Operasi\JenisOperasi;
use App\Models\SIMRS\Operasi\KategoriOperasi;
use App\Models\SIMRS\Operasi\TipeOperasi;
use App\Models\SIMRS\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
                'ruangan_id' => 'nullable|string',
                'tgl_operasi' => 'required|date_format:d-m-Y H:i',
                'tipe_operasi_id' => 'required|exists:tipe_operasi,id',
                'kategori_operasi_id' => 'required|exists:kategori_operasi,id',
                'kelas_rawat_id' => 'required|exists:kelas_rawat,id',
                'diagnosa_awal' => 'required|string'
            ]);

            // Konversi format tanggal sebelum disimpan
            $validated['tgl_operasi'] = \Carbon\Carbon::createFromFormat('d-m-Y H:i', $validated['tgl_operasi'])->toDateTimeString();

            // Set default values untuk field yang wajib tapi tidak ada di form
            // $validated['doctor_id'] = 1; // atau null jika nullable
            $validated['ruangan_id'] = null;

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
            $orders = OrderOperasi::with(['registration.patient', 'tipeOperasi', 'kategoriOperasi', 'kelasRawat'])
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
        // Ambil data order dengan relasi yang diperlukan
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
                'tipe_operasi' => $prosedur->order->tipeOperasi->tipe ?? 'N/A',
                'tipe_penggunaan' => $prosedur->tipe_penggunaan ?? 'N/A',
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

            // Hanya bisa hapus jika status masih draft
            if ($prosedur->status === 'final') {
                return response()->json([
                    'success' => false,
                    'message' => 'Prosedur dengan status final tidak dapat dihapus.'
                ], 422);
            }

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

    // Method untuk mendapatkan tindakan berdasarkan kategori operasi


    public function storeProsedur(Request $request)
    {
        try {
            // Validasi data yang masuk (tetap sama)
            $validated = $request->validate([
                'order_operasi_id' => 'required|exists:order_operasi,id',
                'tgl_operasi' => 'required|date',
                'ruangan_id' => 'required|exists:rooms,id',
                'tipe_operasi_id' => 'required|exists:tipe_operasi,id',
                'tipe_penggunaan' => 'required|in:UMUM,ELEKTIF',
                'kelas_rawat_id' => 'required|exists:kelas_rawat,id',
                'kategori_operasi_id' => 'required|exists:kategori_operasi,id',
                'jenis_operasi_id' => 'required|exists:jenis_operasi,id',
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
                // Pesan error yang sama
            ]);

            // 1. Update data dasar pada tabel order_operasi (tetap sama)
            $order = OrderOperasi::findOrFail($validated['order_operasi_id']);
            $order->update([
                'tgl_operasi' => $validated['tgl_operasi'],
                'ruangan_id' => $validated['ruangan_id'],
                'tipe_operasi_id' => $validated['tipe_operasi_id'],
                'kelas_rawat_id' => $validated['kelas_rawat_id'],
                'kategori_operasi_id' => $validated['kategori_operasi_id'],
                'jenis_operasi_id' => $validated['jenis_operasi_id'],
                'status' => $validated['status'],
            ]);

            // 2. Siapkan data untuk prosedur baru
            $prosedurValues = [
                'order_operasi_id' => $validated['order_operasi_id'],
                'jenis_operasi_id' => $validated['jenis_operasi_id'],
                'tindakan_operasi_id' => $validated['tindakan_operasi_id'],
                'tipe_penggunaan' => $validated['tipe_penggunaan'],
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
                'waktu_mulai' => now(), // Set waktu mulai otomatis
            ];

            // 3. Buat prosedur baru (CREATE saja, tanpa updateOrCreate)
            $prosedur = ProsedurOperasi::create($prosedurValues);

            // 4. Jika status final, set waktu selesai
            if ($validated['status'] === 'final') {
                $prosedur->update([
                    'waktu_selesai' => now()
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Tindakan operasi baru berhasil ditambahkan sebagai ' . ucfirst($validated['status']) . '.',
                'data' => $prosedur,
                'is_new' => true // Tambahkan flag untuk membedakan create vs update
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Data yang dimasukkan tidak valid.'
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating prosedur operasi: ' . $e->getMessage(), [
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


    public function deleteOrder(Request $request)
    {
        try {
            $orderId = $request->input('id');
            $order = OrderOperasi::findOrFail($orderId);

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
}
