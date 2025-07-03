@extends('inc.layout')
@section('title', 'Pertanggung Jawaban ')
@section('content')
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
            <i class="fa fa-spinner fa-spin"></i> Memuat...
        </div>
    </div>

    <main id="js-page-content" role="main" class="page-content">
        <!-- Search Panel -->
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="panel">
                    <div class="panel-hdr">
                        <h2>Laporan <span class="fw-300"><i>Umur PertanggungJawaban</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="{{ route('keuangan.cash-advance.laporan.laporan-pj') }}" method="GET">
                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label>Tanggal Pencairan Awal</label>
                                        <div class="input-group input-grup-sm">
                                            <input type="text" class="form-control datepicker" id="tanggal_awal"
                                                name="tanggal_awal" placeholder="Pilih Tanggal Awal"
                                                value="{{ request('tanggal_awal') }}">
                                            <div class="input-group-append"><span class="input-group-text fs-sm"><i
                                                        class="fal fa-calendar"></i></span></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Nama Pengaju</label>
                                        <select class="form-control select2" id="pengaju_id" name="pengaju_id" required>
                                            <option value="">Pilih Nama Pengaju</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ old('pengaju_id') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                                <div class="form-row justify-content-end">
                                    <button type="submit" class="btn btn-sm btn-primary mr-2"><i
                                            class="fal fa-search mr-1"></i> Cari</button>

                                    <button type="button" id="btn-reset" class="btn btn-sm btn-secondary mr-2"><i
                                            class="fal fa-undo mr-1"></i> Reset</button>

                                    <button type="button" class="btn btn-sm btn-success" id="btn-export">
                                        <i class="fal fa-file-excel mr-2"></i> Export Excel
                                    </button>
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
                        <h2>List <span class="fw-300"><i>Outstanding Pertanggung Jawaban</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="laporan-pj-table" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Pengaju</th>
                                        <th>Keterangan</th>
                                        <th>Kode Pencairan</th>
                                        <th>Tgl Pencairan</th>
                                        <th class="text-right">Pencairan</th>
                                        <th class="text-right">PertanggungJawabkan</th>
                                        {{-- KOLOM SISA DIHAPUS --}}
                                        <th class="text-right aging-highlight aging-success">
                                            <= 7 hari</th>
                                        <th class="text-right aging-highlight aging-warning">8-14 hari</th>
                                        <th class="text-right aging-highlight aging-danger">>= 15 hari</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pencairans as $index => $pencairan)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ optional(optional($pencairan->pengajuan)->pengaju)->name }}</td>
                                            <td>{{ optional($pencairan->pengajuan)->keterangan }}</td>
                                            <td>{{ $pencairan->kode_pencairan }}</td>
                                            <td>{{ \Carbon\Carbon::parse($pencairan->tanggal_pencairan)->format('d-m-Y') }}
                                            </td>
                                            <td class="text-right">
                                                {{ number_format($pencairan->nominal_pencairan, 0, ',', '.') }}</td>
                                            <td class="text-right">
                                                {{ number_format($pencairan->total_telah_dipertanggungjawabkan, 0, ',', '.') }}
                                            </td>

                                            {{-- =============================================== --}}
                                            {{--             LOGIKA UMUR DIPERBAIKI              --}}
                                            {{-- =============================================== --}}
                                            <td class="text-right aging-highlight aging-success">
                                                {{-- Tampilkan jika umur kurang dari atau sama dengan 7 hari (termasuk 0 dan negatif) --}}
                                                {{ $pencairan->umur <= 7 ? number_format($pencairan->sisa, 0, ',', '.') : '-' }}
                                            </td>
                                            <td class="text-right aging-highlight aging-warning">
                                                {{ $pencairan->umur >= 8 && $pencairan->umur <= 14 ? number_format($pencairan->sisa, 0, ',', '.') : '-' }}
                                            </td>
                                            <td class="text-right aging-highlight aging-danger">
                                                {{ $pencairan->umur >= 15 ? number_format($pencairan->sisa, 0, ',', '.') : '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center">Tidak ada data yang cocok dengan filter
                                                Anda.</td>
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
    {{-- JavaScript Anda sudah benar dan tidak perlu diubah --}}
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script>
        $(document).ready(function() {
            // 1. INISIALISASI PLUGIN
            $('.select2').select2({
                placeholder: "Pilih Opsi",
                allowClear: true
            });
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true
            });

            var table = $('#laporan-pj-table').DataTable({
                responsive: true,
                pageLength: 25,
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: []
            });

            // Simpan data awal yang dirender oleh PHP
            var initialData = {!! json_encode($pencairans->values()) !!};

            // 2. FUNGSI UNTUK MENGGAMBAR ULANG TABEL
            function redrawTable(data) {
                table.clear();
                if (data && data.length > 0) {
                    $.each(data, function(index, item) {
                        let aging_7 = item.umur <= 7 ? new Intl.NumberFormat('id-ID').format(item.sisa) :
                            '-';
                        let aging_14 = (item.umur >= 8 && item.umur <= 14) ? new Intl.NumberFormat('id-ID')
                            .format(item.sisa) : '-';
                        let aging_15 = item.umur >= 15 ? new Intl.NumberFormat('id-ID').format(item.sisa) :
                            '-';

                        table.row.add([
                            index + 1,
                            item.pengajuan?.pengaju?.name || 'N/A',
                            item.pengajuan?.keterangan || '-',
                            item.kode_pencairan,
                            new Date(item.tanggal_pencairan).toLocaleDateString('id-ID', {
                                day: '2-digit',
                                month: 'short',
                                year: 'numeric'
                            }),
                            new Intl.NumberFormat('id-ID').format(item.nominal_pencairan),
                            new Intl.NumberFormat('id-ID').format(item
                                .total_telah_dipertanggungjawabkan),
                            aging_7,
                            aging_14,
                            aging_15
                        ]).draw(false);
                    });
                } else {
                    table.draw(); // Gambar tabel kosong dengan pesan "No data available"
                }
            }

            // Gambar tabel dengan data awal saat halaman dimuat
            redrawTable(initialData);

            // 3. EVENT HANDLER UNTUK TOMBOL-TOMBOL

            // A. Event handler untuk form submit (bukan ID yang salah)
            $('form').on('submit', function(e) {
                e.preventDefault();
                $('.loading-overlay').css('display', 'flex');

                $.ajax({
                    url: $(this).attr('action'),
                    type: "GET",
                    data: $(this).serialize(),
                    success: function(response) {
                        redrawTable(response.data);
                        $('.loading-overlay').hide();
                    },
                    error: function(xhr) {
                        console.error("Error:", xhr);
                        alert('Gagal mengambil data.');
                        $('.loading-overlay').hide();
                    }
                });
            });

            // B. TOMBOL RESET - DIPERBAIKI
            $('#btn-reset').on('click', function(e) {
                e.preventDefault(); // Mencegah behavior default

                // Reset semua input dalam form
                $('form')[0].reset();

                // Reset select2 secara khusus
                $('.select2').val(null).trigger('change');

                // Reset datepicker
                $('.datepicker').datepicker('setDate', null);

                // Clear input values secara manual jika perlu
                $('#tanggal_awal').val('');
                $('#pengaju_id').val('').trigger('change');

                // Tampilkan loading
                $('.loading-overlay').css('display', 'flex');

                // Lakukan AJAX request untuk memuat ulang data tanpa filter
                $.ajax({
                    url: "{{ route('keuangan.cash-advance.laporan.laporan-pj') }}",
                    type: "GET",
                    data: {}, // Data kosong = tanpa filter
                    success: function(response) {
                        redrawTable(response.data);
                        $('.loading-overlay').hide();

                        // Optional: Tampilkan pesan sukses
                        console.log('Data berhasil di-reset');
                    },
                    error: function(xhr) {
                        console.error("Error:", xhr);
                        alert('Gagal mereset data.');
                        $('.loading-overlay').hide();
                    }
                });
            });

            // C. Tombol Export
            $('#btn-export').on('click', function() {
                var formData = $('form').serialize();
                var exportUrl = "{{ route('keuangan.cash-advance.laporan.laporan-pj.export') }}?" +
                    formData;
                window.location.href = exportUrl;
            });
        });
    </script>
@endsection
