<div id="panel-form-order" class="panel">
    <div class="panel-hdr">
        <h2>
            {{ isset($order_obat) ? 'Edit' : 'Tambah' }} Pemakaian Obat/Alkes
            <span class="fw-300"><i>Pasien: {{ $registration->patient->name }}</i></span>
        </h2>
    </div>
    <form id="order-form" autocomplete="off" {{ isset($order_obat) ? 'data-order-id=' . $order_obat->id : '' }}>
        @csrf
        @if (isset($order_obat))
            @method('PUT')
        @endif
        <div class="panel-container show">
            <div class="panel-content">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="warehouse_id">Gudang <span
                                    class="text-danger">*</span></label>
                            <select class="form-control select2" id="warehouse_id" name="warehouse_id" required>
                                <option value="">Pilih Gudang...</option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}"
                                        {{ isset($order_obat) && $order_obat->warehouse_id == $warehouse->id ? 'selected' : '' }}>
                                        {{ $warehouse->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="doctor_id">Dokter</label>
                            <select class="form-control select2" id="doctor_id" name="doctor_id">
                                <option value="">Pilih Dokter...</option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}"
                                        {{ isset($order_obat) && $order_obat->doctor_id == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="search_obat">Cari Obat/Alkes</label>
                            <select class="form-control" id="search_obat" name="search_obat"></select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">No. Order</label>
                            <input type="text" class="form-control" value="{{ $order_obat->no_order ?? 'Otomatis' }}"
                                readonly>
                        </div>
                    </div>
                </div>
                <hr>
                <h5 class="frame-heading">Item Order</h5>
                <div class="table-responsive">
                    <table id="item-table" class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th style="width: 35%;">Nama Item</th>
                                <th>Harga</th>
                                <th style="width: 15%;">Jumlah</th>
                                <th>Subtotal</th>
                                <th style="width: 5%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-right">Total</th>
                                <th id="grand-total" class="text-right">Rp 0</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div
                class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 d-flex flex-row align-items-center">
                <button type="button" class="btn btn-secondary" id="btn-cancel-order">Batal</button>
                <button class="btn btn-primary ml-auto" type="submit">Simpan Order</button>
            </div>
        </div>
    </form>
</div>

<script>
    (function() {
        $('#panel-form-order .select2').select2({
            width: '100%',
            dropdownParent: $('#panel-form-order')
        });
        $('#search_obat').select2({
            dropdownParent: $('#panel-form-order'),
            placeholder: 'Ketik nama obat...',
            minimumInputLength: 2,
            ajax: {
                url: '{{ route('api.search-obat') }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        term: params.term,
                        warehouse_id: $('#warehouse_id').val()
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

        let initialItems = {!! isset($order_obat)
            ? json_encode(
                $order_obat->details->map(function ($detail) {
                    return [
                        'id' => $detail->obat_id,
                        'name' => $detail->obat->name,
                        'price' => $detail->price,
                        'quantity' => $detail->quantity,
                    ];
                }),
            )
            : '[]' !!};

        function formatRupiah(angka) {
            if (angka === null || isNaN(angka)) return 'Rp 0';
            var number_string = angka.toString().replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return 'Rp ' + rupiah;
        }

        function calculateTotal() {
            var total = 0;
            $('#item-table tbody tr').each(function() {
                var qty = parseFloat($(this).find('.quantity-input').val()) || 0;
                var price = parseFloat($(this).data('price')) || 0;
                var subtotal = qty * price;
                $(this).find('.subtotal').text(formatRupiah(subtotal));
                total += subtotal;
            });
            $('#grand-total').text(formatRupiah(total));
        }

        function isItemExist(id) {
            let exist = false;
            $('#item-table tbody tr').each(function() {
                if ($(this).data('id') == id) {
                    exist = true;
                }
            });
            return exist;
        }

        function addItemToTable(item) {
            if (isItemExist(item.id)) {
                Swal.fire('Info', 'Item sudah ada di dalam daftar.', 'info');
                return;
            }
            var subtotal = (item.quantity || 1) * item.price;
            var row = `
                <tr data-id="${item.id}" data-price="${item.price}">
                    <td>${item.name}</td>
                    <td class="text-right">${formatRupiah(item.price)}</td>
                    <td><input type="number" class="form-control form-control-sm quantity-input" value="${item.quantity || 1}" min="1"></td>
                    <td class="subtotal text-right">${formatRupiah(subtotal)}</td>
                    <td><button type="button" class="btn btn-danger btn-xs btn-icon waves-effect waves-themed btn-remove-item"><i class="fal fa-times"></i></button></td>
                </tr>`;
            $('#item-table tbody').append(row);
            calculateTotal();
        }

        function renderInitialItems() {
            initialItems.forEach(item => addItemToTable(item));
        }

        renderInitialItems();

        $('#search_obat').on('select2:select', function(e) {
            var data = e.params.data;
            addItemToTable({
                id: data.id,
                name: data.text,
                price: data.price,
                quantity: 1
            });
            $(this).val(null).trigger('change');
        });

        $('#item-table').on('input', '.quantity-input', calculateTotal);

        $('#item-table').on('click', '.btn-remove-item', function() {
            $(this).closest('tr').remove();
            calculateTotal();
        });
    })();
</script>
