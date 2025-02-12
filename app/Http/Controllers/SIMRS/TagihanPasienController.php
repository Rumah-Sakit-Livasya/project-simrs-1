<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\TagihanPasien;
use Illuminate\Http\Request;

class TagihanPasienController extends Controller
{
    public function index()
    {
        // dd(today());
        // dd(TagihanPasien::whereDate('date', today())->get());
        $tagihan_pasien = TagihanPasien::whereDate('date', today())->get();
        return view('pages.simrs.keuangan.kasir.index', compact('tagihan_pasien'));
    }

    public function detailTagihan($id)
    {
        // dd($id);
        // dd(TagihanPasien::where('id', $id)->first());
        $tagihan_pasien = TagihanPasien::where('id', $id)->first();
        return view('pages.simrs.keuangan.kasir.detail', compact('tagihan_pasien'));
    }

    public function getData()
    {
        try {
            $data = TagihanPasien::select(['id', 'date as tanggal', 'tagihan as detail_tagihan', 'quantity', 'nominal', 'tipe_diskon', 'disc', 'diskon as diskon_rp', 'jamin', 'jaminan as jaminan_rp', 'wajib_bayar'])
                ->get(); // Ambil data yang diperlukan

            // Add a 'del' column for delete actions
            $data = $data->map(function ($item) {
                $item->del = '<button class="btn btn-danger delete" data-id="' . $item->id . '"><i class="fas fa-trash"></i></button>'; // Icon trash button
                return $item;
            });

            // Log the data for debugging
            \Log::info('Data fetched for DataTables:', $data->toArray());

            return response()->json([
                'data' => $data,
                'recordsTotal' => $data->count(),
                'recordsFiltered' => $data->count(),
            ]); // Return data in the expected format for DataTables
        } catch (\Exception $e) {
            \Log::error('Error fetching data for DataTables: ' . $e->getMessage());
            return response()->json(['error' => 'Data could not be retrieved.'], 500); // Return error response
        }
    }

    // Method to update the TagihanPasien data
    public function updateTagihan(Request $request, $id)
    {
        $columnMapping = [
            'detail_tagihan' => 'tagihan',
            'tanggal' => 'date',
            'diskon_rp' => 'diskon',
            'jaminan_rp' => 'jaminan',
        ];

        if (array_key_exists($request->column, $columnMapping)) {
            $request->column = $columnMapping[$request->column];
        }

        $request->validate([
            'column' => 'required|string',
            'value' => 'required'
        ]);

        try {
            $tagihan = TagihanPasien::findOrFail($id);
            $tagihan->{$request->column} = $request->value;
            $tagihan->save();

            return response()->json(['success' => 'Data updated successfully.']);
        } catch (\Exception $e) {
            \Log::error('Error updating data: ' . $e->getMessage());
            return response()->json(['error' => 'Data could not be updated. Reason: ' . $e->getMessage()], 500);
        }
    }
}
