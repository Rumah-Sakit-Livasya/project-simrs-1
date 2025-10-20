@extends('inc.layout')
@if (isset($barang))
    @section('title', 'Warehouse Report: Kartu Stok [' . $satuan->kode . '] ' . $barang->nama)
@else
    @section('title', 'Warehouse Report: Kartu Stok ')
@endif
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        @include('pages.simrs.warehouse.report.partials.kartu-stock-form')
        @include('pages.simrs.warehouse.report.partials.kartu-stock-datatable')
    </main>
@endsection

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi Select2
            $('.select2').select2();

            // Inisialisasi Daterangepicker
            $('#datepicker-1').daterangepicker({
                opens: 'left',
                locale: {
                    format: 'YYYY-MM-DD'
                },
                // Set tanggal default jika belum ada request
                @if (!request('tanggal'))
                    startDate: moment().startOf('month'),
                    endDate: moment(),
                @endif
            });

            // Inisialisasi DataTables
            $('#dt-kartu-stok').dataTable({
                responsive: true,
                lengthChange: false,
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [ /* ... tombol export ... */ ]
            });

            // [BARU] Loading state untuk tombol Cari
            $('#filter-form').on('submit', function() {
                const btn = $('#btn-cari');
                // Disable tombol dan tampilkan spinner
                btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mencari...'
                );
            });
        });
    </script>
@endsection
