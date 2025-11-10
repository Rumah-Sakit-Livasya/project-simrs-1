@extends('inc.layout')
@section('title', 'Laporan Rekap IGD Per Dokter')

@section('extended-css')
    {{-- CSS untuk Select2 dan Daterangepicker --}}
    <link rel="stylesheet" media="screen, print" href="/css/formplugins/select2/select2.bundle.css">
    <link rel="stylesheet" media="screen, print"
        href="/css/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.css">
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel">
                    <div class="panel-hdr">
                        <h2>Laporan Rekap IGD Per Dokter</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            {{-- FORM FILTER --}}
                            <form method="GET" action="{{ route('igd.reports.rekap-per-dokter') }}">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label" for="periode">Periode Tgl. Registrasi</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="start_date"
                                                placeholder="Tanggal Awal"
                                                value="{{ request('start_date', now()->startOfMonth()->format('d-m-Y')) }}"
                                                required>
                                            <div class="input-group-append input-group-prepend">
                                                <span class="input-group-text">s/d</span>
                                            </div>
                                            <input type="text" class="form-control" name="end_date"
                                                placeholder="Tanggal Akhir"
                                                value="{{ request('end_date', now()->format('d-m-Y')) }}" required>
                                        </div>
                                        <span class="help-block">Pilih rentang tanggal untuk laporan.</span>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label" for="doctor_id">Dokter</label>
                                        <select class="form-control select2" id="doctor_id" name="doctor_id">
                                            <option value="">Semua Dokter</option>
                                            @foreach ($doctors as $doctor)
                                                <option value="{{ $doctor->id }}"
                                                    {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                                    {{ $doctor->employee->fullname }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label" for="penjamin_id">Penjamin</label>
                                        <select class="form-control select2" id="penjamin_id" name="penjamin_id">
                                            <option value="">Semua Penjamin</option>
                                            @foreach ($penjamins as $penjamin)
                                                <option value="{{ $penjamin->id }}"
                                                    {{ request('penjamin_id') == $penjamin->id ? 'selected' : '' }}>
                                                    {{ $penjamin->nama_perusahaan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fal fa-search mr-1"></i> Tampilkan Laporan
                                        </button>
                                    </div>
                                </div>
                            </form>

                            {{-- HASIL LAPORAN --}}
                            @if ($hasData)
                                <hr class="mt-4 mb-4">
                                <div class="p-3 mb-2 bg-faded">
                                    <h5>Hasil Laporan Periode: {{ request('start_date') }} s/d {{ request('end_date') }}
                                    </h5>
                                </div>
                                <table class="table table-bordered table-striped table-hover w-100" id="report-table">
                                    <thead class="bg-primary-600">
                                        <tr>
                                            <th class="text-center" style="width: 5%;">No</th>
                                            <th>Dokter</th>
                                            <th class="text-center" style="width: 15%;">Jml. Registrasi</th>
                                            {{-- <th class="text-center" style="width: 15%;">Jml. Resep</th>
                                            <th class="text-center" style="width: 15%;">Jml. Cancel Resep</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($results as $index => $result)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>{{ $result->doctor_name }}</td>
                                                <td class="text-center">{{ $result->total_registrasi }}</td>
                                                {{-- <td class="text-center">{{ $result->total_resep }}</td>
                                                <td class="text-center">{{ $result->total_resep_batal }}</td> --}}
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @elseif(request()->has('start_date'))
                                <hr class="mt-4 mb-4">
                                <div class="alert alert-info text-center">
                                    Tidak ada data yang ditemukan untuk periode dan filter yang dipilih.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi Select2
            $('.select2').select2({
                width: '100%'
            });

            // Inisialisasi Datatables
            $('#report-table').DataTable({
                responsive: true,
                "language": {
                    "url": "/js/datagrid/datatables/Indonesian.json"
                },
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                        extend: 'excelHtml5',
                        text: 'Excel',
                        title: 'Laporan Rekap IGD Per Dokter - {{ now()->format('d-m-Y') }}',
                        className: 'btn-outline-success btn-sm mr-1'
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        title: 'Laporan Rekap IGD Per Dokter',
                        className: 'btn-outline-primary btn-sm'
                    }
                ]
            });

            // Inisialisasi Datepicker
            $('input[name="start_date"], input[name="end_date"]').datepicker({
                format: "dd-mm-yyyy",
                autoclose: true,
                todayHighlight: true,
                orientation: "bottom left"
            });
        });
    </script>
@endsection
