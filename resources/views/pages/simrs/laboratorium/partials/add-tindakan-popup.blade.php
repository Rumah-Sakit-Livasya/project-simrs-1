{{-- Menggunakan layout minimalis untuk popup --}}
@extends('inc.layout-no-side')
@section('title', 'Tambah Pemeriksaan')

@section('extended-css')
    <style>
        body {
            background: #f3f3f3;
        }

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

        /* == CSS untuk Quantity Stepper == */
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

        /* Custom style untuk menonaktifkan item yang sudah ada */
        .item-disabled {
            opacity: 0.6;
            background-color: #f8f9fa;
            pointer-events: none;
            /* Mencegah klik */
        }

        .item-disabled .custom-control-label::after {
            content: "(Sudah Ada)";
            font-style: italic;
            color: #888;
            margin-left: 5px;
            font-size: 0.8em;
        }
    </style>
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="panel">
            <div class="panel-hdr">
                <h2>Pilih Pemeriksaan untuk Order: <strong>{{ $order->no_order }}</strong></h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    {{-- BAGIAN PEMILIHAN TINDAKAN --}}
                    <div class="card border">
                        <div class="card-header bg-success-50">
                            <h5 class="card-title text-white">Pilihan Pemeriksaan Laboratorium</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <input type="text" class="form-control" id="searchTindakanPopup"
                                    placeholder="Cari nama pemeriksaan...">
                            </div>

                            @php
                                $totalCategories = $all_laboratorium_categories->count();
                                $columnClass = 'col-xl-3 col-lg-4 col-md-6'; // Default 4 kolom
                                if ($totalCategories == 1) {
                                    $columnClass = 'col-12';
                                } elseif ($totalCategories == 2) {
                                    $columnClass = 'col-md-6';
                                } elseif ($totalCategories == 3) {
                                    $columnClass = 'col-lg-4 col-md-6';
                                }
                            @endphp

                            <div class="row" id="laboratorium-grid-container">
                                @foreach ($all_laboratorium_categories as $category)
                                    <div class="{{ $columnClass }} category-column">
                                        <div class="card border mb-4">
                                            <div class="card-header bg-primary-50">
                                                <h6 class="card-title text-white mb-0">{{ $category->nama_kategori }}</h6>
                                            </div>
                                            <div class="card-body p-0" style="max-height: 350px; overflow-y: auto;">
                                                @forelse ($category->parameter_laboratorium->where('is_order', true) as $parameter)
                                                    @php
                                                        $isDisabled = in_array($parameter->id, $existingParameterIds);
                                                    @endphp
                                                    <div
                                                        class="test-item parameter_laboratorium_popup {{ $isDisabled ? 'item-disabled' : '' }}">
                                                        <div class="custom-control custom-checkbox flex-grow-1">
                                                            <input type="checkbox"
                                                                class="custom-control-input parameter_laboratorium_checkbox_popup"
                                                                value="{{ $parameter->id }}"
                                                                id="popup_parameter_{{ $parameter->id }}"
                                                                {{ $isDisabled ? 'disabled' : '' }}>
                                                            <label class="custom-control-label"
                                                                for="popup_parameter_{{ $parameter->id }}">{{ $parameter->parameter }}</label>
                                                        </div>
                                                        <div class="input-group quantity-stepper">
                                                            <div class="input-group-prepend">
                                                                <button class="btn btn-primary btn-sm btn-quantity-stepper"
                                                                    type="button" data-action="decrement"
                                                                    {{ $isDisabled ? 'disabled' : '' }}>
                                                                    <i class="fal fa-minus"></i>
                                                                </button>
                                                            </div>
                                                            <input type="number" value="1" min="1"
                                                                class="form-control form-control-sm text-center quantity-input parameter_laboratorium_number_popup"
                                                                id="jumlah_popup_{{ $parameter->id }}" readonly
                                                                {{ $isDisabled ? 'disabled' : '' }}>
                                                            <div class="input-group-append">
                                                                <button class="btn btn-primary btn-sm btn-quantity-stepper"
                                                                    type="button" data-action="increment"
                                                                    {{ $isDisabled ? 'disabled' : '' }}>
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
                </div>
                <div
                    class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 d-flex flex-row align-items-center">
                    <button type="button" class="btn btn-primary ml-auto" id="btn-confirm-add-tindakan-popup">Tambahkan
                        Pilihan</button>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    <script>
        $(document).ready(function() {
            // Filter/Search di dalam Popup
            $('#searchTindakanPopup').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $(".category-column").each(function() {
                    var hasVisibleItem = false;
                    $(this).find(".parameter_laboratorium_popup").each(function() {
                        var itemText = $(this).find('label').text().toLowerCase();
                        if (itemText.indexOf(value) > -1) {
                            $(this).show();
                            hasVisibleItem = true;
                        } else {
                            $(this).hide();
                        }
                    });
                    // Sembunyikan seluruh card kategori jika tidak ada item yang cocok
                    $(this).toggle(hasVisibleItem);
                });
            });

            // Handler untuk tombol Quantity Stepper
            $('.btn-quantity-stepper').on('click', function() {
                var action = $(this).data('action');
                var $input = $(this).closest('.quantity-stepper').find('.quantity-input');
                var currentValue = parseInt($input.val());

                if (action === 'increment') {
                    $input.val(currentValue + 1);
                } else if (action === 'decrement' && currentValue > 1) {
                    $input.val(currentValue - 1);
                }
            });

            // Handler untuk tombol "Tambahkan Pilihan"
            $('#btn-confirm-add-tindakan-popup').on('click', function() {
                let selectedData = [];
                let processedIds = new Set();

                // Fungsi untuk menambahkan parameter dan sub-parameter secara rekursif
                function addParameterWithSubs(id, jumlah) {
                    if (processedIds.has(id)) {
                        return;
                    }
                    processedIds.add(id);
                    selectedData.push({
                        id: id,
                        jumlah: parseInt(jumlah)
                    });

                    // Cari sub-parameter dari relasi yang sudah di-push ke window
                    if (window.relasiParameterLaboratorium && window.relasiParameterLaboratorium[id]) {
                        window.relasiParameterLaboratorium[id].forEach(function(subId) {
                            // Sub-parameter default jumlah 1
                            addParameterWithSubs(subId, 1);
                        });
                    }
                }

                // Cari checkbox yang dicentang
                $('.parameter_laboratorium_checkbox_popup:checked').each(function() {
                    let id = $(this).val();
                    let jumlah = $('#jumlah_popup_' + id).val();
                    addParameterWithSubs(id, jumlah);
                });

                if (selectedData.length === 0) {
                    alert('Pilih minimal satu tindakan untuk ditambahkan.');
                    return;
                }

                // Kirim data via AJAX
                $.ajax({
                    url: '{{ route('order.laboratorium.add-tindakan') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        order_id: {{ $order->id }},
                        parameter_data: selectedData // Kirim sebagai 'parameter_data'
                    },
                    beforeSend: function() {
                        $('#btn-confirm-add-tindakan-popup').prop('disabled', true).html(
                            'Menambahkan...');
                    },
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            window.close(); // Tutup jendela popup
                        } else {
                            alert(response.message || 'Gagal menambahkan tindakan.');
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        alert('Terjadi kesalahan. Cek console untuk detail.');
                    },
                    complete: function() {
                        $('#btn-confirm-add-tindakan-popup').prop('disabled', false).html(
                            'Tambahkan Pilihan');
                    }
                });
            });
        });
    </script>
@endsection
