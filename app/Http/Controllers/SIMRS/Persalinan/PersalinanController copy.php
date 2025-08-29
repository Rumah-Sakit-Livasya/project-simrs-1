<?php

namespace App\Http\Controllers\SIMRS\Persalinan;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\Persalinan\OrderPersalinan;
use App\Models\SIMRS\Persalinan\Persalinan;
use App\Models\SIMRS\Persalinan\KategoriPersalinan;
use App\Models\SIMRS\Persalinan\TipePersalinan;
use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\Registration;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PersalinanController extends Controller
{
    /**
     * Display a listing of the resource with enhanced filtering
     */
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
            'dokterUmum.employee',
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
                    $sub->where('registration_number', 'like', "%{$search}%");
                })->orWhereHas('registration.patient', function ($sub) use ($search) {
                    $sub->where('medical_record_number', 'like', "%{$search}%")
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

        return view('pages.simrs.persalinan.index', compact('orders'));
    }

    /**
     * Store a newly created resource in storage
     */
    public function store(Request $request)
    {
        try {
            // Validasi request
            $request->validate([
                'registration_id' => 'required|exists:registrations,id',
                'tgl_persalinan' => 'required|date',
                'kelas_rawat_id' => 'required|exists:kelas_rawat,id',
                'kategori_id' => 'required|exists:kategori_persalinan,id',
                'tipe_penggunaan_id' => 'required|exists:tipe_persalinan,id',
                'dokter_bidan_operator_id' => 'required|exists:doctors,id',
                'melahirkan_bayi' => 'required|boolean',
                'tindakan_id' => 'required|exists:persalinan,id',
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
                    'persalinan_id' => $request->tindakan_id, // Menyimpan ID langsung
                ];

                OrderPersalinan::updateOrCreate(['id' => $orderId], $orderData);
            });

            return response()->json([
                'message' => $orderId ? 'Order persalinan berhasil diperbarui' : 'Order persalinan berhasil disimpan'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Data tidak valid.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error in store: ' . $e->getMessage());
            return response()->json([
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource for editing
     */
    public function show($id)
    {
        try {
            $order = OrderPersalinan::with([
                'registration.patient',
                'persalinan',
                'dokterBidan.employee',
                'kelasRawat',
                'kategori',
                'tipePersalinan'
            ])->findOrFail($id);

            // Format data untuk form edit
            $orderData = [
                'id' => $order->id,
                'registration_id' => $order->registration_id,
                'patient_name' => optional($order->registration->patient)->name,
                'tgl_persalinan' => $order->tgl_persalinan,
                'kelas_rawat_id' => $order->kelas_rawat_id,
                'kategori_id' => $order->kategori_id,
                'tipe_penggunaan_id' => $order->tipe_penggunaan_id,
                'dokter_bidan_operator_id' => $order->dokter_bidan_operator_id,
                'dokter_resusitator_id' => $order->dokter_resusitator_id,
                'dokter_anestesi_id' => $order->dokter_anestesi_id,
                'dokter_umum_id' => $order->dokter_umum_id,
                'asisten_operator_id' => $order->asisten_operator_id,
                'asisten_anestesi_id' => $order->asisten_anestesi_id,
                'melahirkan_bayi' => $order->melahirkan_bayi,
                'persalinan_id' => $order->persalinan_id, // Satu ID tindakan
            ];

            return response()->json($orderData);
        } catch (\Exception $e) {
            Log::error('Error in show: ' . $e->getMessage());
            return response()->json([
                'message' => 'Order tidak ditemukan.'
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage
     */
    public function destroy($id)
    {
        try {
            $order = OrderPersalinan::findOrFail($id);

            // Cek apakah ada data bayi terkait (jika ada tabel bayi)
            // Uncomment jika ada model Bayi:
            // if ($order->bayi()->count() > 0) {
            //     return response()->json([
            //         'message' => 'Tidak dapat menghapus order karena sudah ada data bayi terkait'
            //     ], 422);
            // }

            $order->delete();

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

    /**
     * Get master data for form dropdowns
     */
    public function getMasterData($registrationId)
    {
        try {
            $registration = Registration::with('patient')->find($registrationId);

            if (!$registration) {
                return response()->json([
                    'error' => 'Registration tidak ditemukan'
                ], 404);
            }

            $data = [
                'doctors' => Doctor::select('doctors.id', 'employees.fullname as text')
                    ->join('employees', 'doctors.employee_id', '=', 'employees.id')
                    ->whereHas('employee')
                    ->orderBy('employees.fullname', 'asc')
                    ->get(),

                'kelas_rawat' => KelasRawat::select('id', 'kelas as text')
                    ->orderBy('kelas', 'asc')
                    ->get()
                    ->unique('text')
                    ->values(),

                'kategori' => KategoriPersalinan::select('id', 'nama as text')
                    ->orderBy('nama', 'asc')
                    ->get(),

                'tipe' => TipePersalinan::select('id', 'tipe as text')
                    ->orderBy('tipe', 'asc')
                    ->get(),

                'tindakan' => Persalinan::select('id', 'nama_persalinan as text')
                    ->orderBy('nama_persalinan', 'asc')
                    ->get(),

                'registration' => [
                    'id' => $registration->id,
                    'patient_name' => optional($registration->patient)->name,
                    'registration_number' => $registration->registration_number
                ]
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Error in getMasterData: ' . $e->getMessage());
            return response()->json([
                'error' => 'Terjadi kesalahan saat memuat master data.'
            ], 500);
        }
    }

    /**
     * Get data for DataTables (if needed for AJAX version)
     */
    public function getData(Request $request)
    {
        try {
            $query = OrderPersalinan::with([
                'registration.patient',
                'persalinan',
                'dokterBidan.employee',
                'kelasRawat',
                'kategori',
                'tipePersalinan',
                'user'
            ])->select('order_persalinan.*');

            // Apply filters
            if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
                $query->whereDate('created_at', '>=', $request->tanggal_awal)
                    ->whereDate('created_at', '<=', $request->tanggal_akhir);
            }

            if ($request->filled('search_number')) {
                $search = $request->search_number;
                $query->where(function ($q) use ($search) {
                    $q->whereHas('registration', function ($sub) use ($search) {
                        $sub->where('registration_number', 'like', "%{$search}%");
                    })->orWhereHas('registration.patient', function ($sub) use ($search) {
                        $sub->where('medical_record_number', 'like', "%{$search}%")
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

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('tgl_order', fn($row) => $row->created_at->format('d M Y H:i'))
                ->addColumn('no_reg', fn($row) => optional($row->registration)->registration_number)
                ->addColumn('pasien', function ($row) {
                    $patient = optional($row->registration)->patient;
                    return optional($patient)->name . '<br><small class="text-muted">RM: ' . optional($patient)->medical_record_number . '</small>';
                })
                ->addColumn('tindakan', fn($row) => optional($row->persalinan)->nama_persalinan ?: '-')
                ->addColumn('kelas', fn($row) => optional($row->kelasRawat)->kelas ?: '-')
                ->addColumn('dokter', fn($row) => optional(optional($row->dokterBidan)->employee)->fullname ?: '-')
                ->addColumn('status', function ($row) {
                    return $row->melahirkan_bayi
                        ? '<span class="badge badge-success">Selesai</span>'
                        : '<span class="badge badge-warning">Belum Selesai</span>';
                })
                ->addColumn('action', function ($row) {
                    return '
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-xs btn-primary btn-tambah-order"
                                data-registration-id="' . $row->registration_id . '"
                                data-toggle="tooltip" title="Tambah Order">
                                <i class="fal fa-plus"></i>
                            </button>
                            <button type="button" class="btn btn-xs btn-info btn-data-bayi"
                                data-order-id="' . $row->id . '"
                                data-toggle="tooltip" title="Data Bayi">
                                <i class="fal fa-baby"></i>
                            </button>
                            <button type="button" class="btn btn-xs btn-outline-danger btn-delete"
                                data-url="' . route('persalinan.destroy', $row->id) . '"
                                data-toggle="tooltip" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['pasien', 'status', 'action'])
                ->make(true);
        } catch (\Exception $e) {
            Log::error('Error in getData: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat memuat data.'], 500);
        }
    }

    /**
     * Get data bayi untuk modal (placeholder method)
     */
    public function getDataBayi($orderId)
    {
        try {
            // Placeholder - ganti dengan model Bayi yang sesungguhnya
            // $bayi = Bayi::where('order_persalinan_id', $orderId)->get();

            // Untuk sementara return array kosong
            $bayi = [];

            return response()->json($bayi);
        } catch (\Exception $e) {
            Log::error('Error in getDataBayi: ' . $e->getMessage());
            return response()->json([
                'error' => 'Terjadi kesalahan saat memuat data bayi.'
            ], 500);
        }
    }

    /**
     * Get order data by registration ID
     */
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
                    'persalinan',
                    'user'
                ]);

            return DataTables::of($orders)
                ->addColumn('tgl_order', fn($row) => $row->created_at->format('d/m/Y H:i'))
                ->addColumn('tgl_rencana', fn($row) => date('d/m/Y H:i', strtotime($row->tgl_persalinan)))
                ->addColumn('pasien', fn($row) => optional($row->registration->patient)->name)
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
}
