@extends('inc.layout')
@section('title', 'Rekap Laporan Piutang Penjamin')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Rekap <span class="fw-300"><i>Laporan Piutang Penjamin</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="{{ route('laporan.l-rekap-piutang-penjamin.print') }}" method="get"
                                target="_blank">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-xl-3 text-center col-form-label">Pilih Tahun</label>
                                    <div class="col-xl-6">
                                        <select name="tahun" class="form-control select2">
                                            @for ($i = date('Y'); $i >= 2020; $i--)
                                                <option value="{{ $i }}"
                                                    {{ request('tahun') == $i ? 'selected' : '' }}>
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-xl-3 text-center col-form-label">Penjamin</label>
                                    <div class="col-xl-6">
                                        <select name="penjamin_id" class="form-control select2">
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
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Pilih",
                allowClear: true,
                width: 'resolve'
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
