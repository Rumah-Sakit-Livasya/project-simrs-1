@extends('inc.layout')
@section('title', 'AP Supplier')
@section('content')
    <style>
        .form-control {
            border: 0;
            border-bottom: 1.9px solid #eaeaea;
            border-radius: 0;
            padding-left: 0;
            padding-right: 0;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #eaeaea;
        }

        .select2-selection {
            border: 0 !important;
            border-bottom: 1.9px solid #eaeaea !important;
            border-radius: 0 !important;
        }

        table {
            font-size: 8pt !important;
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.7);
            z-index: 9999;
            display: none;
            justify-content: center;
            align-items: center;
        }

        .loading-spinner {
            color: #333;
            font-size: 2rem;
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
                                            <td>{{ \Carbon\Carbon::parse($item->tanggal_ap)->format('d M Y') }}</td>
                                            <td>{{ $item->kode_ap }}</td>
                                            <td>{{ $item->supplier->nama ?? 'N/A' }}</td>
                                            <td>
                                                {!! $item->details->map(function ($detail) {
                                                        return optional(optional($detail->penerimaanBarang)->purchasable)->no_po;
                                                    })->filter()->unique()->implode('<br>') ?:
                                                    '-' !!}
                                            </td>
                                            <td>{{ $item->no_invoice_supplier }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->due_date)->format('d M Y') }}</td>
                                            <td class="text-right">{{ number_format($item->grand_total, 2, ',', '.') }}</td>
                                            <td>{{ $item->notes ?? '-' }}</td>
                                            <td>{{ $item->userEntry->name ?? 'N/A' }}</td>
                                            <td class="text-center">
                                                <a href="#" class="btn btn-xs btn-success" title="Print"><i
                                                        class="fal fa-print"></i></a>
                                                <a href="{{ route('ap-supplier.edit', $item->id) }}"
                                                    class="btn btn-xs btn-primary" title="Edit"><i
                                                        class="fal fa-edit"></i></a>
                                                <button type="button" class="btn btn-xs btn-danger delete-btn"
                                                    data-url="{{ route('ap-supplier.destroy', $item->id) }}"
                                                    title="Delete"><i class="fal fa-trash"></i></button>
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
    {{-- Script plugin tidak perlu diubah --}}
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/formplugins/sweetalert2/sweetalert2.bundle.js"></script>
    <script src="/js/notifications/toastr/toastr.js"></script>

    <script>
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
                        console.error("Error fetching data: ", xhr);
                        toastr.error('Gagal mengambil data dari server.');
                        $('.loading-overlay').hide(); // Sembunyikan loading
                    }
                });
            });

            function updateTable(data) {
                var table = $('#ap-supplier-table').DataTable();
                table.clear(); // Hapus data lama dari DataTables

                if (data.length === 0) {
                    // Jika tidak ada data, DataTables akan otomatis menampilkan pesan "No data available in table"
                } else {
                    $.each(data, function(index, item) {
                        // Proses data PO
                        var po_numbers = item.details.map(function(detail) {
                            return detail.penerimaan_barang && detail.penerimaan_barang
                                .purchasable ? detail.penerimaan_barang.purchasable.no_po : null;
                        }).filter(Boolean).join('<br>') || '-';

                        // Tambah baris baru ke DataTables
                        table.row.add([
                            moment(item.tanggal_ap).format('DD MMM YYYY'),
                            item.kode_ap,
                            item.supplier ? item.supplier.nama : 'N/A',
                            po_numbers,
                            item.no_invoice_supplier,
                            moment(item.due_date).format('DD MMM YYYY'),
                            new Intl.NumberFormat('id-ID', {
                                style: 'decimal',
                                minimumFractionDigits: 2
                            }).format(item.grand_total),
                            item.notes || '-',
                            item.user_entry ? item.user_entry.name : 'N/A',
                            `
                                <a href="#" class="btn btn-xs btn-success" title="Print"><i class="fal fa-print"></i></a>
                                <a href="/ap-supplier/${item.id}/edit" class="btn btn-xs btn-primary" title="Edit"><i class="fal fa-edit"></i></a>
                                <button type="button" class="btn btn-xs btn-danger delete-btn" data-url="/ap-supplier/${item.id}" title="Delete"><i class="fal fa-trash"></i></button>
                            `
                        ]);
                    });
                }

                table.draw(); // Gambar ulang tabel dengan data baru
            }

            // Logika untuk tombol hapus (tidak berubah)
            $('#ap-supplier-table').on('click', '.delete-btn', function() {
                // ... logika SweetAlert dan AJAX delete Anda ...
                // Setelah sukses hapus, refresh tabel dengan trigger search lagi
                // $('#btn-search').click();
            });
        });
    </script>
@endsection
