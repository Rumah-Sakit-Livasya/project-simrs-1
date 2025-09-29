<?php

namespace App\Http\Controllers;

use App\Http\Controllers\SIMRS\RegistrationController;
use App\Models\PembayaranCreditCard;
use App\Models\PembayaranTransfer;
use App\Models\SIMRS\Bilingan;
use App\Models\OrderRadiologi;
use App\Models\SIMRS\DownPayment;
use App\Models\SIMRS\Laboratorium\OrderLaboratorium;
use App\Models\SIMRS\PembayaranTagihan;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\TagihanPasien;
use App\Models\SIMRS\TutupKunjungan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class BilinganController extends Controller
{
    // Start of Selection
    public function getData($id)
    {
        try {
            // Eager load pembayaran_tagihan supaya relasi tidak dipanggil berulang
            $dataQuery = Bilingan::with('pembayaran_tagihan')->where('id', $id)->get();
            if ($dataQuery->first()->pembayaran_tagihan === null) {
                return response()->json([
                    'data' => [],
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                ]);
            }

            $data = $dataQuery->map(function ($item) {
                $item->tanggal = $item->created_at ? $item->created_at->format('Y-m-d H:i') : null;
                // Karena pembayaran_tagihan sudah di eager load, properti ini langsung tersedia
                $item->total_tagihan = $item->pembayaran_tagihan->total_tagihan;
                $item->jaminan = $item->pembayaran_tagihan->jaminan;
                $item->tagihan_pasien = $item->pembayaran_tagihan->tagihan_pasien;
                $item->jumlah_terbayar = $item->pembayaran_tagihan->jumlah_terbayar;
                $item->sisa_tagihan = $item->pembayaran_tagihan->sisa_tagihan;
                $item->kembalian = $item->pembayaran_tagihan->kembalian;
                $item->print =
                    '<button class="btn btn-outline-primary btn-sm mt-1 btn-icon waves-effect waves-themed btn-print-bill" title="Print Bill" data-billing-id="' . $item->id . '"><i class="fal fa-print"></i></button> ' .
                    '<button class="btn btn-outline-success btn-sm mt-1 btn-icon waves-effect waves-themed badge-sm btn-print-kwitansi" title="Print Kwitansi" data-billing-id="' . $item->id . '"><i class="fal fa-print"></i></button> ' .
                    '<span class="btn btn-outline-warning btn-sm mt-1 btn-icon waves-effect waves-themed badge-sm btn-print-kwitansi-penjamin" title="Print Kwitansi Penjamin" data-billing-id="' . $item->id . '"><i class="fal fa-print"></i></span>';
                $item->aksi = '
                    <div class="d-flex flex-column flex-md-row align-items-stretch gap-2">
                        <button
                            class="btn btn-outline-danger btn-sm btn-icon waves-effect waves-themed btn-cancel-bill w-100 w-md-auto mr-3"
                            title="Batalkan Bill"
                            data-billing-id="' . $item->id . '"
                        >
                            <i class="fal fa-times-circle"></i>
                            <span class="d-none d-md-inline ms-1">Cancel Bill</span>
                        </button>
                ';
                return $item;
            });

            return response()->json([
                'data' => $data,
                'recordsTotal' => $data->count(),
                'recordsFiltered' => $data->count(),
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching data for DataTables: ' . $e->getMessage());
            return response()->json(['error' => 'Data could not be retrieved 1: ' . $e->getMessage()], 500);
        }
    }

    public function getDownPaymentData($id)
    {
        try {
            // 1. Buat query builder, jangan langsung ->get().
            // Eager load relasi untuk performa yang lebih baik (mencegah N+1 problem).
            $query = DownPayment::with('user.employee')
                ->where('bilingan_id', $id)
                ->latest(); // Urutkan berdasarkan yang terbaru

            // 2. Serahkan query ke DataTables untuk diproses
            return DataTables::of($query)
                ->addColumn('tanggal', function ($row) {
                    return $row->created_at->format('Y-m-d H:i:s');
                })
                ->addColumn('user_input', function ($row) {
                    // Gunakan optional chaining (?->) untuk keamanan jika relasi tidak ada
                    return $row->user?->employee?->fullname ?? 'N/A';
                })
                // ==========================================================
                // PERBAIKAN UTAMA DI SINI
                // ==========================================================
                ->addColumn('nominal', function ($row) {
                    // KOLOM INI UNTUK TAMPILAN (DISPLAY): Diberi format Rupiah dan tanda minus jika refund.
                    $nominal = number_format($row->nominal, 0, ',', '.');
                    return $row->tipe === 'DP Refund' ? '-' . $nominal : $nominal;
                })
                ->addColumn('nominal_raw', function ($row) {
                    // KOLOM INI MENTAH UNTUK KALKULASI DI JAVASCRIPT.
                    return $row->nominal;
                })
                // ==========================================================
                ->make(true);
        } catch (\Exception $e) {
            Log::error('Error fetching Down Payment data for DataTables: ' . $e->getMessage());
            // Mengembalikan response error yang sesuai dengan format DataTables
            return response()->json(['error' => 'Data could not be retrieved.'], 500);
        }
    }

    /**
     * Cancel a payment by resetting the is_paid flag on the bilingan,
     * deleting the associated payment record, and also deleting all pembayaran tagihan.
     *
     * @param int $id The Bilingan ID
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * Cancel all payments for a given Bilingan (by Bilingan ID).
     *
     * @param int $id The Bilingan ID
     * @return \Illuminate\Http\JsonResponse
     */
    // public function cancelPayment($id)
    // {
    //     DB::beginTransaction();
    //     try {
    //         // Ambil data Bilingan berdasarkan ID
    //         $bilingan = Bilingan::with(['pembayaran_tagihan', 'tagihanPasien'])->findOrFail($id);

    //         // Cek apakah ada pembayaran tagihan yang bisa dibatalkan
    //         if ($bilingan->pembayaran_tagihan->count() === 0) {
    //             return response()->json(['message' => 'Tidak ada pembayaran yang bisa dibatalkan.'], 404);
    //         }


    //         // Hapus semua pembayaran tagihan yang berelasi dengan bilingan ini
    //         // Set semua pembayaran tagihan jumlah_terbayar menjadi 0
    //         // Set jumlah_terbayar pada pembayaran_tagihan menjadi null tanpa foreach
    //         $bilingan->pembayaran_tagihan->jumlah_terbayar = 0;
    //         $bilingan->pembayaran_tagihan->save();

    //         // Set is_paid pada bilingan menjadi 0 (belum dibayar)
    //         $bilingan->is_paid = 1;
    //         $bilingan->save();

    //         // Set is_paid pada semua tagihan pasien yang terkait menjadi 0
    //         if ($bilingan->tagihanPasien) {
    //             foreach ($bilingan->tagihanPasien as $tagihan) {
    //                 $tagihan->is_paid = 0;
    //                 $tagihan->save();
    //             }
    //         }

    //         DB::commit();

    //         return response()->json(['success' => true, 'message' => 'Pembayaran dan pembayaran tagihan berhasil dibatalkan.']);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         Log::error('Error canceling payment: ' . $e->getMessage());
    //         return response()->json(['message' => 'Gagal membatalkan pembayaran.'], 500);
    //     }
    // }

    /**
     * Cancel a bill by reverting its status from 'final' to 'draft'.
     *
     * @param int $id The Bilingan ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelBill($id)
    {
        try {
            DB::beginTransaction();

            $bilingan = Bilingan::with('pembayaran_tagihan')->findOrFail($id);

            // Hanya bisa membatalkan tagihan dengan status 'final'
            if (strtolower($bilingan->status) !== 'final') {
                return response()->json(['message' => 'Hanya tagihan dengan status "Final" yang bisa dibatalkan.'], 422);
            }

            // Hapus semua pembayaran tagihan yang berelasi dengan bilingan ini
            if ($bilingan->pembayaran_tagihan && $bilingan->pembayaran_tagihan->count() > 0) {
                $bilingan->pembayaran_tagihan()->delete();
            }

            // Kembalikan status ke 'belum final'
            $bilingan->status = 'belum final';
            $bilingan->is_paid = 0;
            $bilingan->save();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Status tagihan berhasil dikembalikan ke Draft dan pembayaran tagihan dihapus.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error canceling bill: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal membatalkan tagihan.'], 500);
        }
    }

    public function storeDownPayment(Request $request)
    {
        $validatedData = $request->validate([
            'bilingan_id' => 'required|integer',
            'user_id' => 'required|integer',
            'metode_pembayaran' => 'required|string',
            'nominal' => 'required|numeric',
            'keterangan' => 'nullable|string',
        ]);
        $validatedData['tipe'] = "Down Payment";

        try {
            $downPayment = DownPayment::create($validatedData);
            return response()->json([
                'success' => 'Down Payment record stored successfully.',
                'data' => $downPayment,
            ]);
        } catch (\Exception $e) {
            Log::error('Error storing DownPayment: ' . $e->getMessage());
            return response()->json([
                'error' => 'Down Payment record could not be stored. Reason: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyDownPayment($id)
    {
        try {
            $downPayment = DownPayment::findOrFail($id);
            $downPayment->delete();
            return response()->json(['success' => 'Down Payment record deleted successfully.']);
        } catch (\Exception $e) {
            Log::error('Error deleting DownPayment: ' . $e->getMessage());
            return response()->json(['error' => 'Down Payment record could not be deleted. Reason: ' . $e->getMessage()], 500);
        }
    }

    public function updateBilinganStatus(Request $request, $id)
    {
        $validatedData = $request->validate([
            'status' => 'required',
            'wajib_bayar' => 'required',
        ]);

        try {
            // Update the related bilingan record if exists
            $bilingan = Bilingan::find($id);
            if ($bilingan) {
                $bilingan->update($validatedData);
            } else {
                return response()->json(['error' => 'Record not found.'], 404);
            }

            if ($validatedData['status'] == 'final') {
                // Find the registration record
                $registration = Registration::findOrFail($bilingan->registration->id);

                if ($registration['registration_type'] == 'rawat-inap') {
                    $registrationController = new RegistrationController();
                    $registrationController->removePatientFromBed($registration->patient->bed->id, $registration->patient->id);
                }

                // Create a new BatalRegister entry
                TutupKunjungan::create([
                    'registration_id' => $registration->id,
                    'user_id' => auth()->user()->id,
                    'alasan_keluar' => "Sembuh",
                    'lp_manual' => '',
                    'proses_keluar' => "Perintah Dokter",
                ]);

                // Update the status of the registration
                $registration->update([
                    'status' => 'tutup_kunjungan',
                    'registration_close_date' => Carbon::now()
                ]);
            }

            return response()->json(['success' => 'Data updated successfully.']);
        } catch (\Exception $e) {
            Log::error('Error updating data: ' . $e->getMessage());
            return response()->json(['error' => 'Data could not be updated. Reason: ' . $e->getMessage()], 500);
        }
    }

    // public function storePembayaranTagihan(Request $request)
    // {
    //     // Cek request
    //     // return dd($request->all());

    //     $validatedData = $request->validate([
    //         'user_id'           => 'required|integer',
    //         'bilingan_id'       => 'required|integer',
    //         'tagihan_pasien'    => 'required|numeric',
    //         'total_tagihan'     => 'required|numeric',
    //         'jaminan'           => 'nullable|numeric',
    //         'jumlah_terbayar'   => 'required|numeric',
    //         'sisa_tagihan'      => 'required|numeric',
    //         'kembalian'         => 'nullable|numeric',
    //         'keterangan'        => 'nullable|string',
    //         'bill_notes'        => 'nullable|string',
    //     ]);

    //     $validatedData['jaminan'] = $validatedData['jaminan'] ?? 0;

    //     try {
    //         //     // Simpan pembayaran utama
    //         $pembayaranTagihan = PembayaranTagihan::create($validatedData);

    //         //     // Simpan data kartu kredit
    //         //     if ($request->has('bank_perusahaan_id_cc')) {
    //         //         foreach ($request->bank_perusahaan_id_cc as $index => $bankId) {
    //         //             $creditCardData = [
    //         //                 'pembayaran_tagihan_id' => $pembayaranTagihan->id,
    //         //                 'bank_perusahaan_id'    => $bankId ?? '-',
    //         //                 'tipe'                  => $request->tipe_cc[$index] ?? '-',
    //         //                 'cc_number'             => $request->cc_number_cc[$index] ?? '-',
    //         //                 'auth_number'          => $request->auth_number_cc[$index] ?? '-',
    //         //                 'batch'                 => $request->batch_cc[$index] ?? '-',
    //         //                 'nominal'               => $request->nominal_cc[$index] ?? 0,
    //         //             ];

    //         //             // Filter out '-' values if you want to allow database defaults to take over
    //         //             $creditCardData = array_filter($creditCardData, function ($value) {
    //         //                 return $value !== null;
    //         //             });

    //         //             PembayaranCreditCard::create($creditCardData);
    //         //         }
    //         //     }

    //         //     // Simpan data transfer
    //         //     if ($request->filled('bank_perusahaan_id_tf') && $request->filled('nominal_tf')) {
    //         //         $transferData = [
    //         //             'pembayaran_tagihan_id' => $pembayaranTagihan->id,
    //         //             'bank_perusahaan_id'    => $request->bank_perusahaan_id_tf,
    //         //             'bank_pengirim'         => $request->bank_pengirim_tf ?? null,
    //         //             'norek_pengirim'        => $request->norek_pengirim_tf ?? null,
    //         //             'nominal'               => $request->nominal_tf,
    //         //         ];

    //         //         // Filter out null values if needed
    //         //         $transferData = array_filter($transferData, function ($value) {
    //         //             return $value !== null;
    //         //         });

    //         //         PembayaranTransfer::create($transferData);
    //         //     }

    //         //     // Update status pembayaran di tabel Bilingan
    //         $bilingan = Bilingan::find($validatedData['bilingan_id']);
    //         if ($bilingan) {
    //             $updateData = ['is_paid' => 1];
    //             if (isset($validatedData['keterangan'])) {
    //                 $updateData['keterangan'] = $validatedData['keterangan'];
    //             }
    //             $bilingan->update($updateData);
    //         }

    //         if ($bilingan->registration) {
    //             $registrationId = $bilingan->registration->id;

    //             // Update semua 'order_radiologi' yang terkait dengan registrasi ini
    //             OrderRadiologi::where('registration_id', $registrationId)
    //                 ->update(['status_billed' => true]);
    //         }
    //         if ($bilingan->registration) {
    //             $registrationId = $bilingan->registration->id;

    //             OrderLaboratorium::where('registration_id', $registrationId)
    //                 ->update(['status_billed' => true]);
    //         }

    //         return response()->json([
    //             'success' => 'Pembayaran Tagihan berhasil disimpan.',
    //             'data'    => $pembayaranTagihan,
    //         ]);
    //     } catch (\Exception $e) {
    //         Log::error('Error storing Pembayaran Tagihan: ' . $e->getMessage());
    //         return response()->json([
    //             'error' => 'Pembayaran Tagihan gagal disimpan. Alasan: ' . $e->getMessage(),
    //         ], 500);
    //     }
    // }

    public function storePembayaranTagihan(Request $request)
    {
        // Validate request data
        $validatedData = $request->validate([
            'user_id'           => 'required|integer',
            'bilingan_id'       => 'required|integer',
            'tagihan_pasien'    => 'required|numeric',
            'total_tagihan'     => 'required|numeric',
            'jaminan'           => 'nullable|numeric',
            'jumlah_terbayar'   => 'required|numeric',
            'sisa_tagihan'      => 'required|numeric',
            'kembalian'         => 'nullable|numeric',
            'keterangan'        => 'nullable|string',
            'bill_notes'        => 'nullable|string',
        ]);

        // Set default for jaminan
        $validatedData['jaminan'] = $validatedData['jaminan'] ?? 0;

        // Generate unique no_transaksi
        $latestPayment = PembayaranTagihan::orderBy('no_transaksi', 'desc')->first();
        $nextSequence = $latestPayment ? ((int) substr($latestPayment->no_transaksi, 2) + 1) : 1;
        $validatedData['no_transaksi'] = 250000000 + $nextSequence; // e.g., 250000001, 250000002, ...

        try {
            // Save main payment record
            $pembayaranTagihan = PembayaranTagihan::create($validatedData);

            // Update Bilingan status
            $bilingan = Bilingan::find($validatedData['bilingan_id']);
            if ($bilingan) {
                $updateData = ['is_paid' => 1];
                if (isset($validatedData['keterangan'])) {
                    $updateData['keterangan'] = $validatedData['keterangan'];
                }
                $bilingan->update($updateData);
            }

            // Update related OrderRadiologi and OrderLaboratorium
            if ($bilingan && $bilingan->registration) {
                $registrationId = $bilingan->registration->id;

                OrderRadiologi::where('registration_id', $registrationId)
                    ->update(['status_billed' => true]);

                OrderLaboratorium::where('registration_id', $registrationId)
                    ->update(['status_billed' => true]);
            }

            // Update is_paid pada table tagihan menjadi 1
            if ($bilingan && $bilingan->tagihanPasien) {
                foreach ($bilingan->tagihanPasien as $tagihan) {
                    $tagihan->update(['is_paid' => 1]);
                }
            }

            return response()->json([
                'success' => 'Pembayaran Tagihan berhasil disimpan.',
                'data'    => $pembayaranTagihan,
            ]);
        } catch (\Exception $e) {
            Log::error('Error storing Pembayaran Tagihan: ' . $e->getMessage());
            return response()->json([
                'error' => 'Pembayaran Tagihan gagal disimpan. Alasan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function printBill($id)
    {
        // Eager load SEMUA relasi yang dibutuhkan oleh view 'print-bill'
        $bilingan = Bilingan::with([
            'registration.patient',
            'registration.penjamin',
            'registration.doctor.employee',
            'registration.departement',
            'registration.kelas_rawat',
            'pembayaran_tagihan',
            'tagihanPasien',
            'downPayment'
        ])->findOrFail($id);

        return view('pages.simrs.keuangan.kasir.partials.print-bill', compact('bilingan'));
    }

    public function printKwitansi($id)
    {
        $bilingan = Bilingan::with('pembayaran_tagihan')->findOrFail($id);
        return view('pages.simrs.keuangan.kasir.partials.print-kwitansi', compact('bilingan'));
    }

    /**
     * NEW METHOD: Fetch unbilled orders for the notification popup.
     */
    public function getOrderNotifications($registration_id)
    {
        // Fetch unbilled lab orders
        $labOrders = OrderLaboratorium::with(['order_parameter_laboratorium.parameter_laboratorium'])
            ->where('registration_id', $registration_id)
            ->where('is_konfirmasi', 0)
            ->get()
            ->flatMap(function ($order) {
                // Setiap order bisa punya banyak order_parameter_laboratorium
                return $order->order_parameter_laboratorium->map(function ($detail) use ($order) {
                    return [
                        'id' => $order->id,
                        'type' => 'laboratorium',
                        'title' => 'Lab: ' . ($detail->parameter_laboratorium->parameter ?? 'Tindakan tidak ditemukan'),
                        'time' => $order->created_at->format('d M Y H:i'),
                        'nominal' => $detail->nominal_rupiah ?? 0,
                    ];
                });
            });

        // Fetch unbilled radiology orders
        $radOrders = OrderRadiologi::with(['order_parameter_radiologi.parameter_radiologi.tarif_parameter_radiologi'])
            ->where('registration_id', $registration_id)
            ->where('status_billed', 0)
            ->get()
            ->flatMap(function ($order) {
                // Setiap order bisa punya banyak order_parameter_radiologi
                return $order->order_parameter_radiologi->map(function ($detail) use ($order) {
                    $parameter = $detail->parameter_radiologi;
                    $tarif = $parameter?->tarif_parameter_radiologi?->first()?->total ?? 0;
                    return [
                        'id' => $order->id,
                        'type' => 'radiologi',
                        'title' => 'Rad: ' . ($parameter?->parameter ?? 'Tindakan tidak ditemukan'),
                        'time' => $order->created_at->format('d M Y H:i'),
                        'nominal' => $tarif,
                    ];
                });
            });

        // Merge and sort the collections by time
        $allOrders = $labOrders->merge($radOrders)->sortByDesc('time')->values();

        return response()->json($allOrders);
    }

    /**
     * NEW METHOD: Process an order and create a new TagihanPasien.
     */
    public function processOrderIntoBill(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer',
            'order_type' => 'required|string|in:laboratorium,radiologi',
            'bilingan_id' => 'required|integer|exists:bilingan,id',
        ]);

        DB::beginTransaction();
        try {
            $bilingan = Bilingan::with('registration')->findOrFail($request->bilingan_id);
            $order = null;
            $tagihanDetail = '';
            $nominal = 0;

            if ($request->order_type === 'laboratorium') {
                // Ambil order laboratorium beserta detail parameter dan tarif
                $order = OrderLaboratorium::with([
                    'order_parameter_laboratorium.parameter_laboratorium.tarif_parameter_laboratorium'
                ])->findOrFail($request->order_id);

                // Ambil semua detail parameter order
                $details = $order->order_parameter_laboratorium;

                // Untuk setiap detail, buat tagihan (jika lebih dari satu parameter)
                foreach ($details as $detail) {
                    $parameter = $detail->parameter_laboratorium;
                    $tarif = $parameter?->tarif_parameter_laboratorium?->first()?->total ?? 0;
                    $tagihanDetail = '[Lab] ' . ($parameter?->parameter ?? 'Tindakan tidak ditemukan');
                    $nominal = $tarif;

                    // Cek apakah sudah ditagihkan
                    if ($order->status_billed) {
                        return response()->json(['message' => 'Order ini sudah ditagihkan.'], 422);
                    }

                    TagihanPasien::create([
                        'user_id' => auth()->id(),
                        'bilingan_id' => $bilingan->id,
                        'registration_id' => $bilingan->registration->id,
                        'date' => now()->format('Y-m-d H:i:s'),
                        'tagihan' => $tagihanDetail,
                        'quantity' => 1,
                        'nominal' => $nominal,
                        'nominal_awal' => $nominal,
                        'wajib_bayar' => $nominal,
                        'tipe_diskon' => 'None',
                        'disc' => 0,
                        'diskon' => 0,
                        'jamin' => 0,
                        'jaminan' => 0,
                    ]);
                }
            } else { // radiologi
                // Ambil order radiologi beserta detail parameter dan tarif
                $order = OrderRadiologi::with([
                    'order_parameter_radiologi.parameter_radiologi.tarif_parameter_radiologi'
                ])->findOrFail($request->order_id);

                $details = $order->order_parameter_radiologi;

                foreach ($details as $detail) {
                    $parameter = $detail->parameter_radiologi;
                    $tarif = $parameter?->tarif_parameter_radiologi?->first()?->total ?? 0;
                    $tagihanDetail = '[Rad] ' . ($parameter?->parameter ?? 'Tindakan tidak ditemukan');
                    $nominal = $tarif;

                    // Cek apakah sudah ditagihkan
                    if ($order->status_billed) {
                        return response()->json(['message' => 'Order ini sudah ditagihkan.'], 422);
                    }

                    TagihanPasien::create([
                        'user_id' => auth()->id(),
                        'bilingan_id' => $bilingan->id,
                        'registration_id' => $bilingan->registration->id,
                        'date' => now()->format('Y-m-d H:i:s'),
                        'tagihan' => $tagihanDetail,
                        'quantity' => 1,
                        'nominal' => $nominal,
                        'nominal_awal' => $nominal,
                        'wajib_bayar' => $nominal,
                        'tipe_diskon' => 'None',
                        'disc' => 0,
                        'diskon' => 0,
                        'jamin' => 0,
                        'jaminan' => 0,
                    ]);
                }
            }

            // Tandai order sudah ditagihkan
            $order->status_billed = 1;
            $order->save();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Order berhasil ditambahkan ke tagihan.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing order into bill: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal memproses order.'], 500);
        }
    }
}
