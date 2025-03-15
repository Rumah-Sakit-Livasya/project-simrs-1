<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Keuangan\Hutang;
use Illuminate\Http\Request;

class HutangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('app-type.keuangan.hutang.index', [
            'hutang' => Hutang::all(),
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

        Hutang::create($validatedData);
        return back()->with('success', 'Hutang ditambahkan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Hutang  $hutang
     * @return \Illuminate\Http\Response
     */
    public function show(Hutang $hutang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Hutang  $hutang
     * @return \Illuminate\Http\Response
     */
    public function edit(Hutang $hutang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Hutang  $hutang
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Hutang $hutang)
    {
        $validatedData = $request->validate([
            'tanggal' => 'max:255|required',
            'nominal' => 'max:255|required',
            'keterangan' => 'required',
        ]);

        Hutang::where('id', $hutang->id)->update($validatedData);
        return back()->with('success', 'Hutang diubah!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Hutang  $hutang
     * @return \Illuminate\Http\Response
     */
    public function destroy(Hutang $hutang)
    {
        //
    }
}
