<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Bilingan;
use App\Models\SIMRS\TagihanPasien;
use Illuminate\Http\Request;

class TagihanPasienController extends Controller
{
    public function index(Request $request)
    {
        $query = Bilingan::query();

        // return $request->registration_date;
        if ($request->filled('registration_date')) {
            $dates = explode(' - ', $request->registration_date);
            $start_date = date('Y-m-d 00:00:00', strtotime(trim($dates[0])));
            $end_date = date('Y-m-d 23:59:59', strtotime(trim($dates[1])));
            $query->whereBetween('created_at', [$start_date, $end_date]);
        } else {
            $query->whereDate('created_at', today());
        }

        if ($request->filled('medical_record_number')) {
            $query->whereHas('registration.patient', function ($q) use ($request) {
                $q->where('medical_record_number', $request->medical_record_number);
            });
        }

        if ($request->filled('name')) {
            $query->whereHas('registration.patient', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
        }

        $tagihan_pasien = $query->get();
        return view('pages.simrs.keuangan.kasir.index', compact('tagihan_pasien'));
    }

    public function detailTagihan($id)
    {
        // dd($id);
        // dd(TagihanPasien::where('id', $id)->first());
        $bilingan = Bilingan::where('id', $id)->first();
        return view('pages.simrs.keuangan.kasir.detail', compact('bilingan'));
    }

    public function getData($id)
    {
        try {
            $data = TagihanPasien::select(['id', 'date as tanggal', 'tagihan as detail_tagihan', 'quantity', 'nominal', 'tipe_diskon', 'disc', 'diskon as diskon_rp', 'jamin', 'jaminan as jaminan_rp', 'wajib_bayar'])
                ->where('bilingan_id', $id)
                ->get(); // Ambil data yang diperlukan

            // Add a 'del' column for delete actions
            $data = $data->map(function ($item) {
                $item->del = '<button class="btn btn-danger delete" data-id="' . $item->id . '"><i class="fas fa-trash"></i></button>'; // Icon trash button

                // If value is 0, display it as is
                $item->nominal = $item->nominal == 0 ? '0' : $item->nominal;
                $item->diskon_rp = $item->diskon_rp == 0 ? '0' : $item->diskon_rp;
                $item->disc = $item->disc == 0 ? '0' : $item->disc;
                $item->jaminan_rp = $item->jaminan_rp == 0 ? '0' : $item->jaminan_rp;
                $item->jamin = $item->jamin == 0 ? '0' : $item->jamin;

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
