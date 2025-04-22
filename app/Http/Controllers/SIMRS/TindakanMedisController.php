<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\GroupPenjamin;
use App\Models\SIMRS\GrupTindakanMedis;
use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\TindakanMedis;
use App\Models\TarifTindakanMedis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TindakanMedisController extends Controller
{
    public function index()
    {
        $tindakan_medis = TindakanMedis::all();
        $grup_tindakan = GrupTindakanMedis::get(['id', 'nama_grup']);
        $groups = GroupPenjamin::all();
        return view('pages.simrs.master-data.layanan-medis.tindakan-medis', compact('tindakan_medis', 'grup_tindakan', 'groups'));
    }

    public function getTindakan($id)
    {
        try {
            $tindakan_medis = TindakanMedis::findOrFail($id);

            return response()->json([
                'grup_tindakan_medis_id' => $tindakan_medis->grup_tindakan_medis_id,
                'kode' => $tindakan_medis->kode,
                'nama_tindakan' => $tindakan_medis->nama_tindakan,
                'nama_billing' => $tindakan_medis->nama_billing,
                'is_konsul' => $tindakan_medis->is_konsul,
                'auto_charge' => $tindakan_medis->auto_charge,
                'is_vaksin' => $tindakan_medis->is_vaksin,
                'mapping_rl_13' => $tindakan_medis->mapping_rl_13,
                'mapping_rl_34' => $tindakan_medis->mapping_rl_34,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Data tidak ditemukan',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan pada server',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getTarifByGroup($tindakanId, $groupPenjaminId = null)
    {
        try {
            // Mengambil semua tarif terkait dengan tindakan_medis_id tertentu dan mengonversinya menjadi array
            $tarifList = TarifTindakanMedis::where('tindakan_medis_id', $tindakanId)
                ->with(['group_penjamin', 'kelas_rawat']) // Pastikan relasi dengan Group_penjamin di-load
                ->get()
                ->toArray(); // Mengonversi koleksi menjadi array

            $kelas_rawat = KelasRawat::all()->toArray(); // Mengonversi kelas_rawat menjadi array
            // return dd($kelas_rawat);

            // Jika groupId diberikan, ambil tarif berdasarkan grup penjamin
            if ($groupPenjaminId) {
                // Filter tarif berdasarkan group_penjamin_id
                $tarifsByGroup = array_filter($tarifList, function ($tarif) use ($groupPenjaminId) {
                    return $tarif['group_penjamin_id'] == $groupPenjaminId;
                });

                // Buat array ID kelas rawat yang sudah ada di tarif
                $existingKelasIds = array_map(function ($tarif) {
                    return $tarif['kelas_rawat_id'];
                }, $tarifsByGroup);

                // Tambahkan tarif default untuk kelas rawat yang belum ada
                foreach ($kelas_rawat as $kelas) {
                    if (!in_array($kelas['id'], $existingKelasIds)) {
                        $tarifsByGroup[] = [
                            'kelas_rawat_id' => $kelas['id'],
                            'kelas' => $kelas['kelas'],
                            'id' => null,
                            'tindakan_medis_id' => $tindakanId,
                            'group_penjamin_id' => $groupPenjaminId,
                            'share_dr' => 0,
                            'share_rs' => 0,
                            'prasarana' => 0,
                            'bhp' => 0,
                            'total' => 0,
                        ];
                    }
                }

                // Kembalikan response dengan tarif yang sudah diperbarui
                return response()->json(array_values($tarifsByGroup), 200);
            }


            // Mengembalikan response dengan daftar tarif
            return response()->json(array_values($tarifList), 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan pada server',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getTarif($id)
    {
        try {
            // Mengambil semua tarif terkait dengan tindakan_medis_id tertentu
            $tarifList = TarifTindakanMedis::where('tindakan_medis_id', $id)
                ->with(['group_penjamin', 'kelas_rawat']) // Pastikan relasi dengan Group_penjamin di-load
                ->get();

            // return dd($tarifList);

            $kelas_rawat = KelasRawat::all();

            // Jika tidak ada tarif yang ditemukan, kembalikan array dengan tarif default
            if ($tarifList->isEmpty()) {
                $data = [];
                foreach ($kelas_rawat as $kelas) {
                    $data[] = [ // Tambahkan tarif default ke dalam array
                        'kelas_rawat_id' => $kelas->id,
                        'kelas' => $kelas->kelas,
                        'id' => null,
                        'tindakan_medis_id' => $id,
                        'group_penjamin_id' => null,
                        'share_dr' => 0,
                        'share_rs' => 0,
                        'prasarana' => 0,
                        'bhp' => 0,
                        'total' => 0,
                    ];
                }
                return response()->json($data, 200); // Kembalikan data default jika tidak ada tarif
            }

            // Mengembalikan response dengan daftar tarif
            return response()->json($tarifList, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan pada server',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateTarif(Request $request, $id)
    {
        // Validasi data yang diterima
        $validator = Validator::make($request->all(), [
            '*.group_penjamin_id' => 'required|integer',
            '*.tindakan_medis_id' => 'required|integer',
            '*.kelas_rawat_id' => 'required|integer',
            '*.share_dr' => 'required|numeric',
            '*.share_rs' => 'required|numeric',
            '*.prasarana' => 'required|numeric',
            '*.bhp' => 'required|numeric',
            '*.total' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Ambil data tarif yang akan diperbarui atau dibuat
        $tarifs = $request->all();

        foreach ($tarifs as $tarifData) {
            // Temukan tarif berdasarkan kelas_rawat_id
            $tarif = TarifTindakanMedis::where('kelas_rawat_id', $tarifData['kelas_rawat_id'])
                ->where('tindakan_medis_id', $tarifData['tindakan_medis_id'])
                ->where('group_penjamin_id', $tarifData['group_penjamin_id'])
                ->first();

            if ($tarif) {
                // Jika tarif ditemukan, perbarui data tarif
                $tarif->group_penjamin_id = $tarifData['group_penjamin_id'];
                $tarif->tindakan_medis_id = $tarifData['tindakan_medis_id'];
                $tarif->kelas_rawat_id = $tarifData['kelas_rawat_id'];
                $tarif->share_dr = $tarifData['share_dr'];
                $tarif->share_rs = $tarifData['share_rs'];
                $tarif->prasarana = $tarifData['prasarana'];
                $tarif->bhp = $tarifData['bhp'];
                $tarif->total = $tarifData['total'];
                $tarif->save();
            } else {
                // Jika tarif tidak ditemukan, buat entri baru
                TarifTindakanMedis::create([
                    'group_penjamin_id' => $tarifData['group_penjamin_id'],
                    'tindakan_medis_id' => $tarifData['tindakan_medis_id'],
                    'kelas_rawat_id' => $tarifData['kelas_rawat_id'],
                    'share_dr' => $tarifData['share_dr'],
                    'share_rs' => $tarifData['share_rs'],
                    'prasarana' => $tarifData['prasarana'],
                    'bhp' => $tarifData['bhp'],
                    'total' => $tarifData['total'],
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data tarif berhasil diperbarui atau dibuat',
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'grup_tindakan_medis_id' => 'required',
            'kode' => 'required',
            'nama_tindakan' => 'required',
            'nama_billing' => 'required',
            'is_konsul' => 'nullable',
            'auto_charge' => 'nullable',
            'is_vaksin' => 'nullable',
            'mapping_rl_13' => 'nullable',
            'mapping_rl_34' => 'nullable',
        ]);

        try {
            $store = TindakanMedis::create($validatedData);
            return response()->json(['message' => ' berhasil ditambahkan!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'grup_tindakan_medis_id' => 'required',
            'kode' => 'required',
            'nama_tindakan' => 'required',
            'nama_billing' => 'required',
            'is_konsul' => 'nullable',
            'auto_charge' => 'nullable',
            'is_vaksin' => 'nullable',
            'mapping_rl_13' => 'nullable',
            'mapping_rl_34' => 'nullable',
        ]);

        try {
            $grup_tindakan_medis = TindakanMedis::find($id);
            $grup_tindakan_medis->update($validatedData);
            return response()->json(['message' => ' berhasil diupdate!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $grup_tindakan_medis = TindakanMedis::find($id);
            $grup_tindakan_medis->delete();
            return response()->json(['message' => ' berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getTindakanByDepartementAndKelas(Request $request)
    {
        $tindakan = TindakanMedis::whereHas('grup_tindakan_medis', function ($query) use ($request) {
            $query->where('departement_id', $request->departement_id);
        })->get();
        return response()->json($tindakan);
    }

    public function getTarifTindakan(Request $request)
    {
        $tarif = TarifTindakanMedis::where('tindakan_medis_id', $request->tindakan_id)
            ->where('group_penjamin_id', $request->group_penjamin_id)
            ->where('kelas_rawat_id', $request->kelas_rawat_id)
            ->first();

        return response()->json([
            'harga' => $tarif ? $tarif->total : 0
        ]);
    }
}
