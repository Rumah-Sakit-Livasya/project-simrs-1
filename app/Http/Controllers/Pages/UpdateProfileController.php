<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

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
            'foto' => 'nullable|image|mimes:jpg,png,jpeg|max:10240',
        ]);

        $employee = Employee::where('id', auth()->user()->employee->id)->first();
        $nama = Str::slug($employee->fullname);

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $extension = $foto->getClientOriginalExtension();
            $file_name = $nama . '_profile.' . $extension;
            $path = 'public/employee/profile/';
            Storage::putFileAs($path, $foto, $file_name);

            // create new image instance (800 x 600)
            $manager = new ImageManager(Driver::class);
            $image = $manager->read(storage_path("app/public/employee/profile/{$file_name}"));
            $image = $image->cover(500, 500, 'center');
            $image->toPng()->save(storage_path("app/public/employee/profile/{$file_name}"));

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
