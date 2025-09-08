@extends('inc.layout')
@section('title', 'Chart of Account')
@section('content')
    <style>
        /* CSS tidak berubah, tetap gunakan styling yang sudah ada */
        #coa-table .coa-level-0 {
            font-weight: 600;
        }

        #coa-table .coa-level-1 {
            padding-left: 2rem !important;
        }

        #coa-table .coa-level-2 {
            padding-left: 4rem !important;
        }

        #coa-table .coa-level-3 {
            padding-left: 6rem !important;
        }

        .dataTables_wrapper .table-responsive {
            max-height: 65vh;
            overflow-y: auto;
        }

        #coa-table thead {
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .btn-group-filter.active {
            background-color: #3a8fe2;
            color: white;
            border-color: #3a8fe2;
        }

        .table-responsive {
            min-height: 400px;
        }

        #coa-table tbody tr:hover {
            background-color: #f1f3f5;
        }
    </style>

    <main id="js-page-content" role="main" class="page-content">
        <div class="row mb-3">
            <div class="col-xl-12 d-flex justify-content-between align-items-center">
                {{-- Tombol Filter Grup COA --}}
                <div>
                    {{-- TOMBOL BARU "SEMUA GRUP" --}}
                    <button class="btn btn-outline-primary btn-sm btn-group-filter active" data-group-id="all">Semua
                        Grup</button>

                    @if (isset($groupCOA) && $groupCOA->count() > 0)
                        @foreach ($groupCOA as $group)
                            <button class="btn btn-outline-primary btn-sm btn-group-filter"
                                data-group-id="{{ $group->id }}">{{ $group->name }}</button>
                        @endforeach
                    @else
                        <div class="alert alert-warning d-inline-block p-2">Tidak ada Grup COA.</div>
                    @endif
                </div>
                {{-- Tombol Aksi Utama --}}
                <div>
                    <button type="button" id="btn-tambah" class="btn btn-primary">
                        <i class="fal fa-plus-circle mr-1"></i> Tambah COA
                    </button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>List Chart of Account</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="coa-table">
                                    <thead class="bg-primary-600 text-white">
                                        <tr>
                                            <th>Chart Of Account</th>
                                            {{-- TAMBAHKAN KOLOM GRUP --}}
                                            <th>Grup</th>
                                            <th class="text-center">Header</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center" style="width: 100px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="coa-table-body">
                                        <tr>
                                            {{-- Sesuaikan colspan --}}
                                            <td colspan="5" class="text-center p-5">
                                                <div class="spinner-border text-primary" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                                <div class="mt-2">Memuat data Chart of Account...</div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- Modal tidak berubah, pastikan path include benar --}}
    {{-- @include('app-type.keuangan.chart-of-account.partials.create-coa') --}}
    {{-- @include('app-type.keuangan.chart-of-account.partials.update-coa') --}}
@endsection

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#tambah-coa .select2, #edit-coa .select2').select2({
                dropdownParent: $(this).closest('.modal'),
                width: '100%'
            });

            // Inisialisasi DataTable dengan kolom baru
            var table = $('#coa-table').DataTable({
                responsive: true,
                paging: false,
                scrollY: "60vh",
                scrollCollapse: true,
                data: [],
                // Definisi kolom yang diperbarui
                columns: [{
                        "data": "chart_of_account",
                        "title": "Chart Of Account"
                    },
                    {
                        "data": "group_name",
                        "title": "Grup"
                    },
                    {
                        "data": "header",
                        "title": "Header",
                        "className": "text-center"
                    },
                    {
                        "data": "status",
                        "title": "Status",
                        "className": "text-center"
                    },
                    {
                        "data": "aksi",
                        "title": "Aksi",
                        "className": "text-center"
                    }
                ],
                language: {
                    search: "Pencarian:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    zeroRecords: "Data tidak ditemukan",
                    info: "Menampilkan _TOTAL_ total data",
                    infoEmpty: "",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                },
                order: [],
                columnDefs: [{
                    orderable: false,
                    targets: [4]
                }]
            });

            let activeGroupId = 'all'; // Default group adalah 'all'

            function loadCoaData(groupId) {
                table.clear().draw();
                $('#coa-table-body').html(
                    '<tr><td colspan="5" class="text-center p-5"><div class="spinner-border text-primary"></div><div class="mt-2">Memuat...</div></td></tr>'
                );

                let url = (groupId === 'all') ?
                    "{{ route('coa.all') }}" :
                    "{{ route('coa.byGroup', ['group_id' => 'PLACEHOLDER']) }}".replace('PLACEHOLDER', groupId);

                activeGroupId = groupId; // Simpan grup aktif

                $.get(url)
                    .done(function(response) {
                        if (Array.isArray(response) && response.length > 0) {
                            let tableData = buildTableData(response, 0);
                            table.clear().rows.add(tableData).draw();
                        } else {
                            table.clear().draw(); // Tampilkan pesan 'Data tidak ditemukan' dari DataTables
                        }
                    })
                    .fail(function(xhr) {
                        console.error("AJAX Gagal:", xhr.responseText);
                        $('#coa-table-body').html(
                            '<tr><td colspan="5" class="text-center text-danger">Gagal memuat data. Silakan coba lagi.</td></tr>'
                        );
                    });
            }

            // Fungsi buildTableData diperbarui untuk menyertakan nama grup
            function buildTableData(data, level) {
                let rows = [];
                data.forEach(function(coa) {
                    let indentClass = `coa-level-${level}`;
                    let icon = coa.header ? '<i class="fal fa-folder-open mr-2 text-warning"></i>' :
                        '<i class="fal fa-file-alt mr-2 text-muted"></i>';
                    let headerBadge = coa.header ? '<span class="badge badge-primary">YES</span>' :
                        '<span class="badge badge-secondary">NO</span>';
                    let statusBadge = coa.status ? '<span class="badge badge-success">Aktif</span>' :
                        '<span class="badge badge-danger">Nonaktif</span>';

                    rows.push({
                        "chart_of_account": `<div class="${indentClass}">${icon}<strong>${coa.code}</strong> - ${coa.name}</div>`,
                        "group_name": coa.group_name,
                        "header": headerBadge,
                        "status": statusBadge,
                        "aksi": `<button class="btn btn-xs btn-outline-primary btn-edit-coa" data-coa-id="${coa.id}" title="Edit"><i class="fal fa-edit"></i></button>
                                 <button class="btn btn-xs btn-outline-danger btn-delete-coa" data-coa-id="${coa.id}" title="Hapus"><i class="fal fa-trash"></i></button>`
                    });

                    if (coa.children && coa.children.length > 0) {
                        rows = rows.concat(buildTableData(coa.children, level + 1));
                    }
                });
                return rows;
            }

            // Fungsi loadCoaDetailsForEdit tidak berubah
            function loadCoaDetailsForEdit(coaId) {
                /* ... sama seperti sebelumnya ... */
            }

            // Fungsi initParentDropdown tidak berubah
            function initParentDropdown(selectElement, groupId) {
                /* ... sama seperti sebelumnya ... */
            }

            // === EVENT LISTENERS ===
            $('.btn-group-filter').on('click', function() {
                $('.btn-group-filter').removeClass('active');
                $(this).addClass('active');
                loadCoaData($(this).data('group-id'));
            });

            $('#btn-tambah').on('click', function() {
                $('#store-form')[0].reset();
                // Jika grup spesifik sedang aktif, otomatis pilih grup itu di modal
                if (activeGroupId !== 'all') {
                    $('#group_id').val(activeGroupId).trigger('change');
                } else {
                    $('#group_id').val('').trigger('change');
                }
                $('#tambah-coa').modal('show');
            });

            // Event listener lainnya (edit, delete, submit form) tidak berubah
            // ...

            // === EKSEKUSI AWAL ===
            // Langsung muat semua data saat halaman siap
            loadCoaData('all');
        });
    </script>
@endsection
