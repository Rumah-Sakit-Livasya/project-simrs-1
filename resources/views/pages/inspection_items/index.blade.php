@extends('inc.layout')
@section('title', 'Master Item Pemeriksaan')

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <ol class="breadcrumb bg-primary-300">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item">Master Data</li>
            <li class="breadcrumb-item active">Item Pemeriksaan</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Daftar Item Pemeriksaan (Checklist)</h2>
                        <div class="panel-toolbar">
                            <button class="btn btn-primary btn-sm" onclick="openCreateItemModal()">Tambah Item</button>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="item-datatable" class="table table-bordered table-hover table-striped w-100">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Item</th>
                                        <th>Deskripsi</th>
                                        <th>Status</th>
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

    {{-- ================================= MODAL SECTION ================================= --}}
    <div class="modal fade" id="itemModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="itemForm" onsubmit="submitItemForm(event)">
                    <div class="modal-header">
                        <h5 class="modal-title" id="itemModalTitle">Modal Title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fal fa-times"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="itemId">
                        <div class="form-group">
                            <label for="name">Nama Item</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <div class="invalid-feedback" id="name-error"></div>
                        </div>
                        <div class="form-group">
                            <label for="description">Deskripsi (Opsional)</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            <div class="invalid-feedback" id="description-error"></div>
                        </div>
                        <div class="form-group">
                            <label for="is_active">Status</label>
                            <select class="form-control" id="is_active" name="is_active" required>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                            <div class="invalid-feedback" id="is_active-error"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" id="itemSubmitButton" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script>
        const itemApiUrl = '/api/internal/inspection-items';
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let itemDataTable;

        function clearItemValidationErrors() {
            $('.form-control').removeClass('is-invalid');
        }

        $(document).ready(function() {
            itemDataTable = $('#item-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: itemApiUrl,
                    type: 'GET'
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'is_active',
                        name: 'is_active',
                        render: function(data) {
                            return data == 1 ? '<span class="badge badge-success">Aktif</span>' :
                                '<span class="badge badge-danger">Tidak Aktif</span>';
                        }
                    },
                    {
                        data: 'id',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            return `
                            <button class="btn btn-warning btn-xs" onclick="openEditItemModal(${data})">Edit</button>
                            <button class="btn btn-danger btn-xs" onclick="deleteItem(${data})">Hapus</button>
                        `;
                        }
                    }
                ]
            });
        });

        function openCreateItemModal() {
            clearItemValidationErrors();
            $('#itemForm')[0].reset();
            $('#itemModalTitle').text('Tambah Item Baru');
            $('#itemId').val('');
            $('#is_active').val(1); // Set default to 'Aktif'
            $('#itemModal').modal('show');
        }

        async function openEditItemModal(id) {
            clearItemValidationErrors();
            try {
                const response = await fetch(`${itemApiUrl}/${id}`);
                if (!response.ok) throw new Error('Gagal mengambil data item.');
                const {
                    data
                } = await response.json();

                $('#itemModalTitle').text('Edit Item');
                $('#itemId').val(data.id);
                $('#name').val(data.name);
                $('#description').val(data.description);
                $('#is_active').val(data.is_active);
                $('#itemModal').modal('show');
            } catch (error) {
                console.error(error);
                Swal.fire('Error', error.message, 'error');
            }
        }

        async function submitItemForm(event) {
            event.preventDefault();
            clearItemValidationErrors();

            const id = $('#itemId').val();
            const method = id ? 'PUT' : 'POST';
            const url = id ? `${itemApiUrl}/${id}` : itemApiUrl;
            const form = document.getElementById('itemForm');
            const formData = new FormData(form);

            if (method === 'PUT') {
                formData.append('_method', 'PUT');
            }

            const submitButton = $('#itemSubmitButton');
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
                            const input = $(`#${key}`);
                            const errorDiv = $(`#${key}-error`);
                            input.addClass('is-invalid');
                            errorDiv.text(result[key][0]);
                        });
                    }
                    throw new Error(result.message || 'Terjadi kesalahan validasi.');
                }

                $('#itemModal').modal('hide');
                itemDataTable.ajax.reload();
                Swal.fire('Sukses', result.message, 'success');

            } catch (error) {
                Swal.fire('Error', error.message, 'error');
            } finally {
                submitButton.prop('disabled', false).html('Simpan');
            }
        }

        function deleteItem(id) {
            Swal.fire({
                title: 'Yakin hapus item ini?',
                text: "Data tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const response = await fetch(`${itemApiUrl}/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            }
                        });
                        const result = await response.json();
                        if (!response.ok) throw new Error(result.message);
                        itemDataTable.ajax.reload();
                        Swal.fire('Dihapus!', result.message, 'success');
                    } catch (error) {
                        Swal.fire('Error', error.message, 'error');
                    }
                }
            });
        }
    </script>
@endsection
