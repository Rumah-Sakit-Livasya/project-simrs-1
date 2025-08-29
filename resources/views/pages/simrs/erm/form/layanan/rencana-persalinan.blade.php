@extends('pages.simrs.erm.index')
@section('erm')
    {{-- content start --}}
    @if (isset($registration) || $registration != null)
        <div class="tab-content p-3">
            <div class="tab-pane fade show active" id="tab_default-1" role="tabpanel">
                @include('pages.simrs.erm.partials.detail-pasien')
                <hr style="border-color: #868686; margin-top: 50px; margin-bottom: 30px;">
                <header class="text-primary text-center font-weight-bold mb-4">
                    <div id="alert-pengkajian"></div>
                    <h2 class="font-weight-bold">Rencana Persalinan</h4>
                </header>
                <hr style="border-color: #868686; margin-top: 30px; margin-bottom: 30px;">
                <div class="row">
                    <div class="col-md-12">
                        @include('pages.simrs.pendaftaran.partials.persalinan')
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
@section('plugin-erm')
    <script></script>

    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let dtPersalinan = null;
            let registrationId = $('#btn-tambah-order-persalinan').data('registration-id') || 0;

            function initializeDataTable() {
                if (dtPersalinan && $.fn.DataTable.isDataTable('#dt-order-persalinan')) {
                    dtPersalinan.ajax.reload();
                    return;
                }
                if (!registrationId || registrationId === 0) {
                    $('#dt-order-persalinan tbody').html(
                        '<tr><td colspan="7" class="text-center text-muted">Registration ID tidak valid.</td></tr>'
                    );
                    return;
                }
                dtPersalinan = $('#dt-order-persalinan').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: false,
                    lengthChange: false,
                    ajax: {
                        url: `/simrs/persalinan/order-data/${registrationId}`,
                        type: 'GET',
                        error: function(xhr) {
                            Swal.fire('Error', 'Gagal memuat data tabel: ' + (xhr.responseJSON
                                ?.message || xhr.statusText), 'error');
                            $('#dt-order-persalinan tbody').html(
                                '<tr><td colspan="7" class="text-center text-danger">Gagal memuat data.</td></tr>'
                            );
                        }
                    },
                    columns: [{
                            data: 'tgl_rencana',
                            name: 'tgl_persalinan'
                        },
                        {
                            data: 'pasien',
                            name: 'registration.patient.name'
                        },
                        {
                            data: 'tindakan',
                            name: 'persalinan.nama_persalinan',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'tipe_persalinan',
                            name: 'tipePersalinan.tipe'
                        },
                        {
                            data: 'kategori',
                            name: 'kategori.nama'
                        },
                        {
                            data: 'dokter_bidan',
                            name: 'dokterBidan.employee.fullname'
                        },
                        {
                            data: 'aksi',
                            name: 'aksi',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        }
                    ],
                    order: [
                        [0, 'desc']
                    ],
                    pageLength: 10,
                    language: {
                        emptyTable: "Tidak ada order persalinan untuk pendaftaran ini.",
                        info: "Menampilkan _START_ - _END_ dari _TOTAL_ entri",
                        infoEmpty: "Tidak ada data",
                        infoFiltered: "(difilter dari _MAX_ total entri)",
                        loadingRecords: "Memuat...",
                        processing: "Proses...",
                        zeroRecords: "Tidak ada data yang cocok",
                        paginate: {
                            first: "Pertama",
                            last: "Terakhir",
                            next: "Selanjutnya",
                            previous: "Sebelumnya"
                        }
                    }
                });
            }

            function resetForm() {
                $('#form-order-vk')[0].reset();
                $('#order_vk_id').val('');
                $('#vk_registration_id').val(registrationId);
                $('#modal-order-vk .select2').val(null).trigger('change');
                $('#kelas_rawat').val(null).trigger('change');
                $('#kelas_rawat, #dokter_bidan_operator, #asisten_operator, #dokter_resusitator, #dokter_anestesi, #asisten_anestesi, #dokter_umum, #tipe_persalinan, #kategori')
                    .html('<option value="">Loading...</option>');
                $('#modal-order-vk .modal-title').text('Input Tindakan Persalinan (VK)');
                $('#vk-step-1').removeClass('d-none');
                $('#vk-step-2').addClass('d-none');
                $('#btn-vk-lanjut').show();
                $('#btn-vk-kembali, #btn-simpan-order-vk').hide();
                $('.form-group label').removeClass('text-danger');
                $('.is-invalid').removeClass('is-invalid');
            }

            function loadAndPopulateDropdowns() {
                $.ajax({
                    url: `/simrs/persalinan/master-data/${registrationId}`,
                    method: 'GET',
                    success: function(data) {
                        populateDropdown('#kelas_rawat', data.kelas_rawat || [], 'Pilih Kelas Rawat');
                        populateDropdown('#dokter_bidan_operator', data.doctors || [],
                            'Pilih Dokter/Bidan');
                        populateDropdown('#asisten_operator', data.doctors || [],
                            'Pilih Asisten Operator');
                        populateDropdown('#dokter_resusitator', data.doctors || [],
                            'Pilih Dokter Resusitator');
                        populateDropdown('#dokter_anestesi', data.doctors || [],
                            'Pilih Dokter Anestesi');
                        populateDropdown('#asisten_anestesi', data.doctors || [],
                            'Pilih Asisten Anestesi');
                        populateDropdown('#dokter_umum', data.doctors || [], 'Pilih Dokter Umum');
                        populateDropdown('#kategori', data.kategori || [], 'Pilih Kategori');
                        populateDropdown('#tipe_persalinan', data.tipe || [], 'Pilih Tipe Penggunaan');

                        let tindakanHtml = '';
                        if (data.tindakan && data.tindakan.length > 0) {
                            data.tindakan.forEach(item => {
                                tindakanHtml +=
                                    `<div class="tindakan-item"><input type="radio" name="tindakan_id" id="tindakan-${item.id}" value="${item.id}" required><label for="tindakan-${item.id}">${item.text}</label></div>`;
                            });
                        } else {
                            tindakanHtml = '<p class="text-muted">Tidak ada tindakan tersedia.</p>';
                        }
                        $('#tindakan-grid-container').html(tindakanHtml);
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'Gagal memuat data master: ' + (xhr.responseJSON?.message ||
                            xhr.statusText), 'error');
                    }
                });
            }

            function populateDropdown(selector, data, placeholder) {
                const $select = $(selector);
                $select.empty().append(`<option value="">${placeholder}</option>`);
                if (Array.isArray(data) && data.length > 0) {
                    data.forEach(item => {
                        $select.append(`<option value="${item.id}">${item.text}</option>`);
                    });
                }
                if ($select.hasClass('select2')) {
                    $select.select2({
                        dropdownParent: $select.closest('.modal'),
                        width: '100%'
                    });
                }
            }

            $('#btn-tambah-order-persalinan').on('click', function() {
                resetForm();
                $('#modal-order-vk').modal('show');
            });

            $('#modal-order-vk').on('shown.bs.modal', function() {
                loadAndPopulateDropdowns();
            });

            $('#btn-reload-persalinan').on('click', function() {
                $(this).html('<i class="fal fa-spin fa-spinner mr-1"></i>Loading...');
                if (dtPersalinan) {
                    dtPersalinan.ajax.reload(function() {
                        $('#btn-reload-persalinan').html(
                            '<span class="fal fa-sync mr-1"></span>Reload');
                    });
                }
            });

            $('#btn-vk-lanjut').on('click', function() {
                let isValid = true;
                $('#vk-step-1 [required]').each(function() {
                    const $field = $(this);
                    let isFieldInvalid = false;

                    if ($field.is(':radio')) {
                        if (!$(`input[name="${$field.attr('name')}"]:checked`).val()) {
                            isFieldInvalid = true;
                        }
                    } else {
                        if (!$field.val()) {
                            isFieldInvalid = true;
                        }
                    }

                    if (isFieldInvalid) {
                        isValid = false;
                        $field.closest('.form-group').find('label').addClass('text-danger');
                    } else {
                        $field.closest('.form-group').find('label').removeClass('text-danger');
                    }
                });

                if (!isValid) {
                    Swal.fire('Peringatan', 'Harap lengkapi semua field wajib (*)', 'warning');
                    return;
                }

                $('#vk-step-1').addClass('d-none');
                $('#vk-step-2').removeClass('d-none');
                $(this).hide();
                $('#btn-vk-kembali, #btn-simpan-order-vk').show();
            });

            $('#btn-vk-kembali').on('click', function() {
                $('#vk-step-2').addClass('d-none');
                $('#vk-step-1').removeClass('d-none');
                $(this).hide();
                $('#btn-simpan-order-vk').hide();
                $('#btn-vk-lanjut').show();
            });

            $('#btn-simpan-order-vk').on('click', function() {
                const $button = $(this);
                if ($('input[name="tindakan_id"]:checked').length === 0) {
                    Swal.fire('Peringatan', 'Anda harus memilih satu tindakan', 'warning');
                    return;
                }

                $button.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm"></span> Menyimpan...');
                $.ajax({
                    url: "{{ route('persalinan.store') }}",
                    type: 'POST',
                    data: new FormData($('#form-order-vk')[0]),
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#modal-order-vk').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        if (dtPersalinan) dtPersalinan.ajax.reload(null, false);
                    },
                    error: function(xhr) {
                        let errorMsg = 'Gagal menyimpan data.';
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            errorMsg = '<ul>';
                            $.each(errors, function(key, value) {
                                errorMsg += `<li>${value[0]}</li>`;
                            });
                            errorMsg += '</ul>';
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            html: errorMsg
                        });
                    },
                    complete: function() {
                        $button.prop('disabled', false).html(
                            '<i class="fas fa-save mr-1"></i>Simpan Order');
                    }
                });
            });

            $('#dt-order-persalinan').on('click', '.btn-delete-persalinan', function() {
                const orderId = $(this).data('id');
                Swal.fire({
                    title: 'Anda Yakin?',
                    text: 'Data order persalinan akan dihapus permanen!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/simrs/persalinan/destroy/${orderId}`,
                            type: 'DELETE',
                            success: function(response) {
                                Swal.fire('Dihapus!', response.message, 'success');
                                if (dtPersalinan) dtPersalinan.ajax.reload(null, false);
                            },
                            error: function(xhr) {
                                Swal.fire('Gagal!', xhr.responseJSON?.message ||
                                    'Gagal menghapus data.', 'error');
                            }
                        });
                    }
                });
            });

            $('#modal-order-vk').on('hidden.bs.modal', function() {
                resetForm();
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
            });

            initializeDataTable();
        });
    </script>
@endsection
