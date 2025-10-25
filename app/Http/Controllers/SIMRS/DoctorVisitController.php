<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Bilingan;
use App\Models\SIMRS\BilinganTagihanPasien;
use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\DoctorVisit;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\TagihanPasien;
use App\Models\SIMRS\TarifVisiteDokter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Yajra\DataTables\Facades\DataTables;

class DoctorVisitController extends Controller
{
    /**
     * Mengambil data visite untuk ditampilkan di Server-Side DataTables.
     */
    public function getData(Request $request, $registrationId)
    {
        try {
            $registration = Registration::findOrFail($registrationId);
        } catch (ModelNotFoundException $e) {
            throw new NotFoundHttpException('Registrasi tidak ditemukan.');
        }

        $query = DoctorVisit::where('registration_id', $registration->id)
            ->with(['doctor.employee', 'kelas_rawat', 'user.employee'])
            ->select('doctor_visits.*'); // Penting untuk DataTables

        return DataTables::of($query)
            ->addIndexColumn() // Menambahkan kolom nomor urut DT_RowIndex
            ->editColumn('visit_date', function ($row) {
                return $row->visit_date->format('d-m-Y H:i');
            })
            ->addColumn('doctor', function ($row) {
                return $row->doctor?->employee?->fullname ?? 'N/A';
            })
            ->addColumn('class_name', function ($row) {
                return $row->kelas_rawat?->kelas ?? 'N/A';
            })
            ->addColumn('user_by', function ($row) {
                return $row->user?->employee?->fullname ?? $row->user?->name ?? 'N/A';
            })
            ->editColumn('is_billed', function ($row) {
                if ($row->is_billed) {
                    return '<span class="badge badge-success">Billed</span>';
                }
                return '<span class="badge badge-warning">Pending</span>';
            })
            ->addColumn('action', function ($row) {
                if (!$row->is_billed) {
                    $deleteUrl = route('visite.destroy', ['registration' => $row->registration_id, 'visit' => $row->id]);
                    return '
            <a href="javascript:void(0);"
               class="btn btn-danger btn-sm btn-icon waves-effect waves-themed"
               onclick="SimrsModules.VisiteDokter.deleteVisit(\'' . $deleteUrl . '\')"
               title="Hapus Visite">
               <i class="fal fa-trash"></i>
            </a>';
                }
                return '';
            })
            ->rawColumns(['is_billed', 'action']) // Kolom yang mengandung HTML
            ->make(true);
    }

    /**
     * Menyimpan data visite baru dan membuat tagihan otomatis.
     */
    public function store(Request $request, $registrationId)
    {
        try {
            $registration = Registration::findOrFail($registrationId);
        } catch (ModelNotFoundException $e) {
            throw new NotFoundHttpException('Registrasi tidak ditemukan.');
        }

        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'visit_date' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            // 1. Tentukan Kelas Rawat
            $kelasRawatId = $registration->kelas_rawat_id ?? 1; // Default ke kelas 1 jika null

            // 2. Cari Tarif Visite Dokter
            $tarif = TarifVisiteDokter::where('doctor_id', $request->doctor_id)
                ->where('kelas_rawat_id', $kelasRawatId)
                ->first();

            if (!$tarif) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Tarif visite untuk dokter ini di kelas perawatan tersebut belum diatur. Silakan atur tarif terlebih dahulu.'
                ], 422);
            }

            // 3. Simpan data visite
            $visit = DoctorVisit::create([
                'registration_id' => $registration->id,
                'doctor_id' => $request->doctor_id,
                'kelas_rawat_id' => $kelasRawatId,
                'user_id' => Auth::id(),
                'visit_date' => $request->visit_date,
            ]);

            // 4. Buat atau ambil Bilingan utama
            $bilingan = Bilingan::firstOrCreate(
                ['registration_id' => $registration->id],
                ['status' => 'belum final', 'is_paid' => 0]
            );

            // 5. Buat Tagihan Pasien
            $visitingDoctor = Doctor::with('employee')->find($request->doctor_id);
            $tagihanPasien = TagihanPasien::create([
                'user_id' => Auth::id(),
                'bilingan_id' => $bilingan->id,
                'registration_id' => $registration->id,
                'doctor_visit_id' => $visit->id, // Link ke visite yang baru dibuat
                'date' => now(),
                'tagihan' => '[Visite Dokter] ' . ($visitingDoctor->employee->fullname ?? 'N/A'),
                'quantity' => 1,
                'nominal' => $tarif->total,
                'nominal_awal' => $tarif->total,
                'wajib_bayar' => $tarif->total,
            ]);

            // 6. Buat relasi Bilingan-Tagihan (jika menggunakan tabel pivot)
            BilinganTagihanPasien::create([
                'tagihan_pasien_id' => $tagihanPasien->id,
                'bilingan_id' => $bilingan->id,
                'status' => 'belum final',
                'is_paid' => 0,
            ]);


            DB::commit();
            return response()->json(['success' => true, 'message' => 'Visite dokter dan tagihan berhasil ditambahkan.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan visite dan tagihan: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan pada server.'], 500);
        }
    }

    /**
     * Menghapus data visite dan tagihan terkait.
     */
    public function destroy(Request $request, $registrationId, $visitId)
    {
        try {
            $registration = Registration::findOrFail($registrationId);
        } catch (ModelNotFoundException $e) {
            throw new NotFoundHttpException('Registrasi tidak ditemukan.');
        }

        try {
            $visit = DoctorVisit::findOrFail($visitId);
        } catch (ModelNotFoundException $e) {
            throw new NotFoundHttpException('Visite dokter tidak ditemukan.');
        }

        if ($visit->registration_id !== $registration->id) {
            return response()->json(['message' => 'Akses tidak valid.'], 403);
        }

        if ($visit->is_billed) {
            return response()->json(['message' => 'Data tidak bisa dihapus karena sudah ditagih.'], 422);
        }

        DB::beginTransaction();
        try {
            // Cari tagihan yang berelasi dengan visite ini
            $tagihan = $visit->tagihan_pasien()->first();

            if ($tagihan) {
                // Hapus dari tabel pivot bilingan_tagihan_pasien
                BilinganTagihanPasien::where('tagihan_pasien_id', $tagihan->id)->delete();
                // Hapus tagihan itu sendiri
                $tagihan->delete();
            }

            // Hapus data visite
            $visit->delete();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Data visite dan tagihan terkait berhasil dihapus.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menghapus visite dan tagihan: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus data.'], 500);
        }
    }
}
