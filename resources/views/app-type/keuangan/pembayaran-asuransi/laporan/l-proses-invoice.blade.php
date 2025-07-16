@extends('inc.layout')
@section('title', 'Laporan Proses Invoice')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <!-- Search Panel -->
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Laporan <span class="fw-300"><i>Proses Invoice</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="{{ route('laporan.l-proses-invoice.print') }}" method="get" target="_blank">
                                @csrf
                                <div class="row">
                                    <!-- Left Column -->
                                    <div class="col-md-6">
                                        <!-- Periode Awal -->
                                        <div class="form-group mb-3">
                                            <label class="form-label">Periode Awal</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control datepicker" name="tanggal_awal"
                                                    placeholder="Pilih tanggal awal"
                                                    value="{{ request('tanggal_awal') ?? '' }}" autocomplete="off">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fal fa-calendar"></i></span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Penjamin -->
                                        <div class="form-group mb-3">
                                            <label class="form-label">Penjamin</label>
                                            <select class="form-control select2" name="penjamin_id">
                                                <option value="">Semua</option>
                                                @foreach ($penjamins as $penjamin)
                                                    <option value="{{ $penjamin->id }}"
                                                        {{ request('penjamin_id') == $penjamin->id ? 'selected' : '' }}>
                                                        {{ $penjamin->nama_perusahaan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Right Column -->
                                    <div class="col-md-6">
                                        <!-- Periode Akhir -->
                                        <div class="form-group mb-3">
                                            <label class="form-label">Periode Akhir</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control datepicker" name="tanggal_akhir"
                                                    placeholder="Pilih tanggal akhir"
                                                    value="{{ request('tanggal_akhir') ?? '' }}" autocomplete="off">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fal fa-calendar"></i></span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Tipe Pengunjung -->
                                        <div class="form-group mb-3">
                                            <label class="form-label">Tipe Pengunjung</label>
                                            <select class="form-control select2" name="tipe_kunjungan">
                                                <option value="">Semua</option>
                                                @foreach ($tipe_kunjungan_list as $tipe)
                                                    <option value="{{ $tipe->registration_type }}">
                                                        {{ ucfirst($tipe->registration_type) }}
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
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>
    <script src="/js/formplugins/inputmask/inputmask.bundle.js"></script>
    <script src="/js/formplugins/sweetalert2/sweetalert2.bundle.js"></script>
    <script src="/js/notifications/toastr/toastr.js"></script>
    <link rel="stylesheet" href="/css/notifications/toastr/toastr.css">
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Pilih",
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
