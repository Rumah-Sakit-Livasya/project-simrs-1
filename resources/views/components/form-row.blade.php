{{-- resources/views/components/form-row.blade.php (Diperbarui untuk AJAX) --}}
@props(['label', 'for'])

<div class="form-group">
    <div class="row align-items-center">
        <div class="col-xl-4 text-right">
            <label class="form-label" for="{{ $for }}">{{ $label }}</label>
        </div>
        <div class="col-xl-8">
            {{ $slot }}
            {{-- Placeholder untuk pesan error dari AJAX, ditargetkan oleh JavaScript --}}
            <div class="invalid-feedback d-block" id="error-{{ $for }}"></div>
        </div>
    </div>
</div>
