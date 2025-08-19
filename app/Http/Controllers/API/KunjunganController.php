<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DokumentasiKunjungan;
use App\Models\Kunjungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class KunjunganController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Kunjungan::with(['jenisKegiatan', 'user', 'roomMaintenance', 'dokumentasi'])->latest();

            // Filter berdasarkan rentang tanggal
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('tanggal_kunjungan', [$request->start_date, $request->end_date]);
            }

            // Filter berdasarkan Jenis Kegiatan
            if ($request->filled('jenis_kegiatan_id')) {
                $query->where('jenis_kegiatan_id', $request->jenis_kegiatan_id);
            }

            // Filter berdasarkan Ruangan
            if ($request->filled('room_maintenance_id')) {
                $query->where('room_maintenance_id', $request->room_maintenance_id);
            }

            // Filter berdasarkan PIC (User)
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('jenis_kegiatan', fn($row) => $row->jenisKegiatan->nama_kegiatan ?? 'N/A')
                ->editColumn('ruangan', fn($row) => $row->roomMaintenance->name ?? 'N/A')
                ->editColumn('pic', fn($row) => $row->user->name ?? 'N/A')

                // HAPUS SEMUA ->addColumn('dokumentasi') DARI SINI.
                // Biarkan data mentah 'dokumentasi' dari ->with() yang dikirim ke frontend.
                // JavaScript akan menanganinya.

                ->addColumn('action', function ($row) {
                    $btn = '<button class="btn btn-warning btn-xs" onclick="openEditKunjunganModal(' . $row->id . ')">Edit</button> ';
                    $btn .= '<button class="btn btn-danger btn-xs" onclick="deleteKunjungan(' . $row->id . ')">Hapus</button>';
                    return $btn;
                })

                // Hanya 'action' yang perlu di-render sebagai HTML mentah dari sisi backend.
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_kunjungan' => 'required|date',
            'jenis_kegiatan_id' => 'required|exists:jenis_kegiatans,id',
            'room_maintenance_id' => 'required|exists:room_maintenance,id', // Ganti validasi
            'user_id' => 'required|exists:users,id',
            'keterangan' => 'nullable|string',
            'dokumentasi' => 'nullable|array', // Validasi array
            'dokumentasi.*' => 'file|mimes:jpg,jpeg,png,pdf|max:2048' // Validasi setiap file
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        DB::beginTransaction();
        try {
            $kunjungan = Kunjungan::create($request->except('dokumentasi'));

            if ($request->hasFile('dokumentasi')) {
                foreach ($request->file('dokumentasi') as $file) {
                    // Simpan file ke 'storage/app/public/dokumentasi'
                    $path = $file->store('dokumentasi', 'public');
                    $kunjungan->dokumentasi()->create(['file_path' => $path]);
                }
            }

            DB::commit();
            return response()->json(['message' => 'Data kunjungan berhasil ditambahkan.'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function show(Kunjungan $kunjungan)
    {
        // Load semua relasi yang dibutuhkan untuk form edit
        $kunjungan->load(['jenisKegiatan', 'user', 'roomMaintenance', 'dokumentasi']);
        return response()->json(['data' => $kunjungan]);
    }

    public function update(Request $request, Kunjungan $kunjungan)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_kunjungan' => 'required|date',
            'jenis_kegiatan_id' => 'required|exists:jenis_kegiatans,id',
            'room_maintenance_id' => 'required|exists:room_maintenance,id',
            'user_id' => 'required|exists:users,id',
            'keterangan' => 'nullable|string',
            'dokumentasi' => 'nullable|array',
            'dokumentasi.*' => 'file|mimes:jpg,jpeg,png,pdf|max:2048',
            // Tambah validasi untuk file yang akan dihapus
            'deleted_docs' => 'nullable|array',
            'deleted_docs.*' => 'integer|exists:dokumentasi_kunjungans,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        DB::beginTransaction();
        try {
            // 1. Hapus dokumentasi yang ditandai untuk dihapus
            if ($request->has('deleted_docs')) {
                // Security check: pastikan hanya menghapus file milik kunjungan ini
                $docsToDelete = DokumentasiKunjungan::where('kunjungan_id', $kunjungan->id)
                    ->whereIn('id', $request->deleted_docs)
                    ->get();

                foreach ($docsToDelete as $doc) {
                    Storage::disk('public')->delete($doc->file_path);
                    $doc->delete();
                }
            }

            // 2. Update data utama kunjungan
            $kunjungan->update($request->except(['dokumentasi', 'deleted_docs']));

            // 3. Tambahkan dokumentasi baru jika ada
            if ($request->hasFile('dokumentasi')) {
                foreach ($request->file('dokumentasi') as $file) {
                    $path = $file->store('dokumentasi', 'public');
                    $kunjungan->dokumentasi()->create(['file_path' => $path]);
                }
            }

            DB::commit();
            return response()->json(['message' => 'Data kunjungan berhasil diperbarui.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Kunjungan $kunjungan)
    {
        DB::beginTransaction();
        try {
            // Hapus file fisik dari storage
            foreach ($kunjungan->dokumentasi as $doc) {
                Storage::disk('public')->delete($doc->file_path);
            }

            // Hapus record dari database
            $kunjungan->delete();

            DB::commit();
            return response()->json(['message' => 'Data kunjungan berhasil dihapus.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
