@extends('inc.layout')
@section('title', 'pencairan')
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
                        <h2>Form <span class="fw-300"><i>Pencairan Form</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="search-form">
                                {{-- Form pencarian tidak berubah signifikan, hanya hapus action & method --}}
                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label>Periode Awal</label>
                                        <div class="input-group input-grup-sm">
                                            <input type="text" class="form-control datepicker" id="tanggal_awal"
                                                name="tanggal_awal" placeholder="Pilih Tanggal Awal">
                                            <div class="input-group-append"><span class="input-group-text fs-sm"><i
                                                        class="fal fa-calendar"></i></span></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Periode Akhir</label>
                                        <div class="input-group input-grup-sm">
                                            <input type="text" class="form-control datepicker" id="tanggal_akhir"
                                                name="tanggal_akhir" placeholder="Pilih Tanggal Akhir">
                                            <div class="input-group-append"><span class="input-group-text fs-sm"><i
                                                        class="fal fa-calendar"></i></span></div>
                                        </div>
                                    </div>

                                </div>
                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label>kode pengaju</label>
                                        <input type="text" class="form-control" id="kode_pengaju" name="kode_pengaju"
                                            placeholder="Masukkan kode pengaju">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>kode pencairan</label>
                                        <input type="text" class="form-control" id="kode_pencairan" name="kode_pencairan"
                                            placeholder="Masukkan kode pencairan">
                                    </div>


                                </div>
                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label>Tipe Pengajuan</label>
                                        <select class="form-control select2" id="tipe_pengajuan" name="tipe_pengajuan">
                                            <option value="">All</option>
                                            <option value="approval">Approval</option>
                                            <option value="Tanpa_pengajuan">Tanpa Pengajuan</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Nama Pengaju</label>
                                        <input type="text" class="form-control" id="invoice_number" name="invoice_number"
                                            placeholder="Masukkan Nomor Invoice">
                                    </div>

                                </div>
                                <div class="form-row justify-content-end">
                                    <button type="submit" id="btn-search" class="btn btn-sm btn-primary mr-2">
                                        <i class="fal fa-search mr-1"></i> Cari
                                    </button>
                                    <a href="{{ route('keuangan.cash-advance.pencairan.pencairancreate') }}"
                                        class="btn btn-sm btn-success">
                                        <i class="fal fa-plus mr-1"></i> Pencairan Baru
                                    </a>
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
                <div id="panel-2" class="panel">
                    <div class="panel-hdr">
                        <h2>Daftar <span class="fw-300"><i>Pencairan</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                            @endif
                            <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Kode Pencairan</th>
                                        <th>Kode Pengajuan</th>
                                        <th>Nama Pengaju</th>
                                        <th>Nominal</th>
                                        <th>Kas/Bank</th>
                                        <th>Keterangan</th>
                                        <th>User Entry</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pencairans as $item)
                                        <tr>
                                            <td></td>
                                            <td>{{ \Carbon\Carbon::parse($item->tanggal_pencairan)->format('d-m-Y') }}</td>
                                            <td>{{ $item->kode_pencairan }}</td>
                                            <td>{{ $item->pengajuan->kode_pengajuan ?? 'N/A' }}</td>
                                            <td>{{ $item->pengajuan->pengaju->name ?? 'N/A' }}</td>
                                            <td class="text-right">
                                                {{ 'Rp ' . number_format($item->nominal_pencairan, 0, ',', '.') }}</td>
                                            <td>{{ $item->bank->name ?? 'N/A' }}</td>
                                            <td>{{ $item->keterangan }}</td>
                                            <td>{{ $item->userEntry->name ?? 'N/A' }}</td>
                                            <td class="text-center">
                                                <a href="javascript:void(0);"
                                                    onclick="printPencairan('{{ route('keuangan.cash-advance.pencairan.print', $item->id) }}')"
                                                    class="btn btn-xs btn-info" title="Cetak Bukti">
                                                    <i class="fal fa-print"></i>
                                                </a>
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
        /**
         * Fungsi ini didefinisikan di scope global (di luar document.ready)
         * agar bisa dipanggil langsung dari atribut 'onclick' di HTML.
         * @param {string} url - URL ke halaman cetak.
         */
        function printPencairan(url) {
            const width = 1040;
            const height = 800;
            const left = (screen.width / 2) - (width / 2);
            const top = (screen.height / 2) - (height / 2);
            const options = `width=${width},height=${height},top=${top},left=${left},scrollbars=yes,resizable=yes`;

            const printWindow = window.open(url, 'CetakPencairan', options);

            if (printWindow) {
                printWindow.focus();
            } else {
                alert('Popup diblokir oleh browser. Silakan izinkan popup untuk situs ini.');
            }
        }
        $(document).ready(function() {


            $(document).on('click', '.btn-print', function(e) {
                e.preventDefault();
                const url = $(this).data('url');
                printPencairan(url);
            });

            // Initialize datepickers
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

            // Validasi range tanggal
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
                allowClear: true,
                placeholder: function() {
                    return $(this).data('placeholder');
                }
            });

            // Set placeholder untuk setiap select2
            $('#dokter_id').attr('data-placeholder', 'Pilih Dokter');
            $('#status').attr('data-placeholder', 'Pilih Status');

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

            // Initialize datatable
            var table = $('#dt-basic-example').DataTable({
                responsive: true,
                lengthChange: false,
                pageLength: 11,
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                        extend: 'pdfHtml5',
                        text: '<i class="fal fa-file-pdf mr-1"></i> PDF',
                        className: 'btn-outline-danger btn-sm mr-1',
                        title: 'Daftar Pembayaran Jasa Dokter',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7] // Exclude action column
                        },
                        orientation: 'landscape'
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fal fa-file-excel mr-1"></i> Excel',
                        className: 'btn-outline-success btn-sm mr-1',
                        title: 'Daftar Pembayaran Jasa Dokter',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7] // Exclude action column
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fal fa-print mr-1"></i> Print',
                        className: 'btn-outline-primary btn-sm',
                        title: 'Daftar Pembayaran Jasa Dokter',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7] // Exclude action column
                        }
                    }
                ],
                columnDefs: [{
                        orderable: false,
                        targets: [0, 8] // Kolom nomor dan aksi tidak bisa diurutkan
                    },
                    {
                        className: 'text-right',
                        targets: [6] // Kolom nominal rata kanan
                    },
                    {
                        className: 'text-center',
                        targets: [0, 5, 7, 8] // Kolom nomor, pajak, status, dan aksi rata tengah
                    }
                ],
                order: [
                    [1, 'desc']
                ], // Urutkan berdasarkan tanggal terbaru
                language: {
                    search: "Pencarian:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    zeroRecords: "Data tidak ditemukan",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                }
            });

            table.on('order.dt draw.dt', function() {
                let i = 1;
                table.cells(null, 0, {
                    search: 'applied',
                    order: 'applied'
                }).every(function(cell) {
                    this.data(i++);
                });
            }).draw();

            // Form validation and submission
            $('form[action="{{ route('keuangan.pembayaran-jasa-dokter.index') }}"]').on('submit', function(e) {
                var tanggalAwal = $('[name="tanggal_awal"]').val();
                var tanggalAkhir = $('[name="tanggal_akhir"]').val();

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

            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // Function untuk cetak bukti pembayaran
            window.printReceipt = function(id) {
                // Implementasi cetak bukti pembayaran
                window.open('/keuangan/pembayaran-jasa-dokter/' + id + '/print', '_blank');
            };

            // Auto-hide success alert after 5 seconds
            setTimeout(function() {
                $('.alert-success').fadeOut('slow');
            }, 5000);

            // Konfirmasi delete dengan SweetAlert
            $(document).on('submit', 'form[method="POST"]', function(e) {
                if ($(this).find('input[name="_method"][value="DELETE"]').length) {
                    e.preventDefault();
                    var form = this;

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data pembayaran ini akan dihapus secara permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                }
            });


        });
    </script>
@endsection
