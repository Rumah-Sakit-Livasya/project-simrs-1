@extends('inc.layout')
@section('title', 'Tambah Pembayaran Asuransi')
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
        <form action="{{ route('keuangan.pembayaran-asuransi.store') }}" method="post" id="form-pembayaran">
            @csrf
            <!-- Panel 1: Informasi Pembayaran A/R -->
            <div class="row">
                <div class="col-xl-12">
                    <div id="panel-1" class="panel">
                        <div class="panel-hdr">
                            <h2>Pembayaran <span class="fw-300"><i>A/R</i></span></h2>
                        </div>
                        <div class="panel-container show">
                            <div class="panel-content">
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="form-group row">
                                            <label class="col-xl-4 text-center col-form-label">Periode Awal</label>
                                            <div class="col-xl-8">
                                                <div class="input-group">
                                                    <input type="text" class="form-control datepicker"
                                                        name="tanggal_awal" placeholder="Pilih tanggal awal"
                                                        value="{{ date('d-m-Y') }}" autocomplete="off" required>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text fs-xl">
                                                            <i class="fal fa-calendar"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-xl-4 text-center col-form-label">Penjamin</label>
                                            <div class="col-xl-8">
                                                <select class="form-control select2 w-100" id="penjamin_id"
                                                    style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                    name="penjamin_id" required>
                                                    <option value="">Pilih Penjamin</option>
                                                    @foreach ($penjamins as $penjamin)
                                                        <option value="{{ $penjamin->id }}">
                                                            {{ $penjamin->nama_perusahaan }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="form-group row">
                                            <label class="col-xl-4 text-center col-form-label">Periode Akhir</label>
                                            <div class="col-xl-8">
                                                <div class="input-group">
                                                    <input type="text" class="form-control datepicker"
                                                        name="tanggal_akhir" placeholder="Pilih tanggal akhir"
                                                        value="{{ date('d-m-Y') }}" autocomplete="off" required>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text fs-xl">
                                                            <i class="fal fa-calendar"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-xl-4 text-center col-form-label">No. Invoice</label>
                                            <div class="col-xl-8">
                                                <input type="text" class="form-control" id="invoice"
                                                    style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                    name="invoice">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-end mt-3">
                                    <div class="col-auto">
                                        <button type="button" class="btn bg-primary-600 mb-3" id="search-btn">
                                            <span class="fal fa-search mr-1"></span> Cari
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel 2: Penerimaan Pembayaran -->
            <div class="row">
                <div class="col-xl-12">
                    <div id="panel-2" class="panel">
                        <div class="panel-hdr">
                            <h2>Penerimaan <span class="fw-300"><i>Pembayaran</i></span></h2>
                        </div>
                        <div class="panel-container show">
                            <div class="panel-content">
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="form-group row">
                                            <label class="col-xl-4 text-center col-form-label">Cash / Bank Account</label>
                                            <div class="col-xl-8">
                                                <select class="form-control select2 w-100" id="bank_account_id"
                                                    style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                    name="bank_account_id" required>
                                                    <option value="">Pilih Bank Account</option>
                                                    <!-- Data bank account akan diisi di sini -->
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-xl-4 text-center col-form-label">Total Penerimaan</label>
                                            <div class="col-xl-8">
                                                <input type="text" class="form-control money" id="total_penerimaan"
                                                    style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                    name="total_penerimaan" value="0" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="form-group row">
                                            <label class="col-xl-4 text-center col-form-label">Tgl. Jurnal</label>
                                            <div class="col-xl-8">
                                                <div class="input-group">
                                                    <input type="text" class="form-control datepicker"
                                                        name="tanggal_jurnal" placeholder="Pilih tanggal jurnal"
                                                        value="{{ date('d-m-Y') }}" autocomplete="off" required>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text fs-xl">
                                                            <i class="fal fa-calendar"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel 3: Data Tagihan -->
            <div class="row">
                <div class="col-12">
                    <div id="panel-3" class="panel">
                        <div class="panel-hdr">
                            <h2>Data <span class="fw-300"><i>Tagihan</i></span></h2>
                        </div>
                        <div class="panel-container show">
                            <div class="panel-content">
                                <div class="table-responsive">
                                    <table id="dt-invoice-table"
                                        class="table table-bordered table-striped table-hover table-sm w-100 text-center">
                                        <thead class="bg-primary-600 align-middle">
                                            <tr>
                                                <th rowspan="2" style="width: 40px;">No</th>
                                                <th rowspan="2" style="min-width: 110px;">No. RM / Reg.</th>
                                                <th rowspan="2" style="min-width: 130px;">Nama Pasien</th>
                                                <th rowspan="2" style="min-width: 130px;">No. Inv.</th>
                                                <th rowspan="2" style="width: 100px;">Tgl Tagihan</th>
                                                <th rowspan="2" style="width: 100px;">Jatuh Tempo</th>
                                                <th rowspan="2" style="min-width: 100px;">Tagihan</th>
                                                <th rowspan="2" style="min-width: 100px;">Pelunasan</th>
                                                <th colspan="5">Due Date Period (IN DAYS)</th>
                                                <th rowspan="2" style="width: 40px;">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input"
                                                            id="check-all">
                                                        <label class="custom-control-label" for="check-all"></label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th style="width: 50px;">&le;0</th>
                                                <th style="width: 50px;">0–15</th>
                                                <th style="width: 50px;">16–30</th>
                                                <th style="width: 50px;">31–60</th>
                                                <th style="width: 50px;">&gt;60</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($query as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->no_rm ?? '-' }} / {{ $item->no_reg ?? '-' }}</td>
                                                    <td>{{ $item->nama_pasien ?? '-' }}</td>
                                                    <td>{{ $item->no_invoice ?? '-' }}</td>
                                                    <td>{{ $item->tgl_tagihan ?? '-' }}</td>
                                                    <td>{{ $item->tgl_jatuh_tempo ?? '-' }}</td>
                                                    <td>{{ number_format($item->total_tagihan, 0, ',', '.') }}</td>
                                                    <td>{{ number_format($item->pelunasan, 0, ',', '.') }}</td>
                                                    <td>{{ $item->age_0 ?? 0 }}</td>
                                                    <td>{{ $item->age_15 ?? 0 }}</td>
                                                    <td>{{ $item->age_30 ?? 0 }}</td>
                                                    <td>{{ $item->age_60 ?? 0 }}</td>
                                                    <td>{{ $item->age_gt60 ?? 0 }}</td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input row-check"
                                                                id="check-{{ $loop->index }}">
                                                            <label class="custom-control-label"
                                                                for="check-{{ $loop->index }}"></label>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="15" class="text-center">Tidak ada data tagihan
                                                        tersedia.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Tombol aksi -->
                                <div class="row justify-content-between mt-3">
                                    <div class="col-auto">
                                        <button type="button" class="btn bg-primary-600 mb-3" id="proses-btn">
                                            <span class="fal fa-cogs mr-1"></span> Proses dan Pelunasan AR
                                        </button>
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn bg-success-600 mb-3" id="save-btn">
                                            <span class="fal fa-save mr-1"></span> Simpan
                                        </button>
                                        <a href="{{ route('keuangan.pembayaran-asuransi.index') }}"
                                            class="btn bg-danger-600 mb-3">
                                            <span class="fal fa-times mr-1"></span> Batal
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>
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
            // Initialize datepicker
            $('.datepicker').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true
            });

            // Initialize select2
            $('.select2').select2({
                placeholder: "Pilih",
                allowClear: true
            });

            // Initialize DataTable
            var table = $('#dt-invoice-table').DataTable({
                responsive: true,
                language: {
                    emptyTable: "Tidak ada data tagihan yang tersedia",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
                    infoFiltered: "(disaring dari _MAX_ total entri)",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    zeroRecords: "Tidak ditemukan data yang sesuai",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                },
                columns: [{
                        data: null,
                        defaultContent: '',
                        orderable: false,
                        className: 'text-center'
                    },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'no_rm'
                    },
                    {
                        data: 'nama_pasien'
                    },
                    {
                        data: 'no_invoice'
                    },
                    {
                        data: 'tgl_tagihan'
                    },
                    {
                        data: 'tgl_jatuh_tempo'
                    },
                    {
                        data: 'total_tagihan',
                        render: function(data) {
                            return 'Rp ' + parseFloat(data).toLocaleString('id-ID');
                        }
                    },
                    {
                        data: 'pelunasan',
                        render: function(data) {
                            return 'Rp ' + parseFloat(data).toLocaleString('id-ID');
                        }
                    },
                    {
                        data: 'due_date_days'
                    }
                ],
                columnDefs: [{
                    targets: 0,
                    checkboxes: {
                        selectRow: true
                    }
                }],
                select: {
                    style: 'multi'
                },
                order: [
                    [1, 'asc']
                ]
            });

            // Handle check all
            $('#check-all').on('click', function() {
                var rows = table.rows({
                    'search': 'applied'
                }).nodes();
                $('input[type="checkbox"]', rows).prop('checked', this.checked);
            });

            // Calculate total when checkbox is clicked
            $('#dt-invoice-table tbody').on('change', 'input[type="checkbox"]', function() {
                calculateTotal();
            });

            // Function to calculate total
            function calculateTotal() {
                var total = 0;
                table.$('input[type="checkbox"]:checked').each(function() {
                    var data = table.row($(this).closest('tr')).data();
                    if (data) {
                        total += parseFloat(data.pelunasan);
                    }
                });
                $('#total_penerimaan').val('Rp ' + total.toLocaleString('id-ID'));
            }

            // Search button click handler
            $('#search-btn').on('click', function() {
                var penjaminId = $('#penjamin_id').val();
                var tanggalAwal = $('input[name="tanggal_awal"]').val();
                var tanggalAkhir = $('input[name="tanggal_akhir"]').val();
                var invoice = $('#invoice').val();

                // Show loading
                $('.loading-overlay').show();

                // AJAX request to get data
                $.ajax({
                    type: 'GET',
                    data: {
                        penjamin_id: penjaminId,
                        tanggal_awal: tanggalAwal,
                        tanggal_akhir: tanggalAkhir,
                        invoice: invoice
                    },
                    success: function(response) {
                        table.clear().rows.add(response.data).draw();
                        $('.loading-overlay').hide();
                    },
                    error: function(xhr) {
                        toastr.error('Terjadi kesalahan saat mengambil data');
                        $('.loading-overlay').hide();
                    }
                });
            });

            // Proses button click handler
            $('#proses-btn').on('click', function() {
                var selected = [];
                table.$('input[type="checkbox"]:checked').each(function() {
                    var data = table.row($(this).closest('tr')).data();
                    if (data) {
                        selected.push(data.id);
                    }
                });

                if (selected.length === 0) {
                    toastr.warning('Pilih minimal satu tagihan untuk diproses');
                    return;
                }

                // Process selected items
                // You can add your logic here
            });
        });
    </script>

@endsection
