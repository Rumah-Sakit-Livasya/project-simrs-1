<script>
    $(document).ready(function() {
        $('.select2').select2();

        $('#add-satuan-tambahan').on('click', function() {
            var newRow = `
                <div class="row align-items-center mb-2">
                    <div class="col-md-5">
                        <select name="satuans_id[]" class="form-control select2-dynamic">
                           <option value="">Pilih Satuan...</option>
                           @foreach ($satuans as $satuan)
                                <option value="{{ $satuan->id }}">{{ $satuan->nama }}</option>
                           @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <input type="number" name="satuans_jumlah[]" class="form-control" placeholder="Isi/Jumlah">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-sm remove-satuan-tambahan">Hapus</button>
                    </div>
                </div>
            `;
            $('#satuan-tambahan-container').append(newRow);
            $('.select2-dynamic').select2();
        });

        $('#satuan-tambahan-container').on('click', '.remove-satuan-tambahan', function() {
            $(this).closest('.row').remove();
        });
    });
</script>
