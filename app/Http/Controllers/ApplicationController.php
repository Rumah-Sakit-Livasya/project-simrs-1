<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function chooseApp()
    {
        return view('home');
    }

    public function setApp(Request $request)
    {
        $appType = $request->input('app_type');
        session(['app_type' => $appType]);

        if ($appType == 'simrs') {
            return redirect()->route('dashboard.simrs');
        } else if ($appType == 'hr') {
            return redirect()->route('attendances');
        } else if ($appType == 'logistik') {
            return redirect()->route('logistik');
        } else if ($appType == 'keuangan') {
            return redirect()->route('keuangan');
        } else if ($appType == 'kepustakaan') {
            return redirect()->route('kepustakaan');
        } else if ($appType == 'mutu') {
            return redirect()->route('mutu');
        }
    }
}
