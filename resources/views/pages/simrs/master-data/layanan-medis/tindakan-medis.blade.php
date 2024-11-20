@extends('inc.layout')
@section('title', 'Tindakan Medis')
@section('extended-css')
    <style>
        hr {
            border: 1px dashed #fd3995 !important;
        }

        div.table-responsive>div.dataTables_wrapper>div.row>div[class^="col-"]:last-child {
            padding: 0px;
        }

        .dataTables_scrollHeadInner,
        .dataTables_scrollFootInner {
            width: 100% !important;
        }

        #filter-wrapper .form-group {
            display: flex;
            align-items: center;
        }

        #filter-wrapper .form-label {
            margin-bottom: 0;
            width: 100px;
            /* Atur lebar label agar semua label sejajar */
        }

        #filter-wrapper .form-control {
            flex: 1;
        }

        @media (max-width: 767.98px) {
            .custom-margin {
                margin-top: 15px;
            }

            #filter-wrapper .form-group {
                flex-direction: column;
                align-items: flex-start !important;
            }

            #filter-wrapper .form-label {
                width: auto;
                /* Biarkan lebar label mengikuti konten */
                margin-bottom: 0.5rem;
            }

            #filter-wrapper .form-control {
                width: 100%;
            }
        }

        /* Mengatur border untuk setiap sel */
        td {
            border: none;
            /* Menghilangkan semua border */
            border-bottom: 1px solid #ccc;
            /* Menambahkan border bawah */
            padding: 8px;
            /* Menambahkan padding untuk estetika */
        }

        /* Mengatur border untuk input */
        input {
            border: none;
            /* Menghilangkan semua border */
            border-bottom: 1px solid #ccc;
            /* Menambahkan border bawah */
            outline: none;
            /* Menghilangkan outline saat input aktif */
            text-align: right;
            /* Mengatur teks ke kanan */
            width: 100%;
            /* Mengatur lebar input agar sesuai dengan sel */
            padding: 4px 0;
            /* Menambahkan padding vertikal */
        }

        /* Jika Anda ingin mengatur border bawah untuk baris terakhir */
        tr:last-child td {
            border-bottom: none;
            /* Menghilangkan border bawah pada baris terakhir */
        }
    </style>
@endsection
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Form Pencarian</span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content" id="filter-wrapper">

                            <form action="/daftar-rekam-medis" method="get">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group d-flex align-items-center">
                                            <label for="departement" class="form-label">Departement</label>
                                            <input type="text" name="departement_id" id="departement"
                                                class="form-control rounded-0 border-top-0 border-left-0 border-right-0 p-0">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group d-flex align-items-center">
                                            <label for="nama_tindakan_1" class="form-label">Nama</label>
                                            <input type="text" name="nama_tindakan" id="nama_tindakan_1"
                                                class="form-control rounded-0 border-top-0 border-left-0 border-right-0 p-0">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group d-flex align-items-center">
                                            <label for="nama_tindakan_2" class="form-label">RL (1.3 dan 3.1)</label>
                                            <input type="text" name="nama_tindakan" id="nama_tindakan_2"
                                                class="form-control rounded-0 border-top-0 border-left-0 border-right-0 p-0">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group d-flex align-items-center">
                                            <label for="nama_tindakan_3" class="form-label">RL (3.4)</label>
                                            <input type="text" name="nama_tindakan" id="nama_tindakan_3"
                                                class="form-control rounded-0 border-top-0 border-left-0 border-right-0 p-0">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-sm float-right mt-2 btn-primary">
                                            <i class="fas fa-search mr-1"></i> Cari
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Tindakan Medis Rawat Jalan
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- datatable start -->
                            <div class="table-responsive">
                                <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                    <i id="loading-spinner" class="fas fa-spinner fa-spin"></i>
                                    <thead class="bg-primary-600">
                                        <tr>
                                            <th>Group Tindakan Medis</th>
                                            <th>Kode</th>
                                            <th>Nama Tindakan</th>
                                            <th>Nama Billing</th>
                                            <th>Konsul</th>
                                            <th>RL (1.3, 3.1)</th>
                                            <th>RL (3.4)</th>
                                            <th>Fungsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tindakan_medis as $row)
                                            <tr>
                                                <td>{{ $row->grup_tindakan_medis_id }}</td>
                                                <td>{{ $row->kode }}</td>
                                                <td>{{ $row->nama_tindakan }}</td>
                                                <td>{{ $row->nama_billing }}</td>
                                                <td>{{ $row->is_konsul }}</td>
                                                <td>{{ $row->mapping_rl_13 }}</td>
                                                <td>{{ $row->mapping_rl_34 }}</td>
                                                <td style="white-space: nowrap">
                                                    <button class="btn btn-sm btn-success px-2 py-1 btn-edit"
                                                        data-id="{{ $row->id }}">
                                                        <i class="fas fa-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-primary px-2 py-1 btn-tarif"
                                                        data-id="{{ $row->id }}" data-nama="{{ $row->nama_tindakan }}">
                                                        <i class="fas fa-money-bill"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger px-2 py-1 btn-delete"
                                                        data-id="{{ $row->id }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="8" class="text-center">
                                                <button type="button"
                                                    class="btn btn-outline-primary waves-effect waves-themed"
                                                    id="btn-tambah-tindakan" data-toggle="modal"
                                                    data-target="#modal-tambah-tindakan" data-action="tambah">
                                                    <span class="fal fa-plus-circle"></span>
                                                    Tambah Tindakan
                                                </button>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <!-- datatable end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @include('pages.simrs.master-data.layanan-medis.partials.edit-tindakan')
    @include('pages.simrs.master-data.layanan-medis.partials.edit-tarif')
    @include('pages.simrs.master-data.layanan-medis.partials.tambah-tindakan')
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            let lastClickedTindakanId = null; // Variabel untuk menyimpan ID tindakan terakhir yang diklik

            function resetDropdown() {
                $('#group-penjamin').val('').trigger('change');
            }

            // Event handler untuk tombol ubah tarif
            $('.btn-tarif').on('click', function() {
                // Ambil nama tindakan dari atribut tombol

                let currentTindakanId = $(this).data('id'); // Ambil ID tindakan dari tombol yang diklik

                // Reset dropdown hanya jika tindakan yang diklik berbeda dari sebelumnya
                if (lastClickedTindakanId !== currentTindakanId) {
                    resetDropdown();
                }

                lastClickedTindakanId = currentTindakanId; // Update ID tindakan terakhir
            });


            // Set CSRF token untuk semua permintaan AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#group-penjamin').select2({
                dropdownParent: $("#modal-edit-tarif"),
            });

            let tindakanId = null;
            $('#loading-spinner').show();

            $('#btn-tambah-tindakan').click(function() {
                $('#modal-tambah-tindakan').modal('show');
            });

            $('#modal-tambah-tindakan .select2').select2({
                dropdownParent: $('#modal-tambah-tindakan')
            });

            $('.btn-tarif').off('click').on('click', function() {
                // Tampilkan modal dan reset tabel
                $('#modal-edit-tarif').modal('show');
                $('#tarif-inputs').html(`
        <tr>
            <td colspan="6" class="text-center">Silakan pilih grup penjamin terlebih dahulu.</td>
            </tr>
            `);

                // Ambil ID tindakan dari atribut tombol
                let tindakanId = $(this).attr('data-id');
                let tindakanNama = $(this).data('nama');
                console.log(tindakanNama);

                // Set nama tindakan di modal
                $('#nama-tindakan').text(tindakanNama);


                // Reset dropdown grup penjamin ke nilai default
                $('#group-penjamin').val('').trigger('change');

                // Cek jika request sudah pernah dilakukan untuk tindakanId yang sama
                if ($(this).data('loaded')) {
                    console.log('Data sudah di-load, tidak melakukan request ulang.');
                    return;
                }
                $(this).data('loaded', true); // Tandai data sudah dimuat

                // Panggil API untuk mengambil tarif default
                $.ajax({
                    url: `/api/simrs/master-data/layanan-medis/tindakan-medis/tarif/${tindakanId}`,
                    type: 'GET',
                    success: function(response) {
                        console.log('Tarif default loaded:', response);

                        // Tambahkan event listener untuk perubahan dropdown grup penjamin
                        $('#group-penjamin').off('change').on('change', function() {
                            const selectedGroup = $(this).val();

                            if (selectedGroup) {
                                updateTarifByGroup(selectedGroup, tindakanId);
                            } else {
                                // Reset tabel jika dropdown kembali kosong
                                $('#tarif-inputs').html(`
                        <tr>
                            <td colspan="6" class="text-center">Silakan pilih grup penjamin terlebih dahulu.</td>
                        </tr>
                    `);
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        showErrorAlert('Terjadi kesalahan saat memuat tarif default: ' + error);
                    }
                });
            });

            // Fungsi untuk memperbarui tarif berdasarkan grup penjamin
            function updateTarifByGroup(groupId, tindakanId) {
                $.ajax({
                    url: `/api/simrs/master-data/layanan-medis/tindakan-medis/tarif/${tindakanId}/${groupId}`,
                    type: 'GET',
                    success: function(response) {
                        let inputFields = response.map((tarif, index) => `
                <tr>
                    <td style="white-space: nowrap;">
                        ${tarif.kelas || tarif.kelas_rawat.kelas}
                        <input type="hidden" name="kelas_rawat_id_[${index}]" value="${tarif.kelas_rawat_id}" />
                        <input type="hidden" name="group_penjamin_id_[${index}]" value="${groupId}" />
                        <input type="hidden" name="tindakan_medis_id_[${index}]" value="${tarif.tindakan_medis_id}" />
                    </td>
                    <td><input style="text-align:right;" type="text" name="share_dr[${index}]" value="${tarif.share_dr || 0}" onkeyup="calc(${index});" /></td>
                    <td><input style="text-align:right;" type="text" name="share_rs[${index}]" value="${tarif.share_rs || 0}" onkeyup="calc(${index});" /></td>
                    <td><input style="text-align:right;" type="text" name="prasarana[${index}]" value="${tarif.prasarana || 0}" onkeyup="calc(${index});" /></td>
                    <td><input style="text-align:right;" type="text" name="bhp[${index}]" value="${tarif.bhp || 0}" onkeyup="calc(${index});" /></td>
                    <td><input style="text-align:right;" type="text" name="total[${index}]" value="${tarif.total || 0}" readonly="readonly" /></td>
                </tr>`).join('');
                        $('#tarif-inputs').html(inputFields);
                    },
                    error: function(xhr) {
                        console.error('Error fetching tarif:', xhr);
                    }
                });
            }

            // Event listener untuk tombol save
            $('#bSave').off('click').on('click', function() {
                let dataToSave = [];

                $('#tarif-inputs tr').each(function() {
                    let kelasRawatId = $(this).find('input[name^="kelas_rawat_id_"]').val();
                    let groupPenjaminId = $(this).find('input[name^="group_penjamin_id_"]').val();
                    let tindakanMedisId = $(this).find('input[name^="tindakan_medis_id_"]').val();
                    let shareDr = $(this).find('input[name^="share_dr"]').val();
                    let shareRs = $(this).find('input[name^="share_rs"]').val();
                    let prasarana = $(this).find('input[name^="prasarana"]').val();
                    let bhp = $(this).find('input[name^="bhp"]').val();
                    let total = $(this).find('input[name^="total"]').val();

                    if (kelasRawatId) {
                        dataToSave.push({
                            kelas_rawat_id: kelasRawatId,
                            group_penjamin_id: groupPenjaminId,
                            tindakan_medis_id: tindakanMedisId,
                            share_dr: shareDr || 0,
                            share_rs: shareRs || 0,
                            prasarana: prasarana || 0,
                            bhp: bhp || 0,
                            total: total || 0,
                        });
                    }
                });

                console.log('Data to save:', dataToSave);

                $(this).prop('disabled', true);

                $.ajax({
                    url: `/api/simrs/master-data/layanan-medis/tindakan-medis/update/${tindakanId}/tarif`,
                    type: 'PATCH',
                    contentType: 'application/json',
                    data: JSON.stringify(dataToSave),
                    success: function(response) {
                        showSuccessAlert(response.message);
                        setTimeout(() => window.location.reload(), 1000);
                        $('#modal-edit-tarif').modal('hide');
                    },
                    error: function(xhr, status, error) {
                        showErrorAlert('Terjadi kesalahan: ' + error);
                    },
                    complete: function() {
                        $('#bSave').prop('disabled', false);
                    }
                });
            });

            $('.btn-edit').click(function() {
                $('#modal-edit-tindakan').modal('show');
                tindakanId = $(this).attr('data-id');
                $.ajax({
                    url: '/api/simrs/master-data/layanan-medis/tindakan-medis/' + tindakanId,
                    type: 'GET',
                    success: function(response) {
                        // Isi form dengan data yang diterima
                        $('#modal-edit-tindakan #grup_tindakan_medis_id').val(response
                                .grup_tindakan_medis_id)
                            .select2({
                                dropdownParent: $('#modal-edit-tindakan')
                            });
                        $('#modal-edit-tindakan input[name="kode"]').val(response
                            .kode);
                        $('#modal-edit-tindakan input[name="nama_tindakan"]').val(response
                            .nama_tindakan);
                        $('#modal-edit-tindakan input[name="nama_billing"]').val(response
                            .nama_billing);
                        $('#modal-edit-tindakan input[name="is_konsul"][value="' + response
                            .is_konsul + '"]').prop(
                            'checked', true);
                        $('#modal-edit-tindakan input[name="auto_charge"][value="' +
                                response
                                .auto_charge + '"]')
                            .prop(
                                'checked', true);
                        $('#modal-edit-tindakan input[name="is_vaksin"][value="' + response
                            .is_vaksin + '"]').prop(
                            'checked', true);
                        $('#modal-edit-tindakan #mapping_rl_13').val(response.mapping_rl_13)
                            .select2({
                                dropdownParent: $('#modal-edit-tindakan')
                            });
                        $('#modal-edit-tindakan #mapping_rl_34').val(response.mapping_rl_34)
                            .select2({
                                dropdownParent: $('#modal-edit-tindakan')
                            });
                    },
                    error: function(xhr, status, error) {
                        showErrorAlert('Terjadi kesalahan: ' + error);
                    }
                });

            });

            $('.btn-delete').click(function() {
                var tindakanId = $(this).attr('data-id');

                // Menggunakan confirm() untuk mendapatkan konfirmasi dari pengguna
                var userConfirmed = confirm('Anda Yakin ingin menghapus ini?');

                if (userConfirmed) {
                    // Jika pengguna mengklik "Ya" (OK), maka lakukan AJAX request
                    $.ajax({
                        url: '/api/simrs/master-data/layanan-medis/tindakan-medis/' +
                            tindakanId +
                            '/delete',
                        type: 'DELETE',
                        success: function(response) {
                            showSuccessAlert(response.message);

                            setTimeout(() => {
                                console.log('Reloading the page now.');
                                window.location.reload();
                            }, 1000);
                        },
                        error: function(xhr, status, error) {
                            showErrorAlert('Terjadi kesalahan: ' + error);
                        }
                    });
                } else {
                    console.log('Penghapusan dibatalkan oleh pengguna.');
                }
            });

            $('#update-form').on('submit', function(e) {
                e.preventDefault(); // Mencegah form submit secara default

                var formData = $(this).serialize(); // Mengambil semua data dari form

                $.ajax({
                    url: '/api/simrs/master-data/layanan-medis/tindakan-medis/' + tindakanId +
                        '/update',
                    type: 'PATCH',
                    data: formData,
                    beforeSend: function() {
                        $('#update-form').find('.ikon-edit').hide();
                        $('#update-form').find('.spinner-text').removeClass(
                            'd-none');
                    },
                    success: function(response) {
                        $('#modal-edit-tindakan').modal('hide');
                        showSuccessAlert(response.message);

                        setTimeout(() => {
                            console.log('Reloading the page now.');
                            window.location.reload();
                        }, 1000);
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            var errorMessages = '';

                            $.each(errors, function(key, value) {
                                errorMessages += value +
                                    '\n';
                            });

                            $('#modal-edit-tindakan').modal('hide');
                            showErrorAlert('Terjadi kesalahan:\n' +
                                errorMessages);
                        } else {
                            $('#modal-edit-tindakan').modal('hide');
                            showErrorAlert('Terjadi kesalahan: ' + error);
                            console.log(error);
                        }
                    }
                });
            });

            $('#store-form').on('submit', function(e) {
                e.preventDefault(); // Mencegah form submit secara default

                var formData = $(this).serialize(); // Mengambil semua data dari form

                $.ajax({
                    url: '/api/simrs/master-data/layanan-medis/tindakan-medis',
                    type: 'POST',
                    data: formData,
                    beforeSend: function() {
                        $('#store-form').find('.ikon-tambah').hide();
                        $('#store-form').find('.spinner-text').removeClass(
                            'd-none');
                    },
                    success: function(response) {
                        $('#modal-tambah-tindakan').modal('hide');
                        showSuccessAlert(response.message);

                        setTimeout(() => {
                            console.log('Reloading the page now.');
                            window.location.reload();
                        }, 1000);
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            var errorMessages = '';

                            $.each(errors, function(key, value) {
                                errorMessages += value +
                                    '\n';
                            });

                            $('#modal-tambah-tindakan').modal('hide');
                            showErrorAlert('Terjadi kesalahan:\n' +
                                errorMessages);
                        } else {
                            $('#modal-tambah-tindakan').modal('hide');
                            showErrorAlert('Terjadi kesalahan: ' + error);
                            console.log(error);
                        }
                    }
                });
            });

            // initialize datatable
            $('#dt-basic-example').DataTable({
                "drawCallback": function(settings) {
                    // Menyembunyikan preloader setelah data berhasil dimuat
                    $('#loading-spinner').hide();
                },
                responsive: false, // Responsif diaktifkan
                scrollX: true, // Tambahkan scroll horizontal
                lengthChange: false,
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end buttons-container'B>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                        extend: 'pdfHtml5',
                        text: 'PDF',
                        titleAttr: 'Generate PDF',
                        className: 'btn-outline-danger btn-sm mr-1 custom-margin'
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        titleAttr: 'Generate Excel',
                        className: 'btn-outline-success btn-sm mr-1 custom-margin'
                    },
                    {
                        extend: 'csvHtml5',
                        text: 'CSV',
                        titleAttr: 'Generate CSV',
                        className: 'btn-outline-primary btn-sm mr-1 custom-margin'
                    },
                    {
                        extend: 'copyHtml5',
                        text: 'Copy',
                        titleAttr: 'Copy to clipboard',
                        className: 'btn-outline-primary btn-sm mr-1 custom-margin'
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        titleAttr: 'Print Table',
                        className: 'btn-outline-primary btn-sm custom-margin'
                    }
                ]
            });
        });

        function calc(index) {
            // Ambil nilai dari input yang relevan
            let shareDr = parseFloat($(`input[name="share_dr[${index}]"]`).val()) || 0;
            let shareRs = parseFloat($(`input[name="share_rs[${index}]"]`).val()) || 0;
            let prasarana = parseFloat($(`input[name="prasarana[${index}]"]`).val()) || 0;
            let bhp = parseFloat($(`input[name="bhp[${index}]"]`).val()) || 0;

            // Hitung total
            let total = shareDr + shareRs + prasarana + bhp;

            // Set nilai total ke input total
            $(`input[name="total[${index}]"]`).val(total.toFixed(2)); // Format total dengan 2 desimal
        }
    </script>
@endsection
