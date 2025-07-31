@props([
    'judul',
    // 'name_prefix', // <-- BARU: Untuk nama input array, mis: "signature_data"
    'prefix', // <-- TETAP: Untuk ID unik, mis: "perawat_pemeriksa"
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

        {{-- Input ini akan disinkronkan oleh JavaScript sebelum submit --}}
        {{-- DIUBAH: Menggunakan name_prefix untuk membuat array --}}
        <input type="hidden" name="signature_image" id="signature_image_{{ $prefix }}">

        <div class="mt-5 d-flex flex-column align-items-center">
            {{-- DIUBAH: Menggunakan name_prefix untuk membuat array --}}
            <input type="text" name="pic" id="pic_{{ $prefix }}" value="{{ $picName }}"
                style="border-top: none; border-left: none; border-right: none; border-bottom: 1px solid #ccc; width: 200px; text-align: center;"
                placeholder="Nama Jelas">

            {{-- DIUBAH: Menggunakan name_prefix untuk membuat array --}}
            <input type="hidden" name="role" id="role_{{ $prefix }}" value="{{ $roleName }}">

            <div id="tombol_{{ $prefix }}" class="mt-2">
                {{-- DIUBAH: Memanggil fungsi yang sama dengan 'signature-many' --}}
                <a class="badge badge-primary text-white ttd" onclick="openSignaturePadMany('{{ $prefix }}')"
                    id="ttd_pegawai_{{ $prefix }}">Tanda tangan</a>
            </div>
        </div>
    </div>
</div>
