{{-- File: resources/views/pages/simrs/persalinan/partials/action.blade.php --}}

<div class="btn-group" role="group">
    <button type="button" class="btn btn-sm btn-outline-primary" onclick="editOrder({{ $order->id }})" title="Edit">
        <i class="fal fa-edit"></i>
    </button>

    <button type="button" class="btn btn-sm btn-outline-info" onclick="viewOrder({{ $order->id }})"
        title="Lihat Detail">
        <i class="fal fa-eye"></i>
    </button>

    @if ($order->status_order !== 'completed' && $order->status_order !== 'cancelled')
        <button type="button" class="btn btn-sm btn-outline-success" onclick="confirmOrder({{ $order->id }})"
            title="Konfirmasi">
            <i class="fal fa-check"></i>
        </button>
    @endif

    @if ($order->status_order === 'pending' || $order->status_order === 'confirmed')
        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteOrder({{ $order->id }})"
            title="Hapus">
            <i class="fal fa-trash"></i>
        </button>
    @endif
</div>
