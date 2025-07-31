@extends('inc.layout-no-side')
@section('title', 'Daftar Order Operasi')
@section('content')
    <style>
        table {
            font-size: 8pt !important;
        }

        .modal-lg {
            max-width: 800px;
        }

        /* CSS untuk Details Control */
        table.dataTable thead .sorting:after,
        table.dataTable thead .sorting_asc:after,
        table.dataTable thead .sorting_desc:after,
        table.dataTable thead .sorting_asc_disabled:after,
        table.dataTable thead .sorting_desc_disabled:after {
            display: none !important;
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

        #dt-basic-example tbody tr:hover {
            background-color: #f8f9fa;
        }

        .details-control {
            cursor: pointer;
        }

        .dt-hasChild {
            background-color: #f5f5f5 !important;
        }
    </style>
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Tindakan Operasi untuk:
                            <span class="fw-300">
                                <i>{{ $order->registration->patient->name ?? 'Pasien Tidak Ditemukan' }} (RM:
                                    {{ $order->registration->patient->medical_record_number ?? 'N/A' }})</i>
                            </span>
                        </h2>
                        <div class="panel-toolbar">
                            <button id="btn-tambah-tindakan" class="btn btn-primary btn-sm">
                                <i class="fal fa-plus mr-1"></i> Tambah Tindakan
                            </button>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                                    </button>
                                    <strong>Berhasil!</strong> {{ session('success') }}
                                </div>
                            @endif

                            {{-- Tabel untuk menampilkan daftar tindakan --}}
                            <table id="dt-tindakan" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>#</th>
                                        <th>Tindakan</th>
                                        <th>Dokter Operator</th>
                                        <th>Waktu Mulai</th>
                                        <th>Waktu Selesai</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Data akan diisi dari server-side atau client-side,
                                         jika client-side, loop di sini. --}}
                                    @foreach ($order->prosedurOperasi as $key => $prosedur)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $prosedur->tindakanOperasi->nama_tindakan ?? 'N/A' }}</td>
                                            <td>{{ $prosedur->dokterOperator->fullname ?? 'N/A' }}</td>
                                            <td>{{ $prosedur->waktu_mulai ? \Carbon\Carbon::parse($prosedur->waktu_mulai)->format('d-m-Y H:i') : '-' }}
                                            </td>
                                            <td>{{ $prosedur->waktu_selesai ? \Carbon\Carbon::parse($prosedur->waktu_selesai)->format('d-m-Y H:i') : '-' }}
                                            </td>
                                            <td>
                                                @php
                                                    $statusClass = 'secondary';
                                                    if ($prosedur->status == 'selesai') {
                                                        $statusClass = 'success';
                                                    }
                                                    if ($prosedur->status == 'berlangsung') {
                                                        $statusClass = 'info';
                                                    }
                                                    if ($prosedur->status == 'rencana') {
                                                        $statusClass = 'warning';
                                                    }
                                                    if ($prosedur->status == 'batal') {
                                                        $statusClass = 'danger';
                                                    }
                                                @endphp
                                                <span
                                                    class="badge badge-{{ $statusClass }}">{{ ucfirst($prosedur->status) }}</span>
                                            </td>
                                            <td>
                                                <a href="#" class="btn btn-xs btn-icon btn-warning"
                                                    title="Edit Tindakan"><i class="fal fa-edit"></i></a>
                                                <a href="#" class="btn btn-xs btn-icon btn-danger"
                                                    title="Hapus Tindakan"><i class="fal fa-trash-alt"></i></a>
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
    <script>
        $(document).ready(function() {
            // Inisialisasi DataTable dengan konfigurasi bahasa
            $('#dt-tindakan').DataTable({
                responsive: true,
                pageLength: 10,
                // Konfigurasi untuk pesan fallback jika data kosong
                language: {
                    emptyTable: "Belum ada tindakan operasi yang ditambahkan untuk order ini.",
                    zeroRecords: "Tidak ada data yang cocok dengan pencarian Anda.",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
                    infoFiltered: "(difilter dari _MAX_ total entri)",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    search: "Cari:",
                    paginate: {
                        first: "Awal",
                        last: "Akhir",
                        next: "Berikutnya",
                        previous: "Sebelumnya"
                    },
                }
            });

            // Event listener untuk tombol "Tambah Tindakan"
            $('#btn-tambah-tindakan').on('click', function() {
                var orderId = "{{ $order->id }}";
                var url = "{{ route('ok.prosedur.create', ['order' => ':id']) }}";
                url = url.replace(':id', orderId);

                var width = 1200;
                var height = 800;
                var left = (screen.width - width) / 2;
                var top = (screen.height - height) / 2;

                window.open(url, 'InputTindakanOperasi',
                    `width=${width},height=${height},top=${top},left=${left},resizable=yes,scrollbars=yes`
                    );
            });
        });
    </script>
@endsection
