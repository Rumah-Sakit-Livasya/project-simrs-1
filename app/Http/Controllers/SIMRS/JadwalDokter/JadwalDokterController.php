<?php

namespace App\Http\Controllers\SIMRS\JadwalDokter;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Departement;
use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\JadwalDokter;
use Illuminate\Http\Request;

class JadwalDokterController extends Controller
{
    public function index()
    {
        $departements = Departement::all();
        $doctors = Doctor::with('department_from_doctors')->get();
        return view('pages.simrs.master-data.jadwal-dokter.index', compact('departements', 'doctors'));
    }
}
