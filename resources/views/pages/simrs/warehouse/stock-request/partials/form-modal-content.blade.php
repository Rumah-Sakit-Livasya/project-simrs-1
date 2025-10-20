{{-- File ini untuk konten modal Tambah/Edit --}}
<form id="sr-form"
    action="{{ $sr ? route('warehouse.stock-request.pharmacy.update', $sr->id) : route('warehouse.stock-request.pharmacy.store') }}"
    method="POST">
    @csrf
    @if ($sr)
        @method('PUT')
    @endif
    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
    <input type="hidden" name="status" id="status-input"> {{-- Untuk draft/final --}}

    {{-- Isi form Anda di sini (tanggal, gudang, tipe, dll) --}}
    {{-- ... --}}

    {{-- Tabel untuk item-item --}}
    {{-- ... --}}

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="btn-save-draft">Simpan Draft</button>
        <button type="button" class="btn btn-success" id="btn-save-final">Simpan Final</button>
    </div>
</form>

<script>
    // Inisialisasi plugin khusus untuk modal di sini
    $('#sr-form .select2').select2({
        dropdownParent: $('#form-modal')
    });

    // Logika untuk simpan
    $('#btn-save-draft').on('click', function() {
        $('#status-input').val('draft');
        submitForm();
    });

    $('#btn-save-final').on('click', function() {
        $('#status-input').val('final');
        submitForm();
    });

    function submitForm() {
        var form = $('#sr-form');
        var url = form.attr('action');
        var data = form.serialize();

        $.ajax({
            type: "POST",
            url: url,
            data: data,
            success: function(response) {
                $('#form-modal').modal('hide');
                showSuccessAlert('Data berhasil disimpan!');
                $('#dt-sr-pharmacy').DataTable().ajax.reload();
            },
            error: function(xhr) {
                // Handle validation errors
                showErrorAlert('Gagal menyimpan: ' + (xhr.responseJSON ? xhr.responseJSON.message : xhr
                    .statusText));
            }
        });
    }
    // ... (Logika JS untuk menambah/menghapus item di form modal)
</script>
