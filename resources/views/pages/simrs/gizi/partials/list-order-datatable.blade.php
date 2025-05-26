<style>
    .display-none {
        display: none;
    }

    .popover {
        max-width: 100%;
        max-height:
    }

    .top-button-div {
        margin-left: 12px;
        border-radius: 8px;
        padding: 2px;
        /* creates the "border" thickness */
        background: linear-gradient(to right, #5f9fff, #2f00ff);
        /* the fake border */
        position: relative;
        display: inline-block;
        /* shrink to fit if needed */
    }

    .top-button-div>.inner {
        background: white;
        /* this looks like the real background */
        border-radius: 8px;
        /* slightly less than outer */
        padding: 2px;
    }
</style>


<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    Daftar <span class="fw-300"><i>Order Gizi</i></span>
                    <div class="top-button-div">
                        <div class="inner">
                            <button type="button" class="btn btn-sm btn-primary bulk-send-btn" title="Kirim semua pesanan">
                                <i class="fas fa-cubes text-light" style="transform: scale(1.8)"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-info bulk-print-btn" title="Print label semua pesanan">
                                <i class="fas fa-tags text-light" style="transform: scale(1.8)"></i>
                            </button>
                        </div>
                    </div>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">

                    <!-- datatable start -->
                    <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                        <i id="loading-spinner" class="fas fa-spinner fa-spin"></i>
                        <thead class="bg-primary-600">
                            <tr>
                                <th>#</th>
                                <th>Detail</th>
                                <th>Nama Pemesan</th>
                                <th>Untuk</th>
                                <th>[KELAS] Nama Pasien</th>
                                <th>No RM / No Reg</th>
                                <th>Waktu Makan</th>
                                <th>Harga</th>
                                <th>Ditagihkan?</th>
                                <th>Pembayaran</th>
                                <th>Pesanan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-placement="top"
                                            data-bs-toggle="popover" data-bs-title="Detail Order Gizi"
                                            data-bs-html="true"
                                            data-bs-content-id="popover-content-{{ $order->id }}">
                                            <i class="fas fa-list text-light" style="transform: scale(1.8)"></i>
                                        </button>

                                        <div class="display-none" id="popover-content-{{ $order->id }}">
                                            @include('pages.simrs.gizi.partials.detail-order-gizi', [
                                                'order' => $order,
                                            ])
                                        </div>
                                    </td>
                                    <td>
                                        {{ $order->nama_pemesan }}
                                    </td>
                                    <td>
                                        {{ ucfirst($order->untuk) }}
                                    </td>
                                    <td>
                                        [{{ $order->registration->kelas_rawat->kelas }}]
                                        {{ $order->registration->patient->name }}
                                    </td>
                                    <td>
                                        {{ $order->registration->patient->medical_record_number }} /
                                        {{ $order->registration->registration_number }}
                                    </td>
                                    <td>
                                        {{ ucfirst($order->waktu_makan) }}
                                    </td>
                                    <td>
                                        {{ rp($order->total_harga) }}
                                    </td>
                                    <td>
                                        {{ $order->ditagihkan ? 'Ya' : 'Tidak' }}
                                    </td>
                                    <td>
                                        @if ($order->status_order)
                                            {{ $order->status_payment ? 'Payment (Closed)' : 'Not Billed' }}
                                        @else
                                            Deliver First
                                        @endif
                                    </td>
                                    <td>
                                        {{ $order->status_order ? 'Delivered' : 'Process' }}
                                    </td>

                                    <td>
                                        @if ($order->status_order)
                                        <a class="fas fa-pencil pointer fa-lg text-secondary edit-btn"
                                        title="Edit pesanan" data-id="{{ $order->id }}"></a>
                                        @else
                                            <a class="fas fa-cube pointer fa-lg text-warning send-btn"
                                                title="Kirim pesanan" data-id="{{ $order->id }}"></a>
                                        @endif
                                        <a class="fas fa-print pointer fa-lg text-success print-nota-btn"
                                        title="Print nota order" data-id="{{ $order->id }}"></a>
                                        <a class="fas fa-tag pointer fa-lg text-info print-label-btn"
                                            title="Print label pesanan" data-id="{{ $order->id }}"></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Detail</th>
                                <th>Nama Pemesan</th>
                                <th>Untuk</th>
                                <th>[KELAS] Nama Pasien</th>
                                <th>No RM / No Reg</th>
                                <th>Waktu Makan</th>
                                <th>Harga</th>
                                <th>Ditagihkan?</th>
                                <th>Pembayaran</th>
                                <th>Pesanan</th>
                                <th>Aksi</th>
                            </tr>
                        </tfoot>
                    </table>
                    <!-- datatable end -->
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>

<script>
    const list = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    list.map((el) => {
        let opts = {
            animation: true,
        }
        if (el.hasAttribute('data-bs-content-id')) {
            opts.content = document.getElementById(el.getAttribute('data-bs-content-id')).innerHTML;
            opts.html = true;
            opts.sanitize = false;
        }
        new bootstrap.Popover(el, opts);
    })
</script>
