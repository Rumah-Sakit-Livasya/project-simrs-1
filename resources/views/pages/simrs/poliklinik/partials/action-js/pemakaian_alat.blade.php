<script>
    $(document).ready(function() {
        // Fungsi untuk menambahkan baris pemakaian alat baru ke tabel
        function addEquipmentUsageRow(data) {
            const doctorName = data.doctor?.employee?.fullname || 'Tidak Diketahui';
            const equipmentName = data.alat?.nama || 'Tidak Diketahui';
            const className = data.kelas_rawat?.kelas || 'Tidak Diketahui';
            const entryByName = data.user?.name || 'Tidak Diketahui';
            const usageDate = data.tanggal_order ? new Date(data.tanggal_order).toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            }) : 'Tidak Diketahui';

            // Dapatkan jumlah baris saat ini untuk nomor urut
            const rowCount = $('#dt-pemakaian-alat tbody tr').length + 1;

            const newRow = `
                <tr>
                    <td>${rowCount}</td>
                    <td>${usageDate}</td>
                    <td>${doctorName}</td>
                    <td>${equipmentName}</td>
                    <td>${data.qty || 1}</td>
                    <td>${className}</td>
                    <td>${data.lokasi || 'Tidak Diketahui'}</td>
                    <td>${entryByName}</td>
                    <td>
                        <button class="btn btn-danger btn-sm delete-action" data-id="${data.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            // Gunakan API DataTables untuk menambahkan baris baru agar sorting dan pagination tetap berfungsi
            $('#dt-pemakaian-alat').DataTable().row.add($(newRow)).draw(false);
        }

        // Event listener untuk pengiriman form penambahan alat
        // $('#modal-tambah-alat #store-form').on('submit', function(event) {
        //     event.preventDefault();

        //     const formData = {
        //         tanggal_order: $('#tglOrder').val(),
        //         doctor_id: $('#doctor-pemakaian-alat').val(),
        //         peralatan_id: $('#alat_medis').val(),
        //         kelas_rawat_id: $('#kelas').val(),
        //         departement_id: $('#departement').val(),
        //         qty: $('#qty').val(),
        //         user_id: {{ auth()->user()->id }},
        //         registration_id: {{ $registration->id }},
        //         lokasi: $('#lokasi').val()
        //     };

        //     $.ajax({
        //         url: "{{ route('layanan.rajal.pemakaian_alat.store') }}",
        //         method: 'POST',
        //         data: formData,
        //         dataType: 'json',
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         success: function(response) {
        //             if (response.success) {
        //                 $('#modal-tambah-alat').modal('hide');
        //                 addEquipmentUsageRow(response.data);
        //                 showSuccessAlert('Pemakaian alat berhasil ditambahkan!');
        //             } else {

        //                 showErrorAlertNoRefresh('Gagal menambahkan alat: ' + response
        //                     .message);
        //             }
        //         },
        //         error: function(xhr) {
        //             // Tutup Modal Kontol
        //             $('#modal-tambah-alat').removeClass('show').attr('aria-hidden',
        //                 'true').css('display', 'none');
        //             $('.modal-backdrop').remove();

        //             let errorMessage = 'Terjadi kesalahan saat menyimpan data.';
        //             if (xhr.responseJSON && xhr.responseJSON.message) {
        //                 errorMessage = xhr.responseJSON.message;
        //             }
        //             if (xhr.responseJSON && xhr.responseJSON.errors) {
        //                 const errors = Object.values(xhr.responseJSON.errors).flat().join(
        //                     '<br>');
        //                 errorMessage += '<br><br>' + errors;
        //             }
        //             showErrorAlertNoRefresh(errorMessage);
        //         }
        //     });
        // });

        // Ganti event dari submit form menjadi click pada #btn-save-alat
        $('#btn-save-alat').on('click', function(event) {
            event.preventDefault();

            var $btn = $(this);
            // Disable tombol dan tampilkan loading
            $btn.prop('disabled', true);
            var originalHtml = $btn.html();
            $btn.html(
                '<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span> Menyimpan...'
            );

            const formData = {
                tanggal_order: $('#tglOrder').val(),
                doctor_id: $('#doctor-pemakaian-alat').val(),
                peralatan_id: $('#alat_medis').val(),
                kelas_rawat_id: $('#kelas').val(),
                departement_id: $('#departement').val(),
                qty: $('#qty').val(),
                user_id: {{ auth()->user()->id }},
                registration_id: {{ $registration->id }},
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
                    // Aktifkan kembali tombol dan kembalikan isi tombol
                    $btn.prop('disabled', false);
                    $btn.html(originalHtml);

                    if (response.success) {
                        addEquipmentUsageRow(response.data);

                        $('#store-form')[0].reset();
                        $('#store-form select').val(null).trigger('change');

                        // Tutup modal secara paksa jika belum tertutup
                        $('#modal-tambah-alat').removeClass('show').attr('aria-hidden',
                            'true').css('display', 'none');
                        $('.modal-backdrop').remove();
                        $('body').removeClass('modal-open');

                        showSuccessAlert('Pemakaian alat berhasil ditambahkan!');
                    } else {
                        showErrorAlertNoRefresh('Gagal menambahkan alat: ' + response
                            .message);
                    }
                },
                error: function(xhr) {
                    $btn.prop('disabled', false);
                    $btn.html(originalHtml);

                    let errorMessage = 'Terjadi kesalahan saat menyimpan data.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = Object.values(xhr.responseJSON.errors).flat().join(
                            '<br>');
                        errorMessage += '<br><br>' + errors;
                    }
                    showErrorAlertNoRefresh(errorMessage);
                }
            });
        });

        // Event listener untuk tombol hapus
        $('#dt-pemakaian-alat tbody').on('click', '.delete-action', function() {
            const usageId = $(this).data('id');
            const row = $(this).closest('tr');

            showDeleteConfirmation(function() {
                $.ajax({
                    url: "{{ route('layanan.rajal.pemakaian_alat.destroy', ['id' => 'USAGE_ID_PLACEHOLDER']) }}"
                        .replace('USAGE_ID_PLACEHOLDER', usageId),
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#dt-pemakaian-alat').DataTable().row(row).remove()
                                .draw(false);
                            showSuccessAlert('Data berhasil dihapus.');
                        } else {
                            showErrorAlertNoRefresh('Gagal menghapus data: ' +
                                response.message);
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

        // Inisialisasi datepicker
        $('#tglOrder').datepicker({
            format: 'yyyy-mm-dd', // Sesuaikan format dengan yang dibutuhkan backend
            autoclose: true,
            todayHighlight: true,
        });

    });
</script>
