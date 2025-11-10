<?php

namespace App\Http\Controllers\SIMRS\Pengkajian;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Pengkajian\FormKategori;
use App\Models\SIMRS\Pengkajian\FormTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FormBuilderController extends Controller
{
    public function index(Request $request)
    {
        // Mulai query builder
        $query = FormTemplate::query();

        // Jika ada input pencarian 'nama_form'
        if ($request->has('nama_form') && $request->nama_form != '') {
            $query->where('nama_form', 'like', '%' . $request->nama_form . '%');
        }

        // Ambil data yang sudah difilter
        $form = $query->with('kategori')->latest()->get();

        return view('pages.simrs.master-data.form-builder.index', compact('form'));
    }

    public function create()
    {
        $kategori = FormKategori::latest()->get();
        return view('pages.simrs.master-data.form-builder.tambah', compact('kategori'));
    }

    // public function store(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'nama_form' => 'required|string|max:255',
    //         'form_kategori_id' => 'required|exists:form_kategori,id',
    //         'form_source' => 'required|string',
    //         'is_active' => 'required|boolean'
    //     ]);

    //     try {
    //         $validatedData['created_by'] = auth()->user()->id;
    //         $validatedData['modify_by'] = auth()->user()->id;
    //         FormTemplate::create($validatedData);
    //         return response()->json(['message' => 'Form berhasil ditambahkan!'], 200);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }
    /**
     * Menyimpan form template baru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Pastikan di form HTML/Blade, input untuk nama formulir menggunakan name="name"
        $validator = Validator::make($request->all(), [
            'nama_form'             => 'required|string|max:255',
            'form_kategori_id' => 'required',
            'is_active'        => 'required|boolean',
            'form_source'      => 'nullable|string',
            'print_source' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $kategoriIdInput = $request->input('form_kategori_id');
            $kategoriId = null;

            if (is_numeric($kategoriIdInput)) {
                $kategoriId = $kategoriIdInput;
            } else {
                // firstOrCreate mencegah duplikasi dan aman digunakan.
                $newKategori = FormKategori::firstOrCreate(
                    ['nama_kategori' => $kategoriIdInput],
                    ['entry_by' => auth()->id()] // Contoh jika ingin melacak pembuat kategori
                );
                $kategoriId = $newKategori->id;
            }

            FormTemplate::create([
                'nama_form'             => $request->input('nama_form'), // Konsisten dengan validasi
                'form_kategori_id' => $kategoriId,
                'form_source'      => $request->input('form_source'),
                'is_active'        => $request->input('is_active'),
                'created_by'       => auth()->id(),
                'modify_by'       => auth()->id(),
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Template Formulir berhasil dibuat!'
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Cari data form berdasarkan ID
        $formTemplate = FormTemplate::findOrFail($id);

        // Ambil semua kategori untuk dropdown
        $kategori = FormKategori::latest()->get();

        // Return view 'edit' dengan data yang diperlukan
        return view('pages.simrs.master-data.form-builder.edit', compact('formTemplate', 'kategori'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validasi data yang masuk
        $validatedData = $request->validate([
            'nama_form' => 'required|string|max:255',
            'form_kategori_id' => 'required|exists:form_kategori,id',
            'form_source' => 'required|string',
            'print_source' => 'nullable|string',
            'is_active' => 'required|boolean'
        ]);

        try {
            // Cari form yang akan diupdate
            $formTemplate = FormTemplate::findOrFail($id);

            $validatedData['modify_by'] = auth()->user()->id;

            // Update data di database
            $formTemplate->update($validatedData);

            // Kirim respons JSON sukses
            return response()->json(['message' => 'Form berhasil diperbarui!'], 200);
        } catch (\Exception $e) {
            // Kirim respons JSON error
            return response()->json(['error' => 'Gagal memperbarui form: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $formTemplate = FormTemplate::findOrFail($id);
            $formTemplate->delete();

            return response()->json(['message' => 'Form berhasil dihapus!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus form: ' . $e->getMessage()], 500);
        }
    }
    public function getPrintPreview($id)
    {
        try {
            $formTemplate = FormTemplate::findOrFail($id);

            // Prioritaskan print_source. Jika kosong, gunakan form_source.
            $content = $formTemplate->print_source ?: $formTemplate->form_source;

            if (empty($content)) {
                return response()->json(['success' => false, 'message' => 'Konten untuk template ini kosong.'], 404);
            }

            return response()->json(['success' => true, 'content' => $content]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Template tidak ditemukan.'], 404);
        }
    }
}
