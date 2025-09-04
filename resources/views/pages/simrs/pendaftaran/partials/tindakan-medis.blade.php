<div class="panel-hdr border-top">
    <h2 class="text-light">
        <i class="fas fa-address-card mr-3 ml-2 text-primary" style="transform: scale(2.1)"></i>
        <span class="text-primary">Tindakan Medis</span>
    </h2>
</div>
<div class="row">
    <div class="col-md-12 px-4 pb-2 pt-4">
        <div class="panel-container show">
            <div class="panel-content">
                <!-- datatable start -->
                <div class="table-responsive">
                    <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                        <thead class="bg-primary-600">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Dokter</th>
                                <th>Tindakan</th>
                                <th>Kelas</th>
                                <th>Qty</th>
                                <th>Entry By</th>
                                <th>F.O.C</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Rows will be added here dynamically -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="9" class="text-center">
                                    <button type="button" class="btn btn-outline-primary waves-effect waves-themed"
                                        id="btn-tambah-tindakan" data-toggle="modal" data-id="{{ $registration->id }}"
                                        data-target="#modal-tambah-tindakan">
                                        <span class="fal fa-plus-circle"></span>
                                        Tambah Tindakan
                                    </button>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- datatable end -->
                @if (str_contains(\Illuminate\Support\Facades\Route::currentRouteName(), 'daftar-registrasi-pasien') ||
                        str_contains(url()->current(), '/daftar-registrasi-pasien/'))
                    <div class="d-flex justify-content-start m-3">
                        <button type="button" data-target-menu="tindakan-medis"
                            class="btn btn-outline-primary px-4 shadow-sm d-flex align-items-center btn-back-to-layanan">
                            <i class="fas fa-arrow-left mr-2"></i>
                            <span>Kembali ke Menu</span>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@include('pages.simrs.pendaftaran.partials.modal-tindakan-medis')

@section('script-tindakan-medis')
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
                        $('#modal-tambah-tindakan').modal('hide');
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

            // Ambil Tindakan Medis
            $('#departement').change(function() {
                var tindakanMedisSelect = $('#tindakanMedis');
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
                console.log(data);

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
            $('#store-form').on('submit', function(event) {
                event.preventDefault(); // Mencegah pengiriman form default

                // Kumpulkan data dari form
                const formData = {
                    tanggal_tindakan: $('#tglTindakan').val(),
                    registration_id: $('#registration').val(),
                    doctor_id: $('#dokterPerawat').val(),
                    tindakan_medis_id: $('#tindakanMedis').val(),
                    kelas: $('#kelas').val(),
                    departement_id: $('#departement').val(),
                    qty: $('#qty').val(),
                    user_id: {{ auth()->user()->id }}, // Ganti dengan data pengguna yang sebenarnya jika tersedia
                    foc: $('#diskonDokter').is(':checked') ? 'Yes' : 'No',
                };

                // Kirim data ke server (API)
                $.ajax({
                    url: '/api/simrs/order-tindakan-medis', // Sesuaikan endpoint sesuai kebutuhan
                    method: 'POST',
                    data: formData,
                    dataType: 'json', // Pastikan respons diuraikan sebagai JSON
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content') // Pastikan CSRF token tersedia
                    },
                    success: function(response) {
                        $('#modal-tambah-tindakan').modal('hide');
                        // Tambahkan tindakan medis baru ke tabel
                        addMedicalAction(response.data);
                        // Reset form
                        $('#store-form')[0].reset();
                        $('#store-form select').val(null).trigger('change');
                        showSuccessAlert('Tindakan medis berhasil ditambahkan!');
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
                            $('#modal-tambah-tindakan').modal('hide');
                            errorMessage =
                                `Gagal menambahkan tindakan medis. Status: ${xhr.status}, Pesan: ${xhr.statusText}`;
                        }

                        $('#modal-tambah-tindakan').modal('hide');
                        showErrorAlertNoRefresh(errorMessage);
                    }
                });
            });

            // Fungsi untuk membuka modal dan memuat data berdasarkan ID
            $('#modal-tambah-tindakan').on('shown.bs.modal', function(event) {
                let button = $(event.relatedTarget);
                let registrasiId = button.data('id'); // Ambil ID dari data-id

                $('#store-form select').val(null).trigger('change');

                if (registrasiId) {
                    $.ajax({
                        url: `/api/simrs/get-registrasi-data/${registrasiId}`, // Sesuaikan endpoint
                        method: 'GET',
                        dataType: 'json', // Pastikan respons diuraikan sebagai JSON
                        success: function(response) {
                            console.log('Respons get-registrasi-data:', response);
                            if (response.success) {
                                const data = response.data; // Data dari respons API

                                $('#tglTindakan').val(data.tanggal_tindakan ||
                                    formattedDate); // Default ke hari ini jika kosong
                                $('#dokterPerawat').val(data.dokter_id).trigger('change');
                                $('#departement').val(data.departement_id).trigger('change');
                                $('#kelas').val(data.kelas_id).trigger('change');
                                $('#tindakanMedis').empty().append(
                                    '<option value="" selected>Pilih Tindakan Medis</option>'
                                );

                                data.tindakan_medis.forEach(item => {
                                    $('#tindakanMedis').append(
                                        `<option value="${item.id}">${item.nama_tindakan}</option>`
                                    );
                                });

                                $('#tindakanMedis').trigger('change');
                                $('#qty').val(data.qty || 1);
                                $('#diskonDokter').prop('checked', data.diskon_dokter || false);
                            } else {
                                $('#modal-tambah-tindakan').modal('hide');
                                showErrorAlertNoRefresh('Data registrasi tidak ditemukan: ' +
                                    response
                                    .message);
                            }
                        },
                        error: function(xhr) {
                            console.error('Gagal memuat data registrasi:', xhr);
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
                                errorMessage = 'Data registrasi tidak ditemukan.';
                            } else if (xhr.status === 500) {
                                errorMessage =
                                    'Terjadi kesalahan pada server. Silakan coba lagi nanti.';
                            } else {
                                errorMessage =
                                    `Gagal memuat data registrasi. Status: ${xhr.status}, Pesan: ${xhr.statusText}`;
                            }

                            showErrorAlertNoRefresh(errorMessage);
                        },
                    });
                }

                // Initialize Select2 dengan dropdownParent
                $('#store-form #dokterPerawat').select2({
                    dropdownParent: $('#modal-tambah-tindakan'),
                    placeholder: 'Pilih Dokter/Perawat',
                    allowClear: true,
                });

                $('#store-form #departement').select2({
                    dropdownParent: $('#modal-tambah-tindakan'),
                    placeholder: 'Pilih Poliklinik',
                    allowClear: true,
                });

                $('#store-form #kelas').select2({
                    dropdownParent: $('#modal-tambah-tindakan'),
                    placeholder: 'Pilih Kelas',
                    allowClear: true,
                });

                $('#store-form #tindakanMedis').select2({
                    dropdownParent: $('#modal-tambah-tindakan'),
                    placeholder: 'Pilih Tindakan',
                    allowClear: true,
                });
            });
        });
    </script>
@endsection
