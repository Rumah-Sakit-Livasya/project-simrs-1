@extends('inc.layout')
@section('title', 'List Order Radiologi')
@section('content')
    <main id="js-page-content" role="main" class="page-content" style="background: white">
        <div class="panel-hdr border-top">
            <h2 class="text-light">
                <i class="fas fa-address-card mr-3 ml-2 text-primary" style="transform: scale(2.1)"></i>
                <span class="text-primary">Simulasi Harga Radiologi</span>
            </h2>
        </div>
        <div class="row">
            <div class="col-xl-6">
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
            <div class="col-xl-6">
                <h3>Tindakan</h3>
            </div>
            <div class="col-xl-6">
                <h3 class="text-success" style="text-align: right"> <i class="fa fa-calculator"></i> <span
                        id="radiologi-total">Rp 0</span>
                </h3>
            </div>
            <div class="col-xl-12">
                <div class="form-group">
                    <input type="text" class="form-control mb-3" id="searchRadiology" placeholder="Cari tindakan...">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Tindakan</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody id="radiologyTable">
                            @foreach ($radiology_categories as $category)
                                <tr class="table-info">
                                    <td colspan="2">
                                        <h4 style="text-align: center">{{ $category->nama_kategori }}</h4>
                                    </td>
                                </tr>
                                @foreach ($category->parameter_radiologi as $parameter)
                                    <tr class="parameter_radiologi">
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input parameter_radiologi_checkbox" type="checkbox"
                                                    value="{{ $parameter->id }}"
                                                    id="parameter_radiologi_{{ $parameter->id }}">
                                                <label class="form-check-label"
                                                    for="parameter_radiologi_{{ $parameter->id }}">
                                                    {{ $parameter->parameter }}
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="number" value="1"
                                                class="form-control parameter_radiologi_number"
                                                id="jumlah_{{ $parameter->id }}">
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <script>
        window._parameterRadiologi = @json($radiology_categories);
        window._tarifRadiologi = @json($tarifs);
    </script>
    <script src="{{ asset('js/simrs/simulasi-harga-radiologi.js') }}?v={{ time() }}"></script>
@endsection
