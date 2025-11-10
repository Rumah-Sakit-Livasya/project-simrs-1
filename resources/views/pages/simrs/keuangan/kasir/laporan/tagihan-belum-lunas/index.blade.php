@extends('inc.layout')
@section('title', 'Tagihan Belum Lunas')

@push('styles')
    <link rel="stylesheet" media="screen, print"
        href="/css/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.css">
    <style>
        table {
            font-size: 8pt !important;
        }

        .modal-lg {
            max-width: 800px;
        }

        table.dataTable thead .sorting:after,
        table.dataTable thead .sorting_asc:after,
        table.dataTable thead .sorting_desc:after,
        table.dataTable thead .sorting_asc_disabled:after,
        table.dataTable thead .sorting_desc_disabled:after {
            display: none !important;
        }

        #dt-basic-example tbody tr:hover {
            background-color: #f8f9fa;
        }
    </style>
@endpush

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <!-- Search Panel -->
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Tagihan <span class="fw-300"><i>Belum Lunas</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="#" method="GET">
                                <div class="row mb-3">
                                    <!-- Tanggal Billing -->
                                    <div class="col-md-6 mb-3">
                                        <label>Tgl Billing S/D</label>
                                        <div class="input-group">
                                            <input type="text" id="tgl_billing" name="tgl_billing"
                                                class="form-control daterange"
                                                value="{{ request('tgl_billing', $tglBillingInput) }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text fs-sm"><i class="fal fa-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tipe Kunjungan -->
                                    <div class="col-md-6 mb-3">
                                        <label>Tipe Kunjungan</label>
                                        <select class="form-control select2" id="tipe_kunjungan" name="tipe_kunjungan">
                                            @if (!empty($tipeKunjungan) && is_iterable($tipeKunjungan))
                                                @foreach ($tipeKunjungan as $tipe)
                                                    <option value="{{ $tipe }}"
                                                        {{ request('tipe_kunjungan') == $tipe ? 'selected' : '' }}>
                                                        {{ $tipe }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>

                                    <!-- Nama Pasien -->
                                    <div class="col-md-6 mb-3">
                                        <label>Nama Pasien</label>
                                        <input type="text" id="nama_pasien" name="nama_pasien" class="form-control"
                                            value="{{ request('nama_pasien') }}" placeholder="Masukkan nama pasien">
                                    </div>

                                    <!-- No RM -->
                                    <div class="col-md-6 mb-3">
                                        <label>No RM</label>
                                        <input type="text" id="no_rm" name="no_rm" class="form-control"
                                            value="{{ request('no_rm') }}" placeholder="Masukkan nomor RM">
                                    </div>
                                </div>

                                <div class="row justify-content-end mt-3">
                                    <div class="col-auto">
                                        <button type="submit" name="action" value="cari"
                                            class="btn bg-primary-600 mb-3">
                                            <span class="fal fa-search mr-1"></span> Cari
                                        </button>
                                        <button type="submit" name="action" value="xls" class="btn btn-success mb-3">
                                            <span class="fal fa-file-excel mr-1"></span> Export XLS
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table Panel -->
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-2" class="panel">
                    <div class="panel-hdr">
                        <h2>Hasil <span class="fw-300"><i>Pencarian</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="table-responsive">
                                <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                    <thead class="bg-primary-600">
                                        <tr>
                                            <th>#</th>
                                            <th>Tgl Billing</th>
                                            <th>Tgl Registrasi</th>
                                            <th>No Registrasi</th>
                                            <th>No RM</th>
                                            <th>Nama Pasien</th>
                                            <th>Ruangan</th>
                                            <th>User Input</th>
                                            <th class="text-end">Nominal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $dataLaporan = is_iterable($hasilLaporan) ? $hasilLaporan : collect([]);
                                        @endphp
                                        @forelse($dataLaporan as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->registration->created_at)->format('d M Y') }}
                                                </td>
                                                <td>{{ $item->registration->registration_number ?? 'N/A' }}</td>
                                                <td>{{ $item->registration->patient->medical_record_number ?? 'N/A' }}</td>
                                                <td>{{ $item->registration->patient->name ?? 'N/A' }}</td>
                                                <td>{{ $item->registration->departement->name ?? 'N/A' }}</td>
                                                {{-- Mengambil nama user dari tagihan pertama yang terhubung --}}
                                                <td>
                                                    {{ optional(optional($item->tagihanPasien)->first())->user->name ?? 'N/A' }}
                                                </td>
                                                <td class="text-end">
                                                    {{ number_format($item->wajib_bayar ?? 0, 2, ',', '.') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center">Tidak ada data ditemukan.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr class="fw-bold">
                                            <td colspan="8" class="text-end">Total:</td>
                                            <td class="text-end">
                                                {{ number_format($hasilLaporan ? $hasilLaporan->sum('wajib_bayar') : 0, 2, ',', '.') }}
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
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                width: '100%',
                dropdownCssClass: "move-up"
            });

            // Initialize DateRangePicker
            $('.daterange').daterangepicker({
                opens: 'left',
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });

            // Initialize DataTable
            var table = $('#dt-basic-example').DataTable({
                responsive: true,
                lengthChange: false,
                pageLength: 20,
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                        extend: 'pdfHtml5',
                        text: '<i class="fal fa-file-pdf mr-1"></i> PDF',
                        className: 'btn-outline-danger btn-sm mr-1',
                        title: 'Laporan Tagihan Belum Lunas',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7, 8]
                        },
                        orientation: 'landscape'
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fal fa-file-excel mr-1"></i> Excel',
                        className: 'btn-outline-success btn-sm mr-1',
                        title: 'Laporan Tagihan Belum Lunas',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fal fa-print mr-1"></i> Print',
                        className: 'btn-outline-primary btn-sm',
                        title: 'Laporan Tagihan Belum Lunas',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    }
                ],
                columnDefs: [{
                    orderable: false,
                    targets: [0] // Kolom # tidak bisa diurutkan
                }],
                language: {
                    search: "",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(disaring dari _MAX_ total data)",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                }
            });
        });
    </script>
@endsection
