<?php

namespace App\Http\Controllers\SIMRS\Operasi;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\GroupPenjamin;
use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\Operasi\JenisOperasi;
use App\Models\SIMRS\Operasi\KategoriOperasi;
use App\Models\SIMRS\Operasi\TindakanOperasi;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\Room;
use Illuminate\Http\Request;

class TindakanOperasiController extends Controller
{
    public function index()
    {
        $tindakan_operasi = TindakanOperasi::all();
        $jenis_operasi = JenisOperasi::all();
        $kategori_operasi = KategoriOperasi::all();
        return view('pages.simrs.master-data.operasi.tindakan.index', compact('tindakan_operasi', 'jenis_operasi', 'kategori_operasi'));
    }

    public function getTindakan($id)
    {
        try {
            $tindakan_operasi = TindakanOperasi::findOrFail($id);

            return response()->json([
                'jenis_operasi_id' => $tindakan_operasi->jenis_operasi_id,
                'kategori_operasi_id' => $tindakan_operasi->kategori_operasi_id,
                'kode_operasi' => $tindakan_operasi->kode_operasi,
                'nama_operasi' => $tindakan_operasi->nama_operasi,
                'nama_billing' => $tindakan_operasi->nama_billing,
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

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'jenis_operasi_id' => 'required',
            'kategori_operasi_id' => 'required',
            'nama_operasi' => 'required',
            'nama_billing' => 'required',
            'kode_operasi' => 'required'
        ]);

        try {
            $store = TindakanOperasi::create($validatedData);
            return response()->json(['message' => ' berhasil ditambahkan!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function tarifPersalinan($id)
    {
        $tindakan_operasi = TindakanOperasi::findOrFail($id);
        $grup_penjamin = GroupPenjamin::all();
        $kelas_rawat = KelasRawat::select('id', 'kelas')->get();

        return view('pages.simrs.master-data.operasi.tarif.index', compact('tindakan_operasi', 'grup_penjamin', 'kelas_rawat'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'jenis_operasi_id' => 'required',
            'kategori_operasi_id' => 'required',
            'nama_operasi' => 'required',
            'nama_billing' => 'required',
            'kode_operasi' => 'required'
        ]);

        try {
            $tindakan_operasi = TindakanOperasi::find($id);
            $tindakan_operasi->update($validatedData);
            return response()->json(['message' => ' berhasil diupdate!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $tindakan_operasi = TindakanOperasi::find($id);
            $tindakan_operasi->delete();
            return response()->json(['message' => ' berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function createOrderOperasi($registrationId)
    {
        $registration = Registration::findOrFail($registrationId);

        $data = [
            'registration' => $registration,
            'ruangan' => Room::all(),
            'kelas' => KelasRawat::all(),
            'jenisOperasi' => JenisOperasi::all(),
            'kategoriOperasi' => KategoriOperasi::all(),
            'tindakanOperasi' => TindakanOperasi::all()
        ];

        return view('your.view.name', $data);
    }
}
