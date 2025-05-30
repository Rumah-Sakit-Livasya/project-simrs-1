{{-- Blade: partials/signature-field.blade.php --}}
@props(['judul', 'name_prefix', 'index', 'signature_model'])

<div class="text-center">
    <span>{{ $judul }}</span>

    @if ($signature_model?->signature)
        <div class="text-center mt-3">
            <img id="signature_preview_{{ $index }}"
                src="{{ asset('storage/' . $signature_model->signature) }}?v={{ strtotime($signature_model->updated_at) }}"
                alt="Preview Tanda Tangan" class="img-fluid my-2" style="border: 1px solid #ccc; max-height: 200px;">
        </div>
    @else
        <div class="text-center mt-3">
            <img id="signature_preview_{{ $index }}" src="" alt="Preview Tanda Tangan"
                class="img-fluid my-2" style="border: 1px solid #ccc; max-height: 200px; display: none;">
        </div>
    @endif

    <input type="hidden" name="{{ $name_prefix }}[signature_image]" id="signature_image_{{ $index }}">

    <div class="mt-4 d-flex flex-column align-items-center">
        <input type="text" name="{{ $name_prefix }}[pic]" id="pic_{{ $index }}"
            style="border: none; border-bottom: 1px solid #ccc; width: 200px; text-align: center;">
        <div class="mt-2">
            <a class="badge badge-primary text-white ttd" onclick="openSignaturePadMany({{ $index }})"
                id="ttd_pegawai_{{ $index }}">Tanda tangan</a>
        </div>
    </div>
</div>
