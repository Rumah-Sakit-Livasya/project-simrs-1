<?php

namespace App\Http\Controllers;

use App\Models\Inventaris\RoomMaintenance;
use App\Models\JenisKegiatan;
use App\Models\User;
use Illuminate\Http\Request;

class KunjunganPageController extends Controller
{
    public function index()
    {
        $jenisKegiatans = JenisKegiatan::orderBy('nama_kegiatan')->get();
        $users = User::orderBy('name')->get();
        $roomMaintenances = RoomMaintenance::where('status', 1)->orderBy('name')->get(); // Ambil data ruangan

        return view('pages.kunjungan.index', compact('jenisKegiatans', 'users', 'roomMaintenances'));
    }
}
