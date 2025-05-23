@extends('inc.layout')
@section('title', 'Laporan Pembayaran Asuransi')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Laporan <span class="fw-300"><i>Pembayaran Asuransi</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="{{ route('laporan.l-pembayaran-asuransi.print') }}" method="get"
                                target="_blank">
                                @csrf
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="form-group row">
                                            <label class="col-xl-4 text-center col-form-label">Periode Awal</label>
                                            <div class="col-xl-8">
                                                <div class="input-group">
                                                    <input type="text" class="form-control datepicker"
                                                        name="tanggal_awal" placeholder="Pilih tanggal awal"
                                                        value="{{ request('tanggal_awal') ?? '' }}" autocomplete="off">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text fs-xl"><i
                                                                class="fal fa-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-xl-4 text-center col-form-label">Penjamin</label>
                                            <div class="col-xl-8">
                                                <select class="form-control select2 w-100" name="penjamin_id"
                                                    style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0">
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
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="form-group row">
                                            <label class="col-xl-4 text-center col-form-label">Periode Akhir</label>
                                            <div class="col-xl-8">
                                                <div class="input-group">
                                                    <input type="text" class="form-control datepicker"
                                                        name="tanggal_akhir" placeholder="Pilih tanggal akhir"
                                                        value="{{ request('tanggal_akhir') ?? '' }}" autocomplete="off">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text fs-xl"><i
                                                                class="fal fa-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
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
