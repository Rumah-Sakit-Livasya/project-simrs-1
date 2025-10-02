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

                            <form action="{{ route('tagihan.pasien.search') }}" method="post">
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
                                                        value="{{ request('registration_date', old('registration_date', '01/01/2018 - 01/15/2018')) }}">
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
                                                    <input type="text"
                                                        value="{{ request('medical_record_number', old('medical_record_number')) }}"
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
                                                    <label class="form-label" for="name">Nama Pasien</label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" value="{{ request('name', old('name')) }}"
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
                                                            {{ request('registration_type', old('registration_type')) == 'rawat-inap' ? 'selected' : '' }}>
                                                            Rawat Inap</option>
                                                        <option value="rawat-jalan"
                                                            {{ request('registration_type', old('registration_type')) == 'rawat-jalan' ? 'selected' : '' }}>
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
                                                    <input type="text" value="{{ request('address', old('address')) }}"
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
                                            <div class="row align-items-center">
                                                <div class="col-xl-5 text-end">
                                                    <label class="form-label" for="departement_id">Poly/Unit</label>
                                                </div>
                                                <div class="col-xl">
                                                    <select class="select2 form-control w-100" id="departement_id"
                                                        name="departement_id">
                                                        <option value=""></option>
                                                        @foreach ($departements as $departement)
                                                            <option value="{{ $departement->id }}"
                                                                {{ request('departement_id', old('departement_id')) == $departement->id ? 'selected' : '' }}>
                                                                {{ $departement->name }}
                                                            </option>
                                                        @endforeach
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
                            <div class="mb-3">
                                <button id="btn-merge" class="btn btn-warning" disabled>
                                    <i class="fal fa-compress-alt mr-1"></i>
                                    Merge Tagihan Terpilih
                                </button>
                            </div>
                            <!-- datatable start -->
                            <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                <i id="loading-spinner" class="fas fa-spinner fa-spin"></i>
                                <thead class="bg-primary-600" style="font-size: .7rem">
                                    <tr>
                                        <th><input type="checkbox" id="select-all"></th>
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
                                        <tr style="font-size: .8rem">
                                            <td><input type="checkbox" class="merge-checkbox"
                                                    value="{{ $tagihan->id }}"></td>
                                            <td>{{ $loop->iteration }}</td>

                                            {{-- Tanggal dibuat --}}
                                            <td>
                                                @if ($tagihan && $tagihan->created_at)
                                                    <a href="{{ route('tagihan.pasien.detail', $tagihan->id) }}">
                                                        {{ tgl_waktu($tagihan->created_at) }}
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            {{-- Nomor rekam medis --}}
                                            <td>
                                                @if ($tagihan && $tagihan->registration && $tagihan->registration->patient)
                                                    <a href="{{ route('tagihan.pasien.detail', $tagihan->id) }}">
                                                        {{ $tagihan->registration->patient->medical_record_number }}
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            {{-- Nomor registrasi --}}
                                            <td>
                                                @if ($tagihan && $tagihan->registration)
                                                    <a href="{{ route('tagihan.pasien.detail', $tagihan->id) }}">
                                                        {{ $tagihan->registration->registration_number }}
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            {{-- Nama pasien --}}
                                            <td>
                                                @if ($tagihan && $tagihan->registration && $tagihan->registration->patient)
                                                    <a href="{{ route('tagihan.pasien.detail', $tagihan->id) }}">
                                                        {{ $tagihan->registration->patient->name }}
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            {{-- Dokter --}}
                                            <td>
                                                @if ($tagihan && $tagihan->registration && $tagihan->registration->doctor && $tagihan->registration->doctor->employee)
                                                    <a href="{{ route('tagihan.pasien.detail', $tagihan->id) }}">
                                                        {{ $tagihan->registration->doctor->employee->fullname }}
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            {{-- Poliklinik / Rawat Inap --}}
                                            <td>
                                                @if ($tagihan && $tagihan->registration)
                                                    <a href="{{ route('tagihan.pasien.detail', $tagihan->id) }}">
                                                        {{ $tagihan->registration['registration_type'] == 'rawat-inap' ? 'RAWAT INAP' : $tagihan->registration->poliklinik }}
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            {{-- Penjamin --}}
                                            <td>
                                                @if ($tagihan && $tagihan->registration && $tagihan->registration->penjamin)
                                                    <a href="{{ route('tagihan.pasien.detail', $tagihan->id) }}">
                                                        {{ strtolower($tagihan->registration->penjamin->nama_perusahaan) === 'standar' ? 'UMUM' : $tagihan->registration->penjamin->nama_perusahaan }}
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            {{-- Status --}}
                                            <td class="text-center">
                                                {{-- {{ $tagihan->tagihanPasien }} --}}
                                                <a href="{{ route('tagihan.pasien.detail', $tagihan->id) }}">
                                                    @if (!$tagihan->tagihanPasien()->exists())
                                                        <i class='bx bx-money text-secondary'
                                                            style="font-size: 1.2em;"></i>
                                                    @elseif ($tagihan->status === 'final')
                                                        <i class='bx bx-money text-success' style="font-size: 1.2em;"></i>
                                                    @else
                                                        <i class='bx bx-money text-danger' style="font-size: 1.2em;"></i>
                                                    @endif
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
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

        <!-- Modal Merge -->
        <div class="modal fade" id="mergeModal" tabindex="-1" role="dialog" aria-labelledby="mergeModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="mergeModalLabel">Pilih Tagihan Tujuan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Silakan pilih satu tagihan sebagai tujuan utama. Semua item dari tagihan lain akan dipindahkan ke
                            tagihan ini.</p>
                        <form id="mergeForm">
                            @csrf
                            <div id="destination-options">
                                {{-- Opsi pilihan akan di-generate oleh JavaScript --}}
                            </div>
                            <input type="hidden" name="source_ids" id="source_ids">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="button" id="confirm-merge" class="btn btn-primary">Konfirmasi Merge</button>
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
                startDate: moment("{{ $startDate }}", 'YYYY-MM-DD'),
                endDate: moment("{{ $endDate }}", 'YYYY-MM-DD'),
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
                })
                .addClass('table-sm');

            const btnMerge = $('#btn-merge');
            const mergeCheckboxes = $('.merge-checkbox');
            const selectAllCheckbox = $('#select-all');

            // Fungsi untuk memeriksa status checkbox
            function checkSelection() {
                const selectedCount = $('.merge-checkbox:checked').length;
                btnMerge.prop('disabled', selectedCount < 2);
            }

            // Event handler untuk checkbox 'select all'
            selectAllCheckbox.on('change', function() {
                mergeCheckboxes.prop('checked', $(this).prop('checked'));
                checkSelection();
            });

            // Event handler untuk checkbox per baris
            mergeCheckboxes.on('change', function() {
                checkSelection();
            });

            // Event handler saat tombol "Merge Tagihan" diklik
            btnMerge.on('click', function() {
                const selectedIds = [];
                const optionsContainer = $('#destination-options');
                optionsContainer.html(''); // Kosongkan opsi sebelumnya

                $('.merge-checkbox:checked').each(function() {
                    const tagihanId = $(this).val();
                    selectedIds.push(tagihanId);

                    // Ambil data dari baris tabel untuk ditampilkan di modal
                    const row = $(this).closest('tr');
                    const noRm = row.find('td').eq(3).text().trim();
                    const noReg = row.find('td').eq(4).text().trim();
                    const namaPasien = row.find('td').eq(5).text().trim();

                    // Buat radio button untuk pilihan tujuan
                    optionsContainer.append(`
                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="destination_id" id="dest_${tagihanId}" value="${tagihanId}">
                    <label class="form-check-label" for="dest_${tagihanId}">
                        <strong>${namaPasien}</strong> (No. Reg: ${noReg}, No. RM: ${noRm})
                    </label>
                </div>
            `);
                });

                // Setel radio button pertama sebagai default
                optionsContainer.find('input[type=radio]').first().prop('checked', true);

                $('#mergeModal').modal('show');
            });

            // Event handler saat tombol "Konfirmasi Merge" di modal diklik
            // Event handler saat tombol "Konfirmasi Merge" di modal diklik (SUDAH DIPERBAIKI)
            $('#confirm-merge').on('click', function() {
                const destinationId = $('input[name="destination_id"]:checked').val();

                // Ambil SEMUA ID yang dicentang
                const allSelectedIds = [];
                $('.merge-checkbox:checked').each(function() {
                    allSelectedIds.push($(this).val());
                });

                // JANGAN DI-FILTER. Kirim semua ID yang terpilih.
                // Backend akan menangani logika mana yang perlu dipindah dan mana yang tidak.
                const idsToProcess = allSelectedIds;

                // Kirim data via AJAX
                $.ajax({
                    url: '{{ route('tagihan.pasien.merge') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        destination_id: destinationId,
                        source_ids: idsToProcess // Menggunakan variabel baru yang berisi semua ID
                    },
                    beforeSend: function() {
                        $(this).prop('disabled', true).html(
                            '<i class="fas fa-spinner fa-spin"></i> Memproses...');
                    },
                    success: function(response) {
                        $('#mergeModal').modal('hide');
                        showSuccessAlert(response.message);

                        // Redirect ke halaman detail bilingan yang dituju, ke bagian #tagihan-pasien
                        const destinationId = $('input[name="destination_id"]:checked').val();
                        if (destinationId) {
                            window.location.href =
                                '{{ url('simrs/kasir/tagihan-pasien') }}/' + destinationId +
                                '#tagihan-pasien';
                        } else {
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan. Silakan coba lagi.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        alert(errorMessage);
                        console.error(xhr.responseText);
                    },
                    complete: function() {
                        $('#confirm-merge').prop('disabled', false).html('Konfirmasi Merge');
                    }
                });
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
