<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NursingDiannosisPageController extends Controller
{
    public function viewCategories()
    {
        return view('pages.simrs.master-data.diagnosa-keperawatan.categories');
    }

    public function viewDiagnoses()
    {
        return view('pages.simrs.master-data.diagnosa-keperawatan.diagnoses');
    }
}
