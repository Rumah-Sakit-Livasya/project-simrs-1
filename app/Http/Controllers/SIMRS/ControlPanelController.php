<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\OrderRadiologi;
use App\Models\SIMRS\Departement;
use App\Models\SIMRS\GroupPenjamin;
use Illuminate\Http\Request;

class ControlPanelController extends Controller
{
    public function tindakan_rajal()
    {
        $departments = Departement::orderBy('name')->get();
        $grupPenjamins = GroupPenjamin::all();
        return view('pages.simrs.control-panel.tindakan-rajal', compact('departments', 'grupPenjamins'));
    }

    public function radiologi()
    {
        $grupPenjamins = GroupPenjamin::all();
        return view('pages.simrs.control-panel.radiologi', compact('grupPenjamins'));
    }

    public function laboratorium()
    {
        $grupPenjamins = GroupPenjamin::all();
        // Ambil hanya departemen laboratorium
        $departemens = Departement::where('name', 'LIKE', '%LAB%')->get();
        return view('pages.simrs.control-panel.laboratorium', compact('grupPenjamins', 'departemens'));
    }
}
