<?php

namespace App\Http\Controllers\SIMRS\Poliklinik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PoliklinikController extends Controller
{
    public function index()
    {
        return view('pages.simrs.poliklinik.index');
    }
}
