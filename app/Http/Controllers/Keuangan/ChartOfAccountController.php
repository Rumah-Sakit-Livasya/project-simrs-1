<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Keuangan\ChartOfAccount;
use App\Models\Keuangan\GroupChartOfAccount;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ChartOfAccountController extends Controller
{
    public function index()
    {
        $groupCOA = GroupChartOfAccount::orderBy('id', 'asc')->get();

        return view('app-type.keuangan.chart-of-account.index', [
            'groupCOA' => $groupCOA,
        ]);
    }

    public function getAll()
    {
        $allCoa = ChartOfAccount::with(['childrenRecursive', 'group'])
            ->whereNull('parent_id')
            ->orderBy('code', 'asc')
            ->get();

        return response()->json($this->formatTree($allCoa));
    }

    public function getByGroup($group_id)
    {
        $coa = ChartOfAccount::with(['childrenRecursive', 'group'])
            ->where('group_id', $group_id)
            ->whereNull('parent_id')
            ->orderBy('code', 'asc')
            ->get();

        return response()->json($this->formatTree($coa));
    }

    private function formatTree($nodes)
    {
        return $nodes->map(function ($node) {
            return [
                'id' => $node->id,
                'code' => $node->code,
                'name' => $node->name,
                'header' => (bool) $node->header,
                'status' => (bool) $node->status,
                'default' => $node->default,
                'group_name' => $node->group->name,
                'children' => $node->childrenRecursive->isNotEmpty() ? $this->formatTree($node->childrenRecursive) : []
            ];
        });
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'group_id' => 'required|exists:group_chart_of_account,id',
            'code' => 'required|string|max:20|unique:chart_of_account,code',
            'name' => 'required|string|max:255',
            'header' => 'required|boolean',
            'parent_id' => 'nullable|exists:chart_of_account,id',
            'default' => ['required', Rule::in(['Debet', 'Credit'])], // Validasi ENUM dengan ejaan yang benar
            'description' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        $chartOfAccount = ChartOfAccount::create($validatedData);
        return response()->json(['success' => true, 'message' => 'Chart of Account berhasil dibuat.', 'data' => $chartOfAccount]);
    }

    public function show($id)
    {
        $coa = ChartOfAccount::with('group')->findOrFail($id);
        return response()->json($coa);
    }

    public function update(Request $request, $id)
    {
        $chartOfAccount = ChartOfAccount::findOrFail($id);
        $validatedData = $request->validate([
            'group_id' => 'required|exists:group_chart_of_account,id',
            'code' => 'required|string|max:20|unique:chart_of_account,code,' . $id,
            'name' => 'required|string|max:255',
            'header' => 'required|boolean',
            'parent_id' => 'nullable|exists:chart_of_account,id',
            'default' => ['required', Rule::in(['Debet', 'Credit'])], // Validasi ENUM dengan ejaan yang benar
            'description' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        $chartOfAccount->update($validatedData);
        return response()->json(['success' => true, 'message' => 'Chart of Account berhasil diperbarui.', 'data' => $chartOfAccount]);
    }

    public function destroy($id)
    {
        $coa = ChartOfAccount::findOrFail($id);
        if ($coa->children()->exists()) {
            return response()->json(['success' => false, 'message' => 'Hapus gagal! Akun ini memiliki sub-akun.'], 422);
        }
        $coa->delete();
        return response()->json(['success' => true, 'message' => 'Chart of Account berhasil dihapus.']);
    }

    public function getParents(Request $request)
    {
        $query = ChartOfAccount::where('header', true);
        if ($request->filled('group_id')) {
            $query->where('group_id', $request->group_id);
        }
        if ($request->filled('exclude_id')) {
            $query->where('id', '!=', $request->exclude_id);
        }
        $parents = $query->orderBy('code')->get(['id', 'code', 'name']);
        return response()->json($parents);
    }
}
