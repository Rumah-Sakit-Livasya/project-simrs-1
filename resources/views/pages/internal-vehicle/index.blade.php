@extends('inc.layout')
@section('title', 'Master Kendaraan')

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <ol class="breadcrumb bg-primary-300">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item">Master Data</li>
            <li class="breadcrumb-item active">Data Kendaraan</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Daftar Kendaraan Internal</h2>
                        <div class="panel-toolbar">
                            <button class="btn btn-primary btn-sm" onclick="openCreateModal()">Tambah Kendaraan</button>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="vehicle-datatable" class="table table-bordered table-hover table-striped w-100">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Kendaraan</th>
                                        <th>No. Plat</th>
                                        <th>Jenis</th>
                                        <th>Merek & Tipe</th>
                                        <th>Tahun</th>
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
    <div class="modal fade" id="vehicleModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form id="vehicleForm" onsubmit="submitForm(event)">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Modal Title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fal fa-times"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="vehicleId">
                        <input type="hidden" name="_method" id="formMethod" value="POST">

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="name">Nama Kendaraan</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Contoh: KR4 ERTIGA" required>
                                <div class="invalid-feedback" id="name-error"></div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="license_plate">No. Plat</label>
                                <input type="text" class="form-control" id="license_plate" name="license_plate" required>
                                <div class="invalid-feedback" id="license_plate-error"></div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="type">Jenis</label>
                                <input type="text" class="form-control" id="type" name="type"
                                    placeholder="Contoh: KR4" required>
                                <div class="invalid-feedback" id="type-error"></div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="brand_model">Merek & Tipe</label>
                                <input type="text" class="form-control" id="brand_model" name="brand_model"
                                    placeholder="Contoh: Suzuki Ertiga" required>
                                <div class="invalid-feedback" id="brand_model-error"></div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="model_year">Tahun Pembuatan</label>
                                <input type="number" class="form-control" id="model_year" name="model_year"
                                    placeholder="2023" required>
                                <div class="invalid-feedback" id="model_year-error"></div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="tax_due_date">Tgl Pajak Tahunan</label>
                                <input type="date" class="form-control" id="tax_due_date" name="tax_due_date" required>
                                <div class="invalid-feedback" id="tax_due_date-error"></div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="stnk_due_date">Tgl Pajak 5 Tahunan (STNK)</label>
                                <input type="date" class="form-control" id="stnk_due_date" name="stnk_due_date"
                                    required>
                                <div class="invalid-feedback" id="stnk_due_date-error"></div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="service_schedule_km">Jadwal Servis Berkala (KM)</label>
                                <input type="number" class="form-control" id="service_schedule_km"
                                    name="service_schedule_km" placeholder="Contoh: 10000">
                                <div class="invalid-feedback" id="service_schedule_km-error"></div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="service_schedule_months">Jadwal Servis Berkala (Bulan)</label>
                                <input type="number" class="form-control" id="service_schedule_months"
                                    name="service_schedule_months" placeholder="Contoh: 6">
                                <div class="invalid-feedback" id="service_schedule_months-error"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" id="submitButton" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script>
        const apiUrl = '/api/internal/internal-vehicles';
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let dataTable;

        function clearValidationErrors() {
            $('.form-control').removeClass('is-invalid');
        }

        $(document).ready(function() {
            dataTable = $('#vehicle-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: apiUrl,
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
                        data: 'license_plate',
                        name: 'license_plate'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'brand_model',
                        name: 'brand_model'
                    },
                    {
                        data: 'model_year',
                        name: 'model_year'
                    },
                    {
                        data: 'id',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            return `
                            <button class="btn btn-warning btn-xs" onclick="openEditModal(${data})">Edit</button>
                            <button class="btn btn-danger btn-xs" onclick="deleteVehicle(${data})">Hapus</button>
                        `;
                        }
                    }
                ]
            });
        });

        function openCreateModal() {
            clearValidationErrors();
            $('#vehicleForm')[0].reset();
            $('#modalTitle').text('Tambah Kendaraan Baru');
            $('#formMethod').val('POST');
            $('#vehicleId').val('');
            $('#vehicleModal').modal('show');
        }

        async function openEditModal(id) {
            clearValidationErrors();
            try {
                const response = await fetch(`${apiUrl}/${id}`);
                if (!response.ok) throw new Error('Gagal mengambil data kendaraan.');
                const {
                    data
                } = await response.json();

                $('#modalTitle').text('Edit Kendaraan');
                $('#formMethod').val('PUT');
                $('#vehicleId').val(data.id);
                $('#name').val(data.name);
                $('#license_plate').val(data.license_plate);
                $('#type').val(data.type);
                $('#brand_model').val(data.brand_model);
                $('#model_year').val(data.model_year);
                $('#tax_due_date').val(data.tax_due_date);
                $('#stnk_due_date').val(data.stnk_due_date);
                $('#service_schedule_km').val(data.service_schedule_km);
                $('#service_schedule_months').val(data.service_schedule_months);

                $('#vehicleModal').modal('show');
            } catch (error) {
                console.error(error);
                Swal.fire('Error', error.message, 'error');
            }
        }

        async function submitForm(event) {
            event.preventDefault();
            clearValidationErrors();

            const id = $('#vehicleId').val();
            const method = id ? 'PUT' : 'POST';
            const url = id ? `${apiUrl}/${id}` : apiUrl;
            const form = document.getElementById('vehicleForm');
            const formData = new FormData(form);

            // Laravel mengharapkan _method untuk PUT/PATCH request saat menggunakan form POST
            if (method === 'PUT') {
                formData.append('_method', 'PUT');
            }

            const submitButton = $('#submitButton');
            submitButton.prop('disabled', true).html('Menyimpan...');

            try {
                const response = await fetch(url, {
                    method: 'POST', // Selalu POST untuk FormData, method asli dihandle _method
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
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

                $('#vehicleModal').modal('hide');
                dataTable.ajax.reload();
                Swal.fire('Sukses', result.message, 'success');

            } catch (error) {
                console.error(error.message);
                Swal.fire('Error', error.message, 'error');
            } finally {
                submitButton.prop('disabled', false).html('Simpan');
            }
        }

        function deleteVehicle(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const response = await fetch(`${apiUrl}/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            }
                        });
                        const result = await response.json();
                        if (!response.ok) throw new Error(result.message);
                        dataTable.ajax.reload();
                        Swal.fire('Dihapus!', result.message, 'success');
                    } catch (error) {
                        Swal.fire('Error', error.message, 'error');
                    }
                }
            });
        }
    </script>
@endsection
