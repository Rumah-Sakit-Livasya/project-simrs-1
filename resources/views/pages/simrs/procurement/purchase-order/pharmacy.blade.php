@extends('inc.layout')
@section('title', 'List Purchase Order (Pharmacy)')

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Filter <span class="fw-300"><i>Purchase Order Farmasi</i></span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            @include('pages.simrs.procurement.purchase-order.partials.pharmacy-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-2" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Daftar <span class="fw-300"><i>Purchase Order (Pharmacy)</i></span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            @include('pages.simrs.procurement.purchase-order.partials.pharmacy-datatable')
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
    {{-- Moment.js diperlukan oleh daterangepicker --}}
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>

    <script>
        $(document).ready(function() {
            // Inisialisasi Select2
            $('.select2').select2({
                width: '100%'
            });

            // Inisialisasi Date Range Picker
            $('#tanggal_po_filter').daterangepicker({
                opens: 'left',
                startDate: moment().startOf('month'), // Awal bulan ini
                endDate: moment(), // Hari ini
                locale: {
                    format: 'YYYY-MM-DD',
                    cancelLabel: 'Clear'
                }
            });

            // Hapus nilai jika tombol 'Clear' diklik
            $('#tanggal_po_filter').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });

            // --- FUNGSI BANTUAN ---
            /**
             * Membuka jendela popup dengan ukuran yang responsif.
             * @param {string} url - URL yang akan dibuka.
             * @param {string} title - Judul jendela.
             */
            function openPopupWindow(url, title) {
                const width = screen.width * 0.85;
                const height = screen.height * 0.9;
                const left = (screen.width - width) / 2;
                const top = (screen.height - height) / 2;
                const popupWindow = window.open(url, title,
                    `width=${width},height=${height},left=${left},top=${top},resizable=yes,scrollbars=yes`);
                // Tambahkan listener untuk merefresh datatable saat popup ditutup
                if (popupWindow) {
                    var timer = setInterval(function() {
                        if (popupWindow.closed) {
                            clearInterval(timer);
                            table.ajax.reload(null, false); // Reload datatable
                        }
                    }, 500);
                }
            }

            // --- INISIALISASI DATATABLE ---
            var table = $('#dt-po-pharmacy').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                lengthChange: false,
                ajax: {
                    url: "{{ route('procurement.purchase-order.pharmacy') }}",
                    data: function(d) {
                        d.tanggal_po = $('#tanggal_po_filter').val();
                        d.kode_po = $('#kode_po_filter').val();
                        d.nama_barang = $('#nama_barang_filter').val();
                        d.approval = $('#status_filter').val();
                        d.is_auto = $('#tipe_input_filter').val();
                        d.tipe = $('#tipe_po_filter').val();
                    }
                },
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
                        data: 'user_entry',
                        name: 'user.employee.fullname'
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
                        name: 'status_approval',
                        orderable: false,
                        searchable: false,
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
                buttons: [{
                        extend: 'pdfHtml5',
                        text: 'PDF',
                        titleAttr: 'Generate PDF',
                        className: 'btn-outline-danger btn-sm mr-1'
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        titleAttr: 'Generate Excel',
                        className: 'btn-outline-success btn-sm mr-1'
                    },
                    {
                        extend: 'csvHtml5',
                        text: 'CSV',
                        titleAttr: 'Generate CSV',
                        className: 'btn-outline-primary btn-sm mr-1'
                    },
                    {
                        extend: 'copyHtml5',
                        text: 'Copy',
                        titleAttr: 'Copy to clipboard',
                        className: 'btn-outline-primary btn-sm mr-1'
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        titleAttr: 'Print Table',
                        className: 'btn-outline-primary btn-sm'
                    }
                ]
            });

            // --- EVENT LISTENERS ---

            // Tombol Filter
            $('#filter-btn').on('click', function(e) {
                e.preventDefault();
                table.draw();
            });

            // Tombol Tambah PO
            $('#tambah-btn').on('click', function() {
                openPopupWindow("{{ route('procurement.purchase-order.pharmacy.create') }}",
                    "TambahPOFarmasi");
            });

            // Child Row Logic dengan Animasi Loading
            $('#dt-po-pharmacy tbody').on('click', 'button.btn-detail', function() {
                var tr = $(this).closest('tr');
                var row = table.row(tr);
                var id = $(this).data('id');
                var loadingHtml =
                    '<tr><td colspan="8" class="text-center"><i class="fas fa-spinner fa-spin"></i> Memuat data...</td></tr>';

                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    // Tampilkan animasi loading segera
                    row.child(loadingHtml).show();
                    tr.addClass('shown');

                    // Ambil data detail dengan AJAX
                    $.get("/simrs/procurement/purchase-order/pharmacy/detail/" + id, function(data) {
                        // Ganti animasi loading dengan data sebenarnya
                        row.child(data).show();
                    }).fail(function() {
                        // Jika gagal, tampilkan pesan error
                        row.child(
                            '<tr><td colspan="8" class="text-center text-danger">Gagal memuat data detail.</td></tr>'
                        ).show();
                    });
                }
            });

            // Delegated events untuk tombol Aksi (Edit, Hapus, Print)
            $('#dt-po-pharmacy tbody').on('click', '.edit-btn', function() {
                var id = $(this).data('id');
                var url = "{{ url('/simrs/procurement/purchase-order/pharmacy/edit') }}/" + id;
                openPopupWindow(url, "EditPOFarmasi" + id);
            });

            $('#dt-po-pharmacy tbody').on('click', '.print-btn', function() {
                var id = $(this).data('id');
                var url = "{{ url('/simrs/procurement/purchase-order/pharmacy/print') }}/" + id;
                openPopupWindow(url, "PrintPOFarmasi" + id);
            });

            $('#dt-po-pharmacy tbody').on('click', '.delete-btn', function() {
                var id = $(this).data('id');
                var url = `{{ url('/simrs/procurement/purchase-order/pharmacy/destroy') }}/${id}`;

                // Menggunakan fungsi konfirmasi dari template
                showDeleteConfirmation(function() {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {
                            "_token": "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            if (response.success) {
                                // Menggunakan fungsi notifikasi sukses dari template
                                showSuccessAlert(response.message);
                                table.ajax.reload(null,
                                    false); // Reload tabel tanpa reset paging
                            } else {
                                showErrorAlert(response.message);
                            }
                        },
                        error: function(xhr) {
                            showErrorAlert('Terjadi kesalahan: ' + xhr.responseJSON
                                .message);
                        }
                    });
                });
            });
        });
    </script>
@endsection
