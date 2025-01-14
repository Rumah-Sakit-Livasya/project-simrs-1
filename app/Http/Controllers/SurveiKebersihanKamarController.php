<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Inventaris\RoomMaintenance;
use App\Models\SurveiKebersihanKamar;
use Carbon\Carbon;
use Illuminate\Contracts\Support\ValidatedData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class SurveiKebersihanKamarController extends Controller
{
    public function index()
    {
        dd(SurveiKebersihanKamar::all());
        return view('pages.survei.kebersihan_kamar', [
            'survei' => SurveiKebersihanKamar::all(),
        ]);
    }

    public function create()
    {
        $kamar = RoomMaintenance::where('room_code', 'like', '%KMR%')->get();
        return view('pages.survei.tambah_kebersihan_kamar', [
            'kamar' => $kamar,
        ]);
    }

    public function store(Request $request)
    {
        // Validate incoming request
        $validatedData = $request->validate([
            'lantai_kamar' => 'nullable',
            'sudut_kamar' => 'nullable',
            'plafon_kamar' => 'nullable',
            'dinding_kamar' => 'nullable',
            'bed_head' => 'nullable',
            'lantai_toilet' => 'nullable',
            'wastafel_toilet' => 'nullable',
            'closet_toilet' => 'nullable',
            'kaca_toilet' => 'nullable',
            'dinding_toilet' => 'nullable',
            'shower_toilet' => 'nullable',
            'dokumentasi' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10000', // max 10MB
            'room_maintenance_id' => 'required'
        ]);

        try {
            // Set user ID and current date
            $validatedData['user_id'] = auth()->user()->id;
            $validatedData['tanggal'] = Carbon::now('Asia/Jakarta');

            if (request()->hasFile('dokumentasi')) {
                $image = request()->file('dokumentasi');
                $imageName = now()->format('Y-m-d') . '_survei_kebersihan_kamar_' . time() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('survei/kebersihan_kamar/', $imageName, 'private');
                $validatedData['dokumentasi'] = $imageName;
            }
            SurveiKebersihanKamar::create($validatedData);

            return redirect()->route('survei.kebersihan-kamar')->with('success', 'Survei Berhasil Ditambahkan!');
        } catch (\Exception $e) {
            // Return error response if any exceptions occur
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function edit(Request $request, $id)
    {
        $kamar = RoomMaintenance::where('room_code', 'like', '%KMR%')->get();
        $survei_kebersihan = SurveiKebersihanKamar::findOrFail($id);
        return view('pages.survei.edit_kebersihan_kamar', [
            'kamar' => $kamar,
            'survei' => $survei_kebersihan
        ]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'lantai_kamar' => 'nullable',
            'sudut_kamar' => 'nullable',
            'plafon_kamar' => 'nullable',
            'dinding_kamar' => 'nullable',
            'bed_head' => 'nullable',
            'lantai_toilet' => 'nullable',
            'wastafel_toilet' => 'nullable',
            'closet_toilet' => 'nullable',
            'kaca_toilet' => 'nullable',
            'dinding_toilet' => 'nullable',
            'shower_toilet' => 'nullable',
            'dokumentasi' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10000', // max 10MB
            'room_maintenance_id' => 'required',
            'user_id' => 'required'
        ]);

        try {
            $survei = SurveiKebersihanKamar::findOrFail($id);
            $validatedData['tanggal'] = $survei->tanggal;

            // if (request()->hasFile('dokumentasi')) {
            //     $image = request()->file('dokumentasi');
            //     $imageName = now()->format('Y-m-d') . '_survei_kebersihan_kamar_' . time() . '.' . $image->getClientOriginalExtension();
            //     $path = $image->storeAs('survei/kebersihan_kamar/', $imageName, 'private');
            //     $validatedData['dokumentasi'] = $imageName;
            // }
            $survei->update($validatedData);

            return redirect()->route('survei.kebersihan-kamar')->with('success', 'Survei Berhasil Diupdate!');
        } catch (\Exception $e) {
            // Return error response if any exceptions occur
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete(Request $request, $id)
    {

        try {

            // Cari record di database
            $kepustakaan = SurveiKebersihanKamar::where('id', $id)->firstOrFail();

            if ($kepustakaan) {
                if ($kepustakaan->dokumentasi) {
                    // Use the storage facade to delete the file
                    Storage::disk('private')->delete('survei/kebersihan_kamar/' . $kepustakaan->dokumentasi);
                }

                $kepustakaan->delete();
                return response()->json(['message' => 'Survei berhasil dihapus.'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete file or record: ' . $e->getMessage()], 500);
        }
    }
}
