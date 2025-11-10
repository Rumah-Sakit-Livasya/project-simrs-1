@extends('inc.layout')
@section('title', 'Manajemen Template SOAP')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-lg-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            <i class="fal fa-file-medical-alt mr-2"></i>Daftar Template SOAP
                        </h2>
                        <div class="panel-toolbar">
                            <a href="{{ route('dokter.template-soap.create') }}" class="btn btn-primary btn-sm">
                                <i class="fal fa-plus-circle mr-1"></i> Tambah Template
                            </a>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- datatable start -->
                            <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th class="text-center" style="width: 5%">No</th>
                                        <th>Nama Template</th>
                                        <th class="text-center" style="width: 15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
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
    <script>
        $(document).ready(function() {
            // Inisialisasi DataTables
            var table = $('#dt-basic-example').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('dokter.template-soap.data') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'template_name',
                        name: 'template_name'
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

            // Handle tombol delete
            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');

                showDeleteConfirmation(function() {
                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: form.serialize(),
                        success: function(response) {
                            if (response.status === 'success') {
                                showSuccessAlert(response.message);
                                table.ajax.reload();
                            } else {
                                showErrorAlert(response.message);
                            }
                        },
                        error: function() {
                            showErrorAlert('Terjadi kesalahan. Silakan coba lagi.');
                        }
                    });
                });
            });
        });
    </script>
@endsection
