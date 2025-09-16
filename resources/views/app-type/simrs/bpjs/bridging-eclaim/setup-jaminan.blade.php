@extends('inc.layout')
@section('title', 'Daftar Jaminan')
@section('content')

    <style>
        table {
            font-size: 8pt;
        }
    </style>
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Daftar <span class="fw-300"><i>Jaminan</i></span>
                        </h2>
                        <div class="panel-toolbar">
                            <a href="/jaminan/create" class="btn btn-primary btn-sm">
                                <i class="fal fa-plus-circle mr-1"></i>
                                Tambah Jaminan
                            </a>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="dt-daftar-jaminan" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th class="text-center" style="width: 5%;">#</th>
                                        <th style="width: 15%;">Payplan ID</th>
                                        <th>Payment Plan Name</th>
                                        <th style="width: 15%;">Code</th>
                                        <th>Description</th>
                                        <th class="text-center" style="width: 15%;">Fungsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Data will be populated by DataTables --}}
                                </tbody>
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
    <script src="/js/formplugins/sweetalert2/sweetalert2.bundle.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTables
            var table = $('#dt-daftar-jaminan').DataTable({
                responsive: true,
                // The DOM structure is configured to match the screenshot's layout
                // t = table, i = info, p = pagination, l = lengthMenu
                dom: "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                pageLength: 50, // Set default entries to 50 as in the image
                lengthChange: false, // Hide the length change dropdown if not needed, or set to true to show it
                columns: [{
                        data: null,
                        searchable: false,
                        orderable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'payplan_id'
                    },
                    {
                        data: 'payment_plan_name'
                    },
                    {
                        data: 'code'
                    },
                    {
                        data: 'description'
                    },
                    {
                        data: 'payplan_id', // Use a unique ID for actions
                        searchable: false,
                        orderable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            // Instead of a broken image, we use modern buttons with icons
                            var editUrl = `/jaminan/${data}/edit`;
                            var deleteFunc = `deleteJaminan(${data})`;
                            return `
                                <a href="${editUrl}" class="btn btn-xs btn-icon btn-outline-primary" data-toggle="tooltip" title="Edit Jaminan">
                                    <i class="fal fa-pencil"></i>
                                </a>
                                <button onclick="${deleteFunc}" class="btn btn-xs btn-icon btn-outline-danger" data-toggle="tooltip" title="Hapus Jaminan">
                                    <i class="fal fa-trash"></i>
                                </button>
                            `;
                        }
                    }
                ],
                // Add row counter
                "fnDrawCallback": function(oSettings) {
                    var api = this.api();
                    api.column(0, {
                        search: 'applied',
                        order: 'applied'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }
            });

            // --- AJAX Call Placeholder ---
            // In a real application, you'd fetch data via AJAX.
            // For now, we use mock data.
            var mockData = [{
                    payplan_id: 1,
                    payment_plan_name: 'PASIEN BAYAR',
                    code: '999',
                    description: ''
                },
                {
                    payplan_id: 6,
                    payment_plan_name: 'JAMKESOS',
                    code: 'JKS',
                    description: ''
                },
                {
                    payplan_id: 5,
                    payment_plan_name: 'JAMKESDA',
                    code: '001',
                    description: ''
                },
                {
                    payplan_id: 3,
                    payment_plan_name: 'JKN',
                    code: 'JKN',
                    description: ''
                }
            ];

            // Load the mock data into the table
            table.clear().rows.add(mockData).draw();

        });

        // Example function for the delete button
        function deleteJaminan(id) {
            Swal.fire({
                title: 'Anda yakin?',
                text: "Data jaminan ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Place your AJAX delete request here
                    // For demo, we just show a success message
                    Swal.fire('Terhapus!', 'Data jaminan telah dihapus.', 'success');
                    // In a real app, you would reload the table after deletion:
                    // $('#dt-daftar-jaminan').DataTable().ajax.reload();
                }
            });
        }
    </script>
@endsection
