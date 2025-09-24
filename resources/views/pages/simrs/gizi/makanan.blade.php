@extends('inc.layout')
@section('title', 'Daftar Makanan Gizi')

@section('extended-css')
    <link rel="stylesheet" media="screen, print" href="/css/datagrid/datatables/datatables.bundle.css">
    <link rel="stylesheet" media="screen, print" href="/css/formplugins/select2/select2.bundle.css">
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Filter Pencarian Makanan</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="filter-form">
                                <div class="form-group row align-items-center">
                                    <label class="col-md-2 col-form-label text-right" for="nama_makanan_filter">Nama
                                        Makanan</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="nama_makanan_filter"
                                            name="nama_makanan">
                                    </div>
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-primary waves-effect waves-themed">
                                            <i class="fal fa-search mr-1"></i> Cari
                                        </button>
                                        <button type="button" class="btn btn-success waves-effect waves-themed"
                                            data-toggle="modal" data-target="#addModal">
                                            <i class="fal fa-plus mr-1"></i> Tambah Makanan
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
                        <h2>Daftar Makanan</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- datatable start -->
                            <table id="dt-makanan" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Makanan</th>
                                        <th>Harga</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data akan diisi oleh DataTables -->
                                </tbody>
                            </table>
                            <!-- datatable end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- Include Add Modal --}}
    @include('pages.simrs.gizi.partials.add-makanan-modal')

@endsection

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/simrs/makanan-gizi.js"></script>
@endsection
