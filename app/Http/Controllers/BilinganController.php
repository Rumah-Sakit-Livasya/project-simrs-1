<?php

namespace App\Http\Controllers;

use App\Models\SIMRS\Bilingan;
use App\Models\SIMRS\DownPayment;
use App\Models\SIMRS\PembayaranTagihan;
use App\Models\SIMRS\TagihanPasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BilinganController extends Controller
{
    // Start of Selection
    public function getData($id)
    {
        try {
            $data = Bilingan::where('id', $id)->get()->map(function ($item) {
                // Add a 'del' column for delete actions
                // Add additional fields to the item
                $item->tanggal = $item->created_at ? $item->created_at->format('Y-m-d H:i') : null;
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
            ]); // Return data in the expected format for DataTables

            // Log the data for debugging
            Log::info('Data fetched for DataTables:', $data->toArray());

            return response()->json([
                'data' => $data,
                'recordsTotal' => $data->count(),
                'recordsFiltered' => $data->count(),
            ]); // Return data in the expected format for DataTables
        } catch (\Exception $e) {
            Log::error('Error fetching data for DataTables: ' . $e->getMessage());
            return response()->json(['error' => 'Data could not be retrieved: ' . $e->getMessage()], 500); // Return error response with detailed message
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
            return response()->json(['error' => 'Data could not be retrieved: ' . $e->getMessage()], 500);
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
            return response()->json(['success' => 'Data updated successfully.']);
        } catch (\Exception $e) {
            Log::error('Error updating data: ' . $e->getMessage());
            return response()->json(['error' => 'Data could not be updated. Reason: ' . $e->getMessage()], 500);
        }
    }

    public function storePembayaranTagihan(Request $request)
    {
        $validatedData = $request->validate([
            'user_id'           => 'required|integer',
            'bilingan_id'       => 'required|integer',
            'tagihan_pasien'    => 'required|numeric',
            'total_tagihan'     => 'required|numeric',
            'jaminan'           => 'nullable|numeric',
            'jumlah_terbayar'   => 'required|numeric',
            'sisa_tagihan'      => 'required|numeric',
            'kembalian'         => 'nullable|numeric',
            'bill_notes'        => 'nullable|string',
        ]);
        $validatedData['jaminan'] = $validatedData['jaminan'] ?? 0;

        try {
            $pembayaranTagihan = PembayaranTagihan::create($validatedData);

            // Update the related bilingan record to mark as paid
            $bilingan = Bilingan::find($validatedData['bilingan_id']);
            if ($bilingan) {
                $bilingan->update(['is_paid' => 1]);
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
        // try {
        $bilingan = Bilingan::with('pembayaran_tagihan')->findOrFail($id);
        return view('pages.simrs.keuangan.kasir.partials.print-bill', compact('bilingan'));
        // } catch (\Exception $e) {
        //     Log::error('Error printing bill: ' . $e->getMessage());
        //     return redirect()->back()->with('error', 'Bill not found.');
        // }
    }
}
