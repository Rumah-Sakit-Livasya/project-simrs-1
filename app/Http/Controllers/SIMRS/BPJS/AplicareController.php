<?php

namespace App\Http\Controllers\SIMRS\BPJS;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\Room;
use App\Services\BPJS\ApplicareService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AplicareController extends Controller
{
    protected $applicareService;

    // 2. Inject Service melalui constructor agar siap digunakan
    public function __construct(ApplicareService $applicareService)
    {
        $this->applicareService = $applicareService;
    }

    /**
     * Menampilkan halaman utama Bridging Aplicares.
     */
    public function index()
    {
        $response = $this->applicareService->getReferensiKamar();
        $kelasBpjs = [];
        // Perbaikan: Tambahkan pengecekan `is_null` agar tidak error jika koneksi gagal
        if (!is_null($response) && isset($response['metadata']['code']) && $response['metadata']['code'] == 1) {
            $kelasBpjs = $response['response']['list'];
        }

        return view('app-type.simrs.bpjs.aplicares.index', compact('kelasBpjs'));
    }

    public function getDataFromBpjs(Request $request)
    {
        // Ambil data dari BPJS, ambil semua data (misal, sampai 1000 kamar)
        $response = $this->applicareService->getKetersediaanKamar(1, 1000);

        $dataForDatatables = [
            'draw' => $request->input('draw'),
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'data' => [],
        ];

        // Cek jika response sukses dan ada datanya
        if (isset($response['metadata']['code']) && $response['metadata']['code'] == 1 && !empty($response['response']['list'])) {
            $bpjsData = $response['response']['list'];

            // Format data agar sesuai dengan yang diharapkan DataTables
            $dataForDatatables['data'] = $bpjsData;
            $dataForDatatables['recordsTotal'] = count($bpjsData);
            $dataForDatatables['recordsFiltered'] = count($bpjsData);
        } else {
            // Jika gagal, kirim pesan error (opsional)
            $dataForDatatables['error'] = $response['metadata']['message'] ?? 'Gagal mengambil data dari server BPJS.';
        }

        return response()->json($dataForDatatables);
    }

    /**
     * Menyediakan data ruangan untuk DataTables.
     */
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = Room::with(['kelas_rawat'])
                ->withCount('beds')
                ->withCount(['beds as beds_terpakai_count' => function ($query) {
                    $query->whereNotNull('patient_id');
                }]);

            return DataTables::of($data)
                ->addIndexColumn()
                // Perbaikan 1: Tampilkan data mapping BPJS, bukan data internal
                // ->addColumn('aplicare_code', fn($row) => $row->kelas_rawat->kode_bpjs ?? '')
                ->addColumn('class_name', fn($row) => $row->kelas_rawat->kelas ?? '') // Menggunakan nama_bpjs
                ->addColumn('kode_ruang', fn($row) => $row->no_ruang ?? $row->kode_ruang)
                ->addColumn('sisa_bed', fn($row) => $row->beds_count - $row->beds_terpakai_count)
                ->addColumn('mapping_status', function ($row) {
                    return isset($row->kelas_rawat->kode_bpjs)
                        ? '<span class="badge badge-success">Sudah di Mapping</span>'
                        : '<span class="badge badge-warning">Belum di Mapping</span>';
                })
                ->addColumn('action', function ($row) {
                    // ... (tidak ada perubahan di logika action) ...
                    $btn = '<div class="d-flex justify-content-around">';
                    if (!isset($row->kelas_rawat->kode_bpjs)) {
                        $btn .= '<button onclick="openMappingModal(' . $row->id . ')" class="btn btn-icon btn-info btn-xs" title="Mapping Kode Kelas Aplicare"><i class="fas fa-cog"></i></button> ';
                    } else { // Jika sudah di-mapping, tampilkan tombol aksi
                        $btn .= '<button onclick="updateRoom(' . $row->id . ')" class="btn btn-icon btn-primary btn-xs" title="Update Ruangan"><i class="fas fa-sync-alt"></i></button> ';
                        $btn .= '<button onclick="insertRoom(' . $row->id . ')" class="btn btn-icon btn-success btn-xs" title="Insert Ruangan"><i class="fas fa-upload"></i></button> ';
                        $btn .= '<button onclick="deleteRoom(' . $row->id . ')" class="btn btn-icon btn-danger btn-xs" title="Hapus Ruangan"><i class="fas fa-trash-alt"></i></button>';
                    }
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action', 'mapping_status'])
                ->make(true);
        }
    }

    /* ================================================================== */
    /*               IMPLEMENTASI FUNGSI API APLICARES                      */
    /* ================================================================== */

    /**
     * Helper function untuk menyiapkan data payload.
     */
    private function preparePayload(Room $room): array
    {
        // Gunakan data dari `withCount` yang sudah di-load sebelumnya, JANGAN query ulang.
        $kapasitas = $room->beds_count;
        $terisi = $room->beds_terpakai_count;
        $tersedia = $kapasitas - $terisi;

        return [
            "kodekelas"         => $room->kelas_rawat->kode_bpjs,
            "koderuang"         => $room->kode_ruang ?? $room->no_ruang,
            "namaruang"         => $room->ruangan,
            "kapasitas"         => (string) $kapasitas,
            "tersedia"          => (string) $tersedia,
            "tersediapria"      => "0",
            "tersediawanita"    => "0",
            "tersediapriawanita" => "0",
        ];
    }

    public function updateRoom(Request $request, $roomId)
    {
        $room = Room::with('kelas_rawat')->withCount('beds', 'beds as beds_terpakai_count')->findOrFail($roomId);

        // Validasi: Pastikan ruangan sudah di-mapping
        if (!$room->kelas_rawat || !$room->kelas_rawat->kode_bpjs) {
            return response()->json(['success' => false, 'message' => 'Gagal: Ruangan ini belum di-mapping ke kelas BPJS.'], 422);
        }

        $payload = $this->preparePayload($room);
        $response = $this->applicareService->updateKetersediaanBed($payload);

        if (isset($response['metadata']['code']) && $response['metadata']['code'] == 1) {
            return response()->json(['success' => true, 'message' => 'Ruangan ' . $room->ruangan . ' berhasil diupdate di Aplicares.']);
        }

        $errorMessage = $response['metadata']['message'] ?? 'Terjadi kesalahan dari server BPJS.';
        return response()->json(['success' => false, 'message' => 'Update Gagal: ' . $errorMessage], 500);
    }

    public function insertRoom(Request $request, $roomId)
    {
        $room = Room::with('kelas_rawat')->withCount('beds', 'beds as beds_terpakai_count')->findOrFail($roomId);

        if (!$room->kelas_rawat || !$room->kelas_rawat->kode_bpjs) {
            return response()->json(['success' => false, 'message' => 'Gagal: Ruangan ini belum di-mapping ke kelas BPJS.'], 422);
        }

        $payload = $this->preparePayload($room);
        $response = $this->applicareService->createRuangan($payload);

        if (isset($response['metadata']['code']) && $response['metadata']['code'] == 1) {
            return response()->json(['success' => true, 'message' => 'Ruangan ' . $room->ruangan . ' berhasil ditambahkan ke Aplicares.']);
        }

        $errorMessage = $response['metadata']['message'] ?? 'Terjadi kesalahan dari server BPJS.';
        return response()->json(['success' => false, 'message' => 'Insert Gagal: ' . $errorMessage], 500);
    }

    public function deleteRoom(Request $request, $roomId)
    {
        $room = Room::with('kelas_rawat')->findOrFail($roomId);

        if (!$room->kelas_rawat || !$room->kelas_rawat->kode_bpjs) {
            return response()->json(['success' => false, 'message' => 'Gagal: Ruangan ini belum di-mapping ke kelas BPJS.'], 422);
        }

        $kodeKelas = $room->kelas_rawat->kode_bpjs;
        $kodeRuang = $room->kode_ruang; // Asumsi ada kolom 'kode_ruang'

        $response = $this->applicareService->deleteRuangan($kodeKelas, $kodeRuang);

        if (isset($response['metadata']['code']) && $response['metadata']['code'] == 1) {
            return response()->json(['success' => true, 'message' => 'Ruangan ' . $room->ruangan . ' berhasil dihapus dari Aplicares.']);
        }

        $errorMessage = $response['metadata']['message'] ?? 'Terjadi kesalahan dari server BPJS.';
        return response()->json(['success' => false, 'message' => 'Delete Gagal: ' . $errorMessage], 500);
    }

    public function saveMapping(Request $request, $roomId)
    {
        $request->validate([
            'kode_bpjs' => 'required|string',
        ]);

        try {
            $room = Room::findOrFail($roomId);
            $kelasBpjs = KelasRawat::where('kode_bpjs', $request->kode_bpjs)->first();

            if (!$kelasBpjs) {
                // Jika kelas belum ada di database Anda, buat baru.
                // Anda mungkin perlu menyesuaikan ini jika nama kolomnya berbeda.
                $refKelas = $this->applicareService->getReferensiKamar();
                $namaKelas = '';
                foreach ($refKelas['response']['list'] as $ref) {
                    if ($ref['kodekelas'] == $request->kode_bpjs) {
                        $namaKelas = $ref['namakelas'];
                        break;
                    }
                }

                $kelasBpjs = KelasRawat::create([
                    'kode_bpjs' => $request->kode_bpjs,
                    'nama_bpjs' => $namaKelas,
                    // isi kolom lain yang mungkin wajib diisi
                ]);
            }

            // Hubungkan Room dengan KelasRawat
            $room->kelas_rawat_id = $kelasBpjs->id;
            $room->save();

            return response()->json(['success' => true, 'message' => 'Mapping berhasil disimpan.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
