@extends('inc.layout')
@section('title', 'Master Pengemudi')

{{-- Tambahkan style tambahan jika perlu untuk select2 di dalam modal --}}
@section('style')
    <style>
        .select2-container--open {
            z-index: 9999999 !important;
        }
    </style>
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <ol class="breadcrumb bg-primary-300">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item">Master Data</li>
            <li class="breadcrumb-item active">Data Pengemudi</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Daftar Pengemudi (Driver)</h2>
                        <div class="panel-toolbar">
                            <button class="btn btn-primary btn-sm" onclick="openCreateDriverModal()">Tambah
                                Pengemudi</button>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="driver-datatable" class="table table-bordered table-hover table-striped w-100">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Pengemudi</th>
                                        <th>No. SIM</th>
                                        <th>Masa Berlaku SIM</th>
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
    <div class="modal fade" id="driverModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="driverForm" onsubmit="submitDriverForm(event)">
                    <div class="modal-header">
                        <h5 class="modal-title" id="driverModalTitle">Modal Title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fal fa-times"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="driverId">

                        <div class="form-group">
                            <label for="employee_id">Nama Pegawai</label>
                            {{-- Tambahkan class "select2" untuk inisialisasi --}}
                            <select class="form-control select2" id="employee_id" name="employee_id" required
                                style="width: 100%;">
                                <option></option> {{-- Option kosong untuk placeholder --}}
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->fullname }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="employee_id-error"></div>
                        </div>

                        <div class="form-group">
                            <label for="no_sim">No. SIM</label>
                            <input type="text" class="form-control" id="no_sim" name="no_sim" required>
                            <div class="invalid-feedback" id="no_sim-error"></div>
                        </div>

                        <div class="form-group">
                            <label for="masa_berlaku_sim">Masa Berlaku SIM</label>
                            {{-- Ganti type="date" menjadi type="text" dan tambahkan class "datepicker" --}}
                            <div class="input-group">
                                <input type="text" class="form-control datepicker" id="masa_berlaku_sim"
                                    name="masa_berlaku_sim" required readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text fs-xl">
                                        <i class="fal fa-calendar-alt"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="invalid-feedback" id="masa_berlaku_sim-error"></div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" id="driverSubmitButton" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('plugin')
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    {{-- Pastikan script Select2 & Datepicker sudah di-load dari layout utama --}}
    <script>
        const driverApiUrl = '/api/internal/drivers';
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let driverDataTable;

        function clearDriverValidationErrors() {
            $('.form-control').removeClass('is-invalid');
        }

        // ======================= PERUBAHAN KUNCI DI SINI =======================
        $(document).ready(function() {
            // 1. Inisialisasi Select2
            $('.select2').select2({
                placeholder: "-- Pilih Pegawai --",
                // Baris ini PENTING agar search box di select2 berfungsi di dalam modal
                dropdownParent: $('#driverModal')
            });

            // 2. Inisialisasi Datepicker
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd', // Sesuaikan format dengan database
                autoclose: true,
                todayHighlight: true,
                orientation: "bottom left" // Agar tidak tertutup keyboard di mobile
            });
            // =======================================================================

            driverDataTable = $('#driver-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: driverApiUrl,
                    type: 'GET'
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'employee.fullname',
                        name: 'employee.fullname',
                        defaultContent: '<i>N/A</i>'
                    },
                    {
                        data: 'no_sim',
                        name: 'no_sim'
                    },
                    {
                        data: 'masa_berlaku_sim',
                        name: 'masa_berlaku_sim'
                    },
                    {
                        data: 'id',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            return `
                            <button class="btn btn-warning btn-xs" onclick="openEditDriverModal(${data})">Edit</button>
                            <button class="btn btn-danger btn-xs" onclick="deleteDriver(${data})">Hapus</button>
                        `;
                        }
                    }
                ]
            });
        });

        function openCreateDriverModal() {
            clearDriverValidationErrors();
            $('#driverForm')[0].reset();
            $('#driverModalTitle').text('Tambah Pengemudi Baru');
            $('#driverId').val('');

            // PERUBAHAN: Reset Select2 dan Datepicker
            $('#employee_id').val(null).trigger('change');
            $('#masa_berlaku_sim').datepicker('update', '');

            $('#employee_id').prop('disabled', false);
            $('#driverModal').modal('show');
        }

        async function openEditDriverModal(id) {
            clearDriverValidationErrors();
            try {
                const response = await fetch(`${driverApiUrl}/${id}`);
                if (!response.ok) throw new Error('Gagal mengambil data pengemudi.');
                const {
                    data
                } = await response.json();

                $('#driverModalTitle').text('Edit Pengemudi');
                $('#driverId').val(data.id);

                // PERUBAHAN: Set value untuk Select2 dan Datepicker
                $('#employee_id').val(data.employee_id).trigger('change').prop('disabled',
                    true); // .trigger('change') penting!
                $('#masa_berlaku_sim').datepicker('update', data.masa_berlaku_sim);

                $('#no_sim').val(data.no_sim);

                $('#driverModal').modal('show');
            } catch (error) {
                console.error(error);
                Swal.fire('Error', error.message, 'error');
            }
        }

        // Fungsi submitForm tidak perlu diubah, karena FormData akan mengambil value yang benar
        async function submitDriverForm(event) {
            event.preventDefault();
            clearDriverValidationErrors();

            const id = $('#driverId').val();
            const method = id ? 'PUT' : 'POST';
            const url = id ? `${driverApiUrl}/${id}` : driverApiUrl;

            // Re-enable disabled field agar nilainya ikut terkirim
            $('#employee_id').prop('disabled', false);

            const form = document.getElementById('driverForm');
            const formData = new FormData(form);

            if (method === 'PUT') {
                formData.append('_method', 'PUT');
            }

            const submitButton = $('#driverSubmitButton');
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

                $('#driverModal').modal('hide');
                driverDataTable.ajax.reload();
                Swal.fire('Sukses', result.message, 'success');

            } catch (error) {
                console.error(error);
                Swal.fire('Error', error.message, 'error');
            } finally {
                submitButton.prop('disabled', false).html('Simpan');
                // Kembalikan ke state disabled jika sedang mode edit
                if (id) $('#employee_id').prop('disabled', true);
            }
        }

        // Fungsi delete tidak berubah
        function deleteDriver(id) {
            Swal.fire({
                title: 'Yakin hapus data ini?',
                text: "Data tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const response = await fetch(`${driverApiUrl}/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            }
                        });
                        const result = await response.json();
                        if (!response.ok) throw new Error(result.message);
                        driverDataTable.ajax.reload();
                        Swal.fire('Dihapus!', result.message, 'success');
                    } catch (error) {
                        Swal.fire('Error', error.message, 'error');
                    }
                }
            });
        }
    </script>
@endsection
