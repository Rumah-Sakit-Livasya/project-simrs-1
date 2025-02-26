@extends('inc.layout')
@section('title', 'Parameter Radiologi')
@section('extended-css')
    <style>
        div.table-responsive>div.dataTables_wrapper>div.row>div[class^="col-"]:last-child {
            padding: 0px;
        }

        .dataTables_scrollHeadInner,
        .dataTables_scrollFootInner {
            width: 100% !important;
        }
    </style>
@endsection
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Tarif Biaya Registrasi Layanan
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- datatable start -->
                            <div class="mb-3">
                                <select id="grup-penjamin-id" class="form-control select2" name="group_penjamin_id">
                                    @foreach ($grup_penjamin as $row)
                                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <form id="store-form">
                                @csrf
                                @method('POST')
                                <table>
                                    <tr>
                                        <td>Biaya Administrasi</td>
                                        <td class="pl-2"> : </td>
                                        <td class="pl-3">{{ $tarif_registrasi->nama_tarif }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="p-1"></td>
                                    </tr>
                                    <tr class="mt-3">
                                        <td>Tarif</td>
                                        <td class="pl-2"> : </td>
                                        <td class="pl-3">
                                            <input type="number" id="example-input-material" name="harga"
                                                class="form-control form-control-lg rounded-0 border-top-0 border-left-0 border-right-0 px-0 py-0"
                                                style="height: auto;" value="{{ $harga->harga ?? 0 }}">

                                        </td>
                                    </tr>
                                </table>
                                <button type="submit" class="btn btn-primary mt-3 btn-block">Update Tarif</button>
                            </form>

                            <!-- datatable end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            // let tarifRegistId = null;
            const tarifRegistId = @json($tarif_registrasi->id);
            $('#loading-spinner').show();

            $('.select2').select2();

            $('#store-form').on('submit', function(e) {
                e.preventDefault(); // Mencegah form dari pengiriman default

                let grupPenjaminId = $('#grup-penjamin-id').val(); // Ambil grup_penjamin_id
                // Route Laravel dengan menggunakan nama route
                let url =
                    "{{ route('master-data.setup.tarif-registrasi.tarif.store', ['tarifRegistId' => ':tarifRegistId', 'grupPenjaminId' => ':grupPenjaminId']) }}"
                    .replace(':tarifRegistId', tarifRegistId)
                    .replace(':grupPenjaminId', grupPenjaminId);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: $(this).serialize(), // Ambil semua data dari form
                    success: function(response) {
                        showSuccessAlert(response.message);

                        setTimeout(() => {
                            console.log('Reloading the page now.');
                            window.location.reload();
                        }, 1000);
                    },
                    error: function(xhr, status, error) {
                        // Tampilkan pesan error
                        showErrorAlert('Terjadi kesalahan: ' + error);
                    }
                });
            });

            $('#grup-penjamin-id').on('change', function() {

                let grupPenjaminId = $(this).val(); // Ambil grup_penjamin_id

                let url =
                    "{{ route('master-data.setup.tarif-registrasi.tarif.get', ['tarifRegistId' => ':tarifRegistId', 'grupPenjaminId' => ':grupPenjaminId']) }}"
                    .replace(':tarifRegistId', tarifRegistId)
                    .replace(':grupPenjaminId', grupPenjaminId);

                $.ajax({
                    url: url,
                    type: 'GET',
                    data: $(this).serialize(), // Ambil semua data dari form
                    success: function(response) {
                        if ($.isEmptyObject(response)) {
                            console.log(response);
                            $('input[name="harga"]').val(0);
                        } else {
                            $('input[name="harga"]').val(response.harga);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Tampilkan pesan error
                        showErrorAlert('Terjadi kesalahan: ' + error);
                    }
                });
            });

            $('#dt-basic-example').DataTable({
                "drawCallback": function(settings) {
                    $('#loading-spinner').hide();
                },
                responsive: false,
                scrollX: true,
                lengthChange: false,
                paging: false,
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end buttons-container'B>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                        extend: 'pdfHtml5',
                        text: 'PDF',
                        titleAttr: 'Generate PDF',
                        className: 'btn-outline-danger btn-sm mr-1 custom-margin',
                        customize: function(doc) {
                            var table = doc.content[1].table.body;
                            var inputs = $(
                                'input[name^="share_dr"], input[name^="share_rs"], input[name^="total"]'
                            );
                            var rowIdx = 1;

                            inputs.each(function(index) {
                                var value = $(this).val();
                                var colIdx = $(this).closest('td').index();

                                if (table[rowIdx] && table[rowIdx][colIdx]) {
                                    table[rowIdx][colIdx].text = value;
                                }

                                if (index % 3 === 2) {
                                    rowIdx++;
                                }
                            });
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        titleAttr: 'Generate Excel',
                        className: 'btn-outline-success btn-sm mr-1 custom-margin',
                        customize: function(xlsx) {
                            var sheet = xlsx.xl.worksheets['sheet1.xml'];
                            var table = $('#dt-basic-example').DataTable().rows().data();
                            var inputs = $(
                                'input[name^="share_dr"], input[name^="share_rs"], input[name^="total"]'
                            );
                            var rowIdx = 1;

                            inputs.each(function(index) {
                                var value = $(this).val();
                                var cell = $(sheet).find('row:eq(' + (rowIdx + 1) +
                                    ') c[r^="C"], row:eq(' + (rowIdx + 1) +
                                    ') c[r^="D"], row:eq(' + (rowIdx + 1) +
                                    ') c[r^="E"]').eq(index % 3);

                                if (cell.length) {
                                    cell.find('v').text(value);
                                }

                                if (index % 3 === 2) {
                                    rowIdx++;
                                }
                            });
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        text: 'CSV',
                        titleAttr: 'Generate CSV',
                        className: 'btn-outline-primary btn-sm mr-1 custom-margin'
                    },
                    {
                        extend: 'copyHtml5',
                        text: 'Copy',
                        titleAttr: 'Copy to clipboard',
                        className: 'btn-outline-primary btn-sm mr-1 custom-margin'
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        titleAttr: 'Print Table',
                        className: 'btn-outline-primary btn-sm custom-margin'
                    }
                ]
            });

        });
    </script>
@endsection
