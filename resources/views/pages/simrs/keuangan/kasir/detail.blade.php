@extends('inc.layout')
@section('title', 'Kasir')
@section('content')
    <style>
        table {
            font-size: 8pt !important;
        }
    </style>
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <a href="{{ route('tagihan.pasien.index') }}" class="btn btn-primary mb-3">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <div id="panel-5" class="panel" style="height: 80vh;">
                    <div class="panel-container show" style="height: 100%;">
                        <div class="panel-content" style="height: calc(100% - 50px);">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#tagihan-pasien" role="tab"><i
                                            class="fal fa-home mr-1"></i> Tagihan Pasien</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#dp_pasien" role="tab"><i
                                            class="fal fa-clock mr-1"></i> DP Pasien</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#pembayaran-tagihan" role="tab"><i
                                            class="fal fa-user mr-1"></i> Pembayaran Tagihan</a>
                                </li>
                            </ul>
                            <div class="tab-content border border-top-0 p-3" style="height: 100%; overflow-x: hidden  ;">
                                {{-- ==================== Tagihan Pasien ==================== --}}
                                @include('pages.simrs.keuangan.kasir.partials.tagihan-pasien')
                                {{-- ==================== Tagihan Pasien ==================== --}}

                                {{-- ==================== Pembayaran Tagihan ==================== --}}
                                @include('pages.simrs.keuangan.kasir.partials.pembayaran-tagihan')
                                {{-- ==================== Pembayaran Tagihan ==================== --}}


                                {{-- ==================== DP Pasien ==================== --}}
                                @include('pages.simrs.keuangan.kasir.partials.down-payment')
                                {{-- ==================== DP Pasien ==================== --}}

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('pages.simrs.keuangan.kasir.partials.add-tagihan-modal')
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    {{-- Select 2 --}}
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    {{-- Datepicker --}}
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    {{-- Datepicker Range --}}
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>

    @yield('plugin-add-tagihan')
    @yield('plugin-tagihan-pasien')
    @yield('plugin-down-payment')
    @yield('plugin-pembayaran-tagihan')
@endsection
