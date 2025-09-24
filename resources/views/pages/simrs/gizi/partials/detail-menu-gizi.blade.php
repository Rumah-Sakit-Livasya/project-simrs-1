<div class="child-row-content">
    <h6 class="font-weight-bold">Daftar Makanan dalam Menu:</h6>
    <ul class="list-group">
        @forelse ($menu->makanan_menu as $makananMenu)
            <li class="list-group-item d-flex justify-content-between align-items-center p-2">
                <span class="{{ $makananMenu->aktif ? '' : 'text-muted text-strike' }}">
                    {{ $makananMenu->makanan->nama }}
                    {!! $makananMenu->aktif
                        ? ''
                        : '<i class="fal fa-times-circle text-danger fs-xs ml-1" title="Non-aktif dalam menu ini"></i>' !!}
                </span>
                <span class="badge badge-primary badge-pill">Rp {{ number_format($makananMenu->makanan->harga) }}</span>
            </li>
        @empty
            <li class="list-group-item">Tidak ada makanan dalam menu ini.</li>
        @endforelse
    </ul>
</div>
