@extends('inc.layout')
@section('title', 'Live Attendace')
@section('extended-css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        .video-container {
            position: relative;
            padding-top: 100%;
            /* 16:9 Aspect Ratio */
        }

        .video-container video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transform: scaleX(-1);
            /* Membalik video secara horizontal */
        }

        @media (max-width: 576px) {
            .modal-dialog {
                margin: 0;
                width: 100%;
                max-width: 100%;
                height: 100%;
                max-height: 100%;
            }

            .modal-content {
                height: 100%;
                max-height: 100%;
                border-radius: 0;
            }

            .modal-body {
                overflow-y: auto;
            }

            .video-container {
                position: relative;
                width: 100%;
                padding-top: 100%;
                /* 16:9 Aspect Ratio */
            }

            .video-container video {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                object-fit: cover;
                transform: scaleX(-1);
                /* Membalik video secara horizontal */
            }
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
                            Live Attendance </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div id="map" style="width:640;height:255px;"></div>
                            <div class="time-attendance row justify-content-center mb-2" style="color: #666666 !important;">
                                <span class="mt-4 col-md-12 text-center"
                                    style="font-size:1.2em">{{ \Carbon\Carbon::now()->translatedFormat('l, j F Y') }}</span>
                                <h2 class="col-md-12 mt-2 text-center" id="waktu-realtime">
                                    {{ \Carbon\Carbon::now()->translatedFormat('H:i:s') }}</h2>
                                <div class="attendance-btn mt-2">
                                    <button class="btn btn-primary btn-sm btn-clock-in mr-1" id="clock_in">
                                        Clock
                                        In</button>
                                    <button class="btn btn-danger btn-sm btn-clock-in" id="clock_out">
                                        <span class="spinner-border spinner-text spinner-border-sm mr-1 d-none"
                                            role="status" aria-hidden="true"></span>
                                        Clock Out</button>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12">
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
                                            <th style="white-space: nowrap">Tipe</th>
                                            <th style="white-space: nowrap">Waktu</th>
                                            <th style="white-space: nowrap">Foto</th>
                                            <th style="white-space: nowrap">Lokasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($attendances as $row)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td style="white-space: nowrap">
                                                    {{ \Carbon\Carbon::parse($row->date)->translatedFormat('j F Y') }}
                                                </td>
                                                <td style="white-space: nowrap">
                                                    {{ $row->attendance_code == 1 ? 'Clock In' : 'Clock Out' }}
                                                </td>
                                                <td style="white-space: nowrap">
                                                    {{ $row->time }}
                                                </td>
                                                <td style="white-space: nowrap">
                                                    @if ($row->image)
                                                        <img src="{{ asset('/storage/img/absen/outsource/' . $row->image) }}"
                                                            style="height: 200px !important; width: 200px; object-fit: cover; object-position: center;">
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td style="white-space: nowrap">
                                                    {{ $row->location }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th style="white-space: nowrap">No</th>
                                            <th style="white-space: nowrap">Tanggal</th>
                                            <th style="white-space: nowrap">Tipe</th>
                                            <th style="white-space: nowrap">Waktu</th>
                                            <th style="white-space: nowrap">Foto</th>
                                            <th style="white-space: nowrap">Lokasi</th>
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
    @include('pages.absensi.absensi.partials.clockin-modal')
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

        let latitude = null;
        let longitude = null;

        // Check if Geolocation is available
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;
                latitude = lat;
                longitude = lng;
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
        $(document).ready(function() {

            $('#clock_in').on('click', function(e) {
                e.preventDefault();
                console.log("click");
                $('#clockin-modal').modal('show');
            });

            $('#dt-basic-example').dataTable({
                responsive: false,
                "pageLength": 5,
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'B>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                        extend: 'excelHtml5',
                        text: 'Excel',
                        title: 'Rekap Absensi Bulan ' + new Date().toLocaleString('default', {
                            month: 'long',
                        }) + ' ' + new Date().getFullYear(),
                        titleAttr: 'Export to Excel',
                        className: 'btn-outline-default',
                        exportOptions: {
                            columns: ':visible',
                            format: {
                                body: function(data, row, column, node) {
                                    // Menghapus tag HTML dari data sebelum mengekspor ke Excel
                                    return $('<div/>').html(data).text();
                                }
                            }
                        },
                        customize: function(xlsx) {
                            var sheet = xlsx.xl.worksheets['sheet1.xml'];
                            $('row:first c', sheet).attr('style',
                                'text-align: center;'
                            ); // Mengatur gaya untuk heading
                            // $('row c', sheet).attr('s', '25'); // Memberikan border pada sel
                            $('row:nth-child(2) c', sheet).attr('s', '43');
                            $('row:nth-child(2) c', sheet).attr('class', 'style43');

                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        titleAttr: 'Print Table',
                        className: 'btn-outline-default'
                    }
                ]
            });

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

    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const context = canvas.getContext('2d');
        const snap = document.getElementById('snap');
        const uploadButton = document.getElementById('upload');
        const clock_out = document.getElementById('clock_out');

        function toggleSpinner(buttonId, show) {
            const button = document.getElementById(buttonId);
            const spinner = button.querySelector('.spinner-border');
            if (show) {
                spinner.classList.remove('d-none');
            } else {
                spinner.classList.add('d-none');
            }
        }

        async function startCamera() {
            try {
                const constraints = {
                    video: {
                        width: {
                            ideal: 640
                        },
                        height: {
                            ideal: 720
                        }
                    }
                };
                const stream = await navigator.mediaDevices.getUserMedia(constraints);
                const video = document.getElementById('video');
                video.srcObject = stream;
            } catch (error) {
                console.error('Error accessing the camera:', error);
            }
        }

        // snap.addEventListener('click', () => {

        // });

        uploadButton.addEventListener('click', async () => {
            // Disable the button to prevent multiple submissions
            uploadButton.disabled = true;

            toggleSpinner('upload', true);
            context.scale(-1, 1);
            context.drawImage(video, -canvas.width, 0, 640, 480);
            const dataURL = canvas.toDataURL('image/png');
            const formData = new FormData();
            const location = latitude + ", " + longitude;
            formData.append('image', dataURL);
            formData.append('location', location);
            formData.append('latitude', latitude);
            formData.append('longitude', longitude);

            try {
                const response = await fetch('/attendances/outsource', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const result = await response.json();
                if (response.ok) {
                    console.log('Success:', result);
                    $('#clockin-modal').modal('hide');
                    showSuccessAlert(response.message);
                    setTimeout(function() {
                        console.log('Reloading the page now.');
                        window.location.reload();
                    }, 1000);

                } else {
                    $('#clockin-modal').modal('hide');
                    showErrorAlert(result.error);
                }
            } catch (error) {
                showErrorAlert(error.error);
            } finally {
                // Re-enable the button after process completion
                toggleSpinner('upload', false);
                uploadButton.disabled = false;
            }
        });


        clock_out.addEventListener('click', async () => {
            toggleSpinner('clock_out', true);
            const formData = new FormData();
            const location = latitude + ", " + longitude;
            formData.append('location', location);
            formData.append('latitude', latitude);
            formData.append('longitude', longitude);

            $('#clock_in').prop('disabled', true);
            $('#clock_in').find('.spinner-text').removeClass('d-none');
            try {
                const response = await fetch('/outsource/attendances/clock_out', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const result = await response.json();
                if (response.ok) {
                    toggleSpinner('clock_out', false);
                    showSuccessAlert(response.message);
                    setTimeout(function() {
                        console.log('Reloading the page now.');
                        window.location.reload();
                    }, 1000);

                } else {
                    console.error('Error:', result);
                    $('#clockin-modal').modal('hide');
                    showErrorAlert(result.error);
                }
            } catch (error) {
                showErrorAlert(error.error);
            }
        });

        startCamera();
    </script>
@endsection
