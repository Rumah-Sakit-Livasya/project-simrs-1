@php
    use App\Models\Employee;
@endphp
@extends('inc.layout')
@section('title', 'Targets')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row mb-5">
            @can('create okr')
                <div class="col-xl-12">
                    <button type="button" id="btn-tambah" class="btn btn-primary waves-effect waves-themed" data-backdrop="static"
                        data-keyboard="false" data-toggle="modal" data-target="#tambah-data" title="Tambah Job Level">
                        <span class="fal fa-plus-circle mr-1"></span>
                        Tambah OKR
                    </button>
                </div>
            @endcan
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="row">
                    <div class="col-xl-12">
                        <div id="panel-1" class="panel mb-3">
                            <div class="panel-hdr">
                                <h2>
                                    Filter
                                </h2>
                            </div>
                            <div class="panel-container show">
                                <div class="panel-content">
                                    <form action="{{ route('targets') }}" method="get">
                                        @method('GET')
                                        @csrf
                                        <div class="row" id="step-1">
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="filter-bulan">Bulan</label>
                                                    <!-- Mengubah input menjadi select2 -->
                                                    <select
                                                        class="select2 form-control @error('bulan') is-invalid @enderror"
                                                        name="bulan" id="filter-bulan">
                                                        <option value="1"
                                                            {{ old('bulan', isset($selectedBulan) && $selectedBulan == 1 ? 'selected' : '') }}>
                                                            Januari</option>
                                                        <option value="2"
                                                            {{ old('bulan', isset($selectedBulan) && $selectedBulan == 2 ? 'selected' : '') }}>
                                                            Februari</option>
                                                        <option value="3"
                                                            {{ old('bulan', isset($selectedBulan) && $selectedBulan == 3 ? 'selected' : '') }}>
                                                            Maret</option>
                                                        <option value="4"
                                                            {{ old('bulan', isset($selectedBulan) && $selectedBulan == 4 ? 'selected' : '') }}>
                                                            April</option>
                                                        <option value="5"
                                                            {{ old('bulan', isset($selectedBulan) && $selectedBulan == 5 ? 'selected' : '') }}>
                                                            Mei</option>
                                                        <option value="6"
                                                            {{ old('bulan', isset($selectedBulan) && $selectedBulan == 6 ? 'selected' : '') }}>
                                                            Juni</option>
                                                        <option value="7"
                                                            {{ old('bulan', isset($selectedBulan) && $selectedBulan == 7 ? 'selected' : '') }}>
                                                            Juli</option>
                                                        <option value="8"
                                                            {{ old('bulan', isset($selectedBulan) && $selectedBulan == 8 ? 'selected' : '') }}>
                                                            Agustus</option>
                                                        <option value="9"
                                                            {{ old('bulan', isset($selectedBulan) && $selectedBulan == 9 ? 'selected' : '') }}>
                                                            September</option>
                                                        <option value="10"
                                                            {{ old('bulan', isset($selectedBulan) && $selectedBulan == 10 ? 'selected' : '') }}>
                                                            Oktober</option>
                                                        <option value="11"
                                                            {{ old('bulan', isset($selectedBulan) && $selectedBulan == 11 ? 'selected' : '') }}>
                                                            November</option>
                                                        <option value="12"
                                                            {{ old('bulan', isset($selectedBulan) && $selectedBulan == 12 ? 'selected' : '') }}>
                                                            Desember</option>
                                                    </select>

                                                    @error('bulan')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="tahun">Tahun</label>
                                                    <!-- Mengubah input menjadi select2 -->
                                                    <select
                                                        class="select2 form-control @error('tahun') is-invalid @enderror"
                                                        name="tahun" id="tahun">

                                                        <option value="2024"
                                                            {{ old('tahun', isset($selectedTahun) && $selectedTahun == 7 ? 'selected' : '') }}>
                                                            2024</option>
                                                        <option value="2025"
                                                            {{ old('tahun', isset($selectedTahun) && $selectedTahun == 7 ? 'selected' : '') }}>
                                                            2025</option>
                                                        <option value="2026"
                                                            {{ old('tahun', isset($selectedTahun) && $selectedTahun == 7 ? 'selected' : '') }}>
                                                            2026</option>
                                                        <option value="2027"
                                                            {{ old('tahun', isset($selectedTahun) && $selectedTahun == 7 ? 'selected' : '') }}>
                                                            2027</option>
                                                        <option value="2028"
                                                            {{ old('tahun', isset($selectedTahun) && $selectedTahun == 7 ? 'selected' : '') }}>
                                                            2028</option>
                                                        <option value="2029"
                                                            {{ old('tahun', isset($selectedTahun) && $selectedTahun == 7 ? 'selected' : '') }}>
                                                            2029</option>
                                                        <option value="2030"
                                                            {{ old('tahun', isset($selectedTahun) && $selectedTahun == 7 ? 'selected' : '') }}>
                                                            2030</option>
                                                    </select>
                                                    @error('tahun')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4 d-flex align-items-center">
                                                <button type="submit" class="btn btn-primary btn-block w-100">
                                                    <div class="ikon-tambah">
                                                        <span class="fal fa-search mr-1"></span>Cari
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
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Tabel OKR
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- datatable start -->
                            <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                <thead>
                                    <tr>
                                        <th style="white-space: nowrap">No</th>
                                        <th style="white-space: nowrap">Aksi</th>
                                        <th style="white-space: nowrap">Judul</th>
                                        <th style="white-space: nowrap">Status</th>
                                        <th style="white-space: nowrap">Actual</th>
                                        <th style="white-space: nowrap">Target</th>
                                        <th style="white-space: nowrap">Difference</th>
                                        <th style="white-space: nowrap">PIC</th>
                                        <th style="white-space: nowrap">Bulan</th>
                                        <th style="white-space: nowrap">Hasil</th>
                                        <th style="white-space: nowrap">Evaluasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($targets as $row)
                                        <tr>
                                            <td style="white-space: nowrap">{{ $loop->iteration }}</td>
                                            <td style="white-space: nowrap">
                                                @can('edit okr')
                                                    <button type="button" data-backdrop="static" data-keyboard="false"
                                                        class="badge mx-1 btn-edit badge-primary p-2 border-0 text-white"
                                                        data-id="{{ $row->id }}" title="Ubah" data-toggle="tooltip"
                                                        data-placement="top">
                                                        <span class="fal fa-pencil ikon-edit"></span>
                                                        <div class="span spinner-text d-none">
                                                            <span class="spinner-border spinner-border-sm" role="status"
                                                                aria-hidden="true"></span>
                                                            Loading...
                                                        </div>
                                                    </button>
                                                @endcan
                                                @can('edit okr')
                                                    <button type="button" data-backdrop="static" data-keyboard="false"
                                                        class="badge mx-1 btn-edit-hasil badge-warning p-2 border-0 text-white"
                                                        data-id="{{ $row->id }}" title="Hasil" data-toggle="tooltip"
                                                        data-placement="top">
                                                        <i class='bx bx-select-multiple m-0 ikon-hasil'></i>
                                                        <div class="span spinner-text d-none">
                                                            <span class="spinner-border spinner-border-sm" role="status"
                                                                aria-hidden="true"></span>
                                                            Loading...
                                                        </div>
                                                    </button>
                                                @endcan
                                            </td>
                                            <td style="white-space: nowrap">{{ $row->title }}</td>
                                            @if ($row->status === 'Di luar rentang target')
                                                <td style="white-space: nowrap; background-color: #282828; color: #e6e6e6">
                                                    {{ $row->status }}</td>
                                            @elseif($row->status === 'Belum dikerjakan sama sekali')
                                                <td style="white-space: nowrap; background-color: #282828; color: #e6e6e6">
                                                    {{ $row->status }}</td>
                                            @elseif($row->status === 'Belum sesuai target')
                                                <td style="white-space: nowrap; background-color: #f10000; color: #ffffff">
                                                    {{ $row->status }}</td>
                                            @elseif($row->status === 'Hampir mendekati target')
                                                <td style="white-space: nowrap; background-color: #eaff00; color: #0a0a0a">
                                                    {{ $row->status }}</td>
                                            @elseif($row->status === 'Sesuai target')
                                                <td style="white-space: nowrap; background-color: #00cd3a; color: #ffffff">
                                                    {{ $row->status }}</td>
                                            @endif
                                            <td style="white-space: nowrap">{{ $row->actual }}
                                                {{ $row->satuan == 'persen' ? '%' : '' }}</td>
                                            <td style="white-space: nowrap">{{ $row->target }}
                                                {{ $row->satuan == 'persen' ? '%' : '' }}</td>
                                            <td style="white-space: nowrap">{{ $row->difference }}
                                                {{ $row->satuan == 'persen' ? '%' : '' }}</td>
                                            <td style="white-space: nowrap">
                                                {{ Employee::where('id', $row->pic)->first()->fullname ?? '' }}
                                            </td>
                                            <td style="white-space: nowrap">{{ angkaKeBulan($row->bulan) }}</td>
                                            <td style="white-space: nowrap">{{ $row->hasil }}</td>
                                            <td style="white-space: nowrap">{{ $row->evaluasi }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th style="white-space: nowrap">No</th>
                                        <th style="white-space: nowrap">Aksi</th>
                                        <th style="white-space: nowrap">Judul</th>
                                        <th style="white-space: nowrap">Status</th>
                                        <th style="white-space: nowrap">Actual</th>
                                        <th style="white-space: nowrap">Target</th>
                                        <th style="white-space: nowrap">Difference</th>
                                        <th style="white-space: nowrap">PIC</th>
                                        <th style="white-space: nowrap">Bulan</th>
                                        <th style="white-space: nowrap">Hasil</th>
                                        <th style="white-space: nowrap">Evaluasi</th>
                                    </tr>
                                </tfoot>
                            </table>
                            <!-- datatable end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('pages.target.partials.create-data')
        @include('pages.target.partials.update-data')
        @include('pages.target.partials.update-hasil')
    </main>
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            $(function() {
                $('.select2').select2();
                $('#tambah-data #pic').select2({
                    placeholder: 'Pilih PIC',
                    dropdownParent: $('#tambah-data')
                });
                $('#tambah-data #bulan').select2({
                    placeholder: 'Pilih bulan',
                    dropdownParent: $('#tambah-data')
                });
                $('#filter-bulan').select2({
                    placeholder: 'Pilih Bulan',
                    allowClear: true
                });
            });

            $('#status').change(function() {
                if ($(this).is(':checked')) {
                    $('#status-text').text('Aktif');
                    $('input[name=status]').val('on');
                } else {
                    $('#status-text').text('Tidak Aktif');
                    $('input[name=status]').val('off');
                }
            });

            $('.btn-edit').click(function(e) {
                e.preventDefault();
                let button = $(this);
                console.log('clicked');
                let id = button.attr('data-id');
                button.find('.ikon-edit').hide();
                button.find('.spinner-text').removeClass('d-none');

                $.ajax({
                    type: "GET", // Method pengiriman data bisa dengan GET atau POST
                    url: `/api/dashboard/targets/get/${id}`, // Isi dengan url/path file php yang dituju
                    dataType: "json",
                    success: function(data) {
                        button.find('.ikon-edit').show();
                        button.find('.spinner-text').addClass('d-none');
                        $('#ubah-data').modal('show');
                        $('#ubah-data #user_id').val(data.user_id);
                        $('#ubah-data #organization_id').val(data.organization_id);
                        $('#ubah-data #title').val(data.title);
                        $('#ubah-data #actual').val(data.actual);
                        $('#ubah-data #target').val(data.target);
                        $('#ubah-data #min_target').val(data.min_target);
                        $('#ubah-data #pic').val(data.pic).select2({
                            dropdownParent: $('#ubah-data')
                        });
                        $('#ubah-data #bulan').val(data.bulan).select2({
                            dropdownParent: $('#ubah-data')
                        });
                        // Cek radio button sesuai dengan data satuan
                        if (data.satuan === 'baku') {
                            $('#update-baku').prop('checked', true);
                        } else if (data.satuan === 'persen') {
                            $('#update-persen').prop('checked', true);
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });

                $('#update-form').on('submit', function(e) {
                    e.preventDefault();
                    let formData = $(this).serialize();
                    $.ajax({
                        type: "POST",
                        url: '/api/dashboard/targets/update/' + id,
                        data: formData,
                        beforeSend: function() {
                            $('#update-form').find('.ikon-edit').hide();
                            $('#update-form').find('.spinner-text')
                                .removeClass(
                                    'd-none');
                        },
                        success: function(response) {
                            $('#ubah-data').modal('hide');
                            showSuccessAlert(response.message)
                            setTimeout(function() {
                                location.reload();
                            }, 500);
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                        }
                    });
                });
            });

            $('.btn-edit-hasil').click(function(e) {
                e.preventDefault();
                let button = $(this);
                let id = button.attr('data-id');
                button.find('.ikon-hasil').hide();
                button.find('.spinner-text').removeClass('d-none');
                $.ajax({
                    type: "GET", // Method pengiriman data bisa dengan GET atau POST
                    url: `/api/dashboard/targets/get/${id}`, // Isi dengan url/path file php yang dituju
                    dataType: "json",
                    success: function(data) {
                        button.find('.ikon-hasil').show();
                        button.find('.spinner-text').addClass('d-none');
                        $('#ubah-data-hasil').modal('show');
                        $('#ubah-data-hasil #hasil').val(data.hasil);
                        $('#ubah-data-hasil #evaluasi').val(data.evaluasi);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });

                $('#update-form-hasil').on('submit', function(e) {
                    e.preventDefault();
                    let formData = $(this).serialize();
                    $.ajax({
                        type: "POST",
                        url: '/api/dashboard/targets/update-hasil/' + id,
                        data: formData,
                        beforeSend: function() {
                            $('#update-form-hasil').find('.ikon-hasil').hide();
                            $('#update-form-hasil').find('.spinner-text')
                                .removeClass(
                                    'd-none');
                        },
                        success: function(response) {
                            $('#ubah-data-hasil').modal('hide');
                            showSuccessAlert(response.message)
                            setTimeout(function() {
                                location.reload();
                            }, 500);
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                        }
                    });
                });
            });

            $('#store-form').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                $.ajax({
                    type: "POST",
                    url: '/api/dashboard/targets/store',
                    data: formData,
                    beforeSend: function() {
                        $('#store-form').find('.ikon-tambah').hide();
                        $('#store-form').find('.spinner-text').removeClass(
                            'd-none');
                    },
                    success: function(response) {
                        $('#store-form').find('.ikon-edit').show();
                        $('#store-form').find('.spinner-text').addClass('d-none');
                        $('#tambah-data').modal('hide');
                        showSuccessAlert(response.message)
                        setTimeout(function() {
                            location.reload();
                        }, 500);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });

            $('.btn-hapus').click(function(e) {
                e.preventDefault();
                let button = $(this);
                alert('Yakin ingin menghapus ini ?');
                let id = button.attr('data-id');
                $.ajax({
                    type: "GET",
                    url: '/api/dashboard/targets/delete/' + id,
                    beforeSend: function() {
                        button.find('.ikon-hapus').hide();
                        button.find('.spinner-text').removeClass(
                            'd-none');
                    },
                    success: function(response) {
                        button.find('.ikon-edit').show();
                        button.find('.spinner-text').addClass('d-none');
                        showSuccessAlert(response.message)
                        setTimeout(function() {
                            location.reload();
                        }, 500);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });

            $('#dt-basic-example').dataTable({
                responsive: true
            });

            $('.js-thead-colors a').on('click', function() {
                var theadColor = $(this).attr("data-bg");
                console.log(theadColor);
                $('#dt-basic-example thead').removeClassPrefix('bg-').addClass(theadColor);
            });

            $('.js-tbody-colors a').on('click', function() {
                var theadColor = $(this).attr("data-bg");
                console.log(theadColor);
                $('#dt-basic-example').removeClassPrefix('bg-').addClass(theadColor);
            });

        });
    </script>
@endsection
