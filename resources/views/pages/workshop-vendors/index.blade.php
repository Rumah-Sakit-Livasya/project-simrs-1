@extends('inc.layout')
@section('title', 'Master Vendor')

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <ol class="breadcrumb bg-primary-300">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item">Master Data</li>
            <li class="breadcrumb-item active">Data Vendor</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Daftar Vendor / Bengkel Rekanan</h2>
                        <div class="panel-toolbar">
                            <button class="btn btn-primary btn-sm" onclick="openCreateVendorModal()">Tambah Vendor</button>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="vendor-datatable" class="table table-bordered table-hover table-striped w-100">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Bengkel</th>
                                        <th>Kontak Person</th>
                                        <th>No. Telepon</th>
                                        <th>Spesialisasi</th>
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
    <div class="modal fade" id="vendorModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form id="vendorForm" onsubmit="submitVendorForm(event)">
                    <div class="modal-header">
                        <h5 class="modal-title" id="vendorModalTitle">Modal Title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fal fa-times"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="vendorId">
                        <div class="form-group">
                            <label for="name">Nama Bengkel</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <div class="invalid-feedback" id="name-error"></div>
                        </div>
                        <div class="form-group">
                            <label for="address">Alamat</label>
                            <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="contact_person">Kontak Person</label>
                                <input type="text" class="form-control" id="contact_person" name="contact_person">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="phone">No. Telepon</label>
                                <input type="text" class="form-control" id="phone" name="phone">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="specialization">Spesialisasi</label>
                            <input type="text" class="form-control" id="specialization" name="specialization"
                                placeholder="Contoh: AC, Mesin Diesel, Body Repair">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" id="vendorSubmitButton" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script>
        const vendorApiUrl = '/api/internal/workshop-vendors';
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let vendorDataTable;

        function clearVendorValidationErrors() {
            $('.form-control').removeClass('is-invalid');
        }

        $(document).ready(function() {
            vendorDataTable = $('#vendor-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: vendorApiUrl,
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
                        data: 'contact_person',
                        name: 'contact_person'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'specialization',
                        name: 'specialization'
                    },
                    {
                        data: 'id',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            return `
                            <button class="btn btn-warning btn-xs" onclick="openEditVendorModal(${data})">Edit</button>
                            <button class="btn btn-danger btn-xs" onclick="deleteVendor(${data})">Hapus</button>
                        `;
                        }
                    }
                ]
            });
        });

        function openCreateVendorModal() {
            clearVendorValidationErrors();
            $('#vendorForm')[0].reset();
            $('#vendorModalTitle').text('Tambah Vendor Baru');
            $('#vendorId').val('');
            $('#vendorModal').modal('show');
        }

        async function openEditVendorModal(id) {
            clearVendorValidationErrors();
            try {
                const response = await fetch(`${vendorApiUrl}/${id}`);
                if (!response.ok) throw new Error('Gagal mengambil data vendor.');
                const {
                    data
                } = await response.json();

                $('#vendorModalTitle').text('Edit Vendor');
                $('#vendorId').val(data.id);
                $('#name').val(data.name);
                $('#address').val(data.address);
                $('#contact_person').val(data.contact_person);
                $('#phone').val(data.phone);
                $('#specialization').val(data.specialization);
                $('#vendorModal').modal('show');
            } catch (error) {
                Swal.fire('Error', error.message, 'error');
            }
        }

        async function submitVendorForm(event) {
            event.preventDefault();
            const id = $('#vendorId').val();
            const method = id ? 'PUT' : 'POST';
            const url = id ? `${vendorApiUrl}/${id}` : vendorApiUrl;
            const form = document.getElementById('vendorForm');
            const formData = new FormData(form);
            if (method === 'PUT') formData.append('_method', 'PUT');

            const submitButton = $('#vendorSubmitButton');
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
                            $(`#${key}`).addClass('is-invalid');
                            $(`#${key}-error`).text(result[key][0]);
                        });
                    }
                    throw new Error(result.message || 'Terjadi kesalahan validasi.');
                }

                $('#vendorModal').modal('hide');
                vendorDataTable.ajax.reload();
                Swal.fire('Sukses', result.message, 'success');
            } catch (error) {
                Swal.fire('Error', error.message, 'error');
            } finally {
                submitButton.prop('disabled', false).html('Simpan');
            }
        }

        function deleteVendor(id) {
            Swal.fire({
                title: 'Yakin hapus vendor ini?',
                text: "Data tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const response = await fetch(`${vendorApiUrl}/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            }
                        });
                        const result = await response.json();
                        if (!response.ok) throw new Error(result.message);
                        vendorDataTable.ajax.reload();
                        Swal.fire('Dihapus!', result.message, 'success');
                    } catch (error) {
                        Swal.fire('Error', error.message, 'error');
                    }
                }
            });
        }
    </script>
@endsection
