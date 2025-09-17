<?php

namespace App\Http\Controllers\SIMRS\Laboratorium;

use App\Exports\NilaiNormalExport;
use App\Http\Controllers\Controller;
use App\Imports\NilaiNormalImport;
use App\Models\SIMRS\Laboratorium\NilaiNormalLaboratorium;
use App\Models\SIMRS\Laboratorium\ParameterLaboratorium;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class NilaiNormalLaboratoriumController extends Controller
{
    public function index()
    {
        $parameter = ParameterLaboratorium::all();
        $nilai_parameter = NilaiNormalLaboratorium::all();
        return view('pages.simrs.master-data.penunjang-medis.laboratorium.nilai-parameter', compact('parameter', 'nilai_parameter'));
    }

    public function getNilaiNormal($id)
    {
        try {
            $nilai_parameter_laboratorium = NilaiNormalLaboratorium::findOrFail($id);

            $minAge = explode('-', $nilai_parameter_laboratorium['dari_umur']);
            $nilai_parameter_laboratorium['tahun_1'] = $minAge[0];
            $nilai_parameter_laboratorium['bulan_1'] = $minAge[1];
            $nilai_parameter_laboratorium['hari_1'] = $minAge[2];

            $maxAge = explode('-', $nilai_parameter_laboratorium['sampai_umur']);
            $nilai_parameter_laboratorium['tahun_2'] = $maxAge[0];
            $nilai_parameter_laboratorium['bulan_2'] = $maxAge[1];
            $nilai_parameter_laboratorium['hari_2'] = $maxAge[2];

            return response()->json($nilai_parameter_laboratorium, 200);
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
        // return response()->json($request->all());
        $validatedData = $request->validate([
            'user_input' => 'required|integer',
            'tanggal' => 'required|date',
            'parameter_laboratorium_id' => 'required|integer',
            'jenis_kelamin' => 'required|string',
            'tahun_1' => 'required|integer',
            'bulan_1' => 'required|integer',
            'hari_1' => 'required|integer',
            'tahun_2' => 'required|integer',
            'bulan_2' => 'required|integer',
            'hari_2' => 'required|integer',
            'min' => 'nullable|numeric',
            'max' => 'nullable|numeric',
            'nilai_normal' => 'nullable|string',
            'hasil' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'min_kritis' => 'nullable|numeric',
            'max_kritis' => 'nullable|numeric',
        ]);

        $validatedData['dari_umur'] = $validatedData['tahun_1'] . '-' . $validatedData['bulan_1'] . '-' . $validatedData['hari_1'];
        $validatedData['sampai_umur'] = $validatedData['tahun_2'] . '-' . $validatedData['bulan_2'] . '-' . $validatedData['hari_2'];

        try {
            $store = NilaiNormalLaboratorium::create($validatedData);
            return response()->json(['message' => ' berhasil ditambahkan!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'user_input' => 'required|integer',
            'tanggal' => 'required|date',
            'parameter_laboratorium_id' => 'required|integer',
            'jenis_kelamin' => 'required|string',
            'tahun_1' => 'required|integer',
            'bulan_1' => 'required|integer',
            'hari_1' => 'required|integer',
            'tahun_2' => 'required|integer',
            'bulan_2' => 'required|integer',
            'hari_2' => 'required|integer',
            'min' => 'required|numeric',
            'max' => 'required|numeric',
            'nilai_normal' => 'required|string',
            'hasil' => 'required|string',
            'keterangan' => 'required|string',
            'min_kritis' => 'required|numeric',
            'max_kritis' => 'required|numeric',
        ]);

        $validatedData['dari_umur'] = $validatedData['tahun_1'] . '-' . $validatedData['bulan_1'] . '-' . $validatedData['hari_1'];
        $validatedData['sampai_umur'] = $validatedData['tahun_2'] . '-' . $validatedData['bulan_2'] . '-' . $validatedData['hari_2'];

        try {
            $nilai_parameter_laboratorium = NilaiNormalLaboratorium::find($id);
            $nilai_parameter_laboratorium->update($validatedData);
            return response()->json(['message' => ' berhasil diupdate!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $nilai_parameter_laboratorium = NilaiNormalLaboratorium::find($id);
            $nilai_parameter_laboratorium->delete();
            return response()->json(['message' => ' berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Export data nilai normal ke file Excel.
     */
    public function export()
    {
        return Excel::download(new NilaiNormalExport, 'template_nilai_normal_laboratorium.xlsx');
    }

    /**
     * Import data nilai normal dari file Excel.
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xls,xlsx'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            Excel::import(new NilaiNormalImport, $request->file('file'));

            return back()->with('success', 'Data nilai normal berhasil diimport!');
        } catch (ValidationException $e) {
            // Ini adalah bagian yang diubah untuk logging
            $failures = $e->failures();
            $errorMessages = [];

            foreach ($failures as $failure) {
                $row = $failure->row(); // baris yang error
                $attribute = $failure->attribute(); // kolom yang error
                $errors = implode(', ', $failure->errors()); // pesan error
                $values = json_encode($failure->values()); // nilai yang diinput

                // Buat pesan log yang detail
                $logMessage = "Validation Error on Row {$row}: Attribute '{$attribute}' - Errors: [{$errors}] - Input Values: {$values}";

                // Tulis ke laravel.log
                Log::error('[NILAI_NORMAL_IMPORT] ' . $logMessage);

                // Kumpulkan pesan untuk ditampilkan ke user
                $errorMessages[] = "Baris {$row}: {$errors} (Kolom: {$attribute})";
            }

            // Kembalikan ke halaman dengan pesan error yang lebih spesifik
            return back()->withErrors($errorMessages)->withInput();
        } catch (\Exception $e) {
            // Tangani error umum lainnya
            Log::error('[NILAI_NORMAL_IMPORT] General Error: ' . $e->getMessage());
            return back()->withErrors(['file' => 'Terjadi kesalahan umum pada server: ' . $e->getMessage()]);
        }
    }
}
