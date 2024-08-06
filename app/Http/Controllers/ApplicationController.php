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
        } else {
            return redirect()->route('attendances');
        }
    }
}
