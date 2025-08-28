    @extends('inc.layout')
    @section('title', 'Laporan Order Pasien Operasi')
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
                            <h2>Laporan <span class="fw-300"><i>Order Pasien Operasi</i></span></h2>
                        </div>
                        <div class="panel-container show">
                            <div class="panel-content">
                                <form action="{{ route('keuangan.konfirmasi-asuransi.index') }}" method="get">
                                    @csrf
                                    <div class="row mb-3">
                                        <!-- Tanggal Periode (Dari - Sampai) -->
                                        <div class="col-md-6 mb-3">
                                            <label>Awal Periode tgl.Registrasi </label>
                                            <div class="input-group">
                                                <input type="text" class="form-control datepicker" name="tanggal_awal"
                                                    value="{{ request('tanggal_awal') }}">
                                                <div class="input-group-append"><span class="input-group-text fs-sm"><i
                                                            class="fal fa-calendar"></i></span></div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label>Awal Periode tgl.Registrasi</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control datepicker" name="tanggal_akhir"
                                                    value="{{ request('tanggal_akhir') }}">
                                                <div class="input-group-append"><span class="input-group-text fs-sm"><i
                                                            class="fal fa-calendar"></i></span></div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mt-3">
                                            <label>No. RM / Nama pasien</label>
                                            <input type="text" class="form-control" id="invoice" name="invoice"
                                                placeholder="Masukkan No.invoice" value="{{ request('invoice') }}">
                                        </div>

                                        <div class="col-md-6 mt-3">
                                            <label>Ruang Operasi</label>
                                            <select class="form-control select2" id="penjamin_id" name="penjamin_id"
                                                required>
                                                <option value="">Pilih Penjamin</option>
                                                @foreach ($ruangans as $ruangan)
                                                    <option value="{{ $ruangan->id }}"
                                                        {{ request('ruangan_id') == $ruangan->id ? 'selected' : '' }}>
                                                        {{ $ruangan->ruangan }}
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
