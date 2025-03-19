<?php

namespace App\Http\Controllers\SIMRS\Laboratorium;

use App\Http\Controllers\Controller;

class LaboratoriumController extends Controller
{
    public function index()
    {
        return view('laboratorium.index');
    }
}
