@extends('inc.layout')
@section('title', 'Laporan Hapus SEP')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Laporan Hapus SEP
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            {{-- Form ini akan menargetkan jendela baru saat disubmit --}}
                            <form id="laporan-form">
                                @csrf
                                <div class="row">
                                    {{-- Kolom Kiri --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="awal_periode">Awal Periode</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control datepicker" id="awal_periode"
                                                    name="awal_periode" value="{{ date('d-m-Y', strtotime('-12 days')) }}">
                                                <div class="input-group-append"><span class="input-group-text fs-sm"><i
                                                            class="fal fa-calendar"></i></span></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label" for="tipe_rawat">Tipe Rawat</label>
                                            <select class="form-control select2" id="tipe_rawat" name="tipe_rawat">
                                                <option value="Semua">Semua</option>
                                                <option value="Rawat Jalan">Rawat Jalan</option>
                                                <option value="Rawat Inap">Rawat Inap</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label" for="no_rm_pasien">No. RM / Nama Pasien</label>
                                            <input type="text" class="form-control" id="no_rm_pasien" name="no_rm_pasien"
                                                placeholder="Kosongkan jika semua">
                                        </div>
                                    </div>
                                    {{-- Kolom Kanan --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="akhir_periode">Akhir Periode</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control datepicker" id="akhir_periode"
                                                    name="akhir_periode" value="{{ date('d-m-Y') }}">
                                                <div class="input-group-append"><span class="input-group-text fs-sm"><i
                                                            class="fal fa-calendar"></i></span></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label" for="no_sep">No. SEP</label>
                                            <input type="text" class="form-control" id="no_sep" name="no_sep"
                                                placeholder="Kosongkan jika semua">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label" for="no_kartu">No. Kartu</label>
                                            <input type="text" class="form-control" id="no_kartu" name="no_kartu"
                                                placeholder="Kosongkan jika semua">
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fal fa-display mr-1"></i> Tampil
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
    {{-- Include plugin yang diperlukan --}}
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/notifications/toastr/toastr.js"></script>
    <link rel="stylesheet" href="/css/notifications/toastr/toastr.css">

    <script>
        $(document).ready(function() {
            // 1. Inisialisasi plugin
            $('.datepicker').datepicker({
                todayHighlight: true,
                orientation: "bottom left",
                format: 'dd-mm-yyyy', // Format sesuai gambar
                autoclose: true
            });

            $('.select2').select2({
                width: '100%'
            });

            // 2. Handle form submission untuk membuka jendela baru
            $('#laporan-form').on('submit', function(e) {
                e.preventDefault();

                var awalPeriode = $('#awal_periode').val();
                var akhirPeriode = $('#akhir_periode').val();

                if (!awalPeriode || !akhirPeriode) {
                    toastr.error('Silakan isi Awal dan Akhir Periode.', 'Error');
                    return;
                }

                var formData = $(this).serialize();

                // GUNAKAN ROUTE PRINT YANG BARU
                var reportUrl = "{{ route('laporan.print.hapus-sep') }}?" + formData;

                // Buka URL di jendela baru
                window.open(reportUrl, '_blank', 'width=1000,height=800,scrollbars=yes,resizable=yes');

                toastr.info('Laporan sedang dibuka di jendela baru...', 'Info');
            });
        });
    </script>
@endsection
