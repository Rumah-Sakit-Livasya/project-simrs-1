@extends('inc.layout')
@section('title', 'Laporan Order Pasien Persalinan')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Laporan <span class="fw-300"><i>Order Pasien Persalinan</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="filter-form">
                                <div class="row mb-3">
                                    <div class="col-md-6 mb-3">
                                        <label>Awal Periode tgl. Persalinan</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" name="tanggal_awal"
                                                value="{{ now()->format('d-m-Y') }}" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text fs-sm"><i class="fal fa-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Akhir Periode tgl. Persalinan</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" name="tanggal_akhir"
                                                value="{{ now()->format('d-m-Y') }}" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text fs-sm"><i class="fal fa-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <label>No. RM / Nama Pasien</label>
                                        <input type="text" class="form-control" name="invoice"
                                            placeholder="Masukkan No. RM atau Nama Pasien">
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
    {{-- Hanya butuh plugin datepicker --}}
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi plugin
            $('.datepicker').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true
            });

            // Handler untuk form submit
            $('#filter-form').on('submit', function(e) {
                e.preventDefault();
                var params = $(this).serialize();
                // Pastikan route name ini benar sesuai file routes.php Anda
                var url = "{{ route('vk.laporan.order.print') }}?" + params;

                var width = 1200,
                    height = 800,
                    left = (screen.width - width) / 2,
                    top = (screen.height - height) / 2;
                window.open(url, 'LaporanOrderPersalinan', 'width=' + width + ', height=' + height +
                    ', left=' + left + ', top=' + top + ', resizable=yes, scrollbars=yes, status=yes');
            });
        });
    </script>
@endsection
