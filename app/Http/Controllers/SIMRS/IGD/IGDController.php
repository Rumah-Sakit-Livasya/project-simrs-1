<?php

namespace App\Http\Controllers\SIMRS\IGD;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Registration;
use Illuminate\Http\Request;

class IGDController extends Controller
{
    public function index(Request $request)
    {
        $query = Registration::query();
        $filters = ['medical_record_number', 'registration_number', 'registration_name'];
        $filterApplied = false;

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, 'like', '%' . $request->$filter . '%');
                $filterApplied = true;
            }
        }

        // Filter by date range
        if ($request->filled('registration_date')) {
            $dateRange = explode(' - ', $request->registration_date);
            if (count($dateRange) === 2) {
                $startDate = date('Y-m-d 00:00:00', strtotime($dateRange[0]));
                $endDate = date('Y-m-d 23:59:59', strtotime($dateRange[1]));
                $query->whereBetween('registration_date', [$startDate, $endDate]);
                $filterApplied = true;
            }
        }

        // Check if penjamin_id filter is applied
        if ($request->filled('penjamin_id')) {
            $query->where('penjamin_id', $request->penjamin_id)
                ->where('registration_type', 'igd')
                ->where('status', 'aktif');
            $filterApplied = true;
        }

        // Get the filtered results if any filter is applied
        if ($filterApplied) {
            $registration = $query->orderBy('date', 'asc')
                ->where('registration_type', 'igd')
                ->where('status', 'aktif')
                ->get();
        } else {
            // Return empty collection if no filters applied
            $registration = collect();
        }

        return view('pages.simrs.igd.daftar-pasien', [
            'registrations' => $registration
        ]);
    }
}
