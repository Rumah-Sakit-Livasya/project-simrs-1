<?php

namespace App\Http\Controllers;

use App\Models\Sip;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Exports\SipsExport;
use App\Exports\SipTemplateExport;
use App\Imports\SipsImport;
use Maatwebsite\Excel\Facades\Excel;

class SipController extends Controller
{
    public function index()
    {
        $employees = Employee::select('id', 'fullname')->where('is_active', 1)->get();
        // Buat folder 'sip' di dalam 'resources/views/pages'
        return view('pages.pegawai.sip.index', compact('employees'));
    }

    public function data()
    {
        $query = Sip::with('employee:id,fullname')->select('sips.*');
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
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'sip_number' => 'required|string',
            'sip_expiry_date' => 'required|date',
        ]);

        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);
        Sip::create($request->all());
        return response()->json(['success' => 'Data SIP berhasil disimpan.']);
    }

    public function edit(string $id)
    {
        $data = Sip::find($id);
        if (!$data) return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        return response()->json($data);
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'sip_number' => 'required|string',
            'sip_expiry_date' => 'required|date',
        ]);

        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);
        Sip::findOrFail($id)->update($request->all());
        return response()->json(['success' => 'Data SIP berhasil diperbarui.']);
    }

    public function destroy(string $id)
    {
        Sip::destroy($id);
        return response()->json(['success' => 'Data SIP berhasil dihapus.']);
    }

    public function export()
    {
        return Excel::download(new SipsExport, 'data_sip.xlsx');
    }
    public function downloadTemplate()
    {
        return Excel::download(new SipTemplateExport, 'template_sip.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls']);
        try {
            Excel::import(new SipsImport, $request->file('file'));
            return redirect()->back()->with('success', 'Data SIP berhasil diimpor!');
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
