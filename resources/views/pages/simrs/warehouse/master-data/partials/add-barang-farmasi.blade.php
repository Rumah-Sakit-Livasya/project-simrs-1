@extends('inc.layout-no-side')
@section('title', 'Tambah Barang Farmasi')
@section('extended-css')
    <style>
        .display-none {
            display: none;
        }

        .popover {
            max-width: 100%;
        }

        .modal-dialog {
            max-width: 70%;
        }

        .borderless-input {
            border: 0;
            border-bottom: 1.9px solid #eaeaea;
            margin-top: -.5rem;
            border-radius: 0;
        }

        .qty {
            width: 60px;
            margin-left: 10px;
        }

        .form-label {
            font-weight: 500;
        }

        .form-section-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #495057;
        }
    </style>
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Tambah Barang Farmasi</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif
                            <form action="{{ route('warehouse.master-data.barang-farmasi.store') }}" method="post"
                                autocomplete="off">
                                @csrf
                                @method('post')
                                @include(
                                    'pages.simrs.warehouse.master-data.partials.barang-farmasi-form-fields',
                                    ['barang' => new \App\Models\WarehouseBarangFarmasi()]
                                )
                                <div class="row mt-4">
                                    <div class="col-xl-6">
                                        <a onclick="window.close()"
                                            class="btn btn-lg btn-default waves-effect waves-themed">
                                            <span class="fal fa-arrow-left mr-1 text-primary"></span>
                                            <span class="text-primary">Kembali</span>
                                        </a>
                                    </div>
                                    <div class="col-xl-6 text-right">
                                        <button type="submit" id="order-submit"
                                            class="btn btn-lg btn-primary waves-effect waves-themed">
                                            <span class="fal fa-save mr-1"></span>
                                            Simpan
                                        </button>
                                    </div>
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
