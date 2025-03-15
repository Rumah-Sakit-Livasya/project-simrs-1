<?php

namespace App\Http\Controllers\SIMRS\Pengkajian;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Pengkajian\FormKategori;
use App\Models\SIMRS\Pengkajian\FormTemplate;
use Illuminate\Http\Request;

class FormBuilderController extends Controller
{
    public function index()
    {
        $form = FormTemplate::latest()->get();
        return view('pages.simrs.master-data.form-builder.index', compact('form'));
    }

    public function create()
    {
        $kategori = FormKategori::latest()->get();
        return view('pages.simrs.master-data.form-builder.tambah', compact('kategori'));
    }

    public function store(Request $request)
    {

        dd($request);
        // $validatedData = $request->validate([
            
        // ]);

        try {
            $validatedData['is_verified'] = 1;
            $validatedData['awal_rencana_tindak_lanjut'] = json_encode($request->awal_rencana_tindak_lanjut);
            $validatedData['awal_evaluasi_penyakit'] = json_encode($request->awal_evaluasi_penyakit);
            $validatedData['awal_edukasi'] = json_encode($request->awal_edukasi);
            $validatedData['asesmen_dilakukan_melalui'] = json_encode($request->asesmen_dilakukan_melalui);
            $validatedData['user_id'] = auth()->user()->id;
            if ($request->action_type = 'final') {
                $validatedData['is_final'] = 1;
            }
            $store = PengkajianDokterRajal::create($validatedData);
            return response()->json(['message' => ' berhasil ditambahkan!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
