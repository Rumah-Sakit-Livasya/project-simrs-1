@extends('inc.layout')
@section('title', 'Daftar Registrasi Pasien')
@section('content')
    <style>
        table {
            font-size: 8pt !important;
        }

        .active_parameter {}
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

                            <form action="/daftar-registrasi-pasien" method="get">
                                @csrf
                                <div class="row justify-content-center">
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label for="registration_date">Tgl. Registrasi</label>
                                                </div>
                                                <div class="col-xl">
                                                    <div class="form-group row">
                                                        <div class="col-xl ">
                                                            <input type="text" class="form-control" id="datepicker-1"
                                                                style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                                placeholder="Select date" name="registration_date"
                                                                value="01/01/2018 - 01/15/2018">
                                                        </div>
                                                    </div>
                                                    @error('registration_date')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-5" style="text-align: right">
                                                    <label class="form-label text-end" for="medical_record_number">
                                                        No. RM
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" value="{{ request('medical_record_number') }}"
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
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
                                            <div class="row">
                                                <div class="col-xl-5" style="text-align: right">
                                                    <label class="form-label text-end" for="registration_name">
                                                        Nama Pasien
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" value="{{ request('name') }}"
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control" id="name" name="name">
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
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label for="registration_type">Tipe Registrasi</label>
                                                </div>
                                                <div class="col-xl">
                                                    <select class="form-control w-100 select2" id="registration_type"
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        name="registration_type">
                                                        <option value=""></option>
                                                        <option value="rawat-inap">Rawat Inap</option>
                                                        <option value="rawat-jalan">Rawat Jalan</option>
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
                                            <div class="row">
                                                <div class="col-xl-5" style="text-align: right">
                                                    <label class="form-label text-end" for="address">
                                                        Alamat
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" value="{{ request('address') }}"
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control" id="address" name="address">
                                                    @error('address')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-5" style="text-align: right">
                                                    <label class="form-label text-end" for="family_name">
                                                        Nama Keluarga
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" value="{{ request('family_name') }}"
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control" id="family_name" name="family_name">
                                                    @error('family_name')
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
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label for="status">Status</label>
                                                </div>
                                                <div class="col-xl">
                                                    <select class="form-control w-100 select2" id="status"
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        name="status">
                                                        <option value=""></option>
                                                        <option value="Registrasi Aktif">Registrasi Aktif</option>
                                                        <option value="Tutup Kunjungan">Tutup Kunjungan</option>
                                                        <option value="All">All</option>
                                                    </select>
                                                    @error('status')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-5" style="text-align: right">
                                                    <label class="form-label text-end" for="departement_id">
                                                        Poly/Unit
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <select class="select2 form-control w-100" id="departement_id"
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        name="departement_id">
                                                        <option value=""></option>
                                                        <option value="Rawat Inap">Rawat Inap</option>
                                                        <option value="Rawat Jalan">Rawat Jalan</option>
                                                    </select>
                                                    @error('departement_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-5" style="text-align: right">
                                                    <label class="form-label text-end" for="date_of_birth">
                                                        Tgl. Lahir
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" name="date_of_birth"
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        value="{{ request('date_of_birth') }}" class="form-control"
                                                        id="date_of_birth">
                                                    @error('date_of_birth')
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
                            Daftar <span class="fw-300"><i>Registrasi Pasien</i></span>
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
                                        <th>Tgl. Registrasi</th>
                                        <th>No Registrasi</th>
                                        <th>No. RM</th>
                                        <th>Nama Lengkap</th>
                                        <th>Alamat</th>
                                        <th>Poly/Unit</th>
                                        <th>Dokter</th>
                                        <th>Penjamin</th>
                                        <th>User Input</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($registrations as $registration)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td><a
                                                    href="{{ route('detail.registrasi.pasien', $registration->id) }}">{{ $registration->registration_date }}</a>
                                            </td>
                                            <td><a
                                                    href="{{ route('detail.registrasi.pasien', $registration->id) }}">{{ $registration->registration_number }}</a>
                                            </td>
                                            <td><a
                                                    href="{{ route('detail.registrasi.pasien', $registration->id) }}">{{ $registration->patient->medical_record_number }}</a>
                                            </td>
                                            <td><a
                                                    href="{{ route('detail.registrasi.pasien', $registration->id) }}">{{ $registration->patient->name }}</a>
                                            </td>
                                            <td><a
                                                    href="{{ route('detail.registrasi.pasien', $registration->id) }}">{{ $registration->patient->address }}</a>
                                            </td>
                                            <td><a
                                                    href="{{ route('detail.registrasi.pasien', $registration->id) }}">{{ $registration['registration_type'] == 'rawat-inap' ? 'RAWAT INAP' : $registration->poliklinik }}</a>
                                            </td>
                                            <td><a
                                                    href="{{ route('detail.registrasi.pasien', $registration->id) }}">{{ $registration->doctor->employee->fullname }}</a>
                                            </td>
                                            <td><a
                                                    href="{{ route('detail.registrasi.pasien', $registration->id) }}">{{ $registration->penjamin->name }}</a>
                                            </td>
                                            <td><a
                                                    href="{{ route('detail.registrasi.pasien', $registration->id) }}">{{ $registration->employee->fullname }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Tgl. Registrasi</th>
                                        <th>No Registrasi</th>
                                        <th>No. RM</th>
                                        <th>Nama Lengkap</th>
                                        <th>Alamat</th>
                                        <th>Poly/Unit</th>
                                        <th>Dokter Penjamin</th>
                                        <th>Penjamin Penjamin</th>
                                        <th>User Input</th>
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
                templates: controls
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

            /// Get the current date and time
            var today = new Date();

            // Format it as "YYYY-MM-DD"
            var formattedToday = today.getFullYear() + '-' +
                ('0' + (today.getMonth() + 1)).slice(-2) + '-' +
                ('0' + today.getDate()).slice(-2) + ' ' +
                ('0' + today.getHours()).slice(-2) + ':' +
                ('0' + today.getMinutes()).slice(-2) + ':' +
                ('0' + today.getSeconds()).slice(-2);

            // Set the default date for the datepicker
            $('#datepicker-1').daterangepicker({
                opens: 'left',
                startDate: moment(today).format('YYYY-MM-DD'),
                endDate: moment(today).format('YYYY-MM-DD'),
                // timePicker: true, // Enable time selection
                // timePicker24Hour: true, // 24-hour format
                // timePickerSeconds: true, // Include seconds in time selection
                locale: {
                    format: 'YYYY-MM-DD' // Display format for the picker
                }
            }, function(start, end, label) {
                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') +
                    ' to ' + end.format('YYYY-MM-DD'));
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
            });

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
