<div class="row mt-5 justify-content-center">
    <div class="col-md-4 text-center">
        <span>{{ $judul }}</span>

        {{-- Tampilkan tanda tangan jika sudah tersimpan --}}
        @if ($pengkajian?->signature)
            {{-- Tampilkan tanda tangan --}}
            <div class="text-center mt-3">
                <img id="signature_preview"
                    src="{{ asset('storage/' . $pengkajian?->signature?->signature) }}?v={{ strtotime($pengkajian?->signature?->updated_at) }}"
                    alt="Preview Tanda Tangan" class="img-fluid my-2"
                    style="border-radius: 11px; border: 1px solid #ccc; max-height: 200px;">
            </div>
        @else
            {{-- Tampilkan tombol ttd jika belum ada --}}
            <div class="mt-3">
                <img id="signature_preview" src="" alt="Signature Image"
                    style="border-radius: 11px; border: 1px solid #ccc; display:none; max-width:60%;">
            </div>
        @endif
        <input type="hidden" name="signature_image" id="signature_image">
        {{-- Hidden input untuk disimpan di form --}}

        <div class="mt-5 d-flex flex-column align-items-center">
            <input type="text" name="pic" id="pic" value="{{ $pengkajian?->signature?->pic ?? $pic }}"
                style="border-top: none; border-left: none; border-right: none; border-bottom: 1px solid #ccc; width: 200px; text-align: center;">
            <input type="hidden" name="role" id="role" value="{{ $pengkajian?->signature?->role ?? $role }}">
            <div id="tombol-1" class="mt-2">
                <a class="badge badge-primary text-white ttd" onclick="openSignaturePad(1, 'gadar')"
                    id="ttd_pegawai">Tanda tangan</a>
            </div>
        </div>

    </div>
</div>
