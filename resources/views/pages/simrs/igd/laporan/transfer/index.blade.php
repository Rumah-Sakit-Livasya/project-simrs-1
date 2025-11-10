@extends('inc.layout')
@section('title', 'Laporan Transfer Pasien')

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Laporan <span class="fw-300"><i>Transfer Pasien Rawat Inap</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="laporan-transfer-form">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Periode Tgl. Reg.</label>
                                            <div class="d-flex align-items-center">
                                                <input type="text" name="periode_awal" class="form-control datepicker"
                                                    value="{{ now()->startOfMonth()->format('Y-m-d') }}"
                                                    style="width: 45%;">
                                                <span class="mx-2">s/d</span>
                                                <input type="text" name="periode_akhir" class="form-control datepicker"
                                                    value="{{ now()->endOfMonth()->format('Y-m-d') }}" style="width: 45%;">
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label">No. RM / Nama Pasien</label>
                                            <input type="text" name="no_rm_nama" class="form-control"
                                                placeholder="Kosongkan untuk semua pasien">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Kelas</label>
                                            <select name="kelas_id" class="form-control select2">
                                                <option value="">-- Semua Kelas --</option>
                                                @foreach ($kelas_rawat as $kelas)
                                                    <option value="{{ $kelas->id }}">{{ $kelas->kelas }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-end mt-3">
                                    <div class="col-auto">
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
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                width: '100%'
            });

            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true
            });

            $('#btn-cari').on('click', function(e) {
                e.preventDefault();
                const form = $('#laporan-transfer-form');
                const formData = form.serialize();
                const reportUrl = "{{ route('rawat-inap.laporan.transfer.report') }}?" + formData;

                const windowFeatures = "resizable=yes,scrollbars=yes,status=yes,width=1200,height=800";
                window.open(reportUrl, 'reportWindow', windowFeatures);
            });
        });
    </script>
@endsection
