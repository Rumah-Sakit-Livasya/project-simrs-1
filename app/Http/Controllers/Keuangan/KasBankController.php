<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KasBankController extends Controller
{
    public function index()
    {


        return view('app-type.keuangan.setup.kas-bank.index');
    }
}
