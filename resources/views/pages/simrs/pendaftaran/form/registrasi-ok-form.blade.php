<div class="panel-hdr border-top">
    <h2 class="text-light">
        <i class="fas fa-address-card mr-3 ml-2 text-primary" style="transform: scale(2.1)"></i>
        <span class="text-primary">Order Operasi</span>
    </h2>
</div>

<div class="row">
    <div class="col-md-12 px-4 pb-2 pt-4">
        <div class="panel-container show">
            <div class="panel-content">
                <!-- Form for adding new operation order -->
                <div class="form-group row mb-4">
                    <div class="col-md-2">
                        <label for="tgl_order">Tanggal Order</label>
                        <input type="datetime-local" class="form-control" id="tgl_order" name="tgl_order" required>
                    </div>

                    <div class="col-md-2">
                        <label for="kelas_id">Kelas</label>
                        <select class="form-control select2" id="kelas_id" name="kelas_id" required>
                            <option value="">Pilih Kelas</option>
                            @foreach ($kelas_rawat as $kelas)
                                <option value="{{ $kelas->id }}">{{ $kelas->kelas }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="ruangan_id">Ruangan</label>
                        <select class="form-control select2" id="ruangan_id" name="ruangan_id" required>
                            <option value="">Pilih Ruangan</option>
                            @foreach ($ruangan as $room)
                                <option value="{{ $room->id }}">{{ $room->nama_ruangan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="tindakan_operasi_id">Tindakan Operasi</label>
                        <select class="form-control select2" id="tindakan_operasi_id" name="tindakan_operasi_id"
                            required>
                            <option value="">Pilih Tindakan</option>
                            @foreach ($tindakan_operasi as $tindakan)
                                <option value="{{ $tindakan->id }}">{{ $tindakan->nama_operasi }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="tipe_operasi">Tipe Operasi</label>
                        <select class="form-control" id="tipe_operasi" name="tipe_operasi" required>
                            <option value="">Pilih Tipe</option>
                            <option value="Elektif">Elektif</option>
                            <option value="Emergency">Emergency</option>
                        </select>
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" id="btn-tambah-order" class="btn btn-primary">
                            <span class="fal fa-plus-circle"></span> Tambah
                        </button>
                    </div>
                </div>

                <!-- Additional form fields -->
                <div class="form-group row mb-4">
                    <div class="col-md-3">
                        <label for="tipe_penggunaan">Tipe Penggunaan</label>
                        <select class="form-control" id="tipe_penggunaan" name="tipe_penggunaan" required>
                            <option value="">Pilih Tipe</option>
                            <option value="BPJS">BPJS</option>
                            <option value="Umum">Umum</option>
                            <option value="Asuransi">Asuransi</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="diagnosa">Diagnosa</label>
                        <input type="text" class="form-control" id="diagnosa" name="diagnosa" required>
                    </div>

                    <div class="col-md-3">
                        <label for="fungsi">Fungsi</label>
                        <input type="text" class="form-control" id="fungsi" name="fungsi" required>
                    </div>
                </div>

                <!-- datatable start -->
                <div class="table-responsive">
                    <table id="dt-order-operasi" class="table table-bordered table-hover table-striped w-100">
                        <thead class="bg-primary-600">
                            <tr>
                                <th>No</th>
                                <th>Tgl Order</th>
                                <th>Kelas</th>
                                <th>Ruangan</th>
                                <th>Tipe Operasi</th>
                                <th>Tipe Penggunaan</th>
                                <th>Diagnosa</th>
                                <th>Fungsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Rows will be added here dynamically -->
                        </tbody>
                    </table>
                </div>
                <!-- datatable end -->
            </div>
        </div>
    </div>
</div>

@section('scripts')
    <script>
        $(document).ready(function() {
            let currentIndex = 1;
            const registrationId = "{{ $registration->id }}";

            // Initialize Select2
            $('.select2').select2({
                placeholder: 'Pilih',
                allowClear: true
            });

            // Set default date
            $('#tgl_order').val(new Date().toISOString().slice(0, 16));

            // Load existing operation orders
            function loadOrderOperasi() {
                $.ajax({
                    url: `/api/simrs/order-operasi/${registrationId}`,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        const tbody = $('#dt-order-operasi tbody');
                        tbody.empty();
                        currentIndex = 1;

                        if (response.success && response.data.length > 0) {
                            response.data.forEach(order => {
                                const newRow = `
                            <tr>
                                <td>${currentIndex++}</td>
                                <td>${order.tgl_order || '-'}</td>
                                <td>${order.kelas?.kelas || '-'}</td>
                                <td>${order.ruangan?.nama_ruangan || '-'}</td>
                                <td>${order.tipe_operasi || '-'}</td>
                                <td>${order.tipe_penggunaan || '-'}</td>
                                <td>${order.diagnosa || '-'}</td>
                                <td>${order.fungsi || '-'}</td>
                                <td>
                                    <button class="btn btn-danger btn-sm delete-order" data-id="${order.id}">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        `;
                                tbody.append(newRow);
                            });
                        }
                    },
                    error: function(xhr) {
                        console.log('Error loading operation orders:', xhr);
                    }
                });
            }

            // Initial load
            loadOrderOperasi();

            // Add new operation order
            $('#btn-tambah-order').click(function() {
                const formData = {
                    registration_id: registrationId,
                    tgl_order: $('#tgl_order').val(),
                    kelas_id: $('#kelas_id').val(),
                    ruangan_id: $('#ruangan_id').val(),
                    tindakan_operasi_id: $('#tindakan_operasi_id').val(),
                    tipe_operasi: $('#tipe_operasi').val(),
                    tipe_penggunaan: $('#tipe_penggunaan').val(),
                    diagnosa: $('#diagnosa').val(),
                    fungsi: $('#fungsi').val()
                };

                // Validate required fields
                if (!formData.tgl_order || !formData.kelas_id || !formData.ruangan_id ||
                    !formData.tindakan_operasi_id || !formData.tipe_operasi ||
                    !formData.tipe_penggunaan || !formData.diagnosa || !formData.fungsi) {
                    showErrorAlertNoRefresh('Semua field harus diisi!');
                    return;
                }

                $.ajax({
                    url: '/api/simrs/order-operasi',
                    method: 'POST',
                    data: formData,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            showSuccessAlert('Order operasi berhasil ditambahkan!');
                            loadOrderOperasi();
                            // Reset form
                            $('#kelas_id, #ruangan_id, #tindakan_operasi_id, #tipe_operasi, #tipe_penggunaan')
                                .val('').trigger('change');
                            $('#diagnosa, #fungsi').val('');
                        } else {
                            showErrorAlertNoRefresh(response.message ||
                                'Gagal menambahkan order operasi');
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan saat menambahkan order operasi';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showErrorAlertNoRefresh(errorMessage);
                    }
                });
            });

            // Delete operation order
            $(document).on('click', '.delete-order', function() {
                const orderId = $(this).data('id');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Order operasi ini akan dihapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/api/simrs/order-operasi/${orderId}`,
                            method: 'DELETE',
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    showSuccessAlert('Order operasi berhasil dihapus!');
                                    loadOrderOperasi();
                                } else {
                                    showErrorAlertNoRefresh(response.message ||
                                        'Gagal menghapus order operasi');
                                }
                            },
                            error: function(xhr) {
                                let errorMessage =
                                    'Terjadi kesalahan saat menghapus order operasi';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }
                                showErrorAlertNoRefresh(errorMessage);
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
