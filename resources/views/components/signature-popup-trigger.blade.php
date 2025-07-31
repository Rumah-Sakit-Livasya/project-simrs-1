{{-- resources/views/components/signature-popup-trigger.blade.php --}}

@props([
    'inputName',
    'inputId',
    'previewId',
    'initialData' => '',
    'isEditMode' => true, // Default ke mode edit
])

@if ($isEditMode)
    {{-- Tampilan untuk Mode Edit --}}
    <div class="signature-wrapper text-center">
        <div class="signature-preview-container border rounded mb-2"
            style="min-height: 120px; display: flex; align-items: center; justify-content: center; position: relative;">
            <img id="{{ $previewId }}" src="{{ $initialData ?: '' }}" alt="Pratinjau Tanda Tangan"
                style="max-width: 100%; height: auto; display: {{ !empty($initialData) ? 'block' : 'none' }};">

            <span class="text-muted placeholder-text" style="display: {{ empty($initialData) ? 'block' : 'none' }};">Belum
                ada tanda tangan</span>
        </div>

        <button type="button" class="btn btn-outline-primary btn-sm open-signature-popup"
            data-input-target="{{ $inputId }}" data-preview-target="{{ $previewId }}">
            <i class="fas fa-pen-alt"></i> Bubuhkan Tanda Tangan
        </button>

        <input type="hidden" name="{{ $inputName }}" id="{{ $inputId }}" value="{{ $initialData }}">
    </div>
@else
    {{-- Tampilan untuk Mode Lihat (Read-Only) --}}
    <div class="signature-view-wrapper">
        @if (!empty($initialData))
            {{-- [PENTING] Kita set width secara eksplisit dan height auto untuk menjaga rasio aspek --}}
            <img src="{{ $initialData }}" alt="Tanda Tangan"
                style="width: 250px; height: auto; max-width: 100%; border-bottom: 1px solid #333;">
        @else
            {{-- Tampilkan area kosong jika tidak ada tanda tangan --}}
            <div
                style="width: 250px; height: 80px; border-bottom: 1px solid #333; text-align: center; padding-top: 30px; color: #999; margin: 0 auto;">
                <em>(Tidak ada tanda tangan)</em>
            </div>
        @endif
    </div>
@endif
