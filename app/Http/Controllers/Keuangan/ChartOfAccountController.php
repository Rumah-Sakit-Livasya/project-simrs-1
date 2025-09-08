<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Keuangan\ChartOfAccount;
use App\Models\Keuangan\GroupChartOfAccount;
use Illuminate\Http\Request;

class ChartOfAccountController extends Controller
{
    public function index(Request $request)
    {
        // Method index sekarang hanya menyiapkan data untuk filter dan modal
        $groupCOA = GroupChartOfAccount::orderBy('id', 'asc')->get();

        return view('app-type.keuangan.chart-of-account.index', [
            'groupCOA' => $groupCOA,
        ]);
    }

    /**
     * Metode baru untuk mengambil SEMUA COA (untuk tampilan default).
     */
    public function getAll()
    {
        $allCoa = ChartOfAccount::with('childrenRecursive')
            ->whereNull('parent_id') // Mulai dari root
            ->orderBy('code', 'asc')
            ->get();

        return response()->json($this->formatTree($allCoa));
    }

    public function getByGroup($group_id)
    {
        $coa = ChartOfAccount::with('childrenRecursive')
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
                'group_name' => $node->group->name, // Tambahkan nama grup untuk kejelasan
                'children' => $node->childrenRecursive->isNotEmpty() ? $this->formatTree($node->childrenRecursive) : []
            ];
        });
    }

    public function store(Request $request)
    {
        // Validasi dan logika store tidak berubah
        $validatedData = $request->validate([
            'group_id' => 'required|exists:group_chart_of_account,id',
            'code' => 'required|string|max:20|unique:chart_of_account,code',
            'name' => 'required|string|max:255',
            'header' => 'required|boolean',
            'parent_id' => 'nullable|exists:chart_of_account,id',
            'description' => 'nullable|string',
        ]);
        $chartOfAccount = ChartOfAccount::create($validatedData);
        return response()->json(['success' => true, 'message' => 'Chart of Account berhasil dibuat.', 'data' => $chartOfAccount]);
    }

    public function update(Request $request, $id)
    {
        // Validasi dan logika update tidak berubah
        $validatedData = $request->validate([
            'group_id' => 'required|exists:group_chart_of_account,id',
            'code' => 'required|string|max:20|unique:chart_of_account,code,' . $id,
            'name' => 'required|string|max:255',
            'header' => 'required|boolean',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:chart_of_account,id',
        ]);
        $chartOfAccount = ChartOfAccount::findOrFail($id);
        $chartOfAccount->update($validatedData);
        return response()->json(['success' => true, 'message' => 'Chart of Account berhasil diperbarui.', 'data' => $chartOfAccount]);
    }

    public function show($id)
    {
        $coa = ChartOfAccount::with('group')->findOrFail($id); // Eager load group untuk modal edit
        return response()->json($coa);
    }

    public function getParents(Request $request)
    {
        // Logika getParents tidak berubah
        $request->validate(['group_id' => 'nullable|integer']);
        $query = ChartOfAccount::where('header', true);
        if ($request->filled('group_id')) {
            $query->where('group_id', $request->group_id);
        }
        $parents = $query->orderBy('code')->get(['id', 'code', 'name']);
        return response()->json($parents);
    }
}
