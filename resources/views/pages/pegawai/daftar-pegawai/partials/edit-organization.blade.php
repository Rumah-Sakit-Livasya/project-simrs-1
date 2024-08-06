<div class="modal fade p-0" id="ubah-organisasi" tabindex="-2" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form autocomplete="off" novalidate method="post" id="update-form-organization">
                @method('post')
                @csrf
                <div class="modal-header">
                    <h5 class="font-weight-bold">Ubah Kepegawaian</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body py-0">
                    <div class="row justify-content-center align-items-center">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fullname">Nama</label>
                                <input type="text" id="fullname" name="fullname" class="form-control">
                                @error('fullname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text" id="email" name="email" class="form-control">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="employee_code">NIP</label>
                                <input type="text" id="employee_code" name="employee_code" class="form-control">
                                @error('employee_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4 mt-3">
                            <div class="form-group">
                                <label for="birthdate">Tgl. Lahir</label>
                                <div class="input-group">
                                    <input type="text" name="birthdate"
                                        class="form-control datepicker @error('birthdate') is-invalid @enderror"
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
                        </div>
                        <div class="col-md-4 mt-3">
                            <div class="form-group">
                                <label for="identity_number">Nomor Identitas</label>
                                <input type="text" id="nik" name="identity_number" class="form-control">
                                @error('identity_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4 mt-3">
                            <div class="form-group">
                                <label for="mobile_phone">No. Hp</label>
                                <input type="text" id="mobile_phone" name="mobile_phone" class="form-control">
                                @error('mobile_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12 mt-3">
                            <div class="form-group">
                                <label for="company_id">Perusahaan</label>
                                <select class="select2 form-control w-100  @error('company_id') is-invalid @enderror"
                                    id="company_id" name="company_id">
                                    <option value=""></option>
                                    @foreach ($company as $row)
                                        <option value="{{ $row->id }}">{{ $row->id }} -
                                            {{ $row->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('company_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-5 mt-3">
                            <div class="form-group">
                                <label for="departement_id">Departement</label>
                                <select
                                    class="select2 form-control w-100  @error('organization_id') is-invalid @enderror"
                                    id="departement_id" name="departement_id" disabled>
                                    <option value=""></option>
                                    @foreach ($departements as $departement)
                                        <option value="{{ $departement->id }}">{{ $departement->id }} -
                                            {{ $departement->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('departement_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4 mt-3">
                            <div class="form-group">
                                <label for="kode_dpjp">Kode DPJP</label>
                                <input type="text" id="kode_dpjp" name="kode_dpjp" class="form-control"
                                    placeholder="Masukan Kode DPJP" disabled>
                                @error('kode_dpjp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3 mt-5">
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox"
                                        class="custom-control-input @error('is_doctor') is-invalid @enderror"
                                        id="is_doctor" value="on" name="is_doctor">
                                    <label class="custom-control-label" for="is_doctor">Dokter?</label>
                                </div>
                                @error('is_doctor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="form-group">
                                <label for="organization_id">Organisasi</label>
                                <select
                                    class="select2 form-control w-100  @error('organization_id') is-invalid @enderror"
                                    id="organization_id" name="organization_id">
                                    <option value=""></option>
                                    @foreach ($organizations as $organization)
                                        <option value="{{ $organization->id }}">{{ $organization->id }} -
                                            {{ $organization->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('organization_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="form-group">
                                <label for="job_position_id">Jabatan</label>
                                <select
                                    class="select2 form-control w-100  @error('job_position_id') is-invalid @enderror"
                                    id="job_position_id" name="job_position_id">
                                    <option value=""></option>
                                    @foreach ($jobPosition as $row)
                                        <option value="{{ $row->id }}">{{ $row->id }} -
                                            {{ $row->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('job_position_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4 mt-3">
                            <div class="form-group">
                                <label for="employment_status">Status</label>
                                <select
                                    class="select2 form-control w-100  @error('employment_status') is-invalid @enderror"
                                    id="employment_status" name="employment_status">
                                    <option value="Kontrak">Kontrak</option>
                                    <option value="Permanen">Permanen</option>
                                    <option value="Percobaan">Percobaan</option>
                                    <option value="Outsource">Outsource</option>
                                </select>
                                @error('employment_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4 mt-3">
                            <div class="form-group">
                                <label for="join_date">Mulai Kontrak</label>
                                <div class="input-group">
                                    <input type="text" name="join_date"
                                        class="form-control datepicker @error('join_date') is-invalid @enderror"
                                        placeholder="Tanggal Lahir" id="join_date" value="{{ old('join_date') }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text fs-xl">
                                            <i class="fal fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                    @error('join_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                @error('join_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4 mt-3">
                            <div class="form-group">
                                <label for="end_status_date">Akhir Kontrak</label>
                                <div class="input-group">
                                    <input type="text" name="end_status_date"
                                        class="form-control datepicker @error('end_status_date') is-invalid @enderror"
                                        placeholder="Tanggal Lahir" id="end_status_date"
                                        value="{{ old('end_status_date') }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text fs-xl">
                                            <i class="fal fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                    @error('end_status_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                @error('end_status_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-primary" id="tbh-lokasi">
                        <div class="ikon-ubah-organisasi">
                            <span class="fal fa-plus-circle mr-1"></span>
                            Ubah
                        </div>
                        <div class="span spinner-text d-none">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Loading...
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
