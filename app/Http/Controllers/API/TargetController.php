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
            return response()->json($target, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No result'
            ], 404);
        }
    }

    public function store()
    {
        try {
            $validator = request()->validate([
                'organization_id' => 'required',
                'user_id' => 'required',
                'title' => 'required',
                'pic' => 'required',
                'bulan' => 'required',
                'satuan' => 'required',
                'actual' => 'max:255',
                'target' => 'max:255',
                'min_target' => 'max:255',
            ]);

            $target = $validator['target'] ?? 0;
            $actual = $validator['actual'] ?? 0;
            $minTarget = $validator['min_target'] ?? 0;
            $maxTarget = $validator['target'] ?? 0;
            $validator['max_target'] = $validator['target'] ?? 0;

            // Menentukan status berdasarkan perbandingan antara actual dan target
            if ($actual == 0) {
                $validator['status'] = 'Belum dikerjakan sama sekali';
            } elseif ($actual < $minTarget) {
                $validator['status'] = 'Belum sesuai target';
            } elseif ($actual >= $minTarget && $actual < $target) {
                $validator['status'] = 'Hampir mendekati target';
            } elseif ($actual >= $target) {
                $validator['status'] = 'Sesuai target';
            } else {
                $validator['status'] = 'Di luar rentang target';
            }

            // Menentukan selisih antara target dan actual
            $validator['difference'] = $actual - $target;

            Target::create($validator);
            //return response
            return response()->json(['message' => 'Target Berhasil di Tambahkan!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No result',
                'errorLaravel' => $e->getMessage()
            ], 404);
        }
    }

    public function update($id)
    {
        try {
            // Validasi input request
            $validator = request()->validate([
                'organization_id' => 'required',
                'pic' => 'required',
                'bulan' => 'required',
                'satuan' => 'required',
                'user_id' => 'required',
                'title' => 'required',
                'actual' => 'max:255',
                'target' => 'max:255',
                'min_target' => 'max:255',
            ]);

            $target = $validator['target'] ?? 0;
            $actual = $validator['actual'] ?? 0;
            $minTarget = $validator['min_target'] ?? 0;
            $maxTarget = $validator['target'] ?? 0;
            $validator['max_target'] = $validator['target'] ?? 0;

            // Menentukan status berdasarkan perbandingan antara actual dan target
            if ($actual == 0) {
                $validator['status'] = 'Belum dikerjakan sama sekali';
            } elseif ($actual < $minTarget) {
                $validator['status'] = 'Belum sesuai target';
            } elseif ($actual >= $minTarget && $actual < $target) {
                $validator['status'] = 'Hampir mendekati target';
            } elseif ($actual >= $target) {
                $validator['status'] = 'Sesuai target';
            } else {
                $validator['status'] = 'Di luar rentang target';
            }

            // Menentukan selisih antara target dan actual
            $validator['difference'] = $actual - $target;

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
