@extends('inc.layout')
@section('title', 'Approval Purchase Order (Pharmacy)')

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        {{-- Panel Form Filter --}}
        <div class="panel" id="panel-filter">
            <div class="panel-hdr">
                <h2>Filter <span class="fw-300"><i>Approval PO</i></span></h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    {{-- File partial untuk form filter --}}
                    @include('pages.simrs.procurement.approval-po.partials.pharmacy-form')
                </div>
            </div>
        </div>

        {{-- Panel Daftar Data --}}
        <div class="panel" id="panel-data">
            <div class="panel-hdr">
                <h2>Daftar <span class="fw-300"><i>Purchase Order (Pharmacy)</i></span></h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    {{-- File partial untuk tabel --}}
                    @include('pages.simrs.procurement.approval-po.partials.pharmacy-datatable')
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>

    <script>
        $(document).ready(function() {
            // Inisialisasi Select2 & DateRangePicker
            $('.select2').select2({
                width: '100%'
            });
            $('#tanggal_po_filter').daterangepicker({
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

            // --- INISIALISASI DATATABLE ---
            var table = $('#dt-approval-po-pharmacy').DataTable({
                processing: true,
                serverSide: true, // PENTING: Mengaktifkan mode server-side
                responsive: true,
                lengthChange: false,
                ajax: {
                    url: "{{ route('procurement.approval-po.pharmacy') }}",
                    // Mengirim data filter ke controller
                    data: function(d) {
                        d.tanggal_po = $('#tanggal_po_filter').val();
                        d.kode_po = $('#kode_po_filter').val();
                        d.nama_barang = $('#nama_barang_filter').val();
                        d.approval = $('#approval_filter').val();
                        d.tipe = $('#tipe_po_filter').val();
                    }
                },
                // Mendefinisikan kolom sesuai dengan data yang dikirim controller
                columns: [{
                        data: 'detail',
                        name: 'detail',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'kode_po',
                        name: 'kode_po'
                    },
                    {
                        data: 'tanggal_po',
                        name: 'tanggal_po'
                    },
                    {
                        data: 'tanggal_app',
                        name: 'tanggal_app'
                    },
                    {
                        data: 'supplier_name',
                        name: 'supplier.nama'
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan'
                    },
                    {
                        data: 'keterangan_approval',
                        name: 'keterangan_approval'
                    },
                    {
                        data: 'user_app_name',
                        name: 'app_user.employee.fullname'
                    },
                    {
                        data: 'tipe',
                        name: 'tipe'
                    },
                    {
                        data: 'nominal',
                        name: 'nominal',
                        className: 'text-right'
                    },
                    {
                        data: 'status_approval',
                        name: 'approval',
                        className: 'text-center'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [ /* Tombol Export Anda */ ]
            });

            // --- EVENT LISTENERS ---
            // Tombol filter
            $('#filter-btn').on('click', function(e) {
                e.preventDefault();
                table.draw(); // Memuat ulang data tabel dengan filter baru
            });

            // Logika Child Row (Detail)
            $('#dt-approval-po-pharmacy tbody').on('click', 'button.btn-detail', function() {
                var tr = $(this).closest('tr');
                var row = table.row(tr);
                var id = $(this).data('id');
                // Menggunakan endpoint detail yang sama dengan halaman PO
                var url = `{{ url('/simrs/procurement/purchase-order/pharmacy/detail') }}/${id}`;

                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    row.child(
                        '<tr><td colspan="12" class="text-center"><i class="fas fa-spinner fa-spin"></i> Memuat...</td></tr>'
                    ).show();
                    tr.addClass('shown');
                    $.get(url, function(data) {
                        row.child(data).show();
                    }).fail(function() {
                        row.child(
                            '<tr><td colspan="12" class="text-center text-danger">Gagal memuat detail.</td></tr>'
                        ).show();
                    });
                }
            });

            // Logika Tombol Review (membuka modal)
            // Logika Tombol Review (membuka popup window)
            $('#dt-approval-po-pharmacy tbody').on('click', '.btn-review', function() {
                var id = $(this).data('id');
                var url = `{{ url('simrs/procurement/approval-po/pharmacy/edit') }}/${id}`;

                // Definisikan ukuran dan posisi popup
                const width = Math.round(screen.width * 0.7); // 70% dari lebar layar
                const height = Math.round(screen.height * 0.8); // 80% dari tinggi layar
                const left = Math.round((screen.width - width) / 2);
                const top = Math.round((screen.height - height) / 2);

                // Buka jendela baru
                const popupWindow = window.open(
                    url,
                    "ReviewPOFarmasi_" + id,
                    `width=${width},height=${height},left=${left},top=${top},resizable=yes,scrollbars=yes`
                );

                // Fokuskan ke jendela popup jika sudah ada
                if (popupWindow && window.focus) {
                    popupWindow.focus();
                }

                // Tambahkan listener untuk merefresh DataTable saat popup ditutup
                if (popupWindow) {
                    var timer = setInterval(function() {
                        if (popupWindow.closed) {
                            clearInterval(timer);
                            // Reload datatable tanpa reset paging
                            table.ajax.reload(null, false);
                        }
                    }, 500);
                }
            });

        });
    </script>
@endsection
