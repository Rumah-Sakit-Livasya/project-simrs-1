<?php

namespace App\Http\Controllers;

use App\Models\SIMRS\Bilingan;
use App\Models\SIMRS\DownPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BilinganController extends Controller
{
    // Start of Selection
    public function getData($id)
    {
        try {
            $data = Bilingan::where('id', $id)->first()->tagihan_pasien->map(function ($item) {
                // Add a 'del' column for delete actions
                $item->del = '<button class="btn btn-danger delete" data-id="' . $item->id . '"><i class="fas fa-trash"></i></button>'; // Icon trash button
                // Add additional fields to the item
                $item->tanggal = $item->created_at ? $item->created_at->format('Y-m-d') : null; // Format date to yyyy-mm-dd
                $item->total_tagihan = $item->pembayaran_tagihan->total_tagihan ?? null;
                $item->jaminan = $item->pembayaran_tagihan->jaminan ?? null;
                $item->tagihan_pasien = $item->pembayaran_tagihan->tagihan_pasien ?? null;
                $item->jumlah_terbayar = $item->pembayaran_tagihan->jumlah_terbayar ?? null;
                $item->sisa_tagihan = $item->pembayaran_tagihan->sisa_tagihan ?? null;
                $item->kembalian = $item->pembayaran_tagihan->kembalian ?? null;
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
}
