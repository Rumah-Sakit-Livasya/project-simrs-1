<?php

namespace App\Http\Controllers\SIMRS\BPJS;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Room;
use App\Models\SIMRS\KelasRawat;
use App\Services\BPJS\ApplicareService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class AplicareController extends Controller
{
    protected $applicareService;

    public function __construct(ApplicareService $applicareService)
    {
        $this->applicareService = $applicareService;
    }

    public function index()
    {
        $response = $this->applicareService->getReferensiKamar();
        $kelasBpjs = [];
        if (!is_null($response) && isset($response['metadata']['code']) && $response['metadata']['code'] == 1) {
            $kelasBpjs = $response['response']['list'];
        }
        $kelasRawatInternal = KelasRawat::orderBy('aplicare_urutan')->get();
        // dd($kelasRawatInternal);
        return view('app-type.simrs.bpjs.aplicares.index', compact('kelasBpjs', 'kelasRawatInternal'));
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = Room::with(['kelas_rawat'])
                ->withCount('beds')
                ->withCount(['beds as beds_terpakai_count' => function ($query) {
                    $query->whereNotNull('patient_id');
                }]);
            // Hapus join/order, sorting client-side

            return DataTables::of($data)
                ->addIndexColumn()
                // Tambahkan aplicare_code ke data yang dikirim, kita akan membutuhkannya di JS
                ->addColumn('aplicare_code', fn($row) => $row->kelas_rawat->aplicare_code ?? null)
                ->addColumn('class_name', fn($row) => $row->kelas_rawat->kelas ?? '<span class="text-danger">Belum Set</span>')
                // Kirim urutan untuk sorting di JS (jika tidak ada, kasih angka tinggi agar di akhir)
                ->addColumn('aplicare_urutan', fn($row) => $row->kelas_rawat->aplicare_urutan ?? 999)
                ->addColumn('kode_ruang', fn($row) => $row->no_ruang)
                ->addColumn('sisa_bed', fn($row) => $row->beds_count - $row->beds_terpakai_count)
                ->addColumn('mapping_status', function ($row) {
                    if ($row->aplicare_mapping) {
                        return '<span class="badge badge-success">Sudah di Mapping</span>';
                    }
                    return '<span class="badge badge-warning">Belum di Mapping</span>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex justify-content-center">';

                    if ($row->kelas_rawat && $row->kelas_rawat->aplicare_code) {
                        if ($row->aplicare_mapping) {
                            // Biru: mapping (cog), Hijau: sync, Merah: hapus
                            $btn .= '<button onclick="openMappingModal(' . $row->id . ', ' . $row->kelas_rawat_id . ')" class="btn btn-icon btn-info btn-xs" title="Setting Mapping Kelas"><i class="fas fa-cog"></i></button> ';
                            $btn .= '<button onclick="updateRoom(' . $row->id . ')" class="btn btn-icon btn-success btn-xs" title="Update Ketersediaan"><i class="fas fa-sync-alt"></i></button> ';
                            $btn .= '<button onclick="deleteRoom(' . $row->id . ')" class="btn btn-icon btn-danger btn-xs" title="Hapus dari Aplicare"><i class="fas fa-trash-alt"></i></button>';
                        } else {
                            // Jika tidak aktif: mapping dan aktifkan
                            $btn .= '<button onclick="openMappingModal(' . $row->id . ', ' . $row->kelas_rawat_id . ')" class="btn btn-icon btn-info btn-xs" title="Setting Mapping Kelas"><i class="fas fa-cog"></i></button> ';
                            $btn .= '<button onclick="toggleMapping(' . $row->id . ', true)" class="btn btn-icon btn-primary btn-xs" title="Aktifkan & Kirim ke Aplicare"><i class="fas fa-toggle-on"></i></button> ';
                        }
                    } else {
                        // Jika belum ada aplicare_code, hanya tampilkan tombol mapping jika kelas_rawat sudah ada
                        if ($row->kelas_rawat) {
                            $btn .= '<button onclick="openMappingModal(' . $row->id . ', ' . $row->kelas_rawat_id . ')" class="btn btn-icon btn-info btn-xs" title="Setting Mapping Kelas"><i class="fas fa-cog"></i></button> ';
                        } else {
                            $btn .= '<span class="text-muted fs-xs">Set Kelas Rawat</span>';
                        }
                    }
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action', 'mapping_status', 'class_name'])
                ->make(true);
        }
    }

    public function getDataFromBpjs(Request $request)
    {
        $response = $this->applicareService->getKetersediaanKamar(1, 1000);

        $dataForDatatables = [
            'draw' => $request->input('draw'),
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'data' => [],
        ];

        if (!is_null($response) && isset($response['metadata']['code']) && $response['metadata']['code'] == 1 && !empty($response['response']['list'])) {
            $bpjsData = $response['response']['list'];
            $dataForDatatables['data'] = $bpjsData;
            $dataForDatatables['recordsTotal'] = count($bpjsData);
            $dataForDatatables['recordsFiltered'] = count($bpjsData);
        } else {
            $dataForDatatables['error'] = $response['metadata']['message'] ?? 'Gagal mengambil data dari server BPJS.';
        }

        return response()->json($dataForDatatables);
    }

    private function preparePayload(Room $room): array
    {
        $kapasitas = $room->beds_count;
        $terisi = $room->beds_terpakai_count;
        $tersedia = $kapasitas - $terisi;

        return [
            "kodekelas"         => $room->kelas_rawat->aplicare_code,
            "koderuang"         => $room->no_ruang,
            "namaruang"         => $room->ruangan,
            "kapasitas"         => (string) $kapasitas,
            "tersedia"          => (string) $tersedia,
            "tersediapria"      => "0",
            "tersediawanita"    => "0",
            "tersediapriawanita" => "0",
        ];
    }

    /**
     * Ambil data ruangan BPJS (dari server BPJS) berdasarkan roomId lokal.
     *
     * @param int|string $roomId
     * @param bool $checkActive
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    private function getMappedRoomOrFail($roomId, $checkActive = true): array
    {
        // Ambil ruangan lokal, validasi mapping + kelas rawat
        $room = Room::with('kelas_rawat')->withCount('beds', 'beds as beds_terpakai_count')->findOrFail($roomId);

        if ($checkActive && !$room->aplicare_mapping) {
            throw ValidationException::withMessages(['message' => 'Gagal: Ruangan ini tidak aktif di Aplicare.']);
        }
        if (!$room->kelas_rawat) {
            throw ValidationException::withMessages(['message' => 'Gagal: Ruangan ini belum memiliki kelas rawat.']);
        }
        if (!$room->kelas_rawat->aplicare_code) {
            throw ValidationException::withMessages(['message' => 'Gagal: Kelas rawat untuk ruangan ini belum di-mapping ke kode BPJS. Buka menu "Setting Mapping Kelas".']);
        }

        // Ambil data ruangan yang sudah mengacu pada BPJS (applicare) dari BPJS - bed/read
        // Matching by kodekelas dan koderuang
        $kodekelas = $room->kelas_rawat->aplicare_code;
        $namakelas = $room->kelas_rawat->kelas;

        $bpjs = $this->applicareService->getKetersediaanKamar(1, 1000);
        $bpjsData = $bpjs['response']['list'] ?? [];


        // Data di BPJS ditemukan? Ambil entri-nya (matching kodekelas & koderuang)
        $found = collect($bpjsData)->first(function ($item) use ($kodekelas, $namakelas) {
            return
                isset($item['kodekelas'], $item['namakelas']) &&
                (string)$item['kodekelas'] === (string)$kodekelas &&
                (string)$item['namakelas'] === (string)$namakelas;
        });
        dd($found);

        if (!$found) {
            throw ValidationException::withMessages(['message' => 'Data ruangan ini belum terdaftar di BPJS (Aplicare).']);
        }

        return $found;
    }

    public function toggleMapping(Request $request, $roomId)
    {
        $activate = filter_var($request->input('activate'), FILTER_VALIDATE_BOOLEAN);

        try {
            $room = $this->getMappedRoomOrFail($roomId, false);

            if ($activate) {
                $payload = $this->preparePayload($room);
                $response = $this->applicareService->createRuangan($payload);

                $errorMessage = $response['metadata']['message'] ?? 'Terjadi kesalahan dari server BPJS.';
                if (isset($response['metadata']['code']) && $response['metadata']['code'] == 1) {
                    $room->update(['aplicare_mapping' => true]);
                    return response()->json(['success' => true, 'message' => 'Ruangan berhasil diaktifkan dan ditambahkan ke Aplicares.']);
                }
                if (str_contains(strtolower($errorMessage), 'sudah tersedia')) {
                    $room->update(['aplicare_mapping' => true]);
                    return response()->json(['success' => true, 'message' => 'Ruangan sudah terdaftar di BPJS. Flag aktivasi di SIMRS diupdate.']);
                }
                return response()->json(['success' => false, 'message' => 'Aktivasi Gagal: ' . $errorMessage], 500);
            } else {
                $room->update(['aplicare_mapping' => false]);
                return response()->json(['success' => true, 'message' => 'Ruangan berhasil dinonaktifkan dari Aplicare.']);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], $e instanceof ValidationException ? 422 : 500);
        }
    }

    public function updateRoom($roomId)
    {
        try {
            $room = $this->getMappedRoomOrFail($roomId);
            $payload = $this->preparePayload($room);
            dd($payload);
            $response = $this->applicareService->updateKetersediaanBed($payload);

            dd($response);

            \Log::info('Aplicare updateRoom request', [
                'room_id' => $roomId,
                'payload' => $payload,
                'response' => $response,
            ]);

            if (isset($response['metadata']['code']) && $response['metadata']['code'] == 1) {
                return response()->json(['success' => true, 'message' => 'Ketersediaan berhasil diupdate.']);
            }
            return response()->json(['success' => false, 'message' => 'Update Gagal: ' . ($response['metadata']['message'] ?? 'Error BPJS')], 500);
        } catch (\Exception $e) {
            \Log::error('Aplicare updateRoom error', [
                'room_id' => $roomId,
                'exception' => $e,
            ]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], $e instanceof ValidationException ? 422 : 500);
        }
    }

    public function deleteRoom($roomId)
    {
        try {
            $room = $this->getMappedRoomOrFail($roomId);
            $response = $this->applicareService->deleteRuangan($room->kelas_rawat->aplicare_code, $room->no_ruang);

            $room->update(['aplicare_mapping' => false]);

            if (isset($response['metadata']['code']) && $response['metadata']['code'] == 1) {
                return response()->json(['success' => true, 'message' => 'Ruangan berhasil dihapus dari BPJS dan dinonaktifkan.']);
            }
            $errorMessage = $response['metadata']['message'] ?? 'Error dari BPJS.';
            if (str_contains(strtolower($errorMessage), 'tidak ditemukan')) {
                return response()->json(['success' => true, 'message' => 'Ruangan tidak ditemukan di BPJS. Flag dinonaktifkan di SIMRS.']);
            }
            return response()->json(['success' => false, 'message' => 'Delete Gagal: ' . $errorMessage], 500);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], $e instanceof ValidationException ? 422 : 500);
        }
    }

    public function saveKelasMapping(Request $request)
    {
        $request->validate([
            'kelas_rawat_id' => 'required|exists:kelas_rawat,id',
            'aplicare_code' => 'required|string',
            'aplicare_name' => 'required|string',
        ]);

        try {
            $kelasRawat = KelasRawat::findOrFail($request->kelas_rawat_id);
            $kelasRawat->update([
                'aplicare_code' => $request->aplicare_code,
                'aplicare_name' => $request->aplicare_name,
            ]);
            return response()->json(['success' => true, 'message' => 'Mapping untuk kelas ' . $kelasRawat->kelas . ' berhasil disimpan.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
