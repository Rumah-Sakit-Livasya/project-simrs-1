@extends('inc.layout')
@section('title', 'Laporan Rekap Ranap Per Tanggal')

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Laporan <span class="fw-300"><i>Rekap Pasien Rawat Inap Per Tanggal</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="laporan-pertanggal-form">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Periode Tgl. Reg.</label>
                                            <div class="d-flex">
                                                <select name="month" class="form-control select2" style="width: 60%;">
                                                    @foreach ($months as $num => $name)
                                                        <option value="{{ $num }}"
                                                            {{ $num == date('m') ? 'selected' : '' }}>{{ $name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <select name="year" class="form-control select2 ml-2"
                                                    style="width: 40%;">
                                                    @foreach ($years as $year)
                                                        <option value="{{ $year }}"
                                                            {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Penjamin</label>
                                            <select name="penjamin_id" class="form-control select2">
                                                <option value="">-- Semua Penjamin --</option>
                                                @foreach ($penjamin as $p)
                                                    <option value="{{ $p->id }}">{{ $p->nama_perusahaan }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
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
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                width: '100%'
            });

            $('#btn-cari').on('click', function(e) {
                e.preventDefault();
                const form = $('#laporan-pertanggal-form');
                const formData = form.serialize();
                const reportUrl = "{{ route('rawat-inap.laporan-per-tanggal.report') }}?" + formData;

                const windowFeatures = "resizable=yes,scrollbars=yes,status=yes,width=1200,height=800";
                window.open(reportUrl, 'reportWindow', windowFeatures);
            });
        });
    </script>
@endsection
