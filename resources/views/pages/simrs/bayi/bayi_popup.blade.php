<!-- resources/views/pages/simrs/bayi_popup.blade.php -->
@extends('inc.layout-no-side')
@section('title', 'Manajemen Data Bayi')

@push('styles')
    <!-- Tambahkan Boxicons untuk ikon yang lebih bagus -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        .page-content {
            padding: 1.5rem;
            background-color: #f3f3f3;
        }

        .swal2-container {
            z-index: 99999 !important;
        }

        .select2-dropdown {
            z-index: 1060 !important;
        }

        .btn-action {
            width: 30px;
            height: 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
    </style>
@endpush

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="panel">
            <div class="panel-hdr">
                <h2>
                    <i class="fal fa-baby-carriage mr-2"></i>
                    List <span class="fw-300"><i>Data Bayi</i></span>
                    <small class="ml-3">Ibu: {{ $order->registration->patient->name }} (RM:
                        {{ $order->registration->patient->medical_record_number }})</small>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <!-- Tombol Tambah & Tabel Data Bayi -->
                    <div class="mb-3">
                        <button class="btn btn-primary" id="btn-tambah-bayi">
                            <i class="fal fa-plus mr-1"></i> Tambah Data Bayi
                        </button>
                    </div>

                    <table id="dt-bayi-table" class="table table-bordered table-hover table-striped w-100">
                        <thead class="bg-primary-600">
                            <tr>
                                <th>No RM</th>
                                <th>Nama Bayi</th>
                                <th>Tgl Lahir</th>
                                <th>No Label</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Data akan diisi oleh DataTables --}}
                        </tbody>
                    </table>

                    <hr class="my-4">

                    <!-- Container untuk Form Bayi -->
                    <div id="bayi-form-container" style="display: none;">
                        @include('pages.simrs.bayi.form_bayi')
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal untuk Pilih Kamar -->
    @include('pages.simrs.bayi.modal_pilih_kamar')
@endsection

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // SETUP GLOBAL
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            const orderId = {{ $order->id }};
            const type = "{{ $type }}"; // persalinan or operasi

            // =====================================================================
            // BAGIAN 1: MANAJEMEN DATA BAYI (TABEL, FORM, CRUD)
            // =====================================================================
            let dtBayi = null;

            $('#select-dokter-bayi').select2({
                placeholder: "Pilih atau ketik nama dokter",
                dropdownParent: $('#bayi-form-container'),
                ajax: {
                    url: "{{ route('bayi.get_doctors') }}",
                    dataType: 'json',
                    delay: 250,
                    processResults: (data) => ({
                        results: data
                    }),
                    cache: true
                }
            });

            function loadDataBayi(orderId, type) {
                dtBayi = $('#dt-bayi-table').DataTable({
                    destroy: true,
                    processing: true,
                    ajax: {
                        url: `{{ route('bayi.data', ['order' => $order->id, 'type' => $type]) }}`,
                        dataSrc: ''
                    },
                    columns: [{
                            data: 'no_rm',
                            defaultContent: '-'
                        },
                        {
                            data: 'nama_bayi'
                        },
                        {
                            data: 'tgl_lahir',
                            render: (data) => data ? moment(data).format('DD MMM YYYY HH:mm') : '-'
                        },
                        {
                            data: 'no_label',
                            defaultContent: '-'
                        },
                        {
                            data: 'id',
                            orderable: false,
                            searchable: false,
                            className: 'text-center',
                            render: function(data, type, row) {
                                const printUrl =
                                    `{{ route('bayi.print_certificate', ['bayi' => ':id']) }}`
                                    .replace(':id', data);
                                return `<div class="btn-group">
                                        <button class="btn btn-info btn-action btn-print-bayi" data-url="${printUrl}" title="Cetak Akta"><i class='bx bxs-printer'></i></button>
                                        <button class="btn btn-warning btn-action btn-edit-bayi" data-id="${data}" title="Edit Data"><i class='bx bxs-edit'></i></button>

                                    </div>`;
                            }
                        }
                    ],
                    pageLength: 5,
                    lengthChange: false,
                    language: {
                        emptyTable: "Belum ada data bayi untuk order ini."
                    }
                });
            }

            loadDataBayi(orderId, type);

            $('#btn-tambah-bayi').on('click', function() {
                $('#form-bayi')[0].reset();
                $('#bayi_id').val('');
                $('#select-dokter-bayi').val(null).trigger('change');
                $('#bayi_kelas_kamar_input').val('');
                $('#bayi-form-container').slideDown();
                $(this).hide();
                $('html, body').animate({
                    scrollTop: $(document).height()
                }, 'slow');
            });

            $('#btn-batal-bayi').on('click', function() {
                $('#bayi-form-container').slideUp(() => $('#btn-tambah-bayi').show());
            });

            $('#form-bayi').on('submit', function(e) {
                e.preventDefault();
                const button = $('#btn-simpan-bayi');
                button.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm"></span> Menyimpan...');

                const formData = $(this).serializeArray();
                formData.push({
                    name: 'type',
                    value: type
                });
                if (type === 'persalinan') {
                    formData.push({
                        name: 'order_persalinan_id',
                        value: orderId
                    });
                } else if (type === 'operasi') {
                    formData.push({
                        name: 'order_operasi_id',
                        value: orderId
                    });
                }

                $.ajax({
                    url: "{{ route('bayi.store') }}",
                    type: 'POST',
                    data: $.param(formData),
                    success: (response) => {
                        Swal.fire('Berhasil!', response.message, 'success');
                        $('#btn-batal-bayi').click();
                        dtBayi.ajax.reload();
                    },
                    error: (xhr) => {
                        let errorMsg = '<ul>';
                        $.each(xhr.responseJSON.errors, (key, value) => {
                            errorMsg += `<li>${value[0]}</li>`;
                        });
                        errorMsg += '</ul>';
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Validasi',
                            html: errorMsg
                        });
                    },
                    complete: () => button.prop('disabled', false).html('Simpan')
                });
            });

            $('#dt-bayi-table tbody').on('click', '.btn-print-bayi', function() {
                window.open($(this).data('url'), '_blank', 'width=800,height=700');
            }).on('click', '.btn-edit-bayi', function() {
                const bayiId = $(this).data('id');
                $.get(`{{ route('bayi.show', ['bayi' => ':id']) }}`.replace(':id', bayiId), (data) => {
                    if (!data) {
                        Swal.fire('Error', 'Data bayi tidak ditemukan.', 'error');
                        return;
                    }
                    $('#form-bayi')[0].reset();
                    Object.keys(data).forEach(key => {
                        const field = $(`#form-bayi [name="${key}"]`);
                        if (key === 'tgl_lahir' && data[key]) {
                            field.val(moment(data[key]).format('YYYY-MM-DDTHH:mm'));
                        } else if (field.is(':radio')) {
                            $(`input[name="${key}"][value="${data[key]}"]`).prop('checked',
                                true);
                        } else if (key !== 'doctor_id' && key !== 'bed_id') {
                            field.val(data[key]);
                        }
                    });

                    if (data.doctor_id && data.doctor && data.doctor.employee && data.doctor
                        .employee.fullname) {
                        $('#select-dokter-bayi').append(new Option(data.doctor.employee.fullname,
                            data.doctor_id, true, true)).trigger('change');
                    } else {
                        console.log('Data dokter tidak lengkap:', data.doctor);
                    }

                    if (data.bed && data.bed.room && data.bed.room.kelas_rawat) {
                        const roomInfo =
                            `${data.bed.room.kelas_rawat.kelas} / ${data.bed.room.ruangan} - ${data.bed.nama_tt}`;
                        $('#bayi_kelas_kamar_input').val(roomInfo);
                        $('#bayi_bed_id_input').val(data.bed_id);
                        $('#bayi_kelas_rawat_id_input').val(data.bed.room.kelas_rawat_id);
                    } else {
                        console.log('Data bed tidak lengkap:', data.bed);
                    }

                    $('#bayi_id').val(data.id);
                    $('#bayi-form-container').slideDown();
                    $('#btn-tambah-bayi').hide();
                    $('html, body').animate({
                        scrollTop: $(document).height()
                    }, 'slow');
                }).fail((xhr) => {
                    Swal.fire('Error', 'Gagal mengambil data bayi: ' + xhr.responseJSON?.message,
                        'error');
                });
            }).on('click', '.btn-delete-bayi', function() {
                const bayiId = $(this).data('id');
                Swal.fire({
                    title: 'Anda Yakin?',
                    text: "Data bayi akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#d33'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ route('bayi.destroy', ['bayi' => ':id']) }}`.replace(
                                ':id', bayiId),
                            type: 'DELETE',
                            success: (res) => {
                                Swal.fire('Dihapus!', res.message, 'success');
                                dtBayi.ajax.reload();
                            },
                            error: (xhr) => Swal.fire('Gagal!', xhr.responseJSON?.message ||
                                'Gagal.', 'error')
                        });
                    }
                });
            });

            // =====================================================================
            // BAGIAN 2: MODAL PEMILIHAN KAMAR BAYI
            // =====================================================================
            let dtKamarBayi = null;

            $('#kelas_rawat_id_bayi').select2({
                placeholder: "-- Pilih Kelas Rawat --",
                dropdownParent: $('#modal-pilih-kamar-bayi'),
                ajax: {
                    url: "{{ route('bayi.get_kelas_rawat') }}",
                    dataType: 'json',
                    processResults: (data) => ({
                        results: data
                    })
                }
            });

            $('#modal-pilih-kamar-bayi').on('show.bs.modal', function() {
                if (dtKamarBayi) {
                    dtKamarBayi.ajax.reload();
                } else {
                    dtKamarBayi = $('#dt-kamar-bayi-table').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: '{{ route('bayi.get_beds') }}',
                            data: (d) => {
                                d.kelas_rawat_id = $('#kelas_rawat_id_bayi').val();
                            }
                        },
                        columns: [{
                                data: 'ruangan',
                                name: 'room.ruangan'
                            },
                            {
                                data: 'kelas',
                                name: 'room.kelas_rawat.kelas'
                            },
                            {
                                data: 'nama_tt',
                                name: 'beds.nama_tt'
                            },
                            {
                                data: 'pasien',
                                name: 'pasien'
                            },
                            {
                                data: 'fungsi',
                                name: 'fungsi',
                                orderable: false,
                                searchable: false
                            }
                        ],
                        pageLength: 5,
                        lengthChange: false,
                    });
                }
            });

            $('#kelas_rawat_id_bayi').on('change', function() {
                if (dtKamarBayi) dtKamarBayi.ajax.reload();
            });

            $('#dt-kamar-bayi-table').on('click', '.pilih-bed-bayi', function() {
                $('#bayi_kelas_kamar_input').val($(this).data('room-info'));
                $('#bayi_bed_id_input').val($(this).data('bed-id'));
                $('#bayi_kelas_rawat_id_input').val($(this).data('kelas-id'));
                $('#modal-pilih-kamar-bayi').modal('hide');
            });

            $('#modal-pilih-kamar-bayi').on('shown.bs.modal', function() {
                $(this).find('select').select2('open');
                $(this).find('select').select2('close');
            });
        });
    </script>
@endsection
