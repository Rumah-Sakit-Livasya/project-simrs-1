@extends('inc.layout')
@section('title', 'AP Non-PO')
@section('content')

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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
        <!-- Panel Pencarian -->
        <div class="row  justify-content-center">
            <div class="col-xl-10">
                <div class="panel">
                    <div class="panel-hdr">
                        <h2>Form <span class="fw-300"><i>Pencarian AP Non-PO</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="search-form" action="{{ route('keuangan.ap-non-gr.search') }}" method="POST">
                                @csrf
                                <div class="form-row">
                                    <div class="col-md-6     mb-3">

                                        <div class="form-group">
                                            <label for="tanggal_awal">Periode Akhir <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control datepicker" name="tanggal_awal"
                                                    value="{{ old('tanggal_awal', date('d-m-Y')) }}" required
                                                    autocomplete="off">
                                                <div class="input-group-append"><span class="input-group-text"><i
                                                            class="fal fa-calendar"></i></span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="tanggal_akhir">Periode Akhir <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control datepicker" name="tanggal_akhir"
                                                    value="{{ old('tanggal_akhir', date('d-m-Y')) }}" required
                                                    autocomplete="off">
                                                <div class="input-group-append"><span class="input-group-text"><i
                                                            class="fal fa-calendar"></i></span></div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Supplier</label>
                                        <select class="form-control form-control-sm select2" name="supplier_id">
                                            <option value="">Semua Supplier</option>
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}">{{ $supplier->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">


                                        <label class="form-label">No Invoice</label>
                                        <input type="text" class="form-control " name="invoice_number"
                                            placeholder="Masukkan No. Invoice">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fal fa-search mr-1"></i>
                                        Cari</button>
                                    <a href="{{ route('keuangan.ap-non-gr.create') }}"
                                        class="btn btn-sm btn-success ml-2"><i class="fal fa-plus mr-1"></i> Tambah Baru</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel Tabel Data -->
        <div class="row mt-4">
            <div class="col-xl-12">
                <div class="panel">
                    <div class="panel-hdr">
                        <h2>List <span class="fw-300"><i>AP Non-PO</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            {{-- PERUBAHAN: Ganti ID tabel agar unik --}}
                            <table id="ap-non-grn-table" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>#</th>
                                        <th>Tanggal</th>
                                        <th>Kode AP</th>
                                        <th>Supplier</th>
                                        <th>No Invoice</th>
                                        <th>Duedate</th>
                                        <th class="text-right">Nominal</th>
                                        <th>Keterangan</th>
                                        <th>User Entry</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Data awal dimuat oleh Controller, selanjutnya di-handle AJAX --}}
                                    @forelse ($apNonGrn as $ap)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $ap->tanggal_ap->format('d M Y') }}</td>
                                            <td>{{ $ap->kode_ap }}</td>
                                            <td>{{ $ap->supplier->nama ?? '-' }}</td>
                                            <td>{{ $ap->no_invoice_supplier }}</td>
                                            <td>{{ $ap->due_date->format('d M Y') }}</td>
                                            <td class="text-right">{{ number_format($ap->grand_total, 2, ',', '.') }}</td>
                                            <td>{{ $ap->notes ?? '-' }}</td>
                                            <td>{{ $ap->userEntry->name ?? '-' }}</td>
                                            <td class="text-center">
                                                {{-- PERBAIKAN: Menggunakan rute yang benar dan fungsi openPrintPopup --}}
                                                <a href="javascript:void(0);" class="btn btn-xs btn-warning me-1"
                                                    title="Cetak" data-toggle="tooltip"
                                                    onclick="openPrintPopup('{{ route('keuangan.ap-non-gr.print.invoice', $ap->id) }}')">
                                                    <i class="fal fa-print"></i>
                                                </a>
                                                <a href="{{ route('keuangan.ap-non-gr.show', $ap->id) }}"
                                                    class="btn btn-xs btn-primary" title="Detail" style="margin-left:5px;">
                                                    <i class="fal fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center">Tidak ada data untuk ditampilkan.</td>
                                        </tr>
                                    @endforelse
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
    {{-- Copy semua plugin dari AP Supplier --}}
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/notifications/toastr/toastr.js"></script>

    <script>
        // Fungsi print popup sama persis
        function openPrintPopup(url) {
            const popupWidth = 1200,
                popupHeight = 800;
            const left = (screen.width / 2) - (popupWidth / 2);
            const top = (screen.height / 2) - (popupHeight / 2);
            window.open(url, 'PrintWindow',
                `width=${popupWidth},height=${popupHeight},top=${top},left=${left},resizable=yes,scrollbars=yes,status=yes`
            );
        }

        $(document).ready(function() {
            // Inisialisasi plugin dasar
            $('.select2').select2();
            $('.datepicker').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
                orientation: "bottom left"
            });

            // Inisialisasi DataTables
            const table = $('#ap-non-grn-table').DataTable({
                responsive: true,
                lengthChange: false,
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                        extend: 'excelHtml5',
                        className: 'btn-outline-success btn-sm mr-1'
                    },
                    {
                        extend: 'print',
                        className: 'btn-outline-primary btn-sm'
                    }
                ],
                // Pastikan tidak ada ajax load di sini, kita trigger manual
            });

            // Logika AJAX untuk Form Pencarian
            $('#search-form').on('submit', function(e) {
                e.preventDefault();
                $('.loading-overlay').css('display', 'flex');

                $.ajax({
                    // PERUBAHAN: URL menunjuk ke method index
                    url: "{{ route('keuangan.ap-non-gr.index') }}",
                    type: "GET",
                    data: $(this).serialize(),
                    success: function(response) {
                        updateTable(response); // Panggil fungsi untuk membangun ulang tabel
                        $('.loading-overlay').hide();
                    },
                    error: function(xhr) {
                        toastr.error('Gagal mengambil data dari server.');
                        $('.loading-overlay').hide();
                    }
                });
            });

            // Fungsi untuk update tabel dengan data baru
            function updateTable(data) {
                const table = $('#ap-non-grn-table').DataTable();
                table.clear(); // Hapus data lama

                if (data.length > 0) {
                    $.each(data, function(index, item) {
                        // Tombol aksi
                        const actions = `
                            <td class="text-center">
                                <a href="javascript:void(0);" class="btn btn-xs btn-warning me-1" title="Cetak" data-toggle="tooltip"
                                   onclick="openPrintPopup('/keuangan/ap-non-gr/print/${item.id}')">
                                    <i class="fal fa-print"></i>
                                </a>
                                <a href="/keuangan/ap-non-gr/${item.id}" class="btn btn-xs btn-primary" title="Detail" style="margin-left:5px;">
                                    <i class="fal fa-eye"></i>
                                </a>
                            </td>`;

                        // Tambah baris baru ke DataTables
                        table.row.add([
                            index + 1,
                            moment(item.tanggal_ap).format('DD MMM YYYY'),
                            item.kode_ap,
                            item.supplier ? item.supplier.nama : '-',
                            item.no_invoice_supplier,
                            moment(item.due_date).format('DD MMM YYYY'),
                            '<div class="text-right">' + new Intl.NumberFormat('id-ID', {
                                minimumFractionDigits: 2
                            }).format(item.grand_total) + '</div>',
                            item.notes || '-',
                            item.user_entry ? item.user_entry.name : '-',
                            actions
                        ]);
                    });
                }

                table.draw(); // Gambar ulang tabel dengan data baru
                $('[data-toggle="tooltip"]').tooltip(); // Re-inisialisasi tooltip
            }

            // Inisialisasi tooltip saat halaman pertama kali dimuat
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection
