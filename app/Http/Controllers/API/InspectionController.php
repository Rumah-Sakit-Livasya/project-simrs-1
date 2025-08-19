<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Inspection;
use App\Models\InspectionResult; // <-- Pastikan ini ada
use App\Models\VehicleService;   // <-- IMPORT MODEL INI
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class InspectionController extends Controller
{
    public function index(Request $request)
    {
        // REFACOR: Tambahkan withCount untuk menghitung temuan "Rusak" secara efisien
        $data = Inspection::with('inspector')
            ->withCount(['results as findings_count' => function ($query) {
                $query->where('status', 'Rusak');
            }])
            ->latest();

        return DataTables::of($data)->make(true);
    }

    // FUNGSI BARU: Untuk mengambil detail inspeksi
    public function show($id)
    {
        $inspection = Inspection::with('inspector', 'results.item', 'results.vehicle')
            ->find($id);

        if (!$inspection) {
            return response()->json(['message' => 'Inspeksi tidak ditemukan'], 404);
        }

        // Kelompokkan hasil berdasarkan kendaraan untuk mempermudah rendering di frontend
        $resultsByVehicle = $inspection->results->groupBy('vehicle.name');

        return response()->json([
            'inspection' => $inspection,
            'results' => $resultsByVehicle,
        ]);
    }

    public function store(Request $request)
    {
        // ... (Kode store Anda tidak perlu diubah, sudah bagus)
        $validator = Validator::make($request->all(), [
            'inspection_date' => 'required|date',
            'inspector_id' => 'required|exists:users,id',
            'results' => 'nullable|array',
            'results.*.*.status' => 'nullable|in:Baik,Rusak',
            'results.*.*.notes' => 'nullable|string',
            'results.*.*.photo' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        DB::beginTransaction();
        try {
            $inspection = \App\Models\Inspection::create([
                'inspection_date' => $request->inspection_date,
                'inspector_id' => $request->inspector_id,
            ]);

            // Array untuk menampung ID hasil inspeksi yang rusak
            $damagedResultIds = [];

            if ($request->has('results') && is_array($request->results)) {
                foreach ($request->results as $vehicleId => $items) {
                    foreach ($items as $itemId => $result) {
                        if (!empty($result['status'])) {
                            $photoPath = null;
                            if (isset($result['status']) && $result['status'] === 'Rusak' && isset($result['photo'])) {
                                $file = $result['photo'];
                                $path = $file->store('inspection_proofs');
                                $photoPath = Storage::url($path);
                            }

                            $createdResult = \App\Models\InspectionResult::create([
                                'inspection_id' => $inspection->id,
                                'internal_vehicle_id' => $vehicleId,
                                'inspection_item_id' => $itemId,
                                'status' => $result['status'],
                                'notes' => $result['notes'] ?? null,
                                'photo_path' => $photoPath,
                            ]);

                            // --- LOGIKA BARU ---
                            // Jika hasilnya 'Rusak', simpan ID-nya ke array
                            if ($createdResult->status === 'Rusak') {
                                $damagedResultIds[] = $createdResult->id;
                            }
                        }
                    }
                }
            }

            // --- LOGIKA BARU YANG DIPINDAHKAN DARI OBSERVER ---
            // Setelah semua hasil disimpan, proses yang rusak
            if (!empty($damagedResultIds)) {
                // Ambil semua data item rusak dalam satu query untuk efisiensi
                $damagedItems = InspectionResult::with('item', 'vehicle')
                    ->whereIn('id', $damagedResultIds)
                    ->get();

                foreach ($damagedItems as $item) {
                    VehicleService::create([
                        'internal_vehicle_id' => $item->internal_vehicle_id,
                        'reported_by_id' => $inspection->inspector_id,
                        'inspection_result_id' => $item->id,
                        'description_of_issue' => "[Temuan Inspeksi] - " . ($item->item->name ?? 'Item tidak diketahui') . ": " . ($item->notes ?: 'Tidak ada catatan tambahan.'),
                    ]);
                }
            }
            // --- AKHIR DARI LOGIKA BARU ---

            DB::commit();
            return response()->json(['message' => 'Data inspeksi dan tiket perbaikan (jika ada) berhasil dibuat!'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan saat menyimpan data.', 'error' => $e->getMessage()], 500);
        }
    }

    // FUNGSI BARU: Untuk menghapus inspeksi
    public function destroy($id)
    {
        $inspection = Inspection::with('results')->find($id);

        if (!$inspection) {
            return response()->json(['message' => 'Inspeksi tidak ditemukan'], 404);
        }

        DB::beginTransaction();
        try {
            // Hapus foto-foto terkait dari storage
            foreach ($inspection->results as $result) {
                if ($result->photo_path) {
                    // Ubah URL kembali menjadi path storage
                    $storagePath = str_replace('/storage/', '', $result->photo_path);
                    Storage::delete($storagePath);
                }
            }
            // Hapus record inspeksi (results akan terhapus otomatis jika ada foreign key cascade)
            $inspection->delete();
            DB::commit();
            return response()->json(['message' => 'Data inspeksi berhasil dihapus!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal menghapus data.', 'error' => $e->getMessage()], 500);
        }
    }
}
