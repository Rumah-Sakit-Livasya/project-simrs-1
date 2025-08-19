{{-- resources/views/components/form-select.blade.php --}}
@props([
    'id',
    'name',
    'options', // Diharapkan berupa array asosiatif [value => label]
    'selected' => '',
    'placeholder' => 'Pilih salah satu...',
])

<select class="select2 form-control w-100" id="{{ $id }}" name="{{ $name }}">
    @if ($placeholder)
        <option value="">{{ $placeholder }}</option>
    @endif

    @foreach ($options as $value => $label)
        <option value="{{ $value }}" {{ (string) old($name, $selected) === (string) $value ? 'selected' : '' }}>
            {{ $label }}
        </option>
    @endforeach
</select>
