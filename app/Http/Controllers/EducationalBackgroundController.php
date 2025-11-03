<?php

namespace App\Http\Controllers;

use App\Exports\EducationalBackgroundsExport;
use App\Exports\EducationalBackgroundTemplateExport;
use App\Imports\EducationalBackgroundsImport;
use App\Models\EducationalBackground;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class EducationalBackgroundController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::select('id', 'fullname')->get();
        return view('pages.pegawai.educational-background.index', compact('employees'));
    }

    /**
     * API endpoint to fetch data for DataTables.
     */
    public function data()
    {
        $query = Employee::with('educationalBackground')
            ->where('is_active', 1)
            ->select('id', 'fullname');

        return DataTables::of($query)
            ->addColumn('last_education', function ($employee) {
                $background = $employee->educationalBackground;
                return $background ? $background->last_education : 'N/A';
            })
            ->addColumn('graduation_year', function ($employee) {
                $background = $employee->educationalBackground;
                return $background ? $background->graduation_year : 'N/A';
            })
            ->addColumn('diploma_number', function ($employee) {
                $background = $employee->educationalBackground;
                return $background ? $background->diploma_number : 'N/A';
            })
            ->addColumn('action', function ($employee) {
                return '
                    <button class="btn btn-primary btn-xs btn-edit" data-id="' . $employee->id . '">
                        <i class="fal fa-edit"></i>
                    </button>
                    <button class="btn btn-danger btn-xs btn-delete" data-id="' . $employee->id . '">
                        <i class="fal fa-trash-alt"></i>
                    </button>
                ';
            })
            ->editColumn('fullname', function ($employee) {
                return $employee->fullname;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'last_education' => 'required|string|max:100',
            'graduation_year' => 'required|numeric|digits:4',
            'diploma_number' => 'nullable|string|max:150',
            'basic_qualifications' => 'nullable|string',
            'initial_competency' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        EducationalBackground::create($request->all());

        return response()->json(['success' => 'Data berhasil disimpan.']);
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit(int $employee_id)
    {
        $employee = Employee::with('educationalBackground')->find($employee_id);

        if (! $employee) {
            return response()->json([
                'message' => "No query results for model [App\\Models\\Employee] $employee_id",
            ], 404);
        }

        // Jika pegawai belum memiliki educationalBackground, tetap kirim data employee untuk create
        return response()->json(['data' => $employee]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'last_education' => 'nullable|string|max:100',
            'graduation_year' => 'nullable|numeric|digits:4',
            'diploma_number' => 'nullable|string|max:150',
            'basic_qualifications' => 'nullable|string',
            'initial_competency' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $educationalBackground = EducationalBackground::findOrFail($id);
        $educationalBackground->update($request->all());

        return response()->json(['success' => 'Data berhasil diperbarui.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        EducationalBackground::destroy($id);
        return response()->json(['success' => 'Data berhasil dihapus.']);
    }

    /**
     * Download Excel template.
     */
    public function downloadTemplate()
    {
        return Excel::download(new EducationalBackgroundTemplateExport, 'template_pendidikan.xlsx');
    }

    /**
     * Import data from Excel.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new EducationalBackgroundsImport, $request->file('file'));

            return redirect()->back()->with('success', 'Data berhasil diimpor!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = "Baris " . $failure->row() . ": " . implode(", ", $failure->errors());
            }
            return redirect()->back()->with('error', "Gagal mengimpor data. Kesalahan: <br>" . implode("<br>", $errorMessages));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
        }
    }
}
