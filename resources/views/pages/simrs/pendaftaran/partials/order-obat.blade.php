@extends('pages.simrs.pendaftaran.detail-registrasi-pasien')

@section('page-layanan')
    <div id="panel-order-obat" class="panel mt-4">
        <div class="panel-hdr">
            <h2>Daftar Pemakaian Obat/Alkes Ruangan</h2>
            <div class="panel-toolbar">
                <button type="button" id="btn-create-order" class="btn btn-sm btn-primary waves-effect waves-themed">
                    <i class="fal fa-plus"></i> Tambah Order
                </button>
            </div>
        </div>
        <div class="panel-container show">
            <div class="panel-content">
                <table id="dt-order-obat" class="table table-bordered table-hover table-striped w-100">
                    <thead class="bg-primary-600">
                        <tr>
                            <th>No</th>
                            <th>No Order</th>
                            <th>Tanggal</th>
                            <th>Dokter</th>
                            <th>Gudang</th>
                            <th>User Entry</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="panel-form-container" class="mt-4" style="display: none;">
    </div>
@endsection

@push('script-detail-regis')
    <script>
        $(document).ready(function() {
            const registrationId = "{{ $registration->id }}";
            const panelList = $('#panel-order-obat');
            const panelFormContainer = $('#panel-form-container');
            let table;

            function showListPanel(shouldReload = false) {
                panelFormContainer.html('').hide();
                panelList.show();
                if (shouldReload && table) {
                    table.ajax.reload();
                }
            }

            function showFormPanel() {
                panelList.hide();
                panelFormContainer.show();
            }

            function loadForm(url) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        panelFormContainer.html(response);
                        showFormPanel();
                    },
                    error: function() {
                        showErrorAlert('Gagal memuat form.');
                    }
                });
            }

            if (!$.fn.DataTable.isDataTable('#dt-order-obat')) {
                table = $('#dt-order-obat').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: `{{ route('api.order-obat.data', ['registration_id' => $registration->id]) }}`,
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'no_order',
                            name: 'no_order'
                        },
                        {
                            data: 'order_date',
                            name: 'order_date'
                        },
                        {
                            data: 'doctor.name',
                            name: 'doctor.name',
                            defaultContent: '-'
                        },
                        {
                            data: 'warehouse.name',
                            name: 'warehouse.name'
                        },
                        {
                            data: 'user.name',
                            name: 'user.name'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ]
                });
            }

            $('#btn-create-order').on('click', function() {
                const url =
                    `{{ route('order-obat.fetch-create-form', ['registration_id' => $registration->id]) }}`;
                loadForm(url);
            });

            $('#dt-order-obat').on('click', '.btn-edit-order', function() {
                const orderId = $(this).data('id');
                let url =
                    `{{ route('order-obat.fetch-edit-form', ['registration_id' => $registration->id, 'order_obat' => ':id']) }}`;
                url = url.replace(':id', orderId);
                loadForm(url);
            });

            $('#dt-order-obat').on('click', '.btn-delete-order', function() {
                const orderId = $(this).data('id');
                let url =
                    `{{ route('order-obat.destroy', ['registration_id' => $registration->id, 'order_obat' => ':id']) }}`;
                url = url.replace(':id', orderId);

                showDeleteConfirmation(function() {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                showSuccessAlert(response.message);
                                showListPanel(true);
                            } else {
                                showErrorAlert(response.message);
                            }
                        },
                        error: function() {
                            showErrorAlert('Tidak dapat menghubungi server.');
                        }
                    });
                });
            });

            $(panelFormContainer).on('click', '#btn-cancel-order', function() {
                showListPanel();
            });

            $(panelFormContainer).on('submit', '#order-form', function(e) {
                e.preventDefault();

                let itemsData = [];
                $('#item-table tbody tr').each(function() {
                    itemsData.push({
                        obat_id: $(this).data('id'),
                        quantity: $(this).find('.quantity-input').val(),
                        price: $(this).data('price'),
                    });
                });

                if (itemsData.length === 0) {
                    showErrorAlertNoRefresh('Minimal harus ada 1 item order.');
                    return;
                }

                let formData = $(this).serializeArray();
                formData.push({
                    name: 'items',
                    value: JSON.stringify(itemsData)
                });

                let isUpdate = $(this).find('input[name="_method"][value="PUT"]').length > 0;
                let url;

                if (isUpdate) {
                    const orderId = $(this).data('order-id');
                    let updateUrl =
                        `{{ route('order-obat.update', ['registration_id' => $registration->id, 'order_obat' => ':id']) }}`;
                    url = updateUrl.replace(':id', orderId);
                } else {
                    url = `{{ route('order-obat.store', ['registration_id' => $registration->id]) }}`;
                }

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            showSuccessAlert(response.message);
                            showListPanel(true);
                        }
                    },
                    error: function(xhr) {
                        showErrorAlert(xhr.responseJSON.message || 'Terjadi kesalahan.');
                    }
                });
            });
        });
    </script>
@endpush
