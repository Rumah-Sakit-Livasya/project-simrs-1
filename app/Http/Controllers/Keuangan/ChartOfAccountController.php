<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Keuangan\ChartOfAccount;
use App\Models\Keuangan\GroupChartOfAccount;
use Illuminate\Http\Request;

class ChartOfAccountController extends Controller
{

    public function index()
    {
        return view('app-type.keuangan.chart-of-account.index', [
            'groupCOA' => GroupChartOfAccount::orderBy('id', 'asc')->get(),
            'chartOfAccounts' => ChartOfAccount::orderBy('id', 'asc')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'group_id' => 'required|exists:group_chart_of_account,id',
            'code' => 'required|string|max:20|unique:chart_of_account,code',
            'name' => 'required|string|max:255',
            'header' => 'required|boolean',
            'parent_id' => 'nullable|exists:chart_of_account,id',
            'description' => 'nullable|string',
        ], [
            'group_id.required' => 'Field grup wajib diisi',
            'group_id.exists' => 'Grup yang dipilih tidak valid',
            'code.required' => 'Field kode wajib diisi',
            'code.string' => 'Kode harus berupa string',
            'code.max' => 'Kode tidak boleh lebih dari 20 karakter',
            'code.unique' => 'Kode sudah digunakan',
            'name.required' => 'Field nama wajib diisi',
            'name.string' => 'Nama harus berupa string',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter',
            'header.required' => 'Field header wajib diisi',
            'header.boolean' => 'Header harus bernilai true atau false',
            'parent_id.exists' => 'Parent yang dipilih tidak valid',
        ]);

        $chartOfAccount = ChartOfAccount::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Chart of Account berhasil dibuat.',
            'data' => $chartOfAccount
        ]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'group_id' => 'required|exists:group_chart_of_account,id',
            'code' => 'required|string|max:20|unique:chart_of_account,code,' . $id,
            'name' => 'required|string|max:255',
            'header' => 'required|boolean',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:chart_of_account,id',
        ], [
            'group_id.required' => 'Grup harus diisi.',
            'group_id.exists' => 'Grup yang dipilih tidak valid.',
            'code.required' => 'Kode harus diisi.',
            'code.string' => 'Kode harus berupa teks.',
            'code.max' => 'Kode tidak boleh lebih dari 20 karakter.',
            'code.unique' => 'Kode sudah digunakan, silakan gunakan kode lain.',
            'name.required' => 'Nama harus diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',
            'header.required' => 'Header harus diisi.',
            'header.boolean' => 'Header harus bernilai true atau false.',
            'parent_id.exists' => 'Parent yang dipilih tidak valid.',
        ]);

        $chartOfAccount = ChartOfAccount::findOrFail($id);
        $chartOfAccount->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Chart of Account berhasil diperbarui.',
            'data' => $chartOfAccount
        ]);
    }

    public function getByGroup($group_id)
    {
        $coa = ChartOfAccount::with('children')
            ->where('group_id', $group_id)
            ->whereNull('parent_id')
            ->get();

        return response()->json($this->formatTree($coa));
    }

    public function show($id)
    {
        $coa = ChartOfAccount::findOrFail($id);
        return response()->json($coa);
    }

    private function formatTree($nodes)
    {
        return $nodes->map(function ($node) {
            return [
                'id' => $node->id, // Pastikan ID disertakan
                'code' => $node->code,
                'name' => $node->name,
                'header' => $node->header,
                'children' => $node->children ? $this->formatTree($node->children) : []
            ];
        });
    }
}
