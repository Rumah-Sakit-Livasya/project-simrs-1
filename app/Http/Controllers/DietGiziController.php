<?php

namespace App\Http\Controllers;

use App\Models\DietGizi;
use Illuminate\Http\Request;

class DietGiziController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            "kategori_id" => "required|integer",
            "registration_id" => "required|integer"
        ]);

        // first, check if data already exist
        // in DietGizi with same registration_id
        $exist = DietGizi::where("registration_id", $validatedData["registration_id"])->first();
        if ($exist) {
            // if data already exist

            // check if kategori_id == -1
            if ($validatedData["kategori_id"] == -1) {
                // if kategori_id == -1, delete that data
                $exist->delete();
                return back()->with("success","Data berhasil disimpan!");
            }

            // if not,
            // update kategori_id of that data
            $exist->kategori_id = $validatedData["kategori_id"];
            $exist->save();
            return back()->with("success","Data berhasil disimpan!");
        } else {
            // if data not exist,
            // create new data
            $dietGizi = new DietGizi($validatedData);
            $dietGizi->save();
            return back()->with("success","Data berhasil disimpan!");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(DietGizi $dietGizi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DietGizi $dietGizi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DietGizi $dietGizi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DietGizi $dietGizi)
    {
        //
    }
}
