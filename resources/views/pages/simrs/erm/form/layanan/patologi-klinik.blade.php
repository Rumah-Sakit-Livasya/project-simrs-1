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
            @include('pages.simrs.erm.form.penunjang.partials.laboratorium')
        </div>
    @endif
@endsection
@section('plugin-erm')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>

    <script>
        $(document).ready(function() {
            $('body').addClass('layout-composed');

            $('.select2-dropdown').select2({
                placeholder: 'Pilih item berikut',
                dropdownParent: $('#modal-tambah-alat')
            });

            $('#departement_id').select2({
                placeholder: 'Pilih Klinik',
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
            /* Fungsi untuk memformat child row */
            function format(details) {
                // Handle jika tidak ada detail
                if (!details || details.length === 0) {
                    return '<div class="p-3 text-center">Tidak ada detail parameter untuk order ini.</div>';
                }

                // 1. Inisialisasi variabel untuk menyimpan total biaya
                let totalPrice = 0;

                // Memulai string HTML untuk tabel
                let table = `<table class="table table-sm table-striped table-bordered child-table">
                            <thead class="bg-info-50">
                                <tr>
                                    <th scope="col" style="width: 30px;">#</th>
                                    <th scope="col">Parameter</th>
                                    <th scope="col">Harga</th>
                                    <th scope="col">Catatan</th>
                                </tr>
                            </thead>
                            <tbody>`;

                // Loop melalui setiap item detail untuk membuat baris tabel
                details.forEach((item, index) => {
                    // 2. Akumulasi total di dalam loop. Pastikan nilainya adalah angka.
                    totalPrice += (parseFloat(item.nominal_rupiah) || 0);

                    const formattedPrice = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 2
                    }).format(item.nominal_rupiah || 0);

                    const parameterName = item.parameter_laboratorium ? item.parameter_laboratorium
                        .parameter : '<i class="text-muted">N/A</i>';

                    table += `<tr>
                            <td>${index + 1}</td>
                            <td>${parameterName}</td>
                            <td>${formattedPrice}</td>
                            <td>${item.catatan || ''}</td>
                        </tr>`;
                });

                // Menutup tbody
                table += '</tbody>';

                // 3. Tambahkan <tfoot> untuk menampilkan total biaya
                // Format total harga ke dalam format mata uang Rupiah
                const formattedTotal = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 2
                }).format(totalPrice);

                table += `<tfoot>
                        <tr>
                            <td colspan="2" class="font-weight-bold">Total Biaya</td>
                            <td class="font-weight-bold">${formattedTotal}</td>
                            <td></td>
                        </tr>
                      </tfoot>`;

                // Menutup tabel
                table += '</table>';
                return table;
            }

            // Inisialisasi DataTable (tidak ada perubahan di sini)
            var table = $('#dt-lab-orders').DataTable({
                responsive: true,
                order: [
                    [1, 'desc']
                ],
                columnDefs: [{
                    orderable: false,
                    targets: 0
                }]
            });

            // Event listener untuk membuka dan menutup detail (tidak ada perubahan di sini)
            $('#dt-lab-orders tbody').on('click', 'td.details-control', function() {
                var tr = $(this).closest('tr');
                var row = table.row(tr);
                var icon = $(this).find('i');
                var detailData = JSON.parse(tr.attr('data-details'));

                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('details-shown');
                    icon.removeClass('fa-minus-circle text-danger').addClass('fa-plus-circle text-success');
                } else {
                    row.child(format(detailData)).show();
                    tr.addClass('details-shown');
                    icon.removeClass('fa-plus-circle text-success').addClass('fa-minus-circle text-danger');
                }
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
