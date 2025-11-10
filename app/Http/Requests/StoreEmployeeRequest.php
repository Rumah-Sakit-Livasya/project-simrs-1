<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Step 1: Data Diri
            'fullname' => ['required', 'string', 'max:255'],
            'nickname' => ['nullable', 'string', 'max:100'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('employees', 'email'),
                Rule::unique('users', 'email'),
            ],
            'mobile_phone' => ['required', 'string', 'max:20'],
            'whatsapp_number' => ['nullable', 'string', 'max:20'],
            'place_of_birth' => ['required', 'string', 'max:100'],
            // --- UBAH ATURAN FORMAT TANGGAL DI SINI ---
            'birthdate' => ['required', 'date_format:Y-m-d'],
            'identity_expire_date' => ['nullable', 'date_format:Y-m-d'],
            // --- AKHIR PERUBAHAN ---
            'gender' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'marital_status' => ['required', 'string'],
            'spouse_name' => ['nullable', 'string', 'max:255'],
            'religion' => ['required', 'string'],
            'identity_type' => ['required', 'string'],
            'identity_number' => ['required', 'string', 'max:50'],
            'citizen_id_address' => ['required', 'string'],
            'residental_address' => ['nullable', 'string'],
            'npwp_number' => ['nullable', 'string', 'max:50'],
            'tax_status' => ['nullable', 'string', 'max:50'],
            'blood_type' => ['nullable', Rule::in(['A', 'B', 'AB', 'O'])],
            'citizenship' => ['nullable', 'string', 'max:100'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_number' => ['nullable', 'string', 'max:20'],

            // Step 2: Pekerjaan
            'employee_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('employees', 'employee_code'),
            ],
            'employment_status' => ['required', 'string'],
            // --- UBAH ATURAN FORMAT TANGGAL DI SINI ---
            'join_date' => ['required', 'date_format:Y-m-d'],
            // --- AKHIR PERUBAHAN ---
            'end_date' => ['nullable', 'after_or_equal:join_date'],
            'organization_id' => ['required', 'exists:organizations,id'],
            'job_position_id' => ['required', 'exists:job_positions,id'],
            'job_level_id' => ['required', 'exists:job_levels,id'],
            'approval_line' => ['required', 'exists:employees,id'],
            'is_management' => ['nullable', 'boolean'],
            'no_mou' => ['nullable', 'string', 'max:255'],
            // --- UBAH ATURAN FORMAT TANGGAL DI SINI ---
            'mou_start_date' => ['nullable', 'date_format:Y-m-d'],
            'mou_end_date' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:mou_start_date'],
            // --- AKHIR PERUBAHAN ---

            // Step 3: Payroll/Keuangan
            'basic_salary' => ['required', 'numeric', 'min:0'],
            'other_allowance' => ['nullable', 'numeric', 'min:0'],
            'bank_id' => ['nullable', 'exists:banks,id'],
            'account_number' => ['nullable', 'string', 'max:50'],
            'account_holder_name' => ['nullable', 'string', 'max:255'],

            // Step 4: Dokumentasi (Optional)
            'photo' => ['nullable', 'image', 'max:2048'],
            'citizen_card_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
            'npwp_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
            'family_card_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],

            // Step 5: Keluarga
            'family.*.name' => ['nullable', 'string', 'max:255'],
            'family.*.relationship' => ['nullable', 'string', 'max:100'],
            'family.*.birthdate' => ['nullable'],
            'family.*.occupation' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'fullname.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'employee_code.unique' => 'Nomor Induk Pegawai ini sudah terdaftar.',
            'employee_code.required' => 'Nomor Induk Pegawai wajib diisi.',
            'mobile_phone.required' => 'No. HP wajib diisi.',
            'place_of_birth.required' => 'Tempat lahir wajib diisi.',
            'birthdate.required' => 'Tanggal lahir wajib diisi.',
            'birthdate.date_format' => 'Format tanggal lahir tidak sesuai (harus YYYY-MM-DD).',
            'identity_expire_date.date_format' => 'Format tanggal berlaku identitas tidak sesuai (harus YYYY-MM-DD).',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'marital_status.required' => 'Status perkawinan wajib diisi.',
            'religion.required' => 'Agama wajib diisi.',
            'identity_type.required' => 'Jenis identitas wajib diisi.',
            'identity_number.required' => 'Nomor identitas wajib diisi.',
            'citizen_id_address.required' => 'Alamat KTP wajib diisi.',
            'employment_status.required' => 'Status karyawan wajib diisi.',
            'join_date.required' => 'Tanggal masuk wajib diisi.',
            'join_date.date_format' => 'Format tanggal masuk tidak sesuai (harus YYYY-MM-DD).',
            'mou_start_date.date_format' => 'Format tanggal mulai MOU tidak sesuai (harus YYYY-MM-DD).',
            'mou_end_date.date_format' => 'Format tanggal akhir MOU tidak sesuai (harus YYYY-MM-DD).',
            'mou_end_date.after_or_equal' => 'Tanggal akhir MOU harus sama atau setelah tanggal mulai MOU.',
            'organization_id.required' => 'Organisasi harus dipilih.',
            'organization_id.exists' => 'Organisasi tidak ditemukan.',
            'job_position_id.required' => 'Jabatan wajib diisi.',
            'job_position_id.exists' => 'Jabatan tidak ditemukan.',
            'job_level_id.required' => 'Level jabatan wajib diisi.',
            'job_level_id.exists' => 'Level jabatan tidak ditemukan.',
            'approval_line.required' => 'Approval line harus dipilih.',
            'approval_line.exists' => 'Approval line tidak ditemukan.',
            'basic_salary.required' => 'Gaji pokok wajib diisi.',
            'basic_salary.numeric' => 'Gaji pokok harus berupa angka.',
            'basic_salary.min' => 'Gaji pokok minimal 0.',
            'bank_id.exists' => 'Bank tidak ditemukan.',
            'photo.image' => 'Foto harus berupa gambar.',
            'photo.max' => 'Ukuran foto maksimal 2MB.',
            'citizen_card_file.mimes' => 'File KTP harus PDF atau gambar.',
            'citizen_card_file.max' => 'File KTP maksimal 4MB.',
            'npwp_file.mimes' => 'File NPWP harus PDF atau gambar.',
            'npwp_file.max' => 'File NPWP maksimal 4MB.',
            'family_card_file.mimes' => 'File KK harus PDF atau gambar.',
            'family_card_file.max' => 'File KK maksimal 4MB.',
            // Tambahkan pesan kustom lainnya di sini jika perlu
        ];
    }
}
