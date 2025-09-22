{{-- =================================================================================== --}}
{{-- =           AWAL BAGIAN ORDER RADIOLOGI (DESAIN BARU + SCROLL PER KATEGORI)         --}}
{{-- =================================================================================== --}}

{{-- CSS Kustom --}}
<style>
    #panel-order-radiology-baru .panel-content {
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

    .panel-pemeriksaan {
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .panel-pemeriksaan .panel-hdr {
        background-color: #f7f9fa;
        flex-shrink: 0;
    }

    .panel-pemeriksaan .panel-container {
        flex-grow: 1;
        overflow: hidden;
        display: flex;
    }

    .panel-pemeriksaan .panel-content {
        flex-grow: 1;
        overflow-y: auto;
        max-height: 40vh;
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
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
    }

    .quantity-stepper .btn-quantity-stepper:hover {
        background-color: #dee2e6;
        border-color: #adb5bd;
    }

    .quantity-stepper .btn-quantity-stepper:disabled {
        opacity: 0.6;
        cursor: not-allowed;
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

    .quantity-stepper .quantity-input:disabled {
        color: #6c757d;
        background-color: #e9ecef;
        opacity: 1;
    }

    #radiology-order-footer {
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

    /* Style untuk disabled checkbox */
    .parameter_radiologi_checkbox:disabled+.custom-control-label {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* Highlight untuk item yang dipilih */
    .test-item.selected {
        background-color: #f8f9fa;
        border-left: 3px solid #1dc9b7;
    }

    /* Loading state */
    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    /* Search highlight */
    .search-highlight {
        background-color: yellow;
        font-weight: bold;
    }

    /* Error state untuk tarif yang tidak ditemukan */
    .test-price.error {
        color: #dc3545;
        font-style: italic;
    }
</style>

<div class="panel" id="panel-order-radiology-baru">
    <div class="panel-hdr">
        <h2>Buat Order Radiologi Baru</h2>
    </div>
    <div class="panel-container show">
        <form id="form-radiologi" autocomplete="off">
            <div class="panel-content">
                @csrf
                {{-- Data tersembunyi --}}
                <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}">
                <input type="hidden" name="registration_type" value="{{ $registration->registration_type }}">
                <input type="hidden" name="registration_id" value="{{ $registration->id }}">
                <input type="hidden" name="poliklinik" value="{{ $registration->poliklinik }}">

                {{-- INFORMASI ORDER --}}
                <div class="row p-3">
                    <div class="col-md-3">
                        <div class="form-info-item">
                            <label for="order_date">Tanggal Order</label>
                            <input type="text" class="form-control datepicker" id="order_date" name="order_date"
                                value="{{ now()->format('d-m-Y') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-info-item">
                            <label for="doctor_id">Dokter Radiologi <span class="text-danger">*</span></label>
                            <select class="select2 form-control w-100" id="doctor_id" name="doctor_id">
                                <option></option>
                                @foreach ($radiologyDoctors as $doctor)
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
                            <input type="text" class="form-control" id="diagnosa_awal" name="diagnosa_awal"
                                placeholder="Masukkan diagnosa klinis...">
                        </div>
                    </div>
                </div>
                <hr class="mt-0">

                {{-- PEMILIHAN PEMERIKSAAN --}}
                <div class="px-3 mb-3">
                    <div class="form-group">
                        <label class="form-label" for="searchRadiology">Cari Pemeriksaan</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fal fa-search"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" id="searchRadiology"
                                placeholder="Ketik untuk mencari pemeriksaan...">
                        </div>
                    </div>
                </div>

                {{-- GRID CONTAINER UNTUK KATEGORI --}}
                <div class="row px-3" id="radiology-grid-container">
                    @php
                        $totalCategories = $radiology_categories->count();
                        $columnClass = 'col-xl-4 col-lg-6 col-md-6';
                        if ($totalCategories <= 2) {
                            $columnClass = 'col-md-6';
                        }
                    @endphp
                    @foreach ($radiology_categories as $category)
                        <div class="{{ $columnClass }} category-column mb-3">
                            <div class="panel panel-pemeriksaan">
                                <div class="panel">
                                    <h2 class="h6 mb-0">{{ $category->nama_kategori }}</h2>
                                    <div class="panel-toolbar">
                                        <small class="text-muted">{{ $category->parameter_radiologi->count() }}
                                            item</small>
                                    </div>
                                </div>
                                <div class="panel-container show">
                                    <div class="panel-content p-0" style="position: relative;">
                                        @forelse ($category->parameter_radiologi as $parameter)
                                            <div class="test-item parameter_radiologi"
                                                data-parameter-id="{{ $parameter->id }}">
                                                <div class="custom-control custom-checkbox flex-grow-1">
                                                    <input type="checkbox"
                                                        class="custom-control-input parameter_radiologi_checkbox"
                                                        value="{{ $parameter->id }}"
                                                        id="parameter_radiologi_{{ $parameter->id }}">
                                                    <label class="custom-control-label"
                                                        for="parameter_radiologi_{{ $parameter->id }}">
                                                        {{ $parameter->parameter }}
                                                        @if ($parameter->keterangan)
                                                            <small
                                                                class="d-block text-muted">{{ $parameter->keterangan }}</small>
                                                        @endif
                                                    </label>
                                                </div>
                                                <div class="test-price"
                                                    id="harga_parameter_radiologi_{{ $parameter->id }}">
                                                    <i class="fal fa-spinner fa-spin"></i>
                                                </div>
                                                <div class="quantity-stepper">
                                                    <button class="btn btn-sm btn-quantity-stepper" type="button"
                                                        data-action="decrement" disabled>
                                                        <i class="fal fa-minus"></i>
                                                    </button>
                                                    <input type="number" value="1" min="1"
                                                        class="form-control form-control-sm quantity-input parameter_radiologi_number"
                                                        id="jumlah_{{ $parameter->id }}" disabled>
                                                    <button class="btn btn-sm btn-quantity-stepper" type="button"
                                                        data-action="increment" disabled>
                                                        <i class="fal fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="p-3 text-muted text-center">
                                                <i class="fal fa-exclamation-circle mb-2 fs-2"></i>
                                                <p class="mb-0">Tidak ada parameter tersedia.</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    {{-- Pesan jika tidak ada hasil pencarian --}}
                    <div class="col-12 text-center py-4 d-none" id="no-search-results">
                        <i class="fal fa-search fs-2 text-muted mb-3"></i>
                        <h5 class="text-muted">Tidak ada pemeriksaan ditemukan</h5>
                        <p class="text-muted">Coba gunakan kata kunci yang berbeda</p>
                    </div>
                </div>
            </div>

            {{-- FOOTER FORM --}}
            <div class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 d-flex align-items-center p-3"
                id="radiology-order-footer">
                <button class="btn btn-secondary btn-back-to-layanan" type="button" data-target-menu="radiologi">
                    <i class="fal fa-arrow-left mr-1"></i> Kembali
                </button>

                <div class="ml-auto d-flex align-items-center">
                    {{-- Summary info --}}
                    {{-- <div class="mr-3 text-right">
                        <small class="text-muted d-block">Item dipilih: <span id="selected-count">0</span></small>
                        <small class="text-muted">Total quantity: <span id="total-quantity">0</span></small>
                    </div> --}}

                    {{-- Total price --}}
                    <div class="total-price-box mr-3">
                        <small>Total Biaya</small>
                        <h3 id="radiologi-total" data-total="0">Rp 0</h3>
                    </div>

                    {{-- Submit button --}}
                    <button type="button" id="radiologi-submit" class="btn btn-primary btn-lg" disabled>
                        <i class="fal fa-save mr-1"></i> Simpan Order
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal untuk konfirmasi --}}
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Order Radiologi</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Detail Order:</h6>
                        <ul id="order-summary" class="list-unstyled">
                            <!-- Will be populated by JavaScript -->
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Total Biaya:</h6>
                        <h4 class="text-primary" id="modal-total">Rp 0</h4>
                        <small class="text-muted">Termasuk biaya CITO jika ada</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirm-submit">Ya, Simpan Order</button>
            </div>
        </div>
    </div>
</div>

{{-- Script JS --}}
@section('script-radiologi')
    <script>
        // Pass data dari PHP ke JavaScript
        window._tarifRadiologi = @json($radiology_tarifs);
        window._registration = @json($registration);
        window._groupPenjaminId = @json($groupPenjaminId);

        // Debug information
        console.log('Data loaded:', {
            tarifCount: window._tarifRadiologi ? window._tarifRadiologi.length : 0,
            registrationId: window._registration ? window._registration.id : null,
            groupPenjaminId: window._groupPenjaminId
        });
    </script>
    <script src="{{ asset('js/simrs/form-radiologi.js') }}?v={{ time() }}"></script>
@endsection
