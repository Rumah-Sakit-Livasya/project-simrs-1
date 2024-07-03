<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    public function getCompany($id)
    {
        try {
            $company = Company::findOrFail($id);
            return response()->json($company, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No result'
            ], 404);
        }
    }

    public function store()
    {
        try {
            $validator = Validator::make(request()->all(), [
                'name' => 'required|max:40',
                'phone_number' => 'required',
                'email' => 'required|email',
                'address' => 'required',
                'province' => 'required',
                'city' => 'required',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'category' => 'nullable',
                'class' => 'nullable',
                'operating_permit_number' => 'nullable',
            ], [
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'logo.image' => 'File yang diunggah harus berupa gambar.',
                'logo.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif.',
                'logo.max' => 'Ukuran gambar tidak boleh melebihi 2MB.',
                'name.required' => 'Nama wajib diisi.',
                'name.max:40' => 'Nama harus dibawah 40 karakter.',
                'phone_number.required' => 'Nomor telepon wajib diisi.',
                'address.required' => 'Alamat wajib diisi.',
                'province.required' => 'Provinsi wajib diisi.',
                'city.required' => 'Kota wajib diisi.',
            ]);

            if ($validator->fails()) {
                $errorMessages = $validator->errors()->all();
                return response()->json([
                    'message' => implode("\n", $errorMessages)
                ], 422);
            }

            if (request()->hasFile('logo')) {
                $image = request()->file('logo');
                $imageName = Str::slug(request()->name) .  time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/img/', $imageName);
                Company::create([
                    'name' => request()->name,
                    'phone_number' => request()->phone_number,
                    'email' => request()->email,
                    'address' => request()->address,
                    'province' => request()->province,
                    'city' => request()->city,
                    'category' => request()->category,
                    'class' => request()->class,
                    'operating_permit_number' => request()->operating_permit_number,
                    'logo' => $imageName,
                ]);
            } else {
                Company::create([
                    'name' => request()->name,
                    'phone_number' => request()->phone_number,
                    'email' => request()->email,
                    'address' => request()->address,
                    'province' => request()->province,
                    'city' => request()->city,
                    'category' => request()->category,
                    'class' => request()->class,
                    'operating_permit_number' => request()->operating_permit_number,
                ]);
            }

            return response()->json(['message' => 'Perusahaan Berhasil di Tambahkan!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }
    }


    public function update(Request $request, $id)
    {
        // dd(request());
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone_number' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'province' => 'required',
            'city' => 'required',
            'logo' => 'nullable',
            'category' => 'nullable',
            'class' => 'nullable',
            'operating_permit_number' => 'nullable',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'name.required' => 'Nama wajib diisi.',
            'phone_number.required' => 'Nomor telepon wajib diisi.',
            'address.required' => 'Alamat wajib diisi.',
            'province.required' => 'Provinsi wajib diisi.',
            'city.required' => 'Kota wajib diisi.',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            $errorMessages = $validator->errors()->all();

            return response()->json([
                'message' => implode("\n", $errorMessages)
            ], 422);
        }


        // Find company by ID
        $company = Company::find($id);

        // Check if image is not empty
        if ($request->hasFile('logo')) {

            // Upload logo
            $image = $request->file('logo');
            $imageName = Str::slug(request()->name) .  time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/img/', $imageName);

            // Update company with new image
            $company->update([
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'address' => $request->address,
                'province' => $request->province,
                'city' => $request->city,
                'logo' => $imageName,
                'category' => $request->category,
                'class' => $request->class,
                'operating_permit_number' => $request->operating_permit_number,
            ]);
        } else {

            // Update company without image
            $company->update([
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'address' => $request->address,
                'province' => $request->province,
                'city' => $request->city,
                'category' => $request->category,
                'class' => $request->class,
                'operating_permit_number' => $request->operating_permit_number,
            ]);
        }

        // Return response
        return response()->json(['message' => 'Detail Perusahaan Berhasil di Update!']);
    }


    public function updateLocation(Request $request, $id)
    {

        // Define validation rules
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required|numeric',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorDetails = [];
            $errorMessage = "Terdapat kesalahan dalam data yang Anda masukkan. Silakan periksa kembali.";

            $errorMessages = [
                'latitude' => 'Data latitude harus berupa angka.',
                'longitude' => 'Data longitude harus berupa angka.',
                'radius' => 'Data radius harus berupa angka.'
            ];

            foreach (['latitude', 'longitude', 'radius'] as $field) {
                if ($errors->has($field)) {
                    $errorDetails[$field] = $errorMessages[$field];
                    $errorMessage = $errorMessages[$field];
                    break; // Hanya tampilkan satu pesan kesalahan pertama kali
                }
            }

            return response()->json([
                'message' => $errorMessage,
                'errors' => $errorDetails
            ], 422);
        }



        //find company by ID
        $company = Company::find($id);

        // update location
        $company->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'radius' => $request->radius,
        ]);

        //return response
        return response()->json(['message' => 'Lokasi Perusahaan Berhasil di Update!']);
    }
}
