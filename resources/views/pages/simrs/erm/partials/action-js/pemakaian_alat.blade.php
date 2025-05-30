<script>
    $(document).ready(function() {
        // Variabel untuk menyimpan indeks saat ini
        let currentIndex = 1;

        // Event listener untuk tombol hapus tindakan medis
        $(document).on('click', '.delete-action', function() {
            const actionId = $(this).data('id');
            const $row = $(this).closest('tr'); // Baris yang akan dihapus

            // Menggunakan SweetAlert2 untuk konfirmasi penghapusan
            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: "Tindakan medis ini akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/api/simrs/delete-medical-action/${actionId}`, // Pastikan URL ini benar
                        method: 'DELETE',
                        dataType: 'json', // Pastikan respons diuraikan sebagai JSON
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                'content'), // Pastikan CSRF token tersedia
                            'Accept': 'application/json' // Tambahkan header ini untuk memastikan respons JSON
                        },
                        success: function(response) {
                            console.log('Respons delete-medical-action:', response);
                            if (response == 1) {
                                // Hapus baris dari tabel
                                $row.remove();
                                $('#modal-tambah-alat').modal('hide');
                                showSuccessAlert(
                                    'Tindakan medis berhasil dihapus.');
                            } else {
                                $('#modal-tambah-alat').modal('hide');
                                showErrorAlertNoRefresh(
                                    'Gagal menghapus alat medis: ' +
                                    response.message);
                            }
                        },
                        error: function(xhr) {
                            $('#modal-tambah-alat').modal('hide');

                            let errorMessage =
                                'Terjadi kesalahan yang tidak diketahui. Silakan coba lagi nanti.';

                            // Cek apakah respons JSON tersedia
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            } else if (xhr.status === 0) {
                                errorMessage =
                                    'Tidak terhubung ke server. Silakan periksa koneksi internet Anda.';
                            } else if (xhr.status === 404) {
                                errorMessage =
                                    'Tindakan medis yang ingin dihapus tidak ditemukan.';
                            } else if (xhr.status === 500) {
                                errorMessage =
                                    'Terjadi kesalahan pada server. Silakan coba lagi nanti.';
                            } else {
                                errorMessage =
                                    `Gagal menghapus tindakan medis. Status: ${xhr.status}, Pesan: ${xhr.statusText}`;
                            }

                            showErrorAlertNoRefresh(errorMessage);
                        }
                    });
                }
            });
        });

        // Set tanggal default untuk input
        let today = new Date();
        let day = String(today.getDate()).padStart(2, '0');
        let month = String(today.getMonth() + 1).padStart(2, '0');
        let year = today.getFullYear();
        let formattedDate = `${day}-${month}-${year}`;
        $('#tglOrder').val(formattedDate);

        // Inisialisasi datepicker
        // $('#tglOrder').datepicker({
        //     format: 'dd-mm-yyyy',
        //     autoclose: true,
        //     todayHighlight: true,
        // });

        // Ambil Tindakan Medis
        $('#departement').change(function() {
            var tindakanMedisSelect = $('#alat_medis');
            var selectedOption = $(this).find('option:selected');
            var groupTindakanMedisData = selectedOption.data('groups');

            // Kosongkan dropdown tindakan medis
            tindakanMedisSelect.empty();
            tindakanMedisSelect.append('<option value="" selected>Pilih Tindakan Medis</option>');

            // Cek apakah data grup tindakan medis ada dan valid
            if (groupTindakanMedisData && groupTindakanMedisData.length > 0) {
                // Tambahkan opsi tindakan medis berdasarkan grup
                $.each(groupTindakanMedisData, function(index, group) {
                    $.each(group.tindakan_medis, function(i, tindakan) {
                        tindakanMedisSelect.append(
                            $('<option></option>').val(tindakan.id).text(tindakan
                                .nama_tindakan)
                        );
                    });
                });
            } else {
                // Jika tidak ada grup tindakan medis, tambahkan opsi default
                tindakanMedisSelect.append(
                    '<option value="" selected>Tidak ada tindakan medis</option>'
                );
            }
        });

        // Fungsi untuk menambahkan tindakan medis baru ke tabel
        function addMedicalAction(data) {
            const doctorName = data.doctor?.employee?.fullname || 'Tidak Diketahui';
            const actionName = data.tindakan_medis?.nama_tindakan || 'Tidak Diketahui';
            const className = data.departement?.name || 'Tidak Diketahui';
            const qty = data.qty || 0;
            const userName = data.user?.employee?.fullname || 'Tidak Diketahui';
            const foc = data.foc || 'Tidak Diketahui';

            const newRow = `
            <tr>
                <td>${currentIndex++}</td>
                <td style="white-space: nowrap;">${data.tanggal_tindakan || 'Tidak Diketahui'}</td>
                <td>${doctorName}</td>
                <td>${actionName}</td>
                <td>${className}</td>
                <td>${qty}</td>
                <td>${userName}</td>
                <td>${foc}</td>
                <td>
                    <button class="btn btn-danger btn-sm delete-action" data-id="${data.id}">Hapus</button>
                </td>
            </tr>
        `;
            $('#dt-basic-example tbody').append(newRow);
        }

        // Event listener untuk pengiriman form untuk menambahkan tindakan medis baru
        $('#modal-tambah-alat #store-form').on('submit', function(event) {
            event.preventDefault(); // Mencegah pengiriman form default

            // Kumpulkan data dari form
            const formData = {
                tanggal_order: $('#tglOrder').val(),
                registration_id: $('#registration').val(),
                doctor_id: $('#doctor').val(),
                peralatan_id: $('#alat_medis').val(),
                kelas_rawat_id: $('#kelas').val(),
                departement_id: $('#departement').val(),
                qty: $('#qty').val(),
                user_id: {{ auth()->user()->id }},
                registration_id: {{ $registration->id }},
                lokasi: $('#lokasi').val()
            };

            // Kirim data ke server (API)
            $.ajax({
                url: "{{route('layanan.rajal.pemakaian_alat.store')}}", // Sesuaikan endpoint sesuai kebutuhan
                method: 'POST',
                data: formData,
                dataType: 'json', // Pastikan respons diuraikan sebagai JSON
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                        'content') // Pastikan CSRF token tersedia
                },
                success: function(response) {
                    console.log('Respons order-tindakan-medis:', response);
                    if (response.success) {
                        // Tambahkan tindakan medis baru ke tabel
                        addMedicalAction(response.data);

                        // Reset form
                        $('#store-form')[0].reset();
                        $('#store-form select').val(null).trigger('change');
                        $('#modal-tambah-alat').modal('hide');
                        showSuccessAlert('Tindakan medis berhasil ditambahkan!');
                    } else {
                        
                        $('#modal-tambah-alat').modal('hide');
                        showErrorAlertNoRefresh('Gagal menambahkan alat medis: ' +
                            response
                            .message);
                    }
                },
                error: function(xhr) {
                    $('#modal-tambah-alat').modal('hide');

                    let errorMessage =
                        'Terjadi kesalahan yang tidak diketahui. Silakan coba lagi nanti.';

                    // Cek apakah respons JSON tersedia
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.status === 0) {
                        errorMessage =
                            'Tidak terhubung ke server. Silakan periksa koneksi internet Anda.';
                    } else if (xhr.status ===
                        422) { // Unprocessable Entity, biasanya untuk validasi
                        if (xhr.responseJSON.errors) {
                            // Gabungkan semua pesan kesalahan validasi menjadi satu string
                            errorMessage = Object.values(xhr.responseJSON.errors).flat()
                                .join('<br>');
                        } else {
                            errorMessage = 'Data yang dikirim tidak valid.';
                        }
                    } else {
                        errorMessage =
                            `Gagal menambahkan tindakan medis. Status: ${xhr.status}, Pesan: ${xhr.statusText}`;
                    }

                    showErrorAlertNoRefresh(errorMessage);
                }
            });
        });

    });
</script>