@extends('inc.layout')
@section('title', 'Laporan Rekap Kunjungan OK Berdasarkan Tindakan')
@section('content')
    <style>
        /* CSS Anda tidak perlu diubah */
    </style>
    <main id="js-page-content" role="main" class="page-content">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Laporan <span class="fw-300"><i>Rekap Kunjungan OK Berdasarkan Tindakan</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="filter-form">
                                <div class="row mb-3">
                                    <div class="col-md-6 mb-3">
                                        <label>Pilih Tanggal (Tahun akan digunakan untuk Laporan)</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" name="tanggal_awal"
                                                value="{{ request('tanggal_awal', now()->format('d-m-Y')) }}" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text fs-sm"><i class="fal fa-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Tipe Rawat</label>
                                        <select class="form-control select2" name="tipe_rawat">
                                            <option value="">Semua Tipe Rawat</option>
                                            @foreach ($tipe_rawat as $tipe)
                                                <option value="{{ $tipe }}">{{ $tipe }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label>Tipe Operasi</label>
                                        {{-- Menggunakan jenis_operasi dan nama input yang benar --}}
                                        <select class="form-control select2" name="jenis_operasi_id">
                                            <option value="">Semua Tipe Operasi</option>
                                            @foreach ($jenis_operasi as $jenis)
                                                <option value="{{ $jenis->id }}">{{ $jenis->jenis }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label>Tipe Penggunaan</label>
                                        <select class="form-control select2" name="tipe_penggunaan">
                                            <option value="">Semua Tipe Penggunaan</option>
                                            @foreach ($tipe_penggunaan as $tipe)
                                                <option value="{{ $tipe }}">{{ $tipe }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label>Kelas Rawat</label>
                                        <select class="form-control select2" name="kelas_rawat_id">
                                            <option value="">Semua Kelas Rawat</option>
                                            @foreach ($kelas_rawat as $kelas)
                                                <option value="{{ $kelas->id }}">{{ $kelas->kelas }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label>Dokter</label>
                                        <select class="form-control select2" name="dokter_id">
                                            <option value="">Semua Dokter</option>
                                            @foreach ($doctors as $doctor)
                                                {{-- Menggunakan nullsafe operator untuk keamanan --}}
                                                <option value="{{ $doctor->id }}">{{ $doctor->employee?->fullname }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label>Penjamin</label>
                                        <select class="form-control select2" name="penjamin_id">
                                            <option value="">Semua Penjamin</option>
                                            @foreach ($penjamins as $penjamin)
                                                <option value="{{ $penjamin->id }}">{{ $penjamin->nama_perusahaan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row justify-content-end mt-3">
                                    <div class="col-auto">
                                        <button type="submit" class="btn bg-primary-600 mb-3">
                                            <span class="fal fa-search mr-1"></span> Cari & Cetak
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
            // Inisialisasi plugin
            $('.select2').select2();
            $('.datepicker').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
                startView: "days",
                minViewMode: "days"
            });

            // Handler untuk form submit
            $('#filter-form').on('submit', function(e) {
                e.preventDefault();
                var params = $(this).serialize();
                var url = "{{ route('ok.laporan.rekap-kunjungan.print') }}"; // Gunakan route yang benar

                var width = 1200;
                var height = 800;
                var left = (screen.width - width) / 2;
                var top = (screen.height - height) / 2;

                window.open(url + '?' + params, 'RekapKunjunganOK',
                    'width=' + width + ', height=' + height + ', left=' + left + ', top=' + top +
                    ', resizable=yes, scrollbars=yes, status=yes'
                );
            });
        });
    </script>
@endsection
