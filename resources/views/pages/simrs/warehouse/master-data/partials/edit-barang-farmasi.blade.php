@extends('inc.layout-no-side') {{-- Menggunakan layout utama --}}
@section('title', 'Edit Barang Farmasi')

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Form <span class="fw-300"><i>Edit Barang Farmasi</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif
                            <form action="{{ route('warehouse.master-data.barang-farmasi.update', $barang->id) }}"
                                method="POST">
                                @csrf
                                @method('PUT')
                                @include(
                                    'pages.simrs.warehouse.master-data.partials.barang-farmasi-form-fields',
                                    ['barang' => $barang]
                                )
                                <div
                                    class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 d-flex flex-row align-items-center">
                                    <button class="btn btn-warning ml-auto" type="submit">Update</button>
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
    @include('pages.simrs.warehouse.master-data.partials.barang-farmasi-form-js')
@endsection
