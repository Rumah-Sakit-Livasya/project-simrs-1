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
                            <h2>Filter <span class="fw-300"><i>Laporan</i></span></h2>
                        </div>
                        <div class="panel-container show">
                            <div class="panel-content">
                                {{-- Form sekarang akan dikontrol oleh AJAX --}}
                                <form id="search-form">
                                    <div class="form-row">
                                        <div class="col-md-6 mb-3">
                                            <label>Tanggal Pencairan Awal</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control datepicker" name="tanggal_awal"
                                                    value="{{ request('tanggal_awal') }}">
                                                <div class="input-group-append"><span class="input-group-text fs-sm"><i
                                                            class="fal fa-calendar"></i></span></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label>Tanggal Pencairan Akhir</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control datepicker" name="tanggal_akhir"
                                                    value="{{ request('tanggal_akhir') }}">
                                                <div class="input-group-append"><span class="input-group-text fs-sm"><i
                                                            class="fal fa-calendar"></i></span></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label>Nama Pengaju</label>
                                            <input type="text" class="form-control" name="nama_pengaju"
                                                placeholder="Nama Pengaju" value="{{ request('nama_pengaju') }}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label>Tipe Data</label>
                                            <select class="form-control select2" name="tipe_data">
                                                <option value="">Semua</option>
                                                <option value="outstanding"
                                                    {{ request('tipe_data') == 'outstanding' ? 'selected' : '' }}>
                                                    Outstanding (Sisa)</option>
                                                <option value="reimburse"
                                                    {{ request('tipe_data') == 'reimburse' ? 'selected' : '' }}>Reimburse
                                                    (Lebih)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row justify-content-end">
                                        <button type="submit" id="btn-search" class="btn btn-sm btn-primary mr-2"><i
                                                class="fal fa-search mr-1"></i> Cari</button>
                                        <button type="button" id="btn-reset" class="btn btn-sm btn-secondary mr-2"><i
                                                class="fal fa-undo mr-1"></i> Reset</button>
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
                                <table id="pj-detail-table" class="table table-bordered table-hover table-striped w-100">
                                    <thead class="bg-primary-600">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Pengaju</th>
                                            <th>Keterangan</th>
                                            <th>Tanggal Transaksi</th>
                                            <th>Kode Pencairan</th>
                                            <th class="text-right">Nominal PJ</th>
                                            <th class="text-right">Reimburse</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- Data akan diisi oleh JavaScript --}}
                                    </tbody>
                                </table>
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
        <script>
            $(document).ready(function() {
                // Inisialisasi plugin dasar
                $('.select2').select2({
                    placeholder: "Pilih Tipe",
                    allowClear: true
                });
                $('.datepicker').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true,
                    todayHighlight: true
                });

                // Inisialisasi DataTables dengan data awal
                var table = $('#pj-detail-table').DataTable({
                    data: {!! json_encode($pertanggungjawabans) !!},
                    columns: [{
                            data: null,
                            render: function(data, type, row, meta) {
                                return meta.row + 1;
                            }
                        },
                        {
                            data: 'pencairan.pengajuan.pengaju.name',
                            defaultContent: '-'
                        },
                        {
                            data: 'pencairan.pengajuan.keterangan',
                            defaultContent: '-'
                        },
                        {
                            data: 'tanggal_pj',
                            render: function(data) {
                                return data ? moment(data).format('DD-MM-YYYY') : '-';
                            }
                        },
                        {
                            data: 'pencairan.kode_pencairan',
                            defaultContent: '-'
                        },
                        {
                            data: 'total_pj',
                            className: 'text-right',
                            render: function(data) {
                                return formatRupiah(data);
                            }
                        },
                        {
                            data: 'selisih',
                            className: 'text-right',
                            render: function(data) {
                                // Tampilkan hanya jika selisih negatif (reimburse)
                                return data < 0 ? formatRupiah(Math.abs(data)) : 'Rp 0';
                            }
                        }
                    ],
                    responsive: true,
                    pageLength: 25,
                    dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'>>",
                    buttons: [{
                            extend: 'excelHtml5',
                            text: 'Excel',
                            title: 'Laporan Detail Pertanggungjawaban',
                            className: 'btn-outline-success btn-sm mr-1'
                        },

                    ]
                });

                // Fungsi untuk format Rupiah
                function formatRupiah(number) {
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(number || 0);
                }

                // Fungsi untuk memuat ulang data tabel dengan AJAX
                function reloadTable() {
                    $('.loading-overlay').css('display', 'flex');
                    $.ajax({
                        url: "{{ route('keuangan.cash-advance.laporan.laporan-detail') }}",
                        type: "GET",
                        data: $('#search-form').serialize(),
                        success: function(response) {
                            table.clear();
                            table.rows.add(response.data);
                            table.draw();
                            $('.loading-overlay').hide();
                        },
                        error: function(xhr) {
                            console.error("Error fetching data: ", xhr);
                            alert('Gagal mengambil data dari server.');
                            $('.loading-overlay').hide();
                        }
                    });
                }

                // Event listener untuk form pencarian
                $('#search-form').on('submit', function(e) {
                    e.preventDefault();
                    reloadTable();
                });

                // Event listener untuk tombol reset
                $('#btn-reset').on('click', function() {
                    $('#search-form')[0].reset();
                    $('.select2').val(null).trigger('change');
                    reloadTable();
                });
            });
        </script>
    @endsection
