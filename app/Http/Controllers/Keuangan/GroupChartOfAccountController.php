<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Keuangan\GroupChartOfAccount;
use Illuminate\Http\Request;

class GroupChartOfAccountController extends Controller
{
    public function index()
    {
        return view('app-type.keuangan.group-chart-of-account.index', [
            'groupChartOfAccount' => GroupChartOfAccount::orderBy('id', 'asc')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'max:255|required',
            'description' => 'nullable',
        ]);

        GroupChartOfAccount::create($validatedData);
        return back()->with('success', 'Group COA ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'max:255|required',
            'description' => 'nullable',
        ]);

        GroupChartOfAccount::where('id', $id)->update($validatedData);
        return back()->with('success', 'Group COA diubah!');
    }
}
