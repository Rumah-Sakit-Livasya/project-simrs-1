<style>
    .display-none {
        display: none;
    }

    .popover {
        max-width: 100%;
        max-height: 100%
    }
</style>

<div class="panel-hdr border-top">
    <h2 class="text-light">
        <i class="fas fa-address-card mr-3 ml-2 text-primary" style="transform: scale(2.1)"></i>
        <span class="text-primary">Radiologi</span>
    </h2>
</div>
<div>
    <div class="row">
        <div class="col-xl-12">
            <div id="panel-1" class="panel">
                <div class="panel-container show">
                    <div class="panel-content">
                        <!-- datatable start -->
                        <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                            <thead class="bg-primary-600">
                                <tr>
                                    <th>#</th>
                                    <th>Detail</th>
                                    <th>Tanggal</th>
                                    <th>No. Registrasi</th>
                                    <th>No. Order</th>
                                    <th>Poly / Ruang</th>
                                    <th>Penjamin</th>
                                    <th>Dokter</th>
                                    <th>Status Isi Hasil</th>
                                    <th>Status Billed</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($radiologiOrders as $order)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary"
                                                data-bs-placement="top" data-bs-toggle="popover"
                                                data-bs-title="Detail Order Radiologi" data-bs-html="true"
                                                data-bs-content-id="popover-content-{{ $order->id }}">
                                                <i class="fas fa-list text-light"  style="transform: scale(1.8)"></i>
                                            </button>
                                            <div class="display-none" id="popover-content-{{ $order->id }}">
                                                @include(
                                                    'pages.simrs.pendaftaran.partials.detail-order-radiologi',
                                                    ['order' => $order]
                                                )
                                            </div>
                                        </td>
                                        <td>
                                            {{ $order->order_date }}
                                        </td>
                                        <td>
                                            {{ $order->registration->id }}
                                        </td>
                                        <td>
                                            {{ $order->no_order }}
                                        </td>
                                        <td>
                                            {{ $order->registration->poliklinik }}
                                        </td>
                                        <td>
                                            {{ $order->registration->patient->penjamin->name ?? '-' }}
                                        </td>
                                        <td>
                                            {{ $order->doctor->employee->fullname }}
                                        </td>
                                        <td>
                                            {{ $order->status_isi_hasil == 1 ? 'Finished' : 'Ongoing' }}
                                        </td>
                                        <td>
                                            {{ $order->status_billed == 1 ? 'Billed' : 'Not Billed' }}
                                        </td>
                                        <td> - </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Detail</th>
                                    <th>Tanggal</th>
                                    <th>No. Registrasi</th>
                                    <th>No. Order</th>
                                    <th>Poly / Ruang</th>
                                    <th>Penjamin</th>
                                    <th>Dokter</th>
                                    <th>Status Isi Hasil</th>
                                    <th>Status Billed</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                        <!-- datatable end -->
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>

<script>
    let listPopoverRadiologi = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    listPopoverRadiologi.map((el) => {
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


@include('pages.simrs.pendaftaran.partials.order-radiologi')
