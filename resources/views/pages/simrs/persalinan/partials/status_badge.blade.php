{{-- File: resources/views/pages/simrs/persalinan/partials/status_badge.blade.php --}}

@php
    $statusClasses = [
        'pending' => 'badge-warning',
        'confirmed' => 'badge-info',
        'in_progress' => 'badge-primary',
        'completed' => 'badge-success',
        'cancelled' => 'badge-danger',
    ];

    $statusTexts = [
        'pending' => 'Menunggu',
        'confirmed' => 'Dikonfirmasi',
        'in_progress' => 'Sedang Berlangsung',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ];

    $badgeClass = $statusClasses[$order->status_order] ?? 'badge-secondary';
    $statusText = $statusTexts[$order->status_order] ?? ucfirst($order->status_order);
@endphp

<span class="badge {{ $badgeClass }}">{{ $statusText }}</span>
