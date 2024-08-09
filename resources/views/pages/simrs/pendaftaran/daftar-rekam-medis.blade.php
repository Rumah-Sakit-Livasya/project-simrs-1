@extends('inc.layout')
@section('title', 'Daftar Rekam Medis')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row justify-content-center">
            <div class="col-xl-8">
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
                                <div class="row justify-content-center">
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label for="medical_record_number">No. RM</label>
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
                                                    <label class="form-label text-end" for="telp">
                                                        Penjamin
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <select class="form-control w-100 select2" id="penjamin_id"
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        name="penjamin_id">
                                                        <option value=""></option>
                                                        @foreach ($penjamins as $penjamin)
                                                            <option value="{{ $penjamin->id }}">{{ $penjamin->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('penjamin_id')
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
                                                    <label for="name">Nama</label>
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
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-5" style="text-align: right">
                                                    <label class="form-label text-end" for="mobile_phone_number">
                                                        No. HP
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" value="{{ request('mobile_phone_number') }}"
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control" id="mobile_phone_number"
                                                        name="mobile_phone_number">
                                                    @error('mobile_phone_number')
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
                                                    <label for="date_of_birth">Tanggal Lahir</label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" value="{{ request('date_of_birth') }}"
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control" id="date_of_birth" name="date_of_birth">
                                                    @error('date_of_birth')
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
                                </div>
                                <div class="row justify-content-end mt-4">
                                    <div class="col-xl-5">
                                        <a href="{{ route('pendaftaran.pasien.pendaftaran_pasien_baru') }}"
                                            class="btn btn-outline-primary waves-effect waves-themed">
                                            <span class="fal fa-plus-circle"></span>
                                            Tambah Pasien
                                        </a>
                                        <button type="submit" class="btn btn-outline-primary waves-effect waves-themed">
                                            <span class="fal fa-search"></span>
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
                                                    @if ($patient->registration()->orderBy('created_at', 'desc')->first()->status === 'online')
                                                        <a
                                                            href="{{ route('detail.registrasi.pasien', $patient->registration()->orderBy('created_at', 'desc')->first()->id) }}">
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
                                                    @if ($patient->registration()->orderBy('created_at', 'desc')->first()->status === 'online')
                                                        <a
                                                            href="{{ route('detail.registrasi.pasien', $patient->registration()->orderBy('created_at', 'desc')->first()->id) }}">
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
                                                    @if ($patient->registration()->orderBy('created_at', 'desc')->first()->status === 'online')
                                                        <a
                                                            href="{{ route('detail.registrasi.pasien', $patient->registration()->orderBy('created_at', 'desc')->first()->id) }}">
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
                                                    @if ($patient->registration()->orderBy('created_at', 'desc')->first()->status === 'online')
                                                        <a
                                                            href="{{ route('detail.registrasi.pasien', $patient->registration()->orderBy('created_at', 'desc')->first()->id) }}">
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
                                                    @if ($patient->registration()->orderBy('created_at', 'desc')->first()->status === 'online')
                                                        <a
                                                            href="{{ route('detail.registrasi.pasien', $patient->registration()->orderBy('created_at', 'desc')->first()->id) }}">
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
                                                    @if ($patient->registration()->orderBy('created_at', 'desc')->first()->status === 'online')
                                                        <a
                                                            href="{{ route('detail.registrasi.pasien', $patient->registration()->orderBy('created_at', 'desc')->first()->id) }}">
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
                                                    @if ($patient->registration()->orderBy('created_at', 'desc')->first()->status === 'online')
                                                        <a
                                                            href="{{ route('detail.registrasi.pasien', $patient->registration()->orderBy('created_at', 'desc')->first()->id) }}">
                                                            {{ $patient->penjamin->name }}
                                                        </a>
                                                    @endif
                                                @else
                                                    <a href="{{ route('detail.pendaftaran.pasien', $patient->id) }}">
                                                        {{ $patient->penjamin->name }}
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
    <script>
        $(document).ready(function() {

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
