<?php

namespace App\Http\Controllers\SIMRS\Penjamin;

use App\Models\SIMRS\Penjamin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PenjaminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $penjamin = Penjamin::all();
        return view('pages.simrs.master-data.penjamin.index', compact('penjamin'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'group_penjamin_id' => 'required|exists:group_penjamin,id',
            'mulai_kerjasama' => 'required|string',
            'akhir_kerjasama' => 'nullable|string',
            'tipe_perusahaan' => 'required|string',
            'kode_perusahaan' => 'nullable|string',
            'nama_perusahaan' => 'required|string',
            'alamat_surat' => 'nullable|string',
            'alamat_email' => 'nullable|string',
            'direktur' => 'nullable|string',
            'nama_kontak' => 'nullable|string',
            'diskon' => 'required|numeric',
            'jabatan' => 'nullable|string',
            'termasuk_penjamin' => 'required|boolean',
            'fax_kontak' => 'nullable|string',
            'alamat' => 'nullable|string',
            'alamat_tagihan' => 'nullable|string',
            'telepon_kontak' => 'nullable|string',
            'email_kontak' => 'nullable|string',
            'kota' => 'nullable|string',
            'status' => 'required|boolean',
            'kode_pos' => 'nullable|string',
            'jenis_kerjasama' => 'required|string',
            'jenis_kontrak' => 'required|string',
            'pasien_otc' => 'required|boolean',
            'keterangan' => 'nullable|string',
        ]);

        $penjamin = new Penjamin([
            'group_penjamin_id' => $request->get('group_penjamin_id'),
            'mulai_kerjasama' => $request->get('mulai_kerjasama'),
            'akhir_kerjasama' => $request->get('akhir_kerjasama'),
            'tipe_perusahaan' => $request->get('tipe_perusahaan'),
            'kode_perusahaan' => $request->get('kode_perusahaan'),
            'nama_perusahaan' => $request->get('nama_perusahaan'),
            'alamat_surat' => $request->get('alamat_surat'),
            'alamat_email' => $request->get('alamat_email'),
            'direktur' => $request->get('direktur'),
            'nama_kontak' => $request->get('nama_kontak'),
            'diskon' => $request->get('diskon'),
            'jabatan' => $request->get('jabatan'),
            'termasuk_penjamin' => $request->get('termasuk_penjamin'),
            'fax_kontak' => $request->get('fax_kontak'),
            'alamat' => $request->get('alamat'),
            'alamat_tagihan' => $request->get('alamat_tagihan'),
            'telepon_kontak' => $request->get('telepon_kontak'),
            'email_kontak' => $request->get('email_kontak'),
            'kota' => $request->get('kota'),
            'status' => $request->get('status'),
            'kode_pos' => $request->get('kode_pos'),
            'jenis_kerjasama' => $request->get('jenis_kerjasama'),
            'jenis_kontrak' => $request->get('jenis_kontrak'),
            'pasien_otc' => $request->get('pasien_otc'),
            'keterangan' => $request->get('keterangan'),
        ]);

        $penjamin->save();

        return response()->json([
            'message' => 'Penjamin berhasil ditambahkan',
            'data' => $penjamin
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Penjamin  $penjamin
     * @return \Illuminate\Http\Response
     */
    public function show(Penjamin $penjamin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Penjamin  $penjamin
     * @return \Illuminate\Http\Response
     */
    public function edit(Penjamin $penjamin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Penjamin  $penjamin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Penjamin $penjamin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Penjamin  $penjamin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Penjamin $penjamin)
    {
        //
    }
}
