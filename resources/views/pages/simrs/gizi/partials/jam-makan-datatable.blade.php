<style>
    .display-none {
        display: none;
    }

    .popover {
        max-width: 100%;
        max-height:
    }
</style>


<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    Daftar <span class="fw-300"><i>Waktu Jam Makan</i></span>
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
                                <th>Nama Waktu Makan</th>
                                <th>Setiap Jam</th>
                                <th>Aktif?</th>
                                <th>Auto Order?</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jam_makans as $jam_makan)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        {{ $jam_makan->waktu_makan }}
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::createFromFormat('H:i:s', $jam_makan->jam)->format('h:i A') }}
                                    </td>
                                    <td>
                                        {{ $jam_makan->aktif ? 'Aktif' : 'Non Aktif' }}
                                    </td>
                                    <td>
                                        {{ $jam_makan->auto_order ? 'Aktif' : 'Non Aktif' }}
                                    </td>
                                    <td>
                                        <a class="mdi mdi-pencil pointer mdi-24px text-secondary edit-btn"
                                            data-bs-toggle="modal" data-bs-target="#editModal{{ $jam_makan->id }}"
                                            title="Edit" data-id="{{ $jam_makan->id }}"></a>

                                        @include('pages.simrs.gizi.partials.edit-jam-makan-modal', [
                                            'jam_makan' => $jam_makan,
                                        ])

                                        <a class="mdi mdi-close pointer mdi-24px text-danger delete-btn" title="Hapus"
                                            data-id="{{ $jam_makan->id }}"></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Nama Waktu Makan</th>
                                <th>Setiap Jam</th>
                                <th>Aktif?</th>
                                <th>Auto Order?</th>
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
