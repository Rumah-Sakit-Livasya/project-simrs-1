@extends('inc.layout')
@section('title', 'Live Attendace')
@section('extended-css')
    <style>
        /* Styling for table with id "aspek-penilaian" */
        #aspek-penilaian {
            border-collapse: collapse;
            width: 100%;
        }

        /* Styling for table heading row */
        #aspek-penilaian #heading th {
            background-color: #4679cc;
            color: white;
            padding: 10px;
            text-align: left;
            margin-bottom: 5px !important;
        }

        /* Styling for "Tambah Aspek Penilaian" button row */
        #aspek-penilaian td:first-child {
            padding: 5px 5px 5px 0px;
        }

        #aspek-penilaian td {
            padding: 5px 5px 5px 0px;
            /* Padding */
        }

        #aspek-penilaian #btn-tambah-wrapper td {
            padding-top: 10px;
            /* Padding */
        }

        /* Styling for "Tambah Aspek Penilaian" button */
        .btn-tambah-aspek,
        .btn-tambah-indikator {
            background-color: #4679cc;
            /* Warna latar belakang biru */
            color: white;
            /* Warna teks putih */
            padding: 8px 12px;
            /* Padding */
            text-decoration: none;
            /* Hapus dekorasi tautan */
            border-radius: 4px;
            /* Border radius */
        }

        /* Hover effect for "Tambah Aspek Penilaian" button */
        .btn-tambah-aspek:hover,
        .btn-tambah-indikator:hover {
            background-color: #335999;
            /* Warna latar belakang biru yang lebih gelap saat dihover */
        }

        .btn-hapus-aspek:hover,
        .btn-hapus-indikator:hover {
            cursor: pointer;
            color: #335999 !important;
        }
    </style>
@endsection
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Edit Group Form Penilaian
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="" method="PUT" id="group_penilaian_form_update">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h5 class="font-weight-bold text-primary" style="font-size: 0.875rem">Group
                                            Penilaian
                                        </h5>
                                        <hr style="border-color: #fd3995">
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group mb-3">
                                            <label for="nama_group">Nama Group</label>
                                            <div class="input-group">
                                                <input type="text" name="nama_group"
                                                    class="form-control @error('nama_group') is-invalid @enderror"
                                                    placeholder="Pelayanan/Administrasi Umum" id="nama_group"
                                                    value="{{ $group_penilaian->nama_group }}">
                                            </div>
                                            @error('nama_group')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group mb-3">
                                            <label for="penilai">Penilai</label>
                                            <div class="input-group">
                                                <select
                                                    class="select2 form-control mb-3 w-100  @error('periode') is-invalid @enderror"
                                                    id="penilai" name="penilai">
                                                    @foreach ($employee as $row)
                                                        <option value="{{ $row->id }}"
                                                            {{ $group_penilaian->penilai == $row->id ? 'selected' : '' }}>
                                                            {{ $row->fullname }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('penilai')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group mb-3">
                                            <label for="pejabat_penilai">Pejabat Penilai</label>
                                            <div class="input-group">
                                                <select
                                                    class="select2 form-control mb-3 w-100  @error('periode') is-invalid @enderror"
                                                    id="pejabat_penilai" name="pejabat_penilai">
                                                    @foreach ($employee as $row)
                                                        <option value="{{ $row->id }}"
                                                            {{ $group_penilaian->pejabat_penilai == $row->id ? 'selected' : '' }}>
                                                            {{ $row->fullname }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('pejabat_penilai')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group mb-3">
                                            <label for="rumus_penilaian">Rumus Penilaian</label>
                                            <div class="input-group">
                                                <select
                                                    class="select2 form-control mb-3 w-100  @error('periode') is-invalid @enderror"
                                                    id="rumus_penilaian" name="rumus_penilaian">
                                                    <option value="rata-rata"
                                                        {{ $group_penilaian->rumus_penilaian == 'rata-rata' ? 'selected' : '' }}>
                                                        Rata-rata</option>
                                                    <option value="kostum"
                                                        {{ $group_penilaian->rumus_penilaian == 'kostum' ? 'selected' : '' }}>
                                                        Kostum</option>
                                                </select>
                                            </div>
                                            @error('rumus_penilaian')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mt-2">
                                        <h5 class="font-weight-bold text-primary" style="font-size: 0.875rem">Aspek
                                            Penilaian
                                        </h5>
                                        <hr style="border-color: #fd3995">
                                    </div>
                                    <div class="col-lg-12">
                                        <table id="aspek-penilaian">
                                            <tr id="heading">
                                                <th>Aspek Penilaian</th>
                                                <th>Indikator Penilaian</th>
                                            </tr>
                                            @php $number = 0; @endphp
                                            @foreach ($group_penilaian->aspek_penilaians as $index => $aspek)
                                                @php $index = 1; @endphp
                                                @foreach ($aspek->indikator_penilaians as $indikator)
                                                    <tr>
                                                        @if ($index == 1)
                                                            <td style="width:50% !important;">
                                                                <div
                                                                    class="aspek-wrapper p-0 m-0 d-flex align-items-center">
                                                                    <input type="text" name="aspek_penilaian[]"
                                                                        class="form-control @error('aspek_penilaian[]') is-invalid @enderror"
                                                                        placeholder="Aspek Penilaian ke-${number}"
                                                                        value="{{ $aspek->nama }}" id="nama_group"
                                                                        style="width: 70%; float:left">
                                                                    <input type="text" name="bobot[]"
                                                                        class="form-control"
                                                                        placeholder="Bobot ke-${number}"
                                                                        value="{{ $aspek->bobot }}" style="width: 20%">
                                                                    <i class="fas fa-trash-alt btn-hapus-aspek text-danger ml-2"
                                                                        data-id-aspek = "{{ $aspek->id }}"
                                                                        style="font-size: 12pt"></i>
                                                                </div>
                                                            </td>
                                                        @else
                                                            <td></td>
                                                        @endif
                                                        <td style="width:50% !important;">
                                                            <div
                                                                class="indikator-wrapper p-0 m-0 d-flex align-items-center">
                                                                <input type="text"
                                                                    name="indikator_{{ $number }}[]"
                                                                    class="form-control @error('indikator_{{ $number }}[]') is-invalid @enderror"
                                                                    placeholder="Indikator Penilaian ke-${numberIndikator}"
                                                                    style="width: 90%; float:left"
                                                                    value="{{ $indikator->nama }}">
                                                                <i class="fas fa-trash-alt btn-hapus-indikator text-danger ml-2"
                                                                    data-id-indikator = "{{ $indikator->id }}"
                                                                    style="font-size: 12pt"></i>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @php $index = 0; @endphp
                                                @endforeach
                                                @php $number++; @endphp
                                            @endforeach
                                            <tr id="btn-tambah-wrapper">

                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-info btn-block">
                                            <div class="ikon-edit">
                                                <span class="fal fa-upload mr-1"></span>
                                                Update
                                            </div>
                                            <div class="span spinner-text d-none">
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                    aria-hidden="true"></span>
                                                Loading...
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('plugin')
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            // Menangkap klik pada tombol "Tambah Aspek Penilaian"
            $(function() {
                $('.select2').select2({
                    placeholder: 'Pilih Data Berikut',
                });
            });

            $('#group_penilaian_form_update').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                formData.append("group_penilaian_id", "{{ $group_penilaian->id }}");
                const id = "{{ $group_penilaian->id }}";

                $.ajax({
                    type: "POST",
                    url: '/api/dashboard/kpi/group_penilaian/' + id + '/update',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('#group_penilaian_form_update').find('.ikon-edit').hide();
                        $('#group_penilaian_form_update').find('.spinner-text').removeClass(
                            'd-none');
                    },
                    success: function(response) {
                        $('#group_penilaian_form_update').find('.ikon-edit').show();
                        $('#group_penilaian_form_update').find('.spinner-text').addClass(
                            'd-none');
                        showSuccessAlert(response.message)
                        setTimeout(function() {
                            window.location.href =
                                "{{ route('kpi.get.form-penilaian') }}";
                        }, 1000);
                    },
                    error: function(xhr) {
                        // $('#create-attendance-form').modal('hide');
                        var errors = JSON.parse(xhr.responseText);
                        var errorMessage = '';

                        $.each(errors, function(key, value) {
                            errorMessage += value +
                                '. '; // Menambahkan setiap pesan kesalahan
                        });
                        showErrorAlert(
                            errorMessage);
                    }
                });
            });

            $('.btn-hapus-indikator').click(function(e) {
                e.preventDefault();
                let button = $(this);
                confirm('Yakin ingin menghapus ini ?');
                let id = button.attr('data-id-indikator');

                $.ajax({
                    type: "POST",
                    url: "/api/dashboard/kpi/indikator-penilaian/" + id +
                        "/destroy", // Assuming this is the endpoint that accepts POST requests
                    data: {
                        id: id
                    },
                    success: function(response) {
                        showSuccessAlert(response.message);
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    error: function(xhr) {
                        showErrorAlert(xhr.responseText);
                    }
                });
            });

            $('.btn-hapus-aspek').click(function(e) {
                e.preventDefault();
                let button = $(this);
                confirm('Yakin ingin menghapus ini ?');
                let id = button.attr('data-id-aspek');

                $.ajax({
                    type: "POST",
                    url: "/api/dashboard/kpi/aspek-penilaian/" + id +
                        "/destroy", // Assuming this is the endpoint that accepts POST requests
                    data: {
                        id: id
                    },
                    success: function(response) {
                        showSuccessAlert(response.message);
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    error: function(xhr) {
                        showErrorAlert(xhr.responseText);
                    }
                });
            });

        });
    </script>
@endsection
