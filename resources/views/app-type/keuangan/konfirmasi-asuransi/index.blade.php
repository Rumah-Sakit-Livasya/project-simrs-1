    @extends('inc.layout')
    @section('title', 'Daftar Konfirmasi Asuransi')
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
                            <h2>Daftar <span class="fw-300"><i>Konfirmasi A/R</i></span></h2>
                        </div>
                        <div class="panel-container show">
                            <div class="panel-content">
                                <form action="{{ route('keuangan.konfirmasi-asuransi.index') }}" method="get">
                                    @csrf
                                    <div class="row mb-3">
                                        <!-- Tanggal Periode (Dari - Sampai) -->
                                        <div class="col-md-6 mb-3">
                                            <label>Periode Awal</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control datepicker" name="tanggal_awal"
                                                    value="{{ request('tanggal_awal') }}">
                                                <div class="input-group-append"><span class="input-group-text fs-sm"><i
                                                            class="fal fa-calendar"></i></span></div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label>Periode Akhir</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control datepicker" name="tanggal_akhir"
                                                    value="{{ request('tanggal_akhir') }}">
                                                <div class="input-group-append"><span class="input-group-text fs-sm"><i
                                                            class="fal fa-calendar"></i></span></div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mt-3">
                                            <label>No. Invoice</label>
                                            <input type="text" class="form-control" id="invoice" name="invoice"
                                                placeholder="Masukkan No.invoice" value="{{ request('invoice') }}">
                                        </div>

                                        <div class="col-md-6 mt-3">
                                            <label>Penjamin</label>
                                            <select class="form-control select2" id="penjamin_id" name="penjamin_id"
                                                required>
                                                <option value="">Pilih Penjamin</option>
                                                @foreach ($penjamins as $penjamin)
                                                    <option value="{{ $penjamin->id }}"
                                                        {{ request('penjamin_id') == $penjamin->id ? 'selected' : '' }}>
                                                        {{ $penjamin->nama_perusahaan }}
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
                                            <a href="{{ route('keuangan.konfirmasi-asuransi.create') }}"
                                                class="btn bg-primary-600 mb-3" id="create-btn">
                                                <span class="fal fa-plus mr-1"></span> Tambah A/R
                                            </a>
                                        </div>
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
                    <div id="panel-1" class="panel">
                        <div class="panel-hdr">
                            <h2>Daftar <span class="fw-300"><i>Konfirmasi A/R</i></span></h2>
                            <div class="panel-toolbar">
                                @if (request('tanggal_awal') || request('tanggal_akhir') || request('penjamin_id') || request('invoice'))
                                    <span class="badge bg-primary-600 badge-info p-2">
                                        Filter Aktif:
                                        @if (request('tanggal_awal') && request('tanggal_akhir'))
                                            Periode: {{ request('tanggal_awal') }} s/d {{ request('tanggal_akhir') }}
                                        @endif
                                        @if (request('penjamin_id'))
                                            @php
                                                $selectedPenjamin = $penjamins->firstWhere(
                                                    'id',
                                                    request('penjamin_id'),
                                                );
                                            @endphp
                                            {{ request('tanggal_awal') ? ' | ' : '' }}
                                            Penjamin: {{ $selectedPenjamin ? $selectedPenjamin->nama_perusahaan : '' }}
                                        @endif
                                        @if (request('invoice'))
                                            {{ request('tanggal_awal') || request('penjamin_id') ? ' | ' : '' }}
                                            Invoice: {{ request('invoice') }}
                                        @endif
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="panel-container show">
                            <div class="panel-content">
                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true"><i class="fal fa-times"></i></span>
                                        </button>
                                        <strong>Sukses!</strong> {{ session('success') }}
                                    </div>
                                @endif

                                <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                    <thead class="bg-primary-600">
                                        <tr>
                                            <th>#</th>
                                            <th style="width: 10px;"></th> {{-- Kolom untuk ikon expand/collapse --}}
                                            <th>Tgl. AR</th>
                                            <th>No. Invoice</th>
                                            <th>Penjamin</th>
                                            <th>Jumlah</th>
                                            <th>Discount</th>
                                            <th>Keterangan</th>
                                            <th>Fungsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($konfirmasiAsuransi as $konfirmasi)
                                            {{-- MODIFIKASI: Menggunakan data-id untuk identifikasi di JS --}}
                                            <tr data-id="{{ $konfirmasi->id }}">
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                {{-- MODIFIKASI: Menggunakan class dan ikon yang sama dengan Pertanggung Jawaban --}}
                                                <td class="details-control"><i class="fal fa-chevron-up"></i></td>
                                                <td>{{ $konfirmasi->tanggal }}</td>
                                                <td>{{ $konfirmasi->invoice }}</td>
                                                <td>{{ $konfirmasi->penjamin->nama_perusahaan }}</td>
                                                <td class="text-right">
                                                    {{ 'Rp ' . number_format($konfirmasi->jumlah ?? 0, 2, ',', '.') }}
                                                </td>
                                                <td class="text-right">
                                                    {{ number_format($konfirmasi->discount, 0, ',', '.') }}
                                                </td>
                                                <td>{{ $konfirmasi->keterangan }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('cetak-klaim', $konfirmasi->id) }}"
                                                        class="btn btn-xs btn-primary" target="_blank" data-toggle="tooltip"
                                                        title="Cetak Klaim">
                                                        <i class="fal fa-print"></i>
                                                    </a>
                                                    <a href="{{ route('cetak-klaim-kwitansi', $konfirmasi->id) }}"
                                                        class="btn btn-xs btn-info" target="_blank" data-toggle="tooltip"
                                                        title="Cetak Kwitansi Klaim">
                                                        <i class="fa fa-file" aria-hidden="true"></i>
                                                    </a>
                                                    <a href="{{ route('cetak-rekap', $konfirmasi->id) }}"
                                                        class="btn btn-xs btn-success" target="_blank" data-toggle="tooltip"
                                                        title="Cetak Rekap">
                                                        <i class="fal fa-file-alt"></i>
                                                    </a>
                                                    <form
                                                        action="{{ route('keuangan.konfirmasi-asuransi.destroy', $konfirmasi->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Yakin ingin menghapus data konfirmasi ini?')"
                                                        style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                {{-- TAMBAHAN: Template untuk Child Row (diletakkan di luar tabel) --}}
                                <div id="child-row-template" style="display: none;">
                                    <div class="child-row-content">
                                        <h6 class="mb-3"><strong>Rincian untuk Invoice <span
                                                    class="invoice-placeholder">{invoice}</span>:</strong></h6>
                                        <table class="child-table table table-sm table-bordered">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>No. RM</th>
                                                    <th>Nama Pasien</th>
                                                    <th>No. Registrasi</th>
                                                    <th>Bill No</th>
                                                    <th>Tanggal Keluar</th>
                                                    <th class="text-right">Tagihan</th>
                                                    <th>Fungsi</th>
                                                </tr>
                                            </thead>
                                            <tbody class="detail-tbody">
                                                {{-- Isi akan digenerate oleh JavaScript --}}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
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
            $(document).ready(function() {
                // Inisialisasi plugin dasar
                $('.datepicker').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true,
                    todayHighlight: true
                });
                $('.select2').select2({
                    dropdownCssClass: "move-up"
                });

                // ==========================================================
                // LOGIKA JAVASCRIPT BARU (Disamakan dengan Pertanggung Jawaban)
                // ==========================================================

                // 1. Siapkan data detail dalam variabel JavaScript
                const allDetails = {!! json_encode(
                    $konfirmasiAsuransi->mapWithKeys(function ($konfirmasi) {
                        return [
                            $konfirmasi->id => [
                                [
                                    'rm' => optional(optional($konfirmasi->registration)->patient)->medical_record_number,
                                    'pasien' => optional(optional($konfirmasi->registration)->patient)->name,
                                    'reg_no' => optional($konfirmasi->registration)->registration_number,
                                    'bill_no' => optional($konfirmasi->registration)->bill_no,
                                    'tgl_keluar' => $konfirmasi->registration->registration_close_date
                                        ? \Carbon\Carbon::parse($konfirmasi->registration->registration_close_date)->translatedFormat(
                                            'd F Y',
                                        )
                                        : '-',
                                    'tagihan' => $konfirmasi->jumlah,
                                    'cetak_url' => route('cetak-klaim', $konfirmasi->id),
                                ],
                            ],
                        ];
                    }),
                ) !!};

                // 2. Inisialisasi DataTable
                var table = $('#dt-basic-example').DataTable({
                    responsive: true,
                    lengthChange: false,
                    pageLength: 20,
                    dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                    buttons: [{
                            extend: 'pdfHtml5',
                            text: '<i class="fal fa-file-pdf mr-1"></i> PDF',
                            className: 'btn-outline-danger btn-sm mr-1',
                            title: 'Daftar Konfirmasi Asuransi',
                            exportOptions: {
                                columns: [2, 3, 4, 5, 6, 7]
                            },
                            orientation: 'landscape'
                        },
                        {
                            extend: 'excelHtml5',
                            text: '<i class="fal fa-file-excel mr-1"></i> Excel',
                            className: 'btn-outline-success btn-sm mr-1',
                            title: 'Daftar Konfirmasi Asuransi',
                            exportOptions: {
                                columns: [2, 3, 4, 5, 6, 7]
                            }
                        },
                        {
                            extend: 'print',
                            text: '<i class="fal fa-print mr-1"></i> Print',
                            className: 'btn-outline-primary btn-sm',
                            title: 'Daftar Konfirmasi Asuransi',
                            exportOptions: {
                                columns: [2, 3, 4, 5, 6, 7]
                            }
                        }
                    ],
                    columnDefs: [{
                        orderable: false,
                        targets: [0, 1, 8] // Kolom #, detail, dan fungsi tidak bisa diurutkan
                    }]
                });

                // 3. Fungsi untuk memformat child row
                function formatChildRow(invoice, details) {
                    // Ambil template dari div yang kita sembunyikan
                    var template = $('#child-row-template').clone();
                    template.find('.invoice-placeholder').text(invoice);

                    var tbody = template.find('.detail-tbody');
                    tbody.empty();

                    if (details && details.length > 0 && details[0].rm) {
                        details.forEach(function(detail) {
                            var rowHtml = `
                            <tr>
                                <td>${detail.rm || '-'}</td>
                                <td>${detail.pasien || '-'}</td>
                                <td>${detail.reg_no || '-'}</td>
                                <td>${detail.bill_no || '-'}</td>
                                <td>${detail.tgl_keluar || '-'}</td>
                                <td class="text-right">${'Rp ' + new Intl.NumberFormat('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(detail.tagihan || 0)}</td>
                                <td>
                                    <a href="${detail.cetak_url}"
                                        class="btn btn-xs btn-primary" target="_blank"
                                        data-toggle="tooltip" title="Cetak Klaim">
                                        <i class="fal fa-print"></i>
                                    </a>
                                </td>
                            </tr>
                        `;
                            tbody.append(rowHtml);
                        });
                    } else {
                        tbody.append(
                            '<tr><td colspan="7" class="text-center text-muted">Tidak ada rincian data pasien.</td></tr>'
                        );
                    }

                    return template.html();
                }

                // 4. Logika child row menggunakan API DataTables
                $('#dt-basic-example tbody').on('click', 'td.details-control', function() {
                    var tr = $(this).closest('tr');
                    var row = table.row(tr);

                    if (row.child.isShown()) {
                        // Baris ini sudah terbuka, tutup.
                        row.child.hide();
                        tr.removeClass('dt-hasChild');
                    } else {
                        // Ambil konfirmasi ID dari data attribute di TR
                        var konfirmasiId = tr.data('id');
                        var invoice = tr.find('td:eq(3)').text().trim();

                        // Ambil detail dari allDetails berdasarkan konfirmasi ID
                        var details = allDetails[konfirmasiId] || [];

                        // Buka baris dan format kontennya
                        row.child(formatChildRow(invoice, details)).show();
                        tr.addClass('dt-hasChild');

                        // Inisialisasi ulang tooltip jika ada di dalam child row
                        $(row.child()).find('[data-toggle="tooltip"]').tooltip();
                    }
                });

                $('#dt-basic-example tbody').on('click', 'a[target="_blank"]', function(e) {
                    // 2. Hentikan perilaku default link (yaitu membuka tab baru).
                    e.preventDefault();

                    // 3. Ambil URL tujuan dari atribut 'href' link yang diklik.
                    const url = $(this).attr('href');

                    // 4. Tentukan ukuran dan posisi popup agar hampir fullscreen dan di tengah.
                    // Mengambil 90% dari lebar dan tinggi layar.
                    const popupWidth = screen.width * 0.9;
                    const popupHeight = screen.height * 0.9;

                    // Menghitung posisi kiri (x) dan atas (y) untuk meletakkan popup di tengah layar.
                    const left = (screen.width - popupWidth) / 2;
                    const top = (screen.height - popupHeight) / 2;

                    // 5. Gabungkan semua opsi menjadi satu string untuk parameter 'window.open'.
                    // 'resizable=yes' dan 'scrollbars=yes' penting untuk pengalaman pengguna yang baik.
                    const windowFeatures =
                        `width=${popupWidth},height=${popupHeight},left=${left},top=${top},resizable=yes,scrollbars=yes`;

                    // 6. Buka jendela popup baru dengan URL dan fitur yang telah ditentukan.
                    // 'cetakWindow' adalah nama jendela; jika nama yang sama digunakan lagi, jendela yang ada akan digunakan kembali.
                    window.open(url, 'cetakWindow', windowFeatures);
                });
            });
        </script>
    @endsection
