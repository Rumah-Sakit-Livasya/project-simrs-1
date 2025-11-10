@extends('inc.layout')
@section('title', 'Laporan Penerimaan Kasir')

@push('styles')
    <link rel="stylesheet" media="screen, print" href="/css/formplugins/bootstrap-datepicker/bootstrap-datepicker.css">
    <style>
        table {
            font-size: 8pt !important;
        }

        .modal-lg {
            max-width: 800px;
        }

        table.dataTable thead .sorting:after,
        table.dataTable thead .sorting_asc:after,
        table.dataTable thead .sorting_desc:after,
        table.dataTable thead .sorting_asc_disabled:after,
        table.dataTable thead .sorting_desc_disabled:after {
            display: none !important;
        }

        #dt-basic-example tbody tr:hover {
            background-color: #f8f9fa;
        }

        .bg-primary-600 {
            background-color: #021d39 !important;
            color: white;
        }

        .btn-primary-600 {
            background-color: #021d39;
            border-color: #021d39;
            color: white;
        }

        .btn-primary-600:hover {
            background-color: #03306b;
            border-color: #03306b;
            color: white;
        }

        .btn-success-600 {
            background-color: #28a745;
            border-color: #28a745;
            color: white;
        }

        .btn-success-600:hover {
            background-color: #218838;
            border-color: #1e7e34;
            color: white;
        }
    </style>
@endpush

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <!-- Search Panel -->
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Laporan <span class="fw-300"><i>Penerimaan Kasir</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="laporan-kasir-form">
                                <div class="row mb-3">
                                    <!-- Periode Awal -->
                                    <div class="col-md-6 mb-3">
                                        <label>Periode Awal</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" id="periode_awal"
                                                name="periode_awal"
                                                value="{{ request('periode_awal', \Carbon\Carbon::now()->startOfDay()->format('Y-m-d')) }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text fs-sm"><i class="fal fa-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Periode Akhir -->
                                    <div class="col-md-6 mb-3">
                                        <label>Periode Akhir</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" id="periode_akhir"
                                                name="periode_akhir"
                                                value="{{ request('periode_akhir', \Carbon\Carbon::now()->endOfDay()->format('Y-m-d')) }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text fs-sm"><i class="fal fa-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Layanan -->
                                    <div class="col-md-6 mb-3">
                                        <label>Layanan</label>
                                        <select class="form-control select2" id="layanan" name="layanan">
                                            @foreach ($layanans as $layanan)
                                                <option value="{{ $layanan }}"
                                                    {{ request('layanan') == $layanan ? 'selected' : '' }}>
                                                    {{ $layanan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Poliklinik -->
                                    <div class="col-md-6 mb-3">
                                        <label>Poliklinik</label>
                                        <select class="form-control select2" id="poliklinik" name="poliklinik">
                                            <option value="">ALL</option>
                                            @foreach ($polikliniks as $poli)
                                                <option value="{{ $poli->id }}"
                                                    {{ request('poliklinik') == $poli->id ? 'selected' : '' }}>
                                                    {{ $poli->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Jenis Report -->
                                    <div class="col-md-6 mb-3">
                                        <label>Jenis Report</label>
                                        <select class="form-control select2" id="jenis_report" name="jenis_report">
                                            <option value="Detail"
                                                {{ request('jenis_report') == 'Detail' ? 'selected' : '' }}>Detail</option>
                                            <option value="Rekap"
                                                {{ request('jenis_report') == 'Rekap' ? 'selected' : '' }}>Rekap</option>
                                        </select>
                                    </div>

                                    <!-- Penjamin -->
                                    <div class="col-md-6 mb-3">
                                        <label>Penjamin</label>
                                        <select class="form-control select2" id="penjamin" name="penjamin">
                                            <option value="">ALL</option>
                                            @foreach ($penjamins as $penjamin)
                                                <option value="{{ $penjamin->id }}"
                                                    {{ request('penjamin') == $penjamin->id ? 'selected' : '' }}>
                                                    {{ $penjamin->nama_perusahaan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Petugas Kasir -->
                                    <div class="col-md-6 mb-3">
                                        <label>Petugas Kasir</label>
                                        <select class="form-control select2" id="petugas_kasir" name="petugas_kasir[]"
                                            multiple>
                                            @php
                                                $selectedKasirs = request('petugas_kasir', ['ALL']);
                                            @endphp
                                            <option value="ALL"
                                                {{ in_array('ALL', $selectedKasirs) ? 'selected' : '' }}>All</option>
                                            @foreach ($kasirs as $kasir)
                                                <option value="{{ $kasir->id }}"
                                                    {{ in_array($kasir->id, $selectedKasirs) ? 'selected' : '' }}>
                                                    {{ $kasir->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row justify-content-end mt-3">
                                    <div class="col-auto">
                                        <button type="button" id="btn-cari" class="btn bg-primary-600 mb-3">
                                            <span class="fal fa-search mr-1"></span> Cari
                                        </button>
                                        <button type="button" id="btn-xls" class="btn bg-success-600 mb-3">
                                            <span class="fal fa-file-excel mr-1"></span> Xls
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
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                width: '100%',
                dropdownCssClass: "move-up"
            });

            // Initialize Datepicker
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true
            });

            // Handle button clicks to open popup
            $('#btn-cari, #btn-xls').on('click', function(e) {
                e.preventDefault();

                const action = $(this).attr('id') === 'btn-cari' ? 'report' : 'export';
                const form = $('#laporan-kasir-form');
                const formData = form.serialize();

                // IMPORTANT: Make sure these routes exist in your web.php
                const reportUrlBase = "{{ route('laporan.penerimaan-kasir.report') }}";
                const exportUrlBase = "{{ route('laporan.penerimaan-kasir.export') }}";

                const finalUrl = (action === 'report' ? reportUrlBase : exportUrlBase) + '?' + formData;

                // Configure and open the popup window
                const popupWidth = 1200;
                const popupHeight = 800;
                const left = (screen.width - popupWidth) / 2;
                const top = (screen.height - popupHeight) / 2;
                const windowFeatures =
                    `width=${popupWidth},height=${popupHeight},left=${left},top=${top},resizable=yes,scrollbars=yes,status=yes`;

                window.open(finalUrl, 'reportWindow', windowFeatures);
            });

            // Optional: Enter key support for form submission
            $('#laporan-kasir-form').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    $('#btn-cari').click();
                }
            });
        });
    </script>
@endsection
