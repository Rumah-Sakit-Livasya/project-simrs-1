@extends('inc.layout')
@section('title', 'Laporan Poliklinik')

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-lg-10 col-xl-8 mx-auto">
                <div class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Form <span class="fw-300"><i>Pencarian Laporan Poliklinik</i></span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form action="{{ route('laporan.poliklinik.show') }}" method="post" target="_blank">
                                @csrf
                                <input type="hidden" name="export" id="export" value="">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="form-label" for="stgl1">Awal Periode</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text fs-xl"><i class="fal fa-calendar"></i></span>
                                            </div>
                                            <input type="text" class="form-control" placeholder="Pilih Tanggal"
                                                id="stgl1" name="stgl1"
                                                value="{{ \Carbon\Carbon::now()->startOfMonth()->format('d-m-Y') }}"
                                                required>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label" for="stgl2">Akhir Periode</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text fs-xl"><i class="fal fa-calendar"></i></span>
                                            </div>
                                            <input type="text" class="form-control" placeholder="Pilih Tanggal"
                                                id="stgl2" name="stgl2"
                                                value="{{ \Carbon\Carbon::now()->endOfMonth()->format('d-m-Y') }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="form-label" for="did">Poliklinik</label>
                                        <select class="form-control select2" id="did" name="did">
                                            <option value="">Semua Poliklinik</option>
                                            @foreach ($poliklinik as $poli)
                                                <option value="{{ $poli->id }}">{{ $poli->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label" for="pid">Dokter</label>
                                        <select class="form-control select2" id="pid" name="pid">
                                            <option value="">Semua Dokter</option>
                                            @foreach ($dokter as $doc)
                                                <option value="{{ $doc->id }}">{{ $doc->fullname }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label class="form-label" for="insid">Penjamin</label>
                                        <select class="form-control select2" id="insid" name="insid">
                                            <option value="">Semua Penjamin</option>
                                            @foreach ($penjamin as $guarantor)
                                                <option value="{{ $guarantor->id }}">{{ $guarantor->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div
                                    class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 d-flex flex-row align-items-center">
                                    <button class="btn btn-primary ml-auto" type="submit"
                                        onclick="document.getElementById('export').value = ''">
                                        <i class="fal fa-search"></i>
                                        Tampilkan
                                    </button>
                                    <button class="btn btn-success ml-2" type="submit"
                                        onclick="document.getElementById('export').value = 'xls'">
                                        <i class="fal fa-file-excel"></i>
                                        Export Excel
                                    </button>
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
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            // Init select2
            $('.select2').select2({
                width: '100%'
            });

            // Init datepicker
            var controls = {
                leftArrow: '<i class="fal fa-angle-left" style="font-size: 1.25rem"></i>',
                rightArrow: '<i class="fal fa-angle-right" style="font-size: 1.25rem"></i>'
            }

            $('#stgl1').datepicker({
                todayHighlight: true,
                orientation: "bottom left",
                templates: controls,
                format: "dd-mm-yyyy"
            });

            $('#stgl2').datepicker({
                todayHighlight: true,
                orientation: "bottom left",
                templates: controls,
                format: "dd-mm-yyyy"
            });
        });
    </script>
@endsection
