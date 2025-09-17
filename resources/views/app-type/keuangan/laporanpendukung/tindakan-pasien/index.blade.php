@extends('inc.layout') {{-- Pastikan ini adalah layout tema lama Anda --}}
@section('title', 'Laporan Tindakan Medis')

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Filter <span class="fw-300"><i>Laporan Tindakan Medis</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="filter-form">
                                <div class="row mb-3">
                                    {{-- Kolom Tanggal Awal & Akhir --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Periode Tanggal Awal</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" name="tanggal_awal"
                                                value="{{ \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') }}"
                                                required>
                                            <div class="input-group-append"><span class="input-group-text fs-sm"><i
                                                        class="fal fa-calendar"></i></span></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Periode Tanggal Akhir</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" name="tanggal_akhir"
                                                value="{{ \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d') }}" required>
                                            <div class="input-group-append"><span class="input-group-text fs-sm"><i
                                                        class="fal fa-calendar"></i></span></div>
                                        </div>
                                    </div>

                                    {{-- Kolom Tipe Rawat & Dokter --}}
                                    <div class="col-md-6">
                                        <label class="form-label">Tipe Rawat</label>
                                        <select class="form-control select2" id="tipe_rawat" name="tipe_rawat">
                                            <option value="">Semua</option>
                                            @foreach ($tipeRawatList as $tipe)
                                                <option value="{{ $tipe }}">{{ $tipe }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Dokter</label>
                                        <select class="form-control select2" id="dokter_id" name="dokter_id">
                                            <option value="">Semua Dokter</option>
                                            @foreach ($doctors as $doctor)
                                                <option value="{{ $doctor->id }}">
                                                    {{ $doctor->employee->fullname ?? 'N/A' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row justify-content-end mt-3">
                                    <div class="col-auto">
                                        <button type="submit" class="btn bg-primary-600">
                                            <span class="fal fa-print mr-1"></span> Tampilkan & Cetak
                                        </button>
                                        <a href="#" class="btn bg-success-600" id="export-xls-btn">
                                            <span class="fal fa-file-excel mr-1"></span>XLS
                                        </a>
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
    {{-- PASTIKAN FILE JS INI BENAR-BENAR ADA DI LAYOUT ANDA --}}
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    {{-- <script src="/js/notifications/toastr/toastr.js"></script> --}}
    {{-- <link rel="stylesheet" href="/css/notifications/toastr/toastr.css"> --}}

    <script>
        $(document).ready(function() {
            // Inisialisasi Datepicker
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true
            });

            // Inisialisasi Select2
            $('.select2').select2({
                dropdownCssClass: "move-up" // Atribut ini spesifik untuk tema SmartAdmin
            });

            // Fungsi untuk membuat URL dengan parameter filter
            function buildUrl(baseUrl) {
                const tanggal_awal = $('input[name="tanggal_awal"]').val();
                const tanggal_akhir = $('input[name="tanggal_akhir"]').val();
                if (!tanggal_awal || !tanggal_akhir) {
                    alert('Silakan pilih periode tanggal terlebih dahulu.');
                    return null;
                }
                let url = `${baseUrl}?tanggal_awal=${tanggal_awal}&tanggal_akhir=${tanggal_akhir}`;

                // Tambahkan parameter lain jika nilainya ada
                if ($('#tipe_rawat').val()) {
                    url += `&tipe_rawat=${$('#tipe_rawat').val()}`;
                }
                if ($('#dokter_id').val()) {
                    url += `&dokter_id=${$('#dokter_id').val()}`;
                }
                return url;
            }

            // Handler untuk tombol "Tampilkan & Cetak"
            $('#filter-form').on('submit', function(e) {
                e.preventDefault();
                const url = buildUrl("{{ route('laporan-pendukung.tindakan-pasien.print') }}");
                if (url) {
                    window.open(url, '_blank');
                }
            });

            // Handler untuk tombol "XLS"
            $('#export-xls-btn').on('click', function(e) {
                e.preventDefault();
                const url = buildUrl("{{ route('laporan-pendukung.tindakan-pasien.export') }}");
                if (url) {
                    window.location.href = url;
                }
            });
        });
    </script>
@endsection
