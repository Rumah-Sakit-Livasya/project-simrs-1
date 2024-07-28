@extends('inc.layout')
@section('title', 'Report OKR')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
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
                                    <form action="{{ route('targets.report') }}" method="POST">
                                        @method('GET')
                                        @csrf
                                        <div class="row" id="step-1">
                                            <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label for="bulan">Bulan</label>
                                                    <!-- Mengubah input menjadi select2 -->
                                                    <select
                                                        class="select2 form-control @error('bulan') is-invalid @enderror"
                                                        name="bulan" id="bulan">
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
                                            <div class="col-md-3">
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
                                            <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label for="organization_id">Unit</label>
                                                    <!-- Mengubah input menjadi select2 -->
                                                    <select
                                                        class="select2 form-control @error('organization_id') is-invalid @enderror"
                                                        name="organization_id" id="organization_id">
                                                        @foreach ($organizations as $organization)
                                                            <option value="{{ $organization->id }}">
                                                                {{ $organization->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('organization_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-3 d-flex align-items-center">
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

                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            OKR Log
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- datatable start -->
                            <div class="table-responsive">
                                <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                    <thead>
                                        <tr>
                                            <th style="white-space: nowrap">No</th>
                                            <th style="white-space: nowrap">Unit</th>
                                            <th style="white-space: nowrap">Judul</th>
                                            <th style="white-space: nowrap">Status</th>
                                            <th style="white-space: nowrap">Actual</th>
                                            <th style="white-space: nowrap">Target</th>
                                            <th style="white-space: nowrap">Difference</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($targets as $row)
                                            <tr>
                                                <td style="white-space: nowrap">{{ $loop->iteration }}</td>
                                                <td style="white-space: nowrap">{{ $row->organization->name }}</td>
                                                <td style="white-space: nowrap">{{ $row->title }}</td>
                                                @if ($row->status === 'Di luar rentang target')
                                                    <td
                                                        style="white-space: nowrap; background-color: #282828; color: #e6e6e6">
                                                        {{ $row->status }}</td>
                                                @elseif($row->status === 'Belum dikerjakan sama sekali')
                                                    <td
                                                        style="white-space: nowrap; background-color: #282828; color: #e6e6e6">
                                                        {{ $row->status }}</td>
                                                @elseif($row->status === 'Belum sesuai target')
                                                    <td
                                                        style="white-space: nowrap; background-color: #f10000; color: #ffffff">
                                                        {{ $row->status }}</td>
                                                @elseif($row->status === 'Hampir mendekati target')
                                                    <td
                                                        style="white-space: nowrap; background-color: #eaff00; color: #0a0a0a">
                                                        {{ $row->status }}</td>
                                                @elseif($row->status === 'Sesuai target')
                                                    <td
                                                        style="white-space: nowrap; background-color: #00cd3a; color: #ffffff">
                                                        {{ $row->status }}</td>
                                                @endif
                                                <td style="white-space: nowrap">{{ $row->actual }}</td>
                                                <td style="white-space: nowrap">{{ $row->target }}</td>
                                                <td style="white-space: nowrap">{{ $row->difference }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th style="white-space: nowrap">No</th>
                                            <th style="white-space: nowrap">Unit</th>
                                            <th style="white-space: nowrap">Judul</th>
                                            <th style="white-space: nowrap">Status</th>
                                            <th style="white-space: nowrap">Actual</th>
                                            <th style="white-space: nowrap">Target</th>
                                            <th style="white-space: nowrap">Difference</th>
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
@endsection
@section('plugin')
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        var map = L.map('map').setView([0, 0], 13); // Initial placeholder coordinates
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Check if Geolocation is available
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;
                var accuracy = position.coords.accuracy;

                // Set the view to the current location with a closer zoom level
                map.setView([lat, lng], 17); // Zoom level set to 15 for closer view

                // Add a marker at the current location
                var marker = L.marker([lat, lng]).addTo(map)
                    .bindPopup('You are here.<br> Accuracy: ' + accuracy + ' meters.')
                    .openPopup();
            }, function(error) {
                console.error("Geolocation failed: " + error.message);
            });
        } else {
            console.error("Geolocation is not supported by this browser.");
        }
    </script>
    <script>
        /* demo scripts for change table color */
        /* change background */
        $(document).ready(function() {
            $(function() {
                $('.select2').select2();
            });
            $('#datepicker-modal-2').daterangepicker({
                opens: 'left'
            }, function(start, end, label) {
                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end
                    .format('YYYY-MM-DD'));
            });

            $('#store-form').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                formData.append("employee_id", "{{ auth()->user()->employee->id }}");
                formData.append("approved_line_child", "{{ auth()->user()->employee->approval_line }}");
                formData.append("approved_line_parent",
                    "{{ auth()->user()->employee->approval_line_parent }}");

                $.ajax({
                    type: "POST",
                    url: '/employee/request/day-off',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('#store-form').find('.ikon-tambah').hide();
                        $('#store-form').find('.spinner-text').removeClass('d-none');
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
                        showErrorAlert(xhr.responseText);
                    }
                });
            });

            $('.btn-accept').on('click', function(e) {
                e.preventDefault();
                console.log("click");
                let formData = {
                    employee_id: "{{ auth()->user()->employee->id }}"
                }
                let id = $(this).attr('data-id');
                $.ajax({
                    type: "PUT",
                    url: '/employee/approve/day-off/' + id,
                    data: formData,
                    beforeSend: function() {
                        $('#approve-request').find('.ikon-edit').hide();
                        $('#approve-request').find('.spinner-text')
                            .removeClass(
                                'd-none');
                    },
                    success: function(response) {
                        showSuccessAlert(response.message)
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            })

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
    <script>
        if (navigator.geolocation) {
            window.myMap = function() {
                navigator.geolocation.getCurrentPosition(function(position) {
                    let location = {
                        lat: position.coords.latitude - 0.000001,
                        lng: position.coords.longitude
                    };

                    let mapProp = {
                        center: location,
                        zoom: 17.1,
                    };

                    let map = new google.maps.Map(document.getElementById("map"), mapProp);

                    let marker = new google.maps.Marker({
                        position: location,
                        map: map
                    });
                });
            }
        } else {
            showErrorAlert("Browser ini tidak support geolokasi!");
        }
        $(document).ready(function() {
            $('#clock_in').click(function() {

                $('#clock_in').prop('disabled', true);
                $('#clock_in').find('.spinner-text').removeClass('d-none');
                navigator.geolocation.getCurrentPosition(function(position) {
                    let latitude = position.coords.latitude;
                    let longitude = position.coords.longitude;
                    let data_clock_in = {
                        _token: "{{ csrf_token() }}",
                        latitude: latitude,
                        longitude: longitude,
                        clock_in: null,
                        clock_out: null,
                        employee_id: "{{ Auth::user()->employee->id }}",
                        time_in: null

                    };
                    $.ajax({
                        type: "PUT",
                        url: "/api/dashboard/clock-in",
                        data: data_clock_in,
                        async: true,
                        success: function(response) {
                            $('#clock_in').find('.spinner-text').addClass(
                                'd-none');
                            showSuccessAlert(response.message)
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                var errors = xhr.responseJSON;
                                // Lakukan sesuatu dengan pesan kesalahan yang diterima
                                showErrorAlert(errors.error);
                            } else {
                                // Tangani kesalahan lainnya
                                var errors = xhr.responseJSON;
                                showErrorAlert(errors.error);
                            }
                        }
                    });
                });

            })
            $('#clock_out').click(function() {

                $('#clock_out').prop('disabled', true);
                $('#clock_out').find('.spinner-text').removeClass('d-none');
                navigator.geolocation.getCurrentPosition(function(position) {
                    let latitude = position.coords.latitude;
                    let longitude = position.coords.longitude;
                    let data_clock_out = {
                        _token: "{{ csrf_token() }}",
                        latitude: latitude,
                        longitude: longitude,
                        clock_out: null,
                        employee_id: "{{ Auth::user()->employee->id }}",
                        time_out: null
                    };
                    $.ajax({
                        type: "PUT",
                        url: "/api/dashboard/clock-out",
                        data: data_clock_out,
                        async: true,
                        success: function(response) {
                            $('#clock_out').find('.spinner-text').addClass(
                                'd-none');
                            showSuccessAlert(response.message)
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                var errors = xhr.responseJSON;
                                // Lakukan sesuatu dengan pesan kesalahan yang diterima
                                showErrorAlert(errors.error);
                            } else {
                                // Tangani kesalahan lainnya
                                var errors = xhr.responseJSON;
                                showErrorAlert(errors.error);
                            }
                        }
                    });
                });

            })
            setInterval(function() {
                var currentTime = new Date();
                var hours = currentTime.getHours();
                var minutes = currentTime.getMinutes();
                var seconds = currentTime.getSeconds();
                hours = (hours < 10 ? "0" : "") + hours;
                minutes = (minutes < 10 ? "0" : "") + minutes;
                seconds = (seconds < 10 ? "0" : "") + seconds;
                var timeString = hours + ':' + minutes + ':' + seconds;
                $("#waktu-realtime").text(timeString);
            }, 1000);
        });
    </script>
    {{-- <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBHLSY8GcO1KmPQdavk8G1m4wUw0tXlifU&loading=async&callback=myMap&v=weekly"
        async defer></script> --}}
@endsection
