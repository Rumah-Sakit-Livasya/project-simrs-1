@extends('inc.layout')
@section('title', 'Nilai Normal Parameter Laboratorium')

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        {{-- Panel Form Pencarian --}}
        <div class="panel" id="panel-filter">
            <div class="panel-hdr">
                <h2>
                    Filter <span class="fw-300"><i>Pencarian</i></span>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <form action="" method="get">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label" for="nama_parameter">Nama Parameter</label>
                                    <input type="text" name="nama_parameter" id="nama_parameter" class="form-control"
                                        placeholder="Cari nama parameter...">
                                </div>
                            </div>
                            <div class="col-md-4">
                                {{-- Filter tambahan bisa diletakkan di sini --}}
                            </div>
                            <div class="col-md-4">
                                {{-- Filter tambahan bisa diletakkan di sini --}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search mr-1"></i> Cari
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Panel Daftar Data --}}
        <div class="panel" id="panel-data">
            <div class="panel-hdr">
                <h2>
                    Daftar <span class="fw-300"><i>Nilai Normal Parameter</i></span>
                </h2>
                <div class="panel-toolbar">
                    {{-- Tombol Tambah dipindahkan ke sini --}}
                    <button class="btn btn-success btn-sm" id="btn-tambah-nilai-parameter">
                        <i class="fas fa-plus mr-1"></i> Tambah Nilai Normal
                    </button>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="table-responsive">
                        <table id="dt-nilai-parameter" class="table table-bordered table-hover table-striped w-100">
                            <thead class="bg-primary-600">
                                <tr>
                                    <th class="text-center" style="width: 30px;">No</th>
                                    <th>Parameter</th>
                                    <th>Referensi / Nilai Normal</th>
                                    <th>Rentang Umur</th>
                                    <th>Jenis Kelamin</th>
                                    <th class="text-center" style="width: 80px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($nilai_parameter as $row)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $row->parameter_laboratorium->parameter ?? 'N/A' }}</td>
                                        <td>
                                            @if (isset($row->min) && isset($row->max) && ($row->min > 0 || $row->max > 0))
                                                <span class="badge badge-info fs-md">{{ $row->min }} -
                                                    {{ $row->max }}</span>
                                            @else
                                                {{ $row->nilai_normal }}
                                            @endif
                                        </td>
                                        <td>{{ format_umur_range($row->dari_umur, $row->sampai_umur) }}</td>
                                        <td>{{ $row->jenis_kelamin }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-xs btn-icon btn-warning btn-edit"
                                                data-id="{{ $row->id }}" title="Edit Data">
                                                <i class="fas fa-pencil-alt"></i>
                                            </button>
                                            <button class="btn btn-xs btn-icon btn-danger btn-delete"
                                                data-id="{{ $row->id }}" title="Hapus Data">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- Include Modal --}}
    @include('pages.simrs.master-data.penunjang-medis.laboratorium.partials.tambah-nilai-parameter-lab')
    @include('pages.simrs.master-data.penunjang-medis.laboratorium.partials.edit-nilai-parameter-lab')
@endsection

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            // --- Inisialisasi DataTable ---
            $('#dt-nilai-parameter').DataTable({
                responsive: true,
                lengthChange: false,
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                        extend: 'pdfHtml5',
                        text: 'PDF',
                        titleAttr: 'Generate PDF',
                        className: 'btn-outline-danger btn-sm mr-1'
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        titleAttr: 'Generate Excel',
                        className: 'btn-outline-success btn-sm mr-1'
                    },
                    {
                        extend: 'csvHtml5',
                        text: 'CSV',
                        titleAttr: 'Generate CSV',
                        className: 'btn-outline-primary btn-sm mr-1'
                    },
                    {
                        extend: 'copyHtml5',
                        text: 'Copy',
                        titleAttr: 'Copy to clipboard',
                        className: 'btn-outline-primary btn-sm mr-1'
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        titleAttr: 'Print Table',
                        className: 'btn-outline-primary btn-sm'
                    }
                ]
            });

            // --- Event Handler untuk Modal Tambah ---
            $('#btn-tambah-nilai-parameter').on('click', function() {
                $('#modal-tambah-nilai-parameter-laboratorium').modal('show');
            });

            $('#modal-tambah-nilai-parameter-laboratorium').on('shown.bs.modal', function() {
                $(this).find('.select2').select2({
                    dropdownParent: $(this),
                    placeholder: 'Pilih Data'
                });
            });

            // --- Event Delegation untuk Tombol Edit di Tabel ---
            $('#dt-nilai-parameter tbody').on('click', '.btn-edit', function() {
                var dataId = $(this).data('id');
                var url =
                    `/api/simrs/master-data/penunjang-medis/laboratorium/nilai-normal-parameter/${dataId}`;

                $.get(url, function(data) {
                    var $modal = $('#modal-edit-nilai-parameter-laboratorium');
                    var $form = $modal.find('#update-form');
                    $form[0].reset();

                    // Pastikan id_edit ada dan terisi
                    $form.find('#id_edit').val(data.id);

                    // Tanggal & User
                    $form.find('input[name="tanggal"]').val(data.tanggal || '');
                    $form.find('input[name="user_input"]').val(data.user_input || '');

                    // Parameter Laboratorium Select2
                    $modal.find('#parameter_laboratorium_id_edit').select2({
                        dropdownParent: $modal,
                        placeholder: 'Pilih Data'
                    }).val(data.parameter_laboratorium_id).trigger('change');

                    // Jenis Kelamin
                    if (data.jenis_kelamin) {
                        $form.find(`input[name="jenis_kelamin"][value="${data.jenis_kelamin}"]`)
                            .prop('checked', true);
                    }

                    // Dari Umur & Sampai Umur
                    // Mengambil dari data.dari_umur dan data.sampai_umur yang bertipe 'tahun-bulan-hari' (string)
                    // Split menjadi [tahun, bulan, hari] (jika null/undefined, default 0)
                    var dariArr = Array.isArray(data.dari_umur) ?
                        data.dari_umur.map(Number) :
                        (typeof data.dari_umur === 'string' && data.dari_umur.match(
                                /^\d+-\d+-\d+$/) ?
                            data.dari_umur.split('-').map(Number) : [0, 0, 0]);
                    var sampaiArr = Array.isArray(data.sampai_umur) ?
                        data.sampai_umur.map(Number) :
                        (typeof data.sampai_umur === 'string' && data.sampai_umur.match(
                                /^\d+-\d+-\d+$/) ?
                            data.sampai_umur.split('-').map(Number) : [0, 0, 0]);

                    $form.find('#tahun_1_edit').val(typeof data.tahun_1 !== 'undefined' && data
                        .tahun_1 !== null ? data.tahun_1 : dariArr[0]);
                    $form.find('#bulan_1_edit').val(typeof data.bulan_1 !== 'undefined' && data
                        .bulan_1 !== null ? data.bulan_1 : dariArr[1]);
                    $form.find('#hari_1_edit').val(typeof data.hari_1 !== 'undefined' && data
                        .hari_1 !== null ? data.hari_1 : dariArr[2]);

                    $form.find('#tahun_2_edit').val(typeof data.tahun_2 !== 'undefined' && data
                        .tahun_2 !== null ? data.tahun_2 : sampaiArr[0]);
                    $form.find('#bulan_2_edit').val(typeof data.bulan_2 !== 'undefined' && data
                        .bulan_2 !== null ? data.bulan_2 : sampaiArr[1]);
                    $form.find('#hari_2_edit').val(typeof data.hari_2 !== 'undefined' && data
                        .hari_2 !== null ? data.hari_2 : sampaiArr[2]);

                    // Angka Min, Max, Min Kritis, Max Kritis (bisa null)
                    $form.find('#min_edit').val(data.min !== null ? data.min : '');
                    $form.find('#max_edit').val(data.max !== null ? data.max : '');
                    $form.find('#min_kritis_edit').val(data.min_kritis !== null ? data.min_kritis :
                        '');
                    $form.find('#max_kritis_edit').val(data.max_kritis !== null ? data.max_kritis :
                        '');

                    // Hasil
                    $form.find('#hasil_edit').val(data.hasil !== null ? data.hasil : '');

                    // Keterangan
                    $form.find('#keterangan_edit').val(data.keterangan !== null ? data.keterangan :
                        '');

                    // Nilai Normal: radio (bisa null, pastikan clear jika null)
                    if (data.nilai_normal !== null && typeof data.nilai_normal !== 'undefined' &&
                        data.nilai_normal !== '') {
                        $form.find(`input[name="nilai_normal"][value="${data.nilai_normal}"]`).prop(
                            'checked', true);
                    } else {
                        $form.find(`input[name="nilai_normal"]`).prop('checked', false);
                    }

                    $modal.modal('show');
                }).fail(function(xhr) {
                    showErrorAlert('Gagal mengambil data: ' + xhr.responseText);
                });
            });

            // Helper konversi umur dari total hari ke Tahun/Bulan/Hari
            function convertDaysToYMD(totalDays) {
                if (totalDays === null || isNaN(totalDays)) {
                    return {
                        years: 0,
                        months: 0,
                        days: 0
                    };
                }
                let years = Math.floor(totalDays / 365);
                let remainingDays = totalDays % 365;
                let months = Math.floor(remainingDays / 30);
                let days = remainingDays % 30;
                return {
                    years,
                    months,
                    days
                };
            }

            // --- Event Delegation untuk Tombol Hapus di Tabel ---
            $('#dt-nilai-parameter tbody').on('click', '.btn-delete', function() {
                var dataId = $(this).data('id');

                showDeleteConfirmation(function() {
                    $.ajax({
                        url: `/api/simrs/master-data/penunjang-medis/laboratorium/nilai-normal-parameter/${dataId}`,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            showSuccessAlert(response.message);
                            setTimeout(() => window.location.reload(), 1500);
                        },
                        error: function(xhr) {
                            showErrorAlert('Gagal menghapus data: ' + xhr.responseJSON
                                ?.message ?? 'Unknown error');
                        }
                    });
                });
            });

            // --- Handler untuk Submit Form Update ---
            $('#update-form').on('submit', function(e) {
                e.preventDefault();
                var $form = $(this);
                var formData = $form.serialize();
                var dataId = $form.find('#id_edit').val();
                var url =
                    `/api/simrs/master-data/penunjang-medis/laboratorium/nilai-normal-parameter/${dataId}`;

                $.ajax({
                    url: url,
                    type: 'PATCH',
                    data: formData,
                    success: function(response) {
                        $('#modal-edit-nilai-parameter-laboratorium').modal('hide');
                        showSuccessAlert(response.message);
                        setTimeout(() => window.location.reload(), 1500);
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = Object.values(xhr.responseJSON.errors).join('\n');
                            showErrorAlert('Terjadi kesalahan validasi:\n' + errors);
                        } else {
                            showErrorAlert('Terjadi kesalahan: ' + (xhr.responseJSON?.message ??
                                'Unknown error'));
                        }
                    }
                });
            });

            // --- Handler untuk Submit Form Tambah ---
            $('#store-form').on('submit', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();

                $.ajax({
                    url: '/api/simrs/master-data/penunjang-medis/laboratorium/nilai-normal-parameter',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#modal-tambah-nilai-parameter-laboratorium').modal('hide');
                        $('#store-form')[0].reset();
                        $('.select2').val(null).trigger('change');
                        showSuccessAlert(response.message);
                        setTimeout(() => window.location.reload(), 1500);
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = Object.values(xhr.responseJSON.errors).join('\n');
                            showErrorAlert('Terjadi kesalahan validasi:\n' + errors);
                        } else {
                            showErrorAlert('Terjadi kesalahan: ' + (xhr.responseJSON?.message ??
                                'Unknown error'));
                        }
                    }
                });
            });

        });
    </script>
@endsection
