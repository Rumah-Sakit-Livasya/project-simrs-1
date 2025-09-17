@extends('inc.layout')
@section('title', 'Simulasi Harga Laboratorium')

@section('extended-css')
    {{-- CSS Kustom untuk Halaman Ini --}}
    <style>
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .card-tindakan {
            /* Mengganti .card agar tidak bentrok dengan class Bootstrap */
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 15px;
            border: 1px solid #e1e1e1;
        }

        .card-tindakan h3 {
            color: white;
            padding: 10px 15px;
            margin: -15px -15px 10px -15px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
        }

        .item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        .item:last-child {
            border-bottom: none;
        }

        .item .form-check-label {
            /* Memastikan label bisa di-klik */
            cursor: pointer;
        }

        .parameter_laboratorium_number {
            width: 70px;
            /* Sedikit lebih lebar untuk kenyamanan */
            text-align: center;
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
                        <i class="fas fa-calculator mr-2"></i> Total Biaya: <span id="laboratorium-total">Rp 0,00</span>
                    </div>

                    {{-- Form Pencarian --}}
                    <div class="form-group">
                        <label class="form-label" for="searchLaboratorium">Cari Tindakan Laboratorium</label>
                        <input type="text" class="form-control" id="searchLaboratorium"
                            placeholder="Ketik nama tindakan untuk memfilter...">
                    </div>
                    <hr>

                    {{-- Grid Tindakan --}}
                    <div class="grid">
                        @foreach ($laboratorium_categories as $category)
                            <div class="card-tindakan">
                                <h3 class="bg-primary-600">{{ $category->nama_kategori }}</h3>
                                @foreach ($category->parameter_laboratorium as $parameter)
                                    @if ($parameter->is_order)
                                        <div class="item parameter_laboratorium">
                                            <div class="d-flex align-items-center">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox"
                                                        class="custom-control-input parameter_laboratorium_checkbox"
                                                        value="{{ $parameter->id }}"
                                                        id="parameter_laboratorium_{{ $parameter->id }}">
                                                    <label class="custom-control-label"
                                                        for="parameter_laboratorium_{{ $parameter->id }}">
                                                        {{ $parameter->parameter }}
                                                        (<span class="text-info fw-500"
                                                            id="harga_parameter_laboratorium_{{ $parameter->id }}">Rp
                                                            0,00</span>)
                                                    </label>
                                                </div>
                                            </div>
                                            <input type="number" value="1" min="1"
                                                class="form-control form-control-sm parameter_laboratorium_number"
                                                id="jumlah_{{ $parameter->id }}">
                                        </div>
                                    @endif
                                @endforeach
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
        });

        // Menyediakan data dari PHP (Blade) ke JavaScript
        window._kategoriLaboratorium = @json($laboratorium_categories);
        window._tarifLaboratorium = @json($tarifs);
    </script>
    {{-- Skrip ini diasumsikan sudah diubah menjadi jQuery seperti respons sebelumnya --}}
    <script src="{{ asset('js/simrs/simulasi-harga-laboratorium.js') }}?v={{ time() }}"></script>
@endsection
