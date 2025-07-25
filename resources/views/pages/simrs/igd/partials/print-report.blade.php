@extends('inc.layout-no-side')
@section('title', 'Laporan Pasien IGD')
@section('content')
    <div class="container-fluid mt-4">
        <!-- START LOOPING: button -->
        <div class="mb-3 d-flex justify-content-end gap-2">
            <button onclick="window.print()" class="btn btn-primary btn-sm">
                <i class="fas fa-print me-1"></i> Print
            </button>
            <button onclick="window.close()" class="btn btn-danger btn-sm">
                <i class="fas fa-times me-1"></i> Close
            </button>
        </div>
        <!-- END LOOPING: button -->

        <!-- B: Print View -->
        <div id="previews">
            <div class="border-bottom mb-4">
                <h4 class="text-center fw-bold">LAPORAN PASIEN UGD</h4>
                <p class="text-center mb-1">
                    PERIODE: <strong>{{ \Carbon\Carbon::parse($from)->format('d-m-Y') }}</strong>
                    s/d <strong>{{ \Carbon\Carbon::parse($to)->format('d-m-Y') }}</strong>
                </p>
                <p class="text-center mb-1">Unit Layanan: <strong>UGD</strong></p>
                <p class="text-center mb-1">Dokter: <strong>{{ $dokterName }}</strong></p>
                <p class="text-center mb-1">Penjamin: <strong>{{ $penjaminName }}</strong></p>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-sm align-middle text-center w-100"
                    id="dt-basic-example">
                    <thead class="table-light">
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Tanggal Registrasi</th>
                            <th rowspan="2">No. Reg</th>
                            <th colspan="2">No. RM</th>
                            <th rowspan="2">Nama Pasien</th>
                            <th rowspan="2">JK</th>
                            <th rowspan="2">Umur / Tahun</th>
                            <th rowspan="2">Alamat</th>
                            <th rowspan="2">No. Telp</th>
                            <th rowspan="2">Poli</th>
                            <th rowspan="2">Diagnosa</th>
                            <th rowspan="2">Dokter</th>
                            <th rowspan="2">Penjamin</th>
                            <th rowspan="2">Perujuk</th>
                            <th rowspan="2">Petugas</th>
                            <th rowspan="2">Status Pasien</th>
                        </tr>
                        <tr>
                            <th>Baru</th>
                            <th>Lama</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider table-light">
                        @foreach ($pasien as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ tgl_waktu($item->registration_date) }}</td>
                                <td>{{ $item->registration_number }}</td>
                                <td>{{ $item->is_new ? $item->patient->medical_record_number : '' }}</td>
                                <td>{{ $item->is_new ? '' : $item->patient->medical_record_number }}</td>
                                <td class="text-start">{{ $item->patient->name }}</td>
                                <td>{{ strtoupper($item->patient->gender) }}</td>
                                <td>{{ displayAge($item->patient->date_of_birth) }}</td>
                                <td class="text-start">{{ strtoupper($item->patient->address) }}</td>
                                <td> - </td>
                                <td>{{ $item->registration_type === 'igd' ? 'UGD' : '' }}</td>
                                <td class="text-start">
                                    @foreach ($item->cppt as $cppt)
                                        @php
                                            $diagnosaKerjaList = [];

                                            if ($cppt->assesment) {
                                                // Ambil semua kemunculan Diagnosa Kerja
                                                preg_match_all(
                                                    '/diagnosa\s*kerja\s*:\s*(.*?)(\r?\n|Diagnosa\s+\w+\s*:|$)/si',
                                                    $cppt->assesment,
                                                    $matches,
                                                );

                                                if (!empty($matches[1])) {
                                                    foreach ($matches[1] as $match) {
                                                        $diagnosaKerjaList[] = trim($match);
                                                    }
                                                }
                                            }
                                        @endphp

                                        @if (count($diagnosaKerjaList) > 0)
                                            <ul class="mb-0 ps-3">
                                                @foreach ($diagnosaKerjaList as $diagnosa)
                                                    <li style="list-style: none">{{ $diagnosa }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            -
                                        @endif
                                    @endforeach
                                </td>

                                <td class="text-start">{{ $item->doctor->employee->fullname }}</td>
                                <td class="text-start">{{ $item->penjamin->nama_perusahaan }}</td>
                                <td>{{ strtoupper($item->rujukan) }}</td>
                                <td class="text-start">{{ $item->user->employee->fullname }}</td>
                                <td>{{ strtoupper($item->status) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>

    <script>
        $(document).ready(function() {
            $('#dt-basic-example').DataTable({
                responsive: true,
                // scrollY: 400,
                // scrollX: true,
                // scrollCollapse: true,
                // paging: true,
                pageLength: 200,
                //fixedColumns: true,
                fixedColumns: {
                    leftColumns: 2,
                },
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'B>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                        extend: 'colvis',
                        text: '<i class="fas fa-eye"></i> Visibility',
                        titleAttr: 'Col visibility',
                        className: 'btn-primary'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Print',
                        titleAttr: 'Print Table',
                        className: 'btn-primary',
                        exportOptions: {
                            columns: ':visible' // Menggunakan kolom yang terlihat sesuai pengaturan ColVis
                        },
                        customize: function(win) {
                            $(win.document.body).find('table').addClass('display').css('font-size',
                                '9px'); // Menambahkan kelas dan menyesuaikan ukuran font
                            $(win.document.body).find('thead').addClass(
                                'thead-light'); // Menambahkan kelas untuk style header
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        titleAttr: 'Export to Excel',
                        className: 'btn-primary',
                        exportOptions: {
                            columns: ':visible' // Menggunakan kolom yang terlihat sesuai pengaturan ColVis
                        }
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
