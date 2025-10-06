@props([
    'name',
    'id' => null,
    'type' => 'text',
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
    'autocomplete' => null,
])

@if ($label)
    <label for="{{ $id ?? $name }}" class="form-label {{ $labelClass }}">
        {{ $label }}@if ($required)
            <span class="text-danger">*</span>
        @endif
    </label>
@endif
<input type="{{ $type }}" name="{{ $name }}" id="{{ $id ?? $name }}" value="{{ old($name, $value) }}"
    @if ($placeholder) placeholder="{{ $placeholder }}" @endif
    @if ($required) required @endif @if ($disabled) disabled @endif
    @if ($readonly) readonly @endif
    @if ($autocomplete) autocomplete="{{ $autocomplete }}" @endif
    {{ $attributes->merge(['class' => 'form-control ' . $class . ' ' . $inputClass . ($error ? ' is-invalid' : '')]) }}>
@if ($error)
    <div class="invalid-feedback">
        {{ $error }}
    </div>
@endif
