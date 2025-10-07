@extends('inc.layout')
@section('title', 'Order Baru Laboratorium')

{{-- CSS Kustom --}}
@section('extended-css')
    <style>
        .test-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1.25rem;
            /* Padding internal untuk setiap item */
            border-bottom: 1px solid #dee2e6;
        }

        .test-item:last-child {
            border-bottom: none;
        }

        .test-item .test-price {
            min-width: 100px;
            text-align: right;
            color: #28a745;
            font-weight: 500;
        }

        /* == CSS untuk Quantity Stepper (Tombol +/-) == */
        .quantity-stepper {
            width: 120px;
            margin-left: 15px;
            flex-shrink: 0;
        }

        .quantity-stepper .quantity-input {
            -moz-appearance: textfield;
            appearance: textfield;
            background-color: #fff !important;
        }

        .quantity-stepper .quantity-input::-webkit-outer-spin-button,
        .quantity-stepper .quantity-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .quantity-stepper .btn-quantity-stepper {
            width: 32px;
        }

        /* == Styling untuk Total Biaya == */
        .total-price-container {
            text-align: right;
            padding: 1rem;
            background-color: #f3f3f3;
            border-radius: 5px;
            border-top: 3px solid #1dc9b7;
        }

        .total-price-container h2 {
            font-weight: 700;
            color: #1dc9b7;
            margin: 0;
        }

        .total-price-container small {
            color: #868e96;
            display: block;
            margin-bottom: 5px;
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
                            Form <span class="fw-300"><i>Order Baru Laboratorium</i></span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form method="post" name="form-laboratorium" id="form-laboratorium">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}">

                                {{-- BAGIAN 1: INFORMASI PASIEN & ORDER (Tidak ada perubahan) --}}
                                <div class="card border mb-4">
                                    {{-- ... Konten Informasi Pasien ... --}}
                                    <div class="card-header bg-primary-50">
                                        <h5 class="card-title text-white">Informasi Pasien & Order</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            {{-- Kolom Kiri --}}
                                            <div class="col-lg-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label" for="nama_pasien">Nama
                                                        Pasien</label>
                                                    <div class="col-sm-8">
                                                        <div class="input-group">
                                                            <input type="text" readonly class="form-control"
                                                                id="nama_pasien" name="nama_pasien"
                                                                placeholder="Pilih pasien...">
                                                            <div class="input-group-append">
                                                                <button class="btn btn-primary"
                                                                    onclick="event.preventDefault()" id="pilih-pasien-btn"
                                                                    title="Cari Pasien"><i
                                                                        class="fal fa-search"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label" for="mrn_registration_number">No
                                                        RM / Registrasi</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" readonly class="form-control"
                                                            id="mrn_registration_number" name="mrn_registration_number">
                                                        <input type="hidden" name="medical_record_number" value="">
                                                        <input type="hidden" name="registration_number" value="">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label" for="date_of_birth">Tanggal
                                                        Lahir</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" readonly class="form-control datepicker"
                                                            id="date_of_birth" name="date_of_birth">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Jenis Kelamin</label>
                                                    <div class="col-sm-8">
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" id="gender_male" name="jenis_kelamin"
                                                                class="custom-control-input" value="Laki-laki" disabled>
                                                            <label class="custom-control-label"
                                                                for="gender_male">Laki-laki</label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" id="gender_female" name="jenis_kelamin"
                                                                class="custom-control-input" value="Perempuan" disabled>
                                                            <label class="custom-control-label"
                                                                for="gender_female">Perempuan</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label" for="no_telp">No. Telp /
                                                        HP</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control" id="no_telp"
                                                            name="no_telp">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label" for="alamat">Alamat</label>
                                                    <div class="col-sm-8">
                                                        <textarea class="form-control" id="alamat" name="alamat" rows="2"></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Kolom Kanan --}}
                                            <div class="col-lg-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label" for="order_date">Tanggal
                                                        Order</label>
                                                    <div class="col-sm-8">
                                                        <input type="date" class="form-control" id="order_date"
                                                            name="order_date"
                                                            value="{{ \Carbon\Carbon::now()->toDateString() }}">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label" for="tipe_pasien">Tipe
                                                        Pasien</label>
                                                    <div class="col-sm-8">
                                                        <select class="form-control" name="tipe_pasien" id="tipe_pasien">
                                                            <option value="rajal" selected>Rawat Jalan</option>
                                                            <option value="ranap">Rawat Inap</option>
                                                            <option value="otc">OTC</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label"
                                                        for="poly_ruang">Poly/Ruang</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" readonly class="form-control"
                                                            id="poly_ruang" name="poly_ruang">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label" for="doctor_id">Dokter
                                                        Laboratorium</label>
                                                    <div class="col-sm-8">
                                                        <select class="select2 form-control w-100" id="doctor_id"
                                                            name="doctor_id">
                                                            <option value="">-- Pilih Dokter --</option>
                                                            @foreach ($laboratoriumDoctors as $doctor)
                                                                <option value="{{ $doctor->id }}"
                                                                    @if ($doctor->id == 17) selected @endif>
                                                                    {{ $doctor->employee->fullname }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label" for="diagnosa_awal">Diagnosa
                                                        Klinis</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control" id="diagnosa_awal"
                                                            name="diagnosa_awal">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Tipe Order</label>
                                                    <div class="col-sm-8">
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" id="order_type_normal"
                                                                name="order_type" class="custom-control-input"
                                                                value="normal" checked>
                                                            <label class="custom-control-label"
                                                                for="order_type_normal">Normal</label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" id="order_type_cito" name="order_type"
                                                                class="custom-control-input" value="cito">
                                                            <label class="custom-control-label" for="order_type_cito">CITO
                                                                <small>(+30%)</small></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- BAGIAN 2: PEMILIHAN TINDAKAN LABORATORIUM --}}
                                <div class="card border mb-4">
                                    <div class="card-header bg-success-50">
                                        <h5 class="card-title text-white">Pilihan Pemeriksaan Laboratorium</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="searchLaboratorium"
                                                placeholder="Cari nama pemeriksaan...">
                                        </div>

                                        @php
                                            $totalCategories = $laboratorium_categories->count();
                                            $columnClass = 'col-xl-3 col-lg-4 col-md-6'; // Default 4 kolom di layar besar
                                            if ($totalCategories == 1) {
                                                $columnClass = 'col-12';
                                            } elseif ($totalCategories == 2) {
                                                $columnClass = 'col-md-6';
                                            } elseif ($totalCategories == 3) {
                                                $columnClass = 'col-lg-4 col-md-6';
                                            }
                                        @endphp

                                        <div class="row" id="laboratorium-grid-container">
                                            @foreach ($laboratorium_categories as $category)
                                                <div class="{{ $columnClass }} category-column">
                                                    <div class="card border mb-4">
                                                        <div class="card-header bg-primary-50">
                                                            <h6 class="card-title text-white mb-0">
                                                                {{ $category->nama_kategori }}</h6>
                                                        </div>

                                                        {{-- INI PERBAIKANNYA --}}
                                                        <div class="card-body p-0"
                                                            style="max-height: 350px; overflow: scroll">
                                                            @forelse ($category->parameter_laboratorium->where('is_order', true) as $parameter)
                                                                <div class="test-item parameter_laboratorium">
                                                                    <div
                                                                        class="custom-control custom-checkbox flex-grow-1">
                                                                        <input type="checkbox"
                                                                            class="custom-control-input parameter_laboratorium_checkbox"
                                                                            value="{{ $parameter->id }}"
                                                                            id="parameter_laboratorium_{{ $parameter->id }}">
                                                                        <label class="custom-control-label"
                                                                            for="parameter_laboratorium_{{ $parameter->id }}">{{ $parameter->parameter }}</label>
                                                                    </div>
                                                                    <div class="test-price"
                                                                        id="harga_parameter_laboratorium_{{ $parameter->id }}">
                                                                    </div>
                                                                    <div class="input-group quantity-stepper">
                                                                        <div class="input-group-prepend">
                                                                            <button
                                                                                class="btn btn-primary btn-sm btn-quantity-stepper"
                                                                                type="button" data-action="decrement">
                                                                                <i class="fal fa-minus"></i>
                                                                            </button>
                                                                        </div>
                                                                        <input type="number" value="1"
                                                                            min="1"
                                                                            class="form-control form-control-sm text-center quantity-input parameter_laboratorium_number"
                                                                            id="jumlah_{{ $parameter->id }}" readonly>
                                                                        <div class="input-group-append">
                                                                            <button
                                                                                class="btn btn-primary btn-sm btn-quantity-stepper"
                                                                                type="button" data-action="increment">
                                                                                <i class="fal fa-plus"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @empty
                                                                <div class="p-3 text-muted text-center">
                                                                    Tidak ada parameter.
                                                                </div>
                                                            @endforelse
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                {{-- BAGIAN 3: TOTAL & AKSI (Tidak ada perubahan) --}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <a href="{{ route('laboratorium.list-order') }}"
                                            class="btn btn-outline-danger waves-effect waves-themed">
                                            <span class="fal fa-arrow-left mr-1"></span>
                                            Kembali
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="total-price-container">
                                            <small>Total Biaya</small>
                                            <h2 id="laboratorium-total">Rp 0</h2>
                                        </div>
                                        <button onclick="event.preventDefault()"
                                            class="btn btn-lg btn-success waves-effect waves-themed btn-block mt-3 submit-btn">
                                            <span class="fal fa-save mr-1"></span>
                                            Simpan Order
                                        </button>
                                    </div>
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
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi Select2
            $('.select2').select2({
                placeholder: "-- Pilih Dokter --",
                allowClear: true,
                dropdownCssClass: "move-up"
            });
            $('#tipe_pasien').select2({
                placeholder: "-- Pilih Tipe Pasien --",
                allowClear: true,
                dropdownCssClass: "move-up"
            });

            $('.datepicker').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
                orientation: "bottom left"
            });

            // Menyimpan variabel global dari Controller
            window._kategoriLaboratorium = @json($laboratorium_categories);
            window._tarifLaboratorium = @json($tarifs);
            window._penjamins = @json($penjamins);
            window._kelasRawats = @json($kelas_rawats);

            // TIDAK PERLU ADA JAVASCRIPT DISINI LAGI
            // Semua logika sudah dipindahkan ke file eksternal
        });
    </script>

    {{-- Memuat file JS eksternal yang sudah diperbaiki --}}
    <script src="{{ asset('js/simrs/order-laboratorium-jquery.js') }}?v={{ time() }}"></script>
@endsection
