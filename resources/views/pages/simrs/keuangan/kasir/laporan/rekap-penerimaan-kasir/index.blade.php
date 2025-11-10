@extends('inc.layout')
@section('title', 'Rekap Penerimaan Kasir')

@section('content')
    <style>
        /* Styles can remain the same */

        .badge-info {
            font-size: 0.8rem;
            padding: 0.35rem 0.65rem;
        }

        .form-label {
            font-weight: 500;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border-color: #ced4da;
        }

        .btn i {
            margin-right: 5px;
        }
    </style>

    <main id="js-page-content" role="main" class="page-content">
        <!-- Search Panel -->
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Form Pencarian <span class="fw-300"><i>Rekap Penerimaan Kasir</i></span>
                        </h2>
                        <div class="panel-toolbar">
                            @if (request('periode_awal') || request('periode_akhir'))
                                <span class="badge bg-primary-600 badge-info p-2">
                                    Filter Aktif:
                                    @if (request('periode_awal') && request('periode_akhir'))
                                        {{ \Carbon\Carbon::parse(request('periode_awal'))->translatedFormat('d M Y') }}
                                        s/d
                                        {{ \Carbon\Carbon::parse(request('periode_akhir'))->translatedFormat('d M Y') }}
                                    @endif
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="rekap-kasir-form">
                                @csrf
                                <div class="row">
                                    <!-- Periode Awal -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="periode_awal">Periode Awal</label>
                                        <div class="input-group">
                                            {{-- Changed class from datetimepicker to datepicker --}}
                                            <input type="text" class="form-control datepicker" id="periode_awal"
                                                name="periode_awal"
                                                value="{{ \Carbon\Carbon::parse($periodeAwalInput)->format('d-m-Y') }}"
                                                placeholder="dd-mm-yyyy" autocomplete="off">
                                            <div class="input-group-append">
                                                <span class="input-group-text fs-sm">
                                                    <i class="fal fa-calendar-alt"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Periode Akhir -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="periode_akhir">Periode Akhir</label>
                                        <div class="input-group">
                                            {{-- Changed class from datetimepicker to datepicker --}}
                                            <input type="text" class="form-control datepicker" id="periode_akhir"
                                                name="periode_akhir"
                                                value="{{ \Carbon\Carbon::parse($periodeAkhirInput)->format('d-m-Y') }}"
                                                placeholder="dd-mm-yyyy" autocomplete="off">
                                            <div class="input-group-append">
                                                <span class="input-group-text fs-sm">
                                                    <i class="fal fa-calendar-alt"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tombol Aksi -->
                                <div class="row justify-content-end mt-3">
                                    <div class="col-auto">
                                        <button type="button" id="btn-cari" class="btn bg-primary-600">
                                            <i class="fal fa-search"></i> Cari
                                        </button>
                                        <button type="button" id="btn-xls" class="btn bg-success-600 ms-2">
                                            <i class="fal fa-file-excel"></i> Export XLS
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
    <!-- Required Plugins -->
    {{-- Removed daterangepicker and select2 as they are not used on this form --}}
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>

    <script>
        $(document).ready(function() {
            // FIX #1: Initialize with .datepicker() for the date-only plugin
            $('.datepicker').datepicker({
                // FIX #2: Use the correct format without time
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
                orientation: "bottom left" // Good for positioning
            });

            // This part of the logic remains the same, but now handles date-only strings
            $('#btn-cari, #btn-xls').on('click', function(e) {
                e.preventDefault();

                const awal = $('#periode_awal').val();
                const akhir = $('#periode_akhir').val();

                if (!awal || !akhir) {
                    alert('Mohon isi Periode Awal dan Periode Akhir.');
                    return;
                }

                // FIX #3: Convert format from dd-mm-yyyy to YYYY-MM-DD for the controller
                const formatDate = (dateStr) => {
                    return moment(dateStr, 'DD-MM-YYYY').format('YYYY-MM-DD');
                };

                const awalFormatted = formatDate(awal);
                const akhirFormatted = formatDate(akhir);

                if (moment(awalFormatted).isAfter(moment(akhirFormatted))) {
                    alert('Periode Awal tidak boleh lebih besar dari Periode Akhir.');
                    return;
                }

                // Construct URL with YYYY-MM-DD format
                const baseUrl = "{{ route('laporan.rekap-penerimaan-kasir.report') }}";
                const finalUrl = `${baseUrl}?periode_awal=${awalFormatted}&periode_akhir=${akhirFormatted}`;

                // Open popup window (no change here)
                const popupWidth = 1024;
                const popupHeight = 768;
                const left = (screen.width - popupWidth) / 2;
                const top = (screen.height - popupHeight) / 2;
                const windowFeatures =
                    `width=${popupWidth},height=${popupHeight},left=${left},top=${top},resizable=yes,scrollbars=yes`;

                window.open(finalUrl, 'reportWindow', windowFeatures);
            });
        });
    </script>
@endsection
