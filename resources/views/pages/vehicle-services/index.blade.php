@extends('inc.layout')
@section('title', 'Manajemen Tiket Servis')

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <ol class="breadcrumb bg-primary-300">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Tiket Servis Kendaraan</li>
        </ol>
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Daftar Tiket Perbaikan & Servis</h2>
                        <div class="panel-toolbar">
                            {{-- Nanti bisa ditambahkan tombol "Buat Tiket Manual" jika perlu --}}
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="services-datatable" class="table table-bordered table-hover table-striped w-100">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Kendaraan</th>
                                        <th>Deskripsi Masalah</th>
                                        <th>Pelapor</th>
                                        <th>Status</th>
                                        <th>Tanggal Lapor</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Konten diisi oleh DataTables dari API --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- MODAL UNTUK PROSES TIKET SERVIS (REFACTORED) --}}
    <div class="modal fade" id="processModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Proses Tiket Perbaikan Kendaraan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="processServiceForm">
                        <input type="hidden" id="service_id" name="service_id">
                        <div class="alert alert-info bg-info-100">
                            <h5><i class="fal fa-info-circle mr-2"></i> Detail Laporan Kerusakan</h5>
                            <p class="mb-0"><strong>Kendaraan:</strong> <span id="infoVehicle"></span></p>
                            <p class="mb-0"><strong>Deskripsi Masalah:</strong> <span id="infoIssue"></span></p>
                        </div>
                        <hr>
                        <h5><i class="fal fa-cogs mr-2"></i> Detail Pengerjaan</h5>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="service_date">Tanggal Servis</label>
                                {{-- Input ini akan diinisialisasi sebagai Datepicker --}}
                                <input type="text" class="form-control" id="service_date" name="service_date" required
                                    placeholder="YYYY-MM-DD">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="workshop_vendor_id">Bengkel Pelaksana</label>
                                {{-- Select ini akan diinisialisasi sebagai Select2 --}}
                                <select id="workshop_vendor_id" name="workshop_vendor_id" style="width: 100%;"></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="work_done">Deskripsi Perbaikan yang Dilakukan</label>
                            <textarea class="form-control" id="work_done" name="work_done" rows="3" required></textarea>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="labor_cost">Biaya Jasa (Rp)</label>
                                <input type="number" class="form-control" id="labor_cost" name="labor_cost"
                                    placeholder="Contoh: 150000">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="parts_cost">Biaya Suku Cadang (Rp)</label>
                                <input type="number" class="form-control" id="parts_cost" name="parts_cost"
                                    placeholder="Contoh: 350000">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="odometer_at_service">Kilometer Kendaraan saat Servis</label>
                                <input type="number" class="form-control" id="odometer_at_service"
                                    name="odometer_at_service" placeholder="Contoh: 125000">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="status">Update Status</label>
                                {{-- Select ini juga akan diinisialisasi sebagai Select2 --}}
                                <select class="form-control" id="status" name="status" style="width: 100%;" required>
                                    <option></option> {{-- Placeholder untuk Select2 --}}
                                    <option value="In Progress">In Progress</option>
                                    <option value="Completed">Completed</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="invoice">Upload Nota / Faktur (JPG, PNG, PDF - Max 2MB)</label>
                            <input type="file" class="form-control-file" id="invoice" name="invoice"
                                accept="image/jpeg,image/png,application/pdf">
                        </div>

                        {{-- KONTENER BARU UNTUK PREVIEW --}}
                        <div id="invoice-preview-container" class="form-group" style="display: none;">
                            <label>Preview:</label>
                            <div id="invoice-preview"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="saveServiceButton">Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            // ... Inisialisasi Plugin (datepicker, select2) tetap sama ...
            $('#service_date').datepicker({
                todayHighlight: true,
                orientation: "bottom left",
                format: 'yyyy-mm-dd',
                autoclose: true
            });
            $('#workshop_vendor_id').select2({
                placeholder: "-- Pilih Bengkel --",
                dropdownParent: $('#processModal')
            });
            $('#status').select2({
                placeholder: "Pilih Status Baru",
                dropdownParent: $('#processModal')
            });

            const table = $('#services-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('/api/internal/vehicle-services') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'internal_vehicle.name',
                        name: 'internal_vehicle.name',
                        defaultContent: '<i>Dihapus</i>'
                    },
                    {
                        data: 'description_of_issue',
                        name: 'description_of_issue'
                    },
                    {
                        data: 'reporter.name',
                        name: 'reporter.name',
                        defaultContent: '<i>N/A</i>'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data) {
                            let badgeClass = 'badge-secondary';
                            if (data === 'Open') badgeClass = 'badge-danger';
                            if (data === 'In Progress') badgeClass = 'badge-warning';
                            if (data === 'Completed') badgeClass = 'badge-success';
                            return `<span class="badge ${badgeClass}">${data}</span>`;
                        }
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: function(data) {
                            return new Date(data).toLocaleString('id-ID', {
                                dateStyle: 'medium',
                                timeStyle: 'short'
                            });
                        }
                    },
                    {
                        data: 'id',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        // ### LOGIKA BARU UNTUK TOMBOL DINAMIS ###
                        render: function(data, type, row) {
                            let button = '';
                            if (row.status === 'Open') {
                                button =
                                    `<button class="btn btn-primary btn-xs btn-proses" data-id="${row.id}" title="Proses Tiket Ini">Proses</button>`;
                            } else if (row.status === 'In Progress') {
                                button =
                                    `<button class="btn btn-warning btn-xs btn-proses" data-id="${row.id}" title="Lanjutkan Proses">Lanjutkan</button>`;
                            } else if (row.status === 'Completed') {
                                button =
                                    `<button class="btn btn-secondary btn-xs btn-proses" data-id="${row.id}" title="Lihat Detail Selesai">Lihat Detail</button>`;
                            }
                            // Nanti Anda bisa menambahkan tombol hapus di sini jika perlu
                            // button += ` <button class="btn btn-danger btn-xs btn-delete" data-id="${row.id}">Hapus</button>`;
                            return button;
                        }
                    }
                ],
                order: [
                    [0, 'desc']
                ]
            });

            // Event listener untuk tombol "Proses", "Lanjutkan", atau "Lihat Detail"
            $('#services-datatable tbody').on('click', '.btn-proses', function() {
                const serviceId = $(this).data('id');
                const form = $('#processServiceForm');
                const previewContainer = $('#invoice-preview-container');

                // Reset form & preview
                form[0].reset();
                $('#service_id').val(serviceId);
                $('#workshop_vendor_id').val(null).trigger('change');
                $('#status').val(null).trigger('change');
                previewContainer.hide().find('#invoice-preview').empty();

                // Ambil data bengkel (selalu diperlukan)
                $.get(`/api/internal/workshop-vendors-list`, function(vendors) {
                    const vendorSelect = $('#workshop_vendor_id');
                    vendorSelect.empty().append('<option></option>');
                    vendors.forEach(function(vendor) {
                        vendorSelect.append(new Option(vendor.name, vendor.id, false,
                            false));
                    });

                    // Ambil data detail tiket
                    $.get(`/api/internal/vehicle-services/${serviceId}`, function(serviceData) {
                        $('#infoVehicle').text(serviceData.internal_vehicle.name);
                        $('#infoIssue').text(serviceData.description_of_issue);

                        // Isi form dengan data yang ada
                        $('#service_date').datepicker('update', serviceData.service_date ||
                            new Date());
                        $('#work_done').val(serviceData.work_done);
                        $('#labor_cost').val(serviceData.labor_cost);
                        $('#parts_cost').val(serviceData.parts_cost);
                        $('#odometer_at_service').val(serviceData.odometer_at_service);
                        $('#status').val(serviceData.status).trigger('change');
                        $('#workshop_vendor_id').val(serviceData.workshop_vendor_id)
                            .trigger('change');

                        // Tampilkan preview nota yang ada
                        if (serviceData.invoice_path) {
                            const previewHtml =
                                `<a href="${serviceData.invoice_path}" target="_blank" class="btn btn-outline-primary btn-sm">Lihat Faktur Tersimpan</a>`;
                            previewContainer.show().find('#invoice-preview').html(
                                previewHtml);
                        }

                        // ### LOGIKA BARU UNTUK MODE READ-ONLY ###
                        if (serviceData.status === 'Completed') {
                            // Nonaktifkan semua field form dan sembunyikan tombol simpan
                            form.find('input, select, textarea').prop('disabled', true);
                            $('#saveServiceButton').hide();
                            $('.modal-title').text('Detail Tiket Selesai');
                        } else {
                            // Aktifkan kembali field form dan tampilkan tombol simpan
                            form.find('input, select, textarea').prop('disabled', false);
                            $('#saveServiceButton').show();
                            $('.modal-title').text('Proses Tiket Perbaikan Kendaraan');
                        }

                        $('#processModal').modal('show');
                    });
                });
            });

            // Event listener untuk preview file
            $('#invoice').on('change', function() {
                // ... (Kode preview file Anda tidak berubah)
                const file = this.files[0];
                const previewContainer = $('#invoice-preview-container');
                const previewArea = $('#invoice-preview');
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        let previewHtml = '';
                        if (file.type.startsWith('image/')) {
                            previewHtml =
                                `<img src="${e.target.result}" class="img-fluid rounded border" style="max-height: 200px;">`;
                        } else if (file.type === 'application/pdf') {
                            previewHtml =
                                `<div class="text-center"><i class="fal fa-file-pdf fa-3x text-danger"></i><p class="mt-2 small">${file.name}</p></div>`;
                        } else {
                            previewHtml =
                                `<div class="text-center"><i class="fal fa-file fa-3x"></i><p class="mt-2 small">${file.name}</p></div>`;
                        }
                        previewArea.html(previewHtml);
                        previewContainer.show();
                    }
                    reader.readAsDataURL(file);
                } else {
                    previewContainer.hide().find('#invoice-preview').empty();
                }
            });

            // Event listener untuk tombol Simpan
            $('#saveServiceButton').on('click', function() {
                // ... (Kode AJAX submit Anda tidak berubah)
                const form = $('#processServiceForm')[0];
                const formData = new FormData(form);
                const serviceId = $('#service_id').val();
                const submitButton = $(this);
                formData.append('_method', 'PUT');
                submitButton.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm"></span> Menyimpan...');
                $.ajax({
                    url: `/api/internal/vehicle-services/${serviceId}`,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire('Sukses!', response.message, 'success');
                        $('#processModal').modal('hide');
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON.errors;
                        let errorHtml = '<ul>';
                        $.each(errors, function(key, value) {
                            errorHtml += '<li>' + value[0] + '</li>';
                        });
                        errorHtml += '</ul>';
                        Swal.fire('Error!', 'Gagal menyimpan data:<br>' + errorHtml, 'error');
                    },
                    complete: function() {
                        submitButton.prop('disabled', false).html('Simpan Perubahan');
                    }
                });
            });
        });
    </script>
@endsection
