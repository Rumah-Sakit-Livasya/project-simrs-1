<?php

namespace App\Http\Controllers\API;

use App\Exports\DeductionExport;
use App\Exports\SalaryExport;
use App\Http\Controllers\Controller;
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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
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

    public function store()
    {
        try {
            // dd(request());
            $validator = Validator::make(
                request()->all(),
                [
                    'fullname' => 'required',
                    'email' => 'required|email|unique:employees,email',
                    'mobile_phone' => 'required',
                    'place_of_birth' => 'required',
                    'birthdate' => 'required',
                    'gender' => 'required',
                    'marital_status' => 'required',
                    'religion' => 'required',
                    'identity_type' => 'required',
                    'identity_number' => 'required',
                    'citizen_id_address' => 'required',
                    'employee_code' => 'required',
                    'employment_status' => 'required',
                    'join_date' => 'required',
                    'organization_id' => 'required',
                    'job_position_id' => 'required',
                    'job_level_id' => 'required',
                    'approval_line' => 'required',
                    'basic_salary' => 'required',
                    'bank_id' => 'nullable',
                    'account_number' => 'nullable',
                    'account_holder_name' => 'nullable',
                ],
                [
                    'fullname.required' => 'Nama Harus di isi!',
                    'email.required' => 'Email harus diisi',
                    'email.unique' => 'Email sudah ada',
                    'mobile_phone.required' => 'No HP Harus diisi',
                    'place_of_birth.required' => 'Tempat lahir harus diisi',
                    'birthdate.required' => 'Tgl Lahir harus diisi',
                    'gender.required' => 'Jenis kelamin harus diisi',
                    'marital_status.required' => 'Status menikah harus diisi',
                    'religion.required' => 'Agama harus diisi',
                    'identity_type.required' => 'Tipe identitas harus diisi',
                    'identity_number.required' => 'Nomor identitas harus diisi',
                    'citizen_id_address.required' => 'Alamat KTP harus diisi',
                    'employee_code.required' => 'NIP harus diisi',
                    'employment_status.required' => 'Status pegawai harus diisi',
                    'join_date.required' => 'Tanggal masuk harus diisi',
                    'organization_id.required' => 'Organisasi harus diisi',
                    'job_position_id.required' => 'Jabatan harus diisi',
                    'job_level_id.required' => 'Job level harus diisi',
                    'approval_line.required' => 'approval harus diisi',
                    'basic_salary.required' => 'Gaji pokok harus diisi',
                    'bank_id.required' => 'Nama bank harus dipilih',
                    'account_number.required' => 'Nomor rekening harus diisi',
                    'account_holder_name.required' => 'Nama rekening harus diisi',
                ]
            );

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            request()['company_id'] = 1;
            $pegawai = Employee::create(request()->all());
            $bank = BankEmployee::create([
                'employee_id' => $pegawai->id,
                'bank_id' => request()->bank_id,
                'account_holder_name' => request()->account_holder_name,
                'account_number' => request()->account_number,
                'status' => 1,
            ]);

            $user = User::create([
                'employee_id' => $pegawai->id,
                'name' => request()->fullname,
                'email' => request()->email,
                'status' => 1,
            ]);
            $user->assignRole('employee');
            //return response
            return response()->json(['message' => 'Pegawai Berhasil di Tambahkan!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 404);
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

    public function nonAktifPegawai(Request $request, $id)
    {
        try {
            $resignDate = $request->input('tgl_resign');
            $validator = Validator::make(request()->all(), [
                'keterangan' => 'required',
            ]);

            if ($validator->fails()) {
                throw new \Exception("Data keterangan harus diisi!");
            }

            if ($resignDate) {
                $employee = Employee::find($id);
                $employee->update([
                    'resign_date' => $request->tgl_resign,
                    'is_active' => 0
                ]);
            }
            $user = User::where('employee_id', $id);
            $user->update([
                'is_active' => 0
            ]);

            DB::insert('INSERT INTO riwayat_nonaktif_users (employee_id, keterangan, nonaktif_by) VALUES (?, ?, ?)', [
                $id,
                $request->keterangan,
                $request->userLogin, // Pastikan untuk mengenkripsi kata sandi
            ]);

            // Return data dalam format JSON
            return response()->json(['message' => 'Pegawai berhasil dinonaktifkan!']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
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
}
