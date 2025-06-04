@extends('pages.simrs.erm.index')
@section('erm')
    @if (isset($registration) || $registration != null)
        <div class="tab-content p-3">
            <div class="tab-pane fade show active" id="tab_default-1" role="tabpanel">
                @include('pages.simrs.poliklinik.partials.detail-pasien')
                <hr style="border-color: #868686; margin-top: 50px; margin-bottom: 30px;">
                <div class="row">
                    <form action="javascript:void(0)" class="w-100" data-tipe-cppt="dokter" data-tipe-cppt="rawat-jalan"
                        id="cppt-dokter-rajal-form">
                        @csrf
                        @method('POST')
                        <div class="col-md-12">
                            <div class="p-3">
                                <div class="card-head collapsed d-flex justify-content-between">
                                    <div class="title">
                                        <header class="text-primary text-center font-weight-bold mb-4">
                                            <h2 class="font-weight-bold">CPPT DOKTER</h4>
                                        </header>
                                    </div> <!-- Tambahkan judul jika perlu -->
                                    <div class="tools ml-auto">
                                        <!-- Tambahkan ml-auto untuk memindahkan tombol ke kanan -->
                                        <button class="btn btn-primary btnAdd mr-2" id="btnAdd" data-toggle="collapse"
                                            data-parent="#accordion_soap" data-target="#add_soap" aria-expanded="true">
                                            <i class="mdi mdi-plus-circle"></i> Tambah CPPT
                                        </button>
                                        <button class="btn btn-secondary collapsed" data-toggle="collapse"
                                            data-parent="#accordion_soap" data-target="#view-fitler-soap"
                                            aria-expanded="false">
                                            <i class="mdi mdi-filter"></i> Filter
                                        </button>
                                    </div>
                                </div>
                                <div id="add_soap" class="panel-content collapse in" aria-expanded="true">
                                    <form method="post" class="form-horizontal" id="fsSOAP" autocomplete="off">
                                        <input type="hidden" name="registration_id" value="{{ $registration->id }}" />
                                        <input type="hidden" name="medical_record_number" id="noRM_cppt"
                                            value="{{ $registration->patient->medical_record_number }}" />

                                        <!-- Perawat -->
                                        <div class="row">
                                            <div class="col-md-6 mt-3">
                                                <label for="pid_dokter" class="form-label">Dokter</label>
                                                <select
                                                    class="select2 form-control @error('doctor_id') is-invalid @enderror"
                                                    name="doctor_id" id="cppt_doctor_id">
                                                    <option value=""></option>
                                                    @foreach ($jadwal_dokter as $jadwal)
                                                        <option value="{{ $jadwal->doctor_id }}">
                                                            {{ $jadwal->doctor->employee->fullname }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label for="konsulkan_ke" class="form-label">Konsulkan Ke</label>
                                                <select
                                                    class="select2 form-control @error('doctor_id') is-invalid @enderror"
                                                    name="konsulkan_ke" id="konsulkan_ke">
                                                    <option value=""></option>
                                                    @foreach ($jadwal_dokter as $jadwal)
                                                        <option value="{{ $jadwal->doctor_id }}">
                                                            {{ $jadwal->doctor->employee->fullname }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Two Column Layout for Subjective and Objective -->
                                        <div class="row">
                                            <!-- Subjective -->
                                            <div class="col-md-6">
                                                <div class="card mt-3">
                                                    <div class="card-header bg-primary text-white">
                                                        <span>Subjective</span>
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <textarea class="form-control border-0 rounded-0" id="subjective" name="subjective" rows="4"
                                                            placeholder="Keluhan Utama">Alergi obat : 
Reaksi alergi obat : 
Keluhan Utama : KONSULTASI
PASIEN TELAH PENGOBATAN 6 BULAN TB PARU
DI PUSKESMAS JATITUJUH 
Riwayat Penyakit Sekarang : KONSULTASI
PASIEN TELAH PENGOBATAN 6 BULAN TB PARU
DI PUSKESMAS JATITUJUH 
Riwayat Penyakit Dahulu : TIDAK ADA
Riwayat Penyakit Keluarga : TIDAK ADA
Alergi makan : 
Reaksi alergi makan : 
Alergi lainya : 
Reaksi alergi lainya : </textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Objective -->
                                            <div class="col-md-6">
                                                <div class="card mt-3">
                                                    <div class="card-header bg-success text-white">
                                                        <span>Objective</span>
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <textarea class="form-control border-0 rounded-0" id="objective" name="objective" rows="4">Nadi (PR): 
Respirasi (RR): 
Tensi (BP): 
Suhu (T): 
Tinggi Badan: 
Berat Badan: 
Skrining Nyeri:
                                                            </textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Two Column Layout for Assessment and Planning -->
                                        <div class="row">
                                            <!-- Assessment -->
                                            <div class="col-md-6">
                                                <div class="card mt-3">
                                                    <div
                                                        class="card-header bg-danger text-white d-flex justify-content-between">
                                                        <span>Assessment</span>
                                                        <span id="diag_perawat" class="badge badge-warning pointer">Diagnosa
                                                            Keperawatan</span>
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <textarea class="form-control border-0 rounded-0" id="assesment" name="assesment" rows="4"
                                                            placeholder="Diagnosa Keperawatan">Diagnosa Kerja:</textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Planning -->
                                            <div class="col-md-6">
                                                <div class="card mt-3">
                                                    <div
                                                        class="card-header bg-warning text-white d-flex justify-content-between">
                                                        <span>Planning</span>
                                                        <span id="intervensi_perawat"
                                                            class="badge badge-dark pointer">Intervensi</span>
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <textarea class="form-control border-0 rounded-0" id="planning" name="planning" rows="4"
                                                            placeholder="Rencana Tindak Lanjut">Terapi / Tindakan :</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Evaluation Section -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="card mt-3">
                                                    <div class="card-header bg-info text-white">
                                                        Instruksi
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <textarea class="form-control border-0 rounded-0" id="instruksi" name="instruksi" rows="4"
                                                            placeholder="Evaluasi"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card mt-3">
                                                    <div class="card-header bg-info text-white">
                                                        Resep Manual
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <textarea class="form-control border-0 rounded-0" id="resep_manual" name="resep_manual" rows="4"
                                                            placeholder="Resep Manual"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="card mt-3">
                                                    <div class="card-header bg-primary text-white">
                                                        Resep Elektronik
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <div class="row p-2">
                                                            <div class="col-6">
                                                                <select
                                                                    class="select2 form-control @error('doctor_id') is-invalid @enderror"
                                                                    name="doctor_id" id="cppt_doctor_id">
                                                                    <option value="152">BK IBU</option>
                                                                    <option selected="selected" value="3">
                                                                        FARMASI RAJAL</option>
                                                                    <option value="110">FARMASI RANAP</option>
                                                                    <option value="150">OBAT KHUSUS KARYAWAN
                                                                    </option>
                                                                    <option value="140">PSRS</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-6">
                                                                <input type="text" name="nama_obat" id="nama_obat"
                                                                    class="form-control ui-autocomplete-input"
                                                                    placeholder="Cari Obat" autocomplete="off">
                                                                <div class="form-control-line"></div>
                                                                <input type="hidden" name="mbid" id="mbid">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <div class="col-lg-12">
                                                        <div class="card-head deep-purple-text-bg"><header class="no-padding-left">Resep Elektronik</header></div>
                                                        <div class="col-sm-3">
                                                            <select name="mgid" id="mgid" class="sel2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                                                <option value="152">BK IBU</option>
                                                                <option selected="selected" value="3">FARMASI RAJAL</option>
                                                                <option value="110">FARMASI RANAP</option>
                                                                <option value="150">OBAT KHUSUS KARYAWAN</option>
                                                                <option value="140">PSRS</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-7">
                                                            <input type="text" name="nama_obat" id="nama_obat" class="form-control ui-autocomplete-input" placeholder="Cari Obat" autocomplete="off"><div class="form-control-line"></div>
                                                            <input type="hidden" name="mbid" id="mbid">
                                                            <span class="mdi mdi-magnify mdi-24px pink-text form-control-feedback pointer" id="pilih_item"></span>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <div class="form-group">
                                                                <div class="form-radio" style="margin: 5px 12px 0 12px;">
                                                                    <label class="checkbox-styled checkbox-success no-margin">
                                                                        <input name="zat_aktif" id="zat_aktif" value="true" type="checkbox"><span>Zat Aktif</span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div> --}}
                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <table class="table table-striped">
                                                    <thead class="smooth">
                                                        <tr>
                                                            <th style="width: 25%;">Nama Obat</th>
                                                            <th style="width: 10%;">UOM</th>
                                                            <th style="width: 5%;">Stok</th>
                                                            <th style="width: 5%;">Harga</th>
                                                            <th style="width: 10%;">Qty</th>
                                                            <th style="width: 10%;">Subtotal Harga</th>
                                                            <th style="width: 15%">Signa</th>
                                                            <th style="width: 15%">Instruksi</th>
                                                            <th style="width: 1%;">&nbsp;</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="table_re"></tbody>
                                                    <tbody>
                                                        <tr>
                                                            <td colspan="8" align="right">Grand Total</td>
                                                            <td align="right"><span id="grand_total"
                                                                    style="text-align: right;" class="numeric">0</span>
                                                                <input type="hidden" name="total_bpjs" id="total_bpjs"
                                                                    value="0" readonly="">
                                                                <input type="hidden" name="is_bpjs" id="is_bpjs"
                                                                    value="f" readonly="">
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="d-flex justify-content-between mt-4">
                                            <button type="button" class="btn btn-outline-secondary" id="tutup">
                                                <span class="mdi mdi-arrow-up-bold-circle-outline"></span> Tutup
                                            </button>
                                            <button type="button" class="btn btn-primary btn-saves-soap" id="bsSOAP"
                                                name="save">
                                                <span class="mdi mdi-content-save"></span> Simpan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <!-- Filter Section -->
                                <div id="view-fitler-soap" class="panel-content collapse" aria-expanded="false">
                                    <div class="card-body no-padding">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="s_tgl_1" class="col-sm-4 control-label">Tgl.
                                                        CPPT</label>
                                                    <div class="input-daterange input-group col-sm-8"
                                                        id="demo-date-range">
                                                        <input name="sdate" type="text"
                                                            class="datepicker form-control" id="sdate" readonly />
                                                        <span class="input-group-addon">s/d</span>
                                                        <input name="edate" type="text"
                                                            class="datepicker form-control" id="edate" readonly />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="dept" class="col-sm-4 control-label">Status
                                                        Rawat</label>
                                                    <div class="col-sm-8">
                                                        <select class="form-control sel2" id="dept" name="dept">
                                                            <option value=""></option>
                                                            <option value="ri">Rawat Inap</option>
                                                            <option value="rj">Rawat Jalan</option>
                                                            <option value="igd">IGD</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="role" class="col-sm-4 control-label">Tipe
                                                        CPPT</label>
                                                    <div class="col-sm-8">
                                                        <select class="form-control sel2" id="role" name="role">
                                                            <option value=""></option>
                                                            <option value="dokter">Dokter</option>
                                                            <option value="perawat">Perawat</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Filter Section -->
                            </div>
                        </div>
                    </form>

                    <div class="col-md-6">
                        <hr style="border-color: #868686; margin-bottom: 50px;">
                        <div class="card-body">
                            <div class="table-responsive no-margin">
                                <table id="cppt-dokter" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="width:25%;">Tanggal</th>
                                            <th style="width: 70%;">Catatan</th>
                                            <th style="width: 6%;">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody id="list_soap_dokter">
                                        <tr>
                                            <td class="text-center">
                                            </td>
                                            <td>
                                                <table width="100%" class="table-soap nurse">
                                                </table>
                                            </td>
                                            <td>
                                            </td>
                                        </tr>
                                        <!-- Additional rows here -->
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-center">
                                                <!-- Pagination will be handled by DataTables -->
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div><!--end .table-responsive -->
                        </div>
                    </div>
                    <div class="col-md-6">
                        <hr style="border-color: #868686; margin-bottom: 50px;">
                        <div class="card-body">
                            <div class="table-responsive no-margin">
                                <table id="cppt-perawat" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="width:25%;">Tanggal</th>
                                            <th style="width: 70%;">Catatan</th>
                                            <th style="width: 6%;">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody id="list_soap_perawat">
                                        <tr>
                                            <td class="text-center">
                                            </td>
                                            <td>
                                                <table width="100%" class="table-soap nurse">
                                                </table>
                                            </td>
                                            <td>
                                            </td>
                                        </tr>
                                        <!-- Additional rows here -->
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-center">
                                                <!-- Pagination will be handled by DataTables -->
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div><!--end .table-responsive -->
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endif
@endsection
@section('plugin-erm')
    <script script src="/js/formplugins/select2/select2.bundle.js"></script>
    {{-- @include('pages.simrs.erm.partials.action-js.cppt-dokter-rajal') --}}
    <script>
        $(document).ready(function() {

            $('#cppt_doctor_id').val("{{ $registration->doctor_id }}")
            $('.btnAdd').click(function() {
                $('#add_soap').collapse('show');
            });

            $('#tutup').on('click', function() {
                $('#add_soap').collapse('hide');

                $('.btnAdd').attr('aria-expanded', 'false');
                $('.btnAdd').addClass('collapsed');
            });

            // Saat tombol Save Final diklik
            $('#bsSOAP').on('click', function() {
                submitFormCPPT(); // Panggil fungsi submitForm dengan parameter final
            });

            function loadCPPTDokter() {
                $.ajax({
                    // url: '{{-- route('cppt.get') --}}', // Mengambil route Laravel
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        // Bersihkan tabel
                        $('#list_soap_dokter').empty();

                        // Iterasi setiap data dan tambahkan ke dalam tabel
                        $.each(response, function(index, data) {
                            var row = `
                            <tr>
                                <td class="text-center">
                                    <div class="deep-purple-text">${data.created_at}<br>
                                        <span class="green-text" style="font-weight:400;">${data.tipe_rawat}</span><br>
                                        <b style="font-weight: 400;">Dokter ID: ${data.doctor_id}</b><br>
                                        <div class="input-oleh deep-orange-text">Input oleh: ${data.user_id}</div>
                                        <a href="javascript:void(0)" class="d-block text-uppercase badge badge-primary"><i class="mdi mdi-plus-circle"></i> Verifikasi</a>
                                        <div>
                                            <img src="http://192.168.1.253/real/include/images/ttd_blank.png" width="200px;" height="100px;">
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <table width="100%" class="table-soap nurse">
                                        <tbody>
                                            <tr><td colspan="3" class="soap-text title">CPPT</td></tr>
                                            <tr><td class="soap-text deep-purple-text text-center" width="8%">S</td><td>${data.subjective.replace(/\n/g, "<br>")}</td></tr>
                                            <tr><td class="soap-text deep-purple-text text-center">O</td><td>${data.objective.replace(/\n/g, "<br>")}</td></tr>
                                            <tr><td class="soap-text deep-purple-text text-center">A</td><td>${data.assesment}</td></tr>
                                            <tr><td class="soap-text deep-purple-text text-center">P</td><td>${data.planning}</td></tr>
                                            <tr><td class="soap-text deep-purple-text text-center">I</td><td>${data.instruksi}</td></tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td>
                                    <i class="mdi mdi-content-copy blue-text pointer mdi-18px copy-soap" data-id="${data.id}" title="Copy"></i>
                                    <i class="mdi mdi-delete-forever red-text pointer mdi-18px hapus-soap" data-id="${data.id}" title="Hapus"></i>
                                    <i class="mdi mdi-pencil red-text pointer mdi-18px edit-soap" data-id="${data.id}" title="Edit SOAP & Resep Elektronik"></i>
                                    <i class="mdi mdi-printer blue-text pointer mdi-18px print-antrian" data-id="${data.id}" title="Print Antrian Resep"></i>
                                </td>
                            </tr>
                        `;
                            // Tambahkan ke dalam tabel
                            $('#list_soap_dokter').append(row);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }

            function loadCPPTPerawat() {
                $.ajax({
                    url: '{{ route('cppt.get') }}',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        registration_id: "{{ $registration->id }}",
                    },
                    success: function(response) {
                        // Bersihkan isi tbody
                        $('#list_soap_perawat').empty();

                        // Hancurkan DataTable kalau sudah diinisialisasi
                        if ($.fn.DataTable.isDataTable('#cppt-perawat')) {
                            $('#cppt-perawat').DataTable().destroy();
                        }

                        // Loop data dan tambahkan baris
                        $.each(response, function(index, data) {
                            console.log(data);

                            let formattedDate = new Intl.DateTimeFormat('id-ID', {
                                weekday: 'long',
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: false,
                                timeZone: 'Asia/Jakarta'
                            }).format(new Date(data.created_at));
                            var row = `
                                <tr>
                                    <td class="text-center">
                                        <div class="deep-purple-text">
                                            <div class="text-primary mt-3" style="font-weight:400;">${formattedDate}</div>
                                            <div class="text-success mt-3" style="font-weight:400;">${data.tipe_rawat}</div>
                                            <div class="input-oleh text-warning mb-2 mt-1">${data.user.name}</div>
                                            <a href="javascript:void(0)" class="d-block text-uppercase badge badge-primary"><i class="mdi mdi-plus-circle"></i> Verifikasi</a>
                                            <div>
                                                <img src="http://192.168.1.253/real/include/images/ttd_blank.png" width="200px;" height="100px;">
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <table width="100%" class="table-soap nurse">
                                            <tbody>
                                                <tr><td colspan="3" class="soap-text title">CPPT</td></tr>
                                                <tr><td class="soap-text deep-purple-text text-center" width="8%">S</td><td>${data.subjective.replace(/\n/g, "<br>")}</td></tr>
                                                <tr><td class="soap-text deep-purple-text text-center">O</td><td>${data.objective.replace(/\n/g, "<br>")}</td></tr>
                                                <tr><td class="soap-text deep-purple-text text-center">A</td><td>${data.assesment}</td></tr>
                                                <tr><td class="soap-text deep-purple-text text-center">P</td><td>${data.planning}</td></tr>
                                                <tr><td class="soap-text deep-purple-text text-center">I</td><td>${data.instruksi}</td></tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td>
                                        <i class="mdi mdi-content-copy blue-text pointer mdi-18px copy-soap" data-id="${data.id}" title="Copy"></i>
                                        <i class="mdi mdi-delete-forever red-text pointer mdi-18px hapus-soap" data-id="${data.id}" title="Hapus"></i>
                                        <i class="mdi mdi-pencil red-text pointer mdi-18px edit-soap" data-id="${data.id}" title="Edit SOAP & Resep Elektronik"></i>
                                        <i class="mdi mdi-printer blue-text pointer mdi-18px print-antrian" data-id="${data.id}" title="Print Antrian Resep"></i>
                                    </td>
                                </tr>
                            `;
                            $('#list_soap_perawat').append(row);
                        });

                        // Inisialisasi ulang DataTable
                        $('#cppt-perawat').DataTable({
                            paging: false,
                            searching: false,
                            ordering: false,
                            responsive: true,
                            pageLength: 5,
                            info: false // <-- ini yang menyembunyikan "Showing x to y of z entries"
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }

            function submitFormCPPT(actionType) {
                const form = $('#cppt-dokter-rajal-form');
                const registrationNumber = "{{ $registration->registration_number }}";

                const url =
                    "{{ route('cppt.dokter-rajal.store', ['type' => 'rawat-jalan', 'registration_number' => '__registration_number__']) }}"
                    .replace('__registration_number__', registrationNumber);

                // Now you can use `url` in your form submission or AJAX request

                let formData = form.serialize(); // Ambil data dari form

                // Tambahkan tipe aksi (draft atau final) ke data form
                formData += '&action_type=' + actionType;

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,
                    success: function(response) {
                        if (actionType === 'draft') {
                            showSuccessAlert('Data berhasil disimpan sebagai draft!');
                        } else {
                            showSuccessAlert('Data berhasil disimpan sebagai final!');
                        }
                        setTimeout(() => {
                            console.log('Reloading the page now.');
                            window.location.reload();
                        }, 1000);
                    },
                    error: function(response) {
                        // Tangani error
                        var errors = response.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            showErrorAlert(value[0]);
                        });
                    }
                });
            }

            loadCPPTPerawat();
        });
    </script>

    <script>
        $(document).ready(function() {
            $('body').addClass('layout-composed');
            $('.select2').select2({
                placeholder: 'Pilih Item',
            });
            $('#departement_id').select2({
                placeholder: 'Pilih Klinik',
            });

            $('#doctor_id').select2({
                placeholder: 'Pilih Dokter',
            });

            $('#cppt_doctor_id').select2({
                placeholder: 'Pilih Dokter',
            });

            $('#toggle-pasien').on('click', function() {
                var target = $('#js-slide-left'); // Mengambil elemen target berdasarkan data-target
                var backdrop = $('.slide-backdrop'); // Mengambil backdrop

                // Toggle kelas untuk menampilkan atau menyembunyikan panel dan backdrop
                target.toggleClass('hide');
                backdrop.toggleClass('show');
            });

            // Close the panel if the backdrop is clicked
            $('.slide-backdrop').on('click', function() {
                $('#js-slide-left').removeClass('slide-on-mobile-left-show');
                $(this).removeClass('show');
            });
        });
    </script>
@endsection
