{{-- resources/views/pages/simrs/erm/partials/signature-many.blade.php --}}
@props(['judul', 'name_prefix', 'index', 'pic' => '', 'signature_model' => null])

<div class="text-center">
    <span>{{ $judul }}</span>

    @php
        $hasSignature = $signature_model?->signature;
        $signatureSrc = $hasSignature
            ? asset('storage/' . $signature_model->signature) . '?v=' . @strtotime($signature_model->updated_at)
            : '';
        $previewStyle = !$hasSignature ? 'display: none;' : '';
        $picName = $signature_model?->pic ?? $pic;
    @endphp

    <div class="text-center mt-3">
        <img id="signature_preview_{{ $index }}" src="{{ $signatureSrc }}" alt="Preview Tanda Tangan"
            class="img-fluid my-2" style="border: 1px solid #ccc; max-height: 200px; {{ $previewStyle }}">
    </div>

    {{-- Input ini akan disinkronkan oleh JavaScript sebelum submit --}}
    <input type="hidden" name="{{ $name_prefix }}[signature_image]" id="signature_image_{{ $index }}">

    <div class="mt-4 d-flex flex-column align-items-center">
        <input type="text" name="{{ $name_prefix }}[pic]" id="pic_{{ $index }}" value="{{ $picName }}"
            style="border: none; border-bottom: 1px solid #ccc; width: 200px; text-align: center;"
            placeholder="Nama Jelas">

        <div class="mt-2">
            <a class="badge badge-primary text-white ttd"
                onclick="openSignaturePopup('signature_image_{{ $index }}', 'signature_preview_{{ $index }}')"
                id="ttd_pegawai_{{ $index }}">Tanda tangan</a>
        </div>
    </div>
</div>
