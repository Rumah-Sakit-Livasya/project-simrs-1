{{-- CSS Kustom untuk form ini --}}
<style>
    /* Styling baru agar sesuai gambar referensi */
    #panel-order-lab-baru .panel-content {
        padding-bottom: 0;
    }

    .form-info-item {
        margin-bottom: 1.5rem;
    }

    .form-info-item label {
        font-size: 0.8rem;
        color: #868e96;
        margin-bottom: 0.25rem;
        display: block;
    }

    .form-info-item .form-control,
    .form-info-item .select2-container .select2-selection {
        border: none;
        border-bottom: 1px solid #ced4da;
        border-radius: 0;
        padding-left: 0;
        padding-right: 0;
        background-color: transparent;
    }

    .form-info-item .form-control:focus,
    .form-info-item .select2-container--open .select2-selection {
        box-shadow: none;
        border-color: #80bdff;
    }

    .form-info-item .form-control[readonly] {
        background-color: transparent;
    }

    .panel-pemeriksaan .panel-hdr {
        background-color: #f7f9fa;
    }

    .panel-pemeriksaan .panel-toolbar {
        color: #888;
    }

    .test-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.6rem 1rem;
        border-bottom: 1px solid #eee;
    }

    .test-item:last-child {
        border-bottom: none;
    }

    .test-item .test-price {
        min-width: 100px;
        text-align: right;
        color: #28a745;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .quantity-stepper {
        width: 100px;
        margin-left: 1rem;
        flex-shrink: 0;
        display: flex;
        align-items: center;
    }

    .quantity-stepper .btn-quantity-stepper {
        background-color: #e9ecef;
        border: 1px solid #ced4da;
        color: #495057;
        height: 30px;
        width: 30px;
    }

    .quantity-stepper .quantity-input {
        text-align: center;
        border: none;
        background: transparent;
        width: 40px;
        font-weight: 500;
        padding: 0;
        margin: 0 5px;
        -moz-appearance: textfield;
    }

    .quantity-stepper .quantity-input::-webkit-inner-spin-button,
    .quantity-stepper .quantity-input::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    #lab-order-footer {
        position: sticky;
        bottom: 0;
        background: #fff;
        z-index: 100;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
    }

    .total-price-box {
        text-align: right;
    }

    .total-price-box small {
        font-size: 0.8rem;
        color: #868e96;
    }

    .total-price-box h3 {
        color: #1dc9b7;
        font-weight: 700;
        margin: 0;
    }
</style>

<div class="panel" id="panel-order-lab-baru">
    <div class="panel-hdr">
        <h2>Buat Order Laboratorium Baru</h2>
    </div>
    <div class="panel-container show">
        <form id="form-laboratorium" autocomplete="off">
            <div class="panel-content">
                @csrf
                {{-- Data tersembunyi (tidak berubah) --}}
                <input type="text" name="patient_id" value="{{ $patient->id }}">
                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}">
                <input type="hidden" name="registration_type" value="{{ $registration->registration_type }}">
                <input type="hidden" name="poliklinik" value="{{ $registration->poliklinik }}">

                {{-- INFORMASI ORDER - UI BARU --}}
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-info-item">
                            <label for="order_date">Tanggal Order</label>
                            <input type="text" class="form-control" id="order_date" name="order_date" readonly
                                value="{{ now()->format('d-m-Y H:i') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-info-item">
                            <label for="doctor_id">Dokter Laboratorium <span class="text-danger">*</span></label>
                            <select class="select2 form-control w-100" id="doctor_id" name="doctor_id">
                                <option></option> {{-- Placeholder untuk select2 --}}
                                @foreach ($laboratoriumDoctors as $doctor)
                                    <option value="{{ $doctor->id }}">{{ $doctor->employee->fullname }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-info-item">
                            <label>Dokter Perujuk</label>
                            <input type="text" class="form-control" readonly
                                value="{{ $registration->doctor->employee->fullname }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-info-item">
                            <label>Tipe Order</label>
                            <div class="d-flex align-items-center pt-2">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="order_type_normal" name="order_type"
                                        class="custom-control-input" value="normal" checked>
                                    <label class="custom-control-label" for="order_type_normal">Normal</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="order_type_cito" name="order_type"
                                        class="custom-control-input" value="cito">
                                    <label class="custom-control-label" for="order_type_cito">CITO (+30%)</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-info-item">
                            <label for="diagnosa_awal">Diagnosa Klinis <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="diagnosa_awal" name="diagnosa_awal">
                        </div>
                    </div>
                </div>
                <hr class="mt-0">

                {{-- PEMILIHAN PEMERIKSAAN - UI BARU --}}
                <div class="form-group">
                    <label class="form-label" for="searchLaboratorium">Cari Pemeriksaan</label>
                    <input type="text" class="form-control" id="searchLaboratorium"
                        placeholder="Ketik untuk mencari...">
                </div>

                <div class="custom-scroll" style="max-height: 40vh; position: relative;">
                    <div class="row" id="laboratorium-grid-container">
                        @php
                            $totalCategories = $laboratorium_categories->count();
                            $columnClass = 'col-xl-3 col-lg-4 col-md-6';
                            if ($totalCategories == 1) {
                                $columnClass = 'col-12';
                            } elseif ($totalCategories == 2) {
                                $columnClass = 'col-md-6';
                            } elseif ($totalCategories == 3) {
                                $columnClass = 'col-lg-4 col-md-6';
                            }
                        @endphp
                        @foreach ($laboratorium_categories as $category)
                            <div class="{{ $columnClass }} category-column">
                                <div class="panel panel-pemeriksaan mb-3">
                                    <div class="panel-hdr">
                                        <h2 class="h6">{{ $category->nama_kategori }}</h2>
                                        <div class="panel-toolbar">
                                            <i class="fal fa-ellipsis-v"></i>
                                        </div>
                                    </div>
                                    <div class="panel-container show">
                                        <div class="panel-content p-0">
                                            @forelse ($category->parameter_laboratorium->where('is_order', true) as $parameter)
                                                <div class="test-item parameter_laboratorium">
                                                    <div class="custom-control custom-checkbox flex-grow-1">
                                                        <input type="checkbox"
                                                            class="custom-control-input parameter_laboratorium_checkbox"
                                                            value="{{ $parameter->id }}"
                                                            id="parameter_laboratorium_{{ $parameter->id }}">
                                                        <label class="custom-control-label"
                                                            for="parameter_laboratorium_{{ $parameter->id }}">{{ $parameter->parameter }}</label>
                                                    </div>
                                                    <div class="test-price"
                                                        id="harga_parameter_laboratorium_{{ $parameter->id }}">
                                                        {{ rp(0) }}</div>
                                                    <div class="quantity-stepper">
                                                        <button class="btn btn-sm btn-quantity-stepper" type="button"
                                                            data-action="decrement">-</button>
                                                        <input type="number" value="1"
                                                            class="form-control form-control-sm quantity-input parameter_laboratorium_number"
                                                            id="jumlah_{{ $parameter->id }}">
                                                        <button class="btn btn-sm btn-quantity-stepper" type="button"
                                                            data-action="increment">+</button>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="p-3 text-muted text-center">Tidak ada parameter.</div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- FOOTER FORM - UI BARU --}}
            <div class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 d-flex align-items-center"
                id="lab-order-footer">
                <button class="btn btn-secondary btn-back-to-lab-list" type="button"><i
                        class="fal fa-arrow-left mr-1"></i> Kembali</button>
                <div class="ml-auto d-flex align-items-center">
                    <div class="total-price-box mr-3">
                        <small>Total Biaya</small>
                        <h3 id="laboratorium-total">Rp 0</h3>
                    </div>
                    <button type="submit" id="laboratorium-submit" class="btn btn-primary btn-lg"><i
                            class="fal fa-save mr-1"></i> Simpan Order</button>
                </div>
            </div>
        </form>
    </div>
</div>


{{-- Script JS untuk form ini --}}
@section('script-laboratorium')
    <script>
        // Pass data dari PHP ke JavaScript
        window._kategoriLaboratorium = @json($laboratorium_categories);
        window._tarifLaboratorium = @json($laboratorium_tarifs);
        window._registration = @json($registration);
        window._groupPenjaminId = @json($groupPenjaminId);
        window._kelasRawats = @json($kelas_rawats);
    </script>
    {{-- Pastikan nama file JS ini sesuai dan dimuat di layout utama Anda --}}
    <script src="{{ asset('js/simrs/form-laboratorium.js') }}?v={{ time() }}"></script>
    {{-- <script src="{{ asset('js/simrs/order-laboratorium-jquery.js') }}?v={{ time() }}"></script> --}}
@endsection
