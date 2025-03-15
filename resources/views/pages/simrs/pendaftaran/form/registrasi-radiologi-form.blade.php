<form action="{{ route('simpan.registrasi') }}" method="post">
    @csrf
    <input type="hidden" name="patient_id" value="{{ $patient->id }}">
    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
    <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}">
    <input type="hidden" name="registration_type" value="radiologi">
    {{-- POLIKLINIK RADIOLOGI APA? --}}
    {{-- POLIKLINIK RADIOLOGI APA? --}}
    {{-- POLIKLINIK RADIOLOGI APA? --}}
    {{-- POLIKLINIK RADIOLOGI APA? --}}
    {{-- POLIKLINIK RADIOLOGI APA? --}}
    <input type="hidden" name="poliklinik" value="rajal">
    <div class="row">
        <div class="col-xl-6">
            <div class="form-group">
                <div class="row align-items-center">
                    <div class="col-xl-4 text-right">
                        <label class="form-label" for="registration_date">
                            Tanggal Registrasi
                        </label>
                    </div>
                    <div class="col-xl-8">
                        <input type="text"
                            style="border: 0; border-bottom: 1.9px dashed #aaa; margin-top: -.5rem; border-radius: 0"
                            class="form-control" id="registration_date" readonly value="{{ $today }}"
                            name="registration_date">
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
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">
                                        {{ $doctor->employee->fullname }}
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
                        <label class="form-label" for="doctor_id">Tipe Order</label>
                    </div>
                    <div class="col-xl-8">
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="order_type" id="order_type_normal"
                                    value="normal" checked>
                                <label class="form-check-label" for="order_type_normal">
                                    Normal
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="order_type" id="order_type_cito"
                                    value="cito">
                                <label class="form-check-label" for="order_type_cito">
                                    CITO (naik 30%)
                                </label>
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
                        <label class="form-label" for="registration_date">
                            Dokter Perujuk
                        </label>
                    </div>
                    <div class="col-xl-8">
                        <div class="form-group">
                            <select class="select2 form-control w-100" id="doctor_id" name="doctor_id">
                                <option value=""></option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">
                                        {{ $doctor->employee->fullname }}
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
                        <label class="form-label" for="diagnosa_awal">
                            Diagnosa*
                        </label>
                    </div>
                    <div class="col-xl-8">
                        <textarea class="form-control" id="diagnosa_awal" name="diagnosa_awal" rows="5"></textarea>
                        @error('diagnosa_awal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4">
            <span class="form-group">
                @foreach ($radiology_categories as $category)
                    <span class="row align-items-center">
                        <span class="col-12 bg-info">
                            <h4 style="color: white">{{ $category->nama_kategori }}</h4>
                        </span>
                    </span>

                    @foreach ($category->parameter_radiologi as $parameter)
                        <span class="row align-items-center">
                            <span class="col-12">
                                <span class="row">
                                    <span class="col-6">
                                        <span class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                value="{{ $parameter->id }}" id="parameter_radiologi">
                                            <label class="form-check-label" for="parameter_radiologi">
                                                <p>{{ $parameter->parameter }}</p>
                                            </label>
                                        </span>
                                    </span>
                                    <span class="col-6" style="text-align: end">
                                        <input type="number" name="jumlah_{{ $parameter->id }}" value="1"
                                            class="form-control" id="jumlah_{{ $parameter->id }}">
                                    </span>
                                </span>
                            </span>
                        </span>
                    @endforeach
                @endforeach
            </span>
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
                    <button type="submit" class="btn btn-lg btn-primary waves-effect waves-themed">
                        <span class="fal fa-save mr-1"></span>
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

@section('plugin')
    <script>
        window._parameterRadiologi = @json($category->parameter_radiologi);
        window._tarifRadiologi = @json($tarifs);
    </script>
    <script src="/js/simrs/form-radiologi.js"></script>
@endsection
