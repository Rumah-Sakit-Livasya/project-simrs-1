{{-- @dd($reports[0]->user->name) --}}

@php
    use App\Models\Barang;
    use Carbon\Carbon;
@endphp
@extends('inc.layout')
@section('title', 'Dashboard')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        @include('inc.breadcrumb', ['bcrumb' => 'bc_level_dua', 'bc_1' => 'Inventaris Barang'])
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-6">
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
                                        <div class="col-4" style="text-align: right;">
                                            <span
                                                style="font-size: 2rem; font-weight: bold; color: rgba(0,0,0,0.2)">{{ $rooms }}</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
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
                                        <div class="col-4" style="text-align: right;">
                                            <span
                                                style="font-size: 2rem; font-weight: bold; color: rgba(0,0,0,0.2)">{{ $categories }}</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-lg-6">
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
                                        <div class="col-4" style="text-align: right;">
                                            <span
                                                style="font-size: 2rem; font-weight: bold; color: rgba(0,0,0,0.2)">{{ $barang }}</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
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
                                        <div class="col-4" style="text-align: right;">
                                            <span
                                                style="font-size: 2rem; font-weight: bold; color: rgba(0,0,0,0.2)">{{ $template }}</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-lg-6">
                        <div class="card mb-2">
                            <div class="card-body p-1 d-flex align-content-center" style="height: 5rem;">
                                <a href="/user" class="w-100">
                                    <div class="row justify-content-center align-items-center w-100">
                                        <div class="ml-3 mt-2 col-2">
                                            <div class="icon-stack display-3 flex-shrink-0">
                                                <i class="fal fa-circle icon-stack-3x opacity-100 color-primary-400"></i>
                                                <i class="fas fa-users icon-stack-1x opacity-100 color-primary-500"></i>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="ml-3 mt-2">
                                                <strong style="font-size: 1.5em">
                                                    User
                                                </strong>
                                            </div>
                                        </div>
                                        <div class="col-4" style="text-align: right;">
                                            <span
                                                style="font-size: 2rem; font-weight: bold; color: rgba(0,0,0,0.2)">{{ $users }}</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div> --}}
                    {{-- <div class="col-lg-6">
                        <div class="card mb-2">
                            <div class="card-body p-1 d-flex align-content-center" style="height: 5rem;">
                                <a class="w-100" href="/cpanel">
                                    <div class="row justify-content-center align-items-center w-100">
                                        <div class="ml-3 mt-2 col-2">
                                            <div class="icon-stack display-3 flex-shrink-0">
                                                <i class="fal fa-circle icon-stack-3x opacity-100 color-primary-400"></i>
                                                <i class="fas fa-cog icon-stack-1x opacity-100 color-primary-500"></i>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="ml-3 mt-2">
                                                <strong style="font-size: 1.5em">
                                                    Control Panel
                                                </strong>
                                            </div>
                                        </div>
                                        <div class="col-4" style="text-align: right; margin-top: 4.3rem;">
                                            <i style="font-size: 2rem; font-weight: bold; color: rgba(0,0,0,0.2)"
                                                class="fas fa-arrow-right icon-stack-1x opacity-100 color-primary-500"></i>
                                        </div>
                                    </div>
                            </div>
                            </a>
                        </div>
                    </div> --}}
                </div>

                <div id="panel-1" class="panel panel-locked mt-5" data-panel-lock="false" data-panel-close="false"
                    data-panel-fullscreen="false" data-panel-collapsed="false" data-panel-color="false"
                    data-panel-locked="false" data-panel-refresh="false" data-panel-reset="false">
                    <div class="panel-hdr">
                        <h2>
                            <i class='bx bx-history text-muted' style="transform: scale(2)"></i> <span
                                class="ml-3">Riwayat</span>
                        </h2>
                        <div class="panel-toolbar pr-3 align-self-end">
                            <ul id="demo_panel-tabs" class="nav nav-tabs border-bottom-0 nav-tabs-clean" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#tab_default-1"
                                        role="tab">Inventaris Barang</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="   tab" href="#tab_default-2"
                                        role="tab">Revenue</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content border-faded border-left-0 border-right-0 border-top-0">
                            <div class="row no-gutters">
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
                                                    {{ Carbon::parse($report->created_at)->isoFormat('LLL') }}
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('plugin')
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/miscellaneous/fullcalendar/fullcalendar.bundle.js"></script>
    <script src="/js/statistics/sparkline/sparkline.bundle.js"></script>
    <script src="/js/statistics/easypiechart/easypiechart.bundle.js"></script>
    <script src="/js/statistics/flot/flot.bundle.js"></script>
    <script src="/js/miscellaneous/jqvmap/jqvmap.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/datatable/jszip.min.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            $('#dt-basic-example').dataTable({
                responsive: true,
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'print',
                        text: 'Print',
                        className: 'float-right btn btn-primary mr-2',
                        exportOptions: {
                            columns: ':not(.no-export)'
                        }
                    },
                    {
                        extend: 'excel',
                        text: 'Download as Excel',
                        className: 'float-right btn btn-success mr-2',
                        exportOptions: {
                            columns: ':not(.no-export)'
                        }
                    },
                    {
                        extend: 'colvis',
                        text: 'Column Visibility',
                        titleAttr: 'Col visibility',
                        className: 'float-right mb-3 btn btn-warning mr-2',
                        exportOptions: {
                            columns: ':not(.no-export)'
                        },
                        postfixButtons: [{
                                extend: 'print',
                                text: 'Print',
                                exportOptions: {
                                    columns: ':visible:not(.no-export)'
                                }
                            },
                            {
                                extend: 'excel',
                                text: 'Download as Excel',
                                exportOptions: {
                                    columns: ':visible:not(.no-export)'
                                }
                            }
                        ]
                    },
                    {
                        text: '<i class="fas fa-arrow-right mr-2"></i>Lihat Laporan',
                        className: 'float-right mr-3 btn btn-info',
                        action: function(e, dt, node, config) {
                            window.location.href = "{{ route('inventaris.report.index') }}";
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
    </script>
@endsection
