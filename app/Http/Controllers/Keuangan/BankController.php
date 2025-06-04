<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Keuangan\Bank;
use App\Models\Keuangan\ChartOfAccount;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function index()
    {
        return view('app-type.keuangan.bank.index', [
            'banks' => Bank::all(),
            'chartOfAccounts' => ChartOfAccount::orderBy('id', 'asc')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'max:255|required',
            'pemilik' => 'max:255|required',
            'nomor' => 'max:255|required',
            'saldo' => 'max:255|required',
            'akun_kas_bank' => 'nullable|exists:chart_of_account,id',
            'akun_kliring' => 'nullable|exists:chart_of_account,id',
            'is_aktivasi' => 'nullable',
            'is_bank' => 'nullable',
        ]);

        Bank::create($validatedData);
        return back()->with('success', 'Bank ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama' => 'max:255|required',
            'pemilik' => 'max:255|required',
            'nomor' => 'max:255|required',
            'saldo' => 'max:255|required',
            'akun_kas_bank' => 'nullable|exists:chart_of_account,id',
            'akun_kliring' => 'nullable|exists:chart_of_account,id',
            'is_aktivasi' => 'nullable',
            'is_bank' => 'nullable',
        ]);

        Bank::where('id', $id)->update($validatedData);
        return back()->with('success', 'Bank diubah!');
    }
}
