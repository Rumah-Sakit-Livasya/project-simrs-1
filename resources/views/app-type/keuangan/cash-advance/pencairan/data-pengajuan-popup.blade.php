@extends('inc.layout-no-side')
@section('title', 'Pengajuan Data Pop Up')
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
        {{-- Panel Pencarian (Opsional, bisa difungsikan nanti) --}}
        <div class="row justify-content-center">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Filter <span class="fw-300"><i>Data Pengajuan</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="filter-popup-form">
                                <div class="form-row">
                                    <div class="col-md-12 mb-3">
                                        <label for="kode_pengajuan_filter">Kode Pengajuan</label>
                                        <input type="text" class="form-control" id="kode_pengajuan_filter"
                                            name="kode_pengajuan_filter" placeholder="Cari Kode...">
                                    </div>

                                </div>

                                <div class="form-row">
                                    <div class="col-md-12 mb-3">
                                        <label for="nama_pengaju_filter">Nama Pengaju</label>
                                        <input type="text" class="form-control" id="nama_pengaju_filter"
                                            name="nama_pengaju_filter" placeholder="Cari Nama...">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-12 mb-3">
                                        <label for="tipe_pengajuan_filter">Tipe Pengajuan</label>
                                        <select class="form-control select2" id="tipe_pengajuan_filter"
                                            name="tipe_pengajuan">
                                            <option value="">Semua Tipe</option>
                                            <option value="approval_pengajuan"
                                                {{ request('tipe_pengajuan') == 'approval_pengajuan' ? 'selected' : '' }}>
                                                Approval Pengajuan</option>
                                            <option value="non_pengajuan"
                                                {{ request('tipe_pengajuan') == 'non_pengajuan' ? 'selected' : '' }}>Non
                                                Pengajuan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row justify-content-end">
                                    <button type="submit" class="btn btn-sm btn-primary">Cari</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Data --}}
        <div class="row no-gutters">
            <div class="col-xl-12">
                <div id="panel-2" class="panel">
                    <div class="panel-hdr">
                        <h2>Daftar <span class="fw-300"><i>Pengajuan </i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="dt-popup" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>Kode Pengajuan</th>
                                        <th>Tanggal</th>
                                        <th>Nama Pengaju</th>
                                        <th>Pengajuan </th>
                                        <th>Telah Dicairkan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pengajuans as $p)
                                        @php
                                            // Menghitung total yang sudah dicairkan untuk pengajuan ini
                                            $sudahDicairkan = $p->pencairan->sum('nominal_pencairan');
                                        @endphp
                                        <tr>
                                            <td>{{ $p->kode_pengajuan }}</td>
                                            <td>{{ \Carbon\Carbon::parse($p->tanggal_pengajuan)->format('d-m-Y') }}</td>
                                            <td>{{ $p->pengaju->name ?? 'N/A' }}</td>
                                            <td class="text-right">
                                                {{ 'Rp ' . number_format($p->total_nominal_pengajuan, 0, ',', '.') }}
                                            </td>
                                            <td class="text-right">
                                                {{ 'Rp ' . number_format($sudahDicairkan, 0, ',', '.') }}
                                            </td>
                                            <td class="text-center">
                                                {{-- Tombol "pena" dengan semua data yang dibutuhkan --}}
                                                <button class="btn btn-success btn-xs btn-select"
                                                    data-id="{{ $p->id }}" data-kode="{{ $p->kode_pengajuan }}"
                                                    data-nama="{{ $p->pengaju->name ?? 'N/A' }}"
                                                    data-disetujui="{{ $p->total_nominal_disetujui }}"
                                                    data-dicairkan="{{ $sudahDicairkan }}">
                                                    <i class="fal fa-pencil-alt"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Tidak ada data pengajuan</td>
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
    <script>
        $(document).ready(function() {
            // Inisialisasi Select2
            $('.select2').select2({
                placeholder: 'Pilih Tipe Pengajuan',
                width: '100%'
            });

            // Inisialisasi DataTable
            var table = $('#dt-popup').DataTable({
                responsive: true,
                order: [
                    [1, 'desc']
                ], // Urutkan berdasarkan tanggal terbaru
                language: {
                    emptyTable: "Tidak ada data pengajuan yang tersedia",
                    zeroRecords: "Tidak ditemukan data yang sesuai"
                }
            });

            // Fungsi untuk filter data
            $('#filter-popup-form').on('submit', function(e) {
                e.preventDefault();

                var kode = $('#kode_pengajuan_filter').val().toLowerCase();
                var nama = $('#nama_pengaju_filter').val().toLowerCase();
                var tipe = $('#tipe_pengajuan_filter').val();

                table.rows().every(function() {
                    var row = this.node();
                    var rowKode = $(row).find('td:eq(0)').text().toLowerCase();
                    var rowNama = $(row).find('td:eq(2)').text().toLowerCase();

                    var matchKode = kode === '' || rowKode.includes(kode);
                    var matchNama = nama === '' || rowNama.includes(nama);
                    var matchTipe =
                        true; // Default true karena kita tidak punya kolom tipe di tabel

                    if (matchKode && matchNama && matchTipe) {
                        $(row).show();
                    } else {
                        $(row).hide();
                    }
                });
            });

            // Logika untuk mengirim data ke jendela induk
            $('#dt-popup tbody').on('click', '.btn-select', function() {
                var data = $(this).data();

                // Cek apakah jendela induk masih ada dan memiliki fungsi yang kita butuhkan
                if (window.opener && !window.opener.closed && typeof window.opener.selectPengajuan ===
                    'function') {
                    // Hitung sisa pencairan
                    var sisa = parseFloat(data.disetujui) - parseFloat(data.dicairkan);

                    // Panggil fungsi `selectPengajuan` di jendela induk dengan semua parameter
                    window.opener.selectPengajuan(
                        data.id,
                        data.kode,
                        data.nama,
                        data.disetujui,
                        data.dicairkan,
                        sisa
                    );

                    // Tutup jendela pop-up ini
                    window.close();
                } else {
                    // Beri tahu pengguna jika ada masalah
                    alert('Tidak dapat mengirim data. Jendela induk mungkin sudah ditutup.');
                }
            });
        });
    </script>
@endsection
