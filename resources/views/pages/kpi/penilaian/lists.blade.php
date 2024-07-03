@extends('inc.layout')
@section('title', 'KPI - List Penilaian')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div class="card pt-2">
                    <div class="card-body row">
                        <div class="col-xl-5 col-sm-4 mb-2">
                            @php
                                $currentMonth = \Carbon\Carbon::now()->month;

                                // Mendefinisikan array periode
                                $periodes = [
                                    1 => 'Januari - Maret',
                                    4 => 'April - Juni',
                                    7 => 'Juli - September',
                                    10 => 'Oktober - Desember',
                                ];

                                // Mengambil periode berdasarkan bulan saat ini
                                foreach ($periodes as $startMonth => $periode) {
                                    if ($currentMonth >= $startMonth && $currentMonth <= $startMonth + 2) {
                                        $selectedPeriode = $periode;
                                        break;
                                    }
                                }
                            @endphp
                            <h5 class="font-weight-bold text-primary"> Periode {{ $selectedPeriode }}</h5>
                            <span style="font-size: 1.1em">Semua hasil penilaian bisa dilihat pada menu dibawah ini.
                            </span>
                        </div>
                        <div class="col-xl-2 col-sm-2 mb-2">
                            <span class="title-sm d-inline-block mb-2 font-weight-bold text-primary">Sangat Baik</span>
                            <h1 style="font-size: 2em">
                                {{ isset($sangat_baik) ? $sangat_baik->where('periode', $selectedPeriode)->count() : '0' }}
                            </h1>
                        </div>
                        <div class="col-xl-1 col-sm-1 mb-2">
                            <span class="title-sm d-inline-block mb-2 font-weight-bold text-primary">Baik</span>
                            <h1 style="font-size: 2em">
                                {{ isset($baik) ? $baik->where('periode', $selectedPeriode)->count() : '0' }}</h1>
                        </div>
                        <div class="col-xl-1 col-sm-1 mb-2">
                            <span class="title-sm d-inline-block mb-2 font-weight-bold text-primary">Cukup</span>
                            <h1 style="font-size: 2em">
                                {{ isset($cukup) ? $cukup->where('periode', $selectedPeriode)->count() : '0' }}</h1>
                        </div>
                        <div class="col-xl-1 col-sm-2 mb-2">
                            <span class="title-sm d-inline-block mb-2 font-weight-bold text-primary">Kurang</span>
                            <h1 style="font-size: 2em">
                                {{ isset($kurang) ? $kurang->where('periode', $selectedPeriode)->count() : '0' }}</h1>
                        </div>
                        <div class="col-xl-2 col-sm-2 mb-2">
                            <span class="title-sm d-inline-block mb-2 font-weight-bold text-primary">Sangat Kurang</span>
                            <h1 style="font-size: 2em">
                                {{ isset($sangat_kurang) ? $sangat_kurang->where('periode', $selectedPeriode)->count() : '0' }}
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-xl-6 col-sm-12">
                <div id="panel-4" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Grafik Rekap Penilaian
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="panel-tag">
                                Rekap Penilaian Tahun {{ \Carbon\Carbon::now()->translatedFormat('Y') }}
                            </div>
                            <div id="splilneLine" style="width:100%; height:350px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-sm-12">
                <div id="panel-10" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Grafik Ranking Penilaian
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="panel-tag">
                                Top 5 Pegawai Penilaian Terbaik
                            </div>
                            <div id="donutChart" style="width:100%; height:350px;"></div>
                            {{-- <div class="text-right">
                                <button id="donutChartUnload" onclick="donutChartUnload();"
                                    class="btn btn-sm btn-dark ml-auto">Unload Data</button>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Daftar Penilaian
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- datatable start -->
                            <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                <thead>
                                    <tr>
                                        <th style="white-space: nowrap">No</th>
                                        <th style="white-space: nowrap">Pegawai</th>
                                        <th style="white-space: nowrap">Form Group</th>
                                        <th style="white-space: nowrap">Periode</th>
                                        <th style="white-space: nowrap">Tahun</th>
                                        <th style="white-space: nowrap">Total Nilai</th>
                                        <th style="white-space: nowrap">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rekap_penilaian as $row)
                                        <tr>
                                            <td style="white-space: nowrap">{{ $loop->iteration }}</td>
                                            <td style="white-space: nowrap">
                                                <a
                                                    href="{{ route('kpi.show.penilaian.bulanan', [$row->group_penilaian_id, $row->employee_id, $row->periode, $row->tahun]) }}">
                                                    {{ \Str::limit($row->employee->fullname, 25) }}
                                                </a>
                                            </td>
                                            <td style="white-space: nowrap">{{ $row->group_penilaian->nama_group ?? '-' }}
                                            </td>
                                            <td style="white-space: nowrap">{{ $row->periode }}</td>
                                            <td style="white-space: nowrap">{{ $row->tahun }}</td>
                                            <td style="white-space: nowrap">{{ $row->total_nilai }}</td>
                                            <td style="white-space: nowrap">{{ $row->keterangan }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th style="white-space: nowrap">No</th>
                                        <th style="white-space: nowrap">Pegawai</th>
                                        <th style="white-space: nowrap">Form Group</th>
                                        <th style="white-space: nowrap">Periode</th>
                                        <th style="white-space: nowrap">Tahun</th>
                                        <th style="white-space: nowrap">Total Nilai</th>
                                        <th style="white-space: nowrap">Keterangan</th>
                                    </tr>
                                </tfoot>
                            </table>
                            <!-- datatable end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <!-- dependency for c3 charts : this dependency is a BSD license with clause 3 -->
    <script src="/js/statistics/d3/d3.js"></script>
    <!-- c3 charts : MIT license -->
    <script src="/js/statistics/c3/c3.js"></script>
    <script src="/js/statistics/demo-data/demo-c3.js"></script>
    <script>
        let tes = @json($sangat_baik->where('periode', 'Januari - Maret')->count());
        console.log(tes);
        var colors = [myapp_get_color.success_500, myapp_get_color
            .primary_500, myapp_get_color.info_500, myapp_get_color.warning_500, myapp_get_color.danger_500
        ];
        var splilneLine = c3.generate({
            bindto: "#splilneLine",
            data: {
                columns: [
                    [
                        'Sangat Baik',
                        @json($sangat_baik->where('periode', 'Januari - Maret')->count()),
                        @json($sangat_baik->where('periode', 'April - Juni')->count()),
                        @json($sangat_baik->where('periode', 'Juli - September')->count()),
                        @json($sangat_baik->where('periode', 'Oktober - Desember')->count())
                    ],
                    [
                        'Baik',
                        @json($baik->where('periode', 'Januari - Maret')->count()),
                        @json($baik->where('periode', 'April - Juni')->count()),
                        @json($baik->where('periode', 'Juli - September')->count()),
                        @json($baik->where('periode', 'Oktober - Desember')->count())
                    ],
                    [
                        'Cukup',
                        @json($cukup->where('periode', 'Januari - Maret')->count()),
                        @json($cukup->where('periode', 'April - Juni')->count()),
                        @json($cukup->where('periode', 'Juli - September')->count()),
                        @json($cukup->where('periode', 'Oktober - Desember')->count())
                    ],
                    [
                        'Kurang',
                        @json($kurang->where('periode', 'Januari - Maret')->count()),
                        @json($kurang->where('periode', 'April - Juni')->count()),
                        @json($kurang->where('periode', 'Juli - September')->count()),
                        @json($kurang->where('periode', 'Oktober - Desember')->count())
                    ],
                    [
                        'Sangat Kurang',
                        @json($sangat_kurang->where('periode', 'Januari - Maret')->count()),
                        @json($sangat_kurang->where('periode', 'April - Juni')->count()),
                        @json($sangat_kurang->where('periode', 'Juli - September')->count()),
                        @json($sangat_kurang->where('periode', 'Oktober - Desember')->count())
                    ],
                ],
                type: 'spline'
            },
            color: {
                pattern: colors
            },
            axis: {
                x: {
                    type: 'category',
                    categories: ['Januari - Maret', 'April - Juni', 'Juli - September', 'Oktober - Desember']
                }
            }
        });

        var donutChart = c3.generate({
            bindto: "#donutChart",
            data: {
                // iris data from R
                columns: @json($top_5_pegawai),
                type: 'donut' //,
                /*onclick: function (d, i) { console.log("onclick", d, i); },
                onmouseover: function (d, i) { console.log("onmouseover", d, i); },
                onmouseout: function (d, i) { console.log("onmouseout", d, i); }*/
            },
            donut: {
                title: "Penilaian Terbaik"
            },
            color: {
                pattern: colors
            },
            tooltip: {
                format: {
                    value: function(value, ratio, id, index) {
                        // Mengembalikan nilai aktual daripada persentase
                        return value;
                    }
                }
            }
        });
        // function btnEdit(event) {
        //     event.preventDefault();
        //     let button = event.currentTarget;
        //     let id = button.getAttribute('data-id');
        //     let ikonEdit = button.querySelector('.ikon-edit');
        //     let spinnerText = button.querySelector('.spinner-text');
        //     ikonEdit.classList.add('d-none');
        //     spinnerText.classList.remove('d-none');
        //     // button.find('.ikon-edit').hide();
        //     // button.find('.spinner-text').removeClass('d-none');

        //     $.ajax({
        //         type: "GET", // Method pengiriman data bisa dengan GET atau POST
        //         url: `/api/dashboard/location/get/${id}`, // Isi dengan url/path file php yang dituju
        //         dataType: "json",
        //         success: function(data) {
        //             ikonEdit.classList.remove('d-none');
        //             ikonEdit.classList.add('d-block');
        //             spinnerText.classList.add('d-none');
        //             // button.find('.ikon-edit').show();
        //             // button.find('.spinner-text').addClass('d-none');
        //             $('#ubah-data').modal('show');
        //             $('#ubah-data #name').val(data.name);
        //             $('#ubah-data #latitude').val(data.latitude);
        //             $('#ubah-data #longitude').val(data.longitude);
        //         },
        //         error: function(xhr) {
        //             console.log(xhr.responseText);
        //         }
        //     });


        //     $('#update-form').on('submit', function(e) {
        //         e.preventDefault();
        //         let formData = $(this).serialize();
        //         $.ajax({
        //             type: "POST",
        //             url: '/api/dashboard/location/update/' + id,
        //             data: formData,
        //             beforeSend: function() {
        //                 $('#update-form').find('.ikon-edit').hide();
        //                 $('#update-form').find('.spinner-text').removeClass(
        //                     'd-none');
        //             },
        //             success: function(response) {
        //                 $('#ubah-data').modal('hide');
        //                 showSuccessAlert(response.message)
        //                 setTimeout(function() {
        //                     location.reload();
        //                 }, 500);
        //             },
        //             error: function(xhr) {
        //                 console.log(xhr.responseText);
        //             }
        //         });
        //     });
        // }

        // function btnDelete(event) {
        //     event.preventDefault();
        //     let button = event.currentTarget;
        //     alert('Yakin ingin menghapus ini ?');
        //     let id = button.getAttribute('data-id');
        //     let ikonHapus = button.querySelector('.ikon-hapus');
        //     let spinnerText = button.querySelector('.spinner-text');
        //     $.ajax({
        //         type: "GET",
        //         url: '/api/dashboard/location/delete/' + id,
        //         beforeSend: function() {
        //             ikonHapus.classList.add('d-none');
        //             spinnerText.classList.remove('d-none');
        //             // button.find('.ikon-hapus').hide();
        //             // button.find('.spinner-text').removeClass(
        //             //     'd-none');
        //         },
        //         success: function(response) {
        //             ikonHapus.classList.remove('d-none');
        //             ikonHapus.classList.add('d-block');
        //             spinnerText.classList.add('d-none');
        //             showSuccessAlert(response.message)
        //             setTimeout(function() {
        //                 location.reload();
        //             }, 1000);
        //         },
        //         error: function(xhr) {
        //             console.log(xhr.responseText);
        //         }
        //     });
        // }

        $(document).ready(function() {

            // $('#store-form').on('submit', function(e) {
            //     e.preventDefault();
            //     let formData = $(this).serialize();
            //     $.ajax({
            //         type: "POST",
            //         url: '/api/dashboard/location/store',
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

            // $(function() {
            //     $('.select2').select2({
            //         placeholder: 'Pilih Data Berikut',
            //         dropdownParent: $('#tambah-lokasi')
            //     });
            // });

            // Datatable
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
