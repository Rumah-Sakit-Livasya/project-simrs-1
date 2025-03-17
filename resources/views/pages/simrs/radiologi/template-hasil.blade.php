@extends('inc.layout')
@section('title', 'Template Hasil Radiologi')
@section('content')
    <main id="js-page-content" role="main" class="page-content">

        @include('pages.simrs.radiologi.partials.template-hasil-form')

        @include('pages.simrs.radiologi.partials.template-hasil-datatable')

    </main>
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    {{-- Select 2 --}}
    <script src="/js/formplugins/select2/select2.bundle.js"></script>

    <script src="{{ asset('js/simrs/template-hasil-radiologi.js') }}?v={{ time() }}"></script>

    <script src="{{ asset('summernote-0.9.0/summernote-bs4.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            console.log(1);

            $('#summernote').summernote({
                height: 400,
                wdith: 800,
                placeholder: 'Hasil pemeriksaan...'
            });
        });
    </script>
@endsection
