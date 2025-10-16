{{-- resources/views/pages/simrs/applicare/daftar-ketersediaan-kamar.blade.php --}}

@extends('inc.layout')
@section('title', 'Ketersediaan Kamar RS BPJS')
@section('extended-css')
    <link rel="stylesheet" media="screen, print" href="/css/datagrid/datatables/datatables.bundle.css">
    <link rel="stylesheet" media="screen, print" href="/css/formplugins/select2/select2.bundle.css">
@endsection
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        {{-- Breadcrumb dan Subheader (TETAP) --}}
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">SIMRS</a></li>
            <li class="breadcrumb-item">BPJS Aplicare</li>
            <li class="breadcrumb-item active">Ketersediaan Kamar</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>
        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-bed'></i> Ketersediaan Kamar <span class='fw-300'>RS BPJS</span>
                <small>
                    Informasi ketersediaan tempat tidur yang ditarik dari BPJS Aplicare.
                </small>
            </h1>
        </div>

        <div class="panel-container show">
            <div class="panel-content">
                <div class="row mb-5">
                    <div class="col-12 text-right">
                        {{-- TOMBOL TAMBAH DITINGGALKAN SEMENTARA --}}
                        {{-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTambahRuangan">
                        <i class="fal fa-plus mr-1"></i> Tambah Ruangan Baru
                    </button> --}}
                        <button type="button" class="btn btn-secondary" onclick="refreshTable()">
                            <i class="fal fa-sync-alt mr-1"></i> Refresh Data
                        </button>
                    </div>
                </div>

                {{-- Data Table Ketersediaan Kamar --}}
                <div class="row">
                    <div class="col-xl-12">
                        <div id="panel-1" class="panel">
                            <div class="panel-hdr">
                                <h2>Daftar <span class="fw-300"><i>Ketersediaan</i></span> Kamar</h2>
                            </div>
                            <div class="panel-container show">
                                <div class="panel-content">
                                    {{-- Jika ada pesan error dari Controller, tampilkan di sini --}}
                                    @if (session('error'))
                                        <div class="alert alert-danger" role="alert">
                                            <strong>Error Koneksi/API:</strong> {{ session('error') }}
                                        </div>
                                    @endif
                                    @if (session('warning'))
                                        <div class="alert alert-warning" role="alert">
                                            <strong>Peringatan:</strong> {{ session('warning') }}
                                        </div>
                                    @endif

                                    <table id="dt-ketersediaan-kamar"
                                        class="table table-bordered table-hover table-striped w-100">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Kode Kelas</th>
                                                <th>Nama Kelas</th>
                                                <th>Kode Ruang</th>
                                                <th>Nama Ruang</th>
                                                <th>Kapasitas</th>
                                                <th>Tersedia</th>
                                                <th>Tersedia Pria</th>
                                                <th>Tersedia Wanita</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- Data dari Controller akan di-loop di sini --}}
                                            @forelse ($listKamar as $index => $kamar)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $kamar['kodekelas'] }}</td>
                                                    <td>{{ $kamar['namakelas'] }}</td>
                                                    <td>{{ $kamar['koderuang'] }}</td>
                                                    <td>{{ $kamar['namaruang'] }}</td>
                                                    <td>{{ $kamar['kapasitas'] }}</td>
                                                    <td>{{ $kamar['tersedia'] }}</td>
                                                    <td>{{ $kamar['tersediapria'] ?? '-' }}</td>
                                                    <td>{{ $kamar['tersediawanita'] ?? '-' }}</td>
                                                    {{-- Kolom Aksi dikosongkan/dihapus sementara --}}
                                                    <td>-</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="10" class="text-center">Tidak ada data ketersediaan kamar
                                                        yang ditemukan atau koneksi gagal.</td>
                                                </tr>
                                            @endforelse
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

    {{-- MODAL TAMBAH/UPDATE/DELETE DIHILANGKAN SEMENTARA --}}

@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
@endsection
@section('plugin-secondary')
    <script>
        $(document).ready(function() {
            // Inisialisasi Datatables
            $('#dt-ketersediaan-kamar').dataTable({
                responsive: true,
                pageLength: 25,
                dom: '<"row mb-3"<"col-sm-12 col-md-6 d-flex align-items-center justify-content-start"f><"col-sm-12 col-md-6 d-flex align-items-center justify-content-end"lB>>' +
                    '<"row"<"col-sm-12"tr>>' +
                    '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                buttons: [{
                        extend: 'print',
                        text: 'Print'
                    },
                    {
                        extend: 'copy',
                        text: 'Copy'
                    },
                    {
                        extend: 'excel',
                        text: 'Excel'
                    },
                    {
                        extend: 'colvis',
                        text: 'Column Visibility',
                        className: 'btn-sm'
                    }
                ]
            });

            // Modal logic (DITINGGALKAN SEMENTARA)
            // $('#create_kodekelas').select2({ ... });
            // $('#dt-ketersediaan-kamar').on('click', '.btn-delete', function() { ... });
        });

        // Fungsi untuk Refresh Data (hanya reload halaman)
        function refreshTable() {
            location.reload();
        }
    </script>
@endsection
