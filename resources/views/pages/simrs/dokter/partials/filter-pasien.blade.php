<div id="js-slide-left"
    class="h-100 flex-wrap flex-shrink-0 position-relative slide-on-mobile slide-on-mobile-left bg-primary-200 pattern-0 pt-3">
    <div class="position-fixed h-100" style="width: 20rem !important">
        <row class="justify-content-center">
            <div class="col">
                <form action="javascript:void(0)" method="POST" id="filter_pasien">
                    @csrf
                    <div class="form-group mb-2">
                        <select
                            class="select2 form-control @error('registration_type') is-invalid @enderror filter-pasien"
                            name="registration_type" id="registration_type">
                            <option value=""></option>
                            @foreach ($tipeRegis as $tipe)
                                <option value="{{ $tipe['name'] }}">
                                    {{ $tipe['value'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('registration_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-2">
                        <select class="select2 form-control @error('doctor_id') is-invalid @enderror filter-pasien"
                            name="doctor_id" id="doctor_id">
                            <option value=""></option>

                            {{-- Bagian ini sekarang akan berfungsi --}}
                            @foreach ($doctors as $doctor)
                                <option value="{{ $doctor->id }}" @selected(true)>
                                    {{ $doctor->employee->user->name }}
                                </option>
                            @endforeach

                        </select>
                        @error('doctor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-2">
                        <input type="text" id="nama_pasien" name="nama_pasien" class="form-control filter-pasien"
                            placeholder="Nama Pasien">
                    </div>
                    <div class="form-group mb-2">
                        <input type="text" id="medical_record_number" name="medical_record_number"
                            class="form-control filter-pasien" placeholder="No RM">
                    </div>
                    <div class="form-group">
                        {{-- <button type="submit" class="btn btn-primary w-100">Submit</button> --}}
                    </div>
                </form>

                {{-- DAFTAR PASIEN --}}
                @include('pages.simrs.dokter.partials.list-pasien')
            </div>
        </row>
    </div>
</div>
