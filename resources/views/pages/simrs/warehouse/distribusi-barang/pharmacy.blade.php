@extends('inc.layout')
@section('title', 'Distribusi Barang Farmasi')

@section('extended-css')
    <link rel="stylesheet" media="screen, print" href="/css/datagrid/datatables/datatables.bundle.css">
    <link rel="stylesheet" media="screen, print" href="/css/formplugins/select2/select2.bundle.css">
    <link rel="stylesheet" media="screen, print"
        href="/css/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.css">
    <style>
        table.dataTable td.details-control {
            background: url('/img/details_open.png') no-repeat center center;
            cursor: pointer;
        }

        table.dataTable tr.details td.details-control {
            background: url('/img/details_close.png') no-repeat center center;
        }

        .child-row-table {
            width: 100%;
            border-spacing: 0;
            border-collapse: collapse;
        }

        .child-row-table th,
        .child-row-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        .child-row-table th {
            background-color: #f2f2f2;
            font-weight: 500;
        }

        .datepicker {
            width: 100%;
        }
    </style>
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div class="panel">
                    <div class="panel-hdr">
                        <h2>Filter Pencarian</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            {{-- Form filter akan dimuat di sini --}}
                            @include('pages.simrs.warehouse.distribusi-barang.partials.pharmacy-form')
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12">
                <div class="panel">
                    <div class="panel-hdr">
                        <h2>Daftar Distribusi Barang (Farmasi)</h2>
                        <div class="panel-toolbar">
                            <button class="btn btn-primary btn-sm" id="tambah-btn">
                                <i class="fal fa-plus-circle"></i> Tambah Distribusi
                            </button>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            {{-- Tabel akan dimuat di sini --}}
                            @include('pages.simrs.warehouse.distribusi-barang.partials.pharmacy-datatable')
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
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>

    <script>
        // --- Global Functions for AJAX CRUD ---
        function editData(id) {
            // URL diperbarui sesuai rute Anda
            $.get('/simrs/warehouse/distribusi-barang/pharmacy/edit/' + id, function(data) {
                $('#form-modal .modal-body').html(data);
                $('#form-modal').modal('show');
            });
        }

        function printData(id) {
            // URL diperbarui sesuai rute Anda
            var url = '/simrs/warehouse/distribusi-barang/pharmacy/print/' + id;
            window.open(url, '_blank');
        }

        function deleteData(id) {
            showDeleteConfirmation(function() {
                $.ajax({
                    // URL diperbarui sesuai rute Anda
                    url: '/simrs/warehouse/distribusi-barang/pharmacy/' + id,
                    type: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.success) {
                            showSuccessAlert(response.message);
                            $('#dt-distribusi-farmasi').DataTable().ajax.reload();
                        } else {
                            showErrorAlert(response.message);
                        }
                    },
                    error: function(xhr) {
                        showErrorAlert('Terjadi kesalahan: ' + xhr.responseJSON.message);
                    }
                });
            });
        }

        $(document).ready(function() {
            // --- INITIALIZATION ---
            $('.select2').select2({
                width: '100%'
            });
            $('.datepicker').daterangepicker({
                opens: 'left',
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear',
                    format: 'YYYY-MM-DD'
                }
            }).on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format(
                    'YYYY-MM-DD'));
            }).on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });

            // --- DATATABLES & CHILD ROW LOGIC ---
            var table = $('#dt-distribusi-farmasi').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                // URL diperbarui sesuai rute Anda
                ajax: {
                    url: "{{ route('warehouse.distribusi-barang.pharmacy.index') }}",
                    type: "GET",
                    data: function(d) {
                        // Kirim data filter ke server
                        d.kode_db = $('#kode_db').val();
                        d.tanggal_db = $('#tanggal_db').val();
                        d.nama_barang = $('#nama_barang').val();
                        d.status = $('#status').val();
                        d.asal_gudang_id = $('#asal-gudang').val();
                        d.tujuan_gudang_id = $('#tujuan-gudang').val();
                    }
                },
                columns: [{
                        className: 'details-control',
                        orderable: false,
                        data: null,
                        defaultContent: ''
                    },
                    {
                        data: "kode_db"
                    },
                    {
                        data: "tanggal_db"
                    },
                    {
                        data: "asal.nama",
                        defaultContent: "-"
                    },
                    {
                        data: "tujuan.nama",
                        defaultContent: "-"
                    },
                    {
                        data: "keterangan"
                    },
                    {
                        data: "user.employee.fullname",
                        defaultContent: "-"
                    },
                    {
                        data: "status"
                    },
                    {
                        data: "sr.kode_sr",
                        defaultContent: "-"
                    },
                    {
                        data: "action",
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [1, 'desc']
                ],
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",

            });

            // Filter form submission
            $('#filter-form').on('submit', function(e) {
                e.preventDefault();
                table.ajax.reload();
            });

            // Event listener for opening and closing details
            $('#dt-distribusi-farmasi tbody').on('click', 'td.details-control', function() {
                var tr = $(this).closest('tr');
                var row = table.row(tr);
                var rowData = row.data();
                var dbId = rowData.id;

                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('details');
                } else {
                    row.child('<div><i class="fas fa-spinner fa-spin"></i> Memuat detail...</div>').show();
                    tr.addClass('details');
                    // URL diperbarui sesuai rute Anda
                    $.get('/simrs/warehouse/distribusi-barang/pharmacy/' + dbId + '/details', function(
                        response) {
                        if (row.child.isShown()) {
                            row.child(response).show();
                        }
                    }).fail(function() {
                        if (row.child.isShown()) {
                            row.child('<div>Gagal memuat detail.</div>').show();
                        }
                    });
                }
            });

            // --- AJAX for CRUD (Create Button) ---
            $('#tambah-btn').on('click', function() {
                // URL diperbarui sesuai rute Anda
                $.get("{{ route('warehouse.distribusi-barang.pharmacy.create') }}", function(data) {
                    $('#form-modal .modal-body').html(data);
                    $('#form-modal').modal('show');
                });
            });
        });
    </script>

    <script src="{{ asset('js/simrs/warehouse/distribusi-barang/pharmacy.js') }}?v={{ time() }}"></script>
@endsection
