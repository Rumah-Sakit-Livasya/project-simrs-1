@extends('inc.layout')
@section('title', 'Stock Request Farmasi')

@section('extended-css')
    <link rel="stylesheet" media="screen, print" href="/css/datagrid/datatables/datatables.bundle.css">
    <link rel="stylesheet" media="screen, print" href="/css/formplugins/select2/select2.bundle.css">
    <link rel="stylesheet" media="screen, print"
        href="/css/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.css">
    <style>
        /* ... (CSS Anda yang lain untuk child row, dll) ... */
    </style>
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            {{-- Panel Filter --}}
            <div class="col-xl-12">
                <div class="panel">
                    <div class="panel-hdr">
                        <h2>Filter Pencarian</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            @include('pages.simrs.warehouse.stock-request.partials.pharmacy-form')
                        </div>
                    </div>
                </div>
            </div>
            {{-- Panel Daftar --}}
            <div class="col-xl-12">
                <div class="panel">
                    <div class="panel-hdr">
                        <h2>Daftar Stock Request (Farmasi)</h2>
                        <div class="panel-toolbar">
                            {{-- [FIX] Tombol ini sekarang memanggil fungsi createData() yang baru --}}
                            <button class="btn btn-primary btn-sm" onclick="createData()">
                                <i class="fal fa-plus-circle"></i> Buat Stock Request
                            </button>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            @include('pages.simrs.warehouse.stock-request.partials.pharmacy-datatable')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- [FIX] HAPUS SEMUA HTML MODAL DARI SINI --}}
        {{-- Tidak ada lagi <div class="modal fade" id="form-modal" ...> --}}
    </main>
@endsection

@section('plugin')
    {{-- ... (semua JS plugin yang dibutuhkan) ... --}}
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>

    <script>
        // --- [REFACTOR] Global Functions for Popup Windows & CRUD ---

        /**
         * Membuka popup window untuk form create.
         */
        function createData() {
            const url = "{{ route('warehouse.stock-request.pharmacy.create') }}";
            const windowName = "popupWindow_addSRFarmasi";
            const windowFeatures = `width=${screen.width},height=${screen.height},scrollbars=yes,resizable=yes`;

            const popup = window.open(url, windowName, windowFeatures);
            // Menambahkan listener untuk me-refresh datatables saat popup ditutup
            const timer = setInterval(function() {
                if (popup.closed) {
                    clearInterval(timer);
                    $('#dt-sr-pharmacy').DataTable().ajax.reload();
                }
            }, 1000);
        }

        /**
         * Membuka popup window untuk form edit.
         */
        function editData(id) {
            const url = `/simrs/warehouse/stock-request/pharmacy/edit/${id}`;
            const windowName = `popupWindow_editSRFarmasi_${id}`;
            const windowFeatures = `width=${screen.width},height=${screen.height},scrollbars=yes,resizable=yes`;

            const popup = window.open(url, windowName, windowFeatures);
            // Menambahkan listener untuk me-refresh datatables saat popup ditutup
            const timer = setInterval(function() {
                if (popup.closed) {
                    clearInterval(timer);
                    $('#dt-sr-pharmacy').DataTable().ajax.reload();
                }
            }, 1000);
        }

        /**
         * Membuka tab baru untuk print.
         */
        function printData(id) {
            window.open(`/simrs/warehouse/stock-request/pharmacy/print/${id}`, '_blank');
        }

        /**
         * Menangani aksi hapus via AJAX.
         */
        function deleteData(id) {
            showDeleteConfirmation(function() {
                $.ajax({
                    url: `/simrs/warehouse/stock-request/pharmacy/${id}`,
                    type: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.success) {
                            showSuccessAlert(response.message);
                            $('#dt-sr-pharmacy').DataTable().ajax.reload();
                        } else {
                            showErrorAlert(response.message);
                        }
                    },
                    error: function(xhr) {
                        showErrorAlert('Terjadi kesalahan: ' + (xhr.responseJSON ? xhr.responseJSON
                            .message : xhr.statusText));
                    }
                });
            });
        }

        // --- Document Ready ---
        $(document).ready(function() {
            // --- Inisialisasi Plugin ---
            $('.select2').select2({
                width: '100%'
            });

            $('.datepicker').daterangepicker({
                opens: 'left',
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });

            // Kode DataTables yang sudah ada (tidak perlu diubah)
            var table = $('#dt-sr-pharmacy').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('warehouse.stock-request.pharmacy.index') }}",
                    type: "GET",
                    data: function(d) {
                        d.kode_sr = $('#kode_sr').val();
                        d.status = $('#status').val();
                        // ... tambahkan filter lain
                    }
                },
                columns: [{
                        className: 'details-control',
                        orderable: false,
                        data: null,
                        defaultContent: ''
                    },
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'kode_sr',
                        name: 'kode_sr'
                    },
                    {
                        data: 'tanggal_sr',
                        name: 'tanggal_sr'
                    },
                    {
                        data: 'asal.nama',
                        name: 'asal.nama',
                        defaultContent: '-'
                    },
                    {
                        data: 'tujuan.nama',
                        name: 'tujuan.nama',
                        defaultContent: '-'
                    },
                    {
                        data: 'tipe',
                        name: 'tipe'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [2, 'desc']
                ],
                dom: "<'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            });

            // Handle filter form submission
            $('#filter-form').on('submit', function(e) {
                e.preventDefault();
                table.ajax.reload();
            });

            // Add event listener for opening and closing details
            $('#dt-sr-pharmacy tbody').on('click', 'td.details-control', function() {
                var tr = $(this).closest('tr');
                var row = table.row(tr);
                var rowData = row.data();

                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('details');
                } else {
                    row.child('<div><i class="fas fa-spinner fa-spin"></i> Memuat detail...</div>').show();
                    tr.addClass('details');
                    $.get(`/simrs/warehouse/stock-request/pharmacy/${rowData.id}/details`, function(
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
        });
    </script>

    {{-- [FIX] HAPUS pemanggilan ke `pharmacy.js` jika semua logika sudah dipindah ke sini --}}
    {{-- <script src="{{ asset('js/simrs/warehouse/stock-request/pharmacy.js') }}?v={{ time() }}"></script> --}}
@endsection
