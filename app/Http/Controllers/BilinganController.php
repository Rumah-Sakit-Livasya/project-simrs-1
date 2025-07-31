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
use Illuminate\Support\Facades\Log;

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
            $data = DownPayment::where('bilingan_id', $id) // Filter berdasarkan ID bilingan
                ->get() // Ambil data yang diperlukan
                ->map(function ($item) {
                    // Format data untuk DataTable
                    return [
                        'id' => $item->id, // Menampilkan nama user
                        'tanggal' => $item->created_at ? $item->created_at->format('Y-m-d') : null,
                        'metode_pembayaran' => $item->metode_pembayaran,
                        'nominal' => $item->tipe === 'DP Refund' ? '-' . $item->nominal : $item->nominal,
                        'tipe' => $item->tipe,
                        'user_input' => $item->user->employee->fullname ?? null, // Menampilkan nama user
                        'keterangan' => $item->keterangan,
                    ];
                });
            return response()->json([
                'data' => $data,
                'recordsTotal' => $data->count(),
                'recordsFiltered' => $data->count(),
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching billing list data: ' . $e->getMessage());
            return response()->json(['error' => 'Data could not be retrieved 2: ' . $e->getMessage()], 500);
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

    public function storePembayaranTagihan(Request $request)
    {
        // Cek request
        // return dd($request->all());

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

        $validatedData['jaminan'] = $validatedData['jaminan'] ?? 0;

        try {
            //     // Simpan pembayaran utama
            $pembayaranTagihan = PembayaranTagihan::create($validatedData);

            //     // Simpan data kartu kredit
            //     if ($request->has('bank_perusahaan_id_cc')) {
            //         foreach ($request->bank_perusahaan_id_cc as $index => $bankId) {
            //             $creditCardData = [
            //                 'pembayaran_tagihan_id' => $pembayaranTagihan->id,
            //                 'bank_perusahaan_id'    => $bankId ?? '-',
            //                 'tipe'                  => $request->tipe_cc[$index] ?? '-',
            //                 'cc_number'             => $request->cc_number_cc[$index] ?? '-',
            //                 'auth_number'          => $request->auth_number_cc[$index] ?? '-',
            //                 'batch'                 => $request->batch_cc[$index] ?? '-',
            //                 'nominal'               => $request->nominal_cc[$index] ?? 0,
            //             ];

            //             // Filter out '-' values if you want to allow database defaults to take over
            //             $creditCardData = array_filter($creditCardData, function ($value) {
            //                 return $value !== null;
            //             });

            //             PembayaranCreditCard::create($creditCardData);
            //         }
            //     }

            //     // Simpan data transfer
            //     if ($request->filled('bank_perusahaan_id_tf') && $request->filled('nominal_tf')) {
            //         $transferData = [
            //             'pembayaran_tagihan_id' => $pembayaranTagihan->id,
            //             'bank_perusahaan_id'    => $request->bank_perusahaan_id_tf,
            //             'bank_pengirim'         => $request->bank_pengirim_tf ?? null,
            //             'norek_pengirim'        => $request->norek_pengirim_tf ?? null,
            //             'nominal'               => $request->nominal_tf,
            //         ];

            //         // Filter out null values if needed
            //         $transferData = array_filter($transferData, function ($value) {
            //             return $value !== null;
            //         });

            //         PembayaranTransfer::create($transferData);
            //     }

            //     // Update status pembayaran di tabel Bilingan
            $bilingan = Bilingan::find($validatedData['bilingan_id']);
            if ($bilingan) {
                $updateData = ['is_paid' => 1];
                if (isset($validatedData['keterangan'])) {
                    $updateData['keterangan'] = $validatedData['keterangan'];
                }
                $bilingan->update($updateData);
            }

            if ($bilingan->registration) {
                $registrationId = $bilingan->registration->id;

                // Update semua 'order_radiologi' yang terkait dengan registrasi ini
                OrderRadiologi::where('registration_id', $registrationId)
                    ->update(['status_billed' => true]);
            }
            if ($bilingan->registration) {
                $registrationId = $bilingan->registration->id;

                OrderLaboratorium::where('registration_id', $registrationId)
                    ->update(['status_billed' => true]);
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
        $bilingan = Bilingan::with('pembayaran_tagihan')->findOrFail($id);
        return view('pages.simrs.keuangan.kasir.partials.print-bill', compact('bilingan'));
    }
    public function printKwitansi($id)
    {
        $bilingan = Bilingan::with('pembayaran_tagihan')->findOrFail($id);
        return view('pages.simrs.keuangan.kasir.partials.print-kwitansi', compact('bilingan'));
    }
}
