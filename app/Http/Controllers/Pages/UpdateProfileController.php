<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Support\Str;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Time;

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

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $extension = $foto->getClientOriginalExtension();
            $file_name = time() . '_' . $nama . '.' . $extension;
            $path = public_path('profile/' . $nama);

            // Check ukuran file
            $fileSize = $foto->getSize(); // Ukuran dalam bytes
            $shouldCompress = $fileSize > 1024 * 1024; // 1 MB = 1024 * 1024 bytes

            // Jika perlu mengompres
            if ($shouldCompress) {
                $img = \Image::make($foto)->encode($extension, 75); // Mengompres dengan kualitas 75
            } else {
                $img = \Image::make($foto);
            }

            // Buat direktori jika belum ada
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            // Simpan gambar di dalam direktori
            $img->save($path . '/' . $file_name);

            // Update kolom foto di database
            $employee->update(['foto' => 'profile/' . $nama . '/' . $file_name]);

            return back();
        }
    }
}
