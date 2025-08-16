<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\WasteCategory;
use App\Models\Organization;

class DailyWasteInputController extends Controller
{
    public function index()
    {
        $wasteCategories = WasteCategory::all();
        $transportCategories = WasteCategory::whereIn('name', ['Infeksius', 'Domestik'])->get();
        $vehicles = Vehicle::all();

        return view('pages.daily-waste.index', compact('wasteCategories', 'transportCategories', 'vehicles'));
    }

    public function daily()
    {
        $wasteCategories = WasteCategory::all();
        $sanitationOrgId = Organization::where('name', 'Sanitasi')->value('id');

        return view('pages.daily-waste.daily', compact('wasteCategories', 'sanitationOrgId'));
    }

    public function transport()
    {
        $transportCategories = WasteCategory::whereIn('name', ['Infeksius', 'Domestik'])->get();
        $vehicles = Vehicle::all();

        return view('pages.waste-transport.index', compact('transportCategories', 'vehicles'));
    }
}
