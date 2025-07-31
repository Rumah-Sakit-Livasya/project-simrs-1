<?php

namespace App\Http\Controllers;

use App\Models\JamMakanGizi;
use App\Models\KategoriGizi;
use App\Models\MenuGizi;
use App\Models\SIMRS\Registration;
use Illuminate\Http\Request;

class JamMakanGiziController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = JamMakanGizi::query();
        $filterApplied = false;

        if ($request->filled('waktu_makan')) {
            $query->where("waktu_makan", "like", "%" . $request->get("waktu_makan") . "%");
            $filterApplied = true;
        }

        if ($filterApplied) {
            $result = $query->get();
        } else {
            $result = JamMakanGizi::all();
        }

        return view('pages.simrs.gizi.jam-makan', [
            "jam_makans" => $result
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($registration_id)
    {
        $registration = Registration::findOrFail($registration_id);

        return view("pages.simrs.gizi.partials.popup-pilih-diet", [
            'registration' => $registration,
            'categories' => KategoriGizi::all(),
            'jam_makans' => JamMakanGizi::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            "waktu_makan" => "required|string|max:255",
            "jam" => "required|date_format:H:i",
            "auto_order" => "boolean",
            "aktif" => "boolean"
        ]);

        try {
            $autoOrderGizi = new JamMakanGizi();
            $autoOrderGizi->fill($validatedData);
            $autoOrderGizi->save();
            return back()->with("success", "Jam Makan berhasil disimpan!");
        } catch (\Exception $e) {
            return back()->with("error", $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(JamMakanGizi $autoOrderGizi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JamMakanGizi $autoOrderGizi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                "waktu_makan" => "required|string|max:255",
                "jam" => "required|date_format:H:i",
                "auto_order" => "boolean",
                "aktif" => "boolean"
            ]);

            // update menu gizi
            // where id == $id
            JamMakanGizi::where('id', $id)->update($validatedData);

            return redirect()->back()->with('success', 'Jam Makan berhasil diedit!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {

            JamMakanGizi::destroy($id);

            return response()->json([
                'success' => true,
                'message' => 'JamMakan dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
