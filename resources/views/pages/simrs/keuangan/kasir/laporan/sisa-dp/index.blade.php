@extends('inc.layout')
@section('title', 'Laporan Sisa DP Pasien')

@push('styles')
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
    </style>
@endpush

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <!-- Search Panel -->
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Laporan <span class="fw-300"><i>Sisa DP Pasien</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="laporan-sisa-dp-form">
                                <div class="row mb-3">
                                    <!-- Sampai Tanggal -->
                                    <div class="col-md-6 mb-3">
                                        <label>Sampai Tanggal</label>
                                        <div class="input-group">
                                            <input type="text" id="sd_tanggal" name="sd_tanggal"
                                                class="form-control datepicker" value="{{ $sdTanggalInput }}">
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
                                                <option value="{{ $layanan }}">{{ $layanan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row justify-content-end mt-3">
                                    <div class="col-auto">
                                        <button type="button" id="btn-cari" class="btn bg-primary-600 mb-3">
                                            <span class="fal fa-search mr-1"></span> Cari
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

            // Search button handler
            $('#btn-cari').on('click', function(e) {
                e.preventDefault();
                const form = $('#laporan-sisa-dp-form');
                const formData = form.serialize();
                const reportUrl = "{{ route('laporan.sisa-dp.report') }}?" + formData;

                // Open in a new, full-screen capable window
                const windowFeatures = "resizable=yes,scrollbars=yes,status=yes,width=1200,height=800";
                window.open(reportUrl, 'reportWindow', windowFeatures);
            });

            // Optional: Enter key support for form submission
            $('#laporan-sisa-dp-form').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    $('#btn-cari').click();
                }
            });
        });
    </script>
@endsection
