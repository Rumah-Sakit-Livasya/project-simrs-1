@extends('inc.layout-no-side')
@section('title', 'pencairan Data Pop Up')
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
        <div class="row">
            <div class="col-xl-12">
                <div class="panel">
                    <div class="panel-hdr">
                        <h2>Daftar Pencairan Belum Lunas</h2>
                        <div class="panel-toolbar">
                            <button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip"
                                data-offset="0,10" data-original-title="Collapse"></button>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="pencairan-popup-table" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>Kode Pencairan</th>
                                        <th>Tanggal</th>
                                        <th>Pengaju</th>
                                        <th>Nominal Pencairan</th>
                                        <th>Total PJ</th>
                                        <th>Sisa</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pencairans as $p)
                                        @php
                                            $sisa = $p->nominal_pencairan - $p->total_telah_dipertanggungjawabkan;
                                        @endphp
                                        <tr>
                                            <td>{{ $p->kode_pencairan }}</td>
                                            <td>{{ \Carbon\Carbon::parse($p->tanggal_pencairan)->format('d-m-Y') }}</td>
                                            <td>{{ $p->pengajuan->pengaju->name ?? 'N/A' }}</td>
                                            <td class="text-right">{{ rp($p->nominal_pencairan) }}</td>
                                            <td class="text-right">{{ rp($p->total_telah_dipertanggungjawabkan) }}
                                            </td>
                                            <td class="text-right">
                                                {{ rp($p->sisa_yang_belum_dipertanggungjawabkan) }}</td>
                                            <td class="text-center">
                                                <button class="btn btn-success btn-xs btn-select"
                                                    data-id="{{ $p->id }}" data-kode="{{ $p->kode_pencairan }}"
                                                    data-nama="{{ $p->pengajuan->pengaju->name ?? 'N/A' }}"
                                                    data-nominalcair="{{ $p->nominal_pencairan }}"
                                                    data-totalpj="{{ $p->total_telah_dipertanggungjawabkan }}">
                                                    <i class="fal fa-check"></i> Pilih
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada data pencairan yang perlu
                                                dipertanggungjawabkan.</td>
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
    <script>
        $(document).ready(function() {
            var table = $('#pencairan-popup-table').DataTable({
                responsive: true,
                pageLength: 10,
                lengthChange: false,
                order: [
                    [1, 'desc']
                ],
                columnDefs: [{
                    orderable: false,
                    targets: 6
                }],
                language: {
                    search: "Pencarian:",
                    zeroRecords: "Data tidak ditemukan",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    infoEmpty: "Data tidak tersedia",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: ">>",
                        previous: "<<"
                    }
                }
            });

            $('#pencairan-popup-table tbody').on('click', '.btn-select', function() {
                var data = $(this).data();

                if (window.opener && !window.opener.closed && typeof window.opener.selectPencairan ===
                    'function') {
                    window.opener.selectPencairan(
                        data.id,
                        data.kode,
                        data.nama,
                        data.nominalcair,
                        data.totalpj
                    );
                    window.close();
                } else {
                    alert('Tidak dapat mengirim data. Jendela induk mungkin sudah ditutup.');
                }
            });
        });
    </script>
@endsection
