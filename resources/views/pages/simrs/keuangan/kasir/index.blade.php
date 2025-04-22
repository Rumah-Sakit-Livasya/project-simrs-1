@extends('inc.layout')
@section('title', 'Kasir')
@section('content')
    <style>
        table {
            font-size: 8pt !important;
        }
    </style>
    <main id="js-page-content" role="main" class="page-content">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Form <span class="fw-300"><i>Pencarian</i></span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">

                            <form action="" method="post">
                                @csrf
                                <div class="row justify-content-center">
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-xl-4 text-end">
                                                    <label for="registration_date" class="form-label">Tgl.
                                                        Registrasi</label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" class="form-control" id="datepicker-1"
                                                        placeholder="Select date" name="registration_date"
                                                        value="{{ old('registration_date', '01/01/2018 - 01/15/2018') }}">
                                                    @error('registration_date')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-xl-5 text-end">
                                                    <label class="form-label" for="medical_record_number">No. RM</label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" value="{{ old('medical_record_number') }}"
                                                        class="form-control" id="medical_record_number"
                                                        name="medical_record_number" onkeyup="formatAngka(this)">
                                                    @error('medical_record_number')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-xl-5 text-end">
                                                    <label class="form-label" for="registration_name">Nama Pasien</label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" value="{{ old('name') }}" class="form-control"
                                                        id="name" name="name">
                                                    @error('name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-center mt-4">
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-xl-4 text-end">
                                                    <label for="registration_type" class="form-label">Tipe
                                                        Registrasi</label>
                                                </div>
                                                <div class="col-xl">
                                                    <select class="form-control w-100 select2" id="registration_type"
                                                        name="registration_type">
                                                        <option value=""></option>
                                                        <option value="rawat-inap"
                                                            {{ old('registration_type') == 'rawat-inap' ? 'selected' : '' }}>
                                                            Rawat Inap</option>
                                                        <option value="rawat-jalan"
                                                            {{ old('registration_type') == 'rawat-jalan' ? 'selected' : '' }}>
                                                            Rawat Jalan</option>
                                                    </select>
                                                    @error('registration_type')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-xl-5 text-end">
                                                    <label class="form-label" for="address">No Registrasi</label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" value="{{ old('address') }}" class="form-control"
                                                        id="address" name="address">
                                                    @error('address')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-xl-5 text-end">
                                                    <label class="form-label" for="departement_id">Poly/Unit</label>
                                                </div>
                                                <div class="col-xl">
                                                    <select class="select2 form-control w-100" id="departement_id"
                                                        name="departement_id">
                                                        <option value=""></option>
                                                        <option value="Rawat Inap"
                                                            {{ old('departement_id') == 'Rawat Inap' ? 'selected' : '' }}>
                                                            Rawat Inap</option>
                                                        <option value="Rawat Jalan"
                                                            {{ old('departement_id') == 'Rawat Jalan' ? 'selected' : '' }}>
                                                            Rawat Jalan</option>
                                                    </select>
                                                    @error('departement_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-end mt-3">
                                    <div class="col-xl-3">
                                        <button type="submit" class="btn btn-outline-primary waves-effect waves-themed">
                                            <span class="fal fa-search mr-1"></span>
                                            Cari
                                        </button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            List <span class="fw-300"><i>Tagihan Pasien</i></span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- datatable start -->
                            <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                <i id="loading-spinner" class="fas fa-spinner fa-spin"></i>
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>#</th>
                                        <th>Tanggal</th>
                                        <th>No. RM</th>
                                        <th>No Registrasi</th>
                                        <th>Nama Pasien</th>
                                        <th>Nama Dokter</th>
                                        <th>Poly/Ruang</th>
                                        <th>Penjamin</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tagihan_pasien as $tagihan)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <a href="{{ route('tagihan.pasien.detail', $tagihan->id) }}">
                                                    {{ tgl_waktu($tagihan->created_at) }}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('tagihan.pasien.detail', $tagihan->id) }}">
                                                    {{ $tagihan->registration->patient->medical_record_number }}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('tagihan.pasien.detail', $tagihan->id) }}">
                                                    {{ $tagihan->registration->registration_number }}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('tagihan.pasien.detail', $tagihan->id) }}">
                                                    {{ $tagihan->registration->patient->name }}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('tagihan.pasien.detail', $tagihan->id) }}">
                                                    {{ $tagihan->registration->doctor->employee->fullname }}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('tagihan.pasien.detail', $tagihan->id) }}">
                                                    {{ $tagihan->registration['registration_type'] == 'rawat-inap' ? 'RAWAT INAP' : $tagihan->registration->poliklinik }}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('tagihan.pasien.detail', $tagihan->id) }}">
                                                    {{ $tagihan->registration->penjamin->nama_perusahaan }}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('tagihan.pasien.detail', $tagihan->id) }}">
                                                    {{ $tagihan->status }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Tanggal</th>
                                        <th>No. RM</th>
                                        <th>No Registrasi</th>
                                        <th>Nama Pasien</th>
                                        <th>Nama Dokter</th>
                                        <th>Poly/Ruang</th>
                                        <th>Penjamin</th>
                                        <th>Status</th>
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
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    {{-- Select 2 --}}
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    {{-- Datepicker --}}
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    {{-- Datepicker Range --}}
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>

    <script>
        var controls = {
            leftArrow: '<i class="fal fa-angle-left" style="font-size: 1.25rem"></i>',
            rightArrow: '<i class="fal fa-angle-right" style="font-size: 1.25rem"></i>'
        }

        var runDatePicker = function() {

            // minimum setup
            $('#date_of_birth').datepicker({
                todayHighlight: true,
                orientation: "bottom left",
                templates: controls,
                defaultDate: "{{ old('date_of_birth', date('Y-m-d')) }}"
            });

        }

        $(document).ready(function() {

            // Datepciker
            runDatePicker();

            // Select 2
            $(function() {
                $('.select2').select2({
                    dropdownCssClass: "move-up"
                });
                $(".select2").on("select2:open", function() {
                    // Mengambil elemen kotak pencarian
                    var searchField = $(".select2-search__field");

                    // Mengubah urutan elemen untuk memindahkannya ke atas
                    searchField.insertBefore(searchField.prev());
                });
            });
            // Set the default date for the datepicker
            $('#datepicker-1').daterangepicker({
                opens: 'left',
                startDate: moment(
                    "{{ (old('registration_date') ? explode(' - ', old('registration_date'))[0] : request('registration_date')) ? explode(' - ', request('registration_date'))[0] : now()->format('Y-m-d') }}",
                    'YYYY-MM-DD'),
                endDate: moment(
                    "{{ (old('registration_date') ? explode(' - ', old('registration_date'))[1] : request('registration_date')) ? explode(' - ', request('registration_date'))[1] : now()->format('Y-m-d') }}",
                    'YYYY-MM-DD'),
                locale: {
                    format: 'YYYY-MM-DD',
                    separator: ' - ',
                    applyLabel: 'Pilih',
                    cancelLabel: 'Batal',
                    fromLabel: 'Dari',
                    toLabel: 'Sampai',
                    customRangeLabel: 'Custom',
                    weekLabel: 'W',
                    daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                    monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                        'September', 'Oktober', 'November', 'Desember'
                    ]
                }
            }, function(start, end, label) {
                console.log("Tanggal dipilih: " + start.format('YYYY-MM-DD') + ' sampai ' + end.format(
                    'YYYY-MM-DD'));
            });


            $('#loading-spinner').show();
            // initialize datatable
            $('#dt-basic-example').dataTable({
                "drawCallback": function(settings) {
                    // Menyembunyikan preloader setelah data berhasil dimuat
                    $('#loading-spinner').hide();
                },
                responsive: true,
                lengthChange: false,
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                        extend: 'pdfHtml5',
                        text: 'PDF',
                        titleAttr: 'Generate PDF',
                        className: 'btn-outline-danger btn-sm mr-1'
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        titleAttr: 'Generate Excel',
                        className: 'btn-outline-success btn-sm mr-1'
                    },
                    {
                        extend: 'csvHtml5',
                        text: 'CSV',
                        titleAttr: 'Generate CSV',
                        className: 'btn-outline-primary btn-sm mr-1'
                    },
                    {
                        extend: 'copyHtml5',
                        text: 'Copy',
                        titleAttr: 'Copy to clipboard',
                        className: 'btn-outline-primary btn-sm mr-1'
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        titleAttr: 'Print Table',
                        className: 'btn-outline-primary btn-sm'
                    }
                ]
            }).addClass('table-sm');

        });


        // Input RM
        function formatAngka(input) {
            var value = input.value.replace(/\D/g, '');
            var formattedValue = '';

            if (value.length > 6) {
                value = value.substr(0, 6);
            }

            if (value.length > 0) {
                formattedValue = value.match(/.{1,2}/g).join('-');
            }

            input.value = formattedValue;
        }
    </script>
@endsection
