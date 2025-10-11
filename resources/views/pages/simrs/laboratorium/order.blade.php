@extends('inc.layout')
@section('title', 'Order Baru Laboratorium')

{{-- CSS Kustom (tidak ada perubahan) --}}
@section('extended-css')
    <style>
        .test-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1.25rem;
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
                        <h2>Form <span class="fw-300"><i>Order Baru Laboratorium</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form method="post" name="form-laboratorium" id="form-laboratorium">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}">

                                {{-- BAGIAN 1: INFORMASI PASIEN & ORDER (Tidak ada perubahan) --}}
                                <div class="card border mb-4">
                                    {{-- ... Konten Informasi Pasien & Order Anda ... --}}
                                </div>

                                {{-- ========================================================== --}}
                                {{-- BAGIAN 2: PEMILIHAN TINDAKAN (INI YANG DIUBAH) --}}
                                {{-- ========================================================== --}}
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
                                            // Menentukan layout kolom berdasarkan jumlah grup
                                            $totalGroups = $groupedParameters->count();
                                            $columnClass = 'col-xl-3 col-lg-4 col-md-6'; // 4 kolom
                                            if ($totalGroups <= 2) {
                                                $columnClass = 'col-md-6';
                                            } // 2 kolom
                                            if ($totalGroups == 3) {
                                                $columnClass = 'col-lg-4 col-md-6';
                                            } // 3 kolom
                                        @endphp

                                        <div class="row" id="laboratorium-grid-container">
                                            {{-- Iterasi berdasarkan GRUP PARAMETER --}}
                                            @foreach ($groupedParameters as $groupName => $parameters)
                                                <div class="{{ $columnClass }} category-column">
                                                    <div class="card border mb-4">
                                                        <div class="card-header bg-primary-50">
                                                            <h6 class="card-title text-white mb-0">
                                                                {{ $groupName }}
                                                            </h6>
                                                        </div>

                                                        <div class="card-body p-0"
                                                            style="max-height: 350px; overflow-y: auto;">
                                                            @forelse ($parameters as $parameter)
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
                                                                        {{-- Harga akan diisi oleh JS --}}
                                                                    </div>
                                                                    <div class="input-group quantity-stepper">
                                                                        <div class="input-group-prepend">
                                                                            <button
                                                                                class="btn btn-primary btn-sm btn-quantity-stepper"
                                                                                type="button" data-action="decrement">
                                                                                <i class="fal fa-minus"></i>
                                                                            </button>
                                                                        </div>
                                                                        <input type="number" value="1" min="1"
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
                                                                    Tidak ada parameter dalam grup ini.
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
                                    {{-- ... Konten Total Biaya & Tombol Simpan ... --}}
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
            // ... (Inisialisasi Select2 dan Datepicker) ...

            // Variabel global yang diperlukan oleh JS eksternal
            // Tidak lagi butuh _kategoriLaboratorium
            window._tarifLaboratorium = @json($tarifs);
            window._penjamins = @json($penjamins);
            window._kelasRawats = @json($kelas_rawats);
        });
    </script>

    {{-- Memuat file JS eksternal (tidak perlu diubah) --}}
    <script src="{{ asset('js/simrs/order-laboratorium-jquery.js') }}?v={{ time() }}"></script>
@endsection
