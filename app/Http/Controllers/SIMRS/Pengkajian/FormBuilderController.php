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

        $validatedData = $request->validate([
            'nama_form' => 'required',
            'form_kategori_id' => 'required',
            'form_source' => 'required',
            'is_active' => 'nullable'
        ]);

        try {
            $validatedData['created_by'] = auth()->user()->id;
            $validatedData['modify_by'] = auth()->user()->id;
            $store = FormTemplate::create($validatedData);
            return response()->json(['message' => ' berhasil ditambahkan!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
