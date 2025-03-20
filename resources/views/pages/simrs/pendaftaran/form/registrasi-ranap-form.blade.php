<form action="{{ route('simpan.registrasi') }}" method="POST" id="form-registrasi">
    @method('post')
    @csrf
    <input type="hidden" name="patient_id" value="{{ old('patient_id', $patient->id) }}">
    <input type="hidden" name="user_id" value="{{ old('user_id', auth()->user()->id) }}">
    <input type="hidden" name="employee_id" value="{{ old('employee_id', auth()->user()->employee->id) }}">
    <input type="hidden" name="registration_type" value="{{ old('registration_type', 'rawat-inap') }}">
    <div class="row">
        <div class="col-xl-6">
            <div class="form-group">
                <div class="row align-items-center">
                    <div class="col-xl-4 text-right">
                        <label class="form-label" for="registration_date">Tanggal Registrasi</label>
                    </div>
                    <div class="col-xl-8">
                        <input type="text"
                            style="border: 0; border-bottom: 1.9px dashed #aaa; margin-top: -.5rem; border-radius: 0"
                            class="form-control" id="registration_date" readonly
                            value="{{ old('registration_date', $today) }}" name="registration_date">
                        @error('registration_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row align-items-center">
                    <div class="col-xl-4 text-right">
                        <label class="form-label" for="doctor_id">Dokter</label>
                    </div>
                    <div class="col-xl-8">
                        <div class="form-group">
                            <select class="select2 form-control w-100" id="doctor_id" name="doctor_id">
                                <option value=""></option>
                                @foreach ($groupedDoctors as $department => $doctors)
                                    <optgroup label="{{ $department }}">
                                        @foreach ($doctors as $doctor)
                                            <option value="{{ $doctor->id }}"
                                                {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}
                                                data-departement="{{ $department }}">
                                                {{ $doctor->employee->fullname }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row align-items-center">
                    <div class="col-xl-4 text-right">
                        <label class="form-label" for="poliklinik">Kelas / Kamar Rawat</label>
                    </div>
                    <div class="col-xl-8">
                        <div class="input-group bg-white shadow-inset-2">
                            <input id="kelas_rawat_input" readonly name="kamar_tujuan" type="text"
                                class="form-control border-right-0 bg-transparent pr-0" placeholder=""
                                value="{{ old('kamar_tujuan') }}">
                            <input type="hidden" id="bed_id_input" name="bed_id" value="{{ old('bed_id') }}">
                            <input type="hidden" id="kelas_rawat_id_input" name="kelas_rawat_id"
                                value="{{ old('kelas_rawat_id') }}">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fal fa-search" style="cursor: pointer" data-toggle="modal"
                                        data-target="#kelas-rawat-form"></i>
                                </span>
                            </div>
                        </div>
                        @error('poliklinik')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row align-items-center">
                    <div class="col-xl-4 text-right">
                        <label class="form-label" for="kartu_pasien">Kartu Pasien</label>
                    </div>
                    <div class="col-xl-8">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="patient_card" name="patient_card"
                                {{ old('patient_card') ? 'checked' : '' }}>
                            <label class="custom-control-label" for="patient_card">Ya</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row align-items-center">
                    <div class="col-xl-4 text-right">
                        <label class="form-label" for="prosedur_masuk">Prosedur Masuk</label>
                    </div>
                    <div class="col-xl-8">
                        <div class="custom-control custom-checkbox">
                            <div class="frame-wrap">
                                <div class="custom-control custom-radio custom-control-inline p-0">
                                    <input type="radio" class="custom-control-input" id="rawat-jalan"
                                        name="prosedur_masuk" value="rawat-jalan"
                                        {{ old('prosedur_masuk') == 'rawat-jalan' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="rawat-jalan">Rawat Jalan</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" id="igd"
                                        name="prosedur_masuk" value="igd"
                                        {{ old('prosedur_masuk') == 'igd' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="igd">IGD</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" id="vk"
                                        name="prosedur_masuk" value="vk"
                                        {{ old('prosedur_masuk') == 'vk' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="vk">VK</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" id="ok"
                                        name="prosedur_masuk" value="ok"
                                        {{ old('prosedur_masuk') == 'ok' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="ok">OK</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="form-group">
                <div class="row align-items-center">
                    <div class="col-xl-4 text-right">
                        <label class="form-label" for="registration_date">Paket</label>
                    </div>
                    <div class="col-xl-8">
                        <div class="form-group">
                            <select class="form-control w-100" id="paket" name="paket">
                                <option selected></option>
                                <option value="Paket Skin Care"
                                    {{ old('paket') == 'Paket Skin Care' ? 'selected' : '' }}>Paket Skin Care</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row align-items-center">
                    <div class="col-xl-4 text-right">
                        <label class="form-label" for="penjamin">Penjamin</label>
                    </div>
                    <div class="col-xl-8">
                        <div class="form-group">
                            <select class="select2 form-control w-100" id="penjamin" name="penjamin_id">
                                <option selected></option>
                                @foreach ($penjamins as $penjamin)
                                    <option value="{{ $penjamin->id }}"
                                        {{ old('penjamin_id') == $penjamin->id ? 'selected' : '' }}>
                                        {{ $penjamin->nama_perusahaan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row align-items-center">
                    <div class="col-xl-4 text-right">
                        <label class="form-label" for="type">Kelas Titipan</label>
                    </div>
                    <div class="col-xl-8">
                        <div class="form-group">
                            <select class="form-control w-100" id="type" name="titip_kelas_rawat">
                                <option></option>
                                @foreach ($kelasTitipan as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('titip_kelas_rawat') == $item->id ? 'selected' : '' }}>
                                        {{ $item->kelas }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="text-danger" style="font-size: 8pt;">
                                Secara tarif kamar tetap mengikuti tarif kelas yang diinginkan pasien yaitu: Kelas
                                Titipan dari
                            </i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="form-group">
                    <div class="row align-items-center">
                        <div class="col-xl-4 text-right">
                            <label class="form-label" for="rujukan">Rujukan</label>
                        </div>
                        <div class="col-xl-8">
                            <div class="custom-control custom-checkbox">
                                <div class="frame-wrap">
                                    <div class="custom-control custom-radio custom-control-inline p-0">
                                        <input type="radio" class="custom-control-input" id="inisiatif_pribadi"
                                            name="rujukan" value="inisiatif pribadi"
                                            {{ old('rujukan') == 'inisiatif pribadi' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="inisiatif_pribadi">Inisiatif
                                            Pribadi</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" id="dalam_rs"
                                            name="rujukan" value="dalam rs"
                                            {{ old('rujukan') == 'dalam rs' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="dalam_rs">Dalam RS</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" id="luar_rs"
                                            name="rujukan" value="luar rs"
                                            {{ old('rujukan') == 'luar rs' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="luar_rs">Luar RS</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" id="rujukan_bpjs"
                                            name="rujukan" value="rujukan bpjs"
                                            {{ old('rujukan') == 'rujukan bpjs' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="rujukan_bpjs">Rujukan BPJS</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-12 mt-4">
            <div class="form-group">
                <div class="row align-items-center">
                    <div class="col-xl-2 text-right">
                        <label class="form-label" for="diagnosa-awal">Diagnosa Awal</label>
                    </div>
                    <div class="col-xl-10">
                        <textarea class="form-control" id="diagnosa-awal" name="diagnosa_awal" rows="5">{{ old('diagnosa_awal') }}</textarea>
                        @error('diagnosa-awal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-12 mt-5">
            <div class="row">
                <div class="col-xl-6">
                    <a href="/patients/{{ $patient->id }}" class="btn btn-lg btn-default waves-effect waves-themed">
                        <span class="fal fa-arrow-left mr-1 text-primary"></span>
                        <span class="text-primary">Kembali</span>
                    </a>
                </div>
                <div class="col-xl-6 text-right">
                    <button type="submit" class="btn btn-lg btn-primary waves-effect waves-themed" id="simpan-btn"
                        onclick="disableButton(event)">
                        <span class="fal fa-save mr-1"></span>
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="modal fade" id="kelas-rawat-form" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 80vw" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white"><strong>Kelas Rawat</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fal fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="card m-auto border">
                            <div class="card-header py-2 bg-primary">
                                <div class="card-title text-white">Form Pencarian</div>
                            </div>
                            <div class="card-body">
                                <form id="form-cari-kelas">
                                    <div class="form-group">
                                        <label class="form-label" for="kelas_rawat_id">Kelas Rawat</label>
                                        <select class="form-control w-100" id="kelas_rawat_id" name="kelas_rawat_id">
                                            <option value=""></option>
                                            @foreach ($kelas_rawats as $kelas_rawat)
                                                <option value="{{ $kelas_rawat->id }}"
                                                    {{ old('kelas_rawat_id') == $kelas_rawat->id ? 'selected' : '' }}>
                                                    {{ $kelas_rawat->kelas }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <table id="bed-table" style="width: 100%;" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Ruangan</th>
                                    <th>T. Tidur</th>
                                    <th>Pasien</th>
                                    <th>Fungsi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
