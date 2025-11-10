@extends('inc.layout') {{-- Menggunakan layout utama Anda --}}
@section('title', 'Laporan Pasien Rawat Inap')

@push('styles')
    {{-- Anda bisa menambahkan style kustom di sini jika perlu --}}
@endpush

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <!-- Search Panel -->
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Laporan <span class="fw-300"><i>Pasien Rawat Inap</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            {{-- ID form disesuaikan --}}
                            <form id="laporan-ranap-form">
                                <div class="row">
                                    {{-- KOLOM KIRI --}}
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Periode Awal</label>
                                            <div class="input-group">
                                                <input type="text" name="periode_awal" class="form-control datepicker"
                                                    value="{{ now()->startOfMonth()->format('Y-m-d') }}">
                                                <div class="input-group-append">
                                                    <span class="input-group-text fs-sm"><i
                                                            class="fal fa-calendar"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label">Kelas</label>
                                            <select name="kelas_id" class="form-control select2">
                                                <option value="">-- Semua Kelas --</option>
                                                @foreach ($kelas_rawat as $kelas)
                                                    <option value="{{ $kelas->id }}">{{ $kelas->kelas }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label">Penjamin</label>
                                            <select name="penjamin_id" class="form-control select2">
                                                <option value="">-- Semua Penjamin --</option>
                                                @foreach ($penjamin as $p)
                                                    <option value="{{ $p->id }}">{{ $p->nama_perusahaan }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label">No. RM / Nama Pasien</label>
                                            <input type="text" name="no_rm_nama" class="form-control"
                                                placeholder="Masukkan No. RM atau Nama Pasien">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label">Alasan Keluar</label>
                                            <select name="alasan_keluar" class="form-control select2">
                                                <option value="">-- Semua Alasan --</option>
                                                @foreach ($alasan_keluar as $alasan)
                                                    <option value="{{ $alasan }}">{{ $alasan }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- KOLOM KANAN --}}
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Periode Akhir</label>
                                            <div class="input-group">
                                                <input type="text" name="periode_akhir" class="form-control datepicker"
                                                    value="{{ now()->endOfMonth()->format('Y-m-d') }}">
                                                <div class="input-group-append">
                                                    <span class="input-group-text fs-sm"><i
                                                            class="fal fa-calendar"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label">Dokter</label>
                                            <select name="dokter_id" class="form-control select2">
                                                <option value="">-- Semua Dokter --</option>
                                                @foreach ($dokter as $d)
                                                    <option value="{{ $d->id }}">
                                                        {{ $d->employee->fullname ?? 'N/A' }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label d-block">Rujukan</label>
                                            <div class="frame-wrap">
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" class="custom-control-input" id="rujukanSemua"
                                                        name="rujukan" value="semua" checked="">
                                                    <label class="custom-control-label" for="rujukanSemua">Semua
                                                        Rujukan</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" class="custom-control-input" id="rujukanDalam"
                                                        name="rujukan" value="dalam">
                                                    <label class="custom-control-label" for="rujukanDalam">Dalam RS</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" class="custom-control-input" id="rujukanLuar"
                                                        name="rujukan" value="luar">
                                                    <label class="custom-control-label" for="rujukanLuar">Luar RS</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label">ICD-10</label>
                                            <input type="text" name="icd10" class="form-control"
                                                placeholder="Masukkan Kode ICD-10">
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-end mt-3">
                                    <div class="col-auto">
                                        {{-- ID tombol disesuaikan --}}
                                        <button type="button" id="btn-cari" class="btn btn-primary">
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
    {{-- Sesuaikan path jika berbeda --}}
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                width: '100%',
            });

            // Initialize Datepicker
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd', // Format yang umum digunakan backend
                autoclose: true,
                todayHighlight: true
            });

            // Search button handler
            $('#btn-cari').on('click', function(e) {
                e.preventDefault();
                const form = $('#laporan-ranap-form');
                const formData = form.serialize();
                // Route disesuaikan dengan nama route report Anda
                const reportUrl = "{{ route('rawat-inap.laporan.report') }}?" + formData;


                const windowFeatures = "resizable=yes,scrollbars=yes,status=yes,width=1200,height=800";
                window.open(reportUrl, 'reportWindow', windowFeatures);
            });
        });
    </script>
@endsection
