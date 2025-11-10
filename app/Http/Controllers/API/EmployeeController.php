<?php

namespace App\Http\Controllers\API;

use App\Exports\DeductionExport;
use App\Exports\EmployeeExport;
use App\Exports\SalaryExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Imports\DeductionImport;
use App\Imports\EmployeeImport;
use App\Imports\SalaryImport;
use App\Models\AttendanceRequest;
use App\Models\Bank;
use App\Models\BankEmployee;
use App\Models\Company;
use App\Models\DayOffRequest;
use App\Models\Employee;
use App\Models\JobLevel;
use App\Models\JobPosition;
use App\Models\Location;
use App\Models\Organization;
use App\Models\Salary;
use App\Models\SIMRS\Doctor;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{

    public function index()
    {
        try {
            $employees = Employee::where('is_active', 1)->get();
            return response()->json($employees, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No result'
            ], 404);
        }
    }

    public function pegawai($id)
    {
        try {
            $employee = Employee::findOrFail($id);
            $fullname = $employee->fullname;
            if ($employee->gender == "Laki-laki") {
                $foto = $employee->foto ? '/' . $employee->foto : '/img/demo/avatars/avatar-c.png';
            } else {
                $foto = $employee->foto ? '/' . $employee->foto : '/img/demo/avatars/avatar-p.png';
            }
            $jabatan = $employee->job_position->name ?? 'Staff';
            $organisasi = $employee->organization->name ?? '-';
            $email = $employee->email ?? '-';
            $phone = phone($employee->mobile_phone) ?? '-';
            return response()->json([
                'employee' => $employee,
                'fullname' => $fullname,
                'foto' => $foto,
                'email' => $email,
                'phone' => $phone,
                'jabatan' => $jabatan,
                'organisasi' => $organisasi,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function editLokasi($id)
    {
        try {
            $employee = Employee::find($id);
            $lokasi = $employee->locations;
            return response()->json([$employee, $lokasi], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No result'
            ], 404);
        }
    }

    public function editOrganisasi($id)
    {
        try {
            $organization = Employee::where('id', $id)->select('employee_code', 'organization_id', 'company_id', 'job_position_id', 'employment_status', 'join_date', 'end_status_date', 'fullname', 'email', 'identity_number', 'birthdate', 'mobile_phone', 'residental_address')->first();
            return response()->json($organization, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No result'
            ], 404);
        }
    }

    public function edit($id)
    {
        try {
            $employee = Employee::where('id', $id)->first();
            return response()->json($employee, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No result'
            ], 404);
        }
    }

    /**
     * Update data pegawai secara keseluruhan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            // Cari data employee berdasarkan ID, fail jika tidak ditemukan
            $employee = Employee::findOrFail($id);

            // Ambil data asli employee dari database
            $original = $employee->fresh();

            $validator = Validator::make(
                $request->all(),
                [
                    'fullname' => 'required|string|max:255',
                    'email' => [
                        'required',
                        'email',
                        // Hanya periksa unique jika email berubah
                        function ($attribute, $value, $fail) use ($original) {
                            if (
                                $original !== null &&
                                (strtolower($value) !== strtolower($original->email))
                            ) {
                                $exists = Employee::where('email', $value)
                                    ->where('id', '!=', $original->id)
                                    ->exists();
                                if ($exists) {
                                    $fail('Email ini sudah digunakan oleh pegawai lain.');
                                }
                            }
                        },
                    ],
                    'mobile_phone' => 'required',
                    'place_of_birth' => 'required',
                    'birthdate' => 'required|date_format:Y-m-d',
                    'gender' => 'required',
                    'marital_status' => 'required',
                    'religion' => 'required',
                    'identity_type' => 'required',
                    'identity_number' => 'required',
                    'citizen_id_address' => 'required',
                    'employee_code' => [
                        'required',
                        // Hanya periksa unique jika employee_code berubah
                        function ($attribute, $value, $fail) use ($original) {
                            if (
                                $original !== null &&
                                ($value != $original->employee_code)
                            ) {
                                $exists = Employee::where('employee_code', $value)
                                    ->where('id', '!=', $original->id)
                                    ->exists();
                                if ($exists) {
                                    $fail('NIP ini sudah digunakan oleh pegawai lain.');
                                }
                            }
                        },
                    ],
                    'employment_status' => 'required',
                    'join_date' => 'required|date_format:Y-m-d',
                    'organization_id' => 'required|exists:organizations,id',
                    'job_position_id' => 'required|exists:job_positions,id',
                    'job_level_id' => 'required|exists:job_levels,id',
                    'approval_line' => 'nullable|exists:employees,id',
                    'basic_salary' => 'required|numeric',
                    'bank_id' => 'nullable|exists:banks,id',
                ]
            );

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            DB::beginTransaction();

            $employee->update($request->all());

            if ($employee->user) {
                $employee->user->update([
                    'name' => $request->fullname,
                    'email' => $request->email,
                ]);
            }

            BankEmployee::updateOrCreate(
                ['employee_id' => $employee->id],
                [
                    'bank_id' => $request->bank_id,
                    'account_holder_name' => $request->account_holder_name,
                    'account_number' => $request->account_number,
                ]
            );

            if ($request->is_doctor == "on") {
                $employee->update(['is_doctor' => true]);
                Doctor::updateOrCreate(
                    ['employee_id' => $employee->id],
                    [
                        'departement_id' => $request->departement_id,
                        'kode_dpjp' => $request->kode_dpjp
                    ]
                );
            } else {
                $employee->update(['is_doctor' => false]);
                Doctor::where('employee_id', $employee->id)->delete();
            }

            DB::commit();

            return response()->json(['message' => 'Data pegawai berhasil diperbarui!']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Pegawai tidak ditemukan.'], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Gagal memperbarui data: ' . $e->getMessage()], 500);
        }
    }

    public function updatePersonal($id)
    {
        try {
            // Validasi input
            $validator = request()->validate([
                'fullname' => 'required|string|max:255',
                'gender' => 'required|in:Laki-laki,Perempuan',
                'mobile_phone' => 'required|string|max:15',
                'place_of_birth' => 'required|string|max:255',
                'marital_status' => 'required|in:Lajang,Menikah,Janda,Duda',
                'email' => 'required|string|email|max:255',
                'birthdate' => 'required|date|date_format:Y-m-d',
                'religion' => 'required|string|in:Islam,Katholik,Kristen,Budha,Hindu,Lainnya',
                'blood_type' => 'nullable|string|max:255', // Golongan darah boleh kosong
            ]);

            // Cari employee berdasarkan ID
            $employee = Employee::findOrFail($id);
            $user = User::where('employee_id', $employee->id);

            // Perbarui data employee dengan data yang divalidasi
            $employee->update($validator);
            $user->update([
                'name' => request()->fullname
            ]);

            // Return response
            return response()->json(['message' => 'Pengguna berhasil diupdate']);
        } catch (\Exception $e) {
            // Jika terjadi kesalahan, kembalikan pesan kesalahan
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function updateOrganization($id)
    {
        try {
            // Validasi input
            $validator = request()->validate([
                'organization_id' => 'nullable',
            ]);

            // Cari employee berdasarkan ID
            $employee = Employee::findOrFail($id);

            // Perbarui data employee dengan data yang divalidasi
            $employee->update([
                'organization_id' => request()->organization_id,
                'fullname' => request()->fullname,
                'email' => request()->email,
                'employee_code' => request()->employee_code,
                'birthdate' => request()->birthdate,
                'identity_number' => request()->identity_number,
                'mobile_phone' => request()->mobile_phone,
                'company_id' => request()->company_id,
                'job_position_id' => request()->job_position_id,
                'employment_status' => request()->employment_status,
                'join_date' => request()->join_date,
                'end_status_date' => request()->end_status_date,
            ]);

            $user = User::where('employee_id', $id)->first();
            $user->update([
                'name' => request()->fullname,
                'email' => request()->email
            ]);

            $dokter = request()->is_doctor;
            if ($dokter == "on") {
                $employee->is_doctor = true;
                $employee->save();

                Doctor::updateOrCreate(
                    ['employee_id' => $employee->id],
                    [
                        'departement_id' => request()->departement_id,
                        'kode_dpjp' => request()->kode_dpjp,
                    ]
                );
            } else {
                $employee->is_doctor = false;
                $employee->save();

                Doctor::where('employee_id', $employee->id)->delete();
            }


            // Return response
            return response()->json(['message' => 'Organisasi berhasil diupdate']);
        } catch (\Exception $e) {
            // Jika terjadi kesalahan, kembalikan pesan kesalahan
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function updateApprovalLine($id)
    {
        try {
            // Validasi input
            $validator = request()->validate([
                'approval_line' => 'nullable',
                'approval_line_parent' => [
                    'nullable',
                    function ($attribute, $value, $fail) {
                        $approvalLine = request()->input('approval_line');
                        if (!is_null($approvalLine) && $value === $approvalLine) {
                            $fail('Approval Line Child & Parent tidak boleh sama!');
                        }
                    },
                ],
            ]);

            // Cari employee berdasarkan ID
            $employee = Employee::findOrFail($id);

            // Perbarui data employee dengan data yang divalidasi
            $employee->update([
                'approval_line' => request()->approval_line,
                'approval_line_parent' => request()->approval_line_parent,
            ]);

            // Return response
            return response()->json(['message' => 'Approval Line berhasil diupdate']);
        } catch (\Exception $e) {
            // Jika terjadi kesalahan, kembalikan pesan kesalahan
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function updateIdentitas($id)
    {
        try {
            // Validasi input
            $validator = request()->validate([
                'identity_type' => 'required|string|max:255',
                'postal_code' => 'required|string|max:255',
                'identity_number' => 'nullable|string|max:255',
                'citizen_id_address' => 'required|string|max:255',
                'identity_expire_date' => 'nullable',
                'residental_address' => 'required|string|max:255',
            ]);

            // Cari employee berdasarkan ID
            $employee = Employee::findOrFail($id);

            // Perbarui data employee dengan data yang divalidasi
            $employee->update($validator);

            // Return response
            return response()->json(['message' => 'Identitas berhasil diupdate']);
        } catch (\Exception $e) {
            // Jika terjadi kesalahan, kembalikan pesan kesalahan
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function store(StoreEmployeeRequest $request)
    {
        // Validasi sudah otomatis dijalankan.
        $validatedData = $request->validated();

        DB::beginTransaction();
        try {
            // Input Y-m-d sehingga tidak perlu konversi Carbon lagi.
            $employeeData = $validatedData;
            $employeeData['company_id'] = 1;
            $employeeData['is_management'] = $request->has('is_management') ? 1 : 0;

            $employee = Employee::create($employeeData);

            if (!empty($validatedData['bank_id'])) {
                BankEmployee::create([
                    'employee_id' => $employee->id,
                    'bank_id' => $validatedData['bank_id'],
                    'account_holder_name' => $validatedData['account_holder_name'] ?? null,
                    'account_number' => $validatedData['account_number'] ?? null,
                    'status' => 1,
                ]);
            }

            $user = User::create([
                'employee_id' => $employee->id,
                'name' => $validatedData['fullname'],
                'email' => $validatedData['email'],
                'password' => 'password',
                'is_active' => 1,
            ]);

            $user->assignRole('employee');

            DB::commit();

            return response()->json(['message' => 'Pegawai dan Akun User berhasil ditambahkan!'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal saat menyimpan pegawai baru: ' . $e->getMessage());

            return response()->json([
                'error' => 'Terjadi kesalahan pada server. Gagal menyimpan data.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function storeLocation()
    {
        try {
            $validator = Validator::make(request()->all(), [
                'id' => 'required|exists:employees', // Ensure 'id' exists in the 'employees' table
                'location_name' => 'required',
            ]);

            if ($validator->fails()) {
                throw new \Exception("Data harus diisi semua!");
            }

            $employee = Employee::find(request()->id);

            if (!$employee) {
                throw new \Exception("Pegawai tidak ditemukan!"); // Handle case where employee is not found
            }
            $location = request()->location_name;

            if ($employee->locations()->exists()) {
                $employee->locations()->sync($location);
            } else {
                $employee->locations()->attach($location);
            }

            return response()->json([
                'success' => true,
                'message' => 'Lokasi berhasil ditambahkan!',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Handle the export of employee data.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export()
    {
        return Excel::download(new EmployeeExport, 'daftar-pegawai.xlsx');
    }

    public function import(Request $request)
    {
        try {
            Excel::import(new EmployeeImport, $request->file('employee_import'));
            //return response
            return response()->json(['message' => 'Employee Berhasil di Tambahkan!']);
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json([
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    public function getEmployeesByOrganization(Request $request)
    {
        // Ambil ID organisasi dari request
        $organizationId = $request->input('organization_id');

        // Ambil data karyawan berdasarkan organisasi yang dipilih
        $employees = Employee::where('organization_id', $organizationId)->pluck('fullname', 'id');

        // Return data dalam format JSON
        return response()->json(['data' => $employees]);
    }

    /**
     * Menonaktifkan seorang pegawai dengan logging.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function nonAktifPegawai(Request $request, $id)
    {
        // Validasi input dari request
        $validator = Validator::make($request->all(), [
            'keterangan' => 'required|string|max:255',
            'tgl_resign' => 'required|date',
        ], [
            'keterangan.required' => 'Keterangan nonaktif wajib diisi.',
            'tgl_resign.required' => 'Tanggal resign wajib diisi.',
            'tgl_resign.date' => 'Format tanggal resign tidak valid.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Gunakan DB Transaction untuk memastikan semua operasi berhasil atau tidak sama sekali
        DB::beginTransaction();
        try {
            // Cari Pegawai
            $employee = Employee::findOrFail($id);

            // Update status dan tanggal resign
            $employee->update([
                'resign_date' => $request->tgl_resign,
                'is_active' => 0
            ]);

            // Nonaktifkan user terkait
            if ($employee->user) {
                $employee->user()->update(['is_active' => 0]);
            }

            // Log riwayat penonaktifan
            DB::table('riwayat_nonaktif_users')->insert([
                'employee_id' => $id,
                'keterangan' => $request->keterangan,
                'nonaktif_by' => Auth::id(), // Mengambil ID user yang sedang login, ini lebih aman
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit(); // Jika semua berhasil, simpan perubahan

            return response()->json(['success' => 'Pegawai ' . $employee->fullname . ' berhasil dinonaktifkan.']);
        } catch (\Exception $e) {
            DB::rollBack(); // Jika ada error, batalkan semua perubahan
            return response()->json(['error' => 'Gagal menonaktifkan pegawai: ' . $e->getMessage()], 500);
        }
    }

    public function exportSalary(Request $request)
    {
        // Ambil data karyawan berdasarkan filter Unit / Organisasi dan Karyawan
        $organizationId = $request->input('organization_id');
        $employeeId = $request->input('employee_id');

        return Excel::download(new SalaryExport($organizationId, $employeeId), 'salary.xlsx');
    }

    public function importSalary(Request $request)
    {
        try {
            $file = $request->file('salary_import');
            // Lakukan impor menggunakan SalaryImport
            \Maatwebsite\Excel\Facades\Excel::import(new SalaryImport, $file);

            return response()->json(['message' => 'Data Gaji berhasil diimpor!']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function exportDeduction(Request $request)
    {
        // Ambil data karyawan berdasarkan filter Unit / Organisasi dan Karyawan
        $organizationId = $request->input('organization_id');
        $employeeId = $request->input('employee_id');

        return Excel::download(new DeductionExport($organizationId, $employeeId), 'deduction.xlsx');
    }

    public function importDeduction(Request $request)
    {
        try {
            $file = $request->file('deduction_import');
            // Lakukan impor menggunakan DeductionImport
            \Maatwebsite\Excel\Facades\Excel::import(new DeductionImport, $file);

            return response()->json(['message' => 'Data Potongam berhasil diimpor!']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getDoctors()
    {
        $doctors = Employee::where('is_doctor', true)
            ->get(['id', 'fullname']);
        return response()->json($doctors->map(function ($doctor) {
            return [
                'id' => $doctor->id,
                'text' => $doctor->fullname
            ];
        }));
    }

    // Toggle Management
    public function toggleManagement(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $employee->is_management = $request->input('is_management');
        $employee->save();

        return response()->json(['success' => true]);
    }

    /**
     * Menyimpan tanda tangan pegawai.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeSignature(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'signature' => 'required',
        ]);

        try {
            $employee = Employee::findOrFail($request->employee_id);

            // Hapus file ttd lama jika ada
            if ($employee->ttd && Storage::disk('public')->exists($employee->ttd)) {
                Storage::disk('public')->delete($employee->ttd);
            }

            // Proses data Base64
            $signatureData = $request->signature;
            // Menghapus prefix data:image/png;base64,
            list($type, $data) = explode(';', $signatureData);
            list(, $data)      = explode(',', $data);
            $decodedSignature = base64_decode($data);

            // Buat nama file yang unik
            $fileName = 'signatures/ttd_' . $employee->id . '_' . time() . '.png';

            // Simpan file ke storage/app/public/signatures
            Storage::disk('public')->put($fileName, $decodedSignature);

            // Update kolom 'ttd' di database
            $employee->ttd = $fileName;
            $employee->save();

            return response()->json([
                'success' => true,
                'message' => 'Tanda tangan berhasil disimpan.',
                'path' => Storage::url($fileName) // Mengembalikan URL publik dari file
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan tanda tangan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Menampilkan halaman popup untuk tanda tangan.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\View\View
     */
    public function showSignaturePadPage(Employee $employee)
    {
        // Method ini akan me-render view yang diminta
        // dan mengirimkan data pegawai yang bersangkutan
        return view('pages.simrs.erm.partials.signature-pad', compact('employee'));
    }

    /**
     * Memperpanjang kontrak pegawai dengan memperbarui end_status_date.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function extendContract(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'new_end_date' => 'required|date|after:today',
        ], [
            'new_end_date.required' => 'Tanggal akhir kontrak baru wajib diisi.',
            'new_end_date.date' => 'Format tanggal tidak valid.',
            'new_end_date.after' => 'Tanggal akhir kontrak harus setelah hari ini.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $employee = Employee::findOrFail($id);

            // Pastikan pegawai masih berstatus kontrak
            if ($employee->employment_status !== 'Kontrak') {
                return response()->json(['error' => 'Hanya pegawai dengan status Kontrak yang bisa diperpanjang.'], 400);
            }

            // Update tanggal akhir kontrak
            $employee->end_status_date = $request->new_end_date;
            $employee->save();

            return response()->json(['success' => 'Kontrak untuk ' . $employee->fullname . ' berhasil diperpanjang.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal memperpanjang kontrak. Terjadi kesalahan server.'], 500);
        }
    }
}
