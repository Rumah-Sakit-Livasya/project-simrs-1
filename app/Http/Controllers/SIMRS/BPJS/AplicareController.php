<?php

namespace App\Http\Controllers\SIMRS\BPJS;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Room;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AplicareController extends Controller
{
    /**
     * Menampilkan halaman utama Bridging Aplicares.
     */
    public function index()
    {
        return view('app-type.simrs.bpjs.aplicares.index');
    }

    /**
     * Menyediakan data ruangan untuk DataTables.
     */
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            // Mengambil semua ruangan dengan relasi yang dibutuhkan dan jumlah bed
            $data = Room::with(['kelas_rawat'])
                ->withCount('beds') // Menghitung total bed
                ->withCount(['beds as beds_terpakai_count' => function ($query) {
                    $query->whereNotNull('patient_id'); // Menghitung bed yang terisi
                }]);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('aplicare_code', function ($row) {
                    return $row->kelas_rawat->kode_bpjs ?? '';
                })
                ->addColumn('class_name', function ($row) {
                    return $row->kelas_rawat->nama_bpjs ?? '';
                })
                ->addColumn('sisa_bed', function ($row) {
                    return $row->beds_count - $row->beds_terpakai_count;
                })
                ->addColumn('mapping_status', function ($row) {
                    return isset($row->kelas_rawat->kode_bpjs)
                        ? '<span class="badge badge-success">Sudah di Mapping</span>'
                        : '<span class="badge badge-warning">Belum di Mapping</span>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex justify-content-around">';
                    // Tombol Mapping (hanya muncul jika belum di-mapping)
                    if (!isset($row->kelas_rawat->kode_bpjs)) {
                        $btn .= '<button onclick="openMappingModal(' . $row->id . ')" class="btn btn-icon btn-info btn-xs" title="Mapping Kode Kelas Aplicare"><i class="fas fa-cog"></i></button> ';
                    }
                    // Tombol lain
                    $btn .= '<button onclick="updateRoom(' . $row->id . ')" class="btn btn-icon btn-primary btn-xs" title="Update Ruangan"><i class="fas fa-sync-alt"></i></button> ';
                    $btn .= '<button onclick="insertRoom(' . $row->id . ')" class="btn btn-icon btn-success btn-xs" title="Insert Ruangan"><i class="fas fa-upload"></i></button> ';
                    $btn .= '<button onclick="deleteRoom(' . $row->id . ')" class="btn btn-icon btn-danger btn-xs" title="Hapus Ruangan"><i class="fas fa-trash-alt"></i></button>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action', 'mapping_status'])
                ->make(true);
        }
    }

    /* ================================================================== */
    /*               FUNGSI API PLACEHOLDER UNTUK APLICARES               */
    /* ================================================================== */

    // Anda perlu mengimplementasikan logika koneksi ke API BPJS di sini.
    // Ini hanyalah contoh placeholder.

    public function updateRoom(Request $request, $roomId)
    {
        // TODO: Logika untuk mengirim data UPDATE ke API Aplicares
        $room = Room::find($roomId);
        // ... panggil service API BPJS di sini ...
        return response()->json(['success' => true, 'message' => 'Ruangan ' . $room->ruangan . ' berhasil diupdate di Aplicares.']);
    }

    public function insertRoom(Request $request, $roomId)
    {
        // TODO: Logika untuk mengirim data INSERT ke API Aplicares
        $room = Room::find($roomId);
        // ... panggil service API BPJS di sini ...
        return response()->json(['success' => true, 'message' => 'Ruangan ' . $room->ruangan . ' berhasil ditambahkan ke Aplicares.']);
    }

    public function deleteRoom(Request $request, $roomId)
    {
        // TODO: Logika untuk mengirim data DELETE ke API Aplicares
        $room = Room::find($roomId);
        // ... panggil service API BPJS di sini ...
        return response()->json(['success' => true, 'message' => 'Ruangan ' . $room->ruangan . ' berhasil dihapus dari Aplicares.']);
    }
}
