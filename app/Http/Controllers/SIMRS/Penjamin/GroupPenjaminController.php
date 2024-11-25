<?php

namespace App\Http\Controllers\SIMRS\Penjamin;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\GroupPenjamin;
use Illuminate\Http\Request;

class GroupPenjaminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $groups = GroupPenjamin::all();
        return response()->json($groups);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(GroupPenjamin $groupPenjamin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GroupPenjamin $groupPenjamin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GroupPenjamin $groupPenjamin)
    {
        //
    }
}
