<?php

namespace App\Http\Controllers\SIMRS\BPJS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function konfigurasiSistem()
    {
        return view('app-type.simrs.bpjs.setting.konfigurasi-sistem');
    }
}
