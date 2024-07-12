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
                <button class="btn btn-primary mb-3" id="tambah-absensi">Tambah Absensi</button>
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
                                            <th style="white-space: nowrap">Nama</th>
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
                                                <td>{{ $row->employee->fullname }}</td>
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
                                            <th style="white-space: nowrap">Nama</th>
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
    @include('pages.absensi.absensi.partials.tambah')
@endsection
@section('plugin')
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                dropdownParent: $('#tambah-absensi-modal')
            });

            $('#tambah-absensi').click(function() {
                $('#tambah-absensi-modal').modal('show')
            })

            $('#tambah-absensi-form').on('submit', function(event) {
                event.preventDefault(); // Mencegah form dikirim secara default
                let button = $(this);
                button.find('.ikon-edit').hide();
                button.find('.spinner-text').removeClass('d-none');
                async function submitForm() {
                    try {
                        // Mengambil data form
                        const formData = new FormData(this);

                        // Mengirimkan data menggunakan fetch API
                        const response = await fetch('/attendances/outsource/store', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                        const result = await response.json();

                        if (response.ok) {
                            $('#tambah-absensi-modal').modal('hide');
                            button.find('.ikon-edit').show();
                            button.find('.spinner-text').addClass('d-none');

                            showSuccessAlert(result.success);
                            setTimeout(function() {
                                console.log('Reloading the page now.');
                                window.location.reload();
                            }, 1000);

                        } else {
                            $('#tambah-absensi-modal').modal('hide');
                            button.find('.ikon-edit').show();
                            button.find('.spinner-text').addClass('d-none');
                            showErrorAlert(result.error);
                        }
                    } catch (error) {
                        $('#tambah-absensi-modal').modal('hide');
                        showErrorAlert(error.message || 'An error occurred');
                    }
                }

                // Memanggil fungsi async untuk submit form
                submitForm.call(this);
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
        });
    </script>
@endsection
