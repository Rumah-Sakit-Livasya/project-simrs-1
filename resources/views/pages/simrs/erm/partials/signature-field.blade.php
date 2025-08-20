@props([
    'judul',
    'prefix', // <-- ID unik untuk elemen HTML
    'pic' => '',
    'role' => '',
    'signature_model' => null,
])

<div class="row mt-5 justify-content-center">
    <div class="col-md-4 text-center">
        <span>{{ $judul }}</span>

        @php
            $hasSignature = $signature_model?->signature;
            $signatureSrc = $hasSignature
                ? asset('storage/' . $signature_model->signature) . '?v=' . @strtotime($signature_model->updated_at ?? time())
                : '';
            $previewStyle = !$hasSignature ? 'display: none;' : '';
            $picName = $signature_model?->pic ?? $pic;
            $roleName = $signature_model?->role ?? $role;
        @endphp

        <div class="text-center mt-3">
            <img id="signature_preview_{{ $prefix }}" src="{{ $signatureSrc }}" alt="Preview Tanda Tangan"
                class="img-fluid my-2"
                style="border-radius: 11px; border: 1px solid #ccc; max-height: 200px; max-width: 60%; {{ $previewStyle }}">
        </div>

        {{-- PERUBAHAN DI SINI: Atribut 'name' diubah menjadi format array --}}
        <input type="hidden" name="signature_data[signature_image]" id="signature_image_{{ $prefix }}">

        <div class="mt-5 d-flex flex-column align-items-center">
            {{-- PERUBAHAN DI SINI: Atribut 'name' diubah menjadi format array --}}
            <input type="text" name="signature_data[pic]" id="pic_{{ $prefix }}" value="{{ $picName }}"
                style="border-top: none; border-left: none; border-right: none; border-bottom: 1px solid #ccc; width: 200px; text-align: center;"
                placeholder="Nama Jelas">

            {{-- PERUBAHAN DI SINI: Atribut 'name' diubah menjadi format array --}}
            <input type="hidden" name="signature_data[role]" id="role_{{ $prefix }}" value="{{ $roleName }}">

            <div id="tombol_{{ $prefix }}" class="mt-2">
                {{-- Bagian ini sudah benar, memanggil fungsi JavaScript yang terpadu --}}
                <a class="badge badge-primary text-white ttd" onclick="openSignaturePadMany('{{ $prefix }}')"
                    id="ttd_pegawai_{{ $prefix }}">Tanda tangan</a>
            </div>
        </div>
    </div>
</div>
