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
                    Daftar <span class="fw-300"><i>Retur Resep</i></span>
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
                                <th>Tanggal Retur</th>
                                <th>Kode Retur</th>
                                <th>No RM</th>
                                <th>No Registrasi</th>
                                <th>Nama Pasien</th>
                                <th>Gudang</th>
                                <th>Poly / Ruang</th>
                                <th>User Entry</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($returs as $retur)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-placement="top"
                                            data-bs-toggle="popover" data-bs-title="Detail Retur Resep"
                                            data-bs-html="true"
                                            data-bs-content-id="popover-content-{{ $retur->id }}">
                                            <i class="fas fa-list text-light" style="transform: scale(1.8)"></i>
                                        </button>
                                        <div class="display-none" id="popover-content-{{ $retur->id }}">
                                            @include('pages.simrs.farmasi.retur-resep.partials.rr-detail', [
                                                'retur' => $retur,
                                            ])
                                        </div>
                                    </td>
                                    <td>{{ tgl($retur->tanggal_retur) }}</td>
                                    <td>{{ $retur->kode_retur }}</td>
                                    <td>{{ $retur->patient->medical_record_number }}</td>
                                    <td>{{ $retur->registration->registration_number }}</td>
                                    <td>{{ $retur->patient->name }}</td>
                                    <td>{{ $retur->gudang->nama }}</td>
                                    @php
                                        $poly_ruang = '';
                                        if ($retur->registration->registration_type == 'rawat-jalan') {
                                            $poly_ruang = 'RAWAT JALAN';
                                        } elseif ($retur->registration->registration_type == 'rawat-inap') {
                                            $poly_ruang =
                                                'RAWAT INAP (' .
                                                $retur->registration->kelas_rawat->kelas .
                                                ' - ' .
                                                $retur->registration->patient->bed->room->ruangan .
                                                ' ' .
                                                $retur->registration->patient->bed->nama_tt .
                                                ')';
                                        }
                                    @endphp
                                    <td>{{ $poly_ruang }}</td>
                                    <td>{{ $retur->user->name }}</td>
                                    <td>
                                        <a class="mdi mdi-printer pointer mdi-24px text-primary print-btn"
                                            title="Print" data-id="{{ $retur->id }}"></a>
                                        <a class="mdi mdi-close pointer mdi-24px text-danger delete-btn" title="Hapus"
                                            data-id="{{ $retur->id }}"></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Detail</th>
                                <th>Tanggal Retur</th>
                                <th>Kode Retur</th>
                                <th>No RM</th>
                                <th>No Registrasi</th>
                                <th>Nama Pasien</th>
                                <th>Gudang</th>
                                <th>Poly / Ruang</th>
                                <th>User Entry</th>
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
