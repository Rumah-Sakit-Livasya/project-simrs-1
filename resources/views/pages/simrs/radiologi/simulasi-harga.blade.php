@extends('inc.layout')

@section('title', 'Simulasi Harga Radiologi')

@section('extended-css')
    {{-- CSS yang disesuaikan untuk card yang lebih kecil dan ringkas --}}
    <style>
        /* Grid yang menampung lebih banyak item per baris */
        #radiology-action-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1rem;
        }

        /* Styling Card Tindakan yang lebih ringkas */
        .radiology-card {
            border: 1px solid #e9e9e9;
            border-radius: 4px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }

        .radiology-card .card-header {
            border-bottom: 1px solid #e9e9e9;
            padding: 0.6rem 1rem;
        }

        .radiology-card .card-title {
            font-weight: 500;
            margin-bottom: 0;
            color: #fff;
            font-size: 0.9rem;
        }

        /* Item tindakan dengan padding yang lebih kecil */
        .radiology-item {
            padding: 0.6rem 1rem;
        }

        .radiology-item .custom-control-label {
            cursor: pointer;
            user-select: none;
            flex-grow: 1;
        }

        .radiology-item .parameter_radiologi_number {
            width: 65px;
            text-align: center;
            margin-left: 1rem;
        }

        /* Memastikan select2 tampil di atas elemen lain */
        .select2-container {
            z-index: 1050 !important;
        }
    </style>
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="panel">
            <div class="panel-hdr">
                <h2>
                    <i class="fas fa-calculator-alt mr-2 text-primary"></i>
                    Simulasi Harga Radiologi
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content p-4">
                    {{-- Konten filter dan pengaturan simulasi --}}
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

                    <hr class="my-4">

                    {{-- Tampilan Total Estimasi Harga seperti di halaman Lab --}}
                    <div class="alert alert-info text-center fs-xl fw-700 p-3 mb-4" role="alert">
                        <i class="fas fa-calculator mr-2"></i> Total Estimasi Biaya: <span id="radiologi-total">Rp
                            0,00</span>
                    </div>

                    {{-- Konten pilihan tindakan radiologi --}}
                    <div class="form-group">
                        <label class="form-label h5" for="searchRadiology">Pilih Tindakan Radiologi</label>
                        <input type="text" class="form-control mb-3" id="searchRadiology"
                            placeholder="Ketik untuk mencari tindakan...">
                    </div>

                    <div id="radiology-action-grid">
                        @foreach ($radiology_categories as $category)
                            <div class="card radiology-card">
                                <div class="card-header bg-primary-600">
                                    <h5 class="card-title">{{ $category->nama_kategori }}</h5>
                                </div>
                                <div class="list-group list-group-flush">
                                    @foreach ($category->parameter_radiologi as $parameter)
                                        <div
                                            class="list-group-item d-flex justify-content-between align-items-center radiology-item">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox"
                                                    class="custom-control-input parameter_radiologi_checkbox"
                                                    id="parameter_radiologi_{{ $parameter->id }}"
                                                    value="{{ $parameter->id }}">
                                                <label class="custom-control-label"
                                                    for="parameter_radiologi_{{ $parameter->id }}">
                                                    {{ $parameter->parameter }}
                                                    (<span class="text-info"
                                                        id="harga_parameter_radiologi_{{ $parameter->id }}">{{ rp(0) }}</span>)
                                                </label>
                                            </div>
                                            <input type="number" value="1" min="1"
                                                class="form-control form-control-sm parameter_radiologi_number"
                                                id="jumlah_{{ $parameter->id }}">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                {{-- Panel Footer dihapus karena total harga sudah dipindah ke atas --}}
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    {{-- Menambahkan section plugin untuk inisialisasi Select2 --}}
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi Select2
            $('.select2').select2({
                placeholder: "Pilih Opsi",
                allowClear: true
            });
        });

        // Menyediakan data dari PHP ke JavaScript
        window._kategoriRadiologi = @json($radiology_categories);
        window._tarifRadiologi = @json($tarifs);
    </script>
    {{-- Memuat file JS yang benar untuk simulasi harga radiologi --}}
    <script src="{{ asset('js/simrs/simulasi-harga-radiologi.js') }}?v={{ time() }}"></script>
@endsection
