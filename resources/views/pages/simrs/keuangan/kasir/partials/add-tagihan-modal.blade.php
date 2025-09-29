{{-- filepath: d:\IT\App\simrs-laravel\resources\views\pages\simrs\Keuangan\kasir\partials\add-tagihan-modal.blade.php --}}
<div class="modal fade" id="add-tagihan-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form id="formAddTagihan" method="POST" action="{{ route('tagihan.pasien.store') }}">
                <input type="hidden" name="bilingan_id" value="{{ $bilingan->id }}">
                <input type="hidden" name="registration_id" value="{{ $bilingan->registration->id }}">
                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Tambah Tagihan</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date">Tanggal & Jam</label>
                                <input type="text" class="form-control datetimepicker" id="date" name="date"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="tipe_tagihan">Tipe Tagihan</label>
                                <select class="form-control" id="tipe_tagihan" name="tipe_tagihan" required>
                                    <option value="Biaya Tindakan Medis">Biaya Tindakan Medis</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="kelas_rawat">Kelas Rawat</label>
                                <select class="form-control select2" id="kelas_rawat" name="kelas_rawat_id"
                                    required></select>
                            </div>
                            <div class="form-group">
                                <label for="dokter">Dokter</label>
                                <select class="form-control select2" id="dokter" name="dokter_id" required></select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="departement">Departement</label>
                                <select class="form-control select2" id="departement" name="departement_id"
                                    required></select>
                            </div>
                            <div class="form-group">
                                <label for="tindakan">Tindakan</label>
                                <select class="form-control select2" id="tindakan" name="tindakan_id"
                                    required></select>
                            </div>
                            <div class="form-group">
                                <label for="quantity">Quantity</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" min="1"
                                    value="1" required>
                            </div>
                            <div class="form-group">
                                <label for="nominal_awal">Harga</label>
                                <input type="text" class="form-control" id="nominal_awal" name="nominal_awal"
                                    readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" id="submit-tagihan" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('plugin-add-tagihan')
    <style>
        .select2-container--open {
            z-index: 10560 !important;
            /* Pastikan lebih tinggi dari modal */
        }
    </style>
    <script>
        $(document).ready(function() {
            $('#submit-tagihan').on('click', function(e) {
                e.preventDefault();
                // Variabel
                const userId = {{ auth()->user()->id }};
                const registrationId = {{ $bilingan->registration->id }};
                const bilinganId = {{ $bilingan->id }};

                // Ambil data dari form
                const data = {
                    date: $('#date').val(),
                    tipe_tagihan: $('#tipe_tagihan').val(),
                    kelas_rawat_id: $('#kelas_rawat').val(),
                    dokter_id: $('#dokter').val(),
                    departement_id: $('#departement').val(),
                    tindakan_id: $('#tindakan').val(),
                    quantity: $('#quantity').val(),
                    nominal_awal: $('#nominal_awal').val().replace(/\./g, ''), // Hapus format angka
                    bilingan_id: bilinganId,
                    registration_id: registrationId,
                    user_id: userId,
                };

                // Kirim data ke API
                $.ajax({
                    url: '/simrs/kasir/tagihan-pasien',
                    type: 'POST',
                    dataType: 'json',
                    contentType: 'application/json',
                    data: JSON.stringify(data),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content') // Pastikan CSRF token tersedia
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Data berhasil disimpan.',
                            timer: 2000,
                            showConfirmButton: false,
                        });

                        // Tutup modal dan reset form
                        $('#add-tagihan-modal').modal('hide');
                        $('#formAddTagihan')[0].reset();
                        $('.select2').val(null).trigger('change');

                        // Reload table tagihanTable
                        $('#tagihanTable').DataTable().ajax.reload();
                    },
                    error: function(xhr) {
                        console.log('Error:', xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Terjadi kesalahan saat menyimpan data.',
                        });
                    },
                });
            });

            const registrationId = {{ $bilingan->registration->id }}; // Ganti dengan ID registrasi yang sesuai
            const bilinganId = {{ $bilingan->id }}; // Ganti dengan ID registrasi yang sesuai
            loadRegistrationData(registrationId);
            console.log(
                "{{ $bilingan->registration->penjamin->group_penjamin->id }}"
            ); // Ganti dengan ID registrasi yang sesuai


            $('#add-tagihan-modal').on('shown.bs.modal', function() {
                // Mengisi field tanggal_dan_jam dengan tanggal dan waktu saat ini
                const now = new Date();

                // Format tanggal menjadi YYYY-MM-DD HH:mm:ss
                const year = now.getFullYear();
                const month = String(now.getMonth() + 1).padStart(2, '0'); // Bulan dimulai dari 0
                const day = String(now.getDate()).padStart(2, '0');
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');

                const formattedDate = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
                $('#date').val(formattedDate);

                $('#tipe_tagihan').select2({
                    dropdownParent: $('#add-tagihan-modal'),
                    placeholder: 'Pilih Tipe Tagihan',
                    allowClear: true
                });

                // Set default option values from registration (bilingan->registration)
                var kelasRawatDefault = {
                    id: '{{ $bilingan->registration->kelas_rawat_id }}',
                    text: '{{ $bilingan->registration->kelas_rawat->kelas ?? 'Pilih Kelas Rawat' }}'
                };
                var dokterDefault = {
                    id: '{{ $bilingan->registration->doctor_id }}',
                    text: '{{ $bilingan->registration->doctor->employee->fullname ?? 'Pilih Dokter' }}'
                };
                var departementDefault = {
                    id: '{{ $bilingan->registration->departement_id }}',
                    text: '{{ $bilingan->registration->departement->name ?? 'Pilih Departement' }}'
                };

                // Kelas Rawat Select2 with default option
                $('#kelas_rawat').append(new Option(kelasRawatDefault.text, kelasRawatDefault.id, true,
                    true)).trigger('change');
                $('#kelas_rawat').select2({
                    dropdownParent: $('#add-tagihan-modal'),
                    placeholder: 'Pilih Kelas Rawat',
                    allowClear: true,
                    ajax: {
                        url: '/api/simrs/master-data/setup/kelas-rawat',
                        dataType: 'json',
                        processResults: function(data) {
                            return {
                                results: data.map(function(item) {
                                    return {
                                        id: item.id,
                                        text: item.kelas
                                    };
                                })
                            };
                        }
                    }
                });

                // Dokter Select2 with default option
                $('#dokter').append(new Option(dokterDefault.text, dokterDefault.id, true, true))
                    .trigger(
                        'change');
                $('#dokter').select2({
                    dropdownParent: $('#add-tagihan-modal'),
                    placeholder: 'Pilih Dokter',
                    allowClear: true,
                    ajax: {
                        url: '/api/simrs/master-data/employee/doctors',
                        dataType: 'json',
                        processResults: function(data) {
                            return {
                                results: data.map(function(item) {
                                    return {
                                        id: item.id,
                                        text: item.text
                                    };
                                })
                            };
                        }
                    }
                });

                // Departement Select2 with default option
                $('#departement').append(new Option(departementDefault.text, departementDefault.id,
                    true,
                    true)).trigger('change');
                $('#departement').select2({
                    dropdownParent: $('#add-tagihan-modal'),
                    placeholder: 'Pilih Departement',
                    allowClear: true,
                    ajax: {
                        url: '/api/simrs/master-data/setup/departemen',
                        dataType: 'json',
                        processResults: function(data) {
                            return {
                                results: data.map(function(item) {
                                    return {
                                        id: item.id,
                                        text: item.name
                                    };
                                })
                            };
                        }
                    }
                });

                $('#tindakan').select2({
                    dropdownParent: $('#add-tagihan-modal'),
                    placeholder: 'Pilih Tindakan',
                    allowClear: true,
                    ajax: {
                        url: '/api/simrs/master-data/layanan-medis/tindakan-medis',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                search: params.term, // Kata kunci pencarian
                                departement_id: $('#departement').val(),
                                kelas_rawat_id: $('#kelas_rawat').val()
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data.map(function(item) {
                                    return {
                                        id: item.id,
                                        text: item.nama_tindakan
                                    };
                                })
                            };
                        }
                    }
                });

                // Fungsi untuk update harga berdasarkan tarif dan quantity
                function updateHarga() {
                    const tindakanId = $('#tindakan').val();
                    const groupPenjaminId = "{{ $bilingan->registration->penjamin->group_penjamin->id }}";
                    const kelasRawatId = $('#kelas_rawat').val();
                    if (tindakanId && groupPenjaminId && kelasRawatId) {
                        $.ajax({
                            url: '/api/simrs/master-data/layanan-medis/tarif-tindakan',
                            type: 'GET',
                            data: {
                                tindakan_id: tindakanId,
                                group_penjamin_id: groupPenjaminId,
                                kelas_rawat_id: kelasRawatId
                            },
                            success: function(response) {
                                let quantity = parseInt($('#quantity').val()) || 1;
                                let total = response.harga;
                                $('#nominal_awal').val(total.toLocaleString('id-ID'));
                            },
                            error: function(xhr) {
                                console.log('Error fetching tarif:', xhr.responseText);
                            }
                        });
                    }
                }

                // Panggil updateHarga saat tindakan dipilih maupun saat quantity berubah
                $('#tindakan').on('change', updateHarga);
                $('#quantity').on('input', updateHarga);
            });

            $('#tindakan').change(function() {
                const tindakanId = $(this).val();
                const groupPenjaminId =
                    "{{ $bilingan->registration->penjamin->group_penjamin->id }}"; // ID Group Penjamin
                const kelasRawatId = $('#kelas_rawat').val();

                if (tindakanId && groupPenjaminId && kelasRawatId) {
                    $.ajax({
                        url: '/api/simrs/master-data/layanan-medis/tarif-tindakan',
                        type: 'GET',
                        data: {
                            tindakan_id: tindakanId,
                            group_penjamin_id: groupPenjaminId,
                            kelas_rawat_id: kelasRawatId
                        },
                        success: function(response) {
                            $('#nominal_awal').val(response.harga.toLocaleString('id-ID'));
                        },
                        error: function(xhr) {
                            console.log('Error fetching tarif:', xhr.responseText);
                        }
                    });
                }
            });
        });

        function loadRegistrationData(registrationId) {
            $.ajax({
                url: `/api/simrs/get-registrasi-data/${registrationId}`,
                type: 'GET',
                success: function(data) {
                    // Set nilai default untuk Departement
                    $('#departement').val(data.data.departement_id).trigger('change');

                    // Set nilai default untuk Kelas Rawat
                    $('#kelas_rawat').val(data.data.kelas_id).trigger('change');

                    // Set nilai default untuk Dokter
                    $('#dokter').val(data.data.dokter_id).trigger('change');
                },
                error: function(xhr) {
                    console.log('Error loading registration data:', xhr.responseText);
                }
            });
        }
    </script>
@endsection
