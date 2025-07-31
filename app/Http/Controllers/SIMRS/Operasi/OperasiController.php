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
        // Ambil data order berdasarkan $orderId
        $order = OrderOperasi::findOrFail($orderId);

        return view('pages.simrs.operasi.treatment-list', [
            'order' => $order
        ]);
    }

    public function createProsedur(OrderOperasi $order)
    {
        // Load data yang dibutuhkan untuk ditampilkan di form (header)
        $order->load('registration.patient', 'tipeOperasi', 'kelasRawat', 'kategoriOperasi', 'registration.penjamin');

        // Ambil semua data master untuk dropdown
        $jenis_operasi = JenisOperasi::all(); // contoh
        $ruangan_operasi = Room::all(); // contoh
        $doctors = Doctor::all();
        $tindakan_operasi = TindakanOperasi::all();
        $JenisOperasi = JenisOperasi::all();
        $tipe_operasi = KategoriOperasi::all();
        $kelas_rawat = KelasRawat::all();

        return view('pages.simrs.operasi.input_tindakan_ok', [
            'order' => $order,
            'doctors' => $doctors,
            'tindakan_operasi' => $tindakan_operasi,
            'Jenis_operasi' => $jenis_operasi,
            'ruangan_operasi' => $ruangan_operasi,
            'tipe_operasi' => $tipe_operasi,
            'jenis_operasi' => $jenis_operasi,
            'kelas_rawat' => $kelas_rawat,
        ]);
    }

    public function getTindakanByJenis($jenisId)
    {
        $tindakan = TindakanOperasi::where('jenis_operasi_id', $jenisId)
            ->select('id', 'kode_operasi', 'nama_operasi', 'jenis_operasi_id')
            ->get();

        return response()->json($tindakan);
    }


    // public function storeProsedur(Request $request)
    // {
    //     try {
    //         $validated = $request->validate([
    //             'order_operasi_id' => 'required|exists:order_operasi,id',

    //             // Data jadwal operasi yang bisa diubah
    //             'tgl_operasi' => 'required|date',
    //             'ruangan_id' => 'nullable|exists:rooms,id',
    //             'tipe_operasi_id' => 'required|exists:tipe_operasi,id',
    //             'tipe_penggunaan' => 'required|in:UMUM,ELEKTIF',
    //             'kelas_rawat_id' => 'required|exists:kelas_rawat,id',
    //             'kategori_operasi_id' => 'required|exists:kategori_operasi,id',
    //             'tindakan_id' => 'required|exists:tindakan_operasi,id',

    //             // Tim operasi - sesuai dengan field di migration dan form
    //             'dokter_operator_id' => 'required|exists:doctors,id',
    //             'ass_dokter_operator_id' => 'nullable|exists:doctors,id',
    //             'dokter_anastesi_id' => 'nullable|exists:doctors,id',
    //             'ass_dokter_anastesi_id' => 'required|exists:doctors,id',
    //             'dokter_resusitator_id' => 'nullable|exists:doctors,id',
    //             'dokter_tambahan_id' => 'nullable|exists:doctors,id',

    //             // Data operasi
    //             'laporan_operasi' => 'required|string|min:10',
    //             'status' => 'required|in:rencana,selesai',
    //         ], [
    //             // Custom error messages
    //             'order_operasi_id.required' => 'Order operasi tidak ditemukan.',
    //             'order_operasi_id.exists' => 'Order operasi tidak valid.',
    //             'tgl_operasi.required' => 'Tanggal operasi harus diisi.',
    //             'tgl_operasi.date' => 'Format tanggal operasi tidak valid.',
    //             'tipe_operasi_id.required' => 'Tipe operasi harus dipilih.',
    //             'tipe_penggunaan.required' => 'Tipe penggunaan harus dipilih.',
    //             'kelas_rawat_id.required' => 'Kelas rawat harus dipilih.',
    //             'kategori_operasi_id.required' => 'Kategori operasi harus dipilih.',
    //             'tindakan_id.required' => 'Tindakan operasi harus dipilih.',
    //             'tindakan_id.exists' => 'Tindakan operasi tidak valid.',
    //             'dokter_operator_id.required' => 'Dokter operator harus dipilih.',
    //             'dokter_operator_id.exists' => 'Dokter operator tidak valid.',
    //             'ass_dokter_anastesi_id.required' => 'Asisten dokter anestesi harus dipilih.',
    //             'ass_dokter_anastesi_id.exists' => 'Asisten dokter anestesi tidak valid.',
    //             'laporan_operasi.required' => 'Laporan operasi harus diisi.',
    //             'laporan_operasi.min' => 'Laporan operasi minimal 10 karakter.',
    //             'status.required' => 'Status harus dipilih.',
    //             'status.in' => 'Status tidak valid.',
    //         ]);

    //         // Update data order operasi jika ada perubahan
    //         $orderData = [
    //             'tgl_operasi' => $validated['tgl_operasi'],
    //             'ruangan_id' => $validated['ruangan_id'],
    //             'tipe_operasi_id' => $validated['tipe_operasi_id'],
    //             'kelas_rawat_id' => $validated['kelas_rawat_id'],
    //             'kategori_operasi_id' => $validated['kategori_operasi_id'],
    //         ];

    //         $order = OrderOperasi::findOrFail($validated['order_operasi_id']);
    //         $order->update($orderData);

    //         // Ambil data untuk prosedur operasi
    //         $prosedurData = collect($validated)->except([
    //             'tgl_operasi',
    //             'ruangan_id',
    //             'tipe_operasi_id',
    //             'tipe_penggunaan',
    //             'kelas_rawat_id',
    //             'kategori_operasi_id'
    //         ])->toArray();

    //         // Tambahkan field audit
    //         $prosedurData['created_by'] = auth()->id();

    //         // Set waktu mulai jika status selesai
    //         if ($prosedurData['status'] === 'selesai') {
    //             $prosedurData['waktu_mulai'] = now();
    //             $prosedurData['waktu_selesai'] = now();
    //         } else {
    //             $prosedurData['waktu_mulai'] = now();
    //         }

    //         // Buat record prosedur operasi
    //         $prosedur = ProsedurOperasi::create($prosedurData);

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Tindakan operasi berhasil disimpan.',
    //             'data' => [
    //                 'id' => $prosedur->id,
    //                 'status' => $prosedur->status
    //             ]
    //         ]);
    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         return response()->json([
    //             'success' => false,
    //             'errors' => $e->errors(),
    //             'message' => 'Data yang dimasukkan tidak valid.'
    //         ], 422);
    //     } catch (\Exception $e) {
    //         // Log error untuk debugging
    //         \Log::error('Error storing prosedur operasi: ' . $e->getMessage(), [
    //             'request_data' => $request->all(),
    //             'user_id' => auth()->id()
    //         ]);

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }


    public function storeProsedur(Request $request)
    {
        try {
            // Sementara, validasi dikendurkan untuk testing
            $validated = $request->validate([
                'order_operasi_id' => 'required|exists:order_operasi,id',

                'tgl_operasi' => 'required|date',
                'ruangan_id' => 'nullable|exists:rooms,id',
                'tipe_operasi_id' => 'required|exists:tipe_operasi,id',
                'tipe_penggunaan' => 'required|in:UMUM,ELEKTIF',
                'kelas_rawat_id' => 'required|exists:kelas_rawat,id',
                'kategori_operasi_id' => 'required|exists:kategori_operasi,id',
                // 'tindakan_id' => 'nullable|exists:tindakan_operasi,id',

                'dokter_operator_id' => 'required|exists:doctors,id',
                'ass_dokter_operator_id' => 'nullable|exists:doctors,id',
                'dokter_anastesi_id' => 'nullable|exists:doctors,id',
                'ass_dokter_anastesi_id' => 'nullable|exists:doctors,id',
                'dokter_resusitator_id' => 'nullable|exists:doctors,id',
                'dokter_tambahan_id' => 'nullable|exists:doctors,id',

                'laporan_operasi' => 'nullable|string|min:0',
                'status' => 'required|in:rencana,selesai',
            ]);

            // Data dummy jika belum dikirim
            if (empty($validated['laporan_operasi'])) {
                $validated['laporan_operasi'] = 'Belum ada laporan (dummy data)';
            }

            if (empty($validated['tindakan_id'])) {
                $validated['tindakan_id'] = 1; // isi dengan ID tindakan dummy jika perlu
            }

            // Update order_operasi
            $orderData = [
                'tgl_operasi' => $validated['tgl_operasi'],
                'ruangan_id' => $validated['ruangan_id'],
                'tipe_operasi_id' => $validated['tipe_operasi_id'],
                'kelas_rawat_id' => $validated['kelas_rawat_id'],
                'kategori_operasi_id' => $validated['kategori_operasi_id'],
            ];

            $order = OrderOperasi::findOrFail($validated['order_operasi_id']);
            $order->update($orderData);

            // Ambil sisa data untuk prosedur operasi
            $prosedurData = collect($validated)->except([
                'tgl_operasi',
                'ruangan_id',
                'tipe_operasi_id',
                'kelas_rawat_id',
                'kategori_operasi_id'
            ])->toArray();

            $prosedurData['created_by'] = auth()->id();

            if ($prosedurData['status'] === 'selesai') {
                $prosedurData['waktu_mulai'] = now();
                $prosedurData['waktu_selesai'] = now();
            } else {
                $prosedurData['waktu_mulai'] = now();
            }

            $prosedur = ProsedurOperasi::create($prosedurData);

            return response()->json([
                'success' => true,
                'message' => 'Tindakan operasi (dummy) berhasil disimpan.',
                'data' => [
                    'id' => $prosedur->id,
                    'status' => $prosedur->status
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Validasi gagal.'
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error dummy store prosedur: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

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

            // Cek apakah order sudah memiliki tindakan
            // Jika ya, mungkin tidak boleh dihapus
            // if ($order->prosedurOperasi()->count() > 0) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Order tidak dapat dihapus karena sudah memiliki tindakan.'
            //     ], 422);
            // }

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
