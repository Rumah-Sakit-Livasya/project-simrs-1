@extends('inc.layout')
@section('title', 'Laporan Bulanan Unit')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Laporan Bulanan Unit Alat Medis, Nonmedis, dan IT
                        </h2>
                        @include('pages.partials.panel-toolbar')
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form method="post" action="{{ route('inventaris.report.bulanan') }}" class="mb-4">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="month">Bulan</label>
                                        <select name="month" id="month" class="form-control select2">
                                            @for ($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}"
                                                    {{ request('month') == $i ? 'selected' : '' }}>
                                                    {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="year">Tahun</label>
                                        <select name="year" id="year" class="form-control select2">
                                            @for ($i = date('Y'); $i >= 2000; $i--)
                                                <option value="{{ $i }}"
                                                    {{ request('year') == $i ? 'selected' : '' }}>
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="btn btn-primary btn-block">Tampilkan Laporan</button>
                                    </div>
                                </div>
                            </form>

                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5>Data Alat</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-striped">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Kategori</th>
                                                <th>Total Alat</th>
                                                <th>Total Perbaikan Alat</th>
                                                <th>Perbaikan Belum Selesai</th>
                                                <th>Alat Rusak</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="clickable-data-category" data-category="medis">Alat Medis</td>
                                                <td>{{ $alatMedis }} unit</td>
                                                <td>{{ $perbaikanAlatMedis }} unit
                                                    @if ($alatMedis > 0)
                                                        ({{ number_format(($perbaikanAlatMedis / $alatMedis) * 100, 2) }}%)
                                                    @else
                                                        (0.00%)
                                                    @endif
                                                </td>
                                                <td>{{ $perbaikanAlatMedisPending }} unit
                                                    @if ($alatMedis > 0)
                                                        ({{ number_format(($perbaikanAlatMedisPending / $alatMedis) * 100, 2) }}%)
                                                    @else
                                                        (0.00%)
                                                    @endif
                                                </td>
                                                <td>{{ $alatMedisTidakTersedia }} unit
                                                    @if ($alatMedis > 0)
                                                        ({{ number_format(($alatMedisTidakTersedia / $alatMedis) * 100, 2) }}%)
                                                    @else
                                                        (0.00%)
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Alat Nonmedis</td>
                                                <td>{{ $alatNonmedis }} unit</td>
                                                <td>{{ $perbaikanAlatNonMedis }} unit
                                                    @if ($alatNonmedis > 0)
                                                        ({{ number_format(($perbaikanAlatNonMedis / $alatNonmedis) * 100, 2) }}%)
                                                    @else
                                                        (0.00%)
                                                    @endif
                                                </td>
                                                <td>{{ $perbaikanAlatNonMedisPending }} unit
                                                    @if ($alatNonmedis > 0)
                                                        ({{ number_format(($perbaikanAlatNonMedisPending / $alatNonmedis) * 100, 2) }}%)
                                                    @else
                                                        (0.00%)
                                                    @endif
                                                </td>
                                                <td>{{ $alatNonMedisTidakTersedia }} unit
                                                    @if ($alatNonmedis > 0)
                                                        ({{ number_format(($alatNonMedisTidakTersedia / $alatNonmedis) * 100, 2) }}%)
                                                    @else
                                                        (0.00%)
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Alat IT</td>
                                                <td>{{ $alatIT }} unit</td>
                                                <td>{{ $perbaikanAlatIT }} unit
                                                    @if ($alatIT > 0)
                                                        ({{ number_format(($perbaikanAlatIT / $alatIT) * 100, 2) }}%)
                                                    @else
                                                        (0.00%)
                                                    @endif
                                                </td>
                                                <td>{{ $perbaikanAlatITPending }} unit
                                                    @if ($alatIT > 0)
                                                        ({{ number_format(($perbaikanAlatITPending / $alatIT) * 100, 2) }}%)
                                                    @else
                                                        (0.00%)
                                                    @endif
                                                </td>
                                                <td>{{ $alatITTidakTersedia }} unit
                                                    @if ($alatIT > 0)
                                                        ({{ number_format(($alatITTidakTersedia / $alatIT) * 100, 2) }}%)
                                                    @else
                                                        (0.00%)
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal for displaying maintenance items -->
    <div class="modal fade" id="maintenanceModal" tabindex="-1" role="dialog" aria-labelledby="maintenanceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="maintenanceModalLabel">Maintenance Items</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-striped" id="maintenanceTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Barang</th>
                                <th>Kondisi</th>
                                <th>Hasil</th>
                                <th>Estimasi</th>
                                <th>Keterangan</th>
                                <th>RTL</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Foto</th> <!-- New column for photo -->
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be appended here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Photo Modal -->
    <div class="modal fade" id="photoModal" tabindex="-1" role="dialog" aria-labelledby="photoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="photoModalLabel">Photo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img id="photoImage" src="" alt="Photo" class="img-fluid" />
                </div>
            </div>
        </div>
    </div>
@endsection
@section('plugin')
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            $(document).ready(function() {
                $('.select2').select2();
            });

            $('.clickable-data-category').on('click', function() {
                var category = $(this).data('category');
                var month = $('#month').val();
                var year = $('#year').val();

                // Fetch maintenance data based on category, month, and year
                $.ajax({
                    url: '{{ route('inventaris.report.maintenance') }}', // Adjust the route as necessary
                    method: 'GET',
                    data: {
                        category: category,
                        month: month,
                        year: year
                    },
                    success: function(data) {
                        console.log(data);

                        // Clear existing rows
                        $('#maintenanceTable tbody').empty();

                        $.each(data, function(index, item) {
                            $('#maintenanceTable tbody').append(`
                                <tr>
                                    <td>${item.id}</td>
                                    <td>${item.nama_barang}</td>
                                    <td>${item.kondisi}</td>
                                    <td>${item.hasil}</td>
                                    <td>${item.estimasi}</td>
                                    <td>${item.keterangan}</td>
                                    <td>${item.rtl}</td>
                                    <td>${item.status}</td>
                                    <td>${item.tanggal}</td>
                                    <td>
                                        <button class="btn btn-primary view-photo" data-photo="${item.foto}" data-toggle="modal" data-target="#photoModal">View Photo</button>
                                    </td>
                                </tr>
                            `);
                        });

                        // Show the modal
                        $('#maintenanceModal').modal('show');
                    },
                    error: function() {
                        alert('Error fetching maintenance data.');
                    }
                });
            });
        });

        $(document).on('click', '.view-photo', function() {
            var photoUrl = $(this).data(
                'photo'
            ); // Get the photo URL from the button's data attribute
            $('#photoImage').attr('src', '/storage/' + photoUrl);
        });
    </script>
@endsection
