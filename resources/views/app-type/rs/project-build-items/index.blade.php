@extends('inc.layout')
@section('title', 'Katalog Item Proyek')

@section('extended-css')
    <link rel="stylesheet" media="screen, print" href="/css/datagrid/datatables/datatables.bundle.css">
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Project Build</a></li>
            <li class="breadcrumb-item active">Katalog Item Disetujui</li>
        </ol>
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Daftar Item yang Disetujui untuk Proyek</h2>
                        <div class="panel-toolbar">
                            <a href="{{ route('material-approvals.index') }}" class="btn btn-primary btn-sm">
                                <i class="fal fa-tasks"></i> Kelola Persetujuan Material
                            </a>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="alert alert-info">
                                Halaman ini menampilkan semua material yang telah mendapatkan status "Approved" dari modul
                                Persetujuan Material. Gunakan halaman ini sebagai referensi resmi untuk item yang boleh
                                digunakan dalam proyek.
                            </div>
                            <table id="project-item-table" class="table table-bordered table-hover table-striped w-100">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Material</th>
                                        <th>Merek / Tipe</th>
                                        <th>Dokumen Referensi</th>
                                        <th>Disetujui Oleh</th>
                                        <th>Detail</th>
                                    </tr>
                                </thead>
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
            var table = $('#project-item-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('project-build-items.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'material_name',
                        name: 'material_name'
                    },
                    {
                        data: null,
                        name: 'brand',
                        render: function(data, type, row) {
                            return (row.brand || '') + ' / ' + (row.type_or_model || '-');
                        }
                    },
                    {
                        data: 'document.document_number',
                        name: 'document.document_number',
                        defaultContent: '-'
                    },
                    {
                        data: 'reviewer.name',
                        name: 'reviewer.name',
                        defaultContent: 'N/A'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                ],
                responsive: true
            });
        });
    </script>
@endsection
