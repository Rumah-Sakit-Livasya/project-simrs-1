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
        $groupCOA = GroupChartOfAccount::orderBy('id', 'asc')->get();

        // ========================================================
        //          PERBAIKAN UTAMA: HILANGKAN KOMENTAR
        // ========================================================
        // Variabel ini diperlukan oleh modal untuk dropdown 'Parent COA'.
        // Kita ambil semua COA yang merupakan header.
        $chartOfAccounts = ChartOfAccount::where('header', true)
            ->orderBy('code', 'asc')
            ->get();

        return view('app-type.keuangan.chart-of-account.index', [
            'groupCOA' => $groupCOA,
            'chartOfAccounts' => $chartOfAccounts, // Kirim variabel ini ke view
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
        // Query ini sekarang akan bekerja dengan benar karena sudah ada data
        // yang `parent_id`-nya NULL.
        $coa = ChartOfAccount::with('childrenRecursive') // Eager load semua turunan
            ->where('group_id', $group_id)
            ->whereNull('parent_id') // Mencari "akar" dari hierarki
            ->orderBy('code', 'asc')
            ->get();

        // Kirim data yang sudah berbentuk hierarki ke fungsi format
        return response()->json($this->formatTree($coa));
    }

    /**
     * Fungsi rekursif untuk memformat data pohon agar sesuai dengan frontend.
     */
    private function formatTree($nodes)
    {
        // Pastikan semua field yang dibutuhkan oleh JavaScript ada di sini
        return $nodes->map(function ($node) {
            return [
                'id' => $node->id,
                'code' => $node->code,
                'name' => $node->name,
                'header' => (bool) $node->header,
                'status' => (bool) $node->status,
                'default' => $node->default,
                'children' => $node->childrenRecursive->isNotEmpty() ? $this->formatTree($node->childrenRecursive) : []
            ];
        });
    }
    public function show($id)
    {
        $coa = ChartOfAccount::findOrFail($id);
        return response()->json($coa);
    }

    // private function formatTree($nodes)
    // {
    //     return $nodes->map(function ($node) {
    //         return [
    //             'id' => $node->id, // Pastikan ID disertakan
    //             'code' => $node->code,
    //             'name' => $node->name,
    //             'header' => $node->header,
    //             'children' => $node->children ? $this->formatTree($node->children) : []
    //         ];
    //     });
    // }

    public function getParents(Request $request)
    {
        // Validasi sederhana untuk memastikan group_id adalah angka
        $request->validate(['group_id' => 'nullable|integer']);

        // Query dasar: Hanya ambil akun yang merupakan 'header'
        $query = ChartOfAccount::where('header', true);

        // Filter berdasarkan grup HANYA JIKA group_id diberikan
        if ($request->filled('group_id')) {
            $query->where('group_id', $request->group_id);
        }

        $parents = $query->orderBy('code')->get(['id', 'code', 'name']);

        return response()->json($parents);
    }
}
