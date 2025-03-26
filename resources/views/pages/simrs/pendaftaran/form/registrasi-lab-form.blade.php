<form id="form-laboratorium">
    @method('post')
    @csrf
    <input type="hidden" name="patient_id" value="{{ $patient->id }}">
    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
    <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}">
    <input type="hidden" name="registration_type" value="laboratorium">
    <div class="row">
        <div class="col-xl-5">
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
        </div>
        <div class="col-xl-7">
            <div class="form-group">
                <div class="row align-items-center">
                    <div class="col-xl-4 text-right">
                        <label class="form-label" for="">
                            Rujukan
                        </label>
                    </div>
                    <div class="col-xl-8">
                        <div class="custom-control custom-checkbox">
                            <div class="frame-wrap">
                                <div class="custom-control custom-radio custom-control-inline p-0">
                                    <input type="radio" class="custom-control-input" id="inisiatif_pribadi"
                                        name="rujukan" value="inisiatif pribadi">
                                    <label class="custom-control-label" for="inisiatif_pribadi">Inisiatif
                                        Pribadi</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" id="dalam_rs" name="rujukan"
                                        value="dalam rs">
                                    <label class="custom-control-label" for="dalam_rs">Dalam RS</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" id="luar_rs" name="rujukan"
                                        value="luar rs">
                                    <label class="custom-control-label" for="luar_rs">Luar RS</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row align-items-center">
                    <div class="col-xl-4 text-right">
                        <label class="form-label" for="">
                            Tipe Order
                        </label>
                    </div>
                    <div class="col-xl-8">
                        <div class="custom-control custom-checkbox">
                            <div class="frame-wrap">
                                <div class="custom-control custom-radio custom-control-inline p-0">
                                    <input type="radio" class="custom-control-input" id="normal" name="order_type"
                                        value="normal">
                                    <label class="custom-control-label" for="normal">Normal</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" id="cito" name="order_type"
                                        value="cito">
                                    <label class="custom-control-label" for="cito">CITO</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
        </div>
        <div class="col-xl-6">
            <h3>Parameter Laboratorium</h3>
        </div>
        <div class="col-xl-6">
            <h3 class="text-success" style="text-align: right"> <i class="fa fa-calculator"></i> <span
                    id="laboratorium-total">Rp 0</span>
            </h3>
        </div>
        <div class="col-xl-2"></div>
        <div class="col-xl-8">
            <div class="form-group">
                <input type="text" class="form-control mb-3" id="searchLaboratorium"
                    placeholder="Cari parameter...">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody id="laboratoriumTable">
                        @foreach ($laboratorium_categories as $category)
                            <tr class="table-info">
                                <td colspan="2">
                                    <h4 style="text-align: center">{{ $category->nama_kategori }}</h4>
                                </td>
                            </tr>
                            @foreach ($category->parameter_laboratorium as $parameter)
                                @if ($parameter->is_order)
                                    <tr class="parameter_laboratorium">
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input parameter_laboratorium_checkbox"
                                                    type="checkbox" value="{{ $parameter->id }}"
                                                    id="parameter_laboratorium_{{ $parameter->id }}">
                                                <label class="form-check-label"
                                                    for="parameter_laboratorium_{{ $parameter->id }}">
                                                    {{ $parameter->parameter }}
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="number" value="1"
                                                class="form-control parameter_laboratorium_number"
                                                id="jumlah_{{ $parameter->id }}">
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
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
                    <button type="submit" class="btn btn-lg btn-primary waves-effect waves-themed">
                        <span class="fal fa-save mr-1"></span>
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>


<script>
    window._parameterLaboratorium = @json($laboratorium_categories);
    window._tarifLaboratorium = @json($tarifs);
    window._groupPenjaminId = @json($groupPenjaminId);
    window._kelasRawatId = @json($kelasRawatId);
</script>
<script src="{{ asset('js/simrs/form-laboratorium.js') }}?v={{ time() }}"></script>
