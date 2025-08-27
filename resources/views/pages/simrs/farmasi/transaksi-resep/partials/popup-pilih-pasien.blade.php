@extends('inc.layout-no-side')
@section('title', 'Pilih Pasien')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        @include('pages.simrs.farmasi.transaksi-resep.partials.popup-pilih-pasien-form')
        @include('pages.simrs.farmasi.transaksi-resep.partials.popup-pilih-pasien-datatable')
    </main>
@endsection
