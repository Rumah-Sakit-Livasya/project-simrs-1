@extends('inc.layout')
@section('title', 'Antrian Farmasi')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        @include('pages.simrs.farmasi.antrian-farmasi.partials.index-control')
    </main>
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <script src="{{ asset('js/simrs/farmasi/antrian-farmasi/index.js') }}?v={{ time() }}"></script>
@endsection
