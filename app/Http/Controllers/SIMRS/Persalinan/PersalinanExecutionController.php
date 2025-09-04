<?php

namespace App\Http\Controllers\SIMRS\Persalinan;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Persalinan\PersalinanExecution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PersalinanExecutionController extends Controller
{
    /**
     * Show execution details
     */
    public function show($id)
    {
        try {
            $execution = PersalinanExecution::with([
                'orderPersalinan.pasien',
                'orderPersalinan.dokter',
                'orderPersalinan.jenisPersalinan',
                'dokterOperatorActual',
                'asistenOperatorActual',
                'dokterAnestesiActual',
                'asistenAnestesiActual',
                'dokterResusitatorActual',
                'asistenResusitatorActual',
                'dokterUmumActual',
                'ruangBersalinActual',
                'observationRoomActual',
                'verifiedBy'
            ])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $execution
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data eksekusi tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Update execution data
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_persalinan_actual' => 'required|date',
            'jam_mulai' => 'nullable|date_format:H:i',
            'jam_selesai' => 'nullable|date_format:H:i|after:jam_mulai',
            'dokter_operator_actual' => 'nullable|exists:dokters,id',
            'asisten_operator_actual' => 'nullable|exists:dokters,id',
            'dokter_anestesi_actual' => 'nullable|exists:dokters,id',
            'asisten_anestesi_actual' => 'nullable|exists:dokters,id',
            'dokter_resusitator_actual' => 'nullable|exists:dokters,id',
            'asisten_resusitator_actual' => 'nullable|exists:dokters,id',
            'dokter_umum_actual' => 'nullable|exists:dokters,id',
            'ruang_bersalin_actual' => 'nullable|exists:ruangs,id',
            'observation_room_actual' => 'nullable|exists:ruangs,id',
            'hasil_persalinan' => 'nullable|in:live_birth,stillbirth,abortion',
            'jumlah_bayi' => 'nullable|integer|min:1',
            'jenis_kelamin_bayi' => 'nullable|in:L,P',
            'berat_bayi' => 'nullable|numeric|min:0',
            'panjang_bayi' => 'nullable|integer|min:0',
            'apgar_score_1' => 'nullable|integer|min:0|max:10',
            'apgar_score_5' => 'nullable|integer|min:0|max:10',
            'komplikasi' => 'nullable|string',
            'catatan_medis' => 'nullable|string',
            'instruksi_pasca_persalinan' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $execution = PersalinanExecution::findOrFail($id);

            // Update data eksekusi
            $execution->update($request->only([
                'tanggal_persalinan_actual',
                'jam_mulai',
                'jam_selesai',
                'dokter_operator_actual',
                'asisten_operator_actual',
                'dokter_anestesi_actual',
                'asisten_anestesi_actual',
                'dokter_resusitator_actual',
                'asisten_resusitator_actual',
                'dokter_umum_actual',
                'ruang_bersalin_actual',
                'observation_room_actual',
                'hasil_persalinan',
                'jumlah_bayi',
                'jenis_kelamin_bayi',
                'berat_bayi',
                'panjang_bayi',
                'apgar_score_1',
                'apgar_score_5',
                'komplikasi',
                'catatan_medis',
                'instruksi_pasca_persalinan'
            ]));

            // Jika request complete, mark as completed
            if ($request->has('complete') && $request->complete == '1') {
                $execution->status = 'completed';
                $execution->verified_by = auth()->id();
                $execution->verified_at = now();
                $execution->save();

                // Update status order persalinan
                $execution->orderPersalinan->update(['status_order' => 'completed']);

                $message = 'Persalinan berhasil diselesaikan';
            } else {
                $message = 'Data eksekusi berhasil disimpan';
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $execution
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Complete execution (mark as finished)
     */
    public function complete($id)
    {
        DB::beginTransaction();
        try {
            $execution = PersalinanExecution::findOrFail($id);

            // Validasi apakah sudah bisa diselesaikan
            if ($execution->status === 'completed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Eksekusi sudah diselesaikan sebelumnya'
                ], 400);
            }

            // Validasi data wajib untuk completion
            if (!$execution->tanggal_persalinan_actual) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tanggal persalinan actual harus diisi'
                ], 400);
            }

            if (!$execution->dokter_operator_actual) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dokter operator harus diisi'
                ], 400);
            }

            // Update status
            $execution->status = 'completed';
            $execution->verified_by = auth()->id();
            $execution->verified_at = now();
            $execution->save();

            // Update status order persalinan
            $execution->orderPersalinan->update(['status_order' => 'completed']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Persalinan berhasil diselesaikan',
                'data' => $execution
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel execution
     */
    public function cancel($id)
    {
        DB::beginTransaction();
        try {
            $execution = PersalinanExecution::findOrFail($id);

            if ($execution->status === 'completed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Eksekusi yang sudah selesai tidak dapat dibatalkan'
                ], 400);
            }

            // Update status
            $execution->status = 'cancelled';
            $execution->save();

            // Update status order persalinan kembali ke confirmed
            $execution->orderPersalinan->update(['status_order' => 'confirmed']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Eksekusi persalinan berhasil dibatalkan'
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get execution history for a patient
     */
    public function getExecutionHistory($pasienId)
    {
        try {
            $executions = PersalinanExecution::with([
                'orderPersalinan.jenisPersalinan',
                'dokterOperatorActual',
                'ruangBersalinActual'
            ])
                ->whereHas('orderPersalinan', function ($q) use ($pasienId) {
                    $q->where('pasien_id', $pasienId);
                })
                ->where('status', 'completed')
                ->orderBy('tanggal_persalinan_actual', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $executions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil riwayat persalinan'
            ], 500);
        }
    }

    /**
     * Generate laporan persalinan
     */
    public function generateReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'nullable|in:completed,in_progress,cancelled',
            'dokter_id' => 'nullable|exists:dokters,id',
            'ruang_id' => 'nullable|exists:ruangs,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $query = PersalinanExecution::with([
                'orderPersalinan.pasien',
                'orderPersalinan.jenisPersalinan',
                'dokterOperatorActual',
                'ruangBersalinActual'
            ])
                ->whereBetween('tanggal_persalinan_actual', [
                    $request->tanggal_mulai,
                    $request->tanggal_selesai . ' 23:59:59'
                ]);

            if ($request->status) {
                $query->where('status', $request->status);
            }

            if ($request->dokter_id) {
                $query->where('dokter_operator_actual', $request->dokter_id);
            }

            if ($request->ruang_id) {
                $query->where('ruang_bersalin_actual', $request->ruang_id);
            }

            $executions = $query->orderBy('tanggal_persalinan_actual', 'desc')->get();

            // Generate summary
            $summary = [
                'total_persalinan' => $executions->count(),
                'kelahiran_hidup' => $executions->where('hasil_persalinan', 'live_birth')->count(),
                'lahir_mati' => $executions->where('hasil_persalinan', 'stillbirth')->count(),
                'keguguran' => $executions->where('hasil_persalinan', 'abortion')->count(),
                'rata_rata_durasi' => $executions->avg('durasi_menit'),
                'total_biaya' => $executions->sum('biaya_total_actual')
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'executions' => $executions,
                    'summary' => $summary,
                    'periode' => [
                        'mulai' => $request->tanggal_mulai,
                        'selesai' => $request->tanggal_selesai
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal generate laporan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get statistics dashboard
     */
    public function getStatistics(Request $request)
    {
        try {
            $startDate = $request->get('start_date', now()->startOfMonth());
            $endDate = $request->get('end_date', now()->endOfMonth());

            // Total persalinan per bulan
            $monthlyStats = PersalinanExecution::selectRaw('
                DATE_FORMAT(tanggal_persalinan_actual, "%Y-%m") as bulan,
                COUNT(*) as total,
                SUM(CASE WHEN hasil_persalinan = "live_birth" THEN 1 ELSE 0 END) as kelahiran_hidup,
                SUM(CASE WHEN hasil_persalinan = "stillbirth" THEN 1 ELSE 0 END) as lahir_mati,
                AVG(durasi_menit) as rata_durasi
            ')
                ->whereBetween('tanggal_persalinan_actual', [$startDate, $endDate])
                ->where('status', 'completed')
                ->groupBy('bulan')
                ->orderBy('bulan')
                ->get();

            // Top 5 dokter operator
            $topDokter = PersalinanExecution::selectRaw('
                dokter_operator_actual,
                COUNT(*) as total_operasi
            ')
                ->with('dokterOperatorActual:id,nama')
                ->whereBetween('tanggal_persalinan_actual', [$startDate, $endDate])
                ->where('status', 'completed')
                ->whereNotNull('dokter_operator_actual')
                ->groupBy('dokter_operator_actual')
                ->orderBy('total_operasi', 'desc')
                ->limit(5)
                ->get();

            // Utilitas ruang
            $ruangStats = PersalinanExecution::selectRaw('
                ruang_bersalin_actual,
                COUNT(*) as penggunaan
            ')
                ->with('ruangBersalinActual:id,nama')
                ->whereBetween('tanggal_persalinan_actual', [$startDate, $endDate])
                ->where('status', 'completed')
                ->whereNotNull('ruang_bersalin_actual')
                ->groupBy('ruang_bersalin_actual')
                ->orderBy('penggunaan', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'monthly_stats' => $monthlyStats,
                    'top_dokter' => $topDokter,
                    'ruang_stats' => $ruangStats
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik: ' . $e->getMessage()
            ], 500);
        }
    }
}
