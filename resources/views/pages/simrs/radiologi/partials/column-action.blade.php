@if (!request()->is('simrs/radiologi/list-order'))
    <a class="mdi mdi-delete pointer mdi-24px text-danger delete-btn"
        title="Hapus Order" data-id="{{ $order->id }}"></a>
@else
    @if ($order->is_konfirmasi == 1)
        <a class="mdi mdi-printer pointer mdi-24px text-success nota-btn"
            title="Print Nota Order" data-id="{{ $order->id }}"></a>
    @else
        <a class="mdi mdi-cash pointer mdi-24px text-danger pay-btn"
            title="Konfirmasi Tagihan" data-id="{{ $order->id }}"></a>
    @endif

    <a class="mdi mdi-pencil pointer mdi-24px text-secondary edit-btn"
        title="Edit" data-id="{{ $order->id }}"></a>

    <a class="mdi mdi-tag pointer mdi-24px text-danger label-btn"
        title="Print Label" data-id="{{ $order->id }}"></a>

    <a class="mdi mdi-file-document pointer mdi-24px text-warning result-btn"
        title="Print Hasil" data-id="{{ $order->id }}"></a>

    <a class="mdi mdi-delete pointer mdi-24px text-danger delete-btn"
        title="Hapus Order" data-id="{{ $order->id }}"></a>
@endif
