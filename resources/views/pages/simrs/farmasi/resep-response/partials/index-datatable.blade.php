<input type="hidden" id="user_id" value="{{ auth()->user()->id }}">
<input type="hidden" id="username" value="{{ auth()->user()->name }}">

<style>
    .display-none {
        display: none;
    }

    .popover {
        max-width: 100%;
        max-height:
    }

    .nama-pasien,
    .no-resep,
    .nama-dokter,
    .user-input {
        color: green;
    }

    .status-bill {
        color: salmon;
    }

    .reg-pasien {
        color: slateblue;
    }

    .poli-dokter,
    .waktu-input {
        color: orange;
    }
</style>

<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    Daftar <span class="fw-300"><i>Response Time</i></span>
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
                                <th>Order Resep</th>
                                <th>Pasien</th>
                                <th>Dokter</th>
                                <th>Input Resep</th>
                                <th>Penyiapan</th>
                                <th>Racik</th>
                                <th>Verifikasi</th>
                                <th>Penyerahan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($responses as $response)
                                @php
                                    if (isset($response->resep)) {
                                        $registration = $response->resep->registration;
                                    } else {
                                        $registration = $response->re->registration;
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <p class="no-resep">
                                            {{ isset($response->re_id) ? $response->re->kode_re : 'NON E-RESEP' }}</p>
                                        <p class="status-bill">
                                            @if (isset($response->re_id))
                                                @if (isset($response->re->resep) && $response->re->resep->billed)
                                                    Lunas
                                                @else
                                                    Belum Bill
                                                @endif
                                            @else
                                                @if ($response->resep->billed)
                                                    Lunas
                                                @else
                                                    Belum Bill
                                                @endif
                                            @endif
                                        </p>
                                    </td>
                                    <td>
                                        <p class="nama-pasien">{{ $registration->patient->name }}</p>
                                        <p class="status-bill">No RM / Reg:
                                            {{ $registration->patient->medical_record_number }} /
                                            {{ $registration->registration_number }}</p>
                                    </td>
                                    <td>
                                        @if (isset($response->resep) && isset($response->resep->doctor))
                                            <p class="nama-dokter">{{ $response->resep->doctor->employee->fullname }}
                                            </p>
                                            <p class="status-bill">
                                                {{ $response->resep->doctor->department_from_doctors->name }}</p>
                                        @else
                                            <p class="nama-dokter">
                                                {{ $registration->doctor->employee->fullname }}</p>
                                            <p class="status-bill">
                                                {{ $registration->doctor->department_from_doctors->name }}
                                            </p>
                                        @endif
                                    </td>

                                    {{-- input resep --}}
                                    <td>
                                        @if ($response->input_resep_user_id != null)
                                            <p class="user-input">{{ $response->inputer->name }}</p>
                                            <p class="status-bill">{{ tgl_waktu($response->input_resep_time) }}</p>
                                        @else
                                            <button type="button" class="btn btn-primary process-btn"
                                                data-id="{{ $response->id }}" data-type="input_resep">
                                                <p class="fas fa-cog mr-1"></p>
                                            </button>
                                        @endif
                                    </td>

                                    {{-- penyiapan --}}
                                    <td>
                                        @if ($response->penyiapan_user_id != null)
                                            <p class="user-input">{{ $response->penyiap->name }}</p>
                                            <p class="status-bill">{{ tgl_waktu($response->penyiapan_time) }}</p>
                                        @else
                                            <button type="button" class="btn btn-primary process-btn"
                                                data-id="{{ $response->id }}" data-type="penyiapan">
                                                <p class="fas fa-cog mr-1"></p>
                                            </button>
                                        @endif
                                    </td>

                                    {{-- racik --}}
                                    <td>
                                        @if ($response->racik_user_id != null)
                                            <p class="user-input">{{ $response->raciker->name }}</p>
                                            <p class="status-bill">{{ tgl_waktu($response->racik_time) }}</p>
                                        @else
                                            <button type="button" class="btn btn-primary process-btn"
                                                data-id="{{ $response->id }}" data-type="racik">
                                                <p class="fas fa-cog mr-1"></p>
                                            </button>
                                        @endif
                                    </td>

                                    {{-- verifikasi --}}
                                    <td>
                                        @if ($response->verifikasi_user_id != null)
                                            <p class="user-input">{{ $response->verifikator->name }}</p>
                                            <p class="status-bill">{{ tgl_waktu($response->verifikasi_time) }}</p>
                                        @else
                                            <button type="button" class="btn btn-primary process-btn"
                                                data-id="{{ $response->id }}" data-type="verifikasi">
                                                <p class="fas fa-cog mr-1"></p>
                                            </button>
                                        @endif
                                    </td>

                                    {{-- penyerahan --}}
                                    <td>
                                        @if ($response->penyerahan_user_id != null)
                                            <p class="user-input">{{ $response->penyerah->name }}</p>
                                            <p class="status-bill">{{ tgl_waktu($response->penyerahan_time) }}</p>
                                        @else
                                            <button type="button" class="btn btn-primary process-btn"
                                                data-id="{{ $response->id }}" data-type="penyerahan">
                                                <p class="fas fa-cog mr-1"></p>
                                            </button>
                                        @endif
                                    </td>

                                    <td>

                                        @if (isset($response->resep_id) || isset($response->re->resep))
                                            <a class="fas fa-clipboard-list pointer fa-lg text-info telaah-btn"
                                                title="Telaah resep" data-id="{{ $response->id }}"></a>
                                        @endif

                                        @if (isset($response->keterangan))
                                            <a class="fas fa-edit pointer fa-lg text-warning keterangan-btn"
                                                title="Tambah Keterangan" data-id="{{ $response->id }}"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                data-bs-title="Keterangan: {{ $response->keterangan}}"
                                                data-keterangan="{{ $response->keterangan}}"></a>
                                        @else
                                            <a class="fas fa-edit pointer fa-lg text-warning keterangan-btn"
                                                title="Tambah Keterangan" data-id="{{ $response->id }}"
                                                data-keterangan=""></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Order Resep</th>
                                <th>Pasien</th>
                                <th>Dokter</th>
                                <th>Input Resep</th>
                                <th>Penyiapan</th>
                                <th>Racik</th>
                                <th>Verifikasi</th>
                                <th>Penyerahan</th>
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
