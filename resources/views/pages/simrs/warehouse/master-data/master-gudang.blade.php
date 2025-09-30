@extends('inc.layout')
@section('title', 'List Master Gudang')

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-warehouse'></i> Master Gudang
                <small>
                    Pengelolaan data master gudang, apotek, dan warehouse.
                </small>
            </h1>
        </div>

        {{-- Form Pencarian --}}
        @include('pages.simrs.warehouse.master-data.partials.master-gudang-form')

        {{-- Tabel Data --}}
        @include('pages.simrs.warehouse.master-data.partials.master-gudang-datatable')
    </main>

    {{-- Modal Tambah Data --}}
    @include('pages.simrs.warehouse.master-data.partials.gudang-modal')
@endsection

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script> {{-- Tambahkan ini --}}

    <script>
        $(document).ready(function() {
            // Inisialisasi Datatable
            $('#dt-master-gudang').dataTable({
                responsive: true,
                lengthChange: false,
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                        extend: 'pdfHtml5',
                        text: 'PDF',
                        titleAttr: 'Generate PDF',
                        className: 'btn-outline-danger btn-sm mr-1'
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        titleAttr: 'Generate Excel',
                        className: 'btn-outline-success btn-sm mr-1'
                    },
                    {
                        extend: 'csvHtml5',
                        text: 'CSV',
                        titleAttr: 'Generate CSV',
                        className: 'btn-outline-primary btn-sm mr-1'
                    },
                    {
                        extend: 'copyHtml5',
                        text: 'Copy',
                        titleAttr: 'Copy to clipboard',
                        className: 'btn-outline-primary btn-sm mr-1'
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        titleAttr: 'Print Table',
                        className: 'btn-outline-primary btn-sm'
                    }
                ]
            });

            $('.select2').select2({
                // jika select2 berada di dalam modal, dropdownParent diperlukan
                // jika tidak, baris ini tidak akan berpengaruh buruk
                dropdownParent: $(this).closest('.modal')
            });

            $('#filter_apotek').select2({
                placeholder: 'Pilih status apotek'
            });
            $('#filter_warehouse').select2({
                placeholder: 'Pilih status warehouse'
            });
            $('#filter_aktif').select2({
                placeholder: 'Pilih status'
            });

            // Inisialisasi Select2 KHUSUS untuk MODAL
            $('.select2-modal').select2({
                dropdownParent: $('#gudangModal') // Penting!
            });

        });
    </script>
    {{-- JS untuk fungsionalitas hapus --}}
    <script src="{{ asset('js/simrs/warehouse/master-data/master-gudang.js') }}?v={{ time() }}"></script>
@endsection
