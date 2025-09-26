@extends('inc.layout')
@section('title', 'Daftar Menu Gizi')

@section('extended-css')
    <link rel="stylesheet" media="screen, print" href="/css/datagrid/datatables/datatables.bundle.css">
    <link rel="stylesheet" media="screen, print" href="/css/formplugins/select2/select2.bundle.css">

    {{-- CSS BARU UNTUK CHILD ROW DENGAN BOXICONS --}}
    <style>
        td.details-control {
            text-align: center;
            cursor: pointer;
            width: 25px;
            /* Beri lebar tetap agar rapi */
        }

        /* Menggunakan pseudo-element untuk ikon Boxicons */
        td.details-control::before {
            font-family: "Boxicons" !important;
            /* Nama font untuk Boxicons */
            font-weight: normal;
            /* Bobot font standar untuk Boxicons */
            content: "\ebc1";
            /* Unicode untuk ikon bx-plus-square */
            color: #28a745;
            /* Warna hijau untuk "buka" */
            font-size: 1.5rem;
            /* Ukuran ikon bisa disesuaikan */
            line-height: 1;
            vertical-align: middle;
        }

        tr.shown td.details-control::before {
            content: "\eb8d";
            /* Unicode untuk ikon bx-minus-square */
            color: #dc3545;
            /* Warna merah untuk "tutup" */
        }

        /* Style untuk konten child row (tetap sama) */
        .child-row-content {
            padding: 10px 15px;
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
        }
    </style>
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Filter Pencarian Menu</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="filter-form">
                                <div class="form-group row align-items-center">
                                    <label class="col-md-2 col-form-label text-right" for="nama_menu_filter">Nama
                                        Menu</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="nama_menu_filter" name="nama_menu">
                                    </div>
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-primary waves-effect waves-themed">
                                            <i class="fal fa-search mr-1"></i> Cari
                                        </button>
                                        <button type="button" class="btn btn-success waves-effect waves-themed"
                                            data-toggle="modal" data-target="#addMenuModal">
                                            <i class="fal fa-plus mr-1"></i> Tambah Menu
                                        </button>
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
                        <h2>Daftar Menu Gizi</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="dt-menu" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th></th> {{-- Kolom kosong untuk tombol expander --}}
                                        <th>No</th>
                                        <th>Nama Menu</th>
                                        <th>Kategori</th>
                                        <th>Harga</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- Render modal tambah di sini --}}
    @include('pages.simrs.gizi.partials.add-menu-modal', ['foods' => $foods, 'categories' => $categories])

@endsection

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    {{-- Penting! Kirim data makanan ke JS untuk dipakai di modal --}}
    <script>
        window.allFoods = @json(
            $foods->mapWithKeys(function ($item) {
                return [$item['id'] => $item];
            }));
    </script>
    <script src="/js/simrs/menu-gizi.js"></script>
@endsection
