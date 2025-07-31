@extends('inc.layout')
@section('title', 'Daftar Pembayaran Asuransi')
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
                                CSS BARU UNTUK DETAILS CONTROL (Disalin dari Konfirmasi Asuransi)
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
            font-size: 12px;
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
                        <h2>Daftar <span class="fw-300"><i>Pembayaran Asuransi</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="{{ route('keuangan.pembayaran-asuransi.index') }}" method="get">
                                @csrf
                                <div class="row mb-3">
                                    <!-- Tanggal Periode (Dari - Sampai) -->
                                    <div class="col-md-6 mb-3">
                                        <label>Periode Awal</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" name="tanggal_awal"
                                                value="{{ request('tanggal_awal') }}">
                                            <div class="input-group-append"><span class="input-group-text fs-sm"><i
                                                        class="fal fa-calendar"></i></span></div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Periode Akhir</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" name="tanggal_akhir"
                                                value="{{ request('tanggal_akhir') }}">
                                            <div class="input-group-append"><span class="input-group-text fs-sm"><i
                                                        class="fal fa-calendar"></i></span></div>
                                        </div>
                                    </div>


                                    <div class="col-md-6 mt-3">
                                        <label>No. Invoice</label>
                                        <input type="text" class="form-control" id="invoice" name="invoice"
                                            placeholder="Masukkan No.invoice" value="{{ request('invoice') }}">
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label>Penjamin</label>
                                        <select class="form-control select2" id="penjamin_id" name="penjamin_id" required>
                                            <option value="">Pilih Penjamin</option>
                                            @foreach ($penjamins as $penjamin)
                                                <option value="{{ $penjamin->id }}"
                                                    {{ request('penjamin_id') == $penjamin->id ? 'selected' : '' }}>
                                                    {{ $penjamin->nama_perusahaan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="row justify-content-end mt-3">
                                    <div class="col-auto">
                                        <button type="submit" class="btn bg-primary-600 mb-3">
                                            <span class="fal fa-search mr-1"></span> Cari
                                        </button>
                                        <a href="{{ route('keuangan.pembayaran-asuransi.create') }}"
                                            class="btn bg-primary-600 mb-3" id="create-btn">
                                            <span class="fal fa-plus mr-1"></span> Tambah Pembayaran A/R
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
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Daftar <span class="fw-300"><i>Pembayaran Asuransi</i></span></h2>
                        {{-- Panel toolbar tetap sama --}}
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                                    </button>
                                    <strong>Sukses!</strong> {{ session('success') }}
                                </div>
                            @endif

                            <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>#</th>
                                        <th style="width: 10px;"></th> {{-- MODIFIKASI: Kolom untuk ikon expand/collapse --}}
                                        <th>Tgl Transaksi</th>
                                        <th>No. Transaksi</th>
                                        <th>Penjamin</th>
                                        <th>Jumlah</th>
                                        <th>Bank</th>
                                        <th>Keterangan</th>
                                        <th>Fungsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pembayaranAsuransi as $pembayaran)
                                        {{-- MODIFIKASI: Menggunakan data-id untuk identifikasi di JS dan hapus class parent-row --}}
                                        <tr data-id="{{ $pembayaran->id }}">
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            {{-- MODIFIKASI: Menggunakan class dan ikon yang sama dengan Konfirmasi Asuransi --}}
                                            <td class="details-control"><i class="fal fa-chevron-up"></i></td>
                                            <td>{{ \Carbon\Carbon::parse($pembayaran->tanggal)->format('d-m-Y') }}</td>
                                            <td>{{ $pembayaran->nomor_transaksi }}</td>
                                            <td>{{ $pembayaran->penjamin->nama_perusahaan ?? '-' }}</td>
                                            <td class="text-right">
                                                {{ 'Rp ' . number_format($pembayaran->jumlah ?? 0, 2, ',', '.') }}
                                            </td>
                                            <td>{{ $pembayaran->bank->name ?? 'KAS' }}</td>
                                            <td>{{ $pembayaran->keterangan ?? '-' }}</td>
                                            <td class="text-center">
                                                <form
                                                    action="{{ route('keuangan.pembayaran-asuransi.destroy', $pembayaran->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus pembayaran ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        {{-- MODIFIKASI: Hapus Child Row statis dari sini. Akan digenerate oleh JS --}}
                                    @endforeach
                                </tbody>

                            </table>

                            {{-- TAMBAHAN: Template untuk Child Row (diletakkan di luar tabel, disalin dari Konfirmasi) --}}
                            <div id="child-row-template" style="display: none;">
                                <div class="child-row-content">
                                    <h6 class="mb-3"><strong>Rincian untuk No. Transaksi <span
                                                class="invoice-placeholder">{no_transaksi}</span>:</strong></h6>
                                    <table class="child-table table table-sm table-bordered">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>No. RM</th>
                                                <th>Nama Pasien</th>
                                                <th>No. Registrasi</th>
                                                <th>No. Invoice</th>
                                                <th>Tgl. AR</th>
                                                <th class="text-right">Tagihan Invoice</th>
                                                <th class="text-right">Pelunasan</th>
                                            </tr>
                                        </thead>
                                        <tbody class="detail-tbody">
                                            {{-- Isi akan digenerate oleh JavaScript --}}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    {{-- Plugin scripts tetap sama --}}
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>
    <script src="/js/formplugins/inputmask/inputmask.bundle.js"></script>
    <script src="/js/formplugins/sweetalert2/sweetalert2.bundle.js"></script>
    <script src="/js/notifications/toastr/toastr.js"></script>
    <link rel="stylesheet" href="/css/notifications/toastr/toastr.css">

    <script>
        $(document).ready(function() {
            // Inisialisasi plugin dasar
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true
            });
            $('.select2').select2({
                dropdownCssClass: "move-up"
            });

            // ==========================================================
            // LOGIKA JAVASCRIPT BARU (Disalin & diadaptasi dari Konfirmasi Asuransi)
            // ==========================================================

            // 1. Siapkan data detail dalam variabel JavaScript
            const allDetails = {!! json_encode(
                $pembayaranAsuransi->mapWithKeys(function ($pembayaran) {
                    return [
                        $pembayaran->id => $pembayaran->details->map(function ($detail) use ($pembayaran) {
                            $konfirmasi = $detail->konfirmasiasuransi;
                            return [
                                'rm' => optional(optional(optional($konfirmasi)->registration)->patient)->medical_record_number,
                                'pasien' => optional(optional(optional($konfirmasi)->registration)->patient)->name,
                                'reg_no' => optional(optional($konfirmasi)->registration)->registration_number,
                                'invoice' => optional($konfirmasi)->invoice,
                                'tgl_ar' => optional($konfirmasi)->tanggal
                                    ? \Carbon\Carbon::parse($konfirmasi->tanggal)->format('d-m-Y')
                                    : '-',
                                'tagihan_invoice' => optional($konfirmasi)->jumlah,
                                'pelunasan' => $pembayaran->jumlah, // Ini mungkin perlu disesuaikan jika 1 pembayaran untuk banyak invoice
                            ];
                        }),
                    ];
                }),
            ) !!};

            // 2. Inisialisasi DataTable
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
                        title: 'Daftar Pembayaran Asuransi',
                        exportOptions: {
                            columns: [2, 3, 4, 5, 6, 7]
                        },
                        orientation: 'landscape'
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fal fa-file-excel mr-1"></i> Excel',
                        className: 'btn-outline-success btn-sm mr-1',
                        title: 'Daftar Pembayaran Asuransi',
                        exportOptions: {
                            columns: [2, 3, 4, 5, 6, 7]
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fal fa-print mr-1"></i> Print',
                        className: 'btn-outline-primary btn-sm',
                        title: 'Daftar Pembayaran Asuransi',
                        exportOptions: {
                            columns: [2, 3, 4, 5, 6, 7]
                        }
                    }
                ],
                columnDefs: [{
                    orderable: false,
                    targets: [0, 1, 8] // Kolom #, detail, dan fungsi tidak bisa diurutkan
                }]
            });

            // 3. Fungsi untuk memformat child row
            function formatChildRow(no_transaksi, details) {
                // Ambil template dari div yang kita sembunyikan
                var template = $('#child-row-template').clone();
                template.find('.invoice-placeholder').text(no_transaksi);

                var tbody = template.find('.detail-tbody');
                tbody.empty();

                if (details && details.length > 0) {
                    details.forEach(function(detail) {
                        var rowHtml = `
                        <tr>
                            <td>${detail.rm || '-'}</td>
                            <td>${detail.pasien || '-'}</td>
                            <td>${detail.reg_no || '-'}</td>
                            <td>${detail.invoice || '-'}</td>
                            <td>${detail.tgl_ar || '-'}</td>
                            <td class="text-right">${'Rp ' + new Intl.NumberFormat('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(detail.tagihan_invoice || 0)}</td>
                            <td class="text-right">${'Rp ' + new Intl.NumberFormat('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(detail.pelunasan || 0)}</td>
                        </tr>
                    `;
                        tbody.append(rowHtml);
                    });
                } else {
                    tbody.append(
                        '<tr><td colspan="7" class="text-center text-muted">Tidak ada rincian data untuk pembayaran ini.</td></tr>'
                    );
                }

                return template.html();
            }

            // 4. Logika child row menggunakan API DataTables
            $('#dt-basic-example tbody').on('click', 'td.details-control', function() {
                var tr = $(this).closest('tr');
                var row = table.row(tr);

                if (row.child.isShown()) {
                    // Baris ini sudah terbuka, tutup.
                    row.child.hide();
                    tr.removeClass('dt-hasChild');
                } else {
                    // Ambil ID pembayaran dari data attribute di TR
                    var pembayaranId = tr.data('id');
                    var no_transaksi = tr.find('td:eq(3)').text()
                        .trim(); // Ambil No. Transaksi dari kolom ke-4

                    // Ambil detail dari allDetails berdasarkan ID pembayaran
                    var details = allDetails[pembayaranId] || [];

                    // Buka baris dan format kontennya
                    row.child(formatChildRow(no_transaksi, details)).show();
                    tr.addClass('dt-hasChild');

                    // Inisialisasi ulang tooltip jika ada di dalam child row
                    $(row.child()).find('[data-toggle="tooltip"]').tooltip();
                }
            });

            // Inisialisasi Tooltip jika ada di luar child row
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection
