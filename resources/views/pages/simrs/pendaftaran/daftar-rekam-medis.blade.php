@extends('inc.layout')
@section('title', 'Daftar Rekam Medis')
@section('content')

    <style>
        .form-control.datepicker {
            width: 100% !important;
        }
    </style>
    <main id="js-page-content" role="main" class="page-content">
        {{-- =================================================================
        // PANEL FILTER PENCARIAN (BAGIAN YANG DIPERBARUI)
        // ================================================================= --}}
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
                            <form action="/daftar-rekam-medis" method="get">
                                @csrf
                                <div class="row">
                                    {{-- No. RM --}}
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label" for="medical_record_number">No. RM</label>
                                        <input type="text" value="{{ request('medical_record_number') }}"
                                            class="form-control" id="medical_record_number" name="medical_record_number"
                                            placeholder="Contoh: 12-34-56" onkeyup="formatAngka(this)">
                                        @error('medical_record_number')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Nama --}}
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label" for="name">Nama</label>
                                        <input type="text" value="{{ request('name') }}" class="form-control"
                                            id="name" name="name" placeholder="Masukkan nama pasien">
                                        @error('name')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Tanggal Lahir --}}
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label" for="date_of_birth">Tanggal Lahir</label>
                                        <input type="text" value="{{ request('date_of_birth') }}"
                                            class="form-control datepicker" id="date_of_birth" name="date_of_birth"
                                            placeholder="YYYY-MM-DD" autocomplete="off">
                                        @error('date_of_birth')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    {{-- Penjamin --}}
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label" for="penjamin_id">Penjamin</label>
                                        <select class="form-control w-100 select2" id="penjamin_id" name="penjamin_id">
                                            <option value="">Pilih Penjamin</option>
                                            @foreach ($penjamins as $penjamin)
                                                <option value="{{ $penjamin->id }}"
                                                    {{ request('penjamin_id') == $penjamin->id ? 'selected' : '' }}>
                                                    {{ $penjamin->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('penjamin_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- No. HP --}}
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label" for="mobile_phone_number">No. HP</label>
                                        <input type="text" value="{{ request('mobile_phone_number') }}"
                                            class="form-control" id="mobile_phone_number" name="mobile_phone_number"
                                            placeholder="Masukkan nomor HP">
                                        @error('mobile_phone_number')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Alamat --}}
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label" for="address">Alamat</label>
                                        <input type="text" value="{{ request('address') }}" class="form-control"
                                            id="address" name="address" placeholder="Masukkan alamat">
                                        @error('address')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Tombol Aksi --}}
                                <div class="row justify-content-end mt-3">
                                    <div class="col-auto">
                                        <a href="{{ route('pendaftaran.pasien.pendaftaran_pasien_baru') }}"
                                            class="btn btn-primary waves-effect waves-themed">
                                            <span class="fal fa-plus-circle mr-1"></span>
                                            Tambah Pasien
                                        </a>
                                        <button type="submit" class="btn btn-primary waves-effect waves-themed">
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
        {{-- =================================================================
        // AKHIR DARI BAGIAN YANG DIPERBARUI
        // ================================================================= --}}


        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Daftar <span class="fw-300"><i>Rekam Medis</i></span>
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
                                        <th>No. RM</th>
                                        <th>Nama Lengkap</th>
                                        <th>Alamat</th>
                                        <th>Tempat & Tgl. Lahir</th>
                                        <th>No. Hp</th>
                                        <th>Keluarga yang dapat dihubungi</th>
                                        <th>Penjamin</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($patients as $patient)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @if ($patient->registration()->orderBy('created_at', 'desc')->first() !== null)
                                                    @if ($patient->registration()->orderBy('created_at', 'desc')->first()->status === 'aktif')
                                                        <a
                                                            href="{{ route('detail.registrasi.pasien', $patient->registration()->orderBy('created_at', 'desc')->first()->id) }}">
                                                            {{ $patient->medical_record_number }}
                                                        </a>
                                                    @else
                                                        <a href="{{ route('detail.pendaftaran.pasien', $patient->id) }}">
                                                            {{ $patient->medical_record_number }}
                                                        </a>
                                                    @endif
                                                @else
                                                    <a href="{{ route('detail.pendaftaran.pasien', $patient->id) }}">
                                                        {{ $patient->medical_record_number }}
                                                    </a>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($patient->registration()->orderBy('created_at', 'desc')->first() !== null)
                                                    @if ($patient->registration()->orderBy('created_at', 'desc')->first()->status === 'aktif')
                                                        <a
                                                            href="{{ route('detail.registrasi.pasien', $patient->registration()->orderBy('created_at', 'desc')->first()->id) }}">
                                                            {{ $patient->name }}
                                                        </a>
                                                    @else
                                                        <a href="{{ route('detail.pendaftaran.pasien', $patient->id) }}">
                                                            {{ $patient->name }}
                                                        </a>
                                                    @endif
                                                @else
                                                    <a href="{{ route('detail.pendaftaran.pasien', $patient->id) }}">
                                                        {{ $patient->name }}
                                                    </a>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($patient->registration()->orderBy('created_at', 'desc')->first() !== null)
                                                    @if ($patient->registration()->orderBy('created_at', 'desc')->first()->status === 'aktif')
                                                        <a
                                                            href="{{ route('detail.registrasi.pasien', $patient->registration()->orderBy('created_at', 'desc')->first()->id) }}">
                                                            {{ $patient->address }}
                                                        </a>
                                                    @else
                                                        <a href="{{ route('detail.pendaftaran.pasien', $patient->id) }}">
                                                            {{ $patient->address }}
                                                        </a>
                                                    @endif
                                                @else
                                                    <a href="{{ route('detail.pendaftaran.pasien', $patient->id) }}">
                                                        {{ $patient->address }}
                                                    </a>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($patient->registration()->orderBy('created_at', 'desc')->first() !== null)
                                                    @if ($patient->registration()->orderBy('created_at', 'desc')->first()->status === 'aktif')
                                                        <a
                                                            href="{{ route('detail.registrasi.pasien', $patient->registration()->orderBy('created_at', 'desc')->first()->id) }}">
                                                            {{ $patient->place }} , {{ $patient->date_of_birth }}
                                                        </a>
                                                    @else
                                                        <a href="{{ route('detail.pendaftaran.pasien', $patient->id) }}">
                                                            {{ $patient->place }} , {{ $patient->date_of_birth }}
                                                        </a>
                                                    @endif
                                                @else
                                                    <a href="{{ route('detail.pendaftaran.pasien', $patient->id) }}">
                                                        {{ $patient->place }} , {{ $patient->date_of_birth }}
                                                    </a>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($patient->registration()->orderBy('created_at', 'desc')->first() !== null)
                                                    @if ($patient->registration()->orderBy('created_at', 'desc')->first()->status === 'aktif')
                                                        <a
                                                            href="{{ route('detail.registrasi.pasien', $patient->registration()->orderBy('created_at', 'desc')->first()->id) }}">
                                                            {{ $patient->mobile_phone_number }}
                                                        </a>
                                                    @else
                                                        <a href="{{ route('detail.pendaftaran.pasien', $patient->id) }}">
                                                            {{ $patient->mobile_phone_number }}
                                                        </a>
                                                    @endif
                                                @else
                                                    <a href="{{ route('detail.pendaftaran.pasien', $patient->id) }}">
                                                        {{ $patient->mobile_phone_number }}
                                                    </a>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($patient->registration()->orderBy('created_at', 'desc')->first() !== null)
                                                    @if ($patient->registration()->orderBy('created_at', 'desc')->first()->status === 'aktif')
                                                        <a
                                                            href="{{ route('detail.registrasi.pasien', $patient->registration()->orderBy('created_at', 'desc')->first()->id) }}">
                                                            {{ $patient->family->name ?? '*Tidak Diketahui' }}
                                                        </a>
                                                    @else
                                                        <a href="{{ route('detail.pendaftaran.pasien', $patient->id) }}">
                                                            {{ $patient->family->name ?? '*Tidak Diketahui' }}
                                                        </a>
                                                    @endif
                                                @else
                                                    <a href="{{ route('detail.pendaftaran.pasien', $patient->id) }}">
                                                        {{ $patient->family->name ?? '*Tidak Diketahui' }}
                                                    </a>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($patient->registration()->orderBy('created_at', 'desc')->first() !== null)
                                                    @if ($patient->registration()->orderBy('created_at', 'desc')->first()->status === 'aktif')
                                                        <a
                                                            href="{{ route('detail.registrasi.pasien', $patient->registration()->orderBy('created_at', 'desc')->first()->id) }}">
                                                            {{ $patient->penjamin->name ?? '-' }}
                                                        </a>
                                                    @else
                                                        <a href="{{ route('detail.pendaftaran.pasien', $patient->id) }}">
                                                            {{ $patient->penjamin->name ?? '-' }}
                                                        </a>
                                                    @endif
                                                @else
                                                    <a href="{{ route('detail.pendaftaran.pasien', $patient->id) }}">
                                                        {{ $patient->penjamin->name ?? '-' }}
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>No. RM</th>
                                        <th>Nama Lengkap</th>
                                        <th>Alamat</th>
                                        <th>Tempat & Tgl. Lahir</th>
                                        <th>No. Hp</th>
                                        <th>Keluarga yang dapat dihubungi</th>
                                        <th>Penjamin</th>
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
    <script>
        $(document).ready(function() {

            // Inisialisasi Select 2
            $('.select2').select2({
                dropdownCssClass: "move-up"
            });

            // Inisialisasi Datepicker
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true
            });

            $('#loading-spinner').show();
            // Inisialisasi datatable
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


        // Fungsi format input No. RM
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
