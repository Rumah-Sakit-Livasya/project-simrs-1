@extends('pages.simrs.erm.index')
@section('erm')
    @if (isset($registration) || $registration != null)
        {{-- content start --}}
        <div class="tab-content p-3">
            <div class="tab-pane fade show active" id="tab_default-1" role="tabpanel">
                @include('pages.simrs.erm.partials.detail-pasien')
            </div>
        </div>
        <div class="p-3">
            @include('pages.simrs.pendaftaran.partials.laboratorium')
        </div>
    @endif
@endsection
@section('plugin-erm')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script script src="/js/formplugins/select2/select2.bundle.js"></script>
    @include('pages.simrs.poliklinik.partials.action-js.pemakaian_alat')
    <script>
        $(document).ready(function() {
            $('body').addClass('layout-composed');

            $('.select2-dropdown').select2({
                placeholder: 'Pilih item berikut',
                dropdownParent: $('#modal-tambah-alat')
            });

            placeholder: 'Pilih Klinik',
                $('#departement_id').select2({});
            // $('#doctor_id').select2({
            //     placeholder: 'Pilih Dokter',
            // });
            $('#dt-basic-example').dataTable({
                responsive: false,
            });

            $('.js-thead-colors a').on('click', function() {
                var theadColor = $(this).attr("data-bg");
                console.log(theadColor);
                $('#dt-basic-example thead').removeClassPrefix('bg-').addClass(theadColor);
            });

            $('.js-tbody-colors a').on('click', function() {
                var theadColor = $(this).attr("data-bg");
                console.log(theadColor);
                $('#dt-basic-example').removeClassPrefix('bg-').addClass(theadColor);
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Inisialisasi DataTable untuk daftar order
            $('#dt-lab-orders').DataTable({
                responsive: true,
                "order": [
                    [2, "desc"]
                ] // Urutkan berdasarkan tanggal order
            });

            // Inisialisasi Popover (jika masih menggunakan Bootstrap 4/SmartAdmin)
            $('[data-toggle="popover"]').popover();

            // Logika untuk menampilkan/menyembunyikan form
            $('#btn-show-lab-form').on('click', function() {
                $('#panel-laboratorium-list').hide();
                $('#panel-laboratorium-form').show();
            });

            // Event listener untuk tombol kembali di dalam form
            // Diletakkan di sini agar bisa mengakses kedua panel
            $(document).on('click', '.btn-back-to-lab-list', function() {
                $('#panel-laboratorium-form').hide();
                $('#panel-laboratorium-list').show();
            });
        });
    </script>
    <script>
        // Pass data dari PHP ke JavaScript
        window._kategoriLaboratorium = @json($laboratorium_categories);
        window._tarifLaboratorium = @json($laboratorium_tarifs);
        window._registration = @json($registration);
        window._groupPenjaminId = @json($groupPenjaminId);
        window._kelasRawats = @json($kelas_rawats);
    </script>
    {{-- Pastikan nama file JS ini sesuai dan dimuat di layout utama Anda --}}
    <script src="{{ asset('js/simrs/form-laboratorium.js') }}?v={{ time() }}"></script>
@endsection
