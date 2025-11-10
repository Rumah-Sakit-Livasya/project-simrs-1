@extends('inc.layout')
@section('title', 'Laporan Biaya Lain-Lain')

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
    </style>
@endpush

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <!-- Search Panel -->
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Laporan <span class="fw-300"><i>Biaya Lain-Lain</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="laporan-biaya-lain-form">
                                <div class="row mb-3">
                                    <!-- Periode Awal -->
                                    <div class="col-md-6 mb-3">
                                        <label>Periode Awal</label>
                                        <div class="input-group">
                                            <input type="text" id="periode_awal" name="periode_awal"
                                                class="form-control datepicker" value="{{ $periodeAwalInput }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text fs-sm"><i class="fal fa-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Periode Akhir -->
                                    <div class="col-md-6 mb-3">
                                        <label>Periode Akhir</label>
                                        <div class="input-group">
                                            <input type="text" id="periode_akhir" name="periode_akhir"
                                                class="form-control datepicker" value="{{ $periodeAkhirInput }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text fs-sm"><i class="fal fa-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- No RM -->
                                    <div class="col-md-6 mb-3">
                                        <label>No RM</label>
                                        <input type="text" id="no_rm" name="no_rm" class="form-control"
                                            placeholder="Masukkan nomor RM">
                                    </div>

                                    <!-- Tipe Kunjungan -->
                                    <div class="col-md-6 mb-3">
                                        <label>Tipe Kunjungan</label>
                                        <select class="form-control select2" id="tipe_kunjungan" name="tipe_kunjungan">
                                            @foreach ($tipeKunjungan as $tipe)
                                                <option value="{{ $tipe }}">{{ $tipe }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Nama Pasien -->
                                    <div class="col-md-6 mb-3">
                                        <label>Nama Pasien</label>
                                        <input type="text" id="nama_pasien" name="nama_pasien" class="form-control"
                                            placeholder="Masukkan nama pasien">
                                    </div>

                                    <!-- Status Kunjungan -->
                                    <div class="col-md-6 mb-3">
                                        <label>Status Kunjungan</label>
                                        <select class="form-control select2" id="status_kunjungan" name="status_kunjungan">
                                            @foreach ($statusKunjungan as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- No Registrasi -->
                                    <div class="col-md-6 mb-3">
                                        <label>No Registrasi</label>
                                        <input type="text" id="no_registrasi" name="no_registrasi" class="form-control"
                                            placeholder="Masukkan nomor registrasi">
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
                const form = $('#laporan-biaya-lain-form');
                const formData = form.serialize();
                const reportUrl = "{{ route('laporan.biaya-lain-lain.report') }}?" + formData;

                // Open in a new, full-screen capable window
                const windowFeatures = "resizable=yes,scrollbars=yes,status=yes,width=1200,height=800";
                window.open(reportUrl, 'reportWindow', windowFeatures);
            });

            // Optional: Enter key support for form submission
            $('#laporan-biaya-lain-form').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    $('#btn-cari').click();
                }
            });
        });
    </script>
@endsection
