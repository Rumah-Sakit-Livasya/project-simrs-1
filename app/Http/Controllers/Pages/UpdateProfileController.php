<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UpdateProfileController extends Controller
{
    public function show($employeeId)
    {
        $employee = Employee::find($employeeId);
        return response()->json($employee);
    }

    public function update(Request $request)
    {

        $request->validate([
            'foto' => 'nullable|image|mimes:jpg,png,jpeg', // Validasi gambar
        ]);

        $employee = Employee::where('id', auth()->user()->employee->id)->first();
        $nama = Str::slug($employee->fullname);

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $extension = $foto->getClientOriginalExtension();
            $file_name = $nama . '_profile_' . time() . '.' . $extension;
            $path = 'public/employee/profile/' . $file_name;

            // Mengompres gambar
            $img = Image::make($foto)->encode($extension, 75); // Mengompres dengan kualitas 75
            Storage::put($path, $img);

            // Hapus foto yang ada jika ada
            if ($employee->foto) {
                Storage::delete('public/employee/profile/' . $employee->foto);
            }

            // Update foto di database
            $employee->update(['foto' => $file_name]);
        }

        return back();

        // $employee = Employee::where('id', auth()->user()->employee->id)->first();
        // $nama = Str::slug($employee->fullname);

        // if (request()->hasFile('foto')) {
        //     // Upload file
        //     $image = request()->file('foto');
        //     $imageName = $nama . '_profile_' . time() . '.' . $image->getClientOriginalExtension();
        //     $image->storeAs('public/employee/profile/', $imageName);

        //     // Hapus foto yang ada jika ada
        //     if ($employee->foto) {
        //         // Hapus foto yang ada dari penyimpanan
        //         Storage::delete('public/img/pengajuan/cuti/' . $employee->foto);
        //     }

        //     // Update company with new image
        //     $employee->update([
        //         'foto' => $imageName,
        //     ]);
        // }
    }
}
