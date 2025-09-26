@extends('inc.layout')
@section('title', 'Kategori Menu Gizi')

@section('extended-css')
    <link rel="stylesheet" media="screen, print" href="/css/datagrid/datatables/datatables.bundle.css">
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Filter Pencarian Kategori</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="filter-form">
                                <div class="form-group row align-items-center">
                                    <label class="col-md-2 col-form-label text-right" for="nama_kategori_filter">Nama
                                        Kategori</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="nama_kategori_filter"
                                            name="nama_kategori">
                                    </div>
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-primary waves-effect waves-themed">
                                            <i class="fal fa-search mr-1"></i> Cari
                                        </button>
                                        <button type="button" class="btn btn-success waves-effect waves-themed"
                                            data-toggle="modal" data-target="#addKategoriModal">
                                            <i class="fal fa-plus mr-1"></i> Tambah Kategori
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
                        <h2>Daftar Kategori Gizi</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="dt-kategori" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Kategori</th>
                                        <th>COA Pendapatan</th>
                                        <th>COA Biaya</th>
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

    @include('pages.simrs.gizi.partials.add-kategori-modal')

@endsection

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/simrs/kategori-gizi.js"></script>
@endsection
