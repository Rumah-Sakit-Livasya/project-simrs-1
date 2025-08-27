@extends('inc.layout-no-side')
@section('title', 'Resep Elektronik')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        @include('pages.simrs.farmasi.transaksi-resep.partials.popup-resep-elektronik-form')
        @include('pages.simrs.farmasi.transaksi-resep.partials.popup-resep-elektronik-datatable')
    </main>
@endsection
