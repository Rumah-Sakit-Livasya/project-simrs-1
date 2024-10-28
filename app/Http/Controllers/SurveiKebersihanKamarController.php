<?php

namespace App\Http\Controllers;

use App\Models\Inventaris\RoomMaintenance;
use App\Models\SurveiKebersihanKamar;
use Illuminate\Http\Request;

class SurveiKebersihanKamarController extends Controller
{
    public function index()
    {
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
}
