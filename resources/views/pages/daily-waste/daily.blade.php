@extends('inc.layout')
@section('title', 'Pengelola Limbah')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="container-fluid py-4">
            <div class="row mb-3">
                <div class="col-12">
                    <div id="panel-filter" class="panel">
                        <div class="panel-hdr">
                            <h2>Filter Pengelola Limbah</h2>
                        </div>
                        <div class="panel-container show">
                            <div class="panel-content">
                                <!-- === PERUBAHAN UI FILTER DIMULAI DI SINI === -->
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
                                <!-- === PERUBAHAN UI FILTER SELESAI === -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12">
                    <div id="panel-data" class="panel">
                        <div class="panel-hdr">
                            <h2>Pengelola Limbah</h2>
                        </div>
                        <div class="panel-container show">
                            <div class="panel-content">
                                <div class="row mb-3">
                                    <div class="col">
                                        <button type="button" class="btn btn-success mb-3" id="createNewDaily">Tambah Data
                                            Harian</button>

                                        <table class="table table-bordered daily-datatable">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Tanggal</th>
                                                    <th>Kategori</th>
                                                    <th>Volume (Kg)</th>
                                                    <th>PIC (CS)</th>
                                                    <th width="150px">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="3" style="text-align: right; font-weight: bold;">Total
                                                        Volume (Filtered):
                                                    </th>
                                                    <th id="totalVolumeFiltered" style="font-weight: bold;">0.00 Kg
                                                    </th>
                                                    <th colspan="2"></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Daily Modal -->
                <div class="modal fade" id="ajaxDailyModal" tabindex="-1" aria-labelledby="dailyModalTitleLabel"
                    aria-hidden="true">
                    {{-- Konten Modal tidak berubah --}}
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="dailyModalTitle"></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="dailyForm" name="dailyForm">
                                    <div id="dailyItemsContainer"></div>
                                    <div class="text-center my-3" id="addItemWrapper">
                                        <button type="button" class="btn btn-success btn-sm" id="addDailyItem">
                                            <i class="fas fa-plus"></i> Tambah Item
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                <button type="button" class="btn btn-primary" id="saveBtnDaily">Simpan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection


@section('plugin')
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>

    <template id="dailyItemTemplate">
        <div class="item-row mb-3 border-bottom pb-3">
            <input type="hidden" class="item-id" name="id">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Tanggal</label>
                    <input type="text" class="form-control item-date datepicker" name="date" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kategori</label>
                    <select class="form-control item-category-select" name="waste_category_id"
                        data-placeholder="Pilih Kategori" style="width: 100%" required>
                        <option value="">Pilih Kategori</option>
                        @foreach ($wasteCategories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Volume</label>
                    <input type="number" step="0.01" class="form-control item-volume" name="volume" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">PIC (CS)</label>
                    <select class="form-control item-pic-select" data-placeholder="Pilih PIC"
                        style="width: 100%"></select>
                    <input type="hidden" class="item-pic-id" name="pic" required>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-sm remove-item"><i
                            class="fas fa-trash"></i></button>
                </div>
            </div>
        </div>
    </template>

    <script type="text/javascript">
        $(function() {
            // --- Logika Utama Aplikasi ---

            // Setup CSRF Token untuk semua request AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Inisialisasi datepicker untuk filter
            $('#start_date, #end_date').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                orientation: "bottom auto"
            });

            // Inisialisasi DataTable
            var dailyTable = $('.daily-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('daily-inputs.index') }}",
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
                        name: 'volume'
                    },
                    {
                        data: 'pic_name',
                        name: 'employee.fullname',
                        defaultContent: 'N/A'
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
                "footerCallback": function(row, data, start, end, display) {
                    var api = this.api();
                    var total = api.column(3, {
                        page: 'current'
                    }).data().reduce((a, b) => parseFloat(a) + parseFloat(b), 0);
                    $(api.column(3).footer()).html(total.toFixed(2) + ' Kg');
                }
            });

            const sanitationOrgId = {{ $sanitationOrgId ?? 'null' }};

            // Event handler untuk tombol filter
            $('#filterBtn').click(() => dailyTable.draw());
            $('#resetBtn').click(function() {
                $('#start_date').val('');
                $('#end_date').val('');
                dailyTable.draw();
            });

            /**
             * Menginisialisasi semua field Select2 di dalam sebuah baris item.
             * @param {jQuery} rowElement - Elemen jQuery dari baris (.item-row).
             * @param {Object} data - Data untuk baris tersebut (untuk mode edit).
             */
            function initializeRowSelect2(rowElement, data = {}) {
                // Inisialisasi Select2 untuk Kategori dan set nilainya
                rowElement.find('.item-category-select').select2({
                    placeholder: 'Pilih Kategori',
                    width: '100%',
                    dropdownParent: $('#ajaxDailyModal')
                }).val(data.category_id || '').trigger('change');

                // Inisialisasi Select2 untuk PIC
                const picSelect = rowElement.find('.item-pic-select');
                const picHiddenInput = rowElement.find('.item-pic-id');

                picSelect.select2({
                    placeholder: 'Pilih PIC',
                    width: '100%',
                    dropdownParent: $('#ajaxDailyModal'),
                    ajax: {
                        url: "{{ route('getEmployeesByOrganization') }}",
                        dataType: 'json',
                        delay: 250,
                        data: params => ({
                            organization_id: sanitationOrgId,
                            search: params.term
                        }),
                        processResults: function(data) {
                            var results = [];
                            if (data && data.data) {
                                Object.keys(data.data).forEach(key => results.push({
                                    id: key,
                                    text: data.data[key]
                                }));
                            }
                            return {
                                results: results
                            };
                        },
                        cache: true
                    }
                });

                // Pre-populate Select2 PIC jika dalam mode edit
                if (data.pic_id && data.pic_name) {
                    const selectedOption = new Option(data.pic_name, data.pic_id, true, true);
                    picSelect.append(selectedOption).trigger('change');
                    picHiddenInput.val(data.pic_id);
                }

                picSelect.on('select2:select', e => picHiddenInput.val(e.params.data.id));
            }

            /**
             * Inisialisasi datepicker pada input tanggal di dalam modal.
             * @param {jQuery} rowElement - Elemen jQuery dari baris (.item-row).
             */
            function initializeRowDatepicker(rowElement) {
                rowElement.find('.item-date').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true,
                    todayHighlight: true,
                    orientation: "bottom auto"
                });
            }

            /**
             * Membuat baris item baru di dalam modal.
             * @param {Object} data - Data untuk diisi ke dalam baris (opsional, untuk mode edit).
             */
            function createItemRow(data = {}) {
                const template = document.getElementById('dailyItemTemplate').content.cloneNode(true);
                const newItemRow = $(template.querySelector('.item-row'));

                newItemRow.find('.item-id').val(data.id || '');
                newItemRow.find('.item-date').val(data.date || new Date().toISOString().slice(0, 10));
                newItemRow.find('.item-volume').val(data.volume || '');

                $('#dailyItemsContainer').append(newItemRow);
                initializeRowSelect2(newItemRow, data);
                initializeRowDatepicker(newItemRow);
                updateRemoveButtons();
            }

            /**
             * Mengatur visibilitas tombol hapus item di modal.
             */
            function updateRemoveButtons() {
                const rows = $('#dailyItemsContainer .item-row');
                rows.find('.remove-item').toggle(rows.length > 1);
            }

            // --- Event Handlers untuk Aksi CRUD ---

            // Buka modal untuk membuat data baru
            $('#createNewDaily').click(function() {
                $('#dailyForm').trigger("reset");
                $('#dailyItemsContainer').empty();
                $('#dailyModalTitle').html("Tambah Input Harian");
                $('#addItemWrapper').show();
                createItemRow();
                $('#ajaxDailyModal').modal('show');
            });

            // Buka modal untuk mengedit data
            $('body').on('click', '.editDaily', function() {
                const data = $(this).data();
                $('#dailyForm').trigger("reset");
                $('#dailyItemsContainer').empty();
                $('#dailyModalTitle').html("Edit Input Harian");
                $('#addItemWrapper').hide();
                createItemRow({
                    id: data.id,
                    date: data.date,
                    category_id: data.categoryId,
                    volume: data.volume,
                    pic_id: data.picId,
                    pic_name: data.picName
                });
                $('#ajaxDailyModal').modal('show');
            });

            // Tambah baris item di dalam modal
            $('#addDailyItem').click(() => createItemRow());

            // Hapus baris item di dalam modal
            $('#dailyItemsContainer').on('click', '.remove-item', function() {
                $(this).closest('.item-row').remove();
                updateRemoveButtons();
            });

            // Simpan data (Create/Update)
            $('#saveBtnDaily').click(function(e) {
                e.preventDefault();
                $(this).html('Menyimpan...').prop('disabled', true);
                let itemsPayload = [];
                let isValid = true;
                $('#dailyItemsContainer .item-row').each(function() {
                    const row = $(this);
                    const itemData = {
                        id: row.find('.item-id').val() || null,
                        date: row.find('.item-date').val(),
                        waste_category_id: row.find('.item-category-select').val(),
                        volume: row.find('.item-volume').val(),
                        pic: row.find('.item-pic-id').val(),
                    };
                    if (!itemData.date || !itemData.waste_category_id || !itemData.volume || !
                        itemData.pic) {
                        isValid = false;
                        return false;
                    }
                    itemsPayload.push(itemData);
                });

                if (!isValid) {
                    showErrorAlertNoRefresh('Mohon lengkapi semua data pada setiap item!');
                    $('#saveBtnDaily').html('Simpan').prop('disabled', false);
                    return;
                }

                $.ajax({
                    data: JSON.stringify({
                        items: itemsPayload
                    }),
                    url: "{{ route('daily-inputs.store') }}",
                    type: "POST",
                    contentType: 'application/json',
                    dataType: 'json',
                    success: function(data) {
                        $('#ajaxDailyModal').modal('hide');
                        showSuccessAlert(data.success || 'Data berhasil disimpan!');
                        dailyTable.draw(false);
                    },
                    error: function(xhr) {
                        let errorMsg = "Terjadi kesalahan.";
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMsg = "Validasi Gagal:\n";
                            $.each(xhr.responseJSON.errors, (key, value) => {
                                errorMsg += `- ${value[0]}\n`;
                            });
                        }
                        showErrorAlertNoRefresh(errorMsg);
                    },
                    complete: function() {
                        $('#saveBtnDaily').html('Simpan').prop('disabled', false);
                    }
                });
            });

            // Hapus data
            $('body').on('click', '.deleteDaily', function() {
                const id = $(this).data("id");

                // Tampilkan dialog konfirmasi sebelum menghapus
                showDeleteConfirmation(function() {
                    // Kode ini hanya berjalan jika user mengklik "Ya, Hapus!"
                    $.ajax({
                        type: "DELETE",
                        url: `{{ url('api/daily-inputs') }}/${id}`,
                        success: function(data) {
                            showSuccessAlert(data.success || 'Data berhasil dihapus.');
                            dailyTable.draw(false);
                        },
                        error: function(xhr) {
                            showErrorAlertNoRefresh(
                                'Gagal menghapus data. Silakan coba lagi.');
                            console.log('Error:', xhr);
                        }
                    });
                });
            });

        });
    </script>
@endsection
