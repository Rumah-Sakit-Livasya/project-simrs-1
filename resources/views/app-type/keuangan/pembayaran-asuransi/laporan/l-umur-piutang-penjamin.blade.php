@extends('inc.layout')
@section('title', 'Laporan Umur Piutang Penjamin')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <!-- Search Panel -->
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Laporan <span class="fw-300"><i>Umur Piutang Penjamin</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="{{ route('laporan.l-umur-piutang-penjamin.print') }}" method="get"
                                target="_blank">
                                <div class="row">
                                    <!-- Date Range -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label class="form-label">Tgl A/R Payment</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control datepicker" name="tanggal_awal"
                                                    autocomplete="off">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fal fa-calendar"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label class="form-label">Sampai</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control datepicker" name="tanggal_akhir"
                                                    autocomplete="off">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fal fa-calendar"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Insurance Provider -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label class="form-label">Penjamin</label>
                                            <select class="form-control select2" name="penjamin_id">
                                                <option value="">Semua</option>
                                                @foreach ($penjamins as $penjamin)
                                                    <option value="{{ $penjamin->id }}">{{ $penjamin->nama_perusahaan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-end mt-3">
                                    <div class="col-auto">
                                        <button type="submit" class="btn bg-primary-600 mb-3">
                                            <span class="fal fa-print mr-1"></span> Cari & Cetak
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
            $('.select2').select2({
                placeholder: "Pilih Penjamin",
                allowClear: true,
                width: 'resolve'
            });
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                todayHighlight: true,
                autoclose: true,
                orientation: "bottom auto"
            });
        });

        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Pilih Penjamin",
                allowClear: true,
                width: 'resolve'
            });

            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                todayHighlight: true,
                autoclose: true,
                orientation: "bottom auto"
            });

            // Validasi periode wajib diisi
            $('form').on('submit', function(e) {
                const start = $('input[name="tanggal_awal"]').val();
                const end = $('input[name="tanggal_akhir"]').val();

                if (!start || !end) {
                    e.preventDefault();
                    toastr.error('Periode awal dan akhir wajib diisi!');
                }
            });
        });
    </script>
@endsection
