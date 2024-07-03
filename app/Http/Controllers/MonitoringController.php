<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index()
    {
        $employees_count = Employee::where('company_id', auth()->user()->employee->company_id)->count();
        dd($employees_count);
    }
}
