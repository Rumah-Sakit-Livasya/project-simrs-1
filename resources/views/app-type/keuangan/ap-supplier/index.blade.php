@extends('inc.layout')
@section('title', 'AP Supplier')
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
                        <h2>Form <span class="fw-300"><i>Pencarian</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="search-form">
                                {{-- Form pencarian tidak berubah signifikan, hanya hapus action & method --}}
                                <div class="form-row">
                                    <div class="col-md-4 mb-3">
                                        <label>Periode Awal</label>
                                        <div class="input-group input-grup-sm">
                                            <input type="text" class="form-control datepicker" id="tanggal_awal"
                                                name="tanggal_awal" placeholder="Pilih Tanggal Awal">
                                            <div class="input-group-append"><span class="input-group-text fs-sm"><i
                                                        class="fal fa-calendar"></i></span></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label>Periode Akhir</label>
                                        <div class="input-group input-grup-sm">
                                            <input type="text" class="form-control datepicker" id="tanggal_akhir"
                                                name="tanggal_akhir" placeholder="Pilih Tanggal Akhir">
                                            <div class="input-group-append"><span class="input-group-text fs-sm"><i
                                                        class="fal fa-calendar"></i></span></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label>Supplier</label>
                                        <select class="form-control select2" id="supplier_id" name="supplier_id">
                                            <option value="">Semua Supplier</option>
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}">{{ $supplier->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-4 mb-3">
                                        <label>Nomor PO</label>
                                        <input type="text" class="form-control" id="po_number" name="po_number"
                                            placeholder="Masukkan Nomor PO">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label>No Invoice</label>
                                        <input type="text" class="form-control" id="invoice_number" name="invoice_number"
                                            placeholder="Masukkan Nomor Invoice">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label>GRN</label>
                                        <input type="text" class="form-control" id="grn_number" name="grn_number"
                                            placeholder="Masukkan Nomor GRN">
                                    </div>
                                </div>
                                <div class="form-row justify-content-end">
                                    <button type="submit" id="btn-search" class="btn btn-sm btn-primary mr-2">
                                        <i class="fal fa-search mr-1"></i> Cari
                                    </button>
                                    <a href="{{ route('keuangan.ap-supplier.partials.create') }}"
                                        class="btn btn-sm btn-success">
                                        <i class="fal fa-plus mr-1"></i> Tambah Baru
                                    </a>
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
                        <h2>List <span class="fw-300"><i>AP Supplier</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="ap-supplier-table" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>#</th>
                                        <th>Tanggal</th>
                                        <th>Kode AP</th>
                                        <th>Supplier</th>
                                        <th>PO</th>
                                        <th>No Invoice</th>
                                        <th>Duedate</th>
                                        <th class="text-right">Nominal</th>
                                        <th>Keterangan</th>
                                        <th>User Entry</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- ===================== PERUBAHAN BESAR ===================== --}}
                                    {{-- Tampilkan data awal dari controller menggunakan @forelse --}}
                                    @forelse($ap_suppliers as $item)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->tanggal_ap)->format('d M Y') }}</td>
                                            <td>{{ $item->kode_ap }}</td>
                                            <td>{{ $item->supplier->nama ?? 'N/A' }}</td>
                                            <td>
                                                @php
                                                    // Menggunakan Laravel Collection untuk mengolah data relasi secara efisien
                                                    $po_numbers = $item->details
                                                        ->map(function ($detail) {
                                                            // Mengambil 'kode_po' dari relasi bertingkat
                                                            return optional(
                                                                optional($detail->penerimaanBarang)->po,
                                                            )->kode_po;
                                                        })
                                                        ->filter() // Menghapus nilai null jika ada relasi yang putus
                                                        ->unique() // Hanya menampilkan nomor PO yang unik
                                                        ->implode('<br>'); // Menggabungkan beberapa PO dengan baris baru
                                                @endphp

                                                {{-- Menampilkan hasilnya, atau 'NON' jika kosong --}}
                                                {!! $po_numbers ?: 'NON PO' !!}
                                            </td>



                                            <td>{{ $item->no_invoice_supplier }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->due_date)->format('d M Y') }}</td>
                                            <td class="text-right">{{ number_format($item->grand_total, 2, ',', '.') }}</td>
                                            <td>{{ $item->notes ?? '-' }}</td>
                                            <td>{{ $item->userEntry->name ?? 'N/A' }}</td>
                                            <td class="text-center  d-flex">
                                                {{-- Tombol Print (warna kuning) --}}
                                                <a href="javascript:void(0);" class="btn btn-xs btn-warning me-1"
                                                    title="Print Tukar Faktur" data-toggle="tooltip"
                                                    onclick="openPrintPopup('{{ route('keuangan.ap-supplier.print.invoice', $item->id) }}')">
                                                    <i class="fal fa-print"></i>
                                                </a>

                                                {{-- Tombol Detail (warna biru) --}}
                                                <a href="{{ route('keuangan.ap-supplier.show', $item->id) }}"
                                                    class="btn btn-xs btn-info " style="margin-left:5px;" title="Detail"
                                                    data-toggle="tooltip">
                                                    <i class="fal fa-eye"></i>
                                                </a>
                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center text-muted">Tidak ada data untuk
                                                ditampilkan. Silakan gunakan form pencarian.</td>
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
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/formplugins/sweetalert2/sweetalert2.bundle.js"></script>
    <script src="/js/notifications/toastr/toastr.js"></script>

    <script>
        // Definisikan fungsi openPrintPopup di global scope agar bisa diakses dari onclick
        function openPrintPopup(url) {
            const popupWidth = 1600;
            const popupHeight = 1200;
            const left = (screen.width / 2) - (popupWidth / 2);
            const top = (screen.height / 2) - (popupHeight / 2);
            const windowFeatures =
                `width=${popupWidth},height=${popupHeight},top=${top},left=${left},resizable=yes,scrollbars=yes,status=yes`;

            const printWindow = window.open(url, 'PrintWindow', windowFeatures);
            if (window.focus) {
                printWindow.focus();
            }
        }

        $(document).ready(function() {
            // Inisialisasi plugin dasar
            $('.select2').select2({
                placeholder: "Pilih opsi",
                allowClear: true
            });

            $('.datepicker').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
                orientation: "bottom left"
            });

            // Inisialisasi DataTables untuk fitur export, search, dll. TAPI TANPA server-side
            $('#ap-supplier-table').DataTable({
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
                ]
            });

            // =========================================================================
            // PERUBAHAN BESAR: Logika AJAX untuk Form Pencarian
            // =========================================================================
            $('#search-form').on('submit', function(e) {
                e.preventDefault();
                $('.loading-overlay').css('display', 'flex'); // Tampilkan loading

                $.ajax({
                    url: "{{ route('keuangan.ap-supplier.index') }}",
                    type: "GET",
                    data: $(this).serialize(), // Kirim semua data form
                    success: function(response) {
                        // Panggil fungsi untuk membangun ulang tabel
                        updateTable(response);
                        $('.loading-overlay').hide(); // Sembunyikan loading
                    },
                    error: function(xhr) {
                        console.log("Error fetching data: ", xhr);
                        toastr.error('Gagal mengambil data dari server.');
                        $('.loading-overlay').hide(); // Sembunyikan loading
                    }
                });
            });

            function updateTable(data) {
                var table = $('#ap-supplier-table').DataTable();
                table.clear(); // Hapus data lama

                if (data.length === 0) {
                    // Biarkan kosong, DataTables akan menampilkan pesan default
                } else {
                    $.each(data, function(index, item) {

                        // ================================================================
                        //                     PERBAIKAN UTAMA DI SINI
                        // ================================================================
                        // Proses data PO dengan rantai objek yang BENAR
                        var po_numbers = item.details.map(function(detail) {
                                // Pastikan setiap langkah dalam rantai objek ada sebelum mengaksesnya
                                if (detail.penerimaan_barang && detail.penerimaan_barang.po) {
                                    return detail.penerimaan_barang.po.kode_po;
                                }
                                return null;
                            })
                            .filter(Boolean) // Hapus nilai null
                            .filter((v, i, a) => a.indexOf(v) === i) // Ambil nilai unik
                            .join('<br>') || '-'; // Gabungkan atau beri tanda strip

                        // Tambah baris baru ke DataTables
                        table.row.add([
                            index + 1,
                            moment(item.tanggal_ap).format('DD MMM YYYY'),
                            item.kode_ap,
                            item.supplier ? item.supplier.nama : 'N/A',
                            po_numbers, // Gunakan variabel yang sudah diproses dengan benar
                            item.no_invoice_supplier,
                            moment(item.due_date).format('DD MMM YYYY'),
                            '<div class="text-right">' + new Intl.NumberFormat('id-ID', {
                                style: 'decimal',
                                minimumFractionDigits: 2
                            }).format(item.grand_total) + '</div>',
                            item.notes || '-',
                            item.user_entry ? item.user_entry.name : 'N/A',
                            `<div class="text-center">
                    <button type="button"
                        class="btn btn-xs btn-icon btn-outline-info js-btn-print"
                        title="Print Tukar Faktur"
                        data-toggle="tooltip"
                        data-url="/keuangan/ap-supplier/${item.id}/print/invoice">
                        <i class='bx bx-printer'></i>
                    </button>
                    <a href="/keuangan/ap-supplier/${item.id}" class="btn btn-xs btn-primary" title="Detail" data-toggle="tooltip">
                        <i class="fal fa-eye"></i>
                    </a>
                </div>`
                        ]);
                    });
                }

                table.draw(); // Gambar ulang tabel dengan data baru

                // Inisialisasi tooltip untuk elemen baru
                $('[data-toggle="tooltip"]').tooltip();
            }

            // Event delegation untuk tombol print yang dibuat secara dinamis
            $('#ap-supplier-table').on('click', '.js-btn-print', function(e) {
                e.preventDefault();
                const printUrl = $(this).data('url');

                if (printUrl) {
                    openPrintPopup(printUrl);
                } else {
                    console.log('URL untuk print tidak ditemukan pada tombol.');
                    toastr.error('URL print tidak ditemukan.');
                }
            });

            // Logika untuk tombol hapus menggunakan event delegation
            $('#ap-supplier-table').on('click', '.delete-btn', function(e) {
                e.preventDefault();
                const deleteUrl = $(this).data('url');
                const row = $(this).closest('tr');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: deleteUrl,
                            type: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    toastr.success('Data berhasil dihapus.');
                                    // Refresh tabel dengan trigger search lagi
                                    $('#search-form').trigger('submit');
                                } else {
                                    toastr.error('Gagal menghapus data.');
                                }
                            },
                            error: function(xhr) {
                                console.log('Error deleting data:', xhr);
                                toastr.error('Terjadi kesalahan saat menghapus data.');
                            }
                        });
                    }
                });
            });

            // Inisialisasi tooltip untuk elemen yang sudah ada saat halaman dimuat
            $('[data-toggle="tooltip"]').tooltip();

            // Loading overlay styling
            if ($('.loading-overlay').length === 0) {
                $('body').append(`
            <div class="loading-overlay" style="
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
            ">
                <div class="loading-spinner" style="
                    background: white;
                    padding: 20px;
                    border-radius: 5px;
                    text-align: center;
                ">
                    <i class="fa fa-spinner fa-spin"></i> Memuat...
                </div>
            </div>
        `);
            }




        });
    </script>
@endsection
