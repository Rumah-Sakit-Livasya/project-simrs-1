@extends('inc.layout')
@section('title', 'Laporan Rekap Kunjungan Persalinan')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Laporan <span class="fw-300"><i>Rekap Kunjungan Persalinan Berdasarkan Tindakan</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="filter-form">
                                <div class="row mb-3">
                                    {{-- [PERBAIKAN] Menggunakan rentang tanggal --}}
                                    <div class="col-md-6 mb-3">
                                        <label>Pilih Rentang Tanggal</label>
                                        <div class="input-daterange input-group" id="datepicker-5">
                                            <input type="text" class="form-control" name="tanggal_awal" value="{{ now()->startOfMonth()->format('d-m-Y') }}" required>
                                            <div class="input-group-append input-group-prepend">
                                                <span class="input-group-text fs-xl"><i class="fal fa-long-arrow-right"></i></span>
                                            </div>
                                            <input type="text" class="form-control" name="tanggal_akhir" value="{{ now()->endOfMonth()->format('d-m-Y') }}" required>
                                        </div>
                                        <span class="help-block">Laporan akan menampilkan rekapitulasi per bulan berdasarkan rentang tanggal yang dipilih.</span>
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
                                        <label>Kategori Persalinan</label>
                                        <select class="form-control select2" name="kategori_id">
                                            <option value="">Semua Kategori</option>
                                            @foreach ($kategori_persalinan as $kategori)
                                                <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
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
                                        <label>Dokter/Bidan</label>
                                        <select class="form-control select2" name="dokter_id">
                                            <option value="">Semua Dokter/Bidan</option>
                                            @foreach ($doctors as $doctor)
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
                                        <button type="submit" class="btn bg-primary-600 mb-3"><span
                                                class="fal fa-search mr-1"></span> Cari & Cetak</button>
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
            $('.select2').select2();
            
            // [PERBAIKAN] Inisialisasi datepicker untuk rentang tanggal
            $('.input-daterange').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
                orientation: "bottom left" // Agar kalender tidak menutupi input
            });

            $('#filter-form').on('submit', function(e) {
                e.preventDefault();
                var params = $(this).serialize();
                var url = "{{ route('vk.laporan.rekap-per-tindakan.print') }}?" + params;
                var width = 1200,
                    height = 800,
                    left = (screen.width - width) / 2,
                    top = (screen.height - height) / 2;
                window.open(url, 'RekapPersalinan', 'width=' + width + ', height=' + height + ', left=' +
                    left + ', top=' + top + ', resizable=yes, scrollbars=yes, status=yes');
            });
        });
    </script>
@endsection