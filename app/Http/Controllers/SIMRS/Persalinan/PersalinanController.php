<?php

namespace App\Http\Controllers\SIMRS\Persalinan;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Bilingan;
use App\Models\SIMRS\BilinganTagihanPasien;
use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\Persalinan\OrderPersalinan;
use App\Models\SIMRS\Persalinan\Persalinan;
use App\Models\SIMRS\Persalinan\KategoriPersalinan;
use App\Models\SIMRS\Persalinan\TipePersalinan;
use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\Penjamin;
use App\Models\SIMRS\Persalinan\TarifPersalinan;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\TagihanPasien;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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
        // Eager load semua relasi yang dibutuhkan
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

        // === [LOGIKA FILTER DIPERBARUI] ===

        // Filter berdasarkan Tanggal VK (tgl_persalinan)
        if ($request->filled('tgl_vk_awal') && $request->filled('tgl_vk_akhir')) {
            $query->whereDate('tgl_persalinan', '>=', $request->tgl_vk_awal)
                ->whereDate('tgl_persalinan', '<=', $request->tgl_vk_akhir);
        }

        // Filter berdasarkan No. RM (medical_record_number)
        if ($request->filled('no_rm')) {
            $no_rm = $request->no_rm;
            $query->whereHas('registration.patient', function ($q) use ($no_rm) {
                $q->where('medical_record_number', 'like', "%{$no_rm}%");
            });
        }

        // Filter berdasarkan Nama Pasien
        if ($request->filled('nama_pasien')) {
            $nama_pasien = $request->nama_pasien;
            $query->whereHas('registration.patient', function ($q) use ($nama_pasien) {
                $q->where('name', 'like', "%{$nama_pasien}%");
            });
        }

        // [BARU] Filter berdasarkan Status Registrasi
        if ($request->filled('status_registrasi')) {
            $status = $request->status_registrasi;
            $query->whereHas('registration', function ($q) use ($status) {
                $q->where('status', $status);
            });
        }

        // === [AKHIR PERUBAHAN] ===

        // Ambil data dan urutkan
        $orders = $query->orderBy('created_at', 'desc')->get();

        return view('pages.simrs.persalinan.index', compact('orders'));
    }

    /**
     * Store a newly created resource in storage
     */
    // public function store(Request $request)
    // {
    //     try {
    //         // Validasi ini sudah benar untuk logika baru
    //         $request->validate([
    //             'registration_id' => 'required|exists:registrations,id',
    //             'tgl_persalinan' => 'required|date',
    //             'kelas_rawat_id' => 'required|exists:kelas_rawat,id',
    //             'kategori_id' => 'required|exists:kategori_persalinan,id',
    //             'tipe_penggunaan_id' => 'required|exists:tipe_persalinan,id',
    //             'dokter_bidan_operator_id' => 'required|exists:doctors,id',
    //             'melahirkan_bayi' => 'required|boolean',
    //             'tindakan_id' => 'required|exists:persalinan,id', // Kunci perbaikan ada di sini
    //         ]);

    //         DB::transaction(function () use ($request) {
    //             $orderId = $request->input('order_vk_id');

    //             $orderData = [
    //                 'registration_id' => $request->registration_id,
    //                 'tgl_persalinan' => $request->tgl_persalinan,
    //                 'kelas_rawat_id' => $request->kelas_rawat_id,
    //                 'kategori_id' => $request->kategori_id,
    //                 'tipe_penggunaan_id' => $request->tipe_penggunaan_id,
    //                 'dokter_bidan_operator_id' => $request->dokter_bidan_operator_id,
    //                 'dokter_resusitator_id' => $request->dokter_resusitator_id,
    //                 'dokter_anestesi_id' => $request->dokter_anestesi_id,
    //                 'dokter_umum_id' => $request->dokter_umum_id,
    //                 'asisten_operator_id' => $request->asisten_operator_id,
    //                 'asisten_anestesi_id' => $request->asisten_anestesi_id,
    //                 'melahirkan_bayi' => $request->melahirkan_bayi,
    //                 'user_entry_id' => Auth::id(),
    //                 // [PERBAIKAN] Menyimpan ID langsung ke kolom persalinan_id
    //                 'persalinan_id' => $request->tindakan_id,
    //             ];

    //             OrderPersalinan::updateOrCreate(['id' => $orderId], $orderData);
    //         });

    //         return response()->json(['message' => 'Order persalinan berhasil disimpan']);
    //     } catch (ValidationException $e) {
    //         return response()->json(['message' => 'Data tidak valid.', 'errors' => $e->errors()], 422);
    //     } catch (\Exception $e) {
    //         Log::error('Error in store: ' . $e->getMessage());
    //         return response()->json(['message' => 'Terjadi kesalahan server.'], 500);
    //     }
    // }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'registration_id' => 'required|exists:registrations,id',
                'tgl_persalinan' => 'required|date',
                'kelas_rawat_id' => 'required|exists:kelas_rawat,id',
                'kategori_id' => 'required|exists:kategori_persalinan,id',
                'tipe_penggunaan_id' => 'required|exists:tipe_persalinan,id',
                'dokter_bidan_operator_id' => 'required|exists:doctors,id',
                'melahirkan_bayi' => 'required|boolean',
                'tindakan_id' => 'required|exists:persalinan,id',
            ]);

            $order = null;
            DB::transaction(function () use ($request, &$order) {
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
                    'persalinan_id' => $request->tindakan_id,
                ];

                $order = OrderPersalinan::updateOrCreate(['id' => $orderId], $orderData);

                // =========================================================
                // [INTEGRASI BILLING] Panggil method untuk membuat tagihan
                // =========================================================
                $this->createPersalinanBilling($order);
            });

            return response()->json(['message' => 'Order persalinan berhasil disimpan dan tagihan telah dibuat.']);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Data tidak valid.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error in PersalinanController@store: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'Terjadi kesalahan server: ' . $e->getMessage()], 500);
        }
    }


    // =========================================================================
    // [METHOD BARU] Method Private untuk Membuat Tagihan Persalinan
    // Diadaptasi dari OperasiController
    // =========================================================================

    private function createPersalinanBilling(OrderPersalinan $order)
    {
        Log::info("============== START PERSALINAN BILLING ==============");
        Log::info("Processing Order Persalinan ID: {$order->id}");

        if (Cache::has('processing_persalinan_' . $order->id)) {
            Log::warning("Order Persalinan {$order->id} is already being processed");
            return ['success' => false, 'message' => 'Order sedang diproses'];
        }
        Cache::put('processing_persalinan_' . $order->id, true, now()->addMinutes(5));

        DB::beginTransaction();
        try {
            $order->load([
                'registration.patient',
                'registration.penjamin.group_penjamin',
                'kelasRawat',
                'persalinan.tarif', // <-- MEMUAT TARIF DARI TINDAKAN
                'dokterBidan.employee:id,fullname',
                'asistenOperator.employee:id,fullname',
                'dokterAnestesi.employee:id,fullname',
                'asistenAnestesi.employee:id,fullname',
                'dokterResusitator.employee:id,fullname',
                'dokterUmum.employee:id,fullname'
            ]);

            $registration = $order->registration;
            if (!$registration || !$registration->penjamin || !$registration->penjamin->group_penjamin_id) {
                throw new \Exception("Data registrasi/asuransi pasien tidak lengkap.");
            }

            // Clean up existing billing items for this order
            $existingBillingItems = TagihanPasien::where('deskripsi_sistem', 'like', 'order_persalinan_' . $order->id . '%')->get();
            if ($existingBillingItems->isNotEmpty()) {
                BilinganTagihanPasien::whereIn('tagihan_pasien_id', $existingBillingItems->pluck('id'))->delete();
                TagihanPasien::whereIn('id', $existingBillingItems->pluck('id'))->delete();
            }

            $billing = Bilingan::firstOrCreate(
                ['registration_id' => $registration->id],
                ['patient_id' => $registration->patient_id, 'status' => 'belum final', 'wajib_bayar' => 0]
            );

            // Find the correct tariff
            $tarif = TarifPersalinan::where([
                'persalinan_id' => $order->persalinan_id,
                'kelas_rawat_id' => $order->kelas_rawat_id,
                'group_penjamin_id' => $registration->penjamin->group_penjamin_id
            ])->firstOrFail(); // Akan error jika tarif tidak ditemukan, ini bagus untuk debugging.

            $tindakanName = $order->persalinan->nama_persalinan ?? 'Tindakan Persalinan';
            $tagihanItems = [];
            $totalAmount = 0;

            $addBillingItem = function ($description, $amount, $role = null)
            use (&$tagihanItems, &$totalAmount, $registration, $order, $billing) {
                if ($amount <= 0) return;
                $descriptor = 'order_persalinan_' . $order->id . ($role ? '_' . $role : '');
                $tagihanItems[] = [
                    'bilingan_id' => $billing->id,
                    'user_id' => auth()->id(),
                    'registration_id' => $registration->id,
                    'date' => now()->toDateString(),
                    'tagihan' => $description,
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

            // Add Doctor & Other Fees based on tarif_persalinan table
            $this->addFee($addBillingItem, $order->dokterBidan, $tindakanName, 'operator', $tarif->operator_dokter, 'operator');
            $this->addFee($addBillingItem, $order->asistenOperator, $tindakanName, 'ass operator', $tarif->ass_operator_dokter, 'ass_op');
            $this->addFee($addBillingItem, $order->dokterAnestesi, $tindakanName, 'anastesi', $tarif->anastesi_dokter, 'anestesi');
            $this->addFee($addBillingItem, $order->asistenAnestesi, $tindakanName, 'ass anastesi', $tarif->ass_anastesi_dokter, 'ass_anes');
            $this->addFee($addBillingItem, $order->dokterResusitator, $tindakanName, 'resusitator', $tarif->resusitator_dokter, 'resusitator');
            $this->addFee($addBillingItem, $order->dokterUmum, $tindakanName, 'dokter umum', $tarif->umum_dokter, 'umum');

            // Add RS & Prasarana Fees
            $addBillingItem("[Biaya Persalinan] Jasa RS, " . $tindakanName, $tarif->operator_rs, 'rs_op');
            $addBillingItem("[Biaya Persalinan] Jasa Prasarana, " . $tindakanName, $tarif->operator_prasarana, 'prasarana');
            $addBillingItem("[Biaya Persalinan] RUANGAN PERSALINAN", $tarif->ruang, 'ruang');

            if (empty($tagihanItems)) {
                throw new \Exception("Tidak ada item tagihan yang dihasilkan dari tarif.");
            }

            TagihanPasien::insert($tagihanItems);
            $billing->increment('wajib_bayar', $totalAmount);

            DB::commit();
            Cache::forget('processing_persalinan_' . $order->id);
            Log::info("BILLING PERSALINAN SUCCESSFUL - Order ID: {$order->id}, Total: {$totalAmount}");
        } catch (\Exception $e) {
            DB::rollBack();
            Cache::forget('processing_persalinan_' . $order->id);
            Log::error("BILLING PERSALINAN FAILED: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            // Re-throw exception agar ditangkap oleh controller utama dan menampilkan pesan error
            throw $e;
        } finally {
            Log::info("============== END PERSALINAN BILLING ==============");
        }
    }

    /**
     * Helper method to add doctor/assistant fees for persalinan.
     */
    private function addFee($callback, $doctor, $procedureName, $role, $amount, $roleKey)
    {
        if (!$doctor || !$doctor->employee || $amount <= 0) {
            return;
        }
        $doctorName = $doctor->employee->fullname;
        $description = "[Biaya Persalinan] {$procedureName}, {$role} [{$doctorName}]";
        $callback($description, $amount, $roleKey);
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
                // ->addColumn('tgl_order', fn($row) => $row->created_at->format('d M Y H:i'))
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
                // ->addColumn('tgl_order', fn($row) => $row->created_at->format('d/m/Y H:i'))
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

    public function laporanOrderPasien(Request $request)
    {
        // Mengambil semua data yang dibutuhkan oleh view rekap-per-tindakan.blade.php
        $doctors = Doctor::with('employee:id,fullname')
            ->whereHas('employee', fn($q) => $q->where('is_active', true))
            ->get()->filter(fn($d) => $d->employee !== null);
        $penjamins = Penjamin::where('status', true)->orderBy('nama_perusahaan')->get();
        $kelas_rawat = KelasRawat::orderBy('kelas')->get();
        $kategori_persalinan = KategoriPersalinan::orderBy('nama')->get();
        $tipe_rawat = ['RAWAT JALAN', 'RAWAT INAP', 'IGD'];

        // Me-return view dan mengirimkan semua variabel
        return view('pages.simrs.persalinan.laporan.order', [
            'doctors' => $doctors,
            'penjamins' => $penjamins,
            'kelas_rawat' => $kelas_rawat,
            'kategori_persalinan' => $kategori_persalinan,
            'tipe_rawat' => $tipe_rawat,
        ]);
    }

    /**
     * LAPORAN 1: Mengambil data dan menampilkan halaman cetak Order Pasien Persalinan.
     */
    public function printLaporanOrderPasien(Request $request)
    {
        $query = OrderPersalinan::with([
            'registration.patient',
            'registration.penjamin',
            'registration.poli',
            'persalinan', // Relasi ke tindakan persalinan
            'dokterBidan.employee',
            'dokterAnestesi.employee',
            'dokterResusitator.employee',
            'user'
        ]);

        // Filter berdasarkan tanggal persalinan
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $start = Carbon::createFromFormat('d-m-Y', $request->tanggal_awal)->startOfDay();
            $end = Carbon::createFromFormat('d-m-Y', $request->tanggal_akhir)->endOfDay();
            $query->whereBetween('tgl_order', [$start, $end]);
        }

        // Filter berdasarkan No RM atau Nama Pasien
        if ($request->filled('invoice')) {
            $query->whereHas('registration.patient', function ($q) use ($request) {
                $q->where('medical_record_number', 'like', '%' . $request->invoice . '%')
                    ->orWhere('name', 'like', '%' . $request->invoice . '%');
            });
        }

        $data = [];
        $error = null;

        try {
            $orders = $query->latest('tgl_persalinan')->get();

            $data = $orders->map(function ($order) {
                if (!$order->registration || !$order->registration->patient) return null;
                $patient = $order->registration->patient;
                $registration = $order->registration;
                $age = $patient->date_of_birth ? Carbon::parse($patient->date_of_birth)->age : 'N/A';

                return [
                    'tanggal_registrasi' => $registration->registration_date ? Carbon::parse($registration->registration_date)->format('d-m-Y') : 'N/A',
                    'registration_number' => $registration->registration_number ?? 'N/A',
                    'medical_record_number' => $patient->medical_record_number ?? 'N/A',
                    'patient_name' => $patient->name ?? 'N/A',
                    'gender' => $patient->gender ?? 'N/A',
                    'age' => $age,
                    'address' => $patient->address ?? 'N/A',
                    'poli' => $registration->poli?->nama_poli ?? $registration->poli?->name ?? 'N/A',
                    'dokter' => $order->dokterBidan?->employee?->fullname ?? 'N/A',
                    'penjamin' => $registration->penjamin?->nama_perusahaan ?? 'N/A',
                    'perujuk' => $registration->rujukan ?? 'N/A',
                    'tindakan' => $order->persalinan?->nama_persalinan ?? 'N/A',
                    'dr_resusitator' => $order->dokterResusitator?->employee?->fullname ?? 'N/A',
                    'dr_anestesi' => $order->dokterAnestesi?->employee?->fullname ?? 'N/A',
                    'petugas' => $order->user?->name ?? 'N/A',
                ];
            })->filter()->values();
        } catch (\Exception $e) {
            Log::error('Error generating Laporan Order Persalinan: ' . $e->getMessage());
            $error = 'Gagal memproses laporan: ' . $e->getMessage();
        }

        return view('pages.simrs.persalinan.laporan.order-print', [
            'orders' => $data,
            'period_start' => $request->tanggal_awal ?? now()->format('d-m-Y'),
            'period_end' => $request->tanggal_akhir ?? now()->format('d-m-Y'),
            'print_date' => now()->format('d-m-Y'),
            'error' => $error,
        ]);
    }

    /**
     * LAPORAN 2: Menampilkan halaman filter untuk Laporan Rekap Tindakan Persalinan.
     */
    public function laporanRekapTindakan(Request $request)
    {
        $doctors = Doctor::with('employee:id,fullname')
            ->whereHas('employee', fn($q) => $q->where('is_active', true))
            ->get()->filter(fn($d) => $d->employee !== null);

        $penjamins = Penjamin::where('status', true)->orderBy('nama_perusahaan')->get();
        $kelas_rawat = KelasRawat::orderBy('kelas')->get();
        $kategori_persalinan = KategoriPersalinan::orderBy('nama')->get();

        $tipe_rawat = ['RAWAT JALAN', 'RAWAT INAP', 'IGD'];

        return view('pages.simrs.persalinan.laporan.rekap-per-tindakan', [
            'doctors' => $doctors,
            'penjamins' => $penjamins,
            'kelas_rawat' => $kelas_rawat,
            'kategori_persalinan' => $kategori_persalinan,
            'tipe_rawat' => $tipe_rawat,
        ]);
    }

    /**
     * LAPORAN 2: Mengambil data dan menampilkan halaman cetak Rekap Tindakan Persalinan.
     * [VERSI PERBAIKAN LENGKAP]
     */
    public function printLaporanRekapTindakan(Request $request)
    {
        // 1. Menggunakan rentang tanggal untuk filter yang lebih fleksibel
        $start_date = Carbon::createFromFormat('d-m-Y', $request->tanggal_awal)->startOfDay();
        $end_date = Carbon::createFromFormat('d-m-Y', $request->tanggal_akhir)->endOfDay();

        $query = OrderPersalinan::query()
            ->join('persalinan', 'order_persalinan.persalinan_id', '=', 'persalinan.id')
            ->join('registrations', 'order_persalinan.registration_id', '=', 'registrations.id')
            // 2. [PERBAIKAN UTAMA] Filter berdasarkan rentang tanggal pada kolom 'tgl_order'
            ->whereBetween('order_persalinan.tgl_order', [$start_date, $end_date])
            ->whereNull('order_persalinan.deleted_at');

        // Apply filters
        if ($request->filled('tipe_rawat')) {
            $query->where('registrations.registration_type', $request->tipe_rawat);
        }
        if ($request->filled('kategori_id')) {
            $query->where('order_persalinan.kategori_id', $request->kategori_id);
        }
        if ($request->filled('kelas_rawat_id')) {
            $query->where('order_persalinan.kelas_rawat_id', $request->kelas_rawat_id);
        }
        if ($request->filled('dokter_id')) {
            $query->where('order_persalinan.dokter_bidan_operator_id', $request->dokter_id);
        }
        if ($request->filled('penjamin_id')) {
            $query->where('registrations.penjamin_id', $request->penjamin_id);
        }

        $results = [];
        $error = null;

        try {
            $results = $query->select(
                'persalinan.nama_persalinan',
                // 3. [PERBAIKAN KEDUA] Menggunakan 'tgl_order' untuk rekapitulasi bulanan
                DB::raw("SUM(CASE WHEN MONTH(order_persalinan.tgl_order) = 1 THEN 1 ELSE 0 END) as jan"),
                DB::raw("SUM(CASE WHEN MONTH(order_persalinan.tgl_order) = 2 THEN 1 ELSE 0 END) as feb"),
                DB::raw("SUM(CASE WHEN MONTH(order_persalinan.tgl_order) = 3 THEN 1 ELSE 0 END) as mar"),
                DB::raw("SUM(CASE WHEN MONTH(order_persalinan.tgl_order) = 4 THEN 1 ELSE 0 END) as apr"),
                DB::raw("SUM(CASE WHEN MONTH(order_persalinan.tgl_order) = 5 THEN 1 ELSE 0 END) as mei"),
                DB::raw("SUM(CASE WHEN MONTH(order_persalinan.tgl_order) = 6 THEN 1 ELSE 0 END) as jun"),
                DB::raw("SUM(CASE WHEN MONTH(order_persalinan.tgl_order) = 7 THEN 1 ELSE 0 END) as jul"),
                DB::raw("SUM(CASE WHEN MONTH(order_persalinan.tgl_order) = 8 THEN 1 ELSE 0 END) as agu"),
                DB::raw("SUM(CASE WHEN MONTH(order_persalinan.tgl_order) = 9 THEN 1 ELSE 0 END) as sep"),
                DB::raw("SUM(CASE WHEN MONTH(order_persalinan.tgl_order) = 10 THEN 1 ELSE 0 END) as okt"),
                DB::raw("SUM(CASE WHEN MONTH(order_persalinan.tgl_order) = 11 THEN 1 ELSE 0 END) as nov"),
                DB::raw("SUM(CASE WHEN MONTH(order_persalinan.tgl_order) = 12 THEN 1 ELSE 0 END) as des"),
                DB::raw("COUNT(order_persalinan.id) as total")
            )
                ->groupBy('persalinan.nama_persalinan')
                ->orderBy('persalinan.nama_persalinan')
                ->get();
        } catch (\Exception $e) {
            Log::error('Error generating Rekap Persalinan Report: ' . $e->getMessage());
            $error = 'Gagal memproses laporan: ' . $e->getMessage();
        }

        // 4. Menyiapkan data filter untuk ditampilkan di halaman cetak
        $filters = [
            'period_start' => $start_date->format('d-m-Y'),
            'period_end' => $end_date->format('d-m-Y'),
            'tipe_rawat' => $request->tipe_rawat ?? 'Semua',
            'kategori' => $request->filled('kategori_id') ? KategoriPersalinan::find($request->kategori_id)->nama : 'Semua',
            'kelas_rawat' => $request->filled('kelas_rawat_id') ? KelasRawat::find($request->kelas_rawat_id)->kelas : 'Semua',
            'dokter' => $request->filled('dokter_id') ? Doctor::with('employee:id,fullname')->find($request->dokter_id)->employee->fullname : 'Semua',
            'penjamin' => $request->filled('penjamin_id') ? Penjamin::find($request->penjamin_id)->nama_perusahaan : 'Semua',
        ];

        return view('pages.simrs.persalinan.laporan.rekap-per-tindakan-print', [
            'results' => $results,
            'filters' => $filters,
            'error' => $error,
            'print_date' => now()->format('d-m-Y'),
        ]);
    }
}
