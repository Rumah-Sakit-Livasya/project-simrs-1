@extends('inc.layout')
@section('title', 'Tambah Konfirmasi Asuransi')
@section('content')

    @push('style')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    @endpush
    <style>
        table {
            font-size: 8pt !important;
        }

        .badge-pending {
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

        .child-row {
            display: none;
        }

        .dropdown-icon {
            font-size: 14px;
            transition: transform 0.3s ease;
            display: inline-block;
        }

        .dropdown-icon.rotated {
            transform: rotate(180deg);
        }

        .child-row td {
            background-color: #f9f9f9;
            border-bottom: 2px solid #ddd;
        }

        .child-row td>div {
            padding: 15px;
            margin: 0;
        }

        tr.parent-row.active {
            border-bottom: none !important;
        }

        .control-details {
            cursor: pointer;
            text-align: center;
            width: 50px;
        }

        .control-details .dropdown-icon {
            font-size: 18px;
            transition: transform 0.3s ease, color 0.3s ease;
            display: inline-block;
            color: #3498db;
        }

        .control-details .dropdown-icon.rotated {
            transform: rotate(180deg);
            color: #e74c3c;
        }

        .control-details:hover .dropdown-icon {
            color: #2980b9;
        }

        table.dataTable thead .sorting:after,
        table.dataTable thead .sorting_asc:after,
        table.dataTable thead .sorting_desc:after,
        table.dataTable thead .sorting_asc_disabled:after,
        table.dataTable thead .sorting_desc_disabled:after {
            display: none !important;
        }

        .child-row td>div {
            padding: 15px;
            width: 100%;
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

        .child-row {
            transition: all 0.3s ease;
        }

        .child-row.show {
            opacity: 1;
        }

        td.control-details::before {
            display: none !important;
        }

        #pj-table tbody tr.parent-row:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }

        #pj-table tbody tr.child-row:hover {
            background-color: #f1f1f1;
        }

        .loading-overlay {
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
        }

        .loading-spinner {
            background: white;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
        }

        /* Fix untuk memastikan child row dapat di-toggle */
        .parent-row.expanded {
            border-bottom: none !important;
        }

        .toggle-detail {
            border: none;
            background: transparent;
            color: #3498db;
            padding: 5px;
        }

        .toggle-detail:hover {
            color: #2980b9;
            background: rgba(52, 152, 219, 0.1);
        }

        .toggle-detail:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.25);
        }

        /* PERBAIKAN CSS UNTUK DETAILS CONTROL */
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
            /* Default: Panah ke atas (chevron-up) */
            transform: rotate(0deg);
        }

        .details-control:hover i {
            color: #2980b9;
        }

        /* Saat baris memiliki class 'dt-hasChild' (child row terbuka), putar ikon 180 derajat (menjadi panah ke bawah) */
        tr.dt-hasChild td.details-control i {
            transform: rotate(180deg);
            color: #e74c3c;
        }

        .child-row-content {
            padding: 15px;
            background-color: #f9f9f9;
        }

        /* Styling untuk badge pada detail */
        .badge-info {
            background-color: #17a2b8;
            color: white;
        }

        .badge-secondary {
            background-color: #6c757d;
            color: white;
        }
    </style>

    <!-- Loading overlay div -->
    <div class="loading-overlay">
        <div class="loading-spinner">
            <i class="fa fa-spinner fa-spin"></i> Memproses...
        </div>
    </div>

    <main id="js-page-content" role="main" class="page-content">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Form <span class="fw-300"><i>Pencarian Konfirmasi Asuransi</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="search-form">
                                @csrf
                                <!-- Baris Pertama: Periode, Status, Penjamin -->
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

                                    <!-- Status Tagihan -->

                                </div>
                                <div class="row mb-3">

                                    <div class="col-md-4 mb-3">
                                        <label>Penjamin</label>
                                        <select class="form-control select2" id="penjamin_id" name="penjamin_id" required>
                                            <option value="">Pilih Penjamin</option>
                                            @foreach ($penjamins as $penjamin)
                                                <option value="{{ $penjamin->id }}"
                                                    {{ request('penjamin_id') == $penjamin->id ? 'selected' : '' }}>
                                                    {{ $penjamin->nama_perusahaan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!-- Tanggal Periode (Dari - Sampai) -->
                                    <div class="col-md-4 mb-3">
                                        <label>Tagihan Ke</label>
                                        <select class="form-control w-100 select2" id="tagihan_ke"
                                            style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                            name="tagihan_ke">
                                            <option value="">Semua</option>
                                            @foreach ($penjamins as $penjamin)
                                                <option value="{{ $penjamin->id }}"
                                                    {{ request('tagihan_ke') == $penjamin->id ? 'selected' : '' }}>
                                                    {{ $penjamin->nama_perusahaan }}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="col-md-4 mb-3">
                                        <label>Status Tagihan</label>
                                        <select class="form-control select2" id="status" name="status">
                                            <option value="">Semua</option>
                                            <option value="Belum Di Buat Tagihan"
                                                {{ request('status') == 'Belum Di Buat Tagihan' ? 'selected' : '' }}>
                                                Belum Di buat Tagihan
                                            </option>
                                            <option value="Sudah Di Buat Tagihan"
                                                {{ request('status') == 'Sudah Di Buat Tagihan' ? 'selected' : '' }}>
                                                Sudah Di Buat Tagihan
                                            </option>
                                        </select>
                                    </div>
                                </div>


                                {{-- tipe pasien  --}}

                                {{-- <div class="col-md-4">
                                        <div class="form-group row align-items-center">
                                            <label for="tipe_pasien" class="col-md-5 col-form-label text-md-right">
                                                Tipe Pasien
                                            </label>
                                            <div class="col-md-7">
                                                <select class="form-control select2" id="tipe_pasien" name="tipe_pasien">
                                                    <option value="">Semua</option>
                                                    <option value="rawat inap"
                                                        {{ request('tipe_pasien') == 'rawat inap' ? 'selected' : '' }}>
                                                        Rawat Inap
                                                    </option>
                                                    <option value="rawat jalan"
                                                        {{ request('tipe_pasien') == 'rawat jalan' ? 'selected' : '' }}>
                                                        Rawat Jalan
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div> --}}
                                <!-- Baris Kedua: Tagihan Ke, Penjamin, Tipe Pasien -->

                                <!-- Tombol Cari dan Reset -->
                                <div class="row">
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <button type="button" id="btn-reset"
                                            class="btn btn-secondary waves-effect waves-themed mr-2">
                                            <span class="fal fa-undo mr-2"></span>
                                            Reset
                                        </button>
                                        <button type="submit" class="btn btn-primary">Cari</button>

                                    </div>
                                </div>
                            </form>
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
                            Daftar <span class="fw-300"><i>Konfirmasi Asuransi</i></span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- Datatable Start -->
                            <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>#</th>
                                        <th>No. RM</th>
                                        <th>Pasien</th>
                                        <th>Bill</th>
                                        <th>No Reg</th>
                                        <th>Tgl Keluar</th>
                                        <th>Total Tagihan</th>
                                        <th>Diskon</th>
                                        <th>Aksi</th>
                                        <th class="text-center"><input type="checkbox" id="select-all"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($query as $index => $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->registration->patient->medical_record_number ?? '-' }}</td>
                                            <td>{{ $item->patient->name ?? '-' }}</td>
                                            <td>{{ $item->bill ?? '-' }}</td>
                                            <td>{{ $item->registration->registration_number ?? '-' }}</td>
                                            <td>{{ $item->registration->registration_close_date ? \Carbon\Carbon::parse($item->registration->registration_close_date)->format('d/m/Y') : '-' }}
                                            </td>
                                            <td class="text-right">
                                                {{ $item->jumlah ? 'Rp ' . number_format($item->jumlah, 0, ',', '.') : 'Rp 0' }}
                                            </td>
                                            <td class="text-right">
                                                {{ $item->diskon ? 'Rp ' . number_format($item->diskon, 0, ',', '.') : 'Rp 0' }}
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-danger del-btn"
                                                    data-id="{{ $item->id }}"><i class="fal fa-trash"></i></button>
                                            </td>
                                            <td class="text-center">
                                                {{-- <input type="checkbox" class="row-check" name="selected_invoices[]"
                                                    value="{{ $item->id }}"> --}}
                                                <input type="checkbox" class="row-checkbox" value="{{ $item->id }}">
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center text-muted">Tidak ada data tersedia</td>
                                        </tr>
                                    @endforelse
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th colspan="6" class="text-center">
                                            <strong>Total Keseluruhan:</strong>
                                        </th>
                                        <th colspan="4" class="text-right">Rp <span id="total-tagihan">0</span></th>
                                    </tr>
                                </tfoot>
                            </table>
                            <!-- Datatable End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-3" class="panel">
                    <div class="panel-hdr">
                        <h2>Pengaturan <span class="fw-300"><i>Tagihan Asuransi</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="tagihan-form">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <div class="form-group row align-items-center">
                                            <label for="jatuh_tempo" class="col-md-5 col-form-label text-md-right">
                                                Jatuh Tempo (Hari)
                                            </label>
                                            <div class="col-md-7">
                                                <select class="form-control select2" id="jatuh_tempo" name="jatuh_tempo">
                                                    <option value="7">7 hari</option>
                                                    <option value="14">14 hari</option>
                                                    <option value="21">21 hari</option>
                                                    <option value="30" selected>30 hari</option>
                                                    <option value="45">45 hari</option>
                                                    <option value="60">60 hari</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="form-group row align-items-center">
                                            <label for="keterangan" class="col-md-3 col-form-label text-md-right">
                                                Keterangan
                                            </label>
                                            <div class="col-md-9">
                                                <textarea class="form-control" id="keterangan" name="keterangan" rows="1"
                                                    placeholder="Masukkan keterangan tagihan"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-10 d-flex justify-content-start">
                                        <button type="button" id="buat-tagihan"
                                            class="btn btn-success waves-effect waves-themed mr-2">
                                            <span class="fal fa-file-invoice mr-2"></span>
                                            Buat Tagihan
                                        </button>
                                        <button type="button" id="cetak-rekap"
                                            class="btn btn-primary waves-effect waves-themed">
                                            <span class="fal fa-print mr-2"></span>
                                            Cetak Rekap
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/dependency/moment/moment.js"></script>
    <link rel="stylesheet" href="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.css">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('[Init] DOM Loaded');

            // === DATEPICKER ===
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                clearBtn: true,
                language: 'id',
                orientation: 'bottom auto',
                todayBtn: 'linked'
            }).datepicker('setDate', null); // <-- Ini membuat default value kosong


            // === SELECT2 ===
            $('.select2').select2({
                dropdownCssClass: "move-up",
                width: '100%'
            });

            // === INISIALISASI DATATABLE ===
            const table = $('#dt-basic-example').DataTable({
                responsive: true,
                lengthChange: false,
                dom: "<'row mb-3'<'col-md-6'><'col-md-6 text-right'B>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-md-5'i><'col-md-7'p>>",
                buttons: [{
                        extend: 'pdfHtml5',
                        className: 'btn-outline-danger btn-sm mr-1',
                        title: 'Daftar Konfirmasi Asuransi',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        className: 'btn-outline-success btn-sm mr-1',
                        title: 'Daftar Konfirmasi Asuransi',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7]
                        }
                    },
                    {
                        // extend: 'print',
                        // className: 'btn-outline-primary btn-sm',
                        // title: 'Daftar Konfirmasi Asuransi',
                        // exportOptions: {
                        //     columns: [0, 1, 2, 3, 4, 5, 6, 7]
                        // }
                    }
                ],
                language: {
                    emptyTable: "Tidak ada data tersedia",
                    zeroRecords: "Tidak ada data yang cocok dengan pencarian Anda"
                },
                columnDefs: [{
                    orderable: false,
                    targets: [8, 9]
                }],
                drawCallback: function() {
                    calculateTotals();
                }
            });

            // === HITUNG TOTAL TAGIHAN ===
            function calculateTotals() {
                let total = 0;
                $('#dt-basic-example tbody tr').each(function() {
                    const nilai = parseFloat($(this).find('td:eq(6)').text().replace(/[^\d]/g, '')) || 0;
                    total += nilai;
                });
                $('#total-tagihan').text(total.toLocaleString('id-ID'));
            }

            // === FILTER DATA (CARI) ===
            $('#search-form').on('submit', function(e) {
                e.preventDefault();

                // Tampilkan loading overlay
                $('.loading-overlay').css('display', 'flex');

                $.ajax({
                    url: window.location.href,
                    type: 'GET',
                    data: {
                        tanggal_awal: $('#tanggal_awal').val(),
                        tanggal_akhir: $('#tanggal_akhir').val(),
                        penjamin_id: $('#penjamin_id').val(),
                        tagihan_ke: $('#tagihan_ke').val(),
                        status: $('#status').val(),
                        // tipe_pasien: $('#tipe_pasien').val()
                    },
                    success: function(data) {
                        updateTable(data);
                        $('.loading-overlay').hide();
                    },
                    error: function(xhr) {
                        $('.loading-overlay').hide();
                        toastr.error('Gagal mengambil data');
                        console.log(xhr);
                    }
                });
            });

            // === UPDATE TABEL DENGAN DATATABLE API ===
            function updateTable(data) {
                table.clear();

                if (data.length > 0) {
                    $.each(data, function(i, item) {
                        const tgl = item.registration?.registration_close_date ?
                            moment(item.registration.registration_close_date).format('DD/MM/YYYY') :
                            '-';

                        const total = item.jumlah ? 'Rp ' + Number(item.jumlah)
                            .toLocaleString('id-ID') : 'Rp 0';
                        const diskon = item.diskon ? 'Rp ' + Number(item.diskon).toLocaleString('id-ID') :
                            'Rp 0';

                        table.row.add([
                            i + 1,
                            item.registration?.patient?.medical_record_number || '-',
                            item.patient?.name || item.registration?.patient?.name || '-',
                            item.bill || '-',
                            item.registration?.registration_number || '-',
                            tgl,
                            total,
                            diskon,
                            `<a href="/keuangan/konfirmasi-asuransi/${item.id}/edit" class="btn btn-sm btn-info"><i class="fal fa-edit"></i></a>
                             <button type="button" class="btn btn-sm btn-danger del-btn" data-id="${item.id}"><i class="fal fa-trash"></i></button>`,
                            `<input type="checkbox" class="row-checkbox" value="${item.id}">`
                        ]);
                    });
                }

                table.draw();
                calculateTotals();
            }

            // === RESET FORM FILTER ===
            $('#btn-reset').on('click', function(e) {
                e.preventDefault();
                $('#tanggal_awal').datepicker('setDate', moment().subtract(7, 'days').toDate());
                $('#tanggal_akhir').datepicker('setDate', new Date());
                $('#tagihan_ke, #penjamin_id, #status, #tipe_pasien').val('').trigger('change');
                console.log('[Reset] Filter direset');
            });

            // === SELECT ALL CHECKBOX ===
            $('#select-all').on('change', function() {
                $('.row-checkbox').prop('checked', this.checked);
                highlightRows();
            });

            $(document).on('change', '.row-checkbox', function() {
                highlightRows();

                // Check if all checkboxes are checked
                const allChecked = $('.row-checkbox:not(:checked)').length === 0;
                $('#select-all').prop('checked', allChecked);
            });

            function highlightRows() {
                $('tbody tr').each(function() {
                    $(this).toggleClass('table-active', $(this).find('.row-checkbox').is(':checked'));
                });
            }

            // === DELETE BUTTON ===
            $(document).on('click', '.del-btn', function() {
                const id = $(this).data('id');
                if (!confirm('Yakin ingin menghapus data ini?')) return;

                $('.loading-overlay').css('display', 'flex');

                $.ajax({
                    url: `/keuangan/konfirmasi-asuransi/${id}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        $('.loading-overlay').hide();
                        if (res.success) {
                            toastr.success('Data berhasil dihapus');
                            location.reload();
                        } else {
                            toastr.error(res.message || 'Gagal menghapus data');
                        }
                    },
                    error: function(xhr) {
                        $('.loading-overlay').hide();
                        toastr.error('Gagal menghapus data');
                        console.log(xhr);
                    }
                });
            });

            // === BUAT TAGIHAN BUTTON ===
            $('#buat-tagihan').on('click', function() {
                const bilinganIds = $('.row-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (bilinganIds.length === 0) {
                    toastr.error('Silakan pilih data bilingan terlebih dahulu');
                    return;
                }

                // Ambil data dari form
                const jatuhTempo = $('#jatuh_tempo').val();
                const keterangan = $('#keterangan').val();

                $('.loading-overlay').css('display', 'flex');

                $.ajax({
                    url: '{{ route('keuangan.konfirmasi-asuransi.store') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        bilingan_ids: bilinganIds,
                        jatuh_tempo: jatuhTempo, // Tambahkan ini
                        keterangan: keterangan // Tambahkan ini
                    },
                    success: function(res) {
                        $('.loading-overlay').hide();

                        if (res.success) {
                            toastr.success(res.message);
                            setTimeout(function() {
                                window.location.href =
                                    '{{ route('keuangan.konfirmasi-asuransi.index') }}';
                            }, 1500);
                        } else {
                            toastr.error(res.message || 'Gagal menyimpan data konfirmasi');
                        }
                    },
                    error: function(xhr) {
                        $('.loading-overlay').hide();
                        toastr.error('Terjadi kesalahan server saat menyimpan data.');
                        console.log(xhr);
                    }
                });
            });


            // === CETAK REKAP BUTTON ===
            $('#cetak-rekap').on('click', function() {
                const selectedIds = $('.row-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selectedIds.length === 0) {
                    toastr.error('Silakan pilih data terlebih dahulu');
                    return;
                }

                // Redirect to print page with selected IDs
                const url = '/keuangan/konfirmasi-asuransi/print-rekap?ids=' + selectedIds.join(',');
                window.open(url, '_blank');
            });

            // Call calculateTotals on initial load
            calculateTotals();
        });

        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000"
        };


        // === HITUNG TOTAL TERPILIH ===
        function updateSelectedTotal() {
            let total = 0;
            $('.row-checkbox:checked').each(function() {
                const row = $(this).closest('tr');
                const nilai = parseFloat(row.find('td:eq(6)').text().replace(/[^\d]/g, '')) || 0;
                total += nilai;
            });

            $('#total-tagihan').text(total.toLocaleString('id-ID')); // Format ke Rupiah
        }

        // === UPDATE TOTAL SAAT CHECKBOX BERUBAH ===
        $(document).on('change', '.row-checkbox, #select-all', function() {
            if ($(this).attr('id') === 'select-all') {
                $('.row-checkbox').prop('checked', this.checked);
            }
            updateSelectedTotal();
            highlightRows();
        });

        // === HIGHLIGHT ROW ===
        function highlightRows() {
            $('tbody tr').each(function() {
                $(this).toggleClass('table-active', $(this).find('.row-checkbox').is(':checked'));
            });
        }

        // === RESET TANGGAL (optional untuk tombol reset) ===
        $('#btn-reset').on('click', function() {
            $('#tanggal_awal').val('');
            $('#tanggal_akhir').val('');
            $('#tagihan_ke, #penjamin_id, #status, #tipe_pasien').val('').trigger('change');
            updateSelectedTotal();
        });

        // Panggil sekali saat halaman pertama dimuat
        updateSelectedTotal(); // ⬅️ Ini memastikan awalnya Rp 0
        highlightRows();
    </script>


@endsection
