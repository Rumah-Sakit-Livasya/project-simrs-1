<?php

namespace App\Http\Controllers\SIMRS\Persalinan;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\Persalinan\OrderPersalinan;
// [DIHAPUS] Model OrderPersalinanDetail tidak lagi digunakan
use App\Models\SIMRS\Persalinan\OrderPersalinanDetail;
use App\Models\SIMRS\Persalinan\Persalinan;
use App\Models\SIMRS\Persalinan\KategoriPersalinan;
use App\Models\SIMRS\Persalinan\TipePersalinan;
use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\Registration;
// [DIUBAH] Ganti dengan class yang benar untuk ValidationException
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PersalinanController extends Controller
{
    // Method index dan getData tidak diubah karena sudah terlihat benar
    public function index(Request $request)
    {
        // Eager load semua relasi yang dibutuhkan untuk tampilan dan Child Row
        $query = OrderPersalinan::with([
            'registration.patient',
            'persalinan',
            'dokterBidan.employee',
            'asistenOperator.employee',
            'dokterResusitator.employee',
            'dokterAnestesi.employee',
            'asistenAnestesi.employee',
            'kelasRawat',
            'kategori',
            'tipePersalinan',
            'user'
        ]);

        // Terapkan filter dari request
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereDate('created_at', '>=', $request->tanggal_awal)
                ->whereDate('created_at', '<=', $request->tanggal_akhir);
        }

        if ($request->filled('search_number')) {
            $search = $request->search_number;
            $query->where(function ($q) use ($search) {
                $q->whereHas('registration', function ($sub) use ($search) {
                    $sub->where('no_registration', 'like', "%{$search}%");
                })->orWhereHas('registration.patient', function ($sub) use ($search) {
                    $sub->where('no_rm', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('status')) {
            if ($request->status == 'completed') {
                $query->where('melahirkan_bayi', true);
            } elseif ($request->status == 'pending') {
                $query->where('melahirkan_bayi', false);
            }
        }

        // Ambil data dan urutkan
        $orders = $query->orderBy('created_at', 'desc')->get();

        // [BARU] Ambil data registrasi aktif untuk dropdown di modal "Tambah Baru"
        $active_registrations = Registration::where('status', 'active') // Asumsi Anda punya kolom 'status'
            ->with('patient')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.simrs.persalinan.index', compact('orders', 'active_registrations'));
    }



    public function getOrderData($registrationId)
    {
        try {
            if (!$registrationId || $registrationId == 0) {
                return response()->json(['data' => []]);
            }

            $orders = OrderPersalinan::where('registration_id', $registrationId)
                ->with([
                    'registration.patient',
                    'dokterBidan.employee',
                    'tipePersalinan',
                    'kategori',
                    'persalinan', // Relasi langsung ke tindakan persalinan
                    'user'
                ]);

            return DataTables::of($orders)
                ->addColumn('tgl_order', fn($row) => $row->created_at->format('d/m/Y H:i'))
                ->addColumn('tgl_rencana', fn($row) => date('d/m/Y H:i', strtotime($row->tgl_persalinan)))
                ->addColumn('pasien', fn($row) => optional($row->registration->patient)->name)
                // [PERBAIKAN] Mengambil nama tindakan dari relasi langsung
                ->addColumn('tindakan', fn($row) => optional($row->persalinan)->nama_persalinan ?: '-')
                ->addColumn('tipe_persalinan', fn($row) => optional($row->tipePersalinan)->tipe)
                ->addColumn('kategori', fn($row) => optional($row->kategori)->nama)
                ->addColumn('dokter_bidan', fn($row) => optional(optional($row->dokterBidan)->employee)->fullname)
                ->addColumn('aksi', function ($row) {
                    return '
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-danger btn-delete-persalinan"
                                data-id="' . $row->id . '" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['aksi'])
                ->make(true);
        } catch (\Exception $e) {
            Log::error('Error in getOrderData: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat memuat data.'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            // Validasi ini sudah benar untuk logika baru
            $request->validate([
                'registration_id' => 'required|exists:registrations,id',
                'tgl_persalinan' => 'required|date',
                'kelas_rawat_id' => 'required|exists:kelas_rawat,id',
                'kategori_id' => 'required|exists:kategori_persalinan,id',
                'tipe_penggunaan_id' => 'required|exists:tipe_persalinan,id',
                'dokter_bidan_operator_id' => 'required|exists:doctors,id',
                'melahirkan_bayi' => 'required|boolean',
                'tindakan_id' => 'required|exists:persalinan,id', // Kunci perbaikan ada di sini
            ]);

            DB::transaction(function () use ($request) {
                $orderId = $request->input('order_vk_id');

                $orderData = [
                    'registration_id' => $request->registration_id,
                    'tgl_persalinan' => $request->tgl_persalinan,
                    'kelas_rawat_id' => $request->kelas_rawat_id,
                    'kategori_id' => $request->kategori_id,
                    'tipe_penggunaan_id' => $request->tipe_penggunaan_id,
                    'dokter_bidan_operator_id' => $request->dokter_bidan_operator_id,
                    'dokter_resusitator_id' => $request->dokter_resusitator_id,
                    'dokter_anestesi_id' => $request->dokter_anestesi_id,
                    'dokter_umum_id' => $request->dokter_umum_id,
                    'asisten_operator_id' => $request->asisten_operator_id,
                    'asisten_anestesi_id' => $request->asisten_anestesi_id,
                    'melahirkan_bayi' => $request->melahirkan_bayi,
                    'user_entry_id' => Auth::id(),
                    // [PERBAIKAN] Menyimpan ID langsung ke kolom persalinan_id
                    'persalinan_id' => $request->tindakan_id,
                ];

                OrderPersalinan::updateOrCreate(['id' => $orderId], $orderData);
            });

            return response()->json(['message' => 'Order persalinan berhasil disimpan']);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Data tidak valid.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error in store: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan server.'], 500);
        }
    }

    // Method getData tidak diubah karena sudah terlihat benar
    public function getData(Request $request)
    {
        $query = OrderPersalinan::with([
            'registration.patient',
            'persalinan', // Relasi langsung
            'dokterBidan.employee',
            'dokterResusitator.employee',
            'dokterAnestesi.employee',
            'dokterUmum.employee',
            'asistenOperator.employee',
            'asistenAnestesi.employee',
            'kelasRawat',
            'kategori',
            'tipePersalinan',
            'user'
        ])->select('order_persalinan.*');

        // ... (Logika filter tidak diubah) ...
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereDate('created_at', '>=', $request->tanggal_awal)
                ->whereDate('created_at', '<=', $request->tanggal_akhir);
        }

        if ($request->filled('search_number')) {
            $search = $request->search_number;
            $query->where(function ($q) use ($search) {
                $q->whereHas('registration', function ($sub) use ($search) {
                    $sub->where('no_registration', 'like', "%{$search}%");
                })->orWhereHas('registration.patient', function ($sub) use ($search) {
                    $sub->where('no_rm', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('status')) {
            if ($request->status == 'completed') {
                $query->where('melahirkan_bayi', true);
            } elseif (in_array($request->status, ['pending', 'confirmed', 'in_progress', 'active'])) {
                $query->where('melahirkan_bayi', false);
            }
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('tgl_order', fn($row) => $row->created_at->format('d M Y H:i'))
            ->addColumn('pasien', fn($row) => "{$row->registration->patient->name}<br><small class='text-muted'>RM: {$row->registration->patient->no_rm}</small>")
            ->addColumn('no_reg', fn($row) => $row->registration->no_registration)
            ->addColumn('tindakan', fn($row) => optional($row->persalinan)->nama_persalinan ?: '-')
            ->addColumn('ruang', fn($row) => optional($row->kelasRawat)->kelas ?: '-')
            ->addColumn('dokter', fn($row) => optional(optional($row->dokterBidan)->employee)->fullname ?: '-')
            ->addColumn('user_input', fn($row) => optional($row->user)->name ?: '-')
            ->addColumn('action', function ($row) {
                $editUrl = '#';
                $deleteUrl = route('persalinan.destroy', $row->id);
                return '...'; // Tombol aksi
            })
            ->rawColumns(['pasien', 'action'])
            ->make(true);
    }

    // Method getMasterData tidak diubah karena sudah terlihat benar
    public function getMasterData($registrationId)
    {
        try {
            $registration = Registration::find($registrationId);
            if (!$registration) {
                return response()->json(['error' => 'Registration tidak ditemukan'], 404);
            }

            $data = [
                'doctors' => Doctor::select('doctors.id', 'employees.fullname as text')
                    ->join('employees', 'doctors.employee_id', '=', 'employees.id')
                    ->whereHas('employee')
                    ->orderBy('employees.fullname', 'asc')
                    ->get(),
                'kelas_rawat' => KelasRawat::select('id', 'kelas as text')->orderBy('kelas', 'asc')->get()->unique('text')->values(),
                'kategori' => KategoriPersalinan::select('id', 'nama as text')->orderBy('nama', 'asc')->get(),
                'tipe' => TipePersalinan::select('id', 'tipe as text')->orderBy('tipe', 'asc')->get(),
                'tindakan' => Persalinan::select('id', 'nama_persalinan as text')->orderBy('nama_persalinan', 'asc')->get(),
                'registration' => [
                    'id' => $registration->id,
                    'patient_name' => optional($registration->patient)->name
                ]
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Error in getMasterData: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat memuat master data.'], 500);
        }
    }

    /**
     * [PERBAIKAN] Detail Order untuk Edit
     * Disesuaikan untuk logika satu tindakan
     */
    public function show($id)
    {
        try {
            // [DIUBAH] Tidak perlu eager load details lagi
            $order = OrderPersalinan::findOrFail($id);

            // Format data untuk form
            $orderData = [
                'id' => $order->id,
                'registration_id' => $order->registration_id,
                'tgl_rencana_persalinan' => $order->tgl_persalinan,
                'kelas_rawat_id' => $order->kelas_rawat_id,
                'kategori_persalinan_id' => $order->kategori_id,
                'tipe_persalinan_id' => $order->tipe_penggunaan_id,
                'bidan_id' => $order->dokter_bidan_operator_id,
                'dokter_resusitator_id' => $order->dokter_resusitator_id,
                'dokter_anestesi_id' => $order->dokter_anestesi_id,
                'dokter_umum_id' => $order->dokter_umum_id,
                'asisten_operator_id' => $order->asisten_operator_id,
                'asisten_anestesi_id' => $order->asisten_anestesi_id,
                'melahirkan_bayi' => $order->melahirkan_bayi,
                // [DIUBAH] Mengirim satu ID tindakan, bukan array
                'persalinan_id' => $order->persalinan_id
            ];

            return response()->json($orderData);
        } catch (\Exception $e) {
            Log::error('Error in show: ' . $e->getMessage());
            return response()->json(['message' => 'Order tidak ditemukan.'], 404);
        }
    }

    /**
     * [PERBAIKAN] Hapus Order
     * Disederhanakan karena tidak ada tabel detail
     */
    public function destroy($id)
    {
        try {
            $order = OrderPersalinan::findOrFail($id);
            $order->delete(); // Langsung hapus order

            return response()->json([
                'message' => 'Order persalinan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in destroy: ' . $e->getMessage());
            return response()->json([
                'message' => 'Gagal menghapus order: ' . $e->getMessage()
            ], 500);
        }
    }

    public function dataBayi()
    {
        return view('pages.simrs.persalinan.data-bayi');
    }
}
