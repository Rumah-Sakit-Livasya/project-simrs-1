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
                                    <form action="{{ route('targets') }}" method="post">
                                        @method('GET')
                                        @csrf
                                        <div class="row" id="filter-pencarian">
                                            <div class="col">
                                                <div class="form-group mb-3">
                                                    <label for="bulan">Bulan</label>
                                                    <!-- Mengubah input menjadi select2 -->
                                                    <select
                                                        class="select2 form-control @error('bulan') is-invalid @enderror"
                                                        name="bulan" id="bulan-filter">
                                                        <option value="">Pilih Bulan</option>
                                                        <!-- Placeholder option -->
                                                        <option value="1"
                                                            {{ old('bulan', $selectedBulan) == 1 ? 'selected' : '' }}>
                                                            Januari</option>
                                                        <option value="2"
                                                            {{ old('bulan', $selectedBulan) == 2 ? 'selected' : '' }}>
                                                            Februari</option>
                                                        <option value="3"
                                                            {{ old('bulan', $selectedBulan) == 3 ? 'selected' : '' }}>Maret
                                                        </option>
                                                        <option value="4"
                                                            {{ old('bulan', $selectedBulan) == 4 ? 'selected' : '' }}>April
                                                        </option>
                                                        <option value="5"
                                                            {{ old('bulan', $selectedBulan) == 5 ? 'selected' : '' }}>Mei
                                                        </option>
                                                        <option value="6"
                                                            {{ old('bulan', $selectedBulan) == 6 ? 'selected' : '' }}>Juni
                                                        </option>
                                                        <option value="7"
                                                            {{ old('bulan', $selectedBulan) == 7 ? 'selected' : '' }}>Juli
                                                        </option>
                                                        <option value="8"
                                                            {{ old('bulan', $selectedBulan) == 8 ? 'selected' : '' }}>
                                                            Agustus</option>
                                                        <option value="9"
                                                            {{ old('bulan', $selectedBulan) == 9 ? 'selected' : '' }}>
                                                            September</option>
                                                        <option value="10"
                                                            {{ old('bulan', $selectedBulan) == 10 ? 'selected' : '' }}>
                                                            Oktober</option>
                                                        <option value="11"
                                                            {{ old('bulan', $selectedBulan) == 11 ? 'selected' : '' }}>
                                                            November</option>
                                                        <option value="12"
                                                            {{ old('bulan', $selectedBulan) == 12 ? 'selected' : '' }}>
                                                            Desember</option>
                                                    </select>

                                                    @error('bulan')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group mb-3">
                                                    <label for="tahun">Tahun</label>
                                                    <!-- Mengubah input menjadi select2 -->
                                                    <select
                                                        class="select2 form-control @error('tahun') is-invalid @enderror"
                                                        name="tahun" id="tahun">
                                                        <!-- Placeholder option -->
                                                        <option value="2024"
                                                            {{ old('tahun', $selectedTahun) == 2024 ? 'selected' : '' }}>
                                                            2024</option>
                                                        <option value="2025"
                                                            {{ old('tahun', $selectedTahun) == 2025 ? 'selected' : '' }}>
                                                            2025</option>
                                                        <option value="2026"
                                                            {{ old('tahun', $selectedTahun) == 2026 ? 'selected' : '' }}>
                                                            2026</option>
                                                        <option value="2027"
                                                            {{ old('tahun', $selectedTahun) == 2027 ? 'selected' : '' }}>
                                                            2027</option>
                                                        <option value="2028"
                                                            {{ old('tahun', $selectedTahun) == 2028 ? 'selected' : '' }}>
                                                            2028</option>
                                                        <option value="2029"
                                                            {{ old('tahun', $selectedTahun) == 2029 ? 'selected' : '' }}>
                                                            2029</option>
                                                        <option value="2030"
                                                            {{ old('tahun', $selectedTahun) == 2030 ? 'selected' : '' }}>
                                                            2030</option>
                                                    </select>
                                                    @error('tahun')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            @can('admin okr')
                                                <div class="col">
                                                    <div class="form-group mb-3">
                                                        <label for="organization_id">Unit</label>
                                                        <!-- Mengubah input menjadi select2 -->
                                                        <select
                                                            class="select2 form-control @error('organization_id') is-invalid @enderror"
                                                            name="organization_id" id="organization_id">
                                                            <option value="">Pilih data berikut</option>
                                                            <!-- Placeholder option -->
                                                            @foreach ($organizations as $organization)
                                                                <option value="{{ $organization->id }}"
                                                                    {{ old('organization_id') == $organization->id || (isset($selectedOrganization) && $selectedOrganization == $organization->id) ? 'selected' : '' }}>
                                                                    {{ $organization->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('organization_id')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group mb-3">
                                                        <label for="status">Grade</label>
                                                        <!-- Mengubah input menjadi select2 -->
                                                        <select
                                                            class="select2 form-control @error('status') is-invalid @enderror"
                                                            name="status" id="status">
                                                            <option value="">Pilih Grade</option>
                                                            <!-- Placeholder option -->
                                                            <option value="green"
                                                                {{ (old('status') ?? request('status')) == 'green' ? 'selected' : '' }}>
                                                                Hijau</option>
                                                            <option value="blue"
                                                                {{ (old('status') ?? request('status')) == 'blue' ? 'selected' : '' }}>
                                                                Biru</option>
                                                            <option value="yellow"
                                                                {{ (old('status') ?? request('status')) == 'yellow' ? 'selected' : '' }}>
                                                                Kuning</option>
                                                            <option value="red"
                                                                {{ (old('status') ?? request('status')) == 'red' ? 'selected' : '' }}>
                                                                Merah</option>
                                                        </select>

                                                        @error('status')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            @endcan
                                            <div class="col d-flex align-items-center">
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
                                        @can('edit okr')
                                            <th style="white-space: nowrap">Aksi</th>
                                        @endcan
                                        <th style="white-space: nowrap">Judul</th>
                                        <th style="white-space: nowrap">Grade</th>
                                        <th style="white-space: nowrap">Data Awal</th>
                                        <th style="white-space: nowrap">Actual</th>
                                        <th style="white-space: nowrap">Target</th>
                                        <th style="white-space: nowrap">Movement</th>
                                        <th style="white-space: nowrap">Persentase</th>
                                        <th style="white-space: nowrap">PIC</th>
                                        <th style="white-space: nowrap">Bulan</th>
                                        <th style="white-space: nowrap">Hasil</th>
                                        <th style="white-space: nowrap">Evaluasi</th>
                                        <th style="white-space: nowrap">Nama Unit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($targets as $row)
                                        <tr>
                                            <td style="white-space: nowrap">{{ $loop->iteration }}</td>
                                            @can('edit okr')
                                                <td style="white-space: nowrap">
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
                                                    @can('edit hasil okr')
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
                                                @endcan
                                            </td>
                                            <td style="white-space: nowrap">{{ $row->title }}</td>
                                            @if ($row->status === 'green')
                                                <td
                                                    style="white-space: nowrap; text-align: center; background-color: #00cd3a; color: #fefefe">
                                                </td>
                                            @elseif($row->status === 'blue')
                                                <td
                                                    style="white-space: nowrap; text-align: center; background-color: #0a15f7; color: #fefefe">
                                                </td>
                                            @elseif($row->status === 'yellow')
                                                <td
                                                    style="white-space: nowrap; text-align: center; background-color: #eaff00; color: #0a0a0a">
                                                </td>
                                            @elseif($row->status === 'red')
                                                <td
                                                    style="white-space: nowrap; text-align: center; background-color: #f10000; color: #fefefe">
                                                </td>
                                            @elseif($row->status === 'invalid')
                                                <td
                                                    style="white-space: nowrap; text-align: center; background-color: #000; color: #ffffff">
                                                </td>
                                            @endif
                                            <td style="white-space: nowrap">
                                                {{ $row->satuan == 'rupiah' ? rp($row->baseline_data) : $row->baseline_data }}
                                                {{ $row->satuan == 'persen' ? '%' : '' }}
                                            </td>
                                            <td style="white-space: nowrap">
                                                {{ $row->satuan == 'rupiah' ? rp($row->actual) : $row->actual }}
                                                {{ $row->satuan == 'persen' ? '%' : '' }}
                                            </td>
                                            <td style="white-space: nowrap">
                                                {{ $row->satuan == 'rupiah' ? rp($row->target) : $row->target }}
                                                {{ $row->satuan == 'persen' ? '%' : '' }}
                                            </td>

                                            <td style="white-space: nowrap">{{ round($row->movement, 1) }}%</td>
                                            <td style="white-space: nowrap">{{ round($row->persentase, 1) }}%</td>
                                            <td style="white-space: nowrap">
                                                {{ Employee::where('id', $row->pic)->first()->fullname ?? '' }}
                                            </td>
                                            <td style="white-space: nowrap">{{ angkaKeBulan($row->bulan) }}</td>
                                            <td style="white-space: nowrap">{{ $row->hasil }}</td>
                                            <td style="white-space: nowrap">{{ $row->evaluasi }}</td>
                                            <td style="white-space: nowrap">{{ $row->organization->name }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th style="white-space: nowrap">No</th>
                                        @can('edit okr')
                                            <th style="white-space: nowrap">Aksi</th>
                                        @endcan
                                        <th style="white-space: nowrap">Judul</th>
                                        <th style="white-space: nowrap">Grade</th>
                                        <th style="white-space: nowrap">Data Awal</th>
                                        <th style="white-space: nowrap">Actual</th>
                                        <th style="white-space: nowrap">Target</th>
                                        <th style="white-space: nowrap">Movement</th>
                                        <th style="white-space: nowrap">Persentase</th>
                                        <th style="white-space: nowrap">PIC</th>
                                        <th style="white-space: nowrap">Bulan</th>
                                        <th style="white-space: nowrap">Hasil</th>
                                        <th style="white-space: nowrap">Evaluasi</th>
                                        <th style="white-space: nowrap">Nama Unit</th>
                                    </tr>
                                </tfoot>
                            </table>
                            <!-- datatable end -->
                        </div>
                    </div>
                </div>

                {{-- Tabel --}}
                <div id="panel-3" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Capaian
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- datatable start -->
                            <div class="table-responsive">
                                <table id="dt-basic-example2" class="table table-bordered table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th class='text-center' rowspan="2">Unit</th>
                                            <th class='text-center' colspan="4">OKR</th>
                                        </tr>
                                        <tr>
                                            <th>Jumlah OKR</th>
                                            <th>Tercapai</th>
                                            {{-- <th>Hampir Tercapai</th> --}}
                                            <th>Tidak Tercapai</th>
                                            {{-- <th>Minim Progres</th> --}}
                                            <th>Persentase</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ $targetData['name'] }}</td>
                                            <td>{{ $targetData['jumlah_target'] }}</td>
                                            <td>{{ $targetData['target_tercapai'] }}</td>
                                            {{-- <td>{{ $targetData['target_hampir_tercapai'] }}</td> --}}
                                            <td>{{ $targetData['target_tidak_tercapai'] }}</td>
                                            {{-- <td>{{ $targetData['target_tidak_dikerjakan'] }}</td> --}}
                                            <td>{{ $targetData['percentage'] }}%</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- datatable end -->
                        </div>
                    </div>
                </div>

                {{-- Grafik --}}
                <div id="panel-2" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Grafik <span class="fw-300"><i>Capaian</i></span>
                        </h2>
                        <div class="panel-toolbar">
                            <button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip"
                                data-offset="0,10" data-original-title="Collapse"></button>
                            <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip"
                                data-offset="0,10" data-original-title="Fullscreen"></button>
                            <button class="btn btn-panel" data-action="panel-close" data-toggle="tooltip"
                                data-offset="0,10" data-original-title="Close"></button>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div id="barlineCombine">
                                <canvas style="width:100%; height:600px;"></canvas>
                            </div>
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
    <script src="/js/statistics/chartjs/chartjs.bundle.js"></script>
    <script>
        $(document).ready(function() {
            $(function() {
                $('.select2').select2();
                $('#create-bulan').select2({
                    placeholder: 'Pilih data berikut',
                    dropdownParent: $('#tambah-data'),
                    allowClear: true
                });
                $('#update-bulan').select2({
                    placeholder: 'Pilih data berikut',
                    dropdownParent: $('#ubah-data'),
                    allowClear: true
                });
                $('#create-pic').select2({
                    placeholder: 'Pilih data berikut',
                    dropdownParent: $('#tambah-data'),
                    allowClear: true
                });
                $('#update-pic').select2({
                    placeholder: 'Pilih data berikut',
                    dropdownParent: $('#ubah-data'),
                    allowClear: true
                });
                $('#bulan-filter').select2({
                    placeholder: 'Pilih data berikut',
                    allowClear: true
                });
                $('#status').select2({
                    placeholder: 'Pilih data berikut',
                    allowClear: true
                });
                $('#organization_id').select2({
                    placeholder: 'Pilih data berikut',
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
                        $('#ubah-data #baseline_data').val(data.baseline_data);
                        $('#ubah-data #title').val(data.title);
                        $('#ubah-data #actual').val(data.actual);
                        $('#ubah-data #target').val(data.target);
                        $('#ubah-data #custom_target').val(data.custom_target);
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
                        } else if (data.satuan === 'rupiah') {
                            $('#update-rupiah').prop('checked', true);
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
                        $('#ubah-data-hasil #initiative').val(data.initiative);
                        $('#ubah-data-hasil #goal').val(data.goal);
                        $('#ubah-data-hasil #key_result').val(data.key_result);
                        $('#ubah-data-hasil #anggaran').val(data.anggaran);
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

            barlineCombine();
        });

        var barlineCombine = function() {
            var barlineCombineData = {
                labels: {!! json_encode($targetNames) !!}, // Gunakan nama target sebagai label
                datasets: [{
                    type: 'bar',
                    label: 'Capaian (%)',
                    backgroundColor: function(context) {
                        var index = context.dataIndex;
                        var value = context.dataset.data[index];
                        return value >= 100 ? myapp_get_color.success_300 :
                            value >= 60 ? myapp_get_color.info_300 :
                            value >= 30 ? myapp_get_color.warning_300 :
                            myapp_get_color.danger_300;
                    },
                    data: {!! json_encode($percentages) !!}, // Gunakan data persentase yang diambil
                    borderWidth: 0
                }]
            };

            var config = {
                type: 'bar',
                data: barlineCombineData,
                options: {
                    responsive: true,
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Persentase Target Terpenuhi'
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            gridLines: {
                                display: true,
                                color: "#f2f2f2"
                            },
                            ticks: {
                                beginAtZero: true,
                                fontSize: 11
                            }
                        }],
                        yAxes: [{
                            display: true,
                            ticks: {
                                beginAtZero: true,
                                fontSize: 11,
                                max: 100,
                                callback: function(value) {
                                    return value + '%'; // Menambahkan simbol % pada label
                                }
                            }
                        }]
                    }
                }
            }

            new Chart($("#barlineCombine > canvas").get(0).getContext("2d"), config);
        }
    </script>
@endsection
