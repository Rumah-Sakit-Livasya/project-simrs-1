    @extends('inc.layout')
    @section('title', 'Pegawai')
    @section('extended-css')
        <style>
            /* CSS */
            .dataTables_scrollBody thead th {
                min-height: auto;
                /* Menjadikan tinggi minimum header otomatis */
            }

            .dataTables_scrollBody tbody td:nth-child(1),
            .dataTables_scrollBody tbody td:nth-child(2) {
                white-space: nowrap;
                /* Mencegah teks melampaui batas */
                min-height: 40px !important;
                height: 40px !important;
                /* Atur tinggi minimum untuk kolom 1 dan 2 */
            }

            .dataTables_scrollBody tbody td:nth-child(2) {
                min-width: 400px;
            }

            .dataTables_scrollBody {
                height: 410px;
            }
        </style>
    @endsection
    @section('content')
        <main id="js-page-content" role="main" class="page-content">
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="row mb-5">
                        <div class="col-xl-12">
                            <div id="panel-1" class="panel">
                                <div class="panel-container show">
                                    <div class="tambah-pegawai-baru mt-5 mb-3">
                                        <form action="{{ route('payroll.payslip.print') }}" method="post" class="mx-5">
                                            @method('POST')
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                            <div id="step-1">
                                                <div class="form-group mb-3">
                                                    <label for="periode">Periode</label>
                                                    <!-- Mengubah input menjadi select2 -->
                                                    <select
                                                        class="select2 form-control @error('periode') is-invalid @enderror"
                                                        name="periode" id="periode">
                                                        @php
                                                            $currentYear = date('Y');
                                                            $nextYear = $currentYear + 1;
                                                            $months = [
                                                                'Januari',
                                                                'Februari',
                                                                'Maret',
                                                                'April',
                                                                'Mei',
                                                                'Juni',
                                                                'Juli',
                                                                'Agustus',
                                                                'September',
                                                                'Oktober',
                                                                'November',
                                                                'Desember',
                                                            ];
                                                            $lastSearchPeriod = $request->periode ?? ''; // Mendapatkan periode terakhir yang dicari

                                                            foreach ($months as $index => $month) {
                                                                $nextIndex = ($index + 1) % 12; // Menyesuaikan indeks bulan berikutnya
                                                                $nextMonth = $months[$nextIndex];
                                                                $year = $index < 11 ? $currentYear : $nextYear; // Menentukan tahun

                                                                $period = "{$month} {$currentYear} - {$nextMonth} {$year}";
                                                                $selected =
                                                                    $period == $lastSearchPeriod ? 'selected' : ''; // Menandai opsi yang sesuai

                                                                echo "<option value=\"{$period}\" {$selected}>{$period}</option>";
                                                            }
                                                        @endphp
                                                    </select>
                                                    @error('periode')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label class="form-label" for="employee-id">
                                                        Karyawan
                                                    </label>
                                                    <select class="select2 form-control" multiple="multiple"
                                                        id="employee-id" name="employee_id[]">
                                                        @foreach ($employees as $employee)
                                                            <option value="{{ $employee->id }}">
                                                                {{ $employee->fullname }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <!-- Hanya menampilkan data payroll ketika periode telah diisi -->
                                                <div class="btn-next mt-3 text-right">
                                                    <button type="submit"
                                                        class="btn-next-step btn btn-primary btn-sm ml-2">
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
                </div>
            </div>
        </main>

        @include('pages.pegawai.gaji-pegawai.partials.update-payroll')
    @endsection
    @section('plugin')
        <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
        <script src="/js/formplugins/select2/select2.bundle.js"></script>
        <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
        <script src="/js/dependency/moment/moment.js"></script>
        <script src="/js/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>

        <script>
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
                    console.log('kontoll');
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
                        ikonEdit.classList.remove('d-none');
                        ikonEdit.classList.add('d-block');
                        spinnerText.classList.add('d-none');
                        $('#ubah-data-payroll').modal('show');
                        $('#ubah-data-payroll #potongan_absensi').val(data.potongan_absensi)
                        $('#ubah-data-payroll #potongan_keterlambatan').val(data.potongan_keterlambatan)
                        $('#ubah-data-payroll #potongan_izin').val(data.potongan_izin)
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

            $(document).ready(function() {
                $('#cetak-payslip').on('submit', function(e) {
                    e.preventDefault(); // Mencegah perilaku bawaan pengiriman formulir

                    let formData = $(this).serialize(); // Mengambil data formulir

                    // Mengirim permintaan AJAX untuk mendapatkan data payroll sesuai dengan periode yang dipilih
                    $.ajax({
                        type: "POST",
                        url: "{{ route('get.all.payroll') }}", // Ubah URL sesuai dengan rute yang benar
                        data: formData,
                        dataType: "json",
                        success: function(response) {
                            // Mengambil data periode dari form
                            let periode = $('#periode').val();

                            // Mengambil URL untuk mengarahkan ke controller
                            // Ganti 'controller_name' dengan nama controller yang benar
                            let redirectUrl = "{{ route('payroll.payslip.print') }}";

                            // Membuka popup jendela baru dengan URL yang diarahkan ke controller dan membawa data periode
                            let newWindow = window.open(redirectUrl + '?periode=' + periode,
                                '_blank');
                            if (newWindow) {
                                // Jika popup jendela berhasil dibuka, tutup formulir pencarian
                                $('#cetak-payslip').trigger('reset');
                                // Focus ke popup jendela baru
                                newWindow.focus();
                            } else {
                                // Jika gagal membuka popup jendela, tampilkan pesan kesalahan
                                alert('Popup window blocked! Please allow popups for this site.');
                            }
                        },
                    });
                });

                // Datatable Start
                $('#dt-basic-example').DataTable({
                    scrollY: 400, // Sesuaikan tinggi scroll dengan jumlah baris yang ingin ditampilkan
                    scrollX: true,
                    scrollCollapse: true,
                    paging: true,
                    pageLength: 5,
                    fixedColumns: {
                        leftColumns: 2,
                    },
                    autoWidth: true,
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
                                columns: ':visible'
                            },
                            customize: function(win) {
                                $(win.document.body).find('table').addClass('display').css('font-size',
                                    '12px');
                                $(win.document.body).find('thead').addClass(
                                    'thead-light');
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



                $('#periode').select2({
                    placeholder: 'Pilih Periode',
                }).val('{{ $lastSearchPeriod }}').trigger('change');

                $('#employee-id').select2({
                    placeholder: 'kosongkan jika semua',
                    allowClear: true,
                }).val('{{ $lastSearchPeriod }}').trigger('change');

            });
        </script>
    @endsection
