<script>
    $(document).ready(function() {
        // ==========================================================
        // INISIALISASI PLUGIN (YANG SEBELUMNYA DI FILE UTAMA)
        // ==========================================================
        $('body').addClass('layout-composed');

        $('.select2-dropdown').select2({
            placeholder: 'Pilih item berikut',
            dropdownParent: $('#modal-tambah-alat')
        });

        $('#tglOrder').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
        });

        // ==========================================================
        // INISIALISASI DATATABLES DENGAN SERVER-SIDE PROCESSING
        // ==========================================================
        var table = $('#dt-pemakaian-alat').DataTable({
            processing: true,
            serverSide: true,
            responsive: false,
            ajax: "{{ route('layanan.rajal.pemakaian_alat.data', $registration->id) }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'tanggal_order',
                    name: 'tanggal_order'
                },
                {
                    data: 'doctor_name',
                    name: 'doctor.employee.fullname'
                },
                {
                    data: 'alat_name',
                    name: 'alat.nama'
                },
                {
                    data: 'qty',
                    name: 'qty'
                },
                {
                    data: 'kelas_name',
                    name: 'kelas_rawat.kelas'
                },
                {
                    data: 'lokasi',
                    name: 'lokasi'
                },
                {
                    data: 'user_name',
                    name: 'user.name'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        // ==========================================================
        // EVENT LISTENER UNTUK TOMBOL SIMPAN
        // ==========================================================
        $('#btn-save-alat').on('click', function(event) {
            event.preventDefault();

            var $btn = $(this);
            $btn.prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span> Menyimpan...'
            );
            var originalHtml = 'Save changes'; // Simpan teks aslinya

            const formData = {
                tanggal_order: $('#tglOrder').val(),
                doctor_id: $('#doctor-pemakaian-alat').val(),
                peralatan_id: $('#alat_medis').val(),
                kelas_rawat_id: $('#kelas').val(),
                departement_id: $('#departement').val(),
                qty: $('#qty').val(),
                user_id: {{ auth()->user()->id }},
                registration_id: $('#registration').val() || {{ $registration->id }},
                lokasi: $('#lokasi').val()
            };

            $.ajax({
                url: "{{ route('layanan.rajal.pemakaian_alat.store') }}",
                method: 'POST',
                data: formData,
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#modal-tambah-alat').modal('hide');
                        showSuccessAlert('Pemakaian alat berhasil ditambahkan!');
                        table.draw(); // Cukup panggil draw() untuk refresh tabel
                    } else {
                        showErrorAlertNoRefresh('Gagal: ' + response.message);
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Terjadi kesalahan saat menyimpan data.';
                    if (xhr.responseJSON) {
                        errorMessage = xhr.responseJSON.message || errorMessage;
                        if (xhr.responseJSON.errors) {
                            const errors = Object.values(xhr.responseJSON.errors).flat()
                                .join('<br>');
                            errorMessage += '<br><br>' + errors;
                        }
                    }
                    showErrorAlertNoRefresh(errorMessage);
                },
                complete: function() {
                    $btn.prop('disabled', false).html(originalHtml);
                }
            });
        });

        // ==========================================================
        // EVENT LISTENER UNTUK TOMBOL HAPUS (DELEGATION)
        // ==========================================================
        $('#dt-pemakaian-alat tbody').on('click', '.delete-action', function() {
            const usageId = $(this).data('id');
            const row = $(this).closest('tr');

            showDeleteConfirmation(function() {
                $.ajax({
                    url: "/api/simrs/layanan/rawat-jalan/pemakaian-alat/" +
                        usageId, // Perbaiki URL agar cocok dengan route
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            table.row(row).remove().draw(false);
                            showSuccessAlert('Data berhasil dihapus.');
                        } else {
                            showErrorAlertNoRefresh('Gagal menghapus: ' + response
                                .message);
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Gagal menghapus data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showErrorAlertNoRefresh(errorMessage);
                    }
                });
            });
        });

        // ==========================================================
        // EVENT MODAL
        // ==========================================================
        $('#modal-tambah-alat').on('hidden.bs.modal', function() {
            $('#store-form')[0].reset();
            $('#store-form select').val(null).trigger('change');
        });
    });
</script>
