@props([
    'label' => null,
    'help' => null,
    'for' => null,
    'required' => false,
    'error' => null,
])

<div {{ $attributes->class(['mb-4']) }}>
    @if ($label)
        <label @if ($for) for="{{ $for }}" @endif
            class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
            {{ $label }}
            @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    {{ $slot }}

    @if ($error)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $error }}</p>
    @endif

    @if ($help)
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $help }}</p>
    @endif
</div>
