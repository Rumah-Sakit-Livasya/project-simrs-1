{{-- SEMUA FORM DATA PEGAWAI DALAM SATU HALAMAN TANPA PAGINATION/STEPPER --}}

<hr>

{{-- DATA PRIBADI --}}
<h4 class="mb-3">1. Data Pribadi</h4>
<div class="row">
    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label class="form-label" for="fullname">Nama Lengkap</label>
            <input type="text" name="fullname" id="fullname" class="form-control"
                value="{{ old('fullname', $employee->fullname) }}" required>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label class="form-label" for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control"
                value="{{ old('email', $employee->email) }}" required>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label class="form-label" for="mobile_phone">No. HP</label>
            <input type="text" name="mobile_phone" id="mobile_phone" class="form-control"
                value="{{ old('mobile_phone', $employee->mobile_phone) }}" required>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label class="form-label" for="place_of_birth">Tempat Lahir</label>
            <input type="text" name="place_of_birth" id="place_of_birth" class="form-control"
                value="{{ old('place_of_birth', $employee->place_of_birth) }}" required>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label class="form-label" for="birthdate">Tanggal Lahir</label>
            <input type="text" name="birthdate" id="birthdate" class="form-control datepicker"
                value="{{ old('birthdate', $employee->birthdate) }}" required>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label class="form-label" for="gender">Jenis Kelamin</label>
            <select name="gender" id="gender" class="select2 form-control" required>
                <option value=""></option>
                <option value="Laki-laki" @if (old('gender', $employee->gender) == 'Laki-laki') selected @endif>Laki-laki</option>
                <option value="Perempuan" @if (old('gender', $employee->gender) == 'Perempuan') selected @endif>Perempuan</option>
            </select>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="form-group">
            <label class="form-label" for="marital_status">Status Menikah</label>
            <select name="marital_status" id="marital_status" class="select2 form-control" required>
                <option value=""></option>
                <option value="Lajang" @if (old('marital_status', $employee->marital_status) == 'Lajang') selected @endif>Lajang</option>
                <option value="Menikah" @if (old('marital_status', $employee->marital_status) == 'Menikah') selected @endif>Menikah</option>
                <option value="Janda" @if (old('marital_status', $employee->marital_status) == 'Janda') selected @endif>Janda</option>
                <option value="Duda" @if (old('marital_status', $employee->marital_status) == 'Duda') selected @endif>Duda</option>
            </select>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="form-group">
            <label class="form-label" for="blood_type">Golongan Darah</label>
            <select name="blood_type" id="blood_type" class="select2 form-control">
                <option value=""></option>
                <option value="A" @if (old('blood_type', $employee->blood_type) == 'A') selected @endif>A</option>
                <option value="B" @if (old('blood_type', $employee->blood_type) == 'B') selected @endif>B</option>
                <option value="AB" @if (old('blood_type', $employee->blood_type) == 'AB') selected @endif>AB</option>
                <option value="O" @if (old('blood_type', $employee->blood_type) == 'O') selected @endif>O</option>
            </select>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="form-group">
            <label class="form-label" for="religion">Agama</label>
            <select name="religion" id="religion" class="select2 form-control" required>
                <option value=""></option>
                <option value="Islam" @if (old('religion', $employee->religion) == 'Islam') selected @endif>Islam</option>
                <option value="Kristen" @if (old('religion', $employee->religion) == 'Kristen') selected @endif>Kristen</option>
                <option value="Katholik" @if (old('religion', $employee->religion) == 'Katholik') selected @endif>Katholik</option>
                <option value="Hindu" @if (old('religion', $employee->religion) == 'Hindu') selected @endif>Hindu</option>
                <option value="Budha" @if (old('religion', $employee->religion) == 'Budha') selected @endif>Budha</option>
                <option value="Lainnya" @if (old('religion', $employee->religion) == 'Lainnya') selected @endif>Lainnya</option>
            </select>
        </div>
    </div>
</div>

<hr>

{{-- DATA IDENTITAS --}}
<h4 class="mb-3">2. Data Identitas & Alamat</h4>
<div class="row">
    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label class="form-label" for="identity_type">Tipe Identitas</label>
            <select name="identity_type" id="identity_type" class="select2 form-control" required>
                <option value=""></option>
                <option value="KTP" @if (old('identity_type', $employee->identity_type) == 'KTP') selected @endif>KTP</option>
                <option value="SIM" @if (old('identity_type', $employee->identity_type) == 'SIM') selected @endif>SIM</option>
                <option value="Passport" @if (old('identity_type', $employee->identity_type) == 'Passport') selected @endif>Passport</option>
            </select>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label class="form-label" for="identity_number">Nomor Identitas</label>
            <input type="text" name="identity_number" id="identity_number" class="form-control"
                value="{{ old('identity_number', $employee->identity_number) }}" required>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label class="form-label" for="identity_expire_date">Tanggal Kadaluarsa Identitas</label>
            <input type="text" name="identity_expire_date" id="identity_expire_date"
                class="form-control datepicker"
                value="{{ old('identity_expire_date', $employee->identity_expire_date) }}">
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label class="form-label" for="npwp">NPWP</label>
            <input type="text" name="npwp" id="npwp" class="form-control"
                value="{{ old('npwp', $employee->npwp) }}">
        </div>
    </div>
    <div class="col-md-12 mb-3">
        <div class="form-group">
            <label class="form-label" for="citizen_id_address">Alamat Sesuai KTP</label>
            <textarea name="citizen_id_address" id="citizen_id_address" class="form-control" rows="3" required>{{ old('citizen_id_address', $employee->citizen_id_address) }}</textarea>
        </div>
    </div>
    <div class="col-md-12 mb-3">
        <div class="form-group">
            <label class="form-label" for="residental_address">Alamat Tinggal Sekarang</label>
            <div class="custom-control custom-checkbox mb-2">
                <input type="checkbox" class="custom-control-input" id="sama-alamat">
                <label class="custom-control-label" for="sama-alamat">Sama dengan Alamat KTP</label>
            </div>
            <textarea name="residental_address" id="residental_address" class="form-control" rows="3" required>{{ old('residental_address', $employee->residental_address) }}</textarea>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="form-group">
            <label class="form-label" for="postal_code">Kode Pos</label>
            <input type="text" name="postal_code" id="postal_code" class="form-control"
                value="{{ old('postal_code', $employee->postal_code) }}">
        </div>
    </div>
</div>

<hr>

{{-- DATA KEPEGAWAIAN --}}
<h4 class="mb-3">3. Data Kepegawaian</h4>
<div class="row">
    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label class="form-label" for="employee_code">NIP</label>
            <input type="text" name="employee_code" id="employee_code" class="form-control"
                value="{{ old('employee_code', $employee->employee_code) }}" required>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label class="form-label" for="company_id">Perusahaan</label>
            <select name="company_id" id="company_id" class="select2 form-control" required>
                <option value=""></option>
                @foreach ($companies as $company)
                    <option value="{{ $company->id }}" @if (old('company_id', $employee->company_id) == $company->id) selected @endif>
                        {{ $company->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label class="form-label" for="organization_id">Unit / Organisasi</label>
            <select name="organization_id" id="organization_id" class="select2 form-control" required>
                <option value=""></option>
                @foreach ($organizations as $org)
                    <option value="{{ $org->id }}" @if (old('organization_id', $employee->organization_id) == $org->id) selected @endif>
                        {{ $org->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label class="form-label" for="job_position_id">Jabatan</label>
            <select name="job_position_id" id="job_position_id" class="select2 form-control" required>
                <option value=""></option>
                @foreach ($jobPositions as $pos)
                    <option value="{{ $pos->id }}" @if (old('job_position_id', $employee->job_position_id) == $pos->id) selected @endif>
                        {{ $pos->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label class="form-label" for="job_level_id">Level Jabatan</label>
            <select name="job_level_id" id="job_level_id" class="select2 form-control" required>
                <option value=""></option>
                @foreach ($jobLevels as $level)
                    <option value="{{ $level->id }}" @if (old('job_level_id', $employee->job_level_id) == $level->id) selected @endif>
                        {{ $level->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label class="form-label" for="employment_status">Status Pegawai</label>
            <select name="employment_status" id="employment_status" class="select2 form-control" required>
                <option value=""></option>
                <option value="Kontrak" @if (old('employment_status', $employee->employment_status) == 'Kontrak') selected @endif>Kontrak</option>
                <option value="Tetap" @if (old('employment_status', $employee->employment_status) == 'Tetap') selected @endif>Tetap</option>
                <option value="Probation" @if (old('employment_status', $employee->employment_status) == 'Probation') selected @endif>Probation</option>
            </select>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label class="form-label" for="join_date">Tanggal Bergabung</label>
            <input type="text" name="join_date" id="join_date" class="form-control datepicker"
                value="{{ old('join_date', $employee->join_date) }}" required>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label class="form-label" for="end_status_date">Tanggal Berakhir Kontrak (Opsional)</label>
            <input type="text" name="end_status_date" id="end_status_date" class="form-control datepicker"
                value="{{ old('end_status_date', $employee->end_status_date) }}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <hr>
        <h5 class="frame-heading">Khusus Tenaga Medis</h5>
    </div>
    <div class="col-md-2 mb-3">
        <div class="form-group">
            <label class="form-label">Apakah Dokter?</label>
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="is_doctor" name="is_doctor"
                    @if (old('is_doctor', $employee->is_doctor)) checked @endif>
                <label class="custom-control-label" for="is_doctor">Ya</label>
            </div>
        </div>
    </div>
    <div class="col-md-5 mb-3">
        <div class="form-group">
            <label class="form-label" for="departement_id">Spesialisasi / Poli</label>
            <select name="departement_id" id="departement_id" class="select2 form-control"
                {{ $employee->is_doctor ? '' : 'disabled' }}>
                <option value=""></option>
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}" @if (old('departement_id', optional($employee->doctor)->departement_id) == $department->id) selected @endif>
                        {{ $department->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-5 mb-3">
        <div class="form-group">
            <label class="form-label" for="kode_dpjp">Kode DPJP</label>
            <input type="text" name="kode_dpjp" id="kode_dpjp" class="form-control"
                value="{{ old('kode_dpjp', optional($employee->doctor)->kode_dpjp) }}"
                {{ $employee->is_doctor ? '' : 'disabled' }}>
        </div>
    </div>
</div>

<hr>

{{-- GAJI & APPROVAL --}}
<h4 class="mb-3">4. Data Gaji & Approval</h4>
<div class="row">
    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label class="form-label" for="approval_line">Atasan Langsung (Approval 1)</label>
            <select name="approval_line" id="approval_line" class="select2 form-control">
                <option value=""></option>
                @foreach ($allEmployees as $emp)
                    <option value="{{ $emp->id }}" @if (old('approval_line', $employee->approval_line) == $emp->id) selected @endif>
                        {{ $emp->fullname }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label class="form-label" for="approval_line_parent">Atasan Berikutnya (Approval 2)</label>
            <select name="approval_line_parent" id="approval_line_parent" class="select2 form-control">
                <option value=""></option>
                @foreach ($allEmployees as $emp)
                    <option value="{{ $emp->id }}" @if (old('approval_line_parent', $employee->approval_line_parent) == $emp->id) selected @endif>
                        {{ $emp->fullname }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-12 mb-3">
        <div class="form-group">
            <label class="form-label" for="basic_salary">Gaji Pokok</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Rp</span>
                </div>
                <input type="number" class="form-control" name="basic_salary" id="basic_salary"
                    value="{{ old('basic_salary', $employee->basic_salary) }}" required>
            </div>
        </div>
    </div>
</div>
<h5 class="frame-heading mt-3">Informasi Bank</h5>
<div class="row">
    <div class="col-md-4 mb-3">
        <div class="form-group">
            <label class="form-label" for="bank_id">Nama Bank</label>
            <select name="bank_id" id="bank_id" class="select2 form-control">
                <option value=""></option>
                @foreach ($banks as $bank)
                    <option value="{{ $bank->id }}" @if (old('bank_id', optional($employee->bank_employee)->bank_id) == $bank->id) selected @endif>
                        {{ $bank->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="form-group">
            <label class="form-label" for="account_number">Nomor Rekening</label>
            <input type="text" name="account_number" id="account_number" class="form-control"
                value="{{ old('account_number', optional($employee->bank_employee)->account_number) }}">
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="form-group">
            <label class="form-label" for="account_holder_name">Nama Pemilik Rekening</label>
            <input type="text" name="account_holder_name" id="account_holder_name" class="form-control"
                value="{{ old('account_holder_name', optional($employee->bank_employee)->account_holder_name) }}">
        </div>
    </div>
</div>

{{-- Script untuk mengaktifkan/menonaktifkan field dokter --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const isDoctorCheckbox = document.getElementById('is_doctor');
        const departmentSelect = $('#departement_id'); // Pakai jQuery karena sudah select2
        const dpjpInput = document.getElementById('kode_dpjp');

        function toggleDoctorFields() {
            const isChecked = isDoctorCheckbox.checked;
            dpjpInput.disabled = !isChecked;
            departmentSelect.prop('disabled', !isChecked).trigger('change');
        }

        isDoctorCheckbox.addEventListener('change', toggleDoctorFields);

        // Inisialisasi state saat halaman dimuat
        toggleDoctorFields();
    });
</script>
