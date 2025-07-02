@extends('inc.layout')
@section('title', 'Daftar Pembayaran Asuransi')
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
                                    <!-- Status Tagihan -->

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
                        <!-- Panel toolbar tetap sama -->
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            @if (session('success'))
                                <!-- Alert success tetap sama -->
                            @endif

                            <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>#</th>
                                        <th class="control-details"></th>
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
                                        <tr class="parent-row">
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="control-details text-center">
                                                <button type="button" class="btn btn-sm btn-outline-primary toggle-detail">
                                                    <i class="fas fa-chevron-down"></i>
                                                </button>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($pembayaran->tanggal)->format('d-m-Y') }}</td>
                                            <td>{{ $pembayaran->nomor_transaksi }}</td>
                                            <td>{{ $pembayaran->penjamin->nama_perusahaan ?? '-' }}</td>
                                            <td class="text-right">{{ number_format($pembayaran->jumlah, 2, ',', '.') }}
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

                                        {{-- CHILD ROW --}}
                                        <tr class="child-row" style="display: none;">
                                            <td colspan="9" class="bg-light">
                                                <div class="p-3">
                                                    <table class="child-table table table-sm table-bordered w-75 mx-auto">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th>No RM</th>
                                                                <th>Nama Pasien</th>
                                                                <th>No Reg</th>
                                                                <th>Tgl AR</th>
                                                                <th>No Invoice</th>
                                                                <th>Tagihan</th>
                                                                <th>Pelunasan</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($pembayaran->details as $detail)
                                                                @php $konfirmasi = $detail->konfirmasiasuransi; @endphp
                                                                <tr>
                                                                    <td>{{ $konfirmasi->registration->patient->medical_record_number ?? '-' }}
                                                                    </td>
                                                                    <td>{{ $konfirmasi->registration->patient->name ?? '-' }}
                                                                    </td>
                                                                    <td>{{ $konfirmasi->registration->registration_number ?? '-' }}
                                                                    </td>
                                                                    <td>{{ \Carbon\Carbon::parse($konfirmasi->tanggal)->format('d-m-Y') }}
                                                                    </td>
                                                                    <td>{{ $konfirmasi->invoice ?? '-' }}</td>
                                                                    <td class="text-right">
                                                                        {{ number_format($konfirmasi->jumlah ?? 0, 2, ',', '.') }}
                                                                    </td>
                                                                    <td class="text-right">
                                                                        {{ number_format($pembayaran->jumlah ?? 0, 2, ',', '.') }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
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
        </div>
    </main>
@endsection

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/formplugins/bootstrap-daterangepicker/bootstrap-   rangepicker.js"></script>
    <script src="/js/formplugins/inputmask/inputmask.bundle.js"></script>
    <script src="/js/formplugins/sweetalert2/sweetalert2.bundle.js"></script>
    <script src="/js/notifications/toastr/toastr.js"></script>
    <link rel="stylesheet" href="/css/notifications/toastr/toastr.css">

    <script>
        $(document).ready(function() {
            // Inisialisasi Select2
            $('.select2').select2({
                placeholder: "Pilih Penjamin",
                allowClear: true,
                width: 'resolve'
            });

            // Inisialisasi Datepicker
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                todayHighlight: true,
                autoclose: true,
                orientation: "bottom auto"
            });

            // Inisialisasi DataTable
            var table = $('#dt-basic-example').DataTable({
                responsive: true,
                processing: true,
                paging: true,
                ordering: true,
                searching: true,
                lengthChange: true,
                autoWidth: false,
                language: {
                    "sProcessing": "Memproses...",
                    "sLengthMenu": "Tampilkan _MENU_ entri",
                    "sZeroRecords": "Tidak ditemukan data yang sesuai",
                    "sInfo": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    "sInfoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                    "sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                    "oPaginate": {
                        "sFirst": "Pertama",
                        "sPrevious": "Sebelumnya",
                        "sNext": "Selanjutnya",
                        "sLast": "Terakhir"
                    }
                },
                columnDefs: [{
                    targets: 0,
                    className: 'text-center',
                    orderable: false
                }],
                order: [
                    [1, 'desc']
                ]
            });

            // Toggle untuk baris anak (child-row)
            $('#dt-basic-example tbody').on('click', 'tr.parent-row', function() {
                var tr = $(this);
                var row = table.row(tr);

                // Jika baris sudah dibuka, tutup
                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('active');
                    tr.next('tr.child-row').remove();
                } else {
                    // Tutup semua child lain sebelum buka yang ini
                    table.rows().every(function() {
                        if (this.child.isShown()) {
                            this.child.hide();
                            $(this.node()).removeClass('active');
                            $(this.node()).next('tr.child-row').remove();
                        }
                    });

                    // Buat konten child (bisa kamu sesuaikan dengan data)
                    var childHtml = `
                    <div>
                        <table class="child-table table table-bordered">
                            <thead>
                                <tr>
                                    <th>Contoh Kolom A</th>
                                    <th>Contoh Kolom B</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Data A</td>
                                    <td>Data B</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                `;

                    row.child('<tr class="child-row"><td colspan="7">' + childHtml + '</td></tr>').show();
                    tr.addClass('active');
                }
            });

            // Tooltip jika diperlukan
            $('[data-toggle="tooltip"]').tooltip();
        });

        $('#dt-basic-example').on('click', '.toggle-detail', function() {
            var $btn = $(this);
            var $icon = $btn.find('i');
            var $row = $btn.closest('tr');
            var $childRow = $row.next('.child-row');

            if ($childRow.is(':visible')) {
                $childRow.hide();
                $icon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
            } else {
                // Tutup semua dulu
                $('.child-row').hide();
                $('.toggle-detail i').removeClass('fa-chevron-up').addClass('fa-chevron-down');

                $childRow.show();
                $icon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
            }
        });
    </script>
@endsection
