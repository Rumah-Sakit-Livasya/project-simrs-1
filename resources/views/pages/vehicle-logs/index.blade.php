@extends('inc.layout')
@section('title', 'Jurnal Penggunaan Kendaraan')

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
            <li class="breadcrumb-item active">Jurnal Penggunaan</li>
        </ol>
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Riwayat Penggunaan Kendaraan</h2>
                        <div class="panel-toolbar">
                            <button class="btn btn-primary btn-sm" onclick="openPeminjamanModal()">Buat Peminjaman
                                Baru</button>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="log-datatable" class="table table-bordered table-hover table-striped w-100">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Kendaraan</th>
                                        <th>Pengemudi</th>
                                        <th>Tujuan</th>
                                        <th>Waktu Keluar</th>
                                        <th>Waktu Kembali</th>
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

    {{-- MODAL PEMINJAMAN --}}
    <div class="modal fade" id="peminjamanModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form id="peminjamanForm" onsubmit="submitPeminjamanForm(event)">
                    <div class="modal-header">
                        <h5 class="modal-title">Form Peminjaman Kendaraan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fal fa-times"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="internal_vehicle_id">Kendaraan</label>
                                <select class="form-control select2" id="internal_vehicle_id" name="internal_vehicle_id"
                                    required style="width: 100%;">
                                    <option></option>
                                    @foreach ($vehicles as $vehicle)
                                        <option value="{{ $vehicle->id }}">{{ $vehicle->name }}
                                            ({{ $vehicle->license_plate }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="driver_id">Pengemudi</label>
                                <select class="form-control select2" id="driver_id" name="driver_id" required
                                    style="width: 100%;">
                                    <option></option>
                                    @foreach ($drivers as $driver)
                                        <option value="{{ $driver->id }}">{{ $driver->employee->fullname ?? 'N/A' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="start_datetime">Tanggal & Jam Keluar</label>
                                <input type="datetime-local" class="form-control" id="start_datetime" name="start_datetime"
                                    required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="start_odometer">Kilometer Awal</label>
                                <input type="number" class="form-control" id="start_odometer" name="start_odometer"
                                    readonly required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="destination">Tujuan Perjalanan</label>
                            <input type="text" class="form-control" id="destination" name="destination" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" id="peminjamanSubmitButton" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL PENGEMBALIAN --}}
    <div class="modal fade" id="pengembalianModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form id="pengembalianForm" onsubmit="submitPengembalianForm(event)">
                    <div class="modal-header">
                        <h5 class="modal-title">Form Pengembalian Kendaraan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fal fa-times"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="logId">
                        <p><strong>Kendaraan:</strong> <span id="info_vehicle_name"></span></p>
                        <p><strong>KM Awal:</strong> <span id="info_start_odometer"></span></p>
                        <hr>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="end_datetime">Tanggal & Jam Kembali</label>
                                <input type="datetime-local" class="form-control" id="end_datetime" name="end_datetime"
                                    required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="end_odometer">Kilometer Akhir</label>
                                <input type="number" class="form-control" id="end_odometer" name="end_odometer"
                                    required>
                                <div class="invalid-feedback" id="end_odometer-error"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fuel_receipt">Upload Bukti BBM (Opsional)</label>
                            <input type="file" class="form-control-file" id="fuel_receipt" name="fuel_receipt">
                        </div>
                        <div class="form-group">
                            <label for="return_notes">Laporan / Catatan Kondisi Kendaraan</label>
                            <textarea class="form-control" id="return_notes" name="return_notes" rows="3"
                                placeholder="Contoh: Rem terasa kurang pakem, AC tidak dingin."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" id="pengembalianSubmitButton" class="btn btn-primary">Simpan</button>
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
    <script>
        const logApiUrl = '/api/internal/vehicle-logs';
        const vehicleApiUrl = '/api/internal/internal-vehicles';
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        let logDataTable;

        function clearValidationErrors(formId) {
            $(`#${formId} .form-control`).removeClass('is-invalid');
        }

        $(document).ready(function() {
            // Inisialisasi Select2
            $('#internal_vehicle_id').select2({
                placeholder: "-- Pilih Kendaraan --",
                dropdownParent: $('#peminjamanModal')
            });
            $('#driver_id').select2({
                placeholder: "-- Pilih Pengemudi --",
                dropdownParent: $('#peminjamanModal')
            });

            // Otomatis isi KM Awal
            $('#internal_vehicle_id').on('change', async function() {
                const vehicleId = $(this).val();
                if (!vehicleId) {
                    $('#start_odometer').val('');
                    return;
                }
                try {
                    const response = await fetch(`${vehicleApiUrl}/${vehicleId}/last-odometer`);
                    const data = await response.json();
                    $('#start_odometer').val(data.last_odometer);
                } catch (error) {
                    console.error('Gagal mengambil KM terakhir:', error);
                    $('#start_odometer').val('');
                }
            });

            logDataTable = $('#log-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: logApiUrl,
                    type: 'GET'
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'internal_vehicle.name',
                        name: 'internal_vehicle.name'
                    },
                    {
                        data: 'driver.employee.fullname',
                        name: 'driver.employee.fullname'
                    },
                    {
                        data: 'destination',
                        name: 'destination'
                    },
                    {
                        data: 'start_datetime',
                        name: 'start_datetime'
                    },
                    {
                        data: 'end_datetime',
                        name: 'end_datetime',
                        defaultContent: '-'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data) {
                            const badgeClass = data === 'Digunakan' ? 'badge-warning' :
                                'badge-success';
                            return `<span class="badge ${badgeClass}">${data}</span>`;
                        }
                    },
                    {
                        data: 'id',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            if (row.status === 'Digunakan') {
                                return `<button class="btn btn-success btn-xs" onclick="openPengembalianModal(${data})">Kembalikan</button>`;
                            }
                            // Tambah tombol delete jika perlu
                            return `<button class="btn btn-danger btn-xs" onclick="deleteLog(${data})">Hapus</button>`;
                        }
                    }
                ]
            });
        });

        // --- FUNGSI MODAL PEMINJAMAN ---
        function openPeminjamanModal() {
            clearValidationErrors('peminjamanForm');
            $('#peminjamanForm')[0].reset();
            $('#internal_vehicle_id').val(null).trigger('change');
            $('#driver_id').val(null).trigger('change');
            $('#peminjamanModal').modal('show');
        }

        async function submitPeminjamanForm(event) {
            event.preventDefault();
            const form = document.getElementById('peminjamanForm');
            const formData = new FormData(form);
            const submitButton = $('#peminjamanSubmitButton');
            submitButton.prop('disabled', true).html('Menyimpan...');

            try {
                const response = await fetch(logApiUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                const result = await response.json();
                if (!response.ok) throw result;

                $('#peminjamanModal').modal('hide');
                logDataTable.ajax.reload();
                Swal.fire('Sukses', result.message, 'success');
            } catch (error) {
                console.error(error);
                const message = error.message || 'Terjadi kesalahan.';
                Swal.fire('Error', message, 'error');
            } finally {
                submitButton.prop('disabled', false).html('Simpan');
            }
        }

        // --- FUNGSI MODAL PENGEMBALIAN ---
        async function openPengembalianModal(id) {
            clearValidationErrors('pengembalianForm');
            $('#pengembalianForm')[0].reset();
            try {
                const response = await fetch(`${logApiUrl}/${id}`);
                const {
                    data
                } = await response.json();
                $('#logId').val(data.id);
                $('#info_vehicle_name').text(`${data.vehicle.name} (${data.vehicle.license_plate})`);
                $('#info_start_odometer').text(data.start_odometer);
                $('#end_odometer').attr('min', data.start_odometer); // Set validasi min
                $('#pengembalianModal').modal('show');
            } catch (error) {
                Swal.fire('Error', 'Gagal memuat data log.', 'error');
            }
        }

        async function submitPengembalianForm(event) {
            event.preventDefault();
            const id = $('#logId').val();
            const url = `${logApiUrl}/${id}`;
            const form = document.getElementById('pengembalianForm');
            const formData = new FormData(form);
            formData.append('_method', 'PUT'); // Method spoofing untuk update

            const submitButton = $('#pengembalianSubmitButton');
            submitButton.prop('disabled', true).html('Menyimpan...');

            try {
                const response = await fetch(url, {
                    method: 'POST', // Selalu POST untuk FormData
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

                $('#pengembalianModal').modal('hide');
                logDataTable.ajax.reload();
                Swal.fire('Sukses', result.message, 'success');
            } catch (error) {
                Swal.fire('Error', error.message, 'error');
            } finally {
                submitButton.prop('disabled', false).html('Simpan');
            }
        }

        function deleteLog(id) {
            Swal.fire({
                title: 'Yakin hapus log ini?',
                text: "Data tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const response = await fetch(`${logApiUrl}/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            }
                        });
                        const result = await response.json();
                        if (!response.ok) throw new Error(result.message);
                        logDataTable.ajax.reload();
                        Swal.fire('Dihapus!', result.message, 'success');
                    } catch (error) {
                        Swal.fire('Error', error.message, 'error');
                    }
                }
            });
        }
    </script>
@endsection
