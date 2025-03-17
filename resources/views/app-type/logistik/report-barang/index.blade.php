@php
    use App\Models\Barang;
    use Carbon\Carbon;
@endphp

@extends('inc.layout')
@section('title', 'Report Barang')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-3">
                <div class="card mb-2">
                    <div class="card-body p-1 d-flex align-content-center" style="height: 5rem;">
                        <a href="{{ route('inventaris.rooms.index') }}" class="w-100">
                            <div class="row justify-content-center align-items-center w-100">
                                <div class="ml-3 mt-2 col-2">
                                    <div class="icon-stack display-3 flex-shrink-0">
                                        <i class="fal fa-circle icon-stack-3x opacity-100 color-primary-400"></i>
                                        <i class="fas fa-home icon-stack-1x opacity-100 color-primary-500"></i>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="ml-3 mt-2">
                                        <strong style="font-size: 1.5em">
                                            Ruangan
                                        </strong>
                                    </div>
                                </div>
                                <div class="col-3" style="text-align: right;">
                                    <span
                                        style="font-size: 2rem; font-weight: bold; color: rgba(0,0,0,0.2)">{{ count($rooms) }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xl-3">
                <div class="card mb-2">
                    <div class="card-body p-1 d-flex align-content-center" style="height: 5rem;">
                        <a href="{{ route('inventaris.category.index') }}" class="w-100">
                            <div class="row justify-content-center align-items-center w-100">
                                <div class="ml-3 mt-2 col-2">
                                    <div class="icon-stack display-3 flex-shrink-0">
                                        <i class="fal fa-circle icon-stack-3x opacity-100 color-primary-400"></i>
                                        <i class="fas fa-list-alt icon-stack-1x opacity-100 color-primary-500"></i>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="ml-3 mt-2">
                                        <strong style="font-size: 1.5em">
                                            Kategori Barang
                                        </strong>
                                    </div>
                                </div>
                                <div class="col-3" style="text-align: right;">
                                    <span
                                        style="font-size: 2rem; font-weight: bold; color: rgba(0,0,0,0.2)">{{ count($category) }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xl-3">
                <div class="card mb-2">
                    <div class="card-body p-1 d-flex align-content-center" style="height: 5rem;">
                        <a href="{{ route('inventaris.template.index') }}" class="w-100">
                            <div class="row justify-content-center align-items-center w-100">
                                <div class="ml-3 mt-2 col-2">
                                    <div class="icon-stack display-3 flex-shrink-0">
                                        <i class="fal fa-circle icon-stack-3x opacity-100 color-primary-400"></i>
                                        <i class="fas fa-clipboard icon-stack-1x opacity-100 color-primary-500"></i>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="ml-3 mt-2">
                                        <strong style="font-size: 1.5em">
                                            Template Barang
                                        </strong>
                                    </div>
                                </div>
                                <div class="col-3" style="text-align: right;">
                                    <span
                                        style="font-size: 2rem; font-weight: bold; color: rgba(0,0,0,0.2)">{{ count($template) }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xl-3">
                <div class="card mb-2">
                    <div class="card-body p-1 d-flex align-content-center" style="height: 5rem;">
                        <a href="{{ route('inventaris.barang.index') }}" class="w-100">
                            <div class="row justify-content-center align-items-center w-100">
                                <div class="ml-3 mt-2 col-2">
                                    <div class="icon-stack display-3 flex-shrink-0">
                                        <i class="fal fa-circle icon-stack-3x opacity-100 color-primary-400"></i>
                                        <i class="fas fa-cube icon-stack-1x opacity-100 color-primary-500"></i>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="ml-3 mt-2">
                                        <strong style="font-size: 1.5em">
                                            Barang
                                        </strong>
                                    </div>
                                </div>
                                <div class="col-3" style="text-align: right;">
                                    <span
                                        style="font-size: 2rem; font-weight: bold; color: rgba(0,0,0,0.2)">{{ count($barang) }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Report Barang <span class="fw-300"><i>Table</i></span>
                        </h2>
                        @include('pages.partials.panel-toolbar')
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- datatable start -->
                            <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                <thead>
                                    <tr>
                                        <th style="white-space: nowrap">No</th>
                                        <th style="white-space: nowrap">Keterangan</th>
                                        <th style="white-space: nowrap">Tanggal</th>
                                        <th style="white-space: nowrap">Petugas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reports as $report)
                                        <tr>
                                            <td style="white-space: nowrap">{{ $loop->iteration }}</td>
                                            <td>
                                                {{ $report->keterangan }}
                                            </td>
                                            <td>
                                                {{ Carbon::parse($report->created_at)->format('j F Y') }}
                                            </td>
                                            <td style="white-space: nowrap">{{ $report->user->name }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th style="white-space: nowrap">No</th>
                                        <th style="white-space: nowrap">Keterangan</th>
                                        <th style="white-space: nowrap">Tanggal</th>
                                        <th style="white-space: nowrap">Petugas</th>
                                    </tr>
                                </tfoot>
                            </table>
                            <!-- datatable end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/datatable/jszip.min.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        /* demo scripts for change table color */
        /* change background */
        $(document).ready(function() {
            $('#dt-basic-example').DataTable({
                // responsive: true,
                // scrollY: 400,
                // scrollX: true,
                // scrollCollapse: true,
                // paging: true,
                pageLength: 200,
                //fixedColumns: true,
                fixedColumns: {
                    leftColumns: 2,
                },
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'B>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                        extend: 'colvis',
                        text: '<i class="fas fa-eye"></i> Visibility',
                        titleAttr: 'Col visibility',
                        className: 'btn-primary'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Print',
                        titleAttr: 'Print Table',
                        className: 'btn-primary',
                        exportOptions: {
                            columns: ':visible' // Menggunakan kolom yang terlihat sesuai pengaturan ColVis
                        },
                        customize: function(win) {
                            $(win.document.body).find('table').addClass('display').css('font-size',
                                '12px'); // Menambahkan kelas dan menyesuaikan ukuran font
                            $(win.document.body).find('thead').addClass(
                                'thead-light'); // Menambahkan kelas untuk style header
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        titleAttr: 'Export to Excel',
                        className: 'btn-primary',
                        exportOptions: {
                            columns: ':visible' // Menggunakan kolom yang terlihat sesuai pengaturan ColVis
                        }
                    }
                ]
            });

            $('.js-thead-colors a').on('click', function() {
                var theadColor = $(this).attr("data-bg");
                console.log(theadColor);
                $('#dt-basic-example thead').removeClassPrefix('bg-').addClass(theadColor);
            });

            $('.js-tbody-colors a').on('click', function() {
                var theadColor = $(this).attr("data-bg");
                console.log(theadColor);
                $('#dt-basic-example').removeClassPrefix('bg-').addClass(theadColor);
            });

        });

        function previewImage() {
            const image = document.querySelector('#foto');
            const imgPreview = document.querySelector('.image-preview')

            imgPreview.style.display = 'block';

            const oFReader = new FileReader();
            oFReader.readAsDataURL(image.files[0])

            oFReader.onload = function(oFREvent) {
                imgPreview.src = oFREvent.target.result;
            }
        }

        function previewImage2() {
            const image = document.querySelector('#foto2');
            const imgPreview = document.querySelector('.image-preview2')

            imgPreview.style.display = 'block';

            const oFReader = new FileReader();
            oFReader.readAsDataURL(image.files[0])

            oFReader.onload = function(oFREvent) {
                imgPreview.src = oFREvent.target.result;
            }
        }
    </script>
@endsection
