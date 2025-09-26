@extends('inc.layout')
@section('title', 'List Order Radiologi')
@section('extended-css')
    <style>
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 15px;
        }

        .card h3 {
            background-color: #cc33cc;
            color: white;
            padding: 10px;
            margin: -15px -15px 10px -15px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }

        .item:last-child {
            border-bottom: none;
        }

        .parameter_radiologi_number {
            width: 60px;
            margin-left: 10px;
        }
    </style>
@endsection
@section('content')
    <main id="js-page-content" role="main" class="page-content" style="background: white">
        <div class="panel-hdr border-top">
            <h2 class="text-light">
                <i class="fas fa-address-card mr-3 ml-2 text-primary" style="transform: scale(2.1)"></i>
                <span class="text-primary">Simulasi Harga Radiologi</span>
            </h2>
        </div>
        <div class="row">
            <div class="col-xl-4">
                <div class="form-group">
                    <div class="row align-items-center">
                        <div class="col-xl-4 text-right">
                            <label class="form-label" for="doctor_id">Tipe Order</label>
                        </div>
                        <div class="col-xl-8">
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="order_type" id="order_type_normal"
                                        value="normal" checked>
                                    <label class="form-check-label" for="order_type_normal">
                                        Normal
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="order_type" id="order_type_cito"
                                        value="cito">
                                    <label class="form-check-label" for="order_type_cito">
                                        CITO (naik 30%)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="form-group">
                    <div class="row align-items-center">
                        <div class="col-xl-4 text-right">
                            <label class="form-label" for="doctor_id">Group Tarif</label>
                        </div>
                        <div class="col-xl-8">
                            <div class="form-group">
                                <div class="form-check">
                                    <select class="select2 form-control w-100" id="group_tarif" name="group_tarif">
                                        @foreach ($group_penjamins as $group_penjamin)
                                            <option value="{{ $group_penjamin->id }}">{{ $group_penjamin->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="form-group">
                    <div class="row align-items-center">
                        <div class="col-xl-4 text-right">
                            <label class="form-label" for="doctor_id">Kelas Perawatan</label>
                        </div>
                        <div class="col-xl-8">
                            <div class="form-group">
                                <div class="form-check">
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

            <div class="col-xl-12">
                <h3 class="text-success" style="text-align: center"> <i class="fa fa-calculator"></i> <span
                        id="radiologi-total">Rp 0</span>
                </h3>
            </div>
            <div class="col-xl-12">
                <div class="form-group">
                    <input type="text" class="form-control mb-3" id="searchRadiology" placeholder="Cari tindakan...">
                    <div class="grid">
                        @foreach ($radiology_categories as $category)
                            <div class="card">
                                <h3>{{ $category->nama_kategori }}</h3>
                                @foreach ($category->parameter_radiologi as $parameter)
                                    <div class="item parameter_radiologi">
                                        <input type="checkbox" value="{{ $parameter->id }}"
                                            class="parameter_radiologi_checkbox"
                                            id="parameter_radiologi_{{ $parameter->id }}"> <label>
                                            <span class="form-check-label">{{ $parameter->parameter }}</span>(<span
                                                id="harga_parameter_radiologi_{{ $parameter->id }}">{{ rp(0) }}</span>)
                                        </label>

                                        <input type="number" value="1" class="form-control parameter_radiologi_number"
                                            id="jumlah_{{ $parameter->id }}">
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        window._kategoriRadiologi = @json($radiology_categories);
        window._tarifRadiologi = @json($tarifs);
    </script>
    <script src="{{ asset('js/simrs/simulasi-harga-radiologi.js') }}?v={{ time() }}"></script>
@endsection
