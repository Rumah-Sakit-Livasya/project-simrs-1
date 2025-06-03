@extends('inc.layout')
@section('title', 'Daftar Pasien IGD')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Laporan <span class="fw-300"><i>IGD</i></span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="" method="post" autocomplete="off">
                                @csrf
                                <div class="row justify-content-center">
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label for="registration_date">Awal Periode</label>
                                                </div>
                                                <div class="col-xl">
                                                    <div class="form-group row">
                                                        <div class="col-xl ">
                                                            <input type="text" class="form-control" id="datepicker-1"
                                                                style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                                placeholder="Select date" name="registration_date"
                                                                value="">
                                                        </div>
                                                    </div>
                                                    @error('registration_date')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label for="registration_date">Akhir Periode</label>
                                                </div>
                                                <div class="col-xl">
                                                    <div class="form-group row">
                                                        <div class="col-xl ">
                                                            <input type="text" class="form-control" id="datepicker-2"
                                                                style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                                placeholder="Select date" name="registration_date"
                                                                value="">
                                                        </div>
                                                    </div>
                                                    @error('registration_date')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center mt-4">
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label for="doctor_id">Dokter</label>
                                                </div>
                                                <div class="col-xl">
                                                    <select class="form-control w-100 select2" id="doctor_id"
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        name="doctor_id">
                                                        <option value=""></option>
                                                        @foreach ($doctors as $doctor)
                                                            <option value="{{ $doctor->id }}"
                                                                {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                                                {{ $doctor->employee->fullname }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('doctor_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label for="penjamin_id">Dokter</label>
                                                </div>
                                                <div class="col-xl">
                                                    <select class="form-control w-100 select2" id="penjamin_id"
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        name="penjamin_id">
                                                        <option value=""></option>
                                                        @foreach ($penjamin as $p)
                                                            <option value="{{ $p->id }}"
                                                                {{ old('penjamin_id') == $p->id ? 'selected' : '' }}>
                                                                {{ $p->nama_perusahaan }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('penjamin_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-end mt-3">
                                    <div class="col-xl-3">
                                        <button type="submit" class="btn btn-outline-primary waves-effect waves-themed">
                                            <span class="fal fa-search mr-1"></span>
                                            Cari
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('plugin')
    {{-- Select 2 --}}
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    {{-- Datepicker --}}
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script>
        $(document).ready(function() {
            $('#datepicker-1').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true
            });

            $('#datepicker-2').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true
            });

            $('.select2').select2({
                placeholder: "Pilih Status",
                allowClear: true
            });
        });
    </script>
@endsection
