@extends('inc.layout')
@section('title', 'Laporan Ok')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <!-- Search Panel -->
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Filter <span class="fw-300"><i>Laporan OK</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="filter-form">
                                <div class="row mb-3">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Periode Tanggal Awal</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" name="tanggal_awal"
                                                value="{{ request('tanggal_awal', \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d')) }}"
                                                required>
                                            <div class="input-group-append"><span class="input-group-text fs-sm"><i
                                                        class="fal fa-calendar"></i></span></div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Periode Tanggal Akhir</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" name="tanggal_akhir"
                                                value="{{ request('tanggal_akhir', \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d')) }}"
                                                required>
                                            <div class="input-group-append"><span class="input-group-text fs-sm"><i
                                                        class="fal fa-calendar"></i></span></div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">Kelas Rawat</label>
                                        <select class="form-control select2" id="kelas_rawat_id" name="kelas_rawat_id">
                                            <option value="">Semua Kelas</option>
                                            @foreach ($kelas_rawat_list as $kelas)
                                                <option value="{{ $kelas->id }}">{{ $kelas->kelas }}</option>
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
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/notifications/toastr/toastr.js"></script>
    <link rel="stylesheet" href="/css/notifications/toastr/toastr.css">
    <script>
        $(document).ready(function() {
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true
            });
            $('.select2').select2({
                dropdownCssClass: "move-up"
            });

            $('#filter-form').on('submit', function(e) {
                e.preventDefault();
                var tanggal_awal = $('input[name="tanggal_awal"]').val();
                var tanggal_akhir = $('input[name="tanggal_akhir"]').val();
                var kelas_rawat_id = $('#kelas_rawat_id').val();
                if (!tanggal_awal || !tanggal_akhir) {
                    toastr.error('Silakan pilih periode tanggal terlebih dahulu.', 'Error');
                    return;
                }
                var url = "{{ route('laporan-pendukung.ok.print') }}?";
                url += "tanggal_awal=" + encodeURIComponent(tanggal_awal);
                url += "&tanggal_akhir=" + encodeURIComponent(tanggal_akhir);
                if (kelas_rawat_id) {
                    url += "&kelas_rawat_id=" + encodeURIComponent(kelas_rawat_id);
                }
                window.open(url, '_blank', 'width=800,height=600,scrollbars=yes,resizable=yes');
            });

            $('#export-xls-btn').on('click', function(e) {
                e.preventDefault();
                var tanggal_awal = $('input[name="tanggal_awal"]').val();
                var tanggal_akhir = $('input[name="tanggal_akhir"]').val();
                var kelas_rawat_id = $('#kelas_rawat_id').val();

                if (!tanggal_awal || !tanggal_akhir) {
                    toastr.error('Silakan pilih periode tanggal terlebih dahulu.', 'Error');
                    return;
                }

                var url = "{{ route('laporan-pendukung.ok.export') }}?";
                url += "tanggal_awal=" + encodeURIComponent(tanggal_awal);
                url += "&tanggal_akhir=" + encodeURIComponent(tanggal_akhir);
                if (kelas_rawat_id) {
                    url += "&kelas_rawat_id=" + encodeURIComponent(kelas_rawat_id);
                }
                window.location.href = url;
            });
        });
    </script>
@endsection
