@extends('inc.layout')
@section('title', 'Daftar Pasien Gizi')

@section('extended-css')
    <link rel="stylesheet" media="screen, print" href="/css/datagrid/datatables/datatables.bundle.css">
    <link rel="stylesheet" media="screen, print" href="/css/formplugins/select2/select2.bundle.css">
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        @include('pages.simrs.gizi.partials.list-pasien-form')

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Daftar Pasien Rawat Inap</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="dt-pasien" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>No</th>
                                        <th>Kelas</th>
                                        <th>Ruang</th>
                                        <th>T. Tidur</th>
                                        <th>No. Reg</th>
                                        <th>Pasien</th>
                                        <th>Dokter</th>
                                        <th>Diagnosa</th>
                                        <th>Kategori Diet</th>
                                        <th>Asuransi</th>
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
@endsection

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/simrs/pasien-gizi.js"></script>
@endsection
