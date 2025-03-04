<?php

namespace App\Http\Controllers\SIMRS\Poliklinik;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Peralatan\OrderAlatMedis;
use App\Models\User;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    public function storePemakaianAlat(Request $request)
    {
        try {
            // Validasi data input
            $validatedData = $request->validate([
                'tanggal_order' => 'required|date',
                'user_id' => 'required',
                'registration_id' => 'required',
                'doctor_id' => 'required',
                'departement_id' => 'required',
                'peralatan_id' => 'required',
                'kelas_rawat_id' => 'required',
                'qty' => 'required',
                'lokasi' => 'required',
            ]);

            $entry_by = User::where('id', auth()->user()->id)->first();
            
            // Simpan data ke database
            $order = new OrderAlatMedis();
            $order->tanggal_order = $validatedData['tanggal_order'];
            $order->user_id = $validatedData['user_id'];
            $order->entry_by = $entry_by->name;
            $order->registration_id = $validatedData['registration_id'];
            $order->doctor_id = $validatedData['doctor_id'];
            $order->departement_id = $validatedData['departement_id'];
            $order->peralatan_id = $validatedData['peralatan_id'];
            $order->kelas_rawat_id = $validatedData['kelas_rawat_id'];
            $order->qty = $validatedData['qty'];
            $order->lokasi = $validatedData['lokasi'];
            $order->save();

            // Mengembalikan response sukses
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan!',
                'data' => $order
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Menangkap error validasi
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Menangkap error umum lainnya
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data!',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
