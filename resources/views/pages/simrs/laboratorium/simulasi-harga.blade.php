@extends('inc.layout')
@section('title', 'Simulasi Harga Laboratorium')

{{-- CSS Kustom disamakan dengan halaman Order --}}
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
    </style>
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        {{-- Panel untuk Filter dan Opsi --}}
        <div class="panel" id="panel-filter">
            <div class="panel-hdr">
                <h2>
                    Filter & <span class="fw-300"><i>Opsi Simulasi</i></span>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Tipe Order</label>
                                <div class="frame-wrap">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" name="order_type"
                                            id="order_type_normal" value="normal" checked>
                                        <label class="custom-control-label" for="order_type_normal">Normal</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" name="order_type"
                                            id="order_type_cito" value="cito">
                                        <label class="custom-control-label" for="order_type_cito">CITO (+30%)</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label" for="group_tarif">Grup Tarif / Penjamin</label>
                                <select class="select2 form-control w-100" id="group_tarif" name="group_tarif">
                                    @foreach ($group_penjamins as $group_penjamin)
                                        <option value="{{ $group_penjamin->id }}">{{ $group_penjamin->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label" for="kelas_perawatan">Kelas Perawatan</label>
                                <select class="select2 form-control w-100" id="kelas_perawatan" name="kelas_perawatan">
                                    @foreach ($kelas_rawats as $kelas_rawat)
                                        <option value="{{ $kelas_rawat->id }}">{{ $kelas_rawat->kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Panel untuk Daftar Tindakan dan Total Harga --}}
        <div class="panel" id="panel-tindakan">
            <div class="panel-hdr">
                <h2>
                    Daftar Tindakan & <span class="fw-300"><i>Total Harga</i></span>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    {{-- Tampilan Total Harga --}}
                    <div class="alert alert-info text-center fs-xxl fw-700 p-3 mb-4" role="alert">
                        <i class="fas fa-calculator mr-2"></i> Total Biaya: <span id="laboratorium-total">Rp 0</span>
                    </div>

                    {{-- Form Pencarian --}}
                    <div class="form-group">
                        <label class="form-label" for="searchLaboratorium">Cari Tindakan Laboratorium</label>
                        <input type="text" class="form-control" id="searchLaboratorium"
                            placeholder="Ketik nama tindakan untuk memfilter...">
                    </div>
                    <hr>

                    {{-- Grid Tindakan (Struktur Baru) --}}
                    <div class="row" id="laboratorium-grid-container">
                        {{-- Iterasi berdasarkan GRUP PARAMETER --}}
                        @foreach ($groupedParameters as $groupName => $parameters)
                            {{-- Menggunakan layout 2 kolom seperti halaman Order --}}
                            <div class="col-md-6 category-column">
                                <div class="card border mb-4">
                                    <div class="card-header bg-primary-50">
                                        <h6 class="card-title text-white mb-0">{{ $groupName }}</h6>
                                    </div>
                                    <div class="card-body p-0" style="max-height: 350px; overflow-y: auto;">
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
                                                        <button class="btn btn-primary btn-sm btn-quantity-stepper"
                                                            type="button" data-action="decrement">
                                                            <i class="fal fa-minus"></i>
                                                        </button>
                                                    </div>
                                                    <input type="number" value="1" min="1"
                                                        class="form-control form-control-sm text-center quantity-input parameter_laboratorium_number"
                                                        id="jumlah_{{ $parameter->id }}" readonly>
                                                    <div class="input-group-append">
                                                        <button class="btn btn-primary btn-sm btn-quantity-stepper"
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
        </div>
    </main>
@endsection

@section('plugin')
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi Select2
            $('.select2').select2();

            // Menyediakan data tarif dari PHP (Blade) ke JavaScript
            window._tarifLaboratorium = @json($tarifs);

            $('#laboratorium-grid-container').on('click', '.btn-quantity-stepper', function() {
                const button = $(this);
                const action = button.data('action'); // 'increment' or 'decrement'

                // Cari input number yang berada dalam satu grup dengan tombol yang diklik
                const input = button.closest('.quantity-stepper').find('.quantity-input');
                let currentValue = parseInt(input.val());

                if (action === 'increment') {
                    currentValue++;
                } else if (action === 'decrement') {
                    // Jangan biarkan kuantitas kurang dari 1
                    if (currentValue > 1) {
                        currentValue--;
                    }
                }

                // Update nilai input
                input.val(currentValue);

                // PENTING: Memicu event 'change' pada checkbox terkait
                // Ini akan memberitahu script kalkulasi harga bahwa ada perubahan
                // dan total harga harus dihitung ulang.
                input.closest('.test-item').find('.parameter_laboratorium_checkbox').trigger('change');
            });
        });
    </script>
    {{-- Pastikan file JS ini sudah disesuaikan dengan struktur baru --}}
    <script src="{{ asset('js/simrs/simulasi-harga-laboratorium.js') }}?v={{ time() }}"></script>
@endsection
