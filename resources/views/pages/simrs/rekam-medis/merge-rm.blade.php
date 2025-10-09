@extends('inc.layout')
@section('title', 'Penggabungan Rekam Medis')
{{-- CSS Kustom untuk Autocomplete --}}
@section('extended-css')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <style>
        .ui-autocomplete {
            max-height: 250px;
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 2000 !important;
        }

        .ui-menu-item>a {
            display: block;
            padding: 10px;
            clear: both;
            font-weight: 400;
            line-height: 1.42857143;
            color: #333;
            white-space: nowrap;
            text-decoration: none;
        }

        .ui-menu-item>a:hover {
            background-color: #3c6eb4;
            color: #fff;
        }

        .ui-menu-item-wrapper .patient-name {
            font-weight: 500;
            color: #3c6eb4;
        }

        .ui-menu-item-wrapper:hover .patient-name {
            color: #fff;
        }

        .ui-menu-item-wrapper .patient-details {
            font-size: 0.8rem;
            color: #6c757d;
        }

        .ui-menu-item-wrapper:hover .patient-details {
            color: #f8f9fa;
        }

        .ui-autocomplete-loading {
            background: white url("{{ asset('img/loaders/ajax-loader.gif') }}") right center no-repeat;
        }

        #merge-choice-panel {
            display: none;/ Sembunyikan secara default */ border: 1px solid #e9edef;
            background-color: #f6f8fa;
            border-radius: 4px;
        }
    </style>
@endsection
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Formulir <span class="fw-300"><i>Penggabungan Rekam Medis</i></span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form name="fs" id="fs" action="{{ route('rekam-medis.merge.action') }}"
                                method="POST">
                                @csrf
                                <div class="row">
                                    {{-- Kolom Kiri (RM Asal) --}}
                                    <div class="col-md-6">
                                        <h4 class="mb-3">Informasi Rekam Medis (Asal)</h4>
                                        <div class="form-group row">
                                            <label for="rm_from" class="col-sm-4 col-form-label">Pencarian No. RM</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="rm_from" id="rm_from" class="form-control"
                                                    autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="norm" class="col-sm-4 col-form-label">No. RM</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="norm" id="norm" class="form-control"
                                                    readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="name_real" class="col-sm-4 col-form-label">Nama Pasien</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="name_real" id="name_real" class="form-control"
                                                    readonly>
                                            </div>
                                        </div>
                                        {{-- Input lainnya untuk RM Asal... --}}
                                        <div class="form-group row">
                                            <label for="identification_cards" class="col-sm-4 col-form-label">No KTP</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="identification_cards" id="identification_cards"
                                                    class="form-control" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Tempat & Tgl Lahir</label>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <input name="place_of_birth" type="text" class="form-control"
                                                        id="place_of_birth" readonly>
                                                    <div class="input-group-append"><span class="input-group-text">,</span>
                                                    </div>
                                                    <input name="date_of_birth" type="text" class="form-control"
                                                        id="date_of_birth" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="address" class="col-sm-4 col-form-label">Alamat</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="address" id="address" class="form-control"
                                                    readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="mobile_number" class="col-sm-4 col-form-label">No. HP</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="mobile_number" id="mobile_number"
                                                    class="form-control" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Kolom Kanan (RM Tujuan) --}}
                                    <div class="col-md-6">
                                        <h4 class="mb-3">Informasi Rekam Medis (Tujuan)</h4>
                                        <div class="form-group row">
                                            <label for="rm_to" class="col-sm-4 col-form-label">Pencarian No. RM</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="rm_to" id="rm_to" class="form-control"
                                                    autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="norm_to" class="col-sm-4 col-form-label">No. RM</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="norm_to" id="norm_to" class="form-control"
                                                    readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="name_real_to" class="col-sm-4 col-form-label">Nama Pasien</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="name_real_to" id="name_real_to"
                                                    class="form-control" readonly>
                                            </div>
                                        </div>
                                        {{-- Input lainnya untuk RM Tujuan... --}}
                                        <div class="form-group row">
                                            <label for="identification_cards_to" class="col-sm-4 col-form-label">No
                                                KTP</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="identification_cards_to"
                                                    id="identification_cards_to" class="form-control" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Tempat & Tgl Lahir</label>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <input name="place_of_birth_to" type="text" class="form-control"
                                                        id="place_of_birth_to" readonly>
                                                    <div class="input-group-append"><span
                                                            class="input-group-text">,</span></div>
                                                    <input name="date_of_birth_to" type="text" class="form-control"
                                                        id="date_of_birth_to" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="address_to" class="col-sm-4 col-form-label">Alamat</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="address_to" id="address_to"
                                                    class="form-control" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="mobile_number_to" class="col-sm-4 col-form-label">No. HP</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="mobile_number_to" id="mobile_number_to"
                                                    class="form-control" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Panel Pilihan Penggabungan --}}
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div id="merge-choice-panel" class="p-3">
                                            <h5 class="frame-heading">Pilih Data Master yang Akan Dipertahankan</h5>
                                            <div class="frame-wrap">
                                                <div class="custom-control custom-radio mb-2">
                                                    <input type="radio" class="custom-control-input" id="keep_from"
                                                        name="keep_patient_data">
                                                    <label class="custom-control-label" for="keep_from"
                                                        id="label_keep_from">Pertahankan data dari pasien asal</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" class="custom-control-input" id="keep_to"
                                                        name="keep_patient_data">
                                                    <label class="custom-control-label" for="keep_to"
                                                        id="label_keep_to">Pertahankan data dari pasien tujuan</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 d-flex flex-row align-items-center mt-3">
                                    <button class="btn btn-primary ml-auto" type="button" onclick="chkForm();">Gabungkan
                                        Data</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('plugin')
    {{-- Memuat jQuery UI untuk Autocomplete --}}
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
        $(function() {
            function checkAndShowChoicePanel() {
                if ($('#norm').val() && $('#norm_to').val()) {
                    $('#merge-choice-panel').slideDown();
                } else {
                    $('#merge-choice-panel').slideUp();
                }
            }

            function initAutocomplete(selector, fields, radioInfo) {
                $(selector).autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            url: "{{ route('api.patient.search') }}",
                            dataType: "json",
                            data: {
                                term: request.term
                            },
                            success: function(data) {
                                response(data);
                            }
                        });
                    },
                    minLength: 2,
                    select: function(event, ui) {
                        $(fields.norm).val(ui.item.norm);
                        $(fields.name_real).val(ui.item.name_real);
                        $(fields.identification_cards).val(ui.item.identification_cards);
                        $(fields.place_of_birth).val(ui.item.place_of_birth);
                        $(fields.date_of_birth).val(ui.item.date_of_birth);
                        $(fields.address).val(ui.item.address);
                        $(fields.mobile_number).val(ui.item.mobile_phone_number);

                        // Update radio button
                        $(radioInfo.input).val(ui.item.norm);
                        $(radioInfo.label).html(
                            `Pertahankan data dari: <strong>${ui.item.name_real}</strong> (RM: ${ui.item.norm})`
                        );

                        $(selector).val(ui.item.name_real);
                        checkAndShowChoicePanel(); // Cek setelah memilih
                        return false;
                    }
                }).data("ui-autocomplete")._renderItem = function(ul, item) {
                    return $("<li>").append(
                        `<a class="ui-menu-item-wrapper">
                            <div class="patient-name">${item.name_real}</div>
                            <div class="patient-details">No RM: ${item.norm} - Tgl Lahir: ${item.date_of_birth}</div>
                        </a>`
                    ).appendTo(ul);
                };
            }

            // Inisialisasi untuk RM Asal
            initAutocomplete('#rm_from', {
                norm: '#norm',
                name_real: '#name_real',
                identification_cards: '#identification_cards',
                place_of_birth: '#place_of_birth',
                date_of_birth: '#date_of_birth',
                address: '#address',
                mobile_number: '#mobile_number'
            }, {
                input: '#keep_from',
                label: '#label_keep_from'
            });

            // Inisialisasi untuk RM Tujuan
            initAutocomplete('#rm_to', {
                norm: '#norm_to',
                name_real: '#name_real_to',
                identification_cards: '#identification_cards_to',
                place_of_birth: '#place_of_birth_to',
                date_of_birth: '#date_of_birth_to',
                address: '#address_to',
                mobile_number: '#mobile_number_to'
            }, {
                input: '#keep_to',
                label: '#label_keep_to'
            });

            // Cek panel saat load awal
            checkAndShowChoicePanel();
        });

        function chkForm() {
            const norm = $('#norm').val();
            const norm_to = $('#norm_to').val();
            const selectedMaster = $('input[name="keep_patient_data"]:checked').val();

            if (!norm || !norm_to) {
                showErrorAlertNoRefresh('Silakan pilih Rekam Medis Asal dan Tujuan terlebih dahulu!');
                return;
            }

            if (norm === norm_to) {
                showErrorAlertNoRefresh('Rekam Medis Asal dan Tujuan tidak boleh sama!');
                return;
            }

            // Validasi baru: pastikan radio button sudah dipilih
            if (!selectedMaster) {
                showErrorAlertNoRefresh('Silakan pilih data master pasien yang akan dipertahankan!');
                return;
            }

            Swal.fire({
                title: 'Apakah Anda Yakin?',
                html: `Anda akan menggabungkan semua data dari satu RM ke RM lainnya. <br>Data master yang dipertahankan adalah dari RM <strong>${selectedMaster}</strong>. <br><b class="text-danger">Aksi ini tidak dapat dibatalkan!</b>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Gabungkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#fs').submit();
                }
            });
        }
    </script>
@endsection
