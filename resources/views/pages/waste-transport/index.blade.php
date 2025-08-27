@extends('inc.layout')
@section('title', 'Checklist Harian - Transport')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="container-fluid py-4">
            <div class="row mb-3">
                <div class="col-12">
                    <div id="panel-filter" class="panel">
                        <div class="panel-hdr">
                            <h2>Filter Transport</h2>
                        </div>
                        <div class="panel-container show">
                            <div class="panel-content">
                                <div class="row p-3">
                                    {{-- Kolom untuk Tanggal Mulai --}}
                                    <div class="col-md-5 mb-2">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Tanggal Mulai</span>
                                            </div>
                                            <input type="text" id="start_date" name="start_date"
                                                class="form-control datepicker" placeholder="Pilih tanggal...">
                                        </div>
                                    </div>

                                    {{-- Kolom untuk Tanggal Selesai --}}
                                    <div class="col-md-5 mb-2">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Tanggal Selesai</span>
                                            </div>
                                            <input type="text" id="end_date" name="end_date"
                                                class="form-control datepicker" placeholder="Pilih tanggal...">
                                        </div>
                                    </div>

                                    {{-- Kolom untuk Tombol Aksi --}}
                                    <div class="col-md-2 d-flex align-items-center">
                                        <button type="button" class="btn btn-primary" id="filterBtn">Filter</button>
                                        <button type="button" class="btn btn-secondary ml-2" id="resetBtn">Reset</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel Utama untuk Data Pengangkutan -->
            <div id="panel-data" class="panel">
                <div class="panel-hdr">
                    <h2>Data Pengangkutan Limbah</h2>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        <button type="button" class="btn btn-success mb-3" id="createNewTransport">
                            <i class="fas fa-plus mr-2"></i>Tambah Data Pengangkutan
                        </button>
                        <table class="table table-bordered table-hover transport-datatable w-100">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Kategori</th>
                                    <th>Volume (Kg)</th>
                                    <th>PIC (Vendor)</th>
                                    <th>No. Kendaraan</th>
                                    <th width="100px">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" style="text-align: right; font-weight: bold;">
                                        Total Volume:
                                    </th>
                                    <th id="totalVolume" style="font-weight: bold; text-align: right;">
                                        0.00 Kg
                                    </th>
                                    <th colspan="3"></th> <!-- Kolom sisa untuk menyamakan jumlah kolom -->
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal untuk Tambah/Edit Data -->
        <div class="modal fade" id="ajaxTransportModal" tabindex="-1" aria-labelledby="transportModalTitleLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="transportModalTitle"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="transportForm" name="transportForm">
                            <!-- Container untuk baris-baris item dinamis -->
                            <div id="transportItemsContainer"></div>

                            <!-- Wrapper untuk tombol Tambah Item, bisa disembunyikan saat mode edit -->
                            <div class="text-center my-3" id="addItemWrapper">
                                <button type="button" class="btn btn-success btn-sm" id="addTransportItem">
                                    <i class="fas fa-plus"></i> Tambah Item
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" id="saveBtnTransport">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    <!-- === PERUBAHAN UTAMA ADA DI BAGIAN SCRIPT DI BAWAH INI === -->

    <!-- Plugin Dependencies -->
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>

    <!-- Template untuk baris item dinamis, lebih bersih daripada kloning elemen -->
    <template id="transportItemTemplate">
        <div class="item-row mb-4 border-bottom pb-3">
            <input type="hidden" class="item-id" name="id">

            <!-- Menggunakan Bootstrap Grid untuk layout yang rapi -->
            <div class="row align-items-end">

                <!-- Kolom Tanggal -->
                <div class="col-md-2 mb-2">
                    <label class="form-label">Tanggal</label>
                    <div class="input-group">
                        <input type="text" class="form-control item-date datepicker" name="date"
                            placeholder="YYYY-MM-DD" required>
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fal fa-calendar-alt"></i></span>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kategori -->
                <div class="col-md-3 mb-2">
                    <label class="form-label">Kategori</label>
                    <select class="form-control item-category-select" name="waste_category_id" style="width: 100%"
                        required>
                        <option></option> <!-- Placeholder untuk Select2 -->
                        @foreach ($transportCategories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Kolom No. Kendaraan -->
                <div class="col-md-2 mb-2">
                    <label class="form-label">No. Kendaraan</label>
                    <select class="form-control item-vehicle-select" name="vehicle_id" style="width: 100%" required>
                        <option></option> <!-- Placeholder untuk Select2 -->
                        @foreach ($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}">{{ $vehicle->plate_number }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Kolom Volume -->
                <div class="col-md-2 mb-2">
                    <label class="form-label">Volume (Kg)</label>
                    <input type="number" step="0.01" class="form-control item-volume" name="volume"
                        placeholder="0.00" required>
                </div>

                <!-- Kolom PIC (Vendor) -->
                <div class="col-md-2 mb-2">
                    <label class="form-label">PIC (Vendor)</label>
                    <input type="text" class="form-control item-pic" name="pic" placeholder="Nama PIC..."
                        required>
                </div>

                <!-- Kolom Tombol Hapus -->
                <div class="col-md-1 mb-2 text-right">
                    <button type="button" class="btn btn-danger btn-sm remove-item" title="Hapus Item Ini">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </template>

    <script type="text/javascript">
        $(function() {
            // Setup CSRF Token untuk semua request AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#start_date, #end_date').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                orientation: "bottom auto"
            });


            // Inisialisasi DataTable
            var transportTable = $('.transport-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('waste-transports.index') }}",
                    // ===========================================================
                    // === PENAMBAHAN 2: Mengirim Data Filter ke Server ===
                    // ===========================================================
                    data: function(d) {
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                    }
                },
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
                        name: 'volume',
                        className: 'text-right'
                    }, // Rata kanan untuk angka
                    {
                        data: 'pic',
                        name: 'pic'
                    },
                    {
                        data: 'vehicle.plate_number',
                        name: 'vehicle.plate_number'
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
                ],
                // ===============================================================
                // === PENAMBAHAN 3: Menghitung dan Menampilkan Total Volume ===
                // ===============================================================
                "footerCallback": function(row, data, start, end, display) {
                    var api = this.api();

                    // Menghitung total volume untuk data pada halaman saat ini
                    var pageTotal = api
                        .column(3, {
                            page: 'current'
                        })
                        .data()
                        .reduce(function(a, b) {
                            return parseFloat(a) + parseFloat(b);
                        }, 0);

                    // Menambahkan baris footer jika belum ada
                    if ($(api.table().footer()).find('th').length === 0) {
                        $(api.table().footer()).html(
                            '<tr>' +
                            '<th colspan="3" style="text-align:right; font-weight: bold;">Total Volume di Halaman Ini:</th>' +
                            '<th style="font-weight: bold; text-align:right;"></th>' +
                            '<th colspan="3"></th>' +
                            '</tr>'
                        );
                    }

                    // Memperbarui nilai total di footer
                    $(api.column(3).footer()).html(pageTotal.toFixed(2) + ' Kg');
                }

            });

            $('#filterBtn').click(function() {
                transportTable.draw(); // Memuat ulang data tabel dengan parameter filter baru
            });

            $('#resetBtn').click(function() {
                $('#start_date').val('');
                $('#end_date').val('');
                transportTable.draw(); // Memuat ulang data tabel tanpa filter
            });

            /**
             * Menginisialisasi plugin (Select2, Datepicker) pada sebuah baris item.
             * @param {jQuery} rowElement - Elemen jQuery dari baris (.item-row).
             */
            function initializePlugins(rowElement) {
                rowElement.find('.datepicker').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true,
                    todayHighlight: true,
                    orientation: "bottom auto"
                });

                rowElement.find('.item-category-select, .item-vehicle-select').select2({
                    dropdownParent: $('#ajaxTransportModal') // Agar dropdown muncul di atas modal
                });
            }

            /**
             * Mengatur visibilitas tombol hapus item di modal.
             */
            function updateRemoveButtons() {
                const rows = $('#transportItemsContainer .item-row');
                rows.find('.remove-item').toggle(rows.length > 1);
            }

            /**
             * Membuat baris item baru di dalam modal.
             * @param {Object} data - Data untuk diisi ke dalam baris (opsional, untuk mode edit).
             */
            function createItemRow(data = {}) {
                const template = document.getElementById('transportItemTemplate').content.cloneNode(true);
                const newItemRow = $(template.querySelector('.item-row'));

                // Mengisi data jika ada (untuk mode edit)
                newItemRow.find('.item-id').val(data.id || '');
                newItemRow.find('.item-date').val(data.date || new Date().toISOString().slice(0, 10));
                newItemRow.find('.item-category-select').val(data.waste_category_id || '');
                newItemRow.find('.item-vehicle-select').val(data.vehicle_id || '');
                newItemRow.find('.item-volume').val(data.volume || '');
                newItemRow.find('.item-pic').val(data.pic || '');

                $('#transportItemsContainer').append(newItemRow);
                initializePlugins(newItemRow);
                updateRemoveButtons();
            }

            // --- Event Handlers untuk Aksi CRUD ---

            // Buka modal untuk membuat data baru (mode multi-item)
            $('#createNewTransport').click(function() {
                $('#transportForm').trigger("reset");
                $('#transportItemsContainer').empty();
                $('#transportModalTitle').html("Tambah Input Pengangkutan");
                $('#addItemWrapper').show(); // Tampilkan tombol 'Tambah Item'
                createItemRow(); // Buat satu baris kosong
                $('#ajaxTransportModal').modal('show');
            });

            // Buka modal untuk mengedit data (mode single-item)
            $('body').on('click', '.editTransport', function() {
                const data = $(this).data();
                $('#transportForm').trigger("reset");
                $('#transportItemsContainer').empty();
                $('#transportModalTitle').html("Edit Input Pengangkutan");
                $('#addItemWrapper').hide(); // Sembunyikan tombol 'Tambah Item' saat edit

                // Kirim request AJAX untuk mendapatkan data terbaru
                $.get("{{ url('api/waste-transports') }}/" + data.id + '/edit', function(response) {
                    createItemRow(response); // Buat satu baris dan isi dengan data
                    $('#ajaxTransportModal').modal('show');
                });
            });

            // Tambah baris item baru di dalam modal
            $('#addTransportItem').click(() => createItemRow());

            // Hapus baris item dari modal
            $('#transportItemsContainer').on('click', '.remove-item', function() {
                $(this).closest('.item-row').remove();
                updateRemoveButtons();
            });

            // Simpan data (Create/Update) menggunakan metode BATCH
            $('#saveBtnTransport').click(function(e) {
                e.preventDefault();
                $(this).html('Menyimpan...').prop('disabled', true);

                let itemsPayload = [];
                let isValid = true;

                $('#transportItemsContainer .item-row').each(function() {
                    const row = $(this);
                    const itemData = {
                        id: row.find('.item-id').val() || null,
                        date: row.find('.item-date').val(),
                        waste_category_id: row.find('.item-category-select').val(),
                        vehicle_id: row.find('.item-vehicle-select').val(),
                        volume: row.find('.item-volume').val(),
                        pic: row.find('.item-pic').val(),
                    };

                    // Validasi client-side sederhana
                    if (!itemData.date || !itemData.waste_category_id || !itemData.vehicle_id || !
                        itemData.volume || !itemData.pic) {
                        isValid = false;
                        return false; // Hentikan loop .each
                    }
                    itemsPayload.push(itemData);
                });

                if (!isValid) {
                    // Ganti alert() dengan notifikasi yang lebih baik jika ada
                    alert('Mohon lengkapi semua data pada setiap item!');
                    $('#saveBtnTransport').html('Simpan').prop('disabled', false);
                    return;
                }

                // Kirim semua item dalam satu request
                $.ajax({
                    data: JSON.stringify({
                        items: itemsPayload
                    }),
                    // Arahkan ke route yang bisa menangani batch update/create
                    url: "{{ route('waste-transports.storeOrUpdateBatch') }}",
                    type: "POST",
                    contentType: 'application/json',
                    dataType: 'json',
                    success: function(data) {
                        $('#ajaxTransportModal').modal('hide');
                        // Ganti alert() dengan notifikasi sukses (misal: SweetAlert)
                        alert(data.success || 'Data berhasil disimpan!');
                        transportTable.draw(false); // `false` agar tetap di halaman saat ini
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr);
                        // Ganti alert() dengan notifikasi error yang lebih informatif
                        alert('Terjadi kesalahan. Periksa konsol untuk detail.');
                    },
                    complete: function() {
                        $('#saveBtnTransport').html('Simpan').prop('disabled', false);
                    }
                });
            });

            // Hapus data
            $('body').on('click', '.deleteTransport', function() {
                const id = $(this).data("id");

                // Ganti confirm() dengan dialog konfirmasi yang lebih baik (misal: SweetAlert)
                if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                    $.ajax({
                        type: "DELETE",
                        url: `{{ url('api/waste-transports') }}/${id}`,
                        success: function(data) {
                            alert(data.success || 'Data berhasil dihapus.');
                            transportTable.draw(false);
                        },
                        error: function(xhr) {
                            alert('Gagal menghapus data.');
                            console.error('Error:', xhr);
                        }
                    });
                }
            });
        });
    </script>
@endsection
