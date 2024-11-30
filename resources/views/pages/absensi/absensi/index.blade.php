@extends('inc.layout')
@section('title', 'Live Attendace')
@section('extended-css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <style>
        #gambar-detail-absensi {
            display: flex;
            /* Use flexbox to position images side by side */
            justify-content: space-between;
            /* Space images evenly */
            width: 100%;
            margin-bottom: 20px;
            /* Ensure the container takes full width */
        }

        .img-clock {
            width: 50%;
            /* Each image takes 50% of the modal width */
            height: auto;
            /* Maintain aspect ratio */
            object-fit: cover;
            /* Cover the container */
            object-position: center;
            /* Center the image */
        }

        /* Mengatur container video agar memiliki aspek rasio yang benar */
        #map-wrapper {
            position: relative;
            height: 400px;
            /* Adjust the height as needed */
            width: 100%;
            /* Make wrapper take full width of modal */
            margin-bottom: 20px;
            /* Add space below the map */
        }

        #map-detail-absensi {
            height: 100%;
            /* Make map fill the wrapper */
            width: 100%;
            /* Make map fill the wrapper */
            border: 1px solid #ddd;
            /* Optional: add a border around the map for better visibility */
        }

        #canvas {
            transform: none;
            /* Pastikan tidak ada transformasi CSS */
        }

        canvas {
            display: block;
            width: 100%;
            /* Sesuaikan dengan layout */
            height: auto;
            /* Pertahankan rasio aspek */
        }

        .video-container {
            position: relative;
            width: 100%;
            padding-top: 120%;
            /* 5:6 Aspect Ratio for a slightly taller view */
            /* Gunakan padding-top yang sesuai untuk aspect ratio yang diinginkan */
        }

        .video-container video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transform: scaleX(-1);
            /* Mengatur video agar menutupi container dengan benar */
        }

        #map {
            height: 300px;
            /* Atur tinggi sesuai kebutuhan */
            width: 100%;
            /* Lebar peta sesuai dengan elemen kontainer */
        }

        /* Responsif untuk ukuran layar lebih kecil */
        @media (max-width: 768px) {
            .video-container {
                padding-top: 140%;
                /* Adjust the aspect ratio for smaller screens */
                /* Adjust padding-top for a taller aspect ratio on mobile */
            }
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

        /* Tambahkan CSS untuk loading indicator */
        .loading-indicator {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .loading-text {
            font-size: 24px;
            font-weight: bold;
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

                            <!-- Tambahkan elemen loading indicator di dalam body -->
                            <div id="loading-indicator" class="loading-indicator">
                                <div class="spinner-border text-primary font-weight-bold" role="status">
                                </div>
                            </div>

                            <!-- Elemen peta -->
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

                                        <button id="request-camera-button" class="btn btn-primary mt-3 btn-block d-none">
                                            Izinkan Kamera
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
                                            <th style="white-space: nowrap">Detail</th>
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
                                                <td>
                                                    <button class="btn btn-primary text-white py-1 px-2 detail-absensi"
                                                        data-employee-id="{{ $row->employee_id }}"
                                                        data-tanggal="{{ $row->date }}">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
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
                                            <th style="white-space: nowrap">Detail</th>
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
    @include('pages.absensi.absensi.partials.detail-absensi')
@endsection
@section('plugin')
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        // $(document).ready(function() {
        //     const label = "{{ auth()->user()->employee->foto }}";
        //     const name = "{{ auth()->user()->name }}";
        //     const employeeImage = `/storage/employee/profile/${label}`;
        //     const employeeId = "{{ auth()->user()->employee->id }}";
        //     const $video = $('#video');
        //     const $canvas = $('#canvas');
        //     const $info = $('#info');
        //     let detectionCount = 0;
        //     const requiredDetections = 5;
        //     let photoData = null;
        //     let longitude = null;
        //     let latitude = null;
        //     let clockType = null;

        //     async function initFaceRecognition() {
        //         try {
        //             await Promise.all([
        //                 faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
        //                 faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
        //                 faceapi.nets.faceRecognitionNet.loadFromUri('/models'),
        //                 faceapi.nets.ssdMobilenetv1.loadFromUri('/models')
        //             ]);
        //             console.log('Models loaded successfully');
        //         } catch (error) {
        //             console.error('Error loading models:', error);
        //             alert('Error loading models. Check console for details.');
        //         }
        //     }

        //     async function startVideo() {
        //         try {
        //             const stream = await navigator.mediaDevices.getUserMedia({
        //                 video: true
        //             });
        //             $video[0].srcObject = stream;
        //             $video[0].play();
        //         } catch (err) {
        //             console.error("Error accessing webcam: ", err);
        //             showErrorAlert('Error accessing webcam. Check console for details.');
        //         }
        //     }

        //     function updateDimensions() {
        //         const displaySize = {
        //             width: $video.width(),
        //             height: $video.height()
        //         };
        //         faceapi.matchDimensions($canvas[0], displaySize);
        //         return displaySize;
        //     }

        //     $('#clockin-modal').on('shown.bs.modal', async function() {
        //         // Ensure video stream starts when modal is shown
        //         startVideo();

        //         // Set up dimensions for face-api.js
        //         const displaySize = updateDimensions();

        //         try {
        //             const labeledFaceDescriptors = await loadLabeledImages();
        //             console.log('Labeled face descriptors loaded');
        //             const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, 0.6);

        //             setInterval(async () => {
        //                 try {
        //                     const detections = await faceapi.detectAllFaces($video[0],
        //                             new faceapi.TinyFaceDetectorOptions())
        //                         .withFaceLandmarks()
        //                         .withFaceDescriptors();
        //                     const resizedDetections = faceapi.resizeResults(detections,
        //                         displaySize);
        //                     const results = resizedDetections.map(d => faceMatcher
        //                         .findBestMatch(d.descriptor));

        //                     const ctx = $canvas[0].getContext('2d');
        //                     ctx.clearRect(0, 0, $canvas.width(), $canvas.height());

        //                     results.forEach((result, i) => {
        //                         const box = resizedDetections[i].detection.box;
        //                         const drawBox = new faceapi.draw.DrawBox(box, {
        //                             label: result.toString()
        //                         });
        //                         drawBox.draw($canvas[0]);

        //                         if (result.label !== 'unknown') {
        //                             const employeeName = name;
        //                             console.log('Face matched:', employeeName);
        //                             showAlert(employeeName);
        //                             detectionCount++;
        //                             console.log(detectionCount);
        //                             if (detectionCount === 3) {
        //                                 capturePhoto();
        //                             }
        //                         } else {
        //                             $info.text(`Pegawai tidak teridentifikasi!`);
        //                         }
        //                     });
        //                 } catch (error) {
        //                     console.error('Error detecting faces:', error);
        //                 }
        //             }, 100);
        //         } catch (error) {
        //             console.error('Error loading labeled images:', error);
        //             alert('Update Foto profile terlebih dahulu!');
        //         }
        //     });

        //     async function loadLabeledImages() {
        //         try {
        //             const descriptions = [];
        //             const img = await faceapi.fetchImage(employeeImage);
        //             const detections = await faceapi.detectSingleFace(img).withFaceLandmarks()
        //                 .withFaceDescriptor();
        //             if (detections) {
        //                 descriptions.push(detections.descriptor);
        //             } else {
        //                 console.error('No face detected in the image.');
        //                 alert('No face detected in the profile image.');
        //             }
        //             return [new faceapi.LabeledFaceDescriptors(name, descriptions)];
        //         } catch (error) {
        //             console.error('Error loading labeled images:', error);
        //             alert('Update Foto profile terlebih dahulu!');
        //         }
        //     }

        //     function showAlert(employeeName) {
        //         $info.text(`Pegawai Teridentifikasi: ${employeeName}`);
        //     }

        //     async function initMap() {
        //         const map = L.map('map').setView([0, 0], 13); // Initial placeholder coordinates
        //         L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        //             attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        //         }).addTo(map);

        //         if (navigator.geolocation) {
        //             navigator.geolocation.getCurrentPosition(function(position) {
        //                 latitude = position.coords.latitude;
        //                 longitude = position.coords.longitude;
        //                 const accuracy = position.coords.accuracy;

        //                 console.log(
        //                     `Latitude: ${latitude}, Longitude: ${longitude}, Accuracy: ${accuracy}`);

        //                 map.setView([latitude, longitude], 17); // Zoom level set to 17 for closer view

        //                 L.marker([latitude, longitude]).addTo(map)
        //                     .bindPopup('You are here.<br> Accuracy: ' + accuracy + ' meters.')
        //                     .openPopup();
        //             }, function(error) {
        //                 showErrorAlert("Geolocation failed: " + error.message);
        //             });
        //         } else {
        //             showErrorAlert("Geolocation is not supported by this browser.");
        //         }
        //     }

        //     initFaceRecognition();
        //     initMap();

        //     $('#clock_in').click(function(e) {
        //         e.preventDefault();
        //         clockType = 'clockin'; // Set the clockType to clockin
        //         $('#clockin-modal').modal('show');
        //     });

        //     $('#clock_out').click(function(e) {
        //         e.preventDefault();
        //         clockType = 'clockout'; // Set the clockType to clockout
        //         $('#clockin-modal').modal('show');
        //     });

        //     function base64ToBlob(base64, mime) {
        //         const sliceSize = 512;
        //         const byteCharacters = atob(base64);
        //         const bytesLength = byteCharacters.length;
        //         const slicesCount = Math.ceil(bytesLength / sliceSize);
        //         const byteArrays = new Array(slicesCount);

        //         for (let sliceIndex = 0; sliceIndex < slicesCount; sliceIndex++) {
        //             const begin = sliceIndex * sliceSize;
        //             const end = Math.min(begin + sliceSize, bytesLength);

        //             const bytes = new Array(end - begin);
        //             for (let offset = begin, i = 0; offset < end; offset++, i++) {
        //                 bytes[i] = byteCharacters.charCodeAt(offset);
        //             }

        //             byteArrays[sliceIndex] = new Uint8Array(bytes);
        //         }

        //         return new Blob(byteArrays, {
        //             type: mime
        //         });
        //     }

        //     function handleClockIn(photoData) {
        //         if (!longitude || !latitude || !photoData) {
        //             $('#info').text('Missing required data for clocking in.');
        //             return;
        //         }

        //         console.log(`Clocking in with Longitude: ${longitude}, Latitude: ${latitude}`);

        //         const formData = new FormData();
        //         formData.append('_token', '{{ csrf_token() }}'); // Include CSRF token
        //         formData.append('longitude', longitude); // Longitude from geolocation
        //         formData.append('latitude', latitude); // Latitude from geolocation

        //         // Convert Base64 photoData to Blob
        //         const mimeType = 'image/jpeg';
        //         const base64Data = photoData.split(',')[1]; // Remove the data URL part
        //         const photoBlob = base64ToBlob(base64Data, mimeType);

        //         formData.append('photo', photoBlob, 'photo.jpg'); // Append photo data as a Blob
        //         formData.append('employee_id', employeeId); // Employee ID

        //         $.ajax({
        //             url: '/api/dashboard/clock-in',
        //             method: 'POST', // Use PUT method
        //             data: formData,
        //             processData: false,
        //             contentType: false,
        //             beforeSend: function() {
        //                 $('#clock_in_modal').prop('disabled', true);
        //                 $('#clock_in_modal').find('.spinner-border').removeClass('d-none');
        //             },
        //             success: function(response) {
        //                 $('#clock_in_modal').prop('disabled', false);
        //                 $('#clock_in_modal').find('.spinner-border').addClass('d-none');
        //                 $('#clock_in_modal').addClass('d-none');
        //                 $('#clock_in_modal').removeClass('d-none');
        //                 $('#clockin-modal').modal('hide');
        //                 showSuccessAlert(response.message);
        //                 setTimeout(function() {
        //                     console.log('Reloading the page now.');
        //                     window.location.reload();
        //                 }, 1000);
        //             },
        //             error: function(xhr) {
        //                 $('#clock_in_modal').prop('disabled', false);
        //                 $('#clock_in_modal').find('.spinner-border').addClass('d-none');
        //                 $('#clockin-modal').modal('hide');
        //                 showErrorAlert(xhr.responseJSON.error);
        //             }
        //         });
        //     }

        //     function handleClockOut(photoData) {
        //         if (!longitude || !latitude || !photoData) {
        //             $('#info').text('Missing required data for clocking out.');
        //             return;
        //         }

        //         console.log(`Clocking out with Longitude: ${longitude}, Latitude: ${latitude}`);

        //         const formData = new FormData();
        //         formData.append('_token', '{{ csrf_token() }}'); // Include CSRF token
        //         formData.append('longitude', longitude); // Longitude from geolocation
        //         formData.append('latitude', latitude); // Latitude from geolocation

        //         // Convert Base64 photoData to Blob
        //         const mimeType = 'image/jpeg';
        //         const base64Data = photoData.split(',')[1]; // Remove the data URL part
        //         const photoBlob = base64ToBlob(base64Data, mimeType);

        //         formData.append('photo', photoBlob, 'photo.jpg'); // Append photo data as a Blob
        //         formData.append('employee_id', employeeId); // Employee ID

        //         $.ajax({
        //             url: '/api/dashboard/clock-out',
        //             method: 'POST', // Use PUT method
        //             data: formData,
        //             processData: false,
        //             contentType: false,
        //             beforeSend: function() {
        //                 $('#clock_out_modal').prop('disabled', true);
        //                 $('#clock_out_modal').find('.spinner-border').removeClass('d-none');
        //             },
        //             success: function(response) {
        //                 $('#clock_out_modal').prop('disabled', false);
        //                 $('#clock_out_modal').find('.spinner-border').addClass('d-none');
        //                 $('#clock_out_modal').addClass('d-none');
        //                 $('#clockin-modal').modal('hide');
        //                 showSuccessAlert(response.message);
        //                 setTimeout(function() {
        //                     console.log('Reloading the page now.');
        //                     window.location.reload();
        //                 }, 1000);
        //             },
        //             error: function(xhr) {
        //                 $('#clock_out_modal').prop('disabled', false);
        //                 $('#clock_out_modal').find('.spinner-border').addClass('d-none');
        //                 $('#clockin-modal').modal('hide');
        //                 showErrorAlert(xhr.responseJSON.error);
        //             }
        //         });
        //     }

        //     function capturePhoto() {
        //         const canvas = document.createElement('canvas');
        //         canvas.width = $('#video').width();
        //         canvas.height = $('#video').height();
        //         const ctx = canvas.getContext('2d');
        //         ctx.drawImage($('#video')[0], 0, 0, canvas.width, canvas.height);
        //         const photoData = canvas.toDataURL('image/jpeg'); // Base64-encoded image data

        //         // Call the appropriate function based on clockType
        //         if (clockType === 'clockin') {
        //             handleClockIn(photoData);
        //         } else if (clockType === 'clockout') {
        //             handleClockOut(photoData);
        //         }
        //     }
        // });

        // $(document).ready(function() {
        //     const video = document.getElementById('video');
        //     const canvas = document.getElementById('canvas');
        //     const context = canvas.getContext('2d');
        //     const uploadButton = document.getElementById('upload');
        //     let latitude = null;
        //     let longitude = null;
        //     let actionType = null; // Will be either 'clock_in' or 'clock_out'
        //     let employeeId = null;
        //     let tanggal = null;
        //     let modal = null;
        //     let modalBody = null;

        //     function toggleSpinner(buttonId, show) {
        //         const button = document.getElementById(buttonId);
        //         const spinner = button.querySelector('.spinner-border');
        //         if (show) {
        //             spinner.style.display = 'inline-block';
        //         } else {
        //             spinner.style.display = 'none';
        //         }
        //     }

        //     async function startCamera() {
        //         if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        //             alert('Perangkat ini tidak mendukung akses kamera.');
        //             return;
        //         }

        //         try {
        //             const constraints = {
        //                 video: {
        //                     width: {
        //                         ideal: 640
        //                     },
        //                     height: {
        //                         ideal: 720
        //                     }
        //                 }
        //             };

        //             try {
        //                 const stream = await navigator.mediaDevices.getUserMedia(constraints);
        //                 console.log('Stream berhasil didapatkan:', stream);
        //             } catch (error) {
        //                 console.error('Kesalahan getUserMedia:', error);
        //             }

        //             video.srcObject = stream;
        //             video.setAttribute('playsinline', true);
        //             video.addEventListener('loadedmetadata', adjustCanvasSize);
        //         } catch (error) {
        //             console.error('Kesalahan saat mencoba mengakses kamera:', error);
        //             if (error.name === 'NotAllowedError') {
        //                 alert('Izinkan akses kamera untuk melanjutkan.');
        //             } else if (error.name === 'NotFoundError') {
        //                 alert('Kamera tidak ditemukan pada perangkat ini.');
        //             } else {
        //                 alert('Gagal mengakses kamera. Silakan periksa pengaturan perangkat.');
        //             }
        //         }
        //     }


        //     function adjustCanvasSize() {
        //         canvas.width = video.videoWidth;
        //         canvas.height = video.videoHeight;
        //     }

        //     async function requestCameraPermission() {
        //         try {
        //             const constraints = {
        //                 video: {
        //                     facingMode: 'user',
        //                     width: {
        //                         ideal: 640
        //                     },
        //                     height: {
        //                         ideal: 720
        //                     },
        //                     playsinline: true
        //                 }
        //             };
        //             const stream = await navigator.mediaDevices.getUserMedia(constraints);
        //             video.srcObject = stream;
        //             video.setAttribute('playsinline', true);
        //             video.addEventListener('loadedmetadata', () => {
        //                 adjustCanvasSize();
        //             });
        //         } catch (error) {
        //             console.error('Error accessing the camera again:', error);
        //             // alert('Kamera harus diizinkan untuk melanjutkan!');
        //         }
        //     }

        //     async function getLocation() {
        //         toggleLoadingIndicator(true);
        //         return new Promise((resolve, reject) => {
        //             if (navigator.geolocation) {
        //                 navigator.geolocation.getCurrentPosition(position => {
        //                     toggleLoadingIndicator(false);
        //                     latitude = position.coords.latitude;
        //                     longitude = position.coords.longitude;
        //                     resolve(position);
        //                 }, error => {
        //                     toggleLoadingIndicator(true);
        //                     console.error("Geolocation failed: " + error.message);
        //                     reject(error);
        //                 });
        //             } else {
        //                 toggleLoadingIndicator(true);
        //                 console.error("Geolocation is not supported by this browser.");
        //                 reject(new Error("Geolocation not supported"));
        //             }
        //         });
        //     }

        //     function toggleLoadingIndicator(show) {
        //         const loadingIndicator = document.getElementById('loading-indicator');
        //         if (show) {
        //             loadingIndicator.style.display = 'flex';
        //         } else {
        //             loadingIndicator.style.display = 'none';
        //         }
        //     }

        //     async function initializeMap() {
        //         var map = L.map('map').setView([0, 0], 13);

        //         L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        //             attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        //         }).addTo(map);

        //         try {
        //             const position = await getLocation();
        //             const lat = position.coords.latitude;
        //             const lng = position.coords.longitude;
        //             const accuracy = position.coords.accuracy;

        //             map.setView([lat, lng], 17); // Zoom level set to 17

        //             L.marker([lat, lng]).addTo(map)
        //                 .bindPopup('You are here.<br> Accuracy: ' + accuracy + ' meters.')
        //                 .openPopup();
        //         } catch (error) {
        //             console.error("Error initializing map: ", error);
        //             alert("Gagal memuat peta. Silakan coba lagi.");
        //         }
        //     }

        //     $('#clock_in').on('click', function(e) {
        //         e.preventDefault();
        //         actionType = 'clock_in'; // Set flag for Clock In
        //         if (video.srcObject) { // Cek apakah kamera sudah aktif
        //             $('#picture-modal').modal('show');
        //         } else {
        //             alert('Kamera harus aktif dan diizinkan untuk clock in.');
        //         }
        //     });

        //     $('#clock_out').on('click', function(e) {
        //         e.preventDefault();
        //         actionType = 'clock_out'; // Set flag for Clock Out
        //         if (video.srcObject) { // Cek apakah kamera sudah aktif
        //             $('#picture-modal').modal('show');
        //         } else {
        //             alert('Kamera harus aktif dan diizinkan untuk clock out.');
        //             requestCameraPermission(); // Meminta izin untuk mengaktifkan kamera
        //         }
        //     });

        //     $('#dt-basic-example').dataTable({
        //         responsive: false,
        //         "pageLength": 31,
        //         dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'B>>" +
        //             "<'row'<'col-sm-12'tr>>" +
        //             "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        //         buttons: [{
        //                 extend: 'excelHtml5',
        //                 text: 'Excel',
        //                 title: 'Rekap Absensi Bulan ' + new Date().toLocaleString('default', {
        //                     month: 'long',
        //                 }) + ' ' + new Date().getFullYear(),
        //                 titleAttr: 'Export to Excel',
        //                 className: 'btn-outline-default',
        //                 exportOptions: {
        //                     columns: ':visible',
        //                     format: {
        //                         body: function(data, row, column, node) {
        //                             return $('<div/>').html(data).text();
        //                         }
        //                     }
        //                 },
        //                 customize: function(xlsx) {
        //                     var sheet = xlsx.xl.worksheets['sheet1.xml'];
        //                     $('row:first c', sheet).attr('style',
        //                         'text-align: center;');
        //                     $('row:nth-child(2) c', sheet).attr('s', '43');
        //                     $('row:nth-child(2) c', sheet).attr('class', 'style43');
        //                 }
        //             },
        //             {
        //                 extend: 'print',
        //                 text: 'Print',
        //                 titleAttr: 'Print Table',
        //                 className: 'btn-outline-default'
        //             }
        //         ]
        //     });

        //     setInterval(function() {
        //         var currentTime = new Date();
        //         var hours = currentTime.getHours();
        //         var minutes = currentTime.getMinutes();
        //         var seconds = currentTime.getSeconds();
        //         hours = (hours < 10 ? "0" : "") + hours;
        //         minutes = (minutes < 10 ? "0" : "") + minutes;
        //         seconds = (seconds < 10 ? "0" : "") + seconds;
        //         var timeString = hours + ':' + minutes + ':' + seconds;
        //         $("#waktu-realtime").text(timeString);
        //     }, 1000);

        //     startCamera();
        //     initializeMap();

        //     function dataURLToFile(dataURL, filename) {
        //         const [header, data] = dataURL.split(',');
        //         const mime = header.match(/:(.*?);/)[1];
        //         const binary = atob(data);
        //         const array = [];
        //         for (let i = 0; i < binary.length; i++) {
        //             array.push(binary.charCodeAt(i));
        //         }
        //         return new File([new Uint8Array(array)], filename, {
        //             type: mime
        //         });
        //     }

        //     uploadButton.addEventListener('click', async () => {
        //         uploadButton.disabled = true;
        //         toggleSpinner('upload', true);

        //         context.resetTransform();
        //         context.save();
        //         context.translate(canvas.width, 0);
        //         context.scale(-1, 1);
        //         context.drawImage(video, 0, 0, canvas.width, canvas.height);
        //         context.restore();

        //         const dataURL = canvas.toDataURL('image/png');
        //         const file = dataURLToFile(dataURL, 'photo.png');
        //         const formData = new FormData();
        //         formData.append('employee_id', '{{ auth()->user()->employee->id }}');
        //         formData.append('photo', file);
        //         formData.append('location', `${latitude}, ${longitude}`);
        //         formData.append('latitude', latitude);
        //         formData.append('longitude', longitude);

        //         const apiUrl = actionType === 'clock_in' ? '/api/dashboard/clock-in' :
        //             '/api/dashboard/clock-out';

        //         try {
        //             const response = await fetch(apiUrl, {
        //                 method: 'POST',
        //                 headers: {
        //                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //                 },
        //                 body: formData
        //             });

        //             const result = await response.json();
        //             if (response.ok) {
        //                 console.log('Success:', result);
        //                 $('#picture-modal').modal('hide');
        //                 showSuccessAlert(result.message);
        //                 setTimeout(() => {
        //                     console.log('Reloading the page now.');
        //                     window.location.reload();
        //                 }, 1000);
        //             } else {
        //                 $('#picture-modal').modal('hide');
        //                 showErrorAlert(result.error);
        //             }
        //         } catch (error) {
        //             $('#picture-modal').modal('hide');
        //             showErrorAlert(error.error);
        //         } finally {
        //             toggleSpinner('upload', false);
        //             uploadButton.disabled = false;
        //         }
        //     });
        // });

        document.addEventListener('DOMContentLoaded', () => {
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const context = canvas.getContext('2d');
            const uploadButton = document.getElementById('upload');
            const loadingIndicator = document.getElementById('loading-indicator');
            const requestCameraButton = document.getElementById('request-camera-button');
            const clockInButton = document.getElementById('clock_in');
            const clockOutButton = document.getElementById('clock_out');
            const pictureModal = document.getElementById('picture-modal');
            const mapElement = document.getElementById('map');

            let latitude = null;
            let longitude = null;
            let actionType = null;
            let map = null;
            let marker = null;
            let isCameraActive = false;

            // Toggle Loading Indicator
            function toggleLoadingIndicator(show) {
                loadingIndicator.style.display = show ? 'flex' : 'none';
            }

            // Request Camera Access
            async function requestCameraAccess() {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({
                        video: {
                            facingMode: 'user',
                            width: {
                                ideal: 640
                            },
                            height: {
                                ideal: 720
                            }
                        }
                    });
                    video.srcObject = stream;
                    video.setAttribute('playsinline', true);

                    // Tunggu hingga metadata video tersedia untuk mendapatkan dimensi
                    video.onloadedmetadata = () => {
                        video.play();
                        canvas.width = video.videoWidth; // Sesuaikan lebar canvas dengan video
                        canvas.height = video.videoHeight; // Sesuaikan tinggi canvas dengan video
                    };

                    isCameraActive = true;
                } catch (error) {
                    console.warn("Gagal mengakses kamera:", error.message);
                    isCameraActive = false;
                }
            }


            // Request Location Access
            async function getLocation() {
                toggleLoadingIndicator(true);
                return new Promise((resolve, reject) => {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(position => {
                            toggleLoadingIndicator(false);
                            latitude = position.coords.latitude;
                            longitude = position.coords.longitude;

                            // Initialize map
                            if (!map) {
                                map = L.map(mapElement).setView([latitude, longitude], 17);
                                L.tileLayer(
                                    'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                        attribution: '&copy; OpenStreetMap contributors'
                                    }).addTo(map);

                                marker = L.marker([latitude, longitude]).addTo(map);
                                marker.bindPopup("Lokasi Anda").openPopup();
                            } else {
                                marker.setLatLng([latitude, longitude]);
                                map.setView([latitude, longitude], 17);
                            }

                            resolve(position);
                        }, error => {
                            toggleLoadingIndicator(false);
                            alert("Gagal mendapatkan lokasi: " + error.message);
                            reject(error);
                        });
                    } else {
                        toggleLoadingIndicator(false);
                        alert("Browser tidak mendukung geolokasi.");
                        reject(new Error("Geolocation not supported"));
                    }
                });
            }

            // Capture and Upload Image
            async function captureAndUploadImage() {
                uploadButton.disabled = true;

                // Check if camera is active
                let dataURL;
                if (isCameraActive) {
                    // Pastikan dimensi kanvas sama dengan video
                    context.drawImage(video, 0, 0, video.videoWidth, video.videoHeight);
                    dataURL = canvas.toDataURL('image/png');
                } else {
                    // Fallback if camera is not active
                    dataURL = ""; // Or set a default image data here if needed
                }

                const file = dataURLToFile(dataURL || '', 'photo.png');
                const formData = new FormData();
                formData.append('photo', file);
                formData.append('latitude', latitude);
                formData.append('longitude', longitude);
                formData.append('employee_id', '{{ auth()->user()->employee->id }}');

                const apiUrl = actionType === 'clock_in' ? '/api/dashboard/clock-in' :
                    '/api/dashboard/clock-out';

                try {
                    const response = await fetch(apiUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: formData
                    });

                    const result = await response.json();
                    if (response.ok) {
                        alert(`Berhasil ${actionType.replace('_', ' ')}: ${result.message}`);
                        window.location.reload();
                    } else {
                        alert(`Gagal ${actionType.replace('_', ' ')}: ${result.error}`);
                    }
                } catch (error) {
                    console.error(`Error saat ${actionType}:`, error);
                    alert(`Gagal ${actionType}. Silakan coba lagi.`);
                } finally {
                    uploadButton.disabled = false;
                }
            }


            // Convert Data URL to File
            function dataURLToFile(dataURL, filename) {
                if (!dataURL) {
                    return new File([], filename); // Return empty file if no data
                }
                const [header, data] = dataURL.split(',');
                const mime = header.match(/:(.*?);/)[1];
                const binary = atob(data);
                const array = [];
                for (let i = 0; i < binary.length; i++) {
                    array.push(binary.charCodeAt(i));
                }
                return new File([new Uint8Array(array)], filename, {
                    type: mime
                });
            }

            // Initialize Camera and Map
            async function initialize() {
                await requestCameraAccess();
                await getLocation();
            }

            // Clock In and Clock Out Events
            clockInButton.addEventListener('click', async () => {
                actionType = 'clock_in';
                if (!isCameraActive) {
                    alert(
                        "Kamera tidak aktif. Clock In tetap dapat dilakukan, tetapi gambar tidak akan diambil."
                    );
                }
                $(pictureModal).modal('show');
            });

            clockOutButton.addEventListener('click', async () => {
                actionType = 'clock_out';
                if (!isCameraActive) {
                    alert(
                        "Kamera tidak aktif. Clock Out tetap dapat dilakukan, tetapi gambar tidak akan diambil."
                    );
                }
                $(pictureModal).modal('show');
            });

            // Upload Button Event
            uploadButton.addEventListener('click', async () => {
                if (latitude && longitude) {
                    await captureAndUploadImage();
                    $(pictureModal).modal('hide');
                } else {
                    alert("Pastikan lokasi berhasil diakses sebelum upload.");
                }
            });

            // Initialize on page load
            initialize();
        });

        async function fetchAttendanceDetails(employeeId, tanggal) {
            const url = '/api/dashboard/attendances/detail';
            const data = {
                employee_id: employeeId,
                tanggal: tanggal
            };

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify(data)
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                return result;
            } catch (error) {
                console.error('Error fetching attendance details:', error);
                showErrorAlert(error.message);
                throw error;
            }
        }

        $('.detail-absensi').click(async function(e) {
            e.preventDefault();
            employeeId = $(this).attr('data-employee-id');
            tanggal = $(this).attr('data-tanggal');
            modal = $('#detail-absensi-modal');
            modalBody = modal.find('.modal-body');

            try {
                const result = await fetchAttendanceDetails(employeeId, tanggal);

                if (result.success) {
                    const attendance = result.data;
                    $('#tanggal-detail-absensi').text(tanggal);
                    // Show modal
                    modal.modal('show');

                    // Render map after modal is shown
                    modal.on('shown.bs.modal', function() {
                        if (attendance.location) {
                            const [lat, long] = attendance.location.split(',');
                            latitude = lat;
                            longitude = long;

                            // Clear the modal body before appending new content
                            modalBody.html('');

                            // Create wrapper for the map
                            const mapWrapper = document.createElement('div');
                            mapWrapper.id = 'map-wrapper';
                            mapWrapper.style.position = 'relative';
                            mapWrapper.style.height = '300px';
                            mapWrapper.style.marginBottom = '20px';
                            mapWrapper.style.width = '100%';

                            // Create map element and append it to the wrapper
                            const mapElement = document.createElement('div');
                            mapElement.id = 'map-detail-absensi';
                            mapElement.style.height = '100%';
                            mapElement.style.width = '100%';
                            mapWrapper.append(mapElement);

                            // Append map wrapper to modal body
                            modalBody.append(mapWrapper);

                            // Initialize map with placeholder coordinates
                            const map = L.map(mapElement).setView([0, 0], 13);
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                            }).addTo(map);

                            // Set view to actual coordinates after the map is added to the DOM
                            map.setView([latitude, longitude], 17);
                            L.marker([latitude, longitude]).addTo(map)
                                .bindPopup('Lokasi Absen')
                                .openPopup();
                        }

                        // Render images if available
                        if (attendance.foto_clock_in || attendance.foto_clock_out) {
                            const gambarDetail = document.createElement('div');
                            gambarDetail.id = 'gambar-detail-absensi';
                            gambarDetail.style.display = 'flex'; // Use flexbox to position images
                            gambarDetail.style.justifyContent = 'space-between'; // Space images evenly
                            gambarDetail.style.width = '100%'; // Ensure the container takes full width
                            modalBody.append(gambarDetail);

                            if (attendance.foto_clock_in) {
                                const imgClockIn = document.createElement('img');
                                imgClockIn.src = `/storage/${attendance.foto_clock_in}`;
                                imgClockIn.alt = 'Foto Clock In';
                                imgClockIn.className = 'img-clock'; // Use a common class for styling
                                gambarDetail.append(imgClockIn);
                            }

                            if (attendance.foto_clock_out) {
                                const imgClockOut = document.createElement('img');
                                imgClockOut.src = `/storage/${attendance.foto_clock_out}`;
                                imgClockOut.alt = 'Foto Clock Out';
                                imgClockOut.className = 'img-clock'; // Use a common class for styling
                                gambarDetail.append(imgClockOut);
                            }
                        }
                    });

                } else {
                    alert(result.message);
                }
            } catch (error) {
                console.error('Error handling detail-absensi click:', error.message);
                alert('Terjadi kesalahan saat mengambil data absensi.');
            }
        });
    </script>
@endsection
