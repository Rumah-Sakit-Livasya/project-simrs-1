@extends('inc.layout')
@section('title', 'Daftar Rekam Medis')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Daftar Departement
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- datatable start -->
                            <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                <i id="loading-spinner" class="fas fa-spinner fa-spin"></i>
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>#</th>
                                        <th>Nama</th>
                                        <th>Kode</th>
                                        <th>Keterangan</th>
                                        <th>Quota</th>
                                        <th>Kode Poli</th>
                                        <th>Publish Online</th>
                                        <th>Revenue and Cost Center</th>
                                        <th>Master Layanan RL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($departements as $departement)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                {{ $departement->name }}
                                            </td>
                                            <td>
                                                {{ $departement->kode }}
                                            </td>
                                            <td>
                                                {{ $departement->keterangan }}
                                            </td>
                                            <td>
                                                {{ $departement->quota }}
                                            </td>
                                            <td>
                                                {{ $departement->kode_poli }}
                                            </td>
                                            <td>
                                                {{ $departement->publish_online }}
                                            </td>
                                            <td>
                                                {{ $departement->revenue_and_cost_center }}
                                            </td>
                                            <td>
                                                {{ $departement->master_layanan_rl }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="9" class="text-center">
                                            <a href="{{ route('master.data.setup.tambah.departement') }}"
                                                class="btn-outline-primary waves-effect waves-themed">
                                                <span class="fal fa-plus-circle"></span>
                                                Tambah Departement
                                            </a>
                                        </th>
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
    <script>
        $(document).ready(function() {
            $('#loading-spinner').show();
            // initialize datatable
            $('#dt-basic-example').dataTable({
                "drawCallback": function(settings) {
                    // Menyembunyikan preloader setelah data berhasil dimuat
                    $('#loading-spinner').hide();
                },
                responsive: true,
                lengthChange: false,
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                        extend: 'pdfHtml5',
                        text: 'PDF',
                        titleAttr: 'Generate PDF',
                        className: 'btn-outline-danger btn-sm mr-1'
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        titleAttr: 'Generate Excel',
                        className: 'btn-outline-success btn-sm mr-1'
                    },
                    {
                        extend: 'csvHtml5',
                        text: 'CSV',
                        titleAttr: 'Generate CSV',
                        className: 'btn-outline-primary btn-sm mr-1'
                    },
                    {
                        extend: 'copyHtml5',
                        text: 'Copy',
                        titleAttr: 'Copy to clipboard',
                        className: 'btn-outline-primary btn-sm mr-1'
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        titleAttr: 'Print Table',
                        className: 'btn-outline-primary btn-sm'
                    }
                ]
            });

        });


        // Input RM
        function formatAngka(input) {
            var value = input.value.replace(/\D/g, '');
            var formattedValue = '';

            if (value.length > 6) {
                value = value.substr(0, 6);
            }

            if (value.length > 0) {
                formattedValue = value.match(/.{1,2}/g).join('-');
            }

            input.value = formattedValue;
        }
    </script>
@endsection
