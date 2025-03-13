<style>
    .display-none {
        display: none;
    }

    .popover {
        max-width: 100%;
        max-height: 100%
    }
</style>


<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    Daftar <span class="fw-300"><i>Order Radiologi</i></span>
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
                                <th>Tanggal</th>
                                <th>No. RM</th>
                                <th>No. Registrasi</th>
                                <th>No. Order</th>
                                <th>Nama Lengkap</th>
                                <th>Poly / Ruang</th>
                                <th>Penjamin</th>
                                <th>Dokter</th>
                                <th>Status Isi Hasil</th>
                                <th>Status Billed</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-placement="top"
                                            data-bs-toggle="popover" data-bs-title="Detail Order Radiologi"
                                            data-bs-html="true"
                                            data-bs-content-id="popover-content-{{ $order->id }}">
                                            <i class="fas fa-list text-light" style="transform: scale(1.8)"></i>
                                        </button>
                                        <div class="display-none" id="popover-content-{{ $order->id }}">
                                            @include(
                                                'pages.simrs.pendaftaran.partials.detail-order-radiologi',
                                                ['order' => $order]
                                            )
                                        </div>
                                    </td>
                                    <td>
                                        @if ($order->registration->patient->orderBy('created_at', 'desc')->first() !== null)
                                            @if ($order->registration->patient->orderBy('created_at', 'desc')->first()->status === 'aktif')
                                                <a
                                                    href="{{ route('detail.registrasi.pasien', $order->registration->patient->orderBy('created_at', 'desc')->first()->id) }}">
                                                    {{ $order->order_date }}
                                                </a>
                                            @else
                                                <a
                                                    href="{{ route('detail.pendaftaran.pasien', $order->registration->patient->id) }}">
                                                    {{ $order->order_date }}
                                                </a>
                                            @endif
                                        @else
                                            <a
                                                href="{{ route('detail.pendaftaran.pasien', $order->registration->patient->id) }}">
                                                {{ $order->order_date }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($order->registration->patient->orderBy('created_at', 'desc')->first() !== null)
                                            @if ($order->registration->patient->orderBy('created_at', 'desc')->first()->status === 'aktif')
                                                <a
                                                    href="{{ route('detail.registrasi.pasien', $order->registration->patient->orderBy('created_at', 'desc')->first()->id) }}">
                                                    {{ $order->registration->patient->medical_record_number }}
                                                </a>
                                            @else
                                                <a
                                                    href="{{ route('detail.pendaftaran.pasien', $order->registration->patient->id) }}">
                                                    {{ $order->registration->patient->medical_record_number }}
                                                </a>
                                            @endif
                                        @else
                                            <a
                                                href="{{ route('detail.pendaftaran.pasien', $order->registration->patient->id) }}">
                                                {{ $order->registration->patient->medical_record_number }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($order->registration->patient->orderBy('created_at', 'desc')->first() !== null)
                                            @if ($order->registration->patient->orderBy('created_at', 'desc')->first()->status === 'aktif')
                                                <a
                                                    href="{{ route('detail.registrasi.pasien', $order->registration->patient->orderBy('created_at', 'desc')->first()->id) }}">
                                                    {{ $order->registration->registration_number }}
                                                </a>
                                            @else
                                                <a
                                                    href="{{ route('detail.pendaftaran.pasien', $order->registration->patient->id) }}">
                                                    {{ $order->registration->registration_number }}
                                                </a>
                                            @endif
                                        @else
                                            <a
                                                href="{{ route('detail.pendaftaran.pasien', $order->registration->patient->id) }}">
                                                {{ $order->registration->registration_number }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($order->registration->patient->orderBy('created_at', 'desc')->first() !== null)
                                            @if ($order->registration->patient->orderBy('created_at', 'desc')->first()->status === 'aktif')
                                                <a
                                                    href="{{ route('detail.registrasi.pasien', $order->registration->patient->orderBy('created_at', 'desc')->first()->id) }}">
                                                    {{ $order->no_order }}
                                                </a>
                                            @else
                                                <a
                                                    href="{{ route('detail.pendaftaran.pasien', $order->registration->patient->id) }}">
                                                    {{ $order->no_order }}
                                                </a>
                                            @endif
                                        @else
                                            <a
                                                href="{{ route('detail.pendaftaran.pasien', $order->registration->patient->id) }}">
                                                {{ $order->no_order }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($order->registration->patient->orderBy('created_at', 'desc')->first() !== null)
                                            @if ($order->registration->patient->orderBy('created_at', 'desc')->first()->status === 'aktif')
                                                <a
                                                    href="{{ route('detail.registrasi.pasien', $order->registration->patient->orderBy('created_at', 'desc')->first()->id) }}">
                                                    {{ $order->registration->patient->name }}
                                                </a>
                                            @else
                                                <a
                                                    href="{{ route('detail.pendaftaran.pasien', $order->registration->patient->id) }}">
                                                    {{ $order->registration->patient->name }}
                                                </a>
                                            @endif
                                        @else
                                            <a
                                                href="{{ route('detail.pendaftaran.pasien', $order->registration->patient->id) }}">
                                                {{ $order->registration->patient->name }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($order->registration->patient->orderBy('created_at', 'desc')->first() !== null)
                                            @if ($order->registration->patient->orderBy('created_at', 'desc')->first()->status === 'aktif')
                                                <a
                                                    href="{{ route('detail.registrasi.pasien', $order->registration->patient->orderBy('created_at', 'desc')->first()->id) }}">
                                                    {{ $order->registration->poliklinik }}
                                                </a>
                                            @else
                                                <a
                                                    href="{{ route('detail.pendaftaran.pasien', $order->registration->patient->id) }}">
                                                    {{ $order->registration->poliklinik }}
                                                </a>
                                            @endif
                                        @else
                                            <a
                                                href="{{ route('detail.pendaftaran.pasien', $order->registration->patient->id) }}">
                                                {{ $order->registration->poliklinik }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($order->registration->patient->orderBy('created_at', 'desc')->first() !== null)
                                            @if ($order->registration->patient->orderBy('created_at', 'desc')->first()->status === 'aktif')
                                                <a
                                                    href="{{ route('detail.registrasi.pasien', $order->registration->patient->orderBy('created_at', 'desc')->first()->id) }}">
                                                    {{ $order->registration->patient->penjamin->name ?? '-' }}
                                                </a>
                                            @else
                                                <a
                                                    href="{{ route('detail.pendaftaran.pasien', $order->registration->patient->id) }}">
                                                    {{ $order->registration->patient->penjamin->name ?? '-' }}
                                                </a>
                                            @endif
                                        @else
                                            <a
                                                href="{{ route('detail.pendaftaran.pasien', $order->registration->patient->id) }}">
                                                {{ $order->registration->patient->penjamin->name ?? '-' }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($order->registration->patient->orderBy('created_at', 'desc')->first() !== null)
                                            @if ($order->registration->patient->orderBy('created_at', 'desc')->first()->status === 'aktif')
                                                <a
                                                    href="{{ route('detail.registrasi.pasien', $order->registration->patient->orderBy('created_at', 'desc')->first()->id) }}">
                                                    {{ $order->doctor->employee->fullname }}
                                                </a>
                                            @else
                                                <a
                                                    href="{{ route('detail.pendaftaran.pasien', $order->registration->patient->id) }}">
                                                    {{ $order->doctor->employee->fullname }}
                                                </a>
                                            @endif
                                        @else
                                            <a
                                                href="{{ route('detail.pendaftaran.pasien', $order->registration->patient->id) }}">
                                                {{ $order->doctor->employee->fullname }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($order->registration->patient->orderBy('created_at', 'desc')->first() !== null)
                                            @if ($order->registration->patient->orderBy('created_at', 'desc')->first()->status === 'aktif')
                                                <a
                                                    href="{{ route('detail.registrasi.pasien', $order->registration->patient->orderBy('created_at', 'desc')->first()->id) }}">
                                                    {{ $order->status_isi_hasil == 1 ? 'Finished' : 'Ongoing' }}
                                                </a>
                                            @else
                                                <a
                                                    href="{{ route('detail.pendaftaran.pasien', $order->registration->patient->id) }}">
                                                    {{ $order->status_isi_hasil == 1 ? 'Finished' : 'Ongoing' }}
                                                </a>
                                            @endif
                                        @else
                                            <a
                                                href="{{ route('detail.pendaftaran.pasien', $order->registration->patient->id) }}">
                                                {{ $order->status_isi_hasil == 1 ? 'Finished' : 'Ongoing' }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($order->registration->patient->orderBy('created_at', 'desc')->first() !== null)
                                            @if ($order->registration->patient->orderBy('created_at', 'desc')->first()->status === 'aktif')
                                                <a
                                                    href="{{ route('detail.registrasi.pasien', $order->registration->patient->orderBy('created_at', 'desc')->first()->id) }}">
                                                    {{ $order->status_billed == 1 ? 'Billed' : 'Not Billed' }}
                                                </a>
                                            @else
                                                <a
                                                    href="{{ route('detail.pendaftaran.pasien', $order->registration->patient->id) }}">
                                                    {{ $order->status_billed == 1 ? 'Billed' : 'Not Billed' }}
                                                </a>
                                            @endif
                                        @else
                                            <a
                                                href="{{ route('detail.pendaftaran.pasien', $order->registration->patient->id) }}">
                                                {{ $order->status_billed == 1 ? 'Billed' : 'Not Billed' }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($order->status_billed == 1)
                                            <a class="mdi mdi-printer pointer mdi-24px text-success nota-btn"
                                                title="Nota Order Radiologi" data-id="{{ $order->id }}"></a>
                                        @else
                                            <a class="mdi mdi-cash pointer mdi-24px text-danger pay-btn"
                                                title="Konfirmasi Tagihan" data-id="{{ $order->id }}"></a>
                                        @endif

                                        <a class="mdi mdi-pencil pointer mdi-24px text-secondary edit-btn"
                                            title="Edit" data-id="{{ $order->id }}"></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Detail</th>
                                <th>Tanggal</th>
                                <th>No. RM</th>
                                <th>No. Registrasi</th>
                                <th>No. Order</th>
                                <th>Nama Lengkap</th>
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
