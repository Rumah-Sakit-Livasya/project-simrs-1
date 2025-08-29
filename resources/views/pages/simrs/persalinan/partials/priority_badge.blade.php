{{-- File: resources/views/pages/simrs/persalinan/partials/priority_badge.blade.php --}}

@php
    $priorityClass = $order->prioritas === 'emergency' ? 'badge-danger' : 'badge-info';
    $priorityText = $order->prioritas === 'emergency' ? 'Emergency' : 'Normal';
@endphp

<span class="badge {{ $priorityClass }}">{{ $priorityText }}</span>
