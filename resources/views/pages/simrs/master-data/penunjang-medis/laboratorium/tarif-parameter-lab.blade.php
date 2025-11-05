@extends('inc.layout-no-side')
@section('title', 'Parameter Laboratorium')
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
                            Tarif Parameter {{ $parameter_laboratorium->parameter }}
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- datatable start -->
                            <form id="store-form">
                                @csrf
                                <div class="table-responsive">
                                    <div class="mb-3">
                                        <select id="grup-penjamin-id" class="form-control select2" name="grup_penjamin_id">
                                            @foreach ($grup_penjamin as $row)
                                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <table id="dt-basic-example"
                                        class="table table-bordered table-hover table-striped w-100">
                                        <i id="loading-spinner" class="fas fa-spinner fa-spin"></i>
                                        <thead class="bg-primary-600">
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Kelas</th>
                                                <th>Share Dr</th>
                                                <th>Share Rs</th>
                                                <th>Prasarana</th>
                                                <th>BHP</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($kelas_rawat as $row)
                                                @php
                                                    // Relasi sudah di-load, kita hanya perlu mengambil item pertama
                                                    $tarif = $row->tarif_parameter_laboratorium->first();
                                                @endphp
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $row->kelas }}</td>
                                                    <td>
                                                        <input type="text" name="share_dr[{{ $row->id }}]"
                                                            value="{{ $tarif->share_dr ?? 0 }}"
                                                            data-id="{{ $row->id }}"
                                                            class="form-control rounded-0 border-top-0 border-left-0 border-right-0 p-0 mr-2 tarif-dr">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="share_rs[{{ $row->id }}]"
                                                            value="{{ $tarif->share_rs ?? 0 }}"
                                                            data-id="{{ $row->id }}"
                                                            class="form-control rounded-0 border-top-0 border-left-0 border-right-0 p-0 mr-2 tarif-rs">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="bhp[{{ $row->id }}]"
                                                            value="{{ $tarif->bhp ?? 0 }}" data-id="{{ $row->id }}"
                                                            class="form-control rounded-0 border-top-0 border-left-0 border-right-0 p-0 mr-2 tarif-bhp">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="prasarana[{{ $row->id }}]"
                                                            value="{{ $tarif->prasarana ?? 0 }}"
                                                            data-id="{{ $row->id }}"
                                                            class="form-control rounded-0 border-top-0 border-left-0 border-right-0 p-0 mr-2 tarif-prasarana">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="total[{{ $row->id }}]"
                                                            value="{{ $tarif->total ?? 0 }}" data-id="{{ $row->id }}"
                                                            readonly
                                                            class="form-control rounded-0 border-top-0 border-left-0 border-right-0 p-0 mr-2 tarif-total">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

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
            $('.tarif-dr, .tarif-rs, .tarif-bhp, .tarif-prasarana').on('input', function() {
                const rowId = $(this).data('id'); // Get the row ID
                const drInput = $(`.tarif-dr[data-id="${rowId}"]`);
                const rsInput = $(`.tarif-rs[data-id="${rowId}"]`);
                const bhpInput = $(`.tarif-bhp[data-id="${rowId}"]`);
                const prasaranaInput = $(`.tarif-prasarana[data-id="${rowId}"]`);
                const totalInput = $(`.tarif-total[data-id="${rowId}"]`);

                // Parse values as numbers and calculate the total
                const drValue = parseInt(drInput.val()) || 0;
                const rsValue = parseInt(rsInput.val()) || 0;
                const bhpValue = parseInt(bhpInput.val()) || 0;
                const prasaranaValue = parseInt(prasaranaInput.val()) || 0;
                const totalValue = drValue + rsValue + bhpValue + prasaranaValue;

                // Update the total input
                totalInput.val(totalValue); // Format to 2 decimal places
            });

            let parameterId = null;
            $('#loading-spinner').show();

            $('.select2').select2();

            $('#store-form').on('submit', function(e) {
                e.preventDefault(); // Mencegah form dari pengiriman default

                let grupPenjaminId = $('#grup-penjamin-id').val(); // Ambil grup_penjamin_id
                let parameterId = @json($parameter_laboratorium->id);

                // Route Laravel dengan menggunakan nama route
                let url =
                    "{{ route('master-data.penunjang-medis.laboratorium.parameter.tarif.store', ['parameterId' => ':parameterId', 'grupPenjaminId' => ':grupPenjaminId']) }}"
                    .replace(':parameterId', parameterId)
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
                let parameterId = @json($parameter_laboratorium->id);

                let url =
                    "{{ route('master-data.penunjang-medis.laboratorium.parameter.tarif.get', ['parameterId' => ':parameterId', 'grupPenjaminId' => ':grupPenjaminId']) }}"
                    .replace(':parameterId', parameterId)
                    .replace(':grupPenjaminId', grupPenjaminId);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: url,
                    type: 'GET',
                    data: $(this).serialize(), // Ambil semua data dari form
                    success: function(response) {
                        if (response.data.length > 0) {
                            response.data.forEach(function(item) {
                                // Set the value of the corresponding input fields
                                $('input[name="share_dr[' + item.kelas_rawat_id + ']"]')
                                    .val(item.share_dr);
                                $('input[name="share_rs[' + item.kelas_rawat_id + ']"]')
                                    .val(item.share_rs);
                                $('input[name="prasarana[' + item.kelas_rawat_id +
                                        ']"]')
                                    .val(item.prasarana);
                                $('input[name="bhp[' + item.kelas_rawat_id + ']"]')
                                    .val(item.bhp);
                                $('input[name="total[' + item.kelas_rawat_id + ']"]')
                                    .val(item.total);
                            });
                        } else {
                            $('#dt-basic-example tbody input').val(0);
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
