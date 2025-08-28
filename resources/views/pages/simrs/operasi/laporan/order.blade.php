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
            transform: rotate(0deg);
        }

        .details-control:hover i {
            color: #2980b9;
        }

        tr.dt-hasChild td.details-control i {
            transform: rotate(180deg);
            color: #e74c3c;
        }

        td.details-control::before {
            display: none !important;
        }

        .child-row-content {
            padding: 15px;
            background-color: #f9f9f9;
        }

        table.dataTable thead .sorting:after,
        table.dataTable thead .sorting_asc:after,
        table.dataTable thead .sorting_desc:after,
        table.dataTable thead .sorting_asc_disabled:after,
        table.dataTable thead .sorting_desc_disabled:after {
            display: none !important;
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

        #dt-basic-example tbody tr:hover {
            background-color: #f8f9fa;
        }
    </style>
    <main id="js-page-content" role="main" class="page-content">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Laporan <span class="fw-300"><i>Order Pasien Operasi</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="search-form">
                                <div class="row mb-3">
                                    <div class="col-md-6 mb-3">
                                        {{-- LABEL DIPERBAIKI --}}
                                        <label>Awal Periode tgl. Operasi</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" name="tanggal_awal"
                                                value="{{ \Carbon\Carbon::now()->format('d-m-Y') }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text fs-sm"><i class="fal fa-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        {{-- LABEL DIPERBAIKI --}}
                                        <label>Akhir Periode tgl. Operasi</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" name="tanggal_akhir"
                                                value="{{ \Carbon\Carbon::now()->format('d-m-Y') }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text fs-sm"><i class="fal fa-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label>No. RM / Nama Pasien</label>
                                        <input type="text" class="form-control" id="invoice" name="invoice"
                                            placeholder="Masukkan No. RM atau Nama Pasien" value="{{ request('invoice') }}">
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label>Ruang Operasi</label>
                                        <select class="form-control select2" id="ruangan_id" name="ruangan_id">
                                            <option value="">Pilih Ruangan</option>
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
                                            <span class="fal fa-search mr-1"></span> Cari & Cetak
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
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>

    <script>
        $(document).ready(function() {
            // Inisialisasi plugin form
            $('.select2').select2();
            $('.datepicker').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true
            });

            // Event handler untuk submit form
            $('#search-form').on('submit', function(e) {
                e.preventDefault();

                var params = $(this).serialize();
                var url = "{{ route('ok.laporan.order.print') }}?" + params;

                var width = 1200;
                var height = 800;
                var left = (screen.width - width) / 2;
                var top = (screen.height - height) / 2;

                window.open(url, 'LaporanOrderPasien',
                    'width=' + width + ', height=' + height + ', left=' + left + ', top=' + top +
                    ', resizable=yes, scrollbars=yes, status=yes'
                );
            });
        });
    </script>
@endsection
