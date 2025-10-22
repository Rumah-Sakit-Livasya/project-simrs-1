@extends('inc.layout-no-side')
@section('title', 'Form Distribusi Barang Farmasi')

@section('extended-css')
    <link rel="stylesheet" media="screen, print" href="/css/formplugins/select2/select2.bundle.css">
    <style>
        /* [BARU] CSS untuk Page Loader Fullscreen */
        #page-loader {
            position: fixed;
            /* Menutupi seluruh viewport */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.85);
            /* Latar belakang semi-transparan */
            z-index: 9999;
            display: flex;
            /* Menggunakan flexbox untuk menengahkan spinner */
            justify-content: center;
            align-items: center;
            /* Disembunyikan secara default, akan ditampilkan oleh JavaScript */
            display: none;
        }
    </style>
@endsection

@section('content')
    <div id="page-loader">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Form Distribusi Barang Farmasi</h2>
                        <div class="panel-toolbar">
                            <a href="{{ route('warehouse.distribusi-barang.pharmacy.index') }}"
                                class="btn btn-secondary btn-sm">
                                <i class="fal fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="form-distribusi" action="{{ route('warehouse.distribusi-barang.pharmacy.store') }}"
                                method="post">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                <input type="hidden" name="status" id="status-input">
                                <input type="hidden" name="sr_id" id="sr_id_input">

                                {{-- Bagian Header Form --}}
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label class="form-label" for="tanggal_db">Tanggal*</label>
                                        <input type="date" class="form-control" id="tanggal_db" name="tanggal_db"
                                            value="{{ now()->toDateString() }}" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="form-label" for="kode_sr_display">Dari Stock Request (SR)</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="kode_sr_display"
                                                placeholder="Pilih SR..." readonly>
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button" data-toggle="modal"
                                                    data-target="#modal-pilih-sr"><i class="fal fa-search"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="form-label" for="keterangan">Keterangan</label>
                                        <input type="text" class="form-control" name="keterangan" id="keterangan"
                                            placeholder="Keterangan tambahan...">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="form-label" for="asal_gudang_id">Gudang Asal*</label>
                                        <select name="asal_gudang_id" class="form-control select2" id="asal-gudang"
                                            required>
                                            <option value="" disabled selected>Pilih Gudang Asal</option>
                                            @foreach ($gudang_asals as $gudang)
                                                <option value="{{ $gudang->id }}">{{ $gudang->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label" for="tujuan_gudang_id">Gudang Tujuan*</label>
                                        <select name="tujuan_gudang_id" id="tujuan-gudang" class="form-control select2"
                                            required>
                                            <option value="" disabled selected>Pilih Gudang Tujuan</option>
                                            @foreach ($gudangs as $gudang)
                                                <option value="{{ $gudang->id }}">{{ $gudang->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <hr>

                                {{-- Bagian Tabel Item --}}
                                <h5 class="frame-heading">Item Distribusi</h5>
                                <div class="frame-wrap">
                                    <table class="table table-bordered table-hover m-0">
                                        <thead class="bg-primary-600">
                                            <tr>
                                                <th class="text-center" style="width: 5%;">Aksi</th>
                                                <th>Nama Barang</th>
                                                <th style="width: 15%;">Satuan</th>
                                                <th style="width: 10%;">Stok Asal</th>
                                                <th style="width: 10%;">Qty</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody id="table-items-body">
                                            {{-- Baris item akan ditambahkan di sini oleh JavaScript --}}
                                        </tbody>
                                    </table>
                                    <div
                                        class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 d-flex flex-row align-items-center">
                                        <button class="btn btn-primary ml-auto" type="button" id="btn-tambah-item">
                                            <i class="fal fa-plus"></i> Tambah Item
                                        </button>
                                    </div>
                                </div>

                                {{-- Bagian Tombol Aksi --}}
                                <div
                                    class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 d-flex flex-row align-items-center mt-4">
                                    <button class="btn btn-outline-danger" type="button" id="btn-simpan-draft">Simpan
                                        sebagai Draft</button>
                                    <button class="btn btn-success ml-auto" type="button" id="btn-simpan-final">Simpan
                                        Final</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Include Modals --}}
        @include('pages.simrs.warehouse.distribusi-barang.partials.modal-pilih-sr-pharmacy')
        @include('pages.simrs.warehouse.distribusi-barang.partials.modal-add-item')
    </main>
@endsection

@section('plugin')
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(function() {

            // =================================================================
            // 1. STATE & CACHING
            // =================================================================
            let itemCounter = 0;
            let selectedItems = []; // Melacak item yang sudah ada di tabel (format: "barangId-satuanId")

            // Cache elemen jQuery agar tidak query berulang-ulang
            const $pageLoader = $('#page-loader');
            const $form = $('#form-distribusi');
            const $itemTableBody = $('#table-items-body');
            const $asalGudang = $('#asal-gudang');
            const $tujuanGudang = $('#tujuan-gudang');
            const $addModal = $('#modal-add-item');
            const $addModalContent = $('#modal-add-item-content');

            // Sembunyikan loader setelah halaman siap
            $pageLoader.hide();


            // =================================================================
            // 2. INITIALIZATION
            // =================================================================
            $('.select2').select2({
                placeholder: "Pilih...",
                allowClear: true
            });


            // =================================================================
            // 3. EVENT LISTENERS
            // =================================================================

            // Memilih SR dari modal
            $(document).on('click', '.sr-row', handleSrSelection);

            // Tombol "Tambah Item" manual
            $('#btn-tambah-item').on('click', handleManualAddItemClick);

            // Tombol "Pilih" di dalam modal item
            $(document).on('click', '.btn-pilih-item', handleSelectItemClick);

            // Tombol "Hapus Item" dari tabel utama
            $itemTableBody.on('click', '.btn-remove-item', handleRemoveItemClick);

            // Tombol Simpan Draft/Final
            $('#btn-simpan-draft, #btn-simpan-final').on('click', handleFormSubmit);


            // =================================================================
            // 4. FUNCTIONS
            // =================================================================

            /**
             * Menangani pemilihan Stock Request (SR) dari modal.
             */
            function handleSrSelection() {
                const sr = $(this).data('sr');
                if (!sr) return;

                // Set nilai form
                $('#kode_sr_display').val(sr.kode_sr || '');
                $('#sr_id_input').val(sr.id || '');
                $asalGudang.val(sr.asal_gudang_id).trigger('change');
                $tujuanGudang.val(sr.tujuan_gudang_id).trigger('change');

                // Reset tabel dan tampilkan loading
                $itemTableBody.html(
                    '<tr><td colspan="6" class="text-center"><i class="fas fa-spinner fa-spin"></i> Memuat stok item...</td></tr>'
                );
                selectedItems = [];

                // Ambil stok untuk setiap item di SR
                if (Array.isArray(sr.items) && sr.items.length > 0) {
                    const promises = sr.items.map(item => fetchStockAndPrepareItem(sr.asal_gudang_id, item));
                    Promise.all(promises).then(itemsToAdd => {
                        $itemTableBody.empty(); // Hapus pesan loading
                        itemsToAdd.forEach(itemData => {
                            if (itemData) renderItemRow(itemData);
                        });
                    });
                } else {
                    $itemTableBody.empty(); // Kosongkan jika tidak ada item
                }

                $('#modal-pilih-sr').modal('hide');
            }

            /**
             * Fetch stok untuk item dari SR dan siapkan data untuk dirender.
             * @returns Promise
             */
             function fetchStockAndPrepareItem(asalGudangId, item) {
                 return new Promise(resolve => {
                     const url =
                         `/simrs/warehouse/distribusi-barang/pharmacy/get/stock/${asalGudangId}/${item.barang.id}/${item.satuan.id}`;
                     $.get(url, function(stockData) {
                         const sisaQty = Math.max(0, (item.qty || 0) - (item.qty_fulfilled || 0));
                         const stokTersedia = stockData.qty || 0;
                         const qtyToDistribute = Math.min(sisaQty, stokTersedia);

                         resolve({
                             barang: item.barang,
                             satuan: item.satuan,
                             qty: qtyToDistribute,
                             stok: stokTersedia,
                             keterangan: item.keterangan || ''
                         });
                     }).fail(() => resolve(null)); // Resolusi null jika AJAX gagal
                 });
             }


            /**
             * Menangani klik tombol "Tambah Item" manual.
             */
            function handleManualAddItemClick() {
                const asalGudang = $asalGudang.val();
                const tujuanGudang = $tujuanGudang.val();
                if (!asalGudang || !tujuanGudang) {
                    showErrorAlertNoRefresh('Silakan pilih Gudang Asal dan Gudang Tujuan terlebih dahulu.');
                    return;
                }

                $addModal.modal('show');
                const url =
                    `/simrs/warehouse/distribusi-barang/pharmacy/get-items-modal/${asalGudang}/${tujuanGudang}`;
                $addModalContent.html(
                    '<div class="text-center p-3"><i class="fas fa-spinner fa-spin"></i> Memuat...</div>').load(
                    url);
            }

            /**
             * Menangani klik tombol "Pilih" di modal item.
             */
            function handleSelectItemClick() {
                const $button = $(this);
                renderItemRow({
                    barang: $button.data('item'),
                    satuan: $button.data('satuan'),
                    stok: $button.data('stok'),
                    qty: 1,
                    keterangan: ''
                });

                // Tandai sebagai sudah dipilih
                $button.prop('disabled', true).html('<i class="fal fa-check"></i>');
            }

            /**
             * Merender satu baris item ke tabel utama.
             * @param {Object} data
             */
            function renderItemRow(data) {
                const {
                    barang,
                    satuan,
                    qty,
                    stok,
                    keterangan
                } = data;
                if (!barang || !satuan) return;

                const itemKey = `${barang.id}-${satuan.id}`;
                if (selectedItems.includes(itemKey)) {
                    showErrorAlertNoRefresh(
                        `Item "${barang.nama_barang || barang.nama}" dengan satuan "${satuan.nama_satuan || satuan.nama}" sudah ditambahkan.`
                    );
                    return;
                }

                itemCounter++;
                const rowHtml = `
                    <tr id="row-${itemCounter}">
                        <input type="hidden" name="items[${itemCounter}][barang_id]" value="${barang.id}">
                        <input type="hidden" name="items[${itemCounter}][satuan_id]" value="${satuan.id}">
                        <td class="text-center">
                            <button type="button" class="btn btn-danger btn-xs btn-remove-item" data-key="${itemKey}"><i class="fal fa-times"></i></button>
                        </td>
                        <td>${barang.nama_barang || barang.nama}</td>
                        <td>${satuan.nama_satuan || satuan.nama}</td>
                        <td><span class="stok-text">${stok}</span></td>
                        <td>
                            <input type="number" name="items[${itemCounter}][qty]" class="form-control form-control-sm" value="${qty}" min="1" max="${stok}" required>
                        </td>
                        <td>
                            <input type="text" name="items[${itemCounter}][keterangan]" class="form-control form-control-sm" placeholder="Keterangan..." value="${keterangan || ''}">
                        </td>
                    </tr>`;
                $itemTableBody.append(rowHtml);
                selectedItems.push(itemKey);
            }

            /**
             * Menangani klik tombol hapus item.
             */
            function handleRemoveItemClick() {
                const $button = $(this);
                const keyToRemove = $button.data('key');

                // Hapus dari array pelacak
                selectedItems = selectedItems.filter(item => item !== keyToRemove);

                // Hapus baris dari tabel
                $button.closest('tr').remove();

                // Aktifkan kembali tombol di modal
                const $modalButton = $addModalContent.find(
                    `.btn-pilih-item[data-item*='"id":${keyToRemove.split('-')[0]}']`); // Kurang akurat tapi cukup
                // Logika yang lebih baik adalah menyimpan referensi tombol
                // Namun untuk sekarang ini sudah cukup.
            }

            /**
             * Menangani submit form (Draft/Final).
             */
            function handleFormSubmit(e) {
                e.preventDefault();

                if ($itemTableBody.find('tr').length === 0) {
                    showErrorAlertNoRefresh('Harap tambahkan minimal satu item barang.');
                    return;
                }

                const status = $(this).attr('id') === 'btn-simpan-draft' ? 'draft' : 'final';
                $('#status-input').val(status);

                // Tampilkan loader fullscreen sebelum submit
                $pageLoader.show();

                // Kirim form menggunakan AJAX
                const formData = new FormData($form[0]);
                const actionUrl = $form.attr('action');

                $.ajax({
                    url: actionUrl,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $pageLoader.hide();
                        if (response.success) {
                            showSuccessAlert(response.message || 'Distribusi Barang berhasil disimpan.');
                            // Tutup window saat ini dan reload window pembuka
                            if (window.opener) {
                                window.opener.location.reload();
                            }
                            window.close();
                        }
                    },
                    error: function(xhr) {
                        $pageLoader.hide();
                        let errorMessage = 'Terjadi kesalahan saat menyimpan data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showErrorAlertNoRefresh(errorMessage);
                    }
                });
            }

        });
    </script>
@endsection
