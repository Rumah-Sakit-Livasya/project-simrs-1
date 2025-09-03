@extends('inc.layout')
@section('title', 'Laporan Transaksi Petty Cash')
@section('content')
    <style>
        table {
            font-size: 8pt !important;
        }

        .modal-lg {
            max-width: 800px;
        }

        #dt-basic-example tbody tr:hover {
            background-color: #f8f9fa;
        }
    </style>

    <main id="js-page-content" role="main" class="page-content">
        <!-- Search Panel -->
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="panel">
                    <div class="panel-hdr">
                        <h2>Filter Data</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="{{ route('keuangan.petty-cash.laporan') }}" method="get">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Periode Awal</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" name="tanggal_awal"
                                                value="{{ request('tanggal_awal') }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text fs-sm"><i class="fal fa-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Periode Akhir</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" name="tanggal_akhir"
                                                value="{{ request('tanggal_akhir') }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text fs-sm"><i class="fal fa-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Keterangan</label>
                                        <input type="text" class="form-control" name="keterangan"
                                            placeholder="Cari keterangan..." value="{{ request('keterangan') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label class="form-label">Kas</label>
                                            <select class="form-control select2" id="kas_id" name="kas_id">
                                                <option value="">Semua Kas</option>
                                                @foreach ($kass as $kas)
                                                    <option value="{{ $kas->id }}"
                                                        {{ request('kas_id') == $kas->id ? 'selected' : '' }}>
                                                        {{ $kas->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary">
                                            <span class="fal fa-search mr-1"></span> Cari
                                        </button>
                                        <a href="{{ route('keuangan.petty-cash.export', request()->query()) }}"
                                            class="btn btn-secondary ml-2">
                                            <span class="fal fa-file-excel mr-1"></span> Export
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table Panel -->
        <div class="panel mt-4">
            <div class="panel-hdr">
                <h2>Daftar Laporan Transaksi Petty Cash</h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">

                    {{-- PESAN INFORMASI JIKA KAS TIDAK DIPILIH --}}
                    @if (!$isKasFiltered && $reports->isNotEmpty())
                        <div class="alert alert-info">
                            <strong>Informasi:</strong> Untuk menampilkan kolom 'Saldo Akhir' yang akurat, silakan pilih
                            satu Kas spesifik pada filter di atas.
                        </div>
                    @endif

                    <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                        <thead class="bg-primary-600 text-white">
                            <tr>
                                <th>No</th>
                                <th>Tgl Transaksi</th>
                                <th>Kode Transaksi</th>
                                <th>Keterangan</th>
                                <th>Kas</th>
                                <th>User Entry</th>
                                <th class="text-right">Nominal</th>
                                <th class="text-right">Saldo Akhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reports as $index => $report)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($report->tanggal)->format('d-m-Y') }}</td>
                                    <td>{{ $report->kode_transaksi }}</td>
                                    <td>{{ $report->keterangan }}</td>
                                    <td>{{ $report->kas_nama ?? '-' }}</td>
                                    <td>{{ $report->user_name ?? '-' }}</td>
                                    <td class="text-right">{{ number_format($report->total_nominal, 2, ',', '.') }}</td>
                                    <td class="text-right">
                                        @if (!is_null($report->saldo_akhir))
                                            {{ number_format($report->saldo_akhir, 2, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">Tidak ada data laporan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script>
        $(document).ready(function() {
            $('#dt-basic-example').DataTable({
                responsive: true,
                pageLength: 25,
                "ordering": false // Memastikan urutan dari controller tidak diubah
            });

            $('.select2').select2();
            $('.datepicker').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true
            });
        });
    </script>
@endsection
