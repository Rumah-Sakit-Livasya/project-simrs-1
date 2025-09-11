<script>
    $(document).ready(function() {
        // Variabel untuk menyimpan indeks saat ini
        let currentIndex = 1;

        // Sembunyikan elemen 'tindakan-medis' saat pertama kali dimuat
        $('#tindakan-medis').hide();

        // Event listener untuk menu item "Tindakan Medis"
        $('.menu-layanan[data-layanan="tindakan-medis"]').on('click', function() {
            // $('#tindakan-medis').fadeToggle(); // Menampilkan atau menyembunyikan dengan animasi

            const registrationId = $('#registration').val();

            $.ajax({
                url: `/api/simrs/get-medical-actions/${registrationId}`,
                method: 'GET',
                dataType: 'json', // Pastikan respons diuraikan sebagai JSON
                success: function(response) {
                    console.log('Respons get-medical-actions:', response);
                    if (response.success) {
                        const data = response.data;
                        const tbody = $('#dt-basic-example tbody');

                        // Kosongkan baris yang ada
                        tbody.empty();
                        currentIndex = 1; // Reset indeks saat memuat data baru

                        // Isi tabel dengan tindakan medis yang diambil
                        data.forEach(action => {
                            const doctorName = action.doctor?.employee?.fullname ||
                                'Tidak Diketahui';
                            const actionName = action.tindakan_medis
                                ?.nama_tindakan || 'Tidak Diketahui';
                            const className = action.departement?.name ||
                                'Tidak Diketahui';
                            const qty = action.qty || 0;
                            const userName = action.user?.employee?.fullname ||
                                'Tidak Diketahui';
                            const foc = action.foc || 'Tidak Diketahui';

                            const newRow = `
                            <tr>
                                <td>${currentIndex++}</td>
                                <td style="white-space: nowrap;">${action.tanggal_tindakan || 'Tidak Diketahui'}</td>
                                <td>${doctorName}</td>
                                <td>${actionName}</td>
                                <td>${className}</td>
                                <td>${qty}</td>
                                <td>${userName}</td>
                                <td>${foc}</td>
                                <td>
                                    <button class="btn btn-danger btn-sm delete-action" data-id="${action.id}">Hapus</button>
                                </td>
                            </tr>
                        `;
                            tbody.append(newRow);
                        });
                    } else {
                        $('#modal-tambah-tindakan').modal('hide');
                        showErrorAlertNoRefresh('Gagal memuat tindakan medis: ' + response
                            .message);
                    }
                },
                error: function(xhr) {
                    $('#modal-tambah-tindakan').modal('hide');

                    let errorMessage =
                        'Terjadi kesalahan yang tidak diketahui. Silakan coba lagi nanti.';

                    // Cek apakah respons JSON tersedia
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.status === 0) {
                        errorMessage =
                            'Tidak terhubung ke server. Silakan periksa koneksi internet Anda.';
                    } else if (xhr.status === 404) {
                        errorMessage = 'Tindakan medis tidak ditemukan.';
                    } else if (xhr.status === 500) {
                        errorMessage =
                            'Terjadi kesalahan pada server. Silakan coba lagi nanti.';
                    } else {
                        errorMessage =
                            `Gagal memuat tindakan medis. Status: ${xhr.status}, Pesan: ${xhr.statusText}`;
                    }

                    // showErrorAlertNoRefresh(errorMessage);
                }
            });
        });

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
                                $('#modal-tambah-tindakan').modal('hide');
                                showSuccessAlert(
                                    'Tindakan medis berhasil dihapus.');
                            } else {
                                $('#modal-tambah-tindakan').modal('hide');
                                showErrorAlertNoRefresh(
                                    'Gagal menghapus tindakan medis: ' +
                                    response.message);
                            }
                        },
                        error: function(xhr) {
                            $('#modal-tambah-tindakan').modal('hide');

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
        $('#tglTindakan').val(formattedDate);

        // Inisialisasi datepicker
        $('#tglTindakan').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true,
        });

        // Pastikan kode ini ada di dalam file JS Anda
        $('#departement').on('change', function() {
            const tindakanMedisSelect = $('#tindakanMedis');
            const selectedOption = $(this).find('option:selected');
            const groupTindakanMedisData = selectedOption.data('groups');

            tindakanMedisSelect.empty().append('<option value="">Pilih Tindakan Medis</option>');

            if (groupTindakanMedisData && Array.isArray(groupTindakanMedisData)) {
                $.each(groupTindakanMedisData, function(index, group) {
                    if (group.tindakan_medis) {
                        $.each(group.tindakan_medis, function(i, tindakan) {
                            tindakanMedisSelect.append(new Option(tindakan
                                .nama_tindakan, tindakan.id));
                        });
                    }
                });
            }
            tindakanMedisSelect.trigger('change');
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
        $('#modal-tambah-tindakan #store-form').on('submit', function(event) {
            event.preventDefault();

            const modal = $('#modal-tambah-tindakan');
            const loadingOverlay = modal.find('.modal-loading-overlay');

            // Tampilkan loading overlay
            loadingOverlay.show();

            const formData = {
                tanggal_tindakan: $('#tglTindakan').val(),
                registration_id: $('#registration').val(),
                doctor_id: $('#dokterPerawat').val(),
                tindakan_medis_id: $('#tindakanMedis').val(),
                kelas: $('#kelas').val(),
                departement_id: $('#departement').val(),
                qty: $('#qty').val(),
                user_id: {{ auth()->user()->id }},
                foc: $('#diskonDokter').is(':checked') ? 'Yes' : 'No',
            };

            $.ajax({
                url: '/api/simrs/order-tindakan-medis',
                method: 'POST',
                data: formData,
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        addMedicalAction(response.data);
                        modal.modal('hide');
                        showSuccessAlert('Tindakan medis berhasil ditambahkan!');
                    } else {
                        showErrorAlertNoRefresh('Gagal menambahkan tindakan medis: ' +
                            response.message);
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Terjadi kesalahan yang tidak diketahui.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    if (xhr.status === 422) {
                        errorMessage = Object.values(xhr.responseJSON.errors).flat().join(
                            '<br>');
                    }
                    showErrorAlertNoRefresh(errorMessage);
                },
                complete: function() {
                    // Sembunyikan loading overlay SETELAH AJAX selesai
                    loadingOverlay.hide();
                }
            });
        });


        $('#modal-tambah-tindakan').on('shown.bs.modal', function(event) {
            const modal = $(this);
            const loadingOverlay = modal.find('.modal-loading-overlay');
            const registrasiId = "{{ $registration->id }}";
            let today = new Date();
            let day = String(today.getDate()).padStart(2, '0');
            let month = String(today.getMonth() + 1).padStart(2, '0');
            let year = today.getFullYear();
            let formattedDate = `${day}-${month}-${year}`;

            // Tampilkan loading overlay SEGERA
            loadingOverlay.show();

            // Reset form
            $('#store-form')[0].reset();
            $('#tglTindakan').val(formattedDate);
            $('#store-form select').val(null).trigger('change');

            // Inisialisasi Select2
            $('#store-form #dokterPerawat, #store-form #departement, #store-form #kelas, #store-form #tindakanMedis')
                .select2({
                    dropdownParent: $('#modal-tambah-tindakan')
                });

            if (registrasiId) {
                $.ajax({
                    url: `/api/simrs/get-registrasi-data/${registrasiId}`,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            const data = response.data;
                            $('#dokterPerawat').val(data.dokter_id).trigger('change');
                            $('#kelas').val(data.kelas_id).trigger('change');
                            $('#departement').val(data.departement_id).trigger('change');
                        } else {
                            showErrorAlertNoRefresh('Data registrasi tidak ditemukan: ' +
                                response.message);
                        }
                    },
                    error: function(xhr) {
                        showErrorAlertNoRefresh('Gagal memuat data registrasi.');
                    },
                    complete: function() {
                        // Sembunyikan loading overlay SETELAH AJAX selesai (baik sukses maupun gagal)
                        loadingOverlay.hide();
                    }
                });
            } else {
                // Jika tidak ada ID, langsung sembunyikan overlay
                loadingOverlay.hide();
            }
        });
    });
</script>
