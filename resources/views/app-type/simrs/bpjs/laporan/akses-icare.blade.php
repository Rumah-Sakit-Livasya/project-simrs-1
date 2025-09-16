@extends('inc.layout')
@section('title', 'Laporan Jumlah Akses ICare')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Laporan Jumlah Akses ICare
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="laporan-form">
                                @csrf
                                <div class="row align-items-end">
                                    <div class="col-md-6">
                                        <label class="form-label" for="awal_periode">Awal Periode</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control monthpicker" id="awal_periode"
                                                name="awal_periode" value="{{ date('m-Y') }}" readonly>
                                            <div class="input-group-append"><span class="input-group-text fs-sm"><i
                                                        class="fal fa-calendar-alt"></i></span></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="akhir_periode">Akhir Periode</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control monthpicker" id="akhir_periode"
                                                name="akhir_periode" value="{{ date('m-Y') }}" readonly>
                                            <div class="input-group-append"><span class="input-group-text fs-sm"><i
                                                        class="fal fa-calendar-alt"></i></span></div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row justify-content-end mt-3">
                                    <div class="col-auto">
                                        <button type="button" id="btn-excel" class="btn btn-success flex-grow-1 mr-2">
                                            <i class="fal fa-file-excel mr-1"></i> Excel
                                        </button>
                                        <button type="submit" class="btn btn-primary flex-grow-1">
                                            <i class="fal fa-display mr-1"></i> Tampil
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
    {{-- Include plugin yang diperlukan --}}
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/notifications/toastr/toastr.js"></script>
    <link rel="stylesheet" href="/css/notifications/toastr/toastr.css">

    <script>
        $(document).ready(function() {
            // 1. Inisialisasi plugin datepicker untuk memilih bulan
            $('.monthpicker').datepicker({
                format: "mm-yyyy",
                startView: "months",
                minViewMode: "months",
                autoclose: true,
                todayHighlight: true
            });

            // 2. Handle form submission untuk membuka jendela baru
            $('#laporan-form').on('submit', function(e) {
                e.preventDefault();
                openReport(false); // false berarti bukan export excel
            });

            $('#btn-excel').on('click', function() {
                openReport(true); // true berarti export excel
            });

            function openReport(isExport) {
                var awalPeriode = $('#awal_periode').val();
                var akhirPeriode = $('#akhir_periode').val();

                if (!awalPeriode || !akhirPeriode) {
                    toastr.error('Silakan isi Awal dan Akhir Periode.', 'Error');
                    return;
                }

                var formData = $('#laporan-form').serialize();
                if (isExport) {
                    formData += '&export=xls';
                }

                // Ganti dengan route print Anda
                var reportUrl = "{{ route('laporan.print.akses-icare') }}?" + formData;

                if (isExport) {
                    // Untuk export, langsung redirect di tab yang sama
                    window.location.href = reportUrl;
                    toastr.info('Mempersiapkan file Excel...', 'Info');
                } else {
                    // Untuk tampilkan, buka di jendela baru
                    window.open(reportUrl, '_blank', 'width=1200,height=800,scrollbars=yes,resizable=yes');
                    toastr.info('Laporan sedang dibuka di jendela baru...', 'Info');
                }
            }
        });
    </script>
@endsection
