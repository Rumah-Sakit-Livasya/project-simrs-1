@extends('inc.layout')
@section('title', 'Pertanggung Jawaban ')
@section('content')
    <style>
        table {
            font-size: 8pt !important;
        }

        .badge-pending {
            background-color: #f39c12;
            color: white;
        }

        .badge-approved {
            background-color: #00a65a;
            color: white;
        }

        .badge-rejected {
            background-color: #dd4b39;
            color: white;
        }

        .modal-lg {
            max-width: 800px;
        }

        .panel-loading {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 999;
        }

        .child-row {
            display: none;
        }

        .dropdown-icon {
            font-size: 14px;
            transition: transform 0.3s ease;
            display: inline-block;
        }

        .dropdown-icon.rotated {
            transform: rotate(180deg);
        }

        .child-row td {
            background-color: #f9f9f9;
            border-bottom: 2px solid #ddd;
        }

        .child-row td>div {
            padding: 15px;
            margin: 0;
        }

        tr.parent-row.active {
            border-bottom: none !important;
        }

        .control-details {
            cursor: pointer;
            text-align: center;
            width: 50px;
        }

        .control-details .dropdown-icon {
            font-size: 18px;
            transition: transform 0.3s ease, color 0.3s ease;
            display: inline-block;
            color: #3498db;
        }

        .control-details .dropdown-icon.rotated {
            transform: rotate(180deg);
            color: #e74c3c;
        }

        .control-details:hover .dropdown-icon {
            color: #2980b9;
        }

        table.dataTable thead .sorting:after,
        table.dataTable thead .sorting_asc:after,
        table.dataTable thead .sorting_desc:after,
        table.dataTable thead .sorting_asc_disabled:after,
        table.dataTable thead .sorting_desc_disabled:after {
            display: none !important;
        }

        .child-row td>div {
            padding: 15px;
            width: 100%;
        }

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

        .child-row {
            transition: all 0.3s ease;
        }

        .child-row.show {
            opacity: 1;
        }

        td.control-details::before {
            display: none !important;
        }

        #pj-table tbody tr.parent-row:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }

        #pj-table tbody tr.child-row:hover {
            background-color: #f1f1f1;
        }

        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .loading-spinner {
            background: white;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
        }

        /* Fix untuk memastikan child row dapat di-toggle */
        .parent-row.expanded {
            border-bottom: none !important;
        }

        .toggle-detail {
            border: none;
            background: transparent;
            color: #3498db;
            padding: 5px;
        }

        .toggle-detail:hover {
            color: #2980b9;
            background: rgba(52, 152, 219, 0.1);
        }

        .toggle-detail:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.25);
        }

        /* PERBAIKAN CSS UNTUK DETAILS CONTROL */
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
            /* Default: Panah ke atas (chevron-up) */
            transform: rotate(0deg);
        }

        .details-control:hover i {
            color: #2980b9;
        }

        /* Saat baris memiliki class 'dt-hasChild' (child row terbuka), putar ikon 180 derajat (menjadi panah ke bawah) */
        tr.dt-hasChild td.details-control i {
            transform: rotate(180deg);
            color: #e74c3c;
        }

        .child-row-content {
            padding: 15px;
            background-color: #f9f9f9;
        }

        /* Styling untuk badge pada detail */
        .badge-info {
            background-color: #17a2b8;
            color: white;
        }

        .badge-secondary {
            background-color: #6c757d;
            color: white;
        }
    </style>

    <!-- Loading overlay div -->
    <div class="loading-overlay">
        <div class="loading-spinner">
            <i class="fa fa-spinner fa-spin"></i> Memuat...
        </div>
    </div>

    <main id="js-page-content" role="main" class="page-content">
        <!-- Search Panel -->
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Form <span class="fw-300"><i>Pencarian</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="search-form">
                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label>Periode Awal</label>
                                        <div class="input-group input-grup-sm">
                                            <input type="text" class="form-control datepicker" id="tanggal_awal"
                                                name="tanggal_awal" placeholder="Pilih Tanggal Awal"
                                                value="{{ request('tanggal_awal') }}">
                                            <div class="input-group-append"><span class="input-group-text fs-sm"><i
                                                        class="fal fa-calendar"></i></span></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Periode Akhir</label>
                                        <div class="input-group input-grup-sm">
                                            <input type="text" class="form-control datepicker" id="tanggal_akhir"
                                                name="tanggal_akhir" placeholder="Pilih Tanggal Akhir"
                                                value="{{ request('tanggal_akhir') }}">
                                            <div class="input-group-append"><span class="input-group-text fs-sm"><i
                                                        class="fal fa-calendar"></i></span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label>Kode Pertanggung Jawaban</label>
                                        <input type="text" class="form-control" id="kode_pjawaban" name="kode_pjawab"
                                            placeholder="Masukkan Kode Pertanggung Jawaban"
                                            value="{{ request('kode_pjawab') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Kode Pencairan</label>
                                        <input type="text" class="form-control" id="kode_pencairan" name="kode_pencairan"
                                            placeholder="Masukkan Kode Pencairan" value="{{ request('kode_pencairan') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Kode Pengaju</label>
                                        <input type="text" class="form-control" id="kode-pengaju" name="kode-pengaju"
                                            placeholder="Masukkan Kode Pengaju" value="{{ request('kode-pengaju') }}">
                                    </div>
                                </div>
                                <div class="form-row justify-content-end">
                                    <button type="submit" id="btn-search" class="btn btn-sm btn-primary mr-2">
                                        <i class="fal fa-search mr-1"></i> Cari
                                    </button>
                                    <button type="button" id="btn-reset" class="btn btn-sm btn-secondary mr-2">
                                        <i class="fal fa-undo mr-1"></i> Reset
                                    </button>
                                    <a href="{{ route('keuangan.cash-advance.pertanggung-jawaban.pjawabancreta') }}"
                                        class="btn btn-sm btn-success">
                                        <i class="fal fa-plus mr-1"></i> Pertanggung Jawaban Baru
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="row mt-4">
            <div class="col-xl-12">
                <div class="panel">
                    <div class="panel-hdr">
                        <h2>List <span class="fw-300"><i>Pertanggung Jawaban</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            <table id="pj-table" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>No</th>
                                        <th style="width: 8px;"></th> {{-- Kolom untuk ikon expand/collapse --}}
                                        <th>Tanggal PJ</th>
                                        <th>Kode PJ</th>
                                        <th>Kode Pencairan</th>
                                        <th>Nama Pengaju</th>
                                        <th>Total PJ</th>
                                        <th>Sisa PJ</th>
                                        <th class="text-right">Reimburse</th>
                                        <th>User Entry</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pertanggungjawabans as $pj)
                                        <tr class="parent-row" data-pj-id="{{ $pj->id }}">
                                            {{-- MENGGUNAKAN CHEVRON-UP SEBAGAI DEFAULT (PANAH KE ATAS) --}}
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="details-control"><i class="fal fa-chevron-up"></i></td>
                                            <td>{{ \Carbon\Carbon::parse($pj->tanggal_pj)->format('d-m-Y') }}</td>
                                            <td>{{ $pj->kode_pj }}</td>
                                            <td>{{ optional($pj->pencairan)->kode_pencairan }}</td>
                                            <td>{{ optional(optional(optional($pj->pencairan)->pengajuan)->pengaju)->name }}
                                            </td>
                                            <td class="text-right">
                                                {{ 'Rp ' . number_format($pj->total_pj, 0, ',', '.') }}
                                            </td>
                                            <td class="text-right">
                                                @if ($pj->selisih < 0)
                                                    <span class="text-danger">(Reimburse
                                                        {{ 'Rp ' . number_format(abs($pj->selisih), 0, ',', '.') }})</span>
                                                @else
                                                    {{ 'Rp ' . number_format($pj->selisih, 0, ',', '.') }}
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                {{ $pj->reimburse ?? '0' }}
                                            </td>
                                            <td>{{ optional($pj->userEntry)->name }}</td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center text-muted">Belum ada data
                                                pertanggungjawaban.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            {{-- TEMPLATE UNTUK CHILD ROW (DILETAKKAN DI LUAR TABEL) --}}
                            <div id="child-row-template" style="display: none;">
                                <div class="child-row-content">
                                    <h6 class="mb-3"><strong>Rincian untuk <span
                                                class="kode-pj-placeholder">{kode_pj}</span>:</strong></h6>
                                    <table class="child-table table table-sm table-bordered">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Tipe Transaksi</th>
                                                <th>Keterangan</th>
                                                <th>Cost Center</th>
                                                <th class="text-right">Nominal</th>
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
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/formplugins/sweetalert2/sweetalert2.bundle.js"></script>
    <script src="/js/notifications/toastr/toastr.js"></script>

    <script>
        $(document).ready(function() {
            // Inisialisasi plugin dasar
            $('.select2').select2();
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });

            // Simpan data detail di variabel JavaScript
            const allDetails = {!! json_encode(
                $pertanggungjawabans->mapWithKeys(function ($pj) {
                    return [$pj->id => $pj->details];
                }),
            ) !!};

            console.log('All Details:', allDetails); // Debug log

            var table = $('#pj-table').DataTable({
                responsive: true,
                lengthChange: false,
                pageLength: 20,
                order: [
                    [1, 'desc']
                ],
                columnDefs: [{
                    orderable: false,
                    targets: [0, 9]
                }],
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [ /* Tombol export jika perlu */ ]
            });

            // ==========================================================
            // LOGIKA CHILD ROW MENGGUNAKAN API DATATABLES (SUDAH DIPERBAIKI)
            // ==========================================================
            $('#pj-table tbody').on('click', 'td.details-control', function() {
                var tr = $(this).closest('tr');
                var row = table.row(tr);

                if (row.child.isShown()) {
                    // Baris ini sudah terbuka, tutup.
                    row.child.hide();
                    tr.removeClass('dt-hasChild');
                    console.log('Child row closed');
                } else {
                    // Ambil PJ ID dari data attribute di TR
                    var pjId = tr.data('pj-id');
                    var kodePj = tr.find('td:eq(2)').text().trim(); // Kolom ke-3 (index 2) adalah Kode PJ

                    console.log('PJ ID:', pjId, 'Kode PJ:', kodePj);

                    // Ambil detail dari allDetails berdasarkan PJ ID
                    var details = allDetails[pjId] || [];

                    console.log('Details for PJ ID', pjId, ':', details);

                    // Buka baris dan format kontennya
                    row.child(formatChildRow(kodePj, details)).show();
                    tr.addClass('dt-hasChild');
                    console.log('Child row opened');
                }
            });

            // Fungsi untuk memformat child row
            function formatChildRow(kodePj, details) {
                // Ambil template dari div yang kita sembunyikan
                var template = $('#child-row-template').clone();
                template.find('.kode-pj-placeholder').text(kodePj);

                var tbody = template.find('.detail-tbody');
                tbody.empty(); // Kosongkan isi body template

                if (details && details.length > 0) {
                    details.forEach(function(detail) {
                        var tipeBadge = '';
                        var tipeText = (detail.tipe || '').toLowerCase();

                        switch (tipeText) {
                            case 'reimburse':
                                tipeBadge = 'badge-info';
                                break;
                            case 'deduct':
                                tipeBadge = 'badge-warning';
                                break;
                            default:
                                tipeBadge = 'badge-secondary';
                        }

                        var rowHtml = `
                                <tr>
                                    <td>${detail.tipe_transaksi || '-'}</td>
                                    <td>${detail.keterangan || '-'}</td>
                                    <td>${detail.cost_center || '-'}</td>
                                    <td class="text-right">${'Rp ' + new Intl.NumberFormat('id-ID').format(detail.nominal || 0)}</td>
                                </tr>
                            `;
                        tbody.append(rowHtml);
                    });
                } else {
                    tbody.append('<tr><td colspan="5" class="text-center text-muted">Tidak ada rincian.</td></tr>');
                }

                return template.html();
            }

            // Event handler untuk form pencarian
            $('#search-form').on('submit', function(e) {
                e.preventDefault();
                // Implementasi logika pencarian di sini
                console.log('Search form submitted');
            });

            // Event handler untuk tombol reset
            $('#btn-reset').on('click', function() {
                $('#search-form')[0].reset();
                $('.select2').val(null).trigger('change');
                console.log('Form reset');
            });
        });
    </script>
@endsection
