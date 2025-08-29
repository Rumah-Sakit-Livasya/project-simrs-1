<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InterventionPageController extends Controller
{
    public function index()
    {
        return view('pages.simrs.master-data.intervention.index');
    }
}
