<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Keuangan\Bank;
use App\Models\Keuangan\Category;
use App\Models\Keuangan\Transaksi;
use App\Models\Keuangan\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return Bank::all()->first();
        return view('app-type.keuangan.transaksi.index', [
            'transaksi' => Transaksi::all(),
            'types' => Type::all(),
            'banks' => Bank::all(),
            'categories' => Category::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // return $request->type_id;
        $saldo = Bank::where('id', $request->bank_id)->first()->saldo;
        $validatedData = $request->validate([
            'tanggal' => 'max:255|required',
            'category_id' => 'max:255|required',
            'type_id' => 'max:255|required',
            'bank_id' => 'max:255|required',
            'nominal' => 'max:255|required',
            'keterangan' => 'required',
            'foto' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->type_id === "1") { // id 1 = Pemasukan
            $hitung = $saldo + $request->nominal;
        } else if ($request->type_id === "2") {
            $hitung = $saldo - $request->nominal;
        } else {
            return "Cek Tipe Transaksi";
        }

        if ($request->file('foto')) {
            $validatedData['foto'] = $request->file('foto')->store('bukti-transaksi');
        }

        Bank::where('id', $request->bank_id)->update(['saldo' => $hitung]);
        Transaksi::create($validatedData);
        return back()->with('success', 'Transaksi ditambahkan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function show(Transaksi $transaksi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaksi $transaksi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        // return $transaksi;
        $validatedData = $request->validate([
            'tanggal' => 'max:255|required',
            'category_id' => 'max:255|required',
            'bank_id' => 'max:255|required',
            'nominal' => 'max:255|required',
            'jenis' => 'max:255|required',
            'keterangan' => 'required',
            'foto' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($request->file('foto')) {
            if ($request->oldImage) {
                Storage::delete($request->oldImage);
            }

            $validatedData['foto'] = $request->file('foto')->store('bukti-transaksi');
        }

        Transaksi::where('id', $transaksi->id)->update($validatedData);
        return back()->with('success', 'Transaksi diubah!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaksi $transaksi)
    {
        //
    }
}
