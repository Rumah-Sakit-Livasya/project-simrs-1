@props(['judul', 'pic', 'role', 'prefix' => 'single', 'signature_model' => null])


<div class="row mt-5 justify-content-center">
    <div class="col-md-4 text-center">
        <span>{{ $judul }}</span>

        {{-- 1. Siapkan variabel untuk src dan style gambar --}}
        @php
            $hasSignature = $signature_model?->signature;
            $signatureSrc = $hasSignature
                ? asset('storage/' . $signature_model->signature) .
                    '?v=' .
                    strtotime($signature_model->updated_at ?? time())
                : '';
            $previewStyle = !$hasSignature ? 'display:none;' : '';
        @endphp

        {{-- 2. Tampilkan HTML hanya sekali menggunakan variabel tersebut --}}
        <div class="text-center mt-3">
            <img id="signature_preview_{{ $prefix }}" src="{{ $signatureSrc }}" alt="Preview Tanda Tangan"
                class="img-fluid my-2"
                style="border-radius: 11px; border: 1px solid #ccc; max-height: 200px; max-width: 60%; {{ $previewStyle }}">
        </div>

        {{-- Gunakan prefix untuk ID dan nama input. --}}
        <input type="hidden" name="signature_image" id="signature_image_{{ $prefix }}">

        <div class="mt-5 d-flex flex-column align-items-center">
            <input type="text" name="pic" id="pic_{{ $prefix }}"
                value="{{ $signature_model?->pic ?? $pic }}"
                style="border-top: none; border-left: none; border-right: none; border-bottom: 1px solid #ccc; width: 200px; text-align: center;">
            <input type="hidden" name="{{ $prefix }}[role]" id="role_{{ $prefix }}"
                value="{{ $signature_model?->role ?? $role }}">

            <div id="tombol_{{ $prefix }}" class="mt-2">
                <a class="badge badge-primary text-white ttd" onclick="openSignatureSinglePad('{{ $prefix }}')"
                    id="ttd_pegawai_{{ $prefix }}">Tanda tangan</a>
            </div>
        </div>
    </div>
</div>
