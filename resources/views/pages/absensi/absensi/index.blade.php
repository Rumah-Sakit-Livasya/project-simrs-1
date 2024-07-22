@extends('inc.layout')
@section('title', 'Live Attendace')
@section('extended-css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <style>
        #map {
            height: 225px;
            /* Ensure the map container has a height */
        }

        .video-container {
            position: relative;
            width: 100%;
            padding-top: 75%;
            /* Aspect ratio of 4:3 */
        }

        #video,
        #canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        #canvas {
            z-index: 1;
        }

        .icon-dashboard-report {
            font-size: 2em;
            text-align: center;
        }

        .text-dashboard-report {
            font-size: 1em;
            text-align: center;
            color: #666666 !important;
        }

        .bg-opacity-50 {
            background-color: #fd3994a5 !important;
            /* Red with 50% opacity */
        }

        .badge.pos-top.pos-right.dashboard-report {
            font-size: 0.9em;
            top: 9px;
            right: 12px;
            border-radius: 50%;
            height: 20px;
            width: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        @media screen and (max-width: 500px) {
            .badge.pos-top.pos-right.dashboard-report {
                font-size: 0.9em;
                height: 15px;
                width: 15px;
            }
        }
    </style>
    <script defer src="/js/face-api/face-api.min.js"></script>
@endsection
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Live Attendance</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- Map container -->
                            <div id="map"></div>

                            <!-- Time attendance section -->
                            <div class="time-attendance row justify-content-center mb-2" style="color: #666666 !important;">
                                <span class="mt-4 col-md-12 text-center" style="font-size:1.2em">
                                    {{ \Carbon\Carbon::now()->translatedFormat('l, j F Y') }}
                                </span>
                                <h2 class="col-md-12 mt-2 text-center" id="waktu-realtime">
                                    {{ \Carbon\Carbon::now()->translatedFormat('H:i:s') }}
                                </h2>
                                <div class="attendance-btn mt-2">
                                    @if (isset($last_attendance))
                                        <button
                                            class="btn btn-primary btn-sm btn-clock-in mr-1 {{ $last_attendance->clock_in ? 'd-none' : '' }}"
                                            id="clock_in">
                                            <span class="spinner-border spinner-text spinner-border-sm d-none"
                                                role="status" aria-hidden="true"></span>
                                            Clock In
                                        </button>
                                        <button
                                            class="btn btn-danger btn-sm btn-clock-in {{ $last_attendance->clock_out ? 'd-none' : '' }}"
                                            id="clock_out">
                                            <span class="spinner-border spinner-text spinner-border-sm d-none"
                                                role="status" aria-hidden="true"></span>
                                            Clock Out
                                        </button>
                                    @else
                                        <button class="btn btn-primary btn-sm btn-clock-in mr-1" id="clock_in">Clock
                                            In</button>
                                        <button class="btn btn-danger btn-sm btn-clock-in" id="clock_out">Clock Out</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12">
                        <div id="panel-1" class="panel mb-0">
                            <div class="panel-hdr">
                                <h2>
                                    Filter
                                </h2>
                            </div>
                            <div class="panel-container show">
                                <div class="panel-content">
                                    <form action="{{ route('attendances.filter') }}" method="POST">
                                        @method('GET')
                                        @csrf
                                        <div class="row" id="step-1">
                                            <div class="col-md-5">
                                                <div class="form-group mb-3">
                                                    <label for="bulan">Bulan</label>
                                                    <!-- Mengubah input menjadi select2 -->
                                                    <select
                                                        class="select2 form-control @error('bulan') is-invalid @enderror"
                                                        name="bulan" id="bulan">
                                                        <option value="1"
                                                            {{ isset($selectedBulan) && $selectedBulan == 1 ? 'selected' : '' }}>
                                                            Januari</option>
                                                        <option value="2"
                                                            {{ isset($selectedBulan) && $selectedBulan == 2 ? 'selected' : '' }}>
                                                            Februari</option>
                                                        <option value="3"
                                                            {{ isset($selectedBulan) && $selectedBulan == 3 ? 'selected' : '' }}>
                                                            Maret</option>
                                                        <option value="4"
                                                            {{ isset($selectedBulan) && $selectedBulan == 4 ? 'selected' : '' }}>
                                                            April</option>
                                                        <option value="5"
                                                            {{ isset($selectedBulan) && $selectedBulan == 5 ? 'selected' : '' }}>
                                                            Mei</option>
                                                        <option value="6"
                                                            {{ isset($selectedBulan) && $selectedBulan == 6 ? 'selected' : '' }}>
                                                            Juni</option>
                                                        <option value="7"
                                                            {{ isset($selectedBulan) && $selectedBulan == 7 ? 'selected' : '' }}>
                                                            Juli</option>
                                                        <option value="8"
                                                            {{ isset($selectedBulan) && $selectedBulan == 8 ? 'selected' : '' }}>
                                                            Agustus</option>
                                                        <option value="9"
                                                            {{ isset($selectedBulan) && $selectedBulan == 9 ? 'selected' : '' }}>
                                                            September</option>
                                                        <option value="10"
                                                            {{ isset($selectedBulan) && $selectedBulan == 10 ? 'selected' : '' }}>
                                                            Oktober</option>
                                                        <option value="11"
                                                            {{ isset($selectedBulan) && $selectedBulan == 11 ? 'selected' : '' }}>
                                                            November</option>
                                                        <option value="12"
                                                            {{ isset($selectedBulan) && $selectedBulan == 12 ? 'selected' : '' }}>
                                                            Desember</option>
                                                    </select>

                                                    @error('bulan')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group mb-3">
                                                    <label for="tahun">Tahun</label>
                                                    <!-- Mengubah input menjadi select2 -->
                                                    <select
                                                        class="select2 form-control @error('tahun') is-invalid @enderror"
                                                        name="tahun" id="tahun">

                                                        <option value="2024"
                                                            {{ isset($selectedTahun) && $selectedTahun == 7 ? 'selected' : '' }}>
                                                            2024</option>
                                                        <option value="2023"
                                                            {{ isset($selectedTahun) && $selectedTahun == 7 ? 'selected' : '' }}>
                                                            2023</option>
                                                        <option value="2025"
                                                            {{ isset($selectedTahun) && $selectedTahun == 7 ? 'selected' : '' }}>
                                                            2025</option>
                                                        <option value="2026"
                                                            {{ isset($selectedTahun) && $selectedTahun == 7 ? 'selected' : '' }}>
                                                            2026</option>
                                                        <option value="2027"
                                                            {{ isset($selectedTahun) && $selectedTahun == 7 ? 'selected' : '' }}>
                                                            2027</option>
                                                        <option value="2028"
                                                            {{ isset($selectedTahun) && $selectedTahun == 7 ? 'selected' : '' }}>
                                                            2028</option>
                                                        <option value="2029"
                                                            {{ isset($selectedTahun) && $selectedTahun == 7 ? 'selected' : '' }}>
                                                            2029</option>
                                                        <option value="2030"
                                                            {{ isset($selectedTahun) && $selectedTahun == 7 ? 'selected' : '' }}>
                                                            2030</option>
                                                    </select>
                                                    @error('tahun')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-2 d-flex align-items-center">
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

                <div class="row d-flex my-3">
                    <div class="col-md-3 pr-1" style="width: 25%; padding-left: 0px !important!">
                        <div class="card">
                            <div class="card-body p-2">
                                <span
                                    class="badge badge-icon pos-top pos-right dashboard-report">{{ $jumlah_hadir }}</span>
                                <div class="icon-dashboard-report text-primary">
                                    <i class="fal fa-user-alt hadir"></i>
                                </div>
                                <div class="text-dashboard-report">
                                    Hadir
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 px-1" style="width: 25%">
                        <div class="card">
                            <div class="card-body p-2">
                                <span
                                    class="badge badge-icon pos-top pos-right dashboard-report">{{ $jumlah_izin }}</span>
                                <div class="icon-dashboard-report text-success">
                                    <i class="fal fa-file-alt"></i>
                                </div>
                                <div class="text-dashboard-report">
                                    Izin
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 px-1" style="width: 25%">
                        <div class="card">
                            <div class="card-body p-2">
                                <span
                                    class="badge badge-icon pos-top pos-right dashboard-report">{{ $jumlah_sakit }}</span>
                                <div class="icon-dashboard-report text-danger">
                                    <i class="fal fa-first-aid"></i>
                                </div>
                                <div class="text-dashboard-report">
                                    Sakit
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 pl-1" style="width: 25%; padding-right: 0px !important!">
                        <div class="card">
                            <div class="card-body p-2">
                                <span
                                    class="badge badge-icon pos-top pos-right dashboard-report">{{ $jumlah_cuti }}</span>
                                <div class="icon-dashboard-report text-warning">
                                    <i class="fal fa-clock"></i>
                                </div>
                                <div class="text-dashboard-report">
                                    Cuti
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Attendance Log
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
                                            <th style="white-space: nowrap">Tanggal</th>
                                            <th style="white-space: nowrap">Clock In</th>
                                            <th style="white-space: nowrap">Clock Out</th>
                                            <th style="white-space: nowrap">Late Clock In</th>
                                            <th style="white-space: nowrap">Early Clock Out</th>
                                            <th style="white-space: nowrap">Libur</th>
                                            <th style="white-space: nowrap">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($attendances as $row)
                                            <tr
                                                class="{{ $row->is_day_off == 1 ? 'bg-danger text-white bg-opacity-50' : '' }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td style="white-space: nowrap">
                                                    {{ \Carbon\Carbon::parse($row->date)->translatedFormat('j F Y') }}
                                                    {{ $row->shift ? '( ' . $row->shift->time_in . ' - ' . $row->shift->time_out . ' )' : '' }}
                                                </td>
                                                <td style="white-space: nowrap"
                                                    class="{{ $row->clock_in && $row->late_clock_in ? 'text-danger' : '' }}">
                                                    @isset($row->clock_in)
                                                        {{ \Carbon\Carbon::parse($row->clock_in)->format('H:i') }}
                                                    @else
                                                        -
                                                    @endisset
                                                </td>
                                                <td style="white-space: nowrap"
                                                    class="{{ $row->clock_out && $row->early_clock_out ? 'text-danger' : '' }}">
                                                    @isset($row->clock_out)
                                                        {{ \Carbon\Carbon::parse($row->clock_out)->format('H:i') }}
                                                    @else
                                                        -
                                                    @endisset
                                                </td>
                                                <td style="white-space: nowrap">
                                                    {{ $row->late_clock_in ? $row->late_clock_in . ' Menit' : '-' }}
                                                </td>
                                                <td style="white-space: nowrap">
                                                    {{ $row->early_clock_out ? $row->early_clock_out . ' Menit' : '-' }}
                                                </td>
                                                <td style="white-space: nowrap">
                                                    {{ $row->is_day_off == 1 ? 'Ya' : '-' }}
                                                </td>
                                                <td style="white-space: nowrap">
                                                    @isset($row->day_off)
                                                        {{ $row->day_off->attendance_code->description ?? $row->attendance_code->description }}
                                                    @else
                                                        -
                                                    @endisset
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th style="white-space: nowrap">No</th>
                                            <th style="white-space: nowrap">Tanggal</th>
                                            <th style="white-space: nowrap">Clock In</th>
                                            <th style="white-space: nowrap">Clock Out</th>
                                            <th style="white-space: nowrap">Late Clock In</th>
                                            <th style="white-space: nowrap">Early Clock Out</th>
                                            <th style="white-space: nowrap">Libur</th>
                                            <th style="white-space: nowrap">Keterangan</th>
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
    @include('pages.absensi.absensi.partials.face')
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
                map.setView([lat, lng], 17); // Zoom level set to 17 for closer view

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
        $(document).ready(function() {
            const label = "{{ auth()->user()->employee->foto }}";
            const name = "{{ auth()->user()->name }}";
            const employeeImage = `/storage/employee/profile/${label}`;
            const $video = $('#video');
            const $canvas = $('#canvas');
            const $info = $('#info');

            async function initFaceRecognition() {
                try {
                    await Promise.all([
                        faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
                        faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
                        faceapi.nets.faceRecognitionNet.loadFromUri('/models'),
                        faceapi.nets.ssdMobilenetv1.loadFromUri('/models')
                    ]);
                    console.log('Models loaded successfully');
                } catch (error) {
                    console.error('Error loading models:', error);
                    alert('Error loading models. Check console for details.');
                }
            }

            async function startVideo() {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({
                        video: true
                    });
                    $video[0].srcObject = stream;
                    $video[0].play();
                } catch (err) {
                    console.error("Error accessing webcam: ", err);
                    alert('Error accessing webcam. Check console for details.');
                }
            }

            $video.on('play', async () => {
                const displaySize = {
                    width: $video.width(),
                    height: $video.height()
                };
                faceapi.matchDimensions($canvas[0], displaySize);

                try {
                    const labeledFaceDescriptors = await loadLabeledImages();
                    console.log('Labeled face descriptors loaded');
                    const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, 0.6);

                    setInterval(async () => {
                        try {
                            const detections = await faceapi.detectAllFaces($video[0],
                                    new faceapi.TinyFaceDetectorOptions())
                                .withFaceLandmarks()
                                .withFaceDescriptors();
                            const resizedDetections = faceapi.resizeResults(detections,
                                displaySize);
                            const results = resizedDetections.map(d => faceMatcher
                                .findBestMatch(d.descriptor));

                            const ctx = $canvas[0].getContext('2d');
                            ctx.clearRect(0, 0, $canvas.width(), $canvas.height());

                            results.forEach((result, i) => {
                                const box = resizedDetections[i].detection.box;
                                const drawBox = new faceapi.draw.DrawBox(box, {
                                    label: result.toString()
                                });
                                drawBox.draw($canvas[0]);

                                if (result.label !== 'unknown') {
                                    const employeeName = name;
                                    console.log('Face matched:', employeeName);
                                    showAlert(employeeName);
                                }
                            });
                        } catch (error) {
                            console.error('Error detecting faces:', error);
                        }
                    }, 100);
                } catch (error) {
                    console.error('Error loading labeled images:', error);
                    alert('Error loading labeled images. Check console for details.');
                }
            });

            async function loadLabeledImages() {
                try {
                    const descriptions = [];
                    const img = await faceapi.fetchImage(employeeImage);
                    const detections = await faceapi.detectSingleFace(img).withFaceLandmarks()
                        .withFaceDescriptor();
                    if (detections) {
                        descriptions.push(detections.descriptor);
                    } else {
                        console.error('No face detected in the image.');
                        alert('No face detected in the profile image.');
                    }
                    return [new faceapi.LabeledFaceDescriptors(name, descriptions)];
                } catch (error) {
                    console.error('Error loading labeled images:', error);
                    alert('Error loading labeled images. Check console for details.');
                }
            }

            function showAlert(employeeName) {
                $info.text(`Pegawai Teridentifikasi: ${employeeName}`);
            }

            // Initialize face-api.js models
            initFaceRecognition();

            // Event handler for the "Clock In" button
            $('#clock_in').click(function(e) {
                e.preventDefault();
                console.log("Clock In button clicked");
                $('#clockin-modal').modal('show');
                startVideo();
            });

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
                        showSuccessAlert(response.message);
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
                console.log("Click");
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
                        $('#approve-request').find('.spinner-text').removeClass('d-none');
                    },
                    success: function(response) {
                        showSuccessAlert(response.message);
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
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

            $('#clock_out').click(function() {
                $('#clock_out').prop('disabled', true);
                $('#clock_out').find('.spinner-border-sm').removeClass('d-none');
                $('#clock_out').find('.clock-out-text').addClass('d-none');
                $.ajax({
                    url: "{{ route('employee.attendance.clock-out') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            showSuccessAlert(response.message);
                            $('#clock_out').prop('disabled', false);
                            $('#clock_out').find('.spinner-border-sm').addClass('d-none');
                            $('#clock_out').find('.clock-out-text').removeClass('d-none');
                        }
                    },
                    error: function(xhr) {
                        showErrorAlert('Terjadi kesalahan, harap coba lagi');
                        $('#clock_out').prop('disabled', false);
                        $('#clock_out').find('.spinner-border-sm').addClass('d-none');
                        $('#clock_out').find('.clock-out-text').removeClass('d-none');
                    }
                });
            });
        });
    </script>


    {{-- <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBHLSY8GcO1KmPQdavk8G1m4wUw0tXlifU&loading=async&callback=myMap&v=weekly"
        async defer></script> --}}
@endsection
