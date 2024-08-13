<?php

namespace App\Http\Controllers\SIMRS\Laboratorium;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Laboratorium\GrupParameterLaboratorium;
use Illuminate\Http\Request;

class GrupParameterLaboratoriumController extends Controller
{
    public function index()
    {
        $grup_parameter_laboratorium = GrupParameterLaboratorium::all();
        return view('pages.simrs.master-data.penunjang-medis.laboratorium.grup-parameter-lab', compact('grup_parameter_laboratorium'));
    }
}
