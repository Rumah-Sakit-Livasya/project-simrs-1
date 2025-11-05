<?php

namespace App\Http\Controllers\SIMRS\Laboratorium;

use App\Http\Controllers\Controller;
use App\Models\RelasiParameterLaboratorium;
use App\Models\SIMRS\GroupPenjamin;
use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\Laboratorium\GrupParameterLaboratorium;
use App\Models\SIMRS\Laboratorium\KategoriLaboratorium;
use App\Models\SIMRS\Laboratorium\TipeLaboratorium;
use App\Models\SIMRS\Laboratorium\ParameterLaboratorium;
use Illuminate\Http\Request;

class ParameterLaboratoriumController extends Controller
{
    public function index()
    {
        $parameter = ParameterLaboratorium::all();
        $grup_parameter = GrupParameterLaboratorium::all();
        $kategori = KategoriLaboratorium::all();
        $tipe = TipeLaboratorium::all();
        return view('pages.simrs.master-data.penunjang-medis.laboratorium.parameter', compact('parameter', 'grup_parameter', 'kategori', 'tipe'));
    }

    public function getParameter($id)
    {
        try {
            $parameter_laboratorium = ParameterLaboratorium::findOrFail($id);
            $sub_parameters = RelasiParameterLaboratorium::where('main_parameter_id', $id)->get();
            $parameter_laboratorium->sub_parameters = $sub_parameters;
            return response()->json($parameter_laboratorium, 200);
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

    public function tarifParameter($id)
    {
        $parameter_laboratorium = ParameterLaboratorium::findOrFail($id);
        $grup_penjamin = GroupPenjamin::all();

        $kelas_rawat = KelasRawat::query()
            ->with(['tarif_parameter_laboratorium' => function ($query) use ($id) {
                // Eager load relasi tarif, tetapi hanya untuk parameter laboratorium yang relevan
                // dan grup penjamin default (misal, ID 1)
                $query->where('parameter_laboratorium_id', $id)
                    ->where('group_penjamin_id', 1); // Penjamin default saat pertama kali load
            }])
            ->select('id', 'kelas', 'urutan')
            ->orderBy('urutan', 'asc')
            ->get();

        return view('pages.simrs.master-data.penunjang-medis.laboratorium.tarif-parameter-lab', compact('parameter_laboratorium', 'grup_penjamin', 'kelas_rawat'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'grup_parameter_laboratorium_id' => 'required',
            'kategori_laboratorium_id' => 'required',
            'tipe_laboratorium_id' => 'required',
            'parameter' => 'required',
            'satuan' => 'nullable',
            'status' => 'nullable',
            'is_hasil' => 'nullable',
            'is_order' => 'nullable',
            'tipe_hasil' => 'nullable',
            'metode' => 'nullable',
            'no_urut' => 'nullable'
        ]);

        $validatedData['is_hasil'] = $request->is_hasil === "on" ? 1 : 0;
        $validatedData['is_order'] = $request->is_order === "on" ? 1 : 0;
        $lastKode = \DB::table('parameter_laboratorium')->max('kode');
        $validatedData['kode'] = $lastKode ? $lastKode + 1 : 1;

        try {
            $store = ParameterLaboratorium::create($validatedData);

            // for each sub_parameter, add relation between the new parameter and
            // existing sub parameter to `relasi_parameter_laboratorium` table
            if ($request->has('sub_parameter')) {
                $subParameterIds = $request->input('sub_parameter');
                foreach ($subParameterIds as $subParameterId) {
                    \DB::table('relasi_parameter_laboratorium')->insert([
                        'main_parameter_id' => $store->id,
                        'sub_parameter_id' => $subParameterId
                    ]);
                }
            }

            return response()->json(['message' => ' berhasil ditambahkan!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'grup_parameter_laboratorium_id' => 'required',
            'kategori_laboratorium_id' => 'required',
            'tipe_laboratorium_id' => 'required',
            'kode' => 'required',
            'parameter' => 'required',
            'satuan' => 'nullable',
            'status' => 'nullable',
            'is_hasil' => 'nullable',
            'is_order' => 'nullable',
            'tipe_hasil' => 'nullable',
            'metode' => 'nullable',
            'no_urut' => 'nullable',
            'sub_parameter' => 'nullable',
        ]);

        $validatedData['is_hasil'] = $request->is_hasil === "on" ? 1 : 0;
        $validatedData['is_order'] = $request->is_order === "on" ? 1 : 0;

        try {
            $parameter_laboratorium = ParameterLaboratorium::find($id);
            if ($request->has('sub_parameter')) { // update relations between main and sub parameters
                $parameter_laboratorium = ParameterLaboratorium::find($id);
                $parameter_laboratorium->subParameters()->sync($request->sub_parameter);
            }

            $parameter_laboratorium->update($validatedData);
            return response()->json(['message' => ' berhasil diupdate!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $grup_parameter_laboratorium = ParameterLaboratorium::find($id);
            $grup_parameter_laboratorium->delete();
            return response()->json(['message' => ' berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
