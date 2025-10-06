@props([
    'name',
    'id' => null,
    'value' => null,
    'placeholder' => null,
    'class' => '',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'label' => null,
    'labelClass' => '',
    'inputClass' => '',
    'error' => null,
    'rows' => 3,
    'autocomplete' => null,
])

@if ($label)
    <label for="{{ $id ?? $name }}" class="form-label {{ $labelClass }}">
        {{ $label }}@if ($required)
            <span class="text-danger">*</span>
        @endif
    </label>
@endif
<textarea name="{{ $name }}" id="{{ $id ?? $name }}" rows="{{ $rows }}"
    @if ($placeholder) placeholder="{{ $placeholder }}" @endif
    @if ($required) required @endif @if ($disabled) disabled @endif
    @if ($readonly) readonly @endif
    @if ($autocomplete) autocomplete="{{ $autocomplete }}" @endif
    {{ $attributes->merge(['class' => 'form-control ' . $class . ' ' . $inputClass . ($error ? ' is-invalid' : '')]) }}>{{ old($name, $value) }}</textarea>
@if ($error)
    <div class="invalid-feedback">
        {{ $error }}
    </div>
@endif
