@extends('inc.layout')
@section('title', 'Run Payroll')
@section('content')
    <style>
        .swal2-modal {
            width: 50vw !important;
        }

        @media (max-width: 1080px) {
            .swal2-modal {
                width: 95vw !important;
            }
        }
    </style>
    <main id="js-page-content" role="main" class="page-content">
        <div class="panel-container show">
            <div class="panel-content">
                <div class="row mb-5">
                    <div class="col-xl-12">
                        <div id="panel-1" class="panel">
                            <div class="panel-container show">
                                <div class="tambah-pegawai-baru mt-5 mb-3">
                                    <form action="#" method="POST" id="store-form-payroll" class="mx-5">
                                        @method('POST')
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                        <div id="step-1">
                                            <div class="form-group mb-3">
                                                <label for="periode">Periode</label>
                                                <div class="input-group">
                                                    <input type="text" name="periode"
                                                        class="form-control @error('periode') is-invalid @enderror"
                                                        placeholder="Periode" id="periode">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text fs-xl">
                                                            <i class="fal fa-calendar-alt"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                @error('periode')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group mb-3">
                                                <label class="form-label" for="employee-id">
                                                    Karyawan
                                                </label>
                                                <select class="select2 form-control" multiple="multiple" id="employee-id"
                                                    name="employee_id[]">
                                                    @foreach ($employees as $employee)
                                                        <option value="{{ $employee->id }}">
                                                            {{ $employee->fullname }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="btn-next mt-3 text-right">
                                                {{-- <button type="button" class="btn-export btn btn-success btn-sm ml-2">
                                                    <div class="ikon-export">
                                                        <i class='fas fa-file-excel mr-2'></i> Template Potongan Pegawai
                                                    </div>
                                                </button>
                                                <button type="button" class="btn-import btn btn-info btn-sm ml-2">
                                                    <div class="ikon-import">
                                                        <i class='fas fa-file-excel mr-2'></i> Import Potongan Pegawai
                                                    </div>
                                                </button> --}}
                                                <button type="submit" class="btn-next-step btn btn-primary btn-sm ml-2">
                                                    <div class="ikon-tambah">
                                                        <i class='bx bxs-checkbox-minus'></i> Tambah atau Ubah
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

                        <div id="panel-2" class="panel">
                            <div class="panel-container show m-5">
                                <!-- datatable start -->
                                <div class="table-responsive">
                                    <table id="dt-basic-example"
                                        class="datatable table table-bordered table-hover table-striped w-100">
                                        <thead>
                                            <tr>
                                                <th style="white-space: nowrap">No</th>
                                                <th style="white-space: nowrap">employee</th>
                                                <th style="white-space: nowrap">Basic Salary</th>
                                                <th style="white-space: nowrap">Allowance</th>
                                                <th style="white-space: nowrap">Deduction</th>
                                                <th style="white-space: nowrap">Total</th>
                                                <th style="white-space: nowrap">Periode</th>
                                                <th style="white-space: nowrap">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($payrolls as $payroll)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-2">
                                                                {{-- @if ($payroll->employee->foto != null)
                                                                    <img src="{{ '/' . $payroll->employee->foto }}"
                                                                        class="rounded-circle img-thumbnail" alt=""
                                                                        style="width: 25px; height: 25px; object-fit: cover; z-index: 100;">
                                                                @else --}}
                                                                <img src="{{ $payroll->employee->gender == 'Laki-laki' ? '/img/demo/avatars/avatar-c.png' : '/img/demo/avatars/avatar-p.png' }}"
                                                                    class="rounded-circle img-thumbnail" alt=""
                                                                    style="width: 50px; z-index: 100;">
                                                                {{-- @endif --}}
                                                            </div>
                                                            <div class="col-10">
                                                                <strong>
                                                                    <a class="employeeId"
                                                                        style="text-decoration: none !important"
                                                                        href="javascript:void(0)"
                                                                        data-employee-id="{{ $payroll->employee_id }}">
                                                                        {{ $payroll->employee->fullname }}
                                                                    </a>
                                                                </strong>
                                                                <br>
                                                                {{ $payroll->employee->organization->name ?? '-' }}
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ rp($payroll->basic_salary) }}</td>
                                                    <td> <a href="javascript:void(0);"
                                                            data-payroll-id="{{ $payroll->id }}"
                                                            class="js-sweetalert-allowance cursor-pointer">
                                                            {{ rp($payroll->total_allowance) }}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a href="javascript:void(0);" data-payroll-id="{{ $payroll->id }}"
                                                            class="js-sweetalert-deduction cursor-pointer">
                                                            {{ rp($payroll->total_deduction) }}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a href="javascript:void(0);" data-payroll-id="{{ $payroll->id }}"
                                                            class="js-sweetalert-total cursor-pointer">{{ rp($payroll->take_home_pay) }}</a>
                                                    </td>
                                                    <td>{{ $payroll->periode }}</td>
                                                    <td style="white-space: nowrap">
                                                        <button type="button" data-backdrop="static" data-keyboard="false"
                                                            class="badge mx-1 badge-info p-2 border-0 text-white btn-edit"
                                                            data-id="{{ $payroll->id }}" title="Ubah Data"
                                                            onclick="btnEdit(event)">
                                                            <span class="fal fa-pencil ikon-edit"></span>
                                                            <div class="span spinner-text d-none">
                                                                <span class="spinner-border spinner-border-sm"
                                                                    role="status" aria-hidden="true"></span>
                                                            </div>
                                                        </button>
                                                        <button type="button" data-backdrop="static" data-keyboard="false"
                                                            class="badge mx-1 badge-danger p-2 border-0 text-white btn-edit"
                                                            data-id="{{ $payroll->id }}" title="Hapus Data"
                                                            onclick="btnDelete(event)">
                                                            <span class="fal fa-trash ikon-hapus"></span>
                                                            <div class="span spinner-text d-none">
                                                                <span class="spinner-border spinner-border-sm"
                                                                    role="status" aria-hidden="true"></span>
                                                            </div>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-center mt-3">
                                    <button id="runPayrollButton" class="btn btn-outline-info">Run Payroll</button>
                                </div>

                                <!-- datatable end -->

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('pages.pegawai.gaji-pegawai.partials.update-payroll')
    @include('pages.pegawai.gaji-pegawai.partials.tambah-potongan')
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>

    <script>
        // Fungsi untuk memformat angka ke dalam format rupiah
        function formatRupiah(angka) {
            var numberString = angka.toString().replace(/[^,\d]/g, ''),
                split = numberString.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                var separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return 'Rp ' + rupiah;
        }

        // Fungsi untuk menghitung kembali total tunjangan saat nilai input berubah
        function recalculateTotalAllowance() {
            let tunjanganMasaKerja = parseFloat($('#ubah-data-payroll #tunjangan_masa_kerja').val());
            let tunjanganMakanTransport = parseFloat($('#ubah-data-payroll #tunjangan_makan_dan_transport').val());
            let tunjanganJabatan = parseFloat($('#ubah-data-payroll #tunjangan_jabatan').val());

            // Hitung total tunjangan
            let totalTunjangan = tunjanganMasaKerja + tunjanganMakanTransport + tunjanganJabatan;

            // Isi nilai input total_allowance dengan hasil perhitungan
            $('#ubah-data-payroll #total_allowance').val(totalTunjangan);
        }

        function recalculateTotalDeduction() {
            let potonganAbsensi = parseFloat($('#ubah-data-payroll #potongan_absensi').val());
            let potonganketerlambatan = parseFloat($('#ubah-data-payroll #potongan_keterlambatan').val());
            let potonganIzin = parseFloat($('#ubah-data-payroll #potongan_izin').val());
            let simpananPokok = parseFloat($('#ubah-data-payroll #simpanan_pokok').val());
            let potongan_koperasi = parseFloat($('#ubah-data-payroll #potongan_koperasi').val());
            let potongan_bpjs_kesehatan = parseFloat($('#ubah-data-payroll #potongan_bpjs_kesehatan').val());
            let potongan_bpjs_ketenagakerjaan = parseFloat($('#ubah-data-payroll #potongan_bpjs_ketenagakerjaan').val());
            let potongan_pajak = parseFloat($('#ubah-data-payroll #potongan_pajak').val());

            // Hitung total tunjangan
            let totalPotongan = potonganAbsensi + potonganketerlambatan + potonganIzin + simpananPokok + potongan_koperasi +
                potongan_bpjs_kesehatan + potongan_bpjs_ketenagakerjaan + potongan_pajak;

            // Isi nilai input total_deduction dengan hasil perhitungan
            $('#ubah-data-payroll #total_deduction').val(totalPotongan);
        }

        // Panggil fungsi recalculateTotalAllowance saat nilai input berubah
        $('#ubah-data-payroll #tunjangan_masa_kerja, #ubah-data-payroll #tunjangan_makan_dan_transport, #ubah-data-payroll #tunjangan_jabatan')
            .on('keyup', function() {
                recalculateTotalAllowance();
            });

        // Panggil fungsi recalculateTotalDeduction saat nilai input berubah
        $('#ubah-data-payroll #potongan_absensi, #ubah-data-payroll #potongan_keterlambatan, #ubah-data-payroll #potongan_izin, #ubah-data-payroll #simpanan_pokok, #ubah-data-payroll #potongan_koperasi, #ubah-data-payroll #potongan_bpjs_kesehatan, #ubah-data-payroll #potongan_bpjs_ketenagakerjaan, #ubah-data-payroll #potongan_pajak')
            .on('keyup', function() {
                recalculateTotalDeduction();
            });

        function btnEdit(event) {
            event.preventDefault();
            let button = event.currentTarget;
            let id = button.getAttribute('data-id');
            dataId = id;
            let ikonEdit = button.querySelector('.ikon-edit');
            let spinnerText = button.querySelector('.spinner-text');
            ikonEdit.classList.add('d-none');
            spinnerText.classList.remove('d-none');
            // button.find('.ikon-edit').hide();
            // button.find('.spinner-text').removeClass('d-none');

            $.ajax({
                type: "GET", // Method pengiriman data bisa dengan GET atau POST
                url: '/api/dashboard/payroll/get/' + dataId, // Isi dengan url/path file php yang dituju
                dataType: "json",
                success: function(data) {
                    // console.log(data);
                    ikonEdit.classList.remove('d-none');
                    ikonEdit.classList.add('d-block');
                    spinnerText.classList.add('d-none');
                    $('#ubah-data-payroll').modal('show');
                    $('#ubah-data-payroll #potongan_absensi').val(data.potongan_absensi)
                    $('#ubah-data-payroll #potongan_keterlambatan').val(data.potongan_keterlambatan)
                    $('#ubah-data-payroll #potongan_izin').val(data.potongan_izin)
                    $('#ubah-data-payroll #potongan_sakit').val(data.potongan_sakit)
                    $('#ubah-data-payroll #potongan_koperasi').val(data.potongan_koperasi)
                    $('#ubah-data-payroll #potongan_bpjs_kesehatan').val(data.potongan_bpjs_kesehatan)
                    $('#ubah-data-payroll #potongan_bpjs_ketenagakerjaan').val(data
                        .potongan_bpjs_ketenagakerjaan)
                    $('#ubah-data-payroll #potongan_pajak').val(data.potongan_pajak)
                    $('#ubah-data-payroll #simpanan_pokok').val(data.simpanan_pokok)
                    $('#ubah-data-payroll #tunjangan_masa_kerja').val(data.tunjangan_masa_kerja)
                    $('#ubah-data-payroll #tunjangan_makan_dan_transport').val(data
                        .tunjangan_makan_dan_transport)
                    $('#ubah-data-payroll #tunjangan_jabatan').val(data.tunjangan_jabatan)
                    $('#ubah-data-payroll #total_allowance').val(data.total_allowance)
                    $('#ubah-data-payroll #total_deduction').val(data.total_deduction)
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        }

        function btnDelete(event) {
            event.preventDefault();
            let button = event.currentTarget;
            let id = button.getAttribute('data-id');
            dataId = id;

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda tidak akan dapat mengembalikan tindakan ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus saja!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika pengguna mengonfirmasi penghapusan
                    deletePayroll(dataId);
                }
            });
        }

        function deletePayroll(id) {
            $.ajax({
                type: "DELETE", // Menggunakan metode DELETE untuk penghapusan data
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/api/dashboard/payroll/delete/' +
                    id, // Ubah URL sesuai dengan rute yang benar untuk penghapusan data
                dataType: "json",
                success: function(data) {
                    showSuccessAlert(data.message); // Tampilkan pesan sukses
                    location.reload(); // Muat ulang halaman setelah penghapusan berhasil
                },
                error: function(xhr) {
                    showErrorAlert(xhr.responseText);
                }
            });
        }

        $(document).ready(function() {
            $('.btn-export').click(function() {
                // Get the selected value from the select element with id "periode"
                const periode = $('#periode').val();

                // Create the data object to be sent with the request
                const data = {
                    periode: periode
                };

                // Make the AJAX POST request to the specified API URL
                $.ajax({
                    url: '/api/dashboard/employee/salary/export/deductions',
                    type: 'POST',
                    data: JSON.stringify(data),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Add CSRF token for security
                    },
                    xhrFields: {
                        responseType: 'blob' // Set the response type to blob
                    },
                    success: function(response) {
                        // Create a link element
                        const link = document.createElement('a');
                        const url = window.URL.createObjectURL(response);
                        link.href = url;
                        link.download = 'payroll_deductions_' + periode + '.xlsx';
                        document.body.appendChild(link);
                        link.click();
                        window.URL.revokeObjectURL(url); // Clean up the URL object
                        document.body.removeChild(link);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        // Handle the error response here
                        showErrorAlert('Export failed. Please try again.');
                    }
                });
            });

            $('.btn-import').click(function() {
                $('#tambah-potongan-modal').modal('show');
            });

            $('#potongan').on('change', function() {
                // Get the selected file name
                var fileName = $(this).val().split('\\').pop();

                // Update the label text with the file name
                $(this).next('.custom-file-label').html(fileName);
            });

            $('#update-potongan-tombol').on('click', function() {
                // Create a FormData object
                var formData = new FormData($('#form-update-potongan-payroll')[0]);

                // Make the AJAX request
                $.ajax({
                    url: '/api/dashboard/employee/salary/import/deductions',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Add CSRF token for security
                    },
                    success: function(response) {
                        $('#tambah-potongan-modal').modal('hide');
                        showSuccessAlert('File uploaded successfully!');
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    error: function(xhr, status, error) {
                        $('#tambah-potongan-modal').modal('hide');
                        showErrorAlert('File upload failed. Please try again.');
                    }
                });
            });

            $(".js-sweetalert-deduction").on("click", function() {
                var deductionId = $(this).data("payroll-id");

                $.ajax({
                    url: '/api/dashboard/payroll/getDeduction/' + deductionId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        var deductions = `
                                            <div class="container w-75">
                                                <div class="row p-3 border text-left">
                                                    <div class="col-8">Potongan Keterlambatan</div>
                                                    <div class="col-4">: ${formatRupiah(response.potongan_keterlambatan)}</div>
                                                </div>
                                                <div class="row p-3 border text-left">
                                                    <div class="col-8">Potongan Izin</div>
                                                    <div class="col-4">: ${formatRupiah(response.potongan_izin)}</div>
                                                </div>
                                                <div class="row p-3 border text-left">
                                                    <div class="col-8">Potongan Sakit</div>
                                                    <div class="col-4">: ${formatRupiah(response.potongan_sakit)}</div>
                                                </div>
                                                <div class="row p-3 border text-left">
                                                    <div class="col-8">Simpanan Pokok</div>
                                                    <div class="col-4">: ${formatRupiah(response.simpanan_pokok)}</div>
                                                </div>
                                                <div class="row p-3 border text-left">
                                                    <div class="col-8">Potongan Koperasi</div>
                                                    <div class="col-4">: ${formatRupiah(response.potongan_koperasi)}</div>
                                                </div>
                                                <div class="row p-3 border text-left">
                                                    <div class="col-8">Potongan Absensi</div>
                                                    <div class="col-4">: ${formatRupiah(response.potongan_absensi)}</div>
                                                </div>
                                                <div class="row p-3 border text-left">
                                                    <div class="col-8">Potongan BPJSKesehatan:</div>
                                                    <div class="col-4">: ${formatRupiah(response.potongan_bpjs_kesehatan)}</div>
                                                </div>
                                                <div class="row p-3 border text-left">
                                                    <div class="col-8">Potongan BPJSKetenagakerjaan:</div>
                                                    <div class="col-4">: ${formatRupiah(response.potongan_bpjs_ketenagakerjaan)}</div>
                                                </div>
                                                <div class="row p-3 border text-left">
                                                    <div class="col-8">Potongan Pajak</div>
                                                    <div class="col-4">: ${formatRupiah(response.potongan_pajak)}</div>
                                                </div>
                                                <div class="row p-3 border text-left">
                                                    <div class="col-8"><strong>Total Deduction</strong></div>
                                                    <div class="col-4"><strong>: ${formatRupiah(response.total_deduction)}</strong></div>
                                                </div>
                                            </div>
                                        `;
                        Swal.fire({
                            title: 'Deductions Details',
                            html: deductions,
                            icon: 'info',
                            // showCloseButton: true,
                            // showCancelButton: true,
                            focusConfirm: false,
                            confirmButtonText: 'OK',
                        });
                    },
                    error: function(error) {
                        Swal.fire({
                            title: 'Error',
                            text: 'Unable to fetch deductions data.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });

            $(".js-sweetalert-allowance").on("click", function() {
                var allowanceId = $(this).data("payroll-id");

                $.ajax({
                    url: '/api/dashboard/payroll/getAllowance/' + allowanceId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        var allowances = `
                                            <div class="container">
                                                <div class="row p-3 border text-left">
                                                    <div class="col-8">Tunjangan Jabatan</div>
                                                    <div class="col-4">: ${formatRupiah(response.tunjangan_jabatan)}</div>
                                                </div>
                                                <div class="row p-3 border text-left">
                                                    <div class="col-8">Tunjangan Profesi</div>
                                                    <div class="col-4">: ${formatRupiah(response.tunjangan_profesi)}</div>
                                                </div>
                                                <div class="row p-3 border text-left">
                                                    <div class="col-8">Tunjangan Makan dan Transport</div>
                                                    <div class="col-4">: ${formatRupiah(response.tunjangan_makan_dan_transport)}</div>
                                                </div>
                                                <div class="row p-3 border text-left">
                                                    <div class="col-8">Tunjangan Masa Kerja</div>
                                                    <div class="col-4">: ${formatRupiah(response.tunjangan_masa_kerja)}</div>
                                                </div>
                                                <div class="row p-3 border text-left">
                                                    <div class="col-8">Guarantee Fee</div>
                                                    <div class="col-4">: ${formatRupiah(response.guarantee_fee)}</div>
                                                </div>
                                                <div class="row p-3 border text-left">
                                                    <div class="col-8">Uang Duduk</div>
                                                    <div class="col-4">: ${formatRupiah(response.uang_duduk)}</div>
                                                </div>
                                                <div class="row p-3 border text-left">
                                                    <div class="col-8">Tax Allowance:</div>
                                                    <div class="col-4">: ${formatRupiah(response.tax_allowance)}</div>
                                                </div>
                                                <div class="row p-3 border text-left">
                                                    <div class="col-8"><strong>Total Allowance:</strong></div>
                                                    <div class="col-4"><strong>: ${formatRupiah(response.total_allowance)}</strong></div>
                                                </div>
                                            </div>
                                        `;
                        Swal.fire({
                            title: 'Allowances Details',
                            html: allowances,
                            icon: 'info',
                            // showCloseButton: true,
                            // showCancelButton: true,
                            focusConfirm: false,
                            confirmButtonText: 'OK',
                        });
                    },
                    error: function(error) {
                        Swal.fire({
                            title: 'Error',
                            text: 'Unable to fetch allowances data.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });

            $(".js-sweetalert-total").on("click", function() {
                var payrollId = $(this).data("payroll-id");

                $.ajax({
                    url: '/api/dashboard/payroll/getTotalPayroll/' + payrollId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        var totals = `
                                        <div class="container">
                                            <div class="row p-3 border text-left">
                                                <div class="col-8">Basic Salary</div>
                                                <div class="col-4">: ${formatRupiah(response.basic_salary)}</div>
                                            </div>
                                            <div class="row p-3 border text-left">
                                                <div class="col-8">Total Allowance</div>
                                                <div class="col-4">: ${formatRupiah(response.total_allowance)}</div>
                                            </div>
                                            <div class="row p-3 border text-left">
                                                <div class="col-8">Total Deduction</div>
                                                <div class="col-4">: ${formatRupiah(response.total_deduction)}</div>
                                            </div>
                                            <div class="row p-3 border text-left">
                                                <div class="col-8"><strong>Take Home Pay</strong></div>
                                                <div class="col-4"><strong>: ${formatRupiah(response.take_home_pay)}</strong></div>
                                            </div>
                                        </div>
                                    `;
                        Swal.fire({
                            title: 'Totals Details',
                            html: totals,
                            icon: 'info',
                            // showCloseButton: true,
                            // showCancelButton: true,
                            focusConfirm: false,
                            confirmButtonText: 'OK',
                        });
                    },
                    error: function(error) {
                        Swal.fire({
                            title: 'Error',
                            text: 'Unable to fetch allowances data.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });

            $('#store-form-payroll').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                $.ajax({
                    type: "POST",
                    url: "{{ route('api.run.payroll') }}",
                    data: formData,
                    beforeSend: function() {
                        $('#store-form-payroll').find('.ikon-tambah').hide();
                        $('#store-form-payroll').find('.spinner-text').removeClass(
                            'd-none');
                    },
                    success: function(response) {
                        $('#store-form-payroll').find('.ikon-tambah').show();
                        $('#store-form-payroll').find('.spinner-text').addClass('d-none');
                        $('#tambah').modal('hide');
                        showSuccessAlert(response.message)
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    error: function(xhr) {
                        $('#btn-tambah').find('.ikon-tambah').show();
                        $('#btn-tambah').find('.spinner-text').addClass('d-none');
                        $('#tambah').modal('hide');
                        var errors = JSON.parse(xhr.responseText);
                        var errorMessage = '';

                        $.each(errors, function(key, value) {
                            errorMessage += value +
                                '. '; // Menambahkan setiap pesan kesalahan
                        });

                        showErrorAlert(
                            errorMessage); // Menampilkan pesan kesalahan yang sudah dirapikan
                    }
                });
            });

            $('#update-form-payroll').on('submit', function(e) {
                e.preventDefault();
                const fd = new FormData(this);
                // console.log(fd);
                $.ajax({
                    type: 'post',
                    url: '/api/dashboard/payroll/update/' + dataId,
                    processData: false,
                    contentType: false,
                    data: fd,
                    success: function(response) {
                        $('#ubah-data-payroll').modal('hide');
                        showSuccessAlert(response.message);
                        setTimeout(function() {
                            location.reload();
                        }, 500);
                    },
                    error: function(xhr, status, error) {
                        $('#ubah-data-payroll').modal('hide');
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showErrorAlert(errorMessage);
                    }

                });
            });

            $('#runPayrollButton').on('click', function() {
                $.ajax({
                    url: "{{ route('api.run') }}",
                    type: "POST",
                    data: {
                        is_review: 1 // Setiap data payroll akan ditandai sebagai di-review
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Add CSRF token for security
                    },
                    success: function(response) {
                        // Tanggapan dari server berhasil diterima
                        showSuccessAlert(response.message);
                        setTimeout(function() {
                            location.reload();
                        }, 500);
                        // Tambahkan logika lain yang diperlukan setelah mengeksekusi endpoint
                    },
                    error: function(xhr, status, error) {
                        // Penanganan kesalahan saat melakukan permintaan
                        console.error(xhr.responseText);
                    }
                });
            });

            var today = new Date();
            var startDate, endDate;

            // Mendapatkan tanggal awal bulan sebelumnya
            if (today.getDate() >= 26) {
                startDate = new Date(today.getFullYear(), today.getMonth(), 26);
                endDate = new Date(today.getFullYear(), today.getMonth() + 1, 25);
            } else {
                startDate = new Date(today.getFullYear(), today.getMonth() - 1, 26);
                endDate = new Date(today.getFullYear(), today.getMonth(), 25);
            }

            $('#store-form-payroll #periode').daterangepicker({
                startDate: startDate,
                endDate: endDate,
                locale: {
                    format: 'MMMM YYYY', // Menampilkan nama bulan saja
                    firstDay: 1
                }
            });

            $(function() {
                $('#employee-id').select2({
                    placeholder: 'Pilih Karyawan (kosongkan jika semua)',
                    allowClear: true,
                });
            });

            $('#dt-basic-example').DataTable({
                // responsive: true,
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
                        text: 'Column Visibility',
                        titleAttr: 'Col visibility',
                        className: 'btn-outline-default'
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        titleAttr: 'Print Table',
                        className: 'btn-outline-default',
                        exportOptions: {
                            columns: ':visible' // Menggunakan kolom yang terlihat sesuai pengaturan ColVis
                        },
                        customize: function(win) {
                            $(win.document.body).find('table').addClass('display').css('font-size',
                                '12px'); // Menambahkan kelas dan menyesuaikan ukuran font
                            $(win.document.body).find('thead').addClass(
                                'thead-light'); // Menambahkan kelas untuk style header
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        titleAttr: 'Export to Excel',
                        className: 'btn-outline-default',
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
            // Datatable End
        });
    </script>
@endsection
