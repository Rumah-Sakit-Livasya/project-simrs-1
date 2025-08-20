@extends('inc.layout')
@section('title', 'Input Linen Harian')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="container-fluid py-4">

            <div class="row mb-3">
                <div class="col-12">
                    <div id="panel-filter" class="panel">
                        <div class="panel-hdr">
                            <h2>Filter Input Linen</h2>
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

            <div class="panel">
                <div class="panel-hdr">
                    <h2>Input Harian Laundry</h2>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        <button type="button" class="btn btn-success mb-3" id="createNewLinen">
                            <i class="fas fa-plus mr-2"></i>Tambah Input Linen
                        </button>
                        <table class="table table-bordered table-hover linen-datatable w-100">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Jenis Linen</th>
                                    <th>Kategori</th>
                                    <th class="text-right">Volume (Kg)</th>
                                    <th>PIC (Kesling)</th>
                                    <th width="100px">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" style="text-align: right; font-weight: bold;">
                                        Total Volume (Halaman Ini):
                                    </th>
                                    <th id="totalVolumeLinen" style="font-weight: bold; text-align: right;">
                                        0.00 Kg
                                    </th>
                                    <th colspan="2"></th> <!-- Kolom sisa -->
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="ajaxLinenModal" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="linenModalTitle"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="linenForm" name="linenForm">
                            <div id="linenItemsContainer"></div>
                            <div class="text-center my-3" id="addItemWrapper">
                                <button type="button" class="btn btn-success btn-sm" id="addLinenItem">
                                    <i class="fas fa-plus"></i> Tambah Item
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" id="saveBtnLinen">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    <!-- Plugin Dependencies -->
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>

    <!-- Template untuk baris item dinamis -->
    <template id="linenItemTemplate">
        <div class="item-row mb-4 border-bottom pb-3">
            <input type="hidden" class="item-id">
            <div class="row align-items-end">
                <div class="col-md-2 mb-2">
                    <label class="form-label">Tanggal</label>
                    <input type="text" class="form-control item-date datepicker" required>
                </div>
                <div class="col-md-2 mb-2">
                    <label class="form-label">Jenis Linen</label>
                    <select class="form-control item-type-select" style="width: 100%" required>
                        <option></option>
                        @foreach ($linenTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <label class="form-label">Kategori</label>
                    <select class="form-control item-category-select" style="width: 100%" required>
                        <option></option>
                        @foreach ($linenCategories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <label class="form-label">Volume (Kg)</label>
                    <input type="number" step="0.01" class="form-control item-volume" placeholder="0.00" required>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="form-label">PIC (Kesling)</label>
                    <select class="form-control item-pic-select" style="width: 100%" required></select>
                    <input type="hidden" class="item-pic-id">
                </div>
                <div class="col-md-1 mb-2 text-right">
                    <button type="button" class="btn btn-danger btn-sm remove-item" title="Hapus"><i
                            class="fas fa-trash"></i></button>
                </div>
            </div>
        </div>
    </template>

    <script type="text/javascript">
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Inisialisasi datepicker untuk filter
            $('#start_date, #end_date').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true
            });

            const table = $('.linen-datatable').DataTable({
                processing: true,
                serverSide: true,
                // === PERBARUI BAGIAN AJAX DI SINI ===
                ajax: {
                    url: "{{ route('daily-linens.index') }}",
                    data: function(d) {
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                    }
                },
                // Kolom dan konfigurasi lainnya tetap sama
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
                        data: 'linen_type.name',
                        name: 'linenType.name'
                    },
                    {
                        data: 'linen_category.name',
                        name: 'linenCategory.name'
                    },
                    {
                        data: 'volume',
                        name: 'volume',
                        className: 'text-right'
                    },
                    {
                        data: 'pic_employee.fullname',
                        name: 'picEmployee.fullname',
                        defaultContent: 'N/A'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                "footerCallback": function(row, data, start, end, display) {
                    var api = this.api();

                    // Hitung total volume dari data yang ditampilkan di halaman saat ini
                    var pageTotal = api
                        .column(4, {
                            page: 'current'
                        }) // Kolom volume ada di indeks 4
                        .data()
                        .reduce(function(a, b) {
                            // Pastikan nilai adalah angka sebelum menjumlahkan
                            return parseFloat(a) + (parseFloat(b) || 0);
                        }, 0);

                    // Update konten dari elemen <th> dengan id="totalVolumeLinen"
                    $('#totalVolumeLinen').html(pageTotal.toFixed(2) + ' Kg');
                }
            });

            // === TAMBAHKAN EVENT HANDLER UNTUK TOMBOL FILTER DI SINI ===
            $('#filterBtn').click(function() {
                table.draw();
            });

            $('#resetBtn').click(function() {
                $('#start_date').val('');
                $('#end_date').val('');
                table.draw();
            });

            const keslingOrgId = {{ $keslingOrgId ?? 'null' }};
            if (!keslingOrgId) console.error("ID Organisasi Kesling belum diatur!");

            function initializePlugins(row) {
                row.find('.datepicker').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true,
                    todayHighlight: true
                });
                row.find('.item-type-select').select2({
                    placeholder: 'Pilih Jenis',
                    dropdownParent: $('#ajaxLinenModal')
                });
                row.find('.item-category-select').select2({
                    placeholder: 'Pilih Kategori',
                    dropdownParent: $('#ajaxLinenModal')
                });

                const picSelect = row.find('.item-pic-select');
                const picHidden = row.find('.item-pic-id');
                picSelect.select2({
                    placeholder: 'Cari Nama PIC',
                    dropdownParent: $('#ajaxLinenModal'),
                    ajax: {
                        url: "{{ route('getEmployeesByOrganization') }}", // Asumsi route ini ada
                        dataType: 'json',
                        delay: 250,
                        data: params => ({
                            organization_id: keslingOrgId,
                            search: params.term
                        }),
                        processResults: function(data) {
                            return {
                                results: $.map(data.data, (value, key) => ({
                                    id: key,
                                    text: value
                                }))
                            };
                        }
                    }
                }).on('select2:select', e => picHidden.val(e.params.data.id));
            }

            function updateRemoveButtons() {
                const rows = $('#linenItemsContainer .item-row');
                rows.find('.remove-item').toggle(rows.length > 1);
            }

            function createItemRow(data = {}) {
                const template = $('#linenItemTemplate').html();
                const newItemRow = $(template);

                newItemRow.find('.item-id').val(data.id || '');
                newItemRow.find('.item-date').val(data.date || new Date().toISOString().slice(0, 10));
                newItemRow.find('.item-volume').val(data.volume || '');

                $('#linenItemsContainer').append(newItemRow);
                initializePlugins(newItemRow);

                if (data.linen_type_id) newItemRow.find('.item-type-select').val(data.linen_type_id).trigger(
                    'change');
                if (data.linen_category_id) newItemRow.find('.item-category-select').val(data.linen_category_id)
                    .trigger('change');

                if (data.pic_id && data.pic_employee) {
                    const picOption = new Option(data.pic_employee.fullname, data.pic_id, true, true);
                    newItemRow.find('.item-pic-select').append(picOption).trigger('change');
                    newItemRow.find('.item-pic-id').val(data.pic_id);
                }
                updateRemoveButtons();
            }

            $('#createNewLinen').click(() => {
                $('#linenForm')[0].reset();
                $('#linenItemsContainer').empty();
                $('#linenModalTitle').text("Tambah Input Linen Harian");
                $('#addItemWrapper').show();
                createItemRow();
                $('#ajaxLinenModal').modal('show');
            });

            $('body').on('click', '.editLinen', function() {
                const id = $(this).data('id');
                $.get(`{{ url('daily-linens') }}/${id}/edit`, function(data) {
                    $('#linenItemsContainer').empty();
                    $('#linenModalTitle').text("Edit Input Linen");
                    $('#addItemWrapper').hide();
                    createItemRow(data);
                    $('#ajaxLinenModal').modal('show');
                });
            });

            $('#addLinenItem').click(() => createItemRow());
            $('#linenItemsContainer').on('click', '.remove-item', function() {
                $(this).closest('.item-row').remove();
                updateRemoveButtons();
            });

            $('#saveBtnLinen').click(function() {
                $(this).html('Menyimpan...').prop('disabled', true);
                let items = [];
                let isValid = true;
                $('#linenItemsContainer .item-row').each(function() {
                    const row = $(this);
                    const item = {
                        id: row.find('.item-id').val() || null,
                        date: row.find('.item-date').val(),
                        linen_type_id: row.find('.item-type-select').val(),
                        linen_category_id: row.find('.item-category-select').val(),
                        volume: row.find('.item-volume').val(),
                        pic_id: row.find('.item-pic-id').val(),
                    };
                    if (!item.date || !item.linen_type_id || !item.linen_category_id || !item
                        .volume || !item.pic_id) {
                        isValid = false;
                        return false;
                    }
                    items.push(item);
                });

                if (!isValid) {
                    alert('Mohon lengkapi semua data pada setiap item.');
                    $('#saveBtnLinen').html('Simpan').prop('disabled', false);
                    return;
                }

                $.ajax({
                    data: JSON.stringify({
                        items: items
                    }),
                    url: "{{ route('daily-linens.storeOrUpdateBatch') }}",
                    type: "POST",
                    contentType: 'application/json',
                    success: (data) => {
                        $('#ajaxLinenModal').modal('hide');
                        table.draw(false);
                        alert(data.success);
                    },
                    error: (xhr) => {
                        alert('Terjadi kesalahan. Periksa konsol.');
                        console.log('Error:', xhr);
                    },
                    complete: () => $('#saveBtnLinen').html('Simpan').prop('disabled', false)
                });
            });

            $('body').on('click', '.deleteLinen', function() {
                const id = $(this).data("id");
                if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                    $.ajax({
                        type: "DELETE",
                        url: `{{ url('daily-linens') }}/${id}`,
                        success: (data) => {
                            table.draw(false);
                            alert(data.success);
                        },
                        error: (xhr) => {
                            alert('Gagal menghapus data.');
                            console.log('Error:', xhr);
                        }
                    });
                }
            });
        });
    </script>
@endsection
