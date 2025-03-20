<div class="panel-hdr border-top">
    <h2 class="text-light">
        <i class="fas fa-address-card mr-3 ml-2 text-primary" style="transform: scale(2.1)"></i>
        <span class="text-primary">Order Radiologi</span>
    </h2>
</div>
<form id="form-radiologi">
    @csrf
    <input type="hidden" name="patient_id" value="{{ $patient->id }}">
    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
    <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}">
    <input type="hidden" name="registration_type" value="{{ $registration->registration_type }}">
    <input type="hidden" name="poliklinik" value="{{ $registration->poliklinik }}">
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
                        <label class="form-label" for="doctor_id">Dokter Radiologi</label>
                    </div>
                    <div class="col-xl-8">
                        <div class="form-group">
                            <select class="select2 form-control w-100" id="doctor_id" name="doctor_id">
                                <option value=""></option>
                                @foreach ($groupedDoctors as $groupName => $group)
                                    <option class="text-light bg-info" disabled>{{ $groupName }}</option>
                                    @foreach ($group as $doctor)
                                        <option value="{{ $doctor->id }}">
                                            {{ $doctor->employee->fullname }}
                                        </option>
                                    @endforeach
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
                            <select disabled class="select2 form-control w-100">
                                <option value="">{{ $registration->doctor->employee->fullname }}</option>
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
        <div class="col-xl-6">
            <h3>Tindakan</h3>
        </div>

        <div class="col-xl-6">
            <h3 class="text-success" style="text-align: right"> <i class="fa fa-calculator"></i> <span
                    id="radiologi-total">Rp 0</span>
            </h3>
        </div>
        <div class="col-xl-2"></div>
        <div class="col-xl-8">
            <div class="accordion" id="accordionExample">
                <div class="accordion-item">
                    <h2 class="accordion-header" style="text-align: center">
                        <button class="accordion-button collapsed btn btn-primary" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                            Tampilkan Tindakan
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse"
                        data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="form-group">
                                <input type="text" class="form-control mb-3" id="searchRadiology"
                                    placeholder="Cari tindakan...">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Tindakan</th>
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody id="radiologyTable">
                                        @foreach ($radiology_categories as $category)
                                            <tr class="table-info">
                                                <td colspan="2">
                                                    <h4 style="text-align: center">{{ $category->nama_kategori }}</h4>
                                                </td>
                                            </tr>
                                            @foreach ($category->parameter_radiologi as $parameter)
                                                <tr class="parameter_radiologi">
                                                    <td>
                                                        <div class="form-check">
                                                            <input
                                                                class="form-check-input parameter_radiologi_checkbox"
                                                                type="checkbox" value="{{ $parameter->id }}"
                                                                id="parameter_radiologi_{{ $parameter->id }}">
                                                            <label class="form-check-label"
                                                                for="parameter_radiologi_{{ $parameter->id }}">
                                                                {{ $parameter->parameter }}
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="number" value="1"
                                                            class="form-control parameter_radiologi_number"
                                                            id="jumlah_{{ $parameter->id }}">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2"></div>
        <div class="col-xl-12 mt-5">
            <div class="row">
                <div class="col-xl-6">
                    <a href="/patients/{{ $patient->id }}" class="btn btn-lg btn-default waves-effect waves-themed">
                        <span class="fal fa-arrow-left mr-1 text-primary"></span>
                        <span class="text-primary">Kembali</span>
                    </a>
                </div>
                <div class="col-xl-6 text-right">
                    <button type="submit" id="radiologi-submit"
                        class="btn btn-lg btn-primary waves-effect waves-themed">
                        <span class="fal fa-save mr-1"></span>
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

@section('script-radiologi')
    <script>
        window._parameterRadiologi = @json($radiology_categories);
        window._tarifRadiologi = @json($tarifs);
        window._registration = @json($registration);
        window._groupPenjaminId = @json($groupPenjaminId);
    </script>
    <script src="{{ asset('js/simrs/form-radiologi.js') }}?v={{ time() }}"></script>
@endsection
