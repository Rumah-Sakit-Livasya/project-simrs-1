@extends('inc.layout')
@section('title', 'Chart of Account')
@section('content')
    <style>
        thead,
        td {
            font-size: 10px;
        }

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

        #coa-table .coa-level-4 {
            padding-left: 8rem !important;
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

        #coa-table tbody tr:hover {
            background-color: #f1f3f5;
        }

        .select2-container--open {
            z-index: 10050;
            /* Pastikan dropdown di atas modal */
        }

        .select2-dropdown {
            z-index: 10050;
            /* Pastikan dropdown Select2 tidak tertutup elemen lain */
        }
    </style>

    <main id="js-page-content" role="main" class="page-content">
        <div class="row mb-3">
            <div class="col-xl-12 d-flex justify-content-between align-items-center">
                <div id="group-coa-buttons">
                    <button class="btn btn-outline-primary btn-sm btn-group-filter active" data-group-id="all">Semua
                        Grup</button>
                    @forelse ($groupCOA as $group)
                        <button class="btn btn-outline-primary btn-sm btn-group-filter"
                            data-group-id="{{ $group->id }}">{{ $group->name }}</button>
                    @empty
                        <div class="alert alert-warning d-inline-block p-2">Tidak ada Grup COA.</div>
                    @endforelse
                </div>
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
                                <table class="table table-striped table-bordered" id="coa-table" style="width:100%">
                                    <thead class="bg-primary-600 text-white">
                                        <tr>
                                            <th>Chart Of Account</th>
                                            <th>Grup</th>
                                            <th class="text-center">Header</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center" style="width: 100px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- Data akan diisi oleh JavaScript --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- Memanggil file modal --}}
    @include('app-type.keuangan.chart-of-account.partials.create-coa')
    @include('app-type.keuangan.chart-of-account.partials.update-coa')
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

            // Inisialisasi DataTable
            var table = $('#coa-table').DataTable({
                responsive: true,
                paging: false,
                ordering: false, // Mematikan sorting bawaan karena kita urutkan manual
                info: true,
                language: {
                    search: "",
                    lengthMenu: "Tampilkan _MENU_ data",
                    zeroRecords: "Data tidak ditemukan.",
                    info: "Menampilkan _TOTAL_ total data",
                    infoEmpty: "Tidak ada data untuk ditampilkan.",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                }
            });

            let activeGroupId = 'all';

            // Fungsi untuk memuat data COA
            function loadCoaData(groupId) {
                table.clear().draw();
                $('#coa-table tbody').html(
                    '<tr><td colspan="5" class="text-center p-5"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div><div class="mt-2">Memuat data...</div></td></tr>'
                );

                let url = (groupId === 'all') ?
                    "{{ route('coa.all') }}" :
                    "{{ route('coa.byGroup', ['group_id' => 'PLACEHOLDER']) }}".replace('PLACEHOLDER', groupId);
                activeGroupId = groupId;

                $.get(url)
                    .done(function(response) {
                        $('#coa-table tbody').empty();
                        if (Array.isArray(response) && response.length > 0) {
                            let tableData = buildTableData(response, 0);
                            table.rows.add(tableData).draw();
                        } else {
                            table.clear().draw();
                        }
                    })
                    .fail(function(xhr) {
                        console.error("AJAX Gagal:", xhr.responseText);
                        $('#coa-table tbody').html(
                            '<tr><td colspan="5" class="text-center text-danger p-5">Gagal memuat data. Silakan coba lagi.</td></tr>'
                        );
                    });
            }

            // Fungsi untuk membangun data tabel
            function buildTableData(data, level) {
                let rows = [];
                data.forEach(function(coa) {
                    let indentClass = `coa-level-${level}`;
                    let icon = coa.header ? '<i class="fal fa-folder-open mr-2 text-warning"></i>' :
                        '<i class="fal fa-file-alt mr-2 text-muted"></i>';

                    rows.push([
                        `<div class="${indentClass}">${icon}<strong>${coa.code}</strong> - ${coa.name}</div>`,
                        coa.group_name,
                        coa.header ? '<span class="badge badge-primary">YES</span>' :
                        '<span class="badge badge-secondary">NO</span>',
                        coa.status ? '<span class="badge badge-success">Aktif</span>' :
                        '<span class="badge badge-danger">Nonaktif</span>',
                        `<button class="btn btn-xs btn-outline-primary btn-edit-coa" data-coa-id="${coa.id}" title="Edit"><i class="fal fa-edit"></i></button>
                         <button class="btn btn-xs btn-outline-danger btn-delete-coa" data-coa-id="${coa.id}" title="Hapus"><i class="fal fa-trash"></i></button>`
                    ]);

                    if (coa.children && coa.children.length > 0) {
                        rows = rows.concat(buildTableData(coa.children, level + 1));
                    }
                });
                return rows;
            }

            // Fungsi untuk menginisialisasi dropdown parent
            function initParentDropdown(selectElement, groupId, excludeId = null, selectedId = null) {
                selectElement.empty().append('<option value="">Tidak Ada Induk (Root)</option>');
                let url = new URL("{{ route('coa.getParents') }}", window.location.origin);
                if (groupId) url.searchParams.append('group_id', groupId);
                if (excludeId) url.searchParams.append('exclude_id', excludeId);

                $.get(url).done(function(parents) {
                    console.log('Data parents:', parents); // Debugging
                    parents.forEach(function(parent) {
                        selectElement.append(new Option(`(${parent.code}) ${parent.name}`, parent
                            .id));
                    });
                    if (selectedId) {
                        selectElement.val(selectedId).trigger('change');
                    }
                }).fail(function(xhr) {
                    console.error('Gagal memuat data parent:', xhr.responseText);
                });
            }

            // Inisialisasi Select2 saat modal ditampilkan
            $('#tambah-coa').on('shown.bs.modal', function() {
                // Hancurkan Select2 sebelumnya untuk mencegah duplikasi
                $('#group_id, #parent_id').select2('destroy');
                $('#group_id, #parent_id').select2({
                    dropdownParent: $('#tambah-coa'),
                    width: '100%'
                });
            });

            $('#edit-coa').on('shown.bs.modal', function() {
                // Hancurkan Select2 sebelumnya untuk mencegah duplikasi
                $('#edit_group_id, #edit_parent_id').select2('destroy');
                $('#edit_group_id, #edit_parent_id').select2({
                    dropdownParent: $('#edit-coa'),
                    width: '100%'
                });
            });

            // Hancurkan Select2 saat modal ditutup
            $('#tambah-coa, #edit-coa').on('hidden.bs.modal', function() {
                $(this).find('.select2').select2('destroy');
            });

            // Event listener untuk filter grup COA
            $(document).on('click', '.btn-group-filter', function() {
                $('.btn-group-filter').removeClass('active');
                $(this).addClass('active');
                loadCoaData($(this).data('group-id'));
            });

            // Event listener untuk tombol Tambah
            $('#btn-tambah').on('click', function() {
                $('#store-form')[0].reset();
                $('#store-form .is-invalid').removeClass('is-invalid');
                let groupId = (activeGroupId !== 'all') ? activeGroupId : '';
                $('#group_id').val(groupId).trigger('change');
                initParentDropdown($('#parent_id'), groupId);
                $('#tambah-coa').modal('show');
            });

            // Ganti parent dropdown saat grup di modal tambah berubah
            $('#group_id').on('change', function() {
                initParentDropdown($('#parent_id'), $(this).val());
            });

            // Ganti parent dropdown saat grup di modal edit berubah
            $('#edit_group_id').on('change', function() {
                let coaId = $('#edit_coa_id').val();
                initParentDropdown($('#edit_parent_id'), $(this).val(), coaId);
            });

            // Proses simpan data baru
            $('#store-form').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                $.post("{{ route('chart-of-account.store') }}", formData)
                    .done(function() {
                        $('#tambah-coa').modal('hide');
                        Swal.fire('Sukses', 'Data berhasil disimpan.', 'success');
                        loadCoaData(activeGroupId);
                        refreshGroupButtons(); // Refresh tombol grup setelah simpan
                    })
                    .fail(function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        $('.is-invalid').removeClass('is-invalid');
                        $.each(errors, function(key, value) {
                            $(`#${key}`).addClass('is-invalid');
                            $(`#${key}_error`).text(value[0]);
                        });
                    });
            });

            // Proses buka modal edit dan isi data
            $(document).on('click', '.btn-edit-coa', function() {
                let coaId = $(this).data('coa-id');
                $('#update-form .is-invalid').removeClass('is-invalid');
                $.get("{{ route('coa.show', ['coa' => ':id']) }}".replace(':id', coaId)).done(function(
                    data) {
                    $('#edit_coa_id').val(data.id);
                    $('#edit_group_id').val(data.group_id).trigger('change');
                    $('#edit_code').val(data.code);
                    $('#edit_name').val(data.name);
                    $('#edit_default').val(data.default);
                    $('#edit_header').prop('checked', data.header == 1);
                    $('#edit_status').prop('checked', data.status == 1);
                    $('#edit_description').val(data.description);

                    initParentDropdown($('#edit_parent_id'), data.group_id, data.id, data
                        .parent_id);
                    $('#edit-coa').modal('show');
                }).fail(function(xhr) {
                    console.error('Gagal memuat data COA:', xhr.responseText);
                    Swal.fire('Gagal!', 'Gagal memuat data untuk edit.', 'error');
                });
            });

            // Proses update data
            $('#update-form').on('submit', function(e) {
                e.preventDefault();
                let coaId = $('#edit_coa_id').val();
                let formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('chart-of-account.update', ['chartOfAccount' => ':id']) }}"
                        .replace(':id', coaId),
                    type: 'PATCH', // Sesuai dengan rute chart-of-account.update
                    data: formData,
                    success: function() {
                        $('#edit-coa').modal('hide');
                        Swal.fire('Sukses', 'Data berhasil diperbarui.', 'success');
                        loadCoaData(activeGroupId);
                        refreshGroupButtons(); // Refresh tombol grup setelah update
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        $('.is-invalid').removeClass('is-invalid');
                        $.each(errors, function(key, value) {
                            $(`#edit_${key}`).addClass('is-invalid');
                            $(`#edit_${key}_error`).text(value[0]);
                        });
                    }
                });
            });

            // Proses hapus data
            $(document).on('click', '.btn-delete-coa', function() {
                let coaId = $(this).data('coa-id');
                Swal.fire({
                    title: 'Anda yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('chart-of-account.destroy', ['chartOfAccount' => ':id']) }}"
                                .replace(':id', coaId),
                            type: 'DELETE',
                            success: function() {
                                Swal.fire('Dihapus!', 'Data berhasil dihapus.',
                                    'success');
                                loadCoaData(activeGroupId);
                                refreshGroupButtons
                                    (); // Refresh tombol grup setelah hapus
                            },
                            error: function(xhr) {
                                Swal.fire('Gagal!', xhr.responseJSON.message ||
                                    'Terjadi kesalahan.', 'error');
                            }
                        });
                    }
                });
            });

            // Fungsi untuk refresh tombol grup COA
            function refreshGroupButtons() {
                $.get("{{ route('group-chart-of-account.index') }}").done(function(groups) {
                    let buttonsHtml =
                        '<button class="btn btn-outline-primary btn-sm btn-group-filter active" data-group-id="all">Semua Grup</button>';
                    if (groups.length > 0) {
                        groups.forEach(function(group) {
                            buttonsHtml +=
                                `<button class="btn btn-outline-primary btn-sm btn-group-filter" data-group-id="${group.id}">${group.name}</button>`;
                        });
                    } else {
                        buttonsHtml +=
                            '<div class="alert alert-warning d-inline-block p-2">Tidak ada Grup COA.</div>';
                    }
                    $('#group-coa-buttons').html(buttonsHtml);
                }).fail(function(xhr) {
                    console.error('Gagal memuat grup COA:', xhr.responseText);
                    $('#group-coa-buttons').html(
                        '<div class="alert alert-warning d-inline-block p-2">Gagal memuat grup COA. Silakan coba lagi.</div>'
                    );
                });
            }

            // Muat semua data saat halaman pertama kali dibuka
            loadCoaData('all');
            refreshGroupButtons(); // Inisialisasi tombol grup saat halaman dimuat
        });
    </script>
@endsection

<!-- Modal Tambah -->
<div class="modal fade" id="tambah-coa" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Chart of Account</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fal fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <form id="store-form">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="form-label" for="group_id">Grup COA</label>
                            <select class="form-control select2" id="group_id" name="group_id" required>
                                <option value="" disabled selected>Pilih Grup...</option>
                                @foreach ($groupCOA as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback" id="group_id_error"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label" for="parent_id">Induk Akun (Parent)</label>
                            <select class="form-control select2" id="parent_id" name="parent_id">
                                <option value="">Tidak Ada Induk (Root)</option>
                            </select>
                            <span class="invalid-feedback" id="parent_id_error"></span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="form-label" for="code">Kode Akun</label>
                            <input type="text" class="form-control" id="code" name="code" required>
                            <span class="invalid-feedback" id="code_error"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label" for="name">Nama Akun</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <span class="invalid-feedback" id="name_error"></span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label class="form-label" for="default">Saldo Normal</label>
                            <select class="form-control" id="default" name="default" required>
                                <option value="" disabled selected>Pilih Saldo...</option>
                                <option value="Debet">Debet</option>
                                <option value="Credit">Credit</option>
                            </select>
                            <span class="invalid-feedback" id="default_error"></span>
                        </div>
                        <div class="form-group col-md-4 d-flex align-items-center pt-3">
                            <div class="custom-control custom-switch">
                                <input type="hidden" name="header" value="0">
                                <input type="checkbox" class="custom-control-input" id="header" name="header"
                                    value="1">
                                <label class="custom-control-label" for="header">Jadikan Header?</label>
                            </div>
                        </div>
                        <div class="form-group col-md-4 d-flex align-items-center pt-3">
                            <div class="custom-control custom-switch">
                                <input type="hidden" name="status" value="0">
                                <input type="checkbox" class="custom-control-input" id="status" name="status"
                                    value="1" checked>
                                <label class="custom-control-label" for="status">Aktif</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="description">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary" form="store-form">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="edit-coa" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Chart of Account</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fal fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <form id="update-form">
                    <input type="hidden" id="edit_coa_id" name="id">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="form-label" for="edit_group_id">Grup COA</label>
                            <select class="form-control select2" id="edit_group_id" name="group_id" required>
                                <option value="" disabled>Pilih Grup...</option>
                                @foreach ($groupCOA as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback" id="edit_group_id_error"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label" for="edit_parent_id">Induk Akun (Parent)</label>
                            <select class="form-control select2" id="edit_parent_id" name="parent_id">
                                <option value="">Tidak Ada Induk (Root)</option>
                            </select>
                            <span class="invalid-feedback" id="edit_parent_id_error"></span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="form-label" for="edit_code">Kode Akun</label>
                            <input type="text" class="form-control" id="edit_code" name="code" required>
                            <span class="invalid-feedback" id="edit_code_error"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label" for="edit_name">Nama Akun</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                            <span class="invalid-feedback" id="edit_name_error"></span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label class="form-label" for="edit_default">Saldo Normal</label>
                            <select class="form-control" id="edit_default" name="default" required>
                                <option value="" disabled>Pilih Saldo...</option>
                                <option value="Debet">Debet</option>
                                <option value="Credit">Credit</option>
                            </select>
                            <span class="invalid-feedback" id="edit_default_error"></span>
                        </div>
                        <div class="form-group col-md-4 d-flex align-items-center pt-3">
                            <div class="custom-control custom-switch">
                                <input type="hidden" name="header" value="0">
                                <input type="checkbox" class="custom-control-input" id="edit_header" name="header"
                                    value="1">
                                <label class="custom-control-label" for="edit_header">Jadikan Header?</label>
                            </div>
                        </div>
                        <div class="form-group col-md-4 d-flex align-items-center pt-3">
                            <div class="custom-control custom-switch">
                                <input type="hidden" name="status" value="0">
                                <input type="checkbox" class="custom-control-input" id="edit_status" name="status"
                                    value="1">
                                <label class="custom-control-label" for="edit_status">Aktif</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="edit_description">Deskripsi</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary" form="update-form">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</div>
