@extends('inc.layout')
@section('title', 'Laporan Dayoff / TimeOff')
@section('extended-css')
    <style>
        @media screen and (min-width: 680px) {

            #dt-basic-example thead th:first-child,
            #dt-basic-example tbody td:first-child,
            #dt-basic-example tfoot th:first-child {
                position: sticky;
                left: 0;
                z-index: 2;
                /* Ditingkatkan agar kolom "Action" tetap di atas kolom lainnya */
                background-color: #f9f9f9;
            }

            #dt-basic-example thead th:nth-child(2),
            #dt-basic-example tbody td:nth-child(2),
            #dt-basic-example tfoot th:nth-child(2) {
                position: sticky;
                left: 62px;
                /* Sesuaikan dengan lebar kolom pertama */
                z-index: 2;
                /* Ditingkatkan agar kolom "Action" tetap di atas kolom lainnya */
                background-color: #f9f9f9;
            }


            #dt-basic-example thead th:last-child,
            #dt-basic-example tbody td:last-child,
            #dt-basic-example tfoot th:last-child {
                position: sticky;
                right: 0;
                z-index: 2;
                /* Ditingkatkan agar kolom "Action" tetap di atas kolom lainnya */
                background-color: #f9f9f9;
            }
        }

        a.link_nama:hover {
            text-decoration: underline !important;
        }
    </style>
@endsection
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row mt-2">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Daftar Day Off / Time Off <b class="text-primary"> &nbsp;( Tahun {{$currentYear ?? \Carbon\Carbon::now()->year}} )</b>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="table-responsive">
                                <!-- datatable start -->
                                <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                    <thead>
                                        <tr>
                                            <th style="white-space: nowrap">No</th>
                                            <th style="white-space: nowrap">Tanggal</th>
                                            <th style="white-space: nowrap">Status</th>
                                            <th style="white-space: nowrap">Keterangan</th>
                                            <th style="white-space: nowrap">File</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($day_off as $row)
                                            <tr>

                                                <th style="white-space: nowrap">
                                                    {{ $loop->iteration }}
                                                </th>
                                                <th style="white-space: nowrap">
                                                    {{ \Carbon\Carbon::parse($row->date)->format('d F Y') }}
                                                </th>
                                                <th style="white-space: nowrap">
                                                    {{ $row->day_off ? $row->day_off->attendance_code->code : $row->attendance_code->code }}
                                                </th>
                                                <th style="white-space: nowrap">
                                                    {{ $row->day_off->description ?? '-' }}
                                                </th>
                                                <th style="white-space: nowrap">
                                                    @if ($row->day_off->photo)
                                                        <img src="{{ asset('storage/img/pengajuan/cuti' . $row->day_off->photo) }}"
                                                            alt="Gambar Pengajuan">
                                                    @else
                                                        -
                                                    @endif
                                                </th>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th style="white-space: nowrap">No</th>
                                            <th style="white-space: nowrap">Tanggal</th>
                                            <th style="white-space: nowrap">Status</th>
                                            <th style="white-space: nowrap">Keterangan</th>
                                            <th style="white-space: nowrap">File</th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <!-- datatable end -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/statistics/chartist/chartist.js"></script>
    {{-- <script>

        /* stacked bar */
        let data = @json($attendancesAllMonths);
        // console.log(data)
        let result = [];
        Object.values(data).forEach(monthData => {
            let monthArray = [];
            Object.values(monthData).forEach(value => {
                monthArray.push(value);
            });
            result.push(monthArray);
        });
        let formattedResult = [];

        for (let i = 0; i < result[0].length; i++) {
            let columnValues = [];
            for (let j = 0; j < result.length; j++) {
                columnValues.push(result[j][i]);
            }
            formattedResult.push(columnValues);
        }
        var stackedBar = function() {
            new Chartist.Bar('#stackedBar', {
                labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September',
                    'Oktober', 'November', 'Desember'
                ],
                series: formattedResult
            }, {
                stackBars: true,
                axisY: {
                    labelInterpolationFnc: function(value) {
                        return (value);
                    }
                }
            }).on('draw', function(data) {
                if (data.type === 'bar') {
                    data.element.attr({
                        style: 'stroke-width: 30px'
                    });
                }
            });
        }
        /* stacked bar -- end */
    </script> --}}

    <script>
        /* demo scripts for change table color */
        /* change background */
        $(document).ready(function() {
            // stackedBar();
            $('.btn-edit').click(function(e) {
                e.preventDefault();
                let button = $(this);
                let id = button.attr('data-id');
                button.find('.ikon-edit').hide();
                button.find('.spinner-text').removeClass('d-none');

                $.ajax({
                    type: "GET", // Method pengiriman data bisa dengan GET atau POST
                    url: `/api/dashboard/attendances/${id}`, // Isi dengan url/path file php yang dituju

                    dataType: "json",
                    success: function(data) {
                        button.find('.ikon-edit').show();
                        button.find('.spinner-text').addClass('d-none');
                        $('#ubah-data').modal('show');
                        $('#update-form').attr('data-id', data.attendance.id);
                        $('#ubah-data #date').val(data.attendance.date);
                        $('#ubah-data #clock_in').val(data.attendance.clock_in);
                        $('#ubah-data #clock_out').val(data.attendance.clock_out);
                        $('#ubah-data #shift_id').val(data.attendance.shift_id).select2({
                            dropdownParent: $('#ubah-data')
                        });

                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });

                $('#update-form').on('submit', function(e) {
                    e.preventDefault();
                    let formData = $(this).serialize();
                    const id = $(this).attr('data-id');
                    $.ajax({
                        type: "PUT",
                        url: '/api/dashboard/attendances/update/' + id,
                        data: formData,
                        beforeSend: function() {
                            $('#update-form').find('.ikon-edit').hide();
                            $('#update-form').find('.spinner-text')
                                .removeClass(
                                    'd-none');
                        },
                        success: function(response) {
                            $('#ubah-data').modal('hide');
                            showSuccessAlert(response.message);
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        },
                        error: function(xhr) {
                            console.log(xhr.responseJSON.error);
                        }
                    });
                });
            });
            // $('#store-form').on('submit', function(e) {
            //     e.preventDefault();
            //     let formData = $(this).serialize();
            //     $.ajax({
            //         type: "POST",
            //         url: '/api/dashboard/banks/store/',
            //         data: formData,
            //         beforeSend: function() {
            //             $('#store-form').find('.ikon-tambah').hide();
            //             $('#store-form').find('.spinner-text').removeClass(
            //                 'd-none');
            //         },
            //         success: function(response) {
            //             $('#store-form').find('.ikon-edit').show();
            //             $('#store-form').find('.spinner-text').addClass('d-none');
            //             $('#tambah-data').modal('hide');
            //             showSuccessAlert(response.message)
            //             setTimeout(function() {
            //                 location.reload();
            //             }, 500);
            //         },
            //         error: function(xhr) {
            //             console.log(xhr.responseText);
            //         }
            //     });
            // });

            // $('.btn-hapus').click(function(e) {
            //     e.preventDefault();
            //     let button = $(this);
            //     alert('Yakin ingin menghapus ini ?');
            //     let id = button.attr('data-id');
            //     $.ajax({
            //         type: "GET",
            //         url: '/api/dashboard/banks/delete/' + id,
            //         beforeSend: function() {
            //             button.find('.ikon-hapus').hide();
            //             button.find('.spinner-text').removeClass(
            //                 'd-none');
            //         },
            //         success: function(response) {
            //             button.find('.ikon-edit').show();
            //             button.find('.spinner-text').addClass('d-none');
            //             showSuccessAlert(response.message)
            //             setTimeout(function() {
            //                 location.reload();
            //             }, 500);
            //         },
            //         error: function(xhr) {
            //             console.log(xhr.responseText);
            //         }
            //     });
            // });

            $('#dt-basic-example').dataTable({
                "pageLength": 30,
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'B>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                        extend: 'excelHtml5',
                        text: 'Excel',
                        title: 'Rekap Cuti',
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
