@extends('inc.layout')
@section('title', 'Laporan Belum Simpan Tagihan')

@section('content')
    <style>
        table {
            font-size: 8pt !important;
        }

        .modal-lg {
            max-width: 800px;
        }

        /*
                                                                    ====================================================================
                                                                    CSS BARU UNTUK DETAILS CONTROL (Disamakan dengan Pertanggung Jawaban)
                                                                    ====================================================================
                                                                */
        .details-control {
            cursor: pointer;
            text-align: center;
            width: 30px;
            padding: 8px !important;
        }

        .details-control i {
            transition: transform 0.3s ease, color 0.3s ease;
            color: #3498db;
            font-size: 16px;
            /* Default: Panah ke atas (chevron-up), siap untuk diexpand ke bawah */
            transform: rotate(0deg);
        }

        .details-control:hover i {
            color: #2980b9;
        }

        /* Saat baris memiliki class 'dt-hasChild' (child row terbuka), putar ikon 180 derajat */
        tr.dt-hasChild td.details-control i {
            transform: rotate(180deg);
            color: #e74c3c;
        }

        td.details-control::before {
            display: none !important;
        }

        /* Styling untuk child row content */
        .child-row-content {
            padding: 15px;
            background-color: #f9f9f9;
        }

        /* Sembunyikan ikon sort bawaan DataTables */
        table.dataTable thead .sorting:after,
        table.dataTable thead .sorting_asc:after,
        table.dataTable thead .sorting_desc:after,
        table.dataTable thead .sorting_asc_disabled:after,
        table.dataTable thead .sorting_desc_disabled:after {
            display: none !important;
        }

        /* Styling untuk tabel di dalam child row */
        .child-table {
            width: 98% !important;
            margin: 10px auto !important;
            border-radius: 4px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .child-table thead th {
            background-color: #021d39;
            color: white;
            font-size: 12px;
            padding: 8px !important;
        }

        .child-table tbody td {
            padding: 8px !important;
            font-size: 12pxx;
            background-color: white;
        }

        /* Efek hover untuk row */
        #dt-basic-example tbody tr:hover {
            background-color: #f8f9fa;
        }
    </style>

    <main id="js-page-content" role="main" class="page-content">
        <!-- Search Panel -->
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Laporan <span class="fw-300"><i>Belum Simpan Tagihan</i></span></h2>
                        <div class="panel-toolbar">
                            @if (request()->filled([
                                    'periode_awal',
                                    'periode_akhir',
                                    'nama_pasien',
                                    'no_rm',
                                    'no_registrasi',
                                    'tipe_kunjungan',
                                    'status_kunjungan',
                                ]))
                                <span class="badge bg-primary-600 badge-info p-2">
                                    Filter Aktif:
                                    @if (request('periode_awal') && request('periode_akhir'))
                                        {{ \Carbon\Carbon::parse(request('periode_awal'))->translatedFormat('d M Y') }}
                                        s/d
                                        {{ \Carbon\Carbon::parse(request('periode_akhir'))->translatedFormat('d M Y') }}
                                    @endif
                                    @if (request('nama_pasien'))
                                        {{ request('periode_awal') ? ' | ' : '' }} Pasien: {{ request('nama_pasien') }}
                                    @endif
                                    @if (request('no_rm'))
                                        {{ request('periode_awal') || request('nama_pasien') ? ' | ' : '' }} RM:
                                        {{ request('no_rm') }}
                                    @endif
                                    @if (request('no_registrasi'))
                                        {{ request('periode_awal') || request('nama_pasien') || request('no_rm') ? ' | ' : '' }}
                                        Reg: {{ request('no_registrasi') }}
                                    @endif
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="{{ route('laporan.tagihan-belum-disimpan-bill.index') }}" method="GET">
                                @csrf
                                <div class="row">
                                    <!-- Left Column -->
                                    <div class="col-md-6">
                                        <!-- Periode Awal -->
                                        <div class="mb-3">
                                            <label class="form-label">Periode Awal</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control datepicker" name="periode_awal"
                                                    value="{{ request('periode_awal', $periodeAwalInput) }}"
                                                    placeholder="yyyy-mm-dd">
                                                <div class="input-group-append">
                                                    <span class="input-group-text fs-sm">
                                                        <i class="fal fa-calendar"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Nama Pasien -->
                                        <div class="mb-3">
                                            <label class="form-label">Nama Pasien</label>
                                            <input type="text" class="form-control" name="nama_pasien"
                                                value="{{ request('nama_pasien') }}" placeholder="Masukkan nama pasien">
                                        </div>

                                        <!-- Tipe Kunjungan -->
                                        <div class="mb-3">
                                            <label class="form-label">Tipe Kunjungan</label>
                                            <select class="form-control select2" name="tipe_kunjungan">
                                                <option value="">Semua Tipe</option>
                                                @foreach ((array) $tipeKunjungan as $tipe)
                                                    <option value="{{ $tipe }}"
                                                        {{ request('tipe_kunjungan') == $tipe ? 'selected' : '' }}>
                                                        {{ $tipe }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Status Kunjungan -->
                                        <div class="mb-3">
                                            <label class="form-label">Status Kunjungan</label>
                                            <select class="form-control select2" name="status_kunjungan">
                                                <option value="">Semua Status</option>
                                                @foreach ((array) $statusKunjungan as $status)
                                                    <option value="{{ $status }}"
                                                        {{ request('status_kunjungan') == $status ? 'selected' : '' }}>
                                                        {{ $status }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Right Column -->
                                    <div class="col-md-6">
                                        <!-- Periode Akhir -->
                                        <div class="mb-3">
                                            <label class="form-label">Periode Akhir</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control datepicker" name="periode_akhir"
                                                    value="{{ request('periode_akhir', $periodeAkhirInput) }}"
                                                    placeholder="yyyy-mm-dd">
                                                <div class="input-group-append">
                                                    <span class="input-group-text fs-sm">
                                                        <i class="fal fa-calendar"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- No RM -->
                                        <div class="mb-3">
                                            <label class="form-label">No RM</label>
                                            <input type="text" class="form-control" name="no_rm"
                                                value="{{ request('no_rm') }}" placeholder="Masukkan No. RM">
                                        </div>

                                        <!-- No Registrasi -->
                                        <div class="mb-3">
                                            <label class="form-label">No Registrasi</label>
                                            <input type="text" class="form-control" name="no_registrasi"
                                                value="{{ request('no_registrasi') }}"
                                                placeholder="Masukkan No. Registrasi">
                                        </div>
                                    </div>
                                </div>

                                <!-- Tombol Aksi -->
                                <div class="row justify-content-end mt-3">
                                    <div class="col-auto">
                                        <button type="submit" name="action" value="tampilkan" class="btn bg-primary-600">
                                            <i class="fal fa-search"></i> Tampilkan
                                        </button>
                                        <button type="submit" name="action" value="xls"
                                            class="btn bg-success-600 ms-2">
                                            <i class="fal fa-file-excel"></i> Export XLS
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Panel -->
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-2" class="panel">
                    <div class="panel-hdr">
                        <h2>Hasil Pencarian</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped w-100" id="laporan-dt">
                                    <thead class="bg-primary-600">
                                        <tr>
                                            <th>No</th>
                                            <th>Tgl Registrasi</th>
                                            <th>No Registrasi</th>
                                            <th>No RM</th>
                                            <th>Nama Pasien</th>
                                            <th>Ruangan</th>
                                            <th>Tagihan</th>
                                            <th>Penjamin</th>
                                            <th class="text-end">Nominal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $items = $hasilLaporan ?? [];
                                            // Support: $hasilLaporan can be null, array, or Collection
                                            if (is_null($items)) {
                                                $items = [];
                                            } elseif (
                                                !is_array($items) &&
                                                !($items instanceof \Illuminate\Support\Collection)
                                            ) {
                                                $items = [];
                                            }
                                        @endphp

                                        @forelse($items as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    {{ $item->tgl_registrasi ? \Carbon\Carbon::parse($item->tgl_registrasi)->format('d M Y') : '-' }}
                                                </td>
                                                <td>{{ $item->no_registrasi ?? '-' }}</td>
                                                <td>{{ $item->no_rm ?? '-' }}</td>
                                                <td>{{ $item->nama_pasien ?? '-' }}</td>
                                                <td>{{ $item->ruangan ?? '-' }}</td>
                                                <td>{{ $item->tagihan ?? '-' }}</td>
                                                <td>{{ $item->penjamin ?? '-' }}</td>
                                                <td class="text-end">
                                                    {{ isset($item->nominal) ? number_format($item->nominal, 2, ',', '.') : '0,00' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center text-muted">Tidak ada data ditemukan.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr class="fw-bold bg-light">
                                            <td colspan="8" class="text-end">Total:</td>
                                            <td class="text-end">
                                                {{ is_iterable($hasilLaporan) ? number_format(collect($hasilLaporan)->sum('nominal'), 2, ',', '.') : '0,00' }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    <!-- Plugin yang diperlukan -->
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/dependency/moment/moment.js"></script>

    <script>
        $(document).ready(function() {
            // Inisialisasi Select2
            $('.select2').select2({
                dropdownCssClass: "move-up",
                width: '100%'
            });

            // Inisialisasi Datepicker
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                orientation: 'bottom left'
            });

            // Inisialisasi DataTable hanya jika ada hasil
            @if (request()->has('action') && request('action') == 'tampilkan')
                $('#laporan-dt').DataTable({
                    responsive: true,
                    lengthChange: false,
                    pageLength: 20,
                    dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                    buttons: [{
                            extend: 'excelHtml5',
                            text: '<i class="fal fa-file-excel mr-1"></i> Excel',
                            className: 'btn-outline-success btn-sm',
                            title: 'Laporan Belum Simpan Tagihan'
                        },
                        {
                            extend: 'print',
                            text: '<i class="fal fa-print mr-1"></i> Print',
                            className: 'btn-outline-primary btn-sm',
                            title: 'Laporan Belum Simpan Tagihan'
                        }
                    ],
                    columnDefs: [{
                        orderable: false,
                        targets: 0
                    }]
                });
            @endif
        });
    </script>
@endsection
