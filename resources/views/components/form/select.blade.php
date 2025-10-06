@props([
    'name',
    'id' => null,
    'options' => [],
    'selected' => null,
    'placeholder' => null,
    'class' => '',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'label' => null,
    'labelClass' => '',
    'inputClass' => '',
    'error' => null,
])

@php
    // Support for multiple select (if name ends with [] or options is array of arrays)
    $isMultiple = str_ends_with($name, '[]');
    if (is_array($selected)) {
        $selectedValues = array_map('strval', $selected);
    } elseif (is_null($selected)) {
        $selectedValues = [];
    } else {
        $selectedValues = [(string) $selected];
    }
@endphp

@if ($label)
    <label for="{{ $id ?? $name }}" class="form-label {{ $labelClass }}">
        {{ $label }}@if ($required)
            <span class="text-danger">*</span>
        @endif
    </label>
@endif
<select name="{{ $name }}" id="{{ $id ?? $name }}" @if ($required) required @endif
    @if ($disabled) disabled @endif @if ($readonly) readonly @endif
    @if ($isMultiple) multiple @endif
    {{ $attributes->merge(['class' => 'form-control ' . $class . ' ' . $inputClass . ($error ? ' is-invalid' : '')]) }}>
    @if ($placeholder && !$isMultiple)
        <option value="">{{ $placeholder }}</option>
    @endif
    @foreach ($options as $optionValue => $optionLabel)
        @php
            // If optionLabel is an array, try to get 'label' key, else fallback to string cast
            $displayLabel = is_array($optionLabel) ? $optionLabel['label'] ?? '' : (string) $optionLabel;
            $value =
                is_array($optionLabel) && array_key_exists('value', $optionLabel)
                    ? $optionLabel['value']
                    : $optionValue;
        @endphp
        <option value="{{ $value }}" @if (in_array((string) $value, $selectedValues, true)) selected @endif>
            {{ $displayLabel }}
        </option>
    @endforeach
</select>
@if ($error)
    <div class="invalid-feedback">
        {{ $error }}
    </div>
@endif
