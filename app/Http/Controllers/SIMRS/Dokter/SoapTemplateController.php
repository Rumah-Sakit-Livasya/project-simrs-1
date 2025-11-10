<?php

namespace App\Http\Controllers\SIMRS\Dokter;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Dokter\SoapTemplate;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SoapTemplateController extends Controller
{
    public function index()
    {
        return view('pages.simrs.dokter.template-soap.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            $data = SoapTemplate::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('pages.simrs.dokter.template-soap._actions', compact('row'));
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function create()
    {
        return view('pages.simrs.dokter.template-soap.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'template_name' => 'required|string|max:255|unique:soap_templates,template_name',
        ]);

        try {
            SoapTemplate::create($request->all());
            return redirect()->route('dokter.template-soap.index')->with('success', 'Template SOAP berhasil ditambahkan.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Gagal menambahkan template: ' . $th->getMessage())->withInput();
        }
    }

    public function edit(SoapTemplate $soapTemplate)
    {
        return view('pages.simrs.dokter.template-soap.edit', compact('soapTemplate'));
    }

    public function update(Request $request, SoapTemplate $soapTemplate)
    {
        $request->validate([
            'template_name' => 'required|string|max:255|unique:soap_templates,template_name,' . $soapTemplate->id,
        ]);

        try {
            $soapTemplate->update($request->all());
            return redirect()->route('dokter.template-soap.index')->with('success', 'Template SOAP berhasil diperbarui.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Gagal memperbarui template: ' . $th->getMessage())->withInput();
        }
    }

    public function destroy(SoapTemplate $soapTemplate)
    {
        try {
            $soapTemplate->delete();
            return response()->json(['status' => 'success', 'message' => 'Template berhasil dihapus.']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus template.'], 500);
        }
    }
}
