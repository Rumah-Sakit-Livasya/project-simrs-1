<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Keuangan\Piutang;
use Illuminate\Http\Request;

class PiutangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('app-type.keuangan.piutang.index', [
            'piutang' => Piutang::all(),
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
        $validatedData = $request->validate([
            'tanggal' => 'max:255|required',
            'nominal' => 'max:255|required',
            'keterangan' => 'required',
        ]);

        Piutang::create($validatedData);
        return back()->with('success', 'Piutang ditambahkan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Piutang  $piutang
     * @return \Illuminate\Http\Response
     */
    public function show(Piutang $piutang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Piutang  $piutang
     * @return \Illuminate\Http\Response
     */
    public function edit(Piutang $piutang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Piutang  $piutang
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Piutang $piutang)
    {
        $validatedData = $request->validate([
            'tanggal' => 'max:255|required',
            'nominal' => 'max:255|required',
            'keterangan' => 'required',
        ]);

        Piutang::where('id', $piutang->id)->update($validatedData);
        return back()->with('success', 'Piutang diubah!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Piutang  $piutang
     * @return \Illuminate\Http\Response
     */
    public function destroy(Piutang $piutang)
    {
        //
    }
}
