@extends('inc.layout')
@section('title', 'Input Kunjungan')

@section('style')
    <style>
        .select2-container--open {
            z-index: 9999999 !important;
        }

        .datepicker {
            z-index: 9999999 !important;
        }

        #existing-docs-list {
            list-style-type: none;
            padding: 0;
        }

        #existing-docs-list li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: .375rem .75rem;
            border: 1px solid #ced4da;
            margin-bottom: 5px;
            border-radius: .25rem;
            transition: all 0.2s ease-in-out;
        }

        .doc-to-delete {
            text-decoration: line-through;
            opacity: 0.6;
            background-color: #f8d7da;
        }

        #preview-body embed,
        #preview-body img {
            width: 100%;
            height: 75vh;
            border: none;
        }

        #preview-body .card-img-top {
            height: 200px;
            object-fit: cover;
            cursor: pointer;
        }

        #preview-body .file-icon-preview {
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
        }
    </style>
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <ol class="breadcrumb bg-primary-300">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Kunjungan</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>

        {{-- FORM FILTER --}}
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-filter" class="panel">
                    <div class="panel-hdr">
                        <h2>Filter Pencarian</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="filterForm" autocomplete="off">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="start_date">Tanggal Mulai</label>
                                            <input type="text" id="start_date" class="form-control datepicker"
                                                placeholder="Pilih tanggal" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="end_date">Tanggal Akhir</label>
                                            <input type="text" id="end_date" class="form-control datepicker"
                                                placeholder="Pilih tanggal" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="filter_jenis_kegiatan">Jenis Kegiatan</label>
                                            <select id="filter_jenis_kegiatan" class="form-control filter-select2"
                                                style="width: 100%;">
                                                <option value="">Semua</option>
                                                @foreach ($jenisKegiatans as $kegiatan)
                                                    <option value="{{ $kegiatan->id }}">{{ $kegiatan->nama_kegiatan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="filter_ruangan">Ruangan</label>
                                            <select id="filter_ruangan" class="form-control filter-select2"
                                                style="width: 100%;">
                                                <option value="">Semua</option>
                                                @foreach ($roomMaintenances as $room)
                                                    <option value="{{ $room->id }}">{{ $room->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="filter_pic">PIC</label>
                                            <select id="filter_pic" class="form-control filter-select2"
                                                style="width: 100%;">
                                                <option value="">Semua</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <div class="form-group w-100">
                                            <button type="button" id="btn-filter" class="btn btn-primary w-100"><i
                                                    class="fal fa-search"></i> Filter</button>
                                            <button type="button" id="btn-reset" class="btn btn-secondary w-100 mt-2"><i
                                                    class="fal fa-undo"></i> Reset</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- PANEL TABEL --}}
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Daftar Kunjungan</h2>
                        <div class="panel-toolbar">
                            <button class="btn btn-primary btn-sm" onclick="openCreateKunjunganModal()"><i
                                    class="fal fa-plus mr-1"></i> Tambah Kunjungan</button>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="kunjungan-datatable" class="table table-bordered table-hover table-striped w-100">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Kegiatan</th>
                                        <th>Ruangan</th>
                                        <th>PIC</th>
                                        <th>Keterangan</th>
                                        <th>Dokumentasi</th>
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

    {{-- ================================= MODAL CRUD KUNJUNGAN ================================= --}}
    <div class="modal fade" id="kunjunganModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="kunjunganForm" onsubmit="submitKunjunganForm(event)" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="kunjunganModalTitle">Modal Title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fal fa-times"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="kunjunganId">

                        <div class="form-group">
                            <label for="tanggal_kunjungan">Tanggal</label>
                            <div class="input-group">
                                <input type="text" class="form-control datepicker" id="tanggal_kunjungan"
                                    name="tanggal_kunjungan" required readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text fs-xl"><i class="fal fa-calendar-alt"></i></span>
                                </div>
                            </div>
                            <div class="invalid-feedback" id="tanggal_kunjungan-error"></div>
                        </div>

                        <div class="form-group">
                            <label for="jenis_kegiatan_id">Jenis Kegiatan</label>
                            <select class="form-control select2" id="jenis_kegiatan_id" name="jenis_kegiatan_id" required
                                style="width: 100%;">
                                <option></option>
                                @foreach ($jenisKegiatans as $kegiatan)
                                    <option value="{{ $kegiatan->id }}">{{ $kegiatan->nama_kegiatan }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="jenis_kegiatan_id-error"></div>
                        </div>

                        <div class="form-group">
                            <label for="room_maintenance_id">Ruangan</label>
                            <select class="form-control select2" id="room_maintenance_id" name="room_maintenance_id"
                                required style="width: 100%;">
                                <option></option>
                                @foreach ($roomMaintenances as $room)
                                    <option value="{{ $room->id }}">{{ $room->name }} ({{ $room->room_code }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="room_maintenance_id-error"></div>
                        </div>

                        <div class="form-group">
                            <label for="user_id">PIC</label>
                            <select class="form-control select2" id="user_id" name="user_id" required
                                style="width: 100%;">
                                <option></option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="user_id-error"></div>
                        </div>

                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                        </div>

                        <div id="existing-docs-container" class="form-group" style="display: none;">
                            <label>Dokumentasi Saat Ini</label>
                            <ul id="existing-docs-list"></ul>
                        </div>

                        <div class="form-group">
                            <label>Tambah Dokumentasi Baru</label>
                            <div id="new-docs-container">
                                {{-- Input file dinamis akan ditambahkan di sini oleh JavaScript --}}
                            </div>
                            <button type="button" class="btn btn-success btn-xs mt-2" id="add-doc-btn">
                                <i class="fal fa-plus"></i> Tambah File
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" id="kunjunganSubmitButton" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewModalTitle">File Preview</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body" id="preview-body">
                    {{-- Konten preview (img atau embed) akan dimasukkan di sini oleh JavaScript --}}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('plugin')
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>

    <script>
        const kunjunganApiUrl = '/api/kunjungan';
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let kunjunganDataTable;

        function clearKunjunganValidationErrors() {
            $('.form-control, .custom-file-input').removeClass('is-invalid');
            $('.invalid-feedback').text('');
        }

        $(document).ready(function() {
            // Inisialisasi plugin untuk FORM FILTER
            $('.filter-select2').select2({
                placeholder: "Semua",
                allowClear: true
            });

            // Inisialisasi plugin untuk MODAL
            $('#kunjunganModal .select2').select2({
                placeholder: "-- Silakan Pilih --",
                dropdownParent: $('#kunjunganModal') // Ini tetap penting!
            });

            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                orientation: "bottom left"
            });

            // Inisialisasi DataTable
            kunjunganDataTable = $('#kunjungan-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: kunjunganApiUrl,
                    type: 'GET',
                    data: function(d) {
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                        d.jenis_kegiatan_id = $('#filter_jenis_kegiatan').val();
                        d.room_maintenance_id = $('#filter_ruangan').val();
                        d.user_id = $('#filter_pic').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'tanggal_kunjungan',
                        name: 'tanggal_kunjungan'
                    },
                    {
                        data: 'jenis_kegiatan',
                        name: 'jenisKegiatan.nama_kegiatan'
                    },
                    {
                        data: 'ruangan',
                        name: 'roomMaintenance.name'
                    },
                    {
                        data: 'pic',
                        name: 'user.name'
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan',
                        defaultContent: '-'
                    },
                    {
                        data: 'dokumentasi',
                        name: 'dokumentasi',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                columnDefs: [{
                    targets: 6,
                    render: function(data, type, row) {
                        if (!data || !Array.isArray(data) || data.length === 0) {
                            return 'Tidak ada file';
                        }
                        return `<button class="btn btn-info btn-xs btn-view-docs" data-id="${row.id}">Lihat (${data.length} File)</button>`;
                    }
                }]
            });

            // Event handler untuk tombol filter
            $('#btn-filter').on('click', function() {
                kunjunganDataTable.draw();
            });

            $('#btn-reset').on('click', function() {
                $('#filterForm')[0].reset();
                $('.filter-select2').val(null).trigger('change');
                kunjunganDataTable.draw();
            });

            // Enter key triggers filter
            $('#filterForm input, #filterForm select').on('keypress', function(e) {
                if (e.which === 13) {
                    $('#btn-filter').click();
                    return false;
                }
            });

            // Dokumentasi preview
            $('#kunjungan-datatable tbody').on('click', '.btn-view-docs', function() {
                const rowElement = $(this).closest('tr');
                const rowData = kunjunganDataTable.row(rowElement).data();
                const docs = rowData.dokumentasi;
                const previewBody = $('#preview-body');
                previewBody.html('');
                if (!docs || docs.length === 0) {
                    previewBody.html('<p class="text-center">Tidak ada dokumentasi untuk ditampilkan.</p>');
                    $('#previewModal').modal('show');
                    return;
                }
                let modalContent = '<div class="row">';
                docs.forEach(doc => {
                    const url = `/storage/${doc.file_path}`;
                    const fileName = doc.file_path.split('/').pop();
                    const fileType = fileName.split('.').pop().toLowerCase();
                    let filePreviewHtml = '';
                    if (['jpg', 'jpeg', 'png', 'gif'].includes(fileType)) {
                        filePreviewHtml =
                            `<a href="${url}" target="_blank" title="Lihat ukuran penuh"><img src="${url}" class="card-img-top" alt="${fileName}"></a>`;
                    } else if (fileType === 'pdf') {
                        filePreviewHtml =
                            `<div class="file-icon-preview"><i class="fal fa-file-pdf fa-4x text-danger"></i></div>`;
                    } else {
                        filePreviewHtml =
                            `<div class="file-icon-preview"><i class="fal fa-file fa-4x text-muted"></i></div>`;
                    }
                    modalContent += `
                        <div class="col-12 col-sm-6 col-md-6 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-img-top d-flex align-items-center justify-content-center" style="min-height:120px;">
                                    ${filePreviewHtml}
                                </div>
                                <div class="card-body p-2">
                                    <a href="${url}" target="_blank" class="btn btn-outline-primary btn-block btn-sm">Buka di Tab Baru</a>
                                </div>
                            </div>
                        </div>
                    `;
                });
                modalContent += '</div>';
                previewBody.html(modalContent);
                $('#previewModalTitle').text(`Dokumentasi Kunjungan #${rowData.id}`);
                $('#previewModal').modal('show');
            });

            // === LOGIKA INPUT FILE DINAMIS (Sama, tidak berubah) ===
            $('#add-doc-btn').on('click', function() {
                const newFileInput =
                    `<div class="input-group mt-2"><div class="custom-file"><input type="file" class="custom-file-input" name="dokumentasi[]"><label class="custom-file-label">Pilih file</label></div><div class="input-group-append"><button class="btn btn-danger btn-sm remove-new-doc-btn" type="button"><i class="fal fa-trash"></i></button></div></div>`;
                $('#new-docs-container').append(newFileInput);
            });
            $('#new-docs-container').on('click', '.remove-new-doc-btn', function() {
                $(this).closest('.input-group').remove();
            });
            $('#new-docs-container').on('change', '.custom-file-input', function() {
                const fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').html(fileName || 'Pilih file');
            });

            // === LOGIKA MODAL PREVIEW (Sama, tidak berubah) ===
            $('#kunjungan-datatable tbody').on('click', 'a.doc-link', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                const fileType = url.split('.').pop().toLowerCase();
                const previewBody = $('#preview-body');
                previewBody.html('');
                if (['jpg', 'jpeg', 'png', 'gif'].includes(fileType)) {
                    previewBody.html(`<img src="${url}" alt="Preview">`);
                } else if (fileType === 'pdf') {
                    previewBody.html(`<embed src="${url}" type="application/pdf">`);
                } else {
                    previewBody.html(
                        `<div class="alert alert-info text-center">Preview tidak tersedia untuk tipe file ini.</div><a href="${url}" class="btn btn-primary d-block mx-auto" style="width:200px;" download>Download File</a>`
                    );
                }
                $('#previewModal').modal('show');
            });

            // === LOGIKA HAPUS FILE LAMA SAAT EDIT (Sama, tidak berubah) ===
            $('#existing-docs-list').on('click', '.delete-existing-doc-btn', function() {
                const listItem = $(this).closest('li');
                const docId = listItem.data('id');
                if ($(this).hasClass('btn-danger')) {
                    $('#kunjunganForm').append(
                        `<input type="hidden" class="deleted-doc-input" name="deleted_docs[]" value="${docId}">`
                    );
                    listItem.addClass('doc-to-delete');
                    $(this).removeClass('btn-danger').addClass('btn-secondary').html(
                        '<i class="fal fa-undo"></i> Batal');
                } else {
                    $(`input.deleted-doc-input[value="${docId}"]`).remove();
                    listItem.removeClass('doc-to-delete');
                    $(this).removeClass('btn-secondary').addClass('btn-danger').html('Hapus');
                }
            });
        });

        function openCreateKunjunganModal() {
            clearKunjunganValidationErrors();
            $('#kunjunganForm')[0].reset();
            $('#kunjunganModalTitle').text('Tambah Kunjungan Baru');
            $('#kunjunganId').val('');
            $('.select2').val(null).trigger('change');
            $('#tanggal_kunjungan').datepicker('update', '');
            $('#new-docs-container').html('');
            $('#existing-docs-container').hide();
            $('#existing-docs-list').html('');
            $('.deleted-doc-input').remove();
            $('#kunjunganModal').modal('show');
        }

        async function openEditKunjunganModal(id) {
            openCreateKunjunganModal();
            try {
                const response = await fetch(`${kunjunganApiUrl}/${id}`);
                if (!response.ok) throw new Error('Gagal mengambil data kunjungan.');
                const {
                    data
                } = await response.json();
                $('#kunjunganModalTitle').text('Edit Kunjungan');
                $('#kunjunganId').val(data.id);
                $('#keterangan').val(data.keterangan);
                $('#tanggal_kunjungan').datepicker('update', data.tanggal_kunjungan);
                $('#jenis_kegiatan_id').val(data.jenis_kegiatan_id).trigger('change');
                $('#room_maintenance_id').val(data.room_maintenance_id).trigger('change');
                $('#user_id').val(data.user_id).trigger('change');
                const docsList = $('#existing-docs-list');
                if (data.dokumentasi && data.dokumentasi.length > 0) {
                    let docsHtml = '';
                    data.dokumentasi.forEach(doc => {
                        const fileName = doc.file_path.split('/').pop();
                        docsHtml +=
                            `<li data-id="${doc.id}"><span>${fileName}</span><button type="button" class="btn btn-danger btn-xs delete-existing-doc-btn">Hapus</button></li>`;
                    });
                    docsList.html(docsHtml);
                    $('#existing-docs-container').show();
                }
                $('#kunjunganModal').modal('show');
            } catch (error) {
                Swal.fire('Error', error.message, 'error');
            }
        }

        async function submitKunjunganForm(event) {
            event.preventDefault();
            clearKunjunganValidationErrors();
            const id = $('#kunjunganId').val();
            const url = id ? `${kunjunganApiUrl}/${id}` : kunjunganApiUrl;
            const form = document.getElementById('kunjunganForm');
            const formData = new FormData(form);
            const submitButton = $('#kunjunganSubmitButton');
            submitButton.prop('disabled', true).html('Menyimpan...');
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                const result = await response.json();
                if (!response.ok) {
                    if (response.status === 422) {
                        Object.keys(result).forEach(key => {
                            const baseKey = key.split('.')[0];
                            const errorDiv = $(`#${baseKey}-error`);
                            $(`[name^="${baseKey}"]`).addClass('is-invalid');
                            errorDiv.text(result[key][0]);
                        });
                    }
                    throw new Error(result.message || 'Terjadi kesalahan validasi.');
                }
                $('#kunjunganModal').modal('hide');
                kunjunganDataTable.ajax.reload(null, false);
                Swal.fire('Sukses', result.message, 'success');
            } catch (error) {
                if (!error.message.includes('validasi')) {
                    Swal.fire('Error', error.message, 'error');
                }
            } finally {
                submitButton.prop('disabled', false).html('Simpan');
            }
        }

        function deleteKunjungan(id) {
            Swal.fire({
                title: 'Yakin hapus data ini?',
                text: "Data dan semua file dokumentasi terkait akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const response = await fetch(`${kunjunganApiUrl}/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            }
                        });
                        const result = await response.json();
                        if (!response.ok) throw new Error(result.message);
                        kunjunganDataTable.ajax.reload(null, false);
                        Swal.fire('Dihapus!', result.message, 'success');
                    } catch (error) {
                        Swal.fire('Error', error.message, 'error');
                    }
                }
            });
        }
    </script>
@endsection
