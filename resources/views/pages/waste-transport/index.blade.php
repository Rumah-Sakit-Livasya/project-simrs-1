@extends('inc.layout')
@section('title', ' Checklist Harian')
@section('content')
    <main id="js-page-content" role="main" class="page-content">

        <div class="container mt-5">
            <h1 class="mb-4">Waste Management - Transport</h1>

            <!-- Waste Transport -->
            <div class="card">
                <div class="card-header">
                    <h4>Input Pengangkutan</h4>
                </div>
                <div class="card-body">
                    <button type="button" class="btn btn-info mb-3" id="createNewTransport">Tambah Data
                        Pengangkutan</button>
                    <table class="table table-bordered transport-datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Kategori</th>
                                <th>Volume (Kg)</th>
                                <th>PIC (Vendor)</th>
                                <th>No. Kendaraan</th>
                                <th width="150px">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>


        <!-- Transport Modal -->
        <div class="modal fade" id="ajaxTransportModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="transportModalTitle"></h5>
                    </div>
                    <div class="modal-body">
                        <form id="transportForm" name="transportForm">
                            <input type="hidden" name="id" id="transport_id">

                            <!-- Items Container -->
                            <div id="transportItemsContainer">
                                <!-- Item Template -->
                                <div class="item-row mb-3" data-item="0">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label class="form-label">Tanggal</label>
                                            <input type="date" class="form-control item-date" name="items[0][date]"
                                                required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Kategori</label>
                                            <select class="form-control item-category" name="items[0][waste_category_id]"
                                                required>
                                                <option value="">Pilih Kategori</option>
                                                @foreach ($transportCategories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">No. Kendaraan</label>
                                            <select class="form-control item-vehicle" name="items[0][vehicle_id]" required>
                                                <option value="">Pilih Kendaraan</option>
                                                @foreach ($vehicles as $vehicle)
                                                    <option value="{{ $vehicle->id }}">{{ $vehicle->license_plate }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Volume</label>
                                            <input type="number" step="0.01" class="form-control item-volume"
                                                name="items[0][volume]" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">PIC (Vendor)</label>
                                            <input type="text" class="form-control item-pic" name="items[0][pic_vendor]"
                                                required>
                                        </div>
                                        <div class="col-md-1">
                                            <label class="form-label">&nbsp;</label>
                                            <button type="button" class="btn btn-danger btn-sm remove-item"
                                                style="display: none;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Add Item Button -->
                            <div class="text-center mb-3">
                                <button type="button" class="btn btn-success btn-sm" id="addTransportItem">
                                    <i class="fas fa-plus"></i> Tambah Item
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" id="saveBtnTransport">Simpan Semua Item</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
    </body>
@endsection
@section('plugin')
    <script type="text/javascript">
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // WASTE TRANSPORT SCRIPT
            var transportTable = $('.transport-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('waste-transports.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'waste_category.name',
                        name: 'wasteCategory.name'
                    },
                    {
                        data: 'volume',
                        name: 'volume'
                    },
                    {
                        data: 'pic_vendor',
                        name: 'pic_vendor'
                    },
                    {
                        data: 'vehicle.license_plate',
                        name: 'vehicle.license_plate'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                order: [
                    [1, 'desc']
                ] // Order by date column (index 1) descending
            });

            var transportItemCounter = 0;

            $('#createNewTransport').click(function() {
                $('#transport_id').val('');
                $('#transportForm').trigger("reset");
                $('#transportModalTitle').html("Tambah Input Pengangkutan");
                $('#ajaxTransportModal').modal('show');

                // Reset to single item
                $('#transportItemsContainer').html($('#transportItemsContainer .item-row').first().clone());
                $('#transportItemsContainer .item-row').attr('data-item', '0');
                $('#transportItemsContainer .remove-item').hide();
                transportItemCounter = 0;
            });

            // Add new transport item
            $('#addTransportItem').click(function() {
                transportItemCounter++;
                var newItem = $('#transportItemsContainer .item-row').first().clone();
                newItem.attr('data-item', transportItemCounter);
                newItem.find('input, select').val('');
                newItem.find('input, select').each(function() {
                    var name = $(this).attr('name');
                    if (name) {
                        $(this).attr('name', name.replace('[0]', '[' + transportItemCounter + ']'));
                    }
                });
                newItem.find('.remove-item').show();
                $('#transportItemsContainer').append(newItem);
            });

            // Remove transport item
            $(document).on('click', '.remove-item', function() {
                $(this).closest('.item-row').remove();
                // Reindex remaining items
                $('#transportItemsContainer .item-row').each(function(index) {
                    $(this).attr('data-item', index);
                    $(this).find('input, select').each(function() {
                        var name = $(this).attr('name');
                        if (name) {
                            $(this).attr('name', name.replace(/\[\d+\]/, '[' + index +
                                ']'));
                        }
                    });
                });
                transportItemCounter = $('#transportItemsContainer .item-row').length - 1;
                // Hide remove button if only one item left
                if ($('#transportItemsContainer .item-row').length === 1) {
                    $('#transportItemsContainer .remove-item').hide();
                }
            });

            $('body').on('click', '.editTransport', function() {
                var id = $(this).data('id');
                $.get("{{ url('api/waste-transports') }}" + '/' + id + '/edit', function(data) {
                    $('#transportModalTitle').html("Edit Input Pengangkutan");
                    $('#ajaxTransportModal').modal('show');
                    $('#transport_id').val(data.id);
                    // Mengisi form
                    $('#transportForm input[name="date"]').val(data.date);
                    $('#transportForm select[name="waste_category_id"]').val(data
                        .waste_category_id);
                    $('#transportForm select[name="vehicle_id"]').val(data.vehicle_id);
                    $('#transportForm input[name="volume"]').val(data.volume);
                    $('#transportForm input[name="pic_vendor"]').val(data.pic_vendor);
                })
            });

            $('#saveBtnTransport').click(function(e) {
                e.preventDefault();
                $(this).html('Sending..');

                // Validate all items
                var isValid = true;
                $('#transportItemsContainer .item-row').each(function() {
                    var date = $(this).find('.item-date').val();
                    var category = $(this).find('.item-category').val();
                    var vehicle = $(this).find('.item-vehicle').val();
                    var volume = $(this).find('.item-volume').val();
                    var pic = $(this).find('.item-pic').val();

                    if (!date || !category || !vehicle || !volume || !pic) {
                        isValid = false;
                        return false;
                    }
                });

                if (!isValid) {
                    alert('Mohon lengkapi semua data item!');
                    $('#saveBtnTransport').html('Simpan Semua Item');
                    return;
                }

                // Submit each item individually
                var totalItems = $('#transportItemsContainer .item-row').length;
                var submittedItems = 0;
                var hasError = false;

                $('#transportItemsContainer .item-row').each(function(index) {
                    var itemData = {
                        date: $(this).find('.item-date').val(),
                        waste_category_id: $(this).find('.item-category').val(),
                        vehicle_id: $(this).find('.item-vehicle').val(),
                        volume: $(this).find('.item-volume').val(),
                        pic_vendor: $(this).find('.item-pic').val()
                    };

                    $.ajax({
                        data: itemData,
                        url: "{{ route('waste-transports.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function(data) {
                            submittedItems++;
                            if (submittedItems === totalItems) {
                                transportTable.draw();
                                $('#saveBtnTransport').html('Simpan Semua Item');
                                $('#transportForm').trigger("reset");
                                $('#ajaxTransportModal').modal('hide');
                                alert('Semua data berhasil disimpan!');
                            }
                        },
                        error: function(data) {
                            hasError = true;
                            console.log('Error:', data);
                            $('#saveBtnTransport').html('Simpan Semua Item');
                            alert('Terjadi kesalahan saat menyimpan data!');
                        }
                    });
                });
            });

            $('body').on('click', '.deleteTransport', function() {
                var id = $(this).data("id");
                if (confirm("Are You sure want to delete !")) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ url('api/waste-transports') }}" + '/' + id,
                        success: function(data) {
                            transportTable.draw();
                        },
                        error: function(data) {
                            console.log('Error:', data);
                        }
                    });
                }
            });
        });
    </script>
@endsection
