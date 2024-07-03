<div class="tambah-pegawai-baru mt-3 mb-3">
    <div class="row justify-content-center mt-3 mb-5">
        <div>
            <a href="javascript:void(0);"
                class="btn mx-1 btn-lg btn-primary btn-icon step-round rounded-circle position-relative js-waves-off"
                id="step-round-1">
                1
                <span class="step-text">Info Pribadi</span>
            </a>
            <a href="javascript:void(0);"
                class="btn mx-1 btn-success btn-lg btn-icon step-round rounded-circle waves-effect waves-themed d-none"
                id="step-round-1-done">
                <i class="fal fa-check"></i>
            </a>
            <span class="garis"></span>
            <a href="javascript:void(0);"
                class="btn mx-1 btn-lg btn-outline-primary step-round btn-icon rounded-circle position-relative js-waves-off"
                id="step-round-2">
                2
                <span class="step-text">Info Pegawai</span>
            </a>
            <a href="javascript:void(0);"
                class="btn mx-1 btn-success btn-lg btn-icon step-round rounded-circle waves-effect waves-themed d-none"
                id="step-round-2-done">
                <i class="fal fa-check"></i>
            </a>
            <span class="garis"></span>
            <a href="javascript:void(0);"
                class="btn mx-1 btn-lg btn-outline-primary step-round btn-icon rounded-circle position-relative js-waves-off"
                id="step-round-3">
                3
                <span class="step-text">Gaji Pegawai</span>
            </a>
            <a href="javascript:void(0);"
                class="btn mx-1 btn-success btn-lg btn-icon step-round rounded-circle waves-effect waves-themed d-none"
                id="step-round-3-done">
                <i class="fal fa-check"></i>
            </a>
            <span class="garis"></span>
            <a href="javascript:void(0);"
                class="btn mx-1 btn-lg btn-outline-primary step-round btn-icon rounded-circle position-relative js-waves-off">
                4
                <span class="step-text">Invite Pegawai</span>
            </a>
        </div>
    </div>
    <form action="" method="POST" id="store-form-employee">
        @method('POST')
        @csrf
        <div id="step-1">
            <div id="data-personal">
                <div class="row" style="margin-top: 70px">
                    <div class="col-md-12">
                        <h4 class="ui-sortable-handle form-heading">Data
                            Personal</h4>
                        <p class="mb-0">Isi data informasi pegawai berikut
                            ini! </p>
                        <hr style="margin-top: 10px !important;">
                    </div>
                </div>
                <div class="form-group row" style="font-size: 0.8rem !important;">
                    <div class="col-md-12 mb-3">
                        <label>Nama*</label>
                        <input type="text" class="form-control @error('fullname') is-invalid @enderror"
                            autocomplete="off" name="fullname" placeholder="Fizar Rama Waluyo, S. Kom."
                            value="{{ old('fullname') }}">
                        @error('fullname')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Email*</label>
                        <input type="text" class="form-control @error('email') is-invalid @enderror" name="email"
                            autocomplete="off" placeholder="xxxxxx@gmail.com" value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>No. Telepon*</label>
                        <input type="text" class="form-control @error('mobile_phone') is-invalid @enderror"
                            name="mobile_phone" autocomplete="off" placeholder="085xxxxxxxxx"
                            value="{{ old('mobile_phone') }}">
                        @error('mobile_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Tempat Lahir*</label>
                        <input type="text" class="form-control @error('place_of_birth') is-invalid @enderror"
                            name="place_of_birth" autocomplete="off" placeholder="Majalengka"
                            value="{{ old('place_of_birth') }}">
                        @error('place_of_birth')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Tanggal Lahir*</label>
                        <div class="input-group">
                            <input type="text" name="birthdate"
                                class="form-control @error('birthdate') is-invalid @enderror"
                                placeholder="Tanggal Lahir" id="birthdate" value="{{ old('birthdate') }}">
                            <div class="input-group-append">
                                <span class="input-group-text fs-xl">
                                    <i class="fal fa-calendar-alt"></i>
                                </span>
                            </div>
                            @error('birthdate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Jenis Kelamin*</label>
                        <select class="select2 form-control w-100 @error('gender') is-invalid @enderror" id="gender"
                            name="gender">
                            <option value=""></option>
                            <option value="Laki-laki" {{ old('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                            </option>
                            <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan
                            </option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Status Menikah*</label>
                        <select class="select2 form-control w-100" id="marital_status" name="marital_status">
                            <option value=""></option>
                            <option value="Lajang" {{ old('marita_status') == 'Lajang' ? 'selected' : '' }}>Lajang
                            </option>
                            <option value="Menikah" {{ old('marita_status') == 'Menikah' ? 'selected' : '' }}>Menikah
                            </option>
                            <option value="Janda" {{ old('marita_status') == 'Janda' ? 'selected' : '' }}>Janda
                            </option>
                            <option value="Duda" {{ old('marita_status') == 'Duda' ? 'selected' : '' }}>Duda
                            </option>
                        </select>
                        @error('marita_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Golongan Darah</label>
                        <input type="text" class="form-control" name="blood_type" autocomplete="off"
                            placeholder="O">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Agama*</label>
                        <select class="select2 form-control w-100" id="religion" name="religion">
                            <option value=""></option>
                            <option value="Islam" {{ old('religion') == 'Islam' ? 'selected' : '' }}>Islam</option>
                            <option value="Katholik" {{ old('religion') == 'Katholik' ? 'selected' : '' }}>Katholik
                            </option>
                            <option value="Kristen" {{ old('religion') == 'Kristen' ? 'selected' : '' }}>Kristen
                            </option>
                            <option value="Budha" {{ old('religion') == 'Budha' ? 'selected' : '' }}>Budha</option>
                            <option value="Hindu" {{ old('religion') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                            <option value="Lainnya" {{ old('religion') == 'Lainnya' ? 'selected' : '' }}>Lainnya
                            </option>
                        </select>
                        @error('religion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="data-identitas">
                <div class="row mt-3">
                    <div class="col-md-12">
                        <h4 class="ui-sortable-handle form-heading">Kartu
                            Identitas</h4>
                        <p class="mb-0">Isi data identitas berikut ini! </p>
                        <hr style="margin-top: 10px !important;">
                    </div>
                </div>
                <div class="form-group row" style="font-size: 0.8rem !important;">
                    <div class="col-md-6 mb-3">
                        <label>Tipe Identitas*</label>
                        <select class="select2 form-control w-100" id="identity_type" name="identity_type">
                            <option value=""></option>
                            <option value="KTP" {{ old('identity_type') == 'KTP' ? 'selected' : '' }}>KTP</option>
                            <option value="Passport" {{ old('identity_type') == 'Passport' ? 'selected' : '' }}>
                                Passport</option>
                        </select>
                        @error('identity_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Nomor Identitas*</label>
                        <input type="text" class="form-control @error('identity_number') is-invalid @enderror"
                            id="identity_number" name="identity_number" autocomplete="off"
                            placeholder="321xxxxxxxxx" value="{{ old('identity_number') }}">
                        @error('identity_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Masa Berlaku</label>
                        <div class="input-group">
                            <input type="text" name="identity_expire_date" class="form-control "
                                placeholder="Kosongkan Jika Permanent" id="identity_expire_date">
                            <div class="input-group-append">
                                <span class="input-group-text fs-xl">
                                    <i class="fal fa-calendar-alt"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Postal Code</label>
                        <input type="text" class="form-control" name="postal_code" autocomplete="off"
                            placeholder="454xx">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label" for="example-textarea">Alamat di
                            KTP*</label>
                        <textarea class="form-control @error('citizen_id_address') is-invalid @enderror" id="citizen_id_address"
                            rows="3" name="citizen_id_address"></textarea>
                        <div class="custom-control custom-checkbox mt-3">
                            <input type="checkbox" class="custom-control-input" id="sama-alamat">
                            <label class="custom-control-label" for="sama-alamat">Jadikan
                                alaman
                                tempat
                                tinggal</label>
                        </div>
                        @error('citizen_id_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label" for="example-textarea">Alamat Tempat
                            Tinggal</label>
                        <textarea class="form-control" id="residental_address" rows="3" name="residental_address"></textarea>
                    </div>
                </div>
            </div>
            <div class="btn-next mt-3 text-right">
                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary">Batal</a>
                <a href="#" class="btn-next-step btn btn-primary btn-sm ml-2">Selanjutnya</a>
            </div>
        </div>
        <div id="step-2" style="display: none">
            <div id="data-pegawai">
                <div class="row" style="margin-top: 70px">
                    <div class="col-md-12">
                        <h4 class="ui-sortable-handle form-heading">Data
                            Kepegawaian</h4>
                        <p class="mb-0">Isi data informasi pegawai berikut
                            ini! </p>
                        <hr style="margin-top: 10px !important;">
                    </div>
                </div>
                <div class="form-group row" style="font-size: 0.8rem !important;">
                    <div class="col-md-12 mb-3">
                        <label>Nomor Induk Pegawai*</label>
                        <input type="text" class="form-control @error('employee_id') is-invalid @enderror"
                            autocomplete="off" name="employee_id" placeholder="2024xxxxx"
                            value="{{ old('employee_id') }}">
                        @error('employee_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Status Pegawai*</label>
                        <select class="select2 form-control w-100 @error('employment_status') is-invalid @enderror"
                            id="employment_status_create" name="employment_status">
                            <option value=""></option>
                            <option value="Permanen" {{ old('employee_id') == 'Permanen' ? 'selected' : '' }}>Permanen
                            </option>
                            <option value="Kontrak" {{ old('employee_id') == 'Kontrak' ? 'selected' : '' }}>Kontrak
                            </option>
                            <option value="Percobaan" {{ old('employee_id') == 'Percobaan' ? 'selected' : '' }}>
                                Percobaan</option>
                            <option value="Outsource" {{ old('employee_id') == 'Outsource' ? 'selected' : '' }}>
                                Outsource</option>
                        </select>
                        @error('employment_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Tanggal Masuk Kerja*</label>
                        <div class="input-group">
                            <input type="text" name="join_date"
                                class="form-control @error('join_date') is-invalid @enderror"
                                placeholder="Tanggal Masuk Kerja" id="join_date" value="{{ old('join_date') }}">
                            <div class="input-group-append">
                                <span class="input-group-text fs-xl">
                                    <i class="fal fa-calendar-alt"></i>
                                </span>
                            </div>
                            @error('join_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Organisasi*</label>
                        <select class="select2 form-control w-100 @error('organization_id') is-invalid @enderror"
                            id="organization_id_create" name="organization_id">
                            <option value=""></option>
                            @foreach ($organizations as $row)
                                <option value="{{ $row->id }}"
                                    {{ old('organization_id') == $row->id ? 'selected' : '' }}>
                                    {{ $row->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('organization_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Jabatan*</label>
                        <select class="select2 form-control w-100 @error('job_position_id') is-invalid @enderror"
                            id="job_position_id_create" name="job_position_id">
                            <option value=""></option>
                            @foreach ($jobPosition as $row)
                                <option value="{{ $row->id }}"
                                    {{ old('job_position_id') == $row->id ? 'selected' : '' }}>
                                    {{ $row->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('job_position_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Level Jabatan*</label>
                        <select class="select2 form-control w-100 @error('job_level_id') is-invalid @enderror"
                            id="job_level_id" name="job_level_id">
                            <option value=""></option>
                            @foreach ($jobLevel as $row)
                                <option value="{{ $row->id }}"
                                    {{ old('job_level_id') == $row->id ? 'selected' : '' }}>
                                    {{ $row->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('job_level_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Approval Line*</label>
                        <select class="select2 form-control w-100 @error('approval_line') is-invalid @enderror"
                            id="approval_line" name="approval_line">
                            <option value=""></option>
                            @foreach ($employees as $row)
                                <option value="{{ $row->id }}"
                                    {{ old('approval_line') == $row->id ? 'selected' : '' }}>
                                    {{ $row->fullname }}
                                </option>
                            @endforeach
                        </select>
                        @error('approval_line')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Manager</label>
                        <select class="select2 form-control w-100" id="approval_line_parent"
                            name="approval_line_parent">
                            <option value=""></option>
                            @foreach ($employees as $row)
                                <option value="{{ $row->id }}">
                                    {{ $row->fullname }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="btn-next mt-3 text-right">
                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary btn-prev">Sebelumnya</a>
                <a href="#" class="btn-next-step btn btn-primary btn-sm ml-2">Selanjutnya</a>
            </div>
        </div>
        <div id="step-3" style="display: none">
            <div id="data-gaji">
                <div class="row" style="margin-top: 70px">
                    <div class="col-md-12">
                        <h4 class="ui-sortable-handle form-heading">Data Gaji
                        </h4>
                        <p class="mb-0">Isi data informasi berikut ini! </p>
                        <hr style="margin-top: 10px !important;">
                    </div>
                </div>
                <div class="form-group row" style="font-size: 0.8rem !important;">
                    <div class="col-md-6 mb-3">
                        <label>Gaji Pokok*</label>
                        <input type="number" class="form-control @error('basic_salary') is-invalid @enderror"
                            autocomplete="off" name="basic_salary" id="basic_salary" placeholder="2024xxxxx"
                            value="{{ old('basic_salary') }}">
                        @error('basic_salary')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Tanggal Pembayaran Gaji</label>
                        <input type="payment_schedule" class="form-control " value="Default" disabled>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Tipe Gaji</label>
                        <div class="frame-wrap mt-2">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="defaultInline1Radio"
                                    name="salary_type" value="bulanan" checked="">
                                <label class="custom-control-label" for="defaultInline1Radio">Bulanan</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="defaultInline2Radio"
                                    name="salary_type" value="harian">
                                <label class="custom-control-label" for="defaultInline2Radio">Harian</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Dibolehkan Untuk Lembur</label>
                        <div class="frame-wrap mt-2">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="prorate_setting1"
                                    name="allowed_for_overtime" value="1" checked="">
                                <label class="custom-control-label" for="prorate_setting1">Iya</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="prorate_setting2"
                                    name="allowed_for_overtime" value="0">
                                <label class="custom-control-label" for="prorate_setting2">Tidak</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="data-bank">
                <div class="row mt-3">
                    <div class="col-md-12">
                        <h4 class="ui-sortable-handle form-heading">Akun Bank
                        </h4>
                        <p class="mb-0">Isi data bank berikut ini untuk
                            penggajian! </p>
                        <hr style="margin-top: 10px !important;">
                    </div>
                </div>
                <div class="form-group row" style="font-size: 0.8rem !important;">
                    <div class="col-md-12 mb-3">
                        <label>Nama Bank*</label>
                        <select class="select2 form-control w-100 @error('bank_id') is-invalid @enderror"
                            id="bank_id" name="bank_id">
                            <option value=""></option>
                            @foreach ($bank as $item)
                                <option value="{{ $item->id }}"
                                    {{ old('bank_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('bank_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Nomor Rekening*</label>
                        <input type="text" class="form-control @error('account_number') is-invalid @enderror"
                            name="account_number" autocomplete="off" placeholder="4310xxxxxxxx"
                            value="{{ old('account_number') }}">
                        @error('account_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Nama Rekening*</label>
                        <input type="text" class="form-control @error('account_holder_name') is-invalid @enderror"
                            name="account_holder_name" autocomplete="off" placeholder="Fizar Rama Waluyo"
                            value="{{ old('account_holder_name') }}">
                        @error('account_holder_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="data-pajak">
                <div class="row mt-3">
                    <div class="col-md-12">
                        <h4 class="ui-sortable-handle form-heading">Konfigurasi
                            Pajak</h4>
                        <p class="mb-0">Isi data berikut ini! </p>
                        <hr style="margin-top: 10px !important;">
                    </div>
                </div>
                <div class="form-group row" style="font-size: 0.8rem !important;">
                    <div class="col-md-6 mb-3">
                        <label>NPWP</label>
                        <input type="text" class="form-control" name="npwp" autocomplete="off"
                            placeholder="0000 0000 0000 0000">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>PTKP Status</label>
                        <select class="select2 form-control w-100" id="ptkp_status" name="ptkp_status">
                            <option value="TK/0">
                                TK/0
                            </option>
                            <option value="TK/1">
                                TK/1
                            </option>
                            <option value="TK/2">
                                TK/2
                            </option>
                            <option value="TK/3">
                                TK/3
                            </option>
                            <option value="K/0">
                                K/0
                            </option>
                            <option value="K/1">
                                K/1
                            </option>
                            <option value="K/2">
                                K/2
                            </option>
                            <option value="K/3">
                                K/3
                            </option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Tax Methode</label>
                        <select class="select2 form-control w-100" id="tax_methode" name="tax_methode">
                            <option value="Gross">Gross</option>
                            <option value="Gross Up">Gross Up</option>
                            <option value="Netto">Netto</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Tax Salary</label>
                        <select class="select2 form-control w-100" id="tax_salary" name="tax_salary">
                            <option value="Taxable">Taxable</option>
                            <option value="Non-Taxable">Non-Taxable</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Taxable Date</label>
                        <div class="input-group">
                            <input type="text" name="taxable_date" class="form-control "
                                placeholder="Taxable Date" id="taxable_date">
                            <div class="input-group-append">
                                <span class="input-group-text fs-xl">
                                    <i class="fal fa-calendar-alt"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Status Pajak Pegawai</label>
                        <select class="select2 form-control w-100" id="employment_tax_status"
                            name="employment_tax_status">
                            <option value="Pegawai tetap">Pegawai tetap
                            </option>
                            <option value="Pegawai tidak tetap">Pegawai tidak
                                tetap
                            </option>
                            <option value="Bukan pegawai yang bersifat berkesinambungan">
                                Bukan
                                pegawai yang bersifat berkesinambungan</option>
                            <option value="Bukan pegawai yang tidak bersifat berkesinambungan">
                                Bukan pegawai yang tidak bersifat
                                berkesinambungan</option>
                            <option value="Ekspatriat">Ekspatriat</option>
                            <option value="Ekspatriat dalam negeri">Ekspatriat
                                dalam negeri
                            </option>
                            <option value="Tenaga ahli yang bersifat berkesinambungan">
                                Tenaga ahli
                                yang bersifat berkesinambungan</option>
                            <option value="Tenaga ahli yang tidak bersifat berkesinambungan">
                                Tenaga
                                ahli yang tidak bersifat berkesinambungan
                            </option>
                            <option value="Dewan komisaris">Dewan komisaris
                            </option>
                            <option value="Tenaga ahli yang bersifat berkesinambungan 1 PK">
                                Tenaga ahli yang bersifat berkesinambungan 1 PK
                            </option>
                            <option value="Tenaga kerja lepas">Tenaga kerja
                                lepas</option>
                            <option value="Bukan pegawai yang bersifat berkesinambungan 1 PK">
                                Bukan pegawai yang bersifat berkesinambungan 1
                                PK</option>

                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Beginning Netto</label>
                        <input type="text" name="beginning_netto" id="beginning_netto" placeholder="100000"
                            class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>PPH21 Paid</label>
                        <input type="text" name="pph21_paid" id="pph21_paid" placeholder="100000"
                            class="form-control">
                    </div>
                </div>
            </div>
            <div class="btn-next mt-3 text-right">
                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary btn-prev">Sebelumnya</a>
                <a href="#" class="btn-next-step btn btn-primary btn-sm ml-2">Selanjutnya</a>
            </div>
        </div>
        <div id="step-4" style="display: none">
            <div id="data-gaji">
                <div class="row" style="margin-top: 70px">
                    <div class="col-md-12">
                        <h4 class="ui-sortable-handle form-heading">Apakah Data
                            Pegawai
                            yang Sudah
                            Diisikan Benar?
                        </h4>
                        <p class="mb-0">Klik tombol Tambah dibawah ini untuk
                            menyimpan
                            Data
                            Pegawai!</p>
                        <hr style="margin-top: 10px !important;">

                    </div>
                </div>
            </div>
            <div class="btn-next mt-3 text-right">
                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary btn-prev">Sebelumnya</a>
                <button type="submit" class="btn btn-sm btn-primary">
                    <div class="ikon-tambah">
                        <span class="fal fa-plus-circle mr-1"></span>
                        Tambah
                    </div>
                    <div class="span spinner-text d-none">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Loading...
                    </div>
                </button>
            </div>
        </div>
    </form>
</div>
