<div class="d-flex demo">
    @if ($order->status_order)
        <button class="btn btn-secondary btn-sm btn-icon rounded-circle waves-effect waves-themed edit-btn"
            title="Sisa Makanan" data-id="{{ $order->id }}">
            <i class="fal fa-utensils-alt"></i>
        </button>
    @else
        <button class="btn btn-warning btn-sm btn-icon rounded-circle waves-effect waves-themed send-btn"
            title="Kirim Pesanan" data-id="{{ $order->id }}">
            <i class="fal fa-truck"></i>
        </button>
    @endif
    <button class="btn btn-success btn-sm btn-icon rounded-circle waves-effect waves-themed ml-1 print-nota-btn"
        title="Print Nota Order" data-id="{{ $order->id }}">
        <i class="fal fa-print"></i>
    </button>
    <button class="btn btn-info btn-sm btn-icon rounded-circle waves-effect waves-themed ml-1 print-label-btn"
        title="Print Label Pesanan" data-id="{{ $order->id }}">
        <i class="fal fa-tag"></i>
    </button>
</div>
