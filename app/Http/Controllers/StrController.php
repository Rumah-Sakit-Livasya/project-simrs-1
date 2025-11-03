<?php

namespace App\Http\Controllers;

use App\Models\Str;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Exports\StrsExport;
use App\Exports\StrTemplateExport;
use App\Imports\StrsImport;
use Maatwebsite\Excel\Facades\Excel;

class StrController extends Controller
{
    public function index()
    {
        $employees = Employee::select('id', 'fullname')->where('is_active', 1)->get();
        // Buat folder 'str' di dalam 'resources/views/pages'
        return view('pages.pegawai.str.index', compact('employees'));
    }

    public function data()
    {
        $query = Str::with('employee:id,fullname')->select('strs.*');
        return DataTables::of($query)
            ->addColumn('action', function ($row) {
                return '<button class="btn btn-primary btn-xs btn-edit" data-id="' . $row->id . '"><i class="fal fa-edit"></i></button>
                        <button class="btn btn-danger btn-xs btn-delete" data-id="' . $row->id . '"><i class="fal fa-trash-alt"></i></button>';
            })
            ->editColumn('employee.fullname', fn($row) => $row->employee ? $row->employee->fullname : 'N/A')
            ->rawColumns(['action'])->make(true);
    }


    public function store(Request $request)
    {
        // Validasi akan bergantung pada apakah 'is_lifetime' dicentang
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'str_number' => 'required|string',
            'is_lifetime' => 'nullable|boolean', // is_lifetime boleh ada atau tidak
            // 'str_expiry_date' wajib diisi HANYA JIKA 'is_lifetime' tidak dicentang (atau tidak ada)
            'str_expiry_date' => 'required_if:is_lifetime,false|nullable|date',
        ]);

        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);

        // Siapkan data untuk disimpan
        $data = $request->only(['employee_id', 'str_number']);
        $data['is_lifetime'] = $request->has('is_lifetime'); // true jika dicentang, false jika tidak
        $data['str_expiry_date'] = $data['is_lifetime'] ? null : $request->str_expiry_date; // null jika seumur hidup

        Str::create($data);
        return response()->json(['success' => 'Data STR berhasil disimpan.']);
    }

    public function edit(string $id)
    {
        $data = Str::find($id);
        if (!$data) return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        return response()->json($data);
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'str_number' => 'required|string',
            'is_lifetime' => 'nullable|boolean',
            'str_expiry_date' => 'required_if:is_lifetime,false|nullable|date',
        ]);

        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);

        $data = $request->only(['employee_id', 'str_number']);
        $data['is_lifetime'] = $request->has('is_lifetime');
        $data['str_expiry_date'] = $data['is_lifetime'] ? null : $request->str_expiry_date;

        Str::findOrFail($id)->update($data);
        return response()->json(['success' => 'Data STR berhasil diperbarui.']);
    }

    public function destroy(string $id)
    {
        Str::destroy($id);
        return response()->json(['success' => 'Data STR berhasil dihapus.']);
    }

    public function export()
    {
        return Excel::download(new StrsExport, 'data_str.xlsx');
    }

    public function downloadTemplate()
    {
        return Excel::download(new StrTemplateExport, 'template_str.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls']);
        try {
            Excel::import(new StrsImport, $request->file('file'));
            return redirect()->back()->with('success', 'Data STR berhasil diimpor!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) $errorMessages[] = "Baris " . $failure->row() . ": " . implode(", ", $failure->errors());
            return redirect()->back()->with('error', "Gagal mengimpor: <br>" . implode("<br>", $errorMessages));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
