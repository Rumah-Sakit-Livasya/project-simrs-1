<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UpdateProfileController extends Controller
{
    public function show($employeeId)
    {
        $employee = Employee::find($employeeId);
        return response()->json($employee);
    }

    public function update(Request $request)
    {
        $employee = Employee::where('id', auth()->user()->employee->id)->first();
        $nama = Str::slug($employee->fullname);

        if (request()->hasFile('foto')) {
            // Upload file
            $image = request()->file('foto');
            $imageName = $nama . '_profile_' . time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/employee/profile/', $imageName);

            // Hapus foto yang ada jika ada
            if ($employee->foto) {
                // Hapus foto yang ada dari penyimpanan
                Storage::delete('public/img/pengajuan/cuti/' . $employee->foto);
            }

            // Update company with new image
            $employee->update([
                'foto' => $imageName,
            ]);
        }
    }
}
