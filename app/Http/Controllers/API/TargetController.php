<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Target;
use Illuminate\Http\Request;

class TargetController extends Controller
{
    public function getTarget($id)
    {
        try {
            $target = Target::findOrFail($id);
            // Jika data pic disimpan sebagai JSON string, parse dulu
            $target->pic = json_decode($target->pic, true);
            return response()->json($target, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No result'
            ], 404);
        }
    }


    public function store(Request $request)
    {

        $validator = $request->validate([
            'organization_id' => 'required',
            'user_id' => 'required',
            'title' => 'required',
            'pic' => 'required|array',
            'bulan' => 'required',
            'satuan' => 'required',
            'baseline_data' => 'required',
            'actual' => 'max:255',
            'target' => 'max:255',
            'custom_target' => 'max:255',
        ], [
            'organization_id.required' => 'ID organisasi diperlukan.',
            'user_id.required' => 'ID pengguna diperlukan.',
            'title.required' => 'Judul diperlukan.',
            'pic.required' => 'PIC diperlukan.',
            'bulan.required' => 'Bulan diperlukan.',
            'satuan.required' => 'Satuan diperlukan.',
            'baseline_data.required' => 'Data baseline diperlukan.',
            'actual.max' => 'Nilai aktual tidak boleh lebih dari 255 karakter.',
            'target.max' => 'Nilai target tidak boleh lebih dari 255 karakter.',
            'custom_target.max' => 'Nilai target kustom tidak boleh lebih dari 255 karakter.',
        ]);


        // Konversi array pic menjadi string sebelum disimpan ke database
        $validator['pic'] = implode(',', $validator['pic']); // menggabungkan array menjadi string dengan pemisah koma

        $target = $validator['target'] ?? 0;
        $actual = $validator['actual'] ?? 0;
        $baselineData = $validator['baseline_data'] ?? 0;

        // Menghitung movement
        $movement = (($actual - $baselineData) / ($target > 0 ? $target : 1)) * 100;

        // Menghitung persentase pencapaian
        $persentase = ($actual / ($target > 0 ? $target : 1)) * 100;

        // Menggunakan logika dari calculateTargetStats untuk menentukan status
        if ($persentase >= 100) {
            $validator['status'] = 'green';
        } elseif ($persentase >= 60) {
            $validator['status'] = 'blue';
        } elseif ($persentase >= 30) {
            $validator['status'] = 'yellow';
        } elseif ($persentase < 30) {
            $validator['status'] = 'red';
        } else {
            $validator['status'] = 'invalid';
        }

        // Tambahkan hasil movement dan persentase ke dalam data yang akan disimpan
        $validator['movement'] = $movement;
        $validator['persentase'] = $persentase;

        try {
            // Simpan data target ke database
            Target::create($validator);
            // Response sukses
            return response()->json(['message' => 'Target Berhasil Ditambahkan!']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update($id)
    {
        try {
            $validator = request()->validate([
                'organization_id' => 'required',
                'user_id' => 'required',
                'title' => 'required',
                'pic' => 'required',
                'bulan' => 'required',
                'satuan' => 'required',
                'baseline_data' => 'required',
                'actual' => 'max:255',
                'target' => 'max:255',
                'custom_target' => 'max:255',
            ]);

            $target = $validator['target'] ?? 0;
            $actual = $validator['actual'] ?? 0;
            $baselineData = $validator['baseline_data'] ?? 0;

            // Menghitung movement
            $movement = (($actual - $baselineData) / ($target > 0 ? $target : 1)) * 100;

            // Menghitung persentase pencapaian
            $persentase = ($actual / ($target > 0 ? $target : 1)) * 100;

            // Menggunakan logika dari calculateTargetStats untuk menentukan status
            if ($persentase >= 100) {
                $validator['status'] = 'green';
            } elseif ($persentase >= 60) {
                $validator['status'] = 'blue';
            } elseif ($persentase >= 30) {
                $validator['status'] = 'yellow';
            } elseif ($persentase < 30) {
                $validator['status'] = 'red';
            } else {
                $validator['status'] = 'invalid';
            }

            // Tambahkan hasil movement dan persentase ke dalam data yang akan diperbarui
            $validator['movement'] = $movement;
            $validator['persentase'] = $persentase;

            // Temukan Target yang akan diupdate
            $targetItem = Target::findOrFail($id);

            // Update data target
            $targetItem->update($validator);

            // Response sukses
            return response()->json(['message' => 'Target Berhasil Diperbarui!']);
        } catch (\Exception $e) {
            // Response error
            return response()->json([
                'error' => 'Gagal memperbarui target',
                'errorLaravel' => $e->getMessage()
            ], 404);
        }
    }


    public function updateHasil($id)
    {
        try {
            // Validasi input request
            $validator = request()->validate([
                'hasil' => 'nullable',
                'evaluasi' => 'nullable',
                'initiative' => 'nullable',
                'goal' => 'nullable',
                'key_result' => 'nullable',
                'anggaran' => 'nullable',
            ]);

            // Temukan Target yang akan diupdate
            $targetItem = Target::findOrFail($id);

            // Update data target
            $targetItem->update($validator);

            // Response sukses
            return response()->json(['message' => 'Target Berhasil Diperbarui!']);
        } catch (\Exception $e) {
            // Response error
            return response()->json([
                'error' => 'Gagal memperbarui target',
                'errorLaravel' => $e->getMessage()
            ], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $target = Target::find($id);
            $target->delete();
            //return response
            return response()->json(['message' => 'Target Berhasil di Hapus!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No result',
                'errorLaravel' => $e->getMessage()
            ], 404);
        }
    }
}
