@extends('inc.layout')
@section('title', 'Chart of Account')
@section('content')
    <style>
        table {
            font-size: 7pt !important;
        }

        .badge-waiting {
            background-color: #f39c12;
            color: white;
        }

        .badge-approved {
            background-color: #00a65a;
            color: white;
        }

        .badge-rejected {
            background-color: #dd4b39;
            color: white;
        }

        .modal-lg {
            max-width: 800px;
        }

        .panel-loading {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 999;
        }

        /* PENTING: Tambahkan CSS ini jika belum ada untuk memastikan toggle berfungsi */
        .child-row {
            display: none;
            /* Sembunyikan secara default */
        }

        .dropdown-icon {
            font-size: 14px;
            transition: transform 0.3s ease;
            display: inline-block;
        }

        .dropdown-icon.bxs-down-arrow {
            transform: rotate(180deg);
        }

        /* Styling tambahan untuk memperjelas batas row */
        .child-row td {
            background-color: #f9f9f9;
            border-bottom: 2px solid #ddd;
        }

        /* Pastikan table di dalam child row memiliki margin dan padding yang tepat */
        .child-row td>div {
            padding: 15px;
            margin: 0;
        }

        /* Pastikan parent dan child row terhubung secara visual */
        tr.parent-row.active {
            border-bottom: none !important;
        }

        /* Tambahkan di bagian style */
        .control-details {
            cursor: pointer;
            text-align: center;
            width: 30px;
        }

        .control-details .dropdown-icon {
            font-size: 18px;
            transition: transform 0.3s ease, color 0.3s ease;
            display: inline-block;
            color: #3498db;
            /* Warna biru */
        }

        .control-details .dropdown-icon.bxs-up-arrow {
            transform: rotate(180deg);
            color: #e74c3c;
            /* Warna merah saat terbuka */
        }

        .control-details:hover .dropdown-icon {
            color: #2980b9;
            /* Warna biru lebih gelap saat hover */
        }

        /* Sembunyikan ikon sort bawaan DataTables */
        table.dataTable thead .sorting:after,
        table.dataTable thead .sorting_asc:after,
        table.dataTable thead .sorting_desc:after,
        table.dataTable thead .sorting_asc_disabled:after,
        table.dataTable thead .sorting_desc_disabled:after {
            display: none !important;
        }

        /* Styling untuk child row */
        /* Pastikan content di child row tidak overflow */
        .child-row td>div {
            padding: 15px;
            width: 100%;
        }

        /* Styling untuk tabel di dalam child row */
        .child-table {
            width: 98% !important;
            margin: 10px auto !important;
            border-radius: 4px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .child-table thead th {
            background-color: #021d39;
            color: white;
            font-size: 12px;
            padding: 8px !important;
        }

        .child-table tbody td {
            padding: 8px !important;
            font-size: 12px;
            background-color: white;
        }

        /* Animasi untuk transisi smooth */
        .child-row {
            transition: all 0.3s ease;
        }

        .child-row.show {
            opacity: 1;
        }

        td.control-details::before {
            display: none !important;
        }

        /* Efek hover untuk row */
        #dt-basic-example tbody tr.parent-row:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }

        /* Warna berbeda untuk child row */
        #dt-basic-example tbody tr.child-row:hover {
            background-color: #f1f1f1;
        }

        .btn-group-filter.active {
            background-color: #3a8fe2;
            /* Warna primer template Anda */
            color: white;
            border-color: #3a8fe2;
        }

        #coa-table tbody tr:hover {
            background-color: #f1f3f5;
        }

        /* CSS untuk indentasi hierarki */
        #coa-table .coa-level-0 {
            font-weight: 600;
            background-color: #f8f9fa;
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
            max-height: 60vh;
            overflow-y: auto;
            border-bottom: 1px solid #dee2e6;
        }

        #coa-table thead {
            position: sticky;
            top: 0;
            z-index: 1;
            /* Pastikan header di atas konten lain */
        }

        /* Anda bisa menambahkan level-4, level-5, dst. jika perlu */

        .table-responsive {
            min-height: 400px;
            /* Memberi tinggi minimal agar tidak terlihat "lompat" saat loading */
        }
    </style>

    <main id="js-page-content" role="main" class="page-content">
        <div class="row mb-3">
            <div class="col-xl-12 d-flex justify-content-between align-items-center">
                {{-- Tombol Filter Grup COA --}}
                <div>
                    @if (isset($groupCOA) && $groupCOA->count() > 0)
                        @foreach ($groupCOA as $group)
                            <button class="btn btn-outline-primary btn-sm btn-group-filter"
                                data-group-id="{{ $group->id }}">{{ $group->name }}</button>
                        @endforeach
                    @else
                        <div class="alert alert-warning">Tidak ada data Grup COA. Silakan tambahkan terlebih dahulu.</div>
                    @endif
                </div>
                {{-- Tombol Aksi Utama --}}
                <div>
                    <button type="button" id="btn-tambah" class="btn btn-primary">
                        <i class="fal fa-plus-circle mr-1"></i>
                        Tambah COA
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
                                            <th class="text-center">Header</th>
                                            <th class="text-center">Default</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center" style="width: 100px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="coa-table-body">
                                        {{-- Konten tabel akan diisi oleh JavaScript (AJAX) --}}
                                        <tr>
                                            <td colspan="5" class="text-center p-5">
                                                <div class="spinner-border text-primary" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                                <div class="mt-2">Silakan pilih grup untuk memuat data...</div>
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

    {{-- Include file partial untuk modal Tambah & Edit --}}
    {{-- Pastikan path ke file-file ini sudah benar di struktur folder Anda --}}
    @include('app-type.keuangan.chart-of-account.partials.create-coa')
    @include('app-type.keuangan.chart-of-account.partials.update-coa')
@endsection

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            // ==========================================================
            // 1. SETUP & INISIALISASI
            // ==========================================================

            // Setup CSRF Token untuk semua request AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Inisialisasi Select2 untuk modal
            $('#tambah-coa .select2').select2({
                dropdownParent: $('#tambah-coa'),
                width: '100%'
            });
            $('#edit-coa .select2').select2({
                dropdownParent: $('#edit-coa'),
                width: '100%'
            });

            // Inisialisasi DataTables dengan fitur lengkap
            var table = $('#coa-table').DataTable({
                responsive: true,
                // Layout: l=length, f=filtering, t=table, i=info, p=pagination
                dom: "<'row mb-3'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",

                paging: false,

                scrollY: "55vh",
                scrollCollapse: true,

                // Data awal kosong, akan diisi via AJAX
                data: [],

                // Definisi kolom agar DataTables tahu cara mengisi data
                columns: [{
                        "data": "chart_of_account",
                        "title": "Chart Of Account"
                    },
                    {
                        "data": "header",
                        "title": "Header",
                        "className": "text-center"
                    },
                    {
                        "data": "default",
                        "title": "Default",
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

                // Bahasa
                language: {
                    search: "Pencarian:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    zeroRecords: "Data tidak ditemukan",
                    info: "Menampilkan _TOTAL_ total data",
                    infoEmpty: "",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                },

                // Konfigurasi lain
                order: [], // Matikan sorting awal
                columnDefs: [{
                    orderable: false,
                    targets: [4]
                }], // Matikan sort untuk kolom Aksi
            });

            let activeGroupId = $('.btn-group-filter').first().data('group-id');

            // ==========================================================
            // 2. FUNGSI-FUNGSI UTAMA
            // ==========================================================

            function loadCoaByGroup(groupId) {
                // Tampilkan pesan loading di dalam tabel yang sudah kosong
                table.clear().draw();
                $('#coa-table-body').html(
                    '<tr><td colspan="5" class="text-center p-5"><div class="spinner-border text-primary"></div><div class="mt-2">Memuat...</div></td></tr>'
                );

                let url = "{{ route('coa.byGroup', ['group_id' => 'PLACEHOLDER']) }}".replace('PLACEHOLDER',
                    groupId);

                $.get(url)
                    .done(function(response) {
                        if (Array.isArray(response) && response.length > 0) {
                            let tableData = buildTableData(response, 0);
                            table.clear();
                            table.rows.add(tableData).draw();
                        } else {
                            table.clear().draw(); // DataTables akan menampilkan pesan "Data tidak ditemukan"
                        }
                    })
                    .fail(function(xhr) {
                        console.error("AJAX Gagal:", xhr.responseText);
                        $('#coa-table-body').html(
                            '<tr><td colspan="5" class="text-center text-danger">Gagal memuat data.</td></tr>'
                        );
                    });
            }

            function buildTableData(data, level) {
                let rows = [];
                data.forEach(function(coa) {
                    let indentClass = `coa-level-${level}`;
                    let icon = coa.header ? '<i class="fal fa-folder-open mr-2 text-warning"></i>' :
                        '<i class="fal fa-file-alt mr-2 text-muted"></i>';
                    let headerBadge = coa.header ? '<span class="badge badge-primary">YES</span>' :
                        '<span class="badge badge-secondary">NO</span>';
                    let statusBadge = coa.status ? '<span class="badge badge-success">Aktif</span>' :
                        '<span class="badge badge-danger">Tidak Aktif</span>';

                    rows.push({
                        "chart_of_account": `<div class="${indentClass}">${icon}<strong>${coa.code}</strong> - ${coa.name}</div>`,
                        "header": headerBadge,
                        "default": coa.default || '-',
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

            function loadCoaDetailsForEdit(coaId) {
                let url = "{{ route('coa.show', ['coa' => 'PLACEHOLDER']) }}".replace('PLACEHOLDER', coaId);

                $.get(url)
                    .done(function(coa) {
                        $('#edit_coa_id').val(coa.id);
                        $('#edit_group_id').val(coa.group_id).trigger('change');
                        $('#edit_code').val(coa.code);
                        $('#edit_name').val(coa.name);
                        $('#edit_description').val(coa.description);

                        setTimeout(() => $('#edit_parent_id').val(coa.parent_id).trigger('change'), 300);

                        $(`input[name="header"][value="${Number(coa.header)}"]`, '#edit-form').prop('checked',
                            true);
                        $(`input[name="status"][value="${Number(coa.status)}"]`, '#edit-form').prop('checked',
                            true);
                        if (coa.default) $(`input[name="default"][value="${coa.default}"]`, '#edit-form').prop(
                            'checked', true);

                        $('#edit-coa').modal('show');
                    })
                    .fail(() => alert('Gagal memuat detail COA.'));
            }

            function initParentDropdown(selectElement, groupId) {
                let parentUrl = "{{ route('coa.parents') }}" + `?group_id=${groupId}`;
                $.get(parentUrl, function(data) {
                    let select = $(selectElement);
                    let currentVal = select.val();
                    select.empty().append('<option value="">- Tidak ada Parent -</option>');
                    data.forEach(item => select.append(
                        `<option value="${item.id}">${item.code} - ${item.name}</option>`));
                    select.val(currentVal);
                    select.trigger('change');
                });
            }

            // ==========================================================
            // 3. EVENT LISTENERS
            // ==========================================================

            $('.btn-group-filter').on('click', function() {
                $('.btn-group-filter').removeClass('active');
                $(this).addClass('active');
                activeGroupId = $(this).data('group-id');
                loadCoaByGroup(activeGroupId);
            });

            $('#btn-tambah').on('click', function() {
                $('#store-form')[0].reset();
                $('#group_id').val(activeGroupId).trigger('change');
                $('#tambah-coa').modal('show');
            });

            $('#coa-table tbody').on('click', '.btn-edit-coa', function() {
                loadCoaDetailsForEdit($(this).data('coa-id'));
            });

            $('#coa-table tbody').on('click', '.btn-delete-coa', function() {
                let coaId = $(this).data('coa-id');
                if (confirm('Apakah Anda yakin ingin menghapus COA ini?')) {
                    let deleteUrl = `{{ url('/keuangan/setup/chart-of-account') }}/${coaId}`;
                    $.ajax({
                        url: deleteUrl,
                        method: 'DELETE',
                        success: (response) => {
                            alert(response.message);
                            loadCoaByGroup(activeGroupId);
                        },
                        error: (xhr) => alert(xhr.responseJSON.message || 'Gagal menghapus COA.')
                    });
                }
            });

            $('#group_id').on('change', function() {
                initParentDropdown('#parent_id', $(this).val());
            });
            $('#edit_group_id').on('change', function() {
                initParentDropdown('#edit_parent_id', $(this).val());
            });

            $('#store-form').on('submit', function(e) {
                e.preventDefault();
                $.post("{{ route('chart-of-account.store') }}", $(this).serialize())
                    .done((response) => {
                        $('#tambah-coa').modal('hide');
                        alert(response.message || 'Data berhasil disimpan.');
                        loadCoaByGroup(activeGroupId);
                    })
                    .fail((xhr) => alert('Validasi Gagal:\n' + Object.values(xhr.responseJSON.errors).flat()
                        .join('\n')));
            });

            $('#edit-form').on('submit', function(e) {
                e.preventDefault();
                let coaId = $('#edit_coa_id').val();
                $.ajax({
                    url: `{{ url('/keuangan/setup/chart-of-account') }}/${coaId}`,
                    method: 'PATCH',
                    data: $(this).serialize(),
                    success: (response) => {
                        $('#edit-coa').modal('hide');
                        alert(response.message || 'Data berhasil diperbarui.');
                        loadCoaByGroup(activeGroupId);
                    },
                    error: (xhr) => alert('Validasi Gagal:\n' + Object.values(xhr.responseJSON
                        .errors).flat().join('\n'))
                });
            });

            // ==========================================================
            // 4. EKSEKUSI AWAL
            // ==========================================================
            if ($('.btn-group-filter').length > 0) {
                // Otomatis klik tombol filter pertama
                $('.btn-group-filter').first().trigger('click');
            } else {
                // Tampilkan pesan jika tidak ada grup
                table.clear().draw();
                $('#coa-table-body').html(
                    '<tr><td colspan="5" class="text-center text-danger">Tidak ada Grup COA yang bisa dipilih.</td></tr>'
                );
            }
        });
    </script>
@endsection
