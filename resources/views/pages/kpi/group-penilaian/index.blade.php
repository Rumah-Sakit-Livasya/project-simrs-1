@extends('inc.layout')
@section('title', 'Live Attendace')
@section('extended-css')
    <style>
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
            /* Merah dengan opacity 50% */
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

        .badge.pos-top.pos-right.dashboard-report {}
    </style>
@endsection
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row mb-5">
            <div class="col-xl-12">
                <a type="button" href="{{ route('kpi.tbh.form-penilaian') }}"
                    class="btn btn-primary waves-effect waves-themed" title="Tambah Penilaian">
                    <span class="fal fa-plus-circle mr-1"></span>
                    Tambah Group Form Penilaian
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Tabel Group Form Penilaian
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- datatable start -->
                            <table id="Penilaian" class="table table-bordered table-hover table-striped w-100">
                                <thead>
                                    <tr>
                                        {{-- <th style="white-space: nowrap">Foto</th> --}}
                                        <th style="white-space: nowrap">No</th>
                                        <th style="white-space: nowrap">Nama Group</th>
                                        <th style="white-space: nowrap">Penilai</th>
                                        <th style="white-space: nowrap">Pejabat</th>
                                        <th style="white-space: nowrap">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($group_penilaian as $row)
                                        <tr>
                                            {{-- <td style="white-space: nowrap">{{ $user->template_user->foto }}</td> --}}
                                            <td style="white-space: nowrap">{{ $loop->iteration }}</td>
                                            <td style="white-space: nowrap">
                                                <a
                                                    href="{{ route('kpi.tbh.penilaian', $row->id) }}">{{ $row->nama_group }}</a>
                                            </td>
                                            <td style="white-space: nowrap">{{ $row->employee_penilai->fullname }}</td>
                                            <td style="white-space: nowrap">
                                                {{ $row->employee_pejabat_penilai->fullname }}
                                            </td>
                                            <td style="white-space: nowrap">
                                                <a type="button" href="{{ route('kpi.edit.group-penilaian', $row->id) }}"
                                                    class="badge mx-1 btn-edit badge-primary p-2 border-0 text-white"
                                                    title="Ubah">
                                                    <span class="fal fa-pencil ikon-edit"></span>
                                                </a>
                                                <button type="button" data-backdrop="static" data-keyboard="false"
                                                    class="badge mx-1 badge-success p-2 border-0 text-white btn-hapus"
                                                    data-id="{{ $row->id }}" title="Hapus" onclick="btnDelete(event)">
                                                    <span class="fal fa-trash ikon-hapus"></span>
                                                    <div class="span spinner-text d-none">
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span>
                                                        Loading...
                                                    </div>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th style="white-space: nowrap">No</th>
                                        <th style="white-space: nowrap">Nama Group</th>
                                        <th style="white-space: nowrap">Penilai</th>
                                        <th style="white-space: nowrap">Pejabat</th>
                                        <th style="white-space: nowrap">Aksi</th>
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
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        function btnDelete(event) {
            event.preventDefault();
            let button = event.currentTarget;
            confirm('Yakin ingin menghapus ini ?');
            let id = button.getAttribute('data-id');
            let ikonHapus = button.querySelector('.ikon-hapus');
            let spinnerText = button.querySelector('.spinner-text');
            $.ajax({
                type: "GET",
                url: `/api/dashboard/kpi/group_penilaian/${id}/delete`,
                beforeSend: function() {
                    ikonHapus.classList.add('d-none');
                    spinnerText.classList.remove('d-none');
                },
                success: function(response) {
                    ikonHapus.classList.remove('d-none');
                    ikonHapus.classList.add('d-block');
                    spinnerText.classList.add('d-none');
                    showSuccessAlert(response.message)
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                },
                error: function(xhr) {
                    showErrorAlert(xhr.responseText);
                }
            });
        }
        $('#Penilaian').dataTable({
            responsive: true, // not compatible,
            dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'B>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [{
                    extend: 'colvis',
                    text: 'Column Visibility',
                    titleAttr: 'Col visibility',
                    className: 'btn-outline-default'
                },
                {
                    extend: 'excelHtml5', // Menggunakan 'excelHtml5' untuk ekspor Excel
                    text: 'Excel', // Mengubah teks tombol menjadi 'Excel'
                    title: 'Daftar Pegawai',
                    titleAttr: 'Export to Excel', // Mengubah atribut judul tombol
                    className: 'btn-outline-default', // Mengatur kelas tombol
                    exportOptions: {
                        columns: ':not(.no-print)',
                        customize: function(xlsx) {
                            var sheet = xlsx.xl.worksheets['sheet1.xml'];
                            // Menambahkan gaya border ke setiap sel
                            $('row c', sheet).css('border', '1px solid black');
                        }
                    }
                },
                {
                    extend: 'print',
                    text: 'Print',
                    titleAttr: 'Print Table',
                    title: 'Daftar Pegawai',
                    className: 'btn-outline-default',
                    exportOptions: {
                        columns: ':not(.no-print)',
                        scale: 0.54, // Atur skala menjadi 50%
                        customize: function(win) {
                            $(win.document.body)
                                .find('table')
                                .addClass('table')
                                .css('margin', '10px'); // Atur margin sebesar 10px
                            $(win.document.body).find('table').css('transform',
                                'rotate(90deg)'); // Putar tabel 90 derajat (mode landscape)
                            $(win.document.body).find('table').css('width',
                                '100%'); // Menyesuaikan lebar tabel dengan ukuran kertas
                            $(win.document.body).find('table').css('font-size',
                                '10pt'
                            ); // Menyesuaikan ukuran font agar sesuai dengan ukuran kertas
                        }
                    },
                    customize: function(win) {
                        $(win.document.body).css('margin',
                            '10px'); // Atur margin sebesar 10px untuk seluruh halaman
                        $(win.document.body).css('transform',
                            'rotate(0deg)'); // Kembalikan orientasi halaman ke potrait
                    }
                }

            ]
        });
    </script>
@endsection
