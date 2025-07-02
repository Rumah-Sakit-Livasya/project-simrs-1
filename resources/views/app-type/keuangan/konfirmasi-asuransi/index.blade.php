@extends('inc.layout')
@section('title', 'Daftar Konfirmasi Asuransi')
@section('content')
    <style>
        table {
            font-size: 8pt !important;
        }

        .badge-waiting {
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

        /* PENTING: Tambahkan CSS ini jika belum ada untuk memastikan toggle berfungsi */
        .child-row {
            display: none;
            /* Sembunyikan secara default */
        }

        .dropdown-icon {
            font-size: 14px;
            transition: transform 0.3s ease;
            display: inline-block;
        }

        .dropdown-icon.bxs-down-arrow {
            transform: rotate(180deg);
        }

        /* Styling tambahan untuk memperjelas batas row */
        .child-row td {
            background-color: #f9f9f9;
            border-bottom: 2px solid #ddd;
        }

        /* Pastikan table di dalam child row memiliki margin dan padding yang tepat */
        .child-row td>div {
            padding: 15px;
            margin: 0;
        }

        /* Pastikan parent dan child row terhubung secara visual */
        tr.parent-row.active {
            border-bottom: none !important;
        }

        /* Tambahkan di bagian style */
        .control-details {
            cursor: pointer;
            text-align: center;
            width: 30px;
        }

        .control-details .dropdown-icon {
            font-size: 18px;
            transition: transform 0.3s ease, color 0.3s ease;
            display: inline-block;
            color: #3498db;
            /* Warna biru */
        }

        .control-details .dropdown-icon.bxs-up-arrow {
            transform: rotate(180deg);
            color: #e74c3c;
            /* Warna merah saat terbuka */
        }

        .control-details:hover .dropdown-icon {
            color: #2980b9;
            /* Warna biru lebih gelap saat hover */
        }

        /* Sembunyikan ikon sort bawaan DataTables */
        table.dataTable thead .sorting:after,
        table.dataTable thead .sorting_asc:after,
        table.dataTable thead .sorting_desc:after,
        table.dataTable thead .sorting_asc_disabled:after,
        table.dataTable thead .sorting_desc_disabled:after {
            display: none !important;
        }

        /* Styling untuk child row */
        /* Pastikan content di child row tidak overflow */
        .child-row td>div {
            padding: 15px;
            width: 100%;
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

        /* Animasi untuk transisi smooth */
        .child-row {
            transition: all 0.3s ease;
        }

        .child-row.show {
            opacity: 1;
        }

        td.control-details::before {
            display: none !important;
        }

        /* Efek hover untuk row */
        #dt-basic-example tbody tr.parent-row:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }

        /* Warna berbeda untuk child row */
        #dt-basic-example tbody tr.child-row:hover {
            background-color: #f1f1f1;
        }
    </style>
    <main id="js-page-content" role="main" class="page-content">
        <!-- Search Panel -->
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Daftar <span class="fw-300"><i>Konfirmasi A/R</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="{{ route('keuangan.konfirmasi-asuransi.index') }}" method="get">
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
                                    <!-- Status Tagihan -->

                                </div>




                                <div class="row justify-content-end mt-3">
                                    <div class="col-auto">
                                        <button type="submit" class="btn bg-primary-600 mb-3">
                                            <span class="fal fa-search mr-1"></span> Cari
                                        </button>
                                        <a href="{{ route('keuangan.konfirmasi-asuransi.create') }}"
                                            class="btn bg-primary-600 mb-3" id="create-btn">
                                            <span class="fal fa-plus mr-1"></span> Tambah A/R
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
                        <h2>Daftar <span class="fw-300"><i>Konfirmasi A/R</i></span></h2>
                        <div class="panel-toolbar">
                            @if (request('tanggal_awal') || request('tanggal_akhir') || request('penjamin_id') || request('invoice'))
                                <span class="badge bg-primary-600 badge-info p-2">
                                    Filter Aktif:
                                    @if (request('tanggal_awal') && request('tanggal_akhir'))
                                        Periode: {{ request('tanggal_awal') }} s/d {{ request('tanggal_akhir') }}
                                    @endif
                                    @if (request('penjamin_id'))
                                        @php
                                            $selectedPenjamin = $penjamins->firstWhere('id', request('penjamin_id'));
                                        @endphp
                                        {{ request('tanggal_awal') ? ' | ' : '' }}
                                        Penjamin: {{ $selectedPenjamin ? $selectedPenjamin->nama_perusahaan : '' }}
                                    @endif
                                    @if (request('invoice'))
                                        {{ request('tanggal_awal') || request('penjamin_id') ? ' | ' : '' }}
                                        Invoice: {{ request('invoice') }}
                                    @endif
                                </span>
                            @endif
                        </div>
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
                                        <th>Detail</th>
                                        <th>Tgl. AR</th>
                                        <th>No. Invoice</th>
                                        <th>Penjamin</th>
                                        <th>Jumlah</th>
                                        <th>Discount</th>
                                        <th>Keterangan</th>
                                        <th>fungsi</th>
                                    </tr>
                                </thead>
                                <!-- Pastikan struktur HTML memiliki format yang benar: parent row diikuti langsung oleh child row -->
                                <tbody>
                                    @foreach ($konfirmasiAsuransi as $konfirmasi)
                                        <tr class="parent-row" data-id="{{ $konfirmasi->id }}">
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="control-details">
                                                <button type="button" class="btn btn-sm btn-outline-primary toggle-detail">
                                                    <i class="fas fa-chevron-down"></i>
                                                </button>
                                            </td>
                                            <td>{{ $konfirmasi->tanggal }}</td>
                                            <td>{{ $konfirmasi->invoice }}</td>
                                            <td>{{ $konfirmasi->penjamin->nama_perusahaan }}</td>
                                            <td class="text-right">
                                                {{ 'Rp ' . number_format($konfirmasi->jumlah ?? 0, 2, ',', '.') }}
                                            </td>
                                            <td class="text-right">{{ number_format($konfirmasi->discount, 0, ',', '.') }}
                                            </td>
                                            <td>{{ $konfirmasi->keterangan }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('cetak-klaim', $konfirmasi->id) }}"
                                                    class="btn btn-xs btn-primary" target="_blank" data-toggle="tooltip"
                                                    title="Cetak Klaim">
                                                    <i class="fal fa-print"></i>
                                                </a>

                                                <a href="{{ route('cetak-klaim-kwitansi', $konfirmasi->id) }}"
                                                    class="btn btn-xs btn-info" target="_blank" data-toggle="tooltip"
                                                    title="Cetak Kwitansi Klaim">
                                                    <i class="fa fa-file" aria-hidden="true"></i>
                                                </a>

                                                <a href="{{ route('cetak-rekap', $konfirmasi->id) }}"
                                                    class="btn btn-xs btn-success" target="_blank" data-toggle="tooltip"
                                                    title="Cetak Rekap">
                                                    <i class="fal fa-file-alt"></i>
                                                </a>

                                                <form
                                                    action="{{ route('keuangan.konfirmasi-asuransi.destroy', $konfirmasi->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus data konfirmasi ini?')"
                                                    style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>

                                            </td>
                                        </tr>
                                        <tr class="child-row text-center" data-parent="{{ $konfirmasi->id }}">
                                            <td colspan="9">
                                                <div>
                                                    <table class="table table-sm table-bordered bg-light child-table">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th>No. RM</th>
                                                                <th>Nama Pasien</th>
                                                                <th>No. Registrasi</th>
                                                                <th>Bill No</th>
                                                                <th>Tanggal Keluar</th>
                                                                <th>Tagihan</th>
                                                                <th>Fungsi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if ($konfirmasi->registration && $konfirmasi->registration->patient)
                                                                <tr>
                                                                    <td>{{ $konfirmasi->registration->patient->medical_record_number ?? '-' }}
                                                                    </td>
                                                                    <td>{{ $konfirmasi->registration->patient->name ?? '-' }}
                                                                    </td>
                                                                    <td>{{ $konfirmasi->registration->registration_number ?? '-' }}
                                                                    </td>
                                                                    <td>{{ $konfirmasi->registration->bill_no ?? '-' }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $konfirmasi->registration->registration_close_date
                                                                            ? \Carbon\Carbon::parse($konfirmasi->registration->registration_close_date)->translatedFormat('d F Y')
                                                                            : '-' }}
                                                                    </td>

                                                                    <td class="text-right">
                                                                        {{ 'Rp ' . number_format($konfirmasi->jumlah ?? 0, 2, ',', '.') }}
                                                                    </td>

                                                                    <td>
                                                                        <a href="{{ route('cetak-klaim', $konfirmasi->id) }}"
                                                                            class="btn btn-xs btn-primary" target="_blank"
                                                                            data-toggle="tooltip" title="Cetak Klaim">
                                                                            <i class="fal fa-print"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            @else
                                                                <tr>
                                                                    <td colspan="7" class="text-center">Tidak ada data
                                                                        pasien</td>
                                                                </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->

    </main>
@endsection

@section('plugin')
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
            // Hide all child rows initially
            $('.child-row').hide();

            // Initialize datepickers
            // Ganti inisialisasi datepicker dengan yang lebih baik
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                clearBtn: true,
                language: 'id',
                orientation: 'bottom auto',
                templates: {
                    leftArrow: '<i class="fal fa-angle-left"></i>',
                    rightArrow: '<i class="fal fa-angle-right"></i>'
                }
            });

            // Tambahkan validasi range tanggal
            $('form').on('submit', function(e) {
                var startDate = $('[name="tanggal_awal"]').val();
                var endDate = $('[name="tanggal_akhir"]').val();

                if (startDate && endDate) {
                    var start = new Date(startDate);
                    var end = new Date(endDate);

                    if (start > end) {
                        e.preventDefault();
                        toastr.error('Tanggal akhir harus lebih besar atau sama dengan tanggal awal');
                        return false;
                    }
                }

                return true;
            });

            // Initialize select2
            $('.select2').select2({
                dropdownCssClass: "move-up",
                placeholder: "Pilih Penjamin",
                allowClear: true
            });

            // Initialize money format
            $('.money').inputmask({
                alias: 'numeric',
                groupSeparator: '.',
                autoGroup: true,
                digits: 0,
                digitsOptional: false,
                prefix: 'Rp ',
                placeholder: '0',
                rightAlign: false
            });

            // PERBAIKAN: Event handler untuk toggle detail sebelum inisialisasi DataTable
            $(document).on('click', 'td.control-details', function() {
                var tr = $(this).closest('tr.parent-row');
                var childRow = tr.next('tr.child-row');
                var icon = $(this).find('.dropdown-icon');

                childRow.slideToggle(200, function() {
                    if (childRow.is(':visible')) {
                        icon.removeClass('bxs-down-arrow').addClass('bxs-up-arrow');
                    } else {
                        icon.removeClass('bxs-up-arrow').addClass('bxs-down-arrow');
                    }
                });
            });

            // Initialize datatable
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
                        title: 'Daftar Konfirmasi Asuransi',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7]
                        },
                        orientation: 'landscape'
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fal fa-file-excel mr-1"></i> Excel',
                        className: 'btn-outline-success btn-sm mr-1',
                        title: 'Daftar Konfirmasi Asuransi',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7]
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fal fa-print mr-1"></i> Print',
                        className: 'btn-outline-primary btn-sm',
                        title: 'Daftar Konfirmasi Asuransi',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7]
                        }
                    }
                ],
                columnDefs: [{
                        orderable: false,
                        targets: [0, 8] // Kolom expand dan aksi tidak bisa diurutkan
                    },
                    {
                        className: 'text-right',
                        targets: [5, 6] // Kolom jumlah dan discount rata kanan
                    },
                    {
                        className: 'text-center',
                        targets: [0, 1] // Kolom expand dan nomor rata tengah
                    }
                ],
                "drawCallback": function(settings) {
                    // Re-hide child rows after DataTable redraws
                    $('.child-row').hide();
                    // Reset icons
                    $('.dropdown-icon').removeClass('bx-chevron-up').addClass('bx-chevron-down');
                }
            });

            // PERBAIKAN: Tambahkan event handler untuk redraw setelah perubahan halaman/pencarian
            table.on('draw', function() {
                // Pastikan semua child rows disembunyikan setelah redraw
                $('.child-row').hide();
                $('.dropdown-icon').removeClass('bx-chevron-up').addClass('bx-chevron-down');
            });

            // Form validation and submission
            $('form[action="{{ route('keuangan.konfirmasi-asuransi.index') }}"]').on('submit', function(e) {
                var tanggalAwal = $('#datepicker-awal').val();
                var tanggalAkhir = $('#datepicker-akhir').val();

                if (tanggalAwal && tanggalAkhir) {
                    var startDate = new Date(tanggalAwal);
                    var endDate = new Date(tanggalAkhir);

                    if (startDate > endDate) {
                        e.preventDefault();
                        toastr.error('Tanggal awal tidak boleh lebih besar dari tanggal akhir');
                        return false;
                    }
                }

                $('#panel-1 .panel-container').append(
                    '<div class="panel-loading"><i class="fal fa-spinner-third fa-spin-4x fs-xl"></i></div>'
                );
                return true;
            });


            // Delete confirmation handler



            // Helper functions dan event handler lainnya tetap seperti sebelumnya
            // ...
        });
    </script>
@endsection
