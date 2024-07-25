<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Illuminate\Support\Str;

class FaceRecognitionController extends Controller
{

    public function index()
    {
        $employee = Employee::find(auth()->user()->employee_id);
        return view('attendance', compact('employee'));
    }

    public function create()
    {
        return view('test-absen');
    }

    public function store(Request $request)
    {
        $employee = Employee::where('id', auth()->user()->employee->id)->first();
        $nama = Str::slug($employee->fullname);
        if ($request->hasFile('profile_image')) {
            $foto = $request->file('profile_image');
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

        return redirect()->back()->with('success', 'Employee profile created successfully.');
    }
}
