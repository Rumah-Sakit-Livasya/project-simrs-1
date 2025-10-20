<!-- datatable start -->
<table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
    <thead>
        <tr>
            {{-- <th style="white-space: nowrap">Foto</th> --}}
            <th style="white-space: nowrap">No</th>
            <th style="white-space: nowrap">Nama</th>
            <th style="white-space: nowrap">Perusahaan</th>
            <th style="white-space: nowrap">Unit</th>
            <th style="white-space: nowrap">Status</th>
            <th style="white-space: nowrap">Jabatan</th>
            <th style="white-space: nowrap">Status Shift</th>
            <th style="white-space: nowrap">Mulai Kontrak</th>
            <th style="white-space: nowrap">Akhir Kontrak</th>
            <th style="white-space: nowrap">Resign</th>
            <th style="white-space: nowrap">Tgl. Lahir</th>
            <th style="white-space: nowrap">Tempat Lahir</th>
            <th style="white-space: nowrap">Alamat</th>
            <th style="white-space: nowrap">Email</th>
            <th style="white-space: nowrap">No. Hp</th>
            <th style="white-space: nowrap">Agama</th>
            <th style="white-space: nowrap">Jenis Kelamin</th>
            <th style="white-space: nowrap">Status Menikah</th>
            <th style="white-space: nowrap">Tipe Identitas</th>
            <th style="white-space: nowrap">Nomor Identitas</th>
            <th style="white-space: nowrap" class="no-print">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @isset($employees_nonaktif)
            @foreach ($employees_nonaktif as $employee)
                <tr>
                    {{-- <td style="white-space: nowrap">{{ $user->template_user->foto }}</td> --}}
                    <td style="white-space: nowrap">{{ $loop->iteration }}</td>
                    <td style="white-space: nowrap">{{ $employee->fullname }}</td>
                    <td style="white-space: nowrap">
                        {{ $employee->company->name ?? '*belum di setting' }}</td>
                    <td style="white-space: nowrap">
                        {{ $employee->organization->name ?? '*belum di setting' }}</td>
                    <td style="white-space: nowrap">
                        {{ $employee->is_management ? 'Management' : 'Pelayanan' }}</td>
                    <td style="white-space: nowrap">
                        {{ $employee->jobPosition->name ?? '*belum di setting' }}</td>
                    <td style="white-space: nowrap">
                        {{ $employee->employment_status ?? '*belum di setting' }}</td>
                    <td style="white-space: nowrap">
                        {{ $employee->join_date }}
                    </td>
                    <td style="white-space: nowrap">
                        {{ $employee->end_status_date }}
                    </td>
                    <td style="white-space: nowrap">
                        {{ $employee->resign_date ?? '-' }}</td>
                    <td style="white-space: nowrap">
                        {{ $employee->birthdate ?? '-' }}</td>
                    <td style="white-space: nowrap">
                        {{ $employee->place_of_birth ?? '-' }}</td>
                    <td style="white-space: nowrap">
                        {{ $employee->residental_address ?? '-' }}</td>
                    <td style="white-space: nowrap">
                        {{ $employee->email ?? '-' }}</td>
                    <td style="white-space: nowrap">
                        {{ $employee->mobile_phone ?? '-' }}</td>
                    <td style="white-space: nowrap">
                        {{ $employee->religion ?? '-' }}</td>
                    <td style="white-space: nowrap">
                        {{ $employee->gender ?? '-' }}</td>
                    <td style="white-space: nowrap">
                        {{ $employee->marital_status ?? '-' }}</td>
                    <td style="white-space: nowrap">
                        {{ $employee->identity_type ?? '-' }}</td>
                    <td style="white-space: nowrap">
                        {{ $employee->identity_number ?? '-' }}</td>
                    <td style="white-space: nowrap">
                        @if ($employee->doctor)
                            <button type="button" onclick="openTariffPopup({{ $employee->doctor->id }})"
                                class="btn btn-info btn-sm btn-icon waves-effect waves-themed" data-toggle="tooltip"
                                data-placement="top" title="Set Tarif Visite Dokter">
                                <i class="fal fa-pills"></i>
                            </button>
                        @endif
                        <button type="button" data-backdrop="static" data-keyboard="false"
                            class="badge mx-1 badge-danger p-2 border-0 text-white"
                            data-template="<div class=&quot;tooltip&quot; role=&quot;tooltip&quot;><div class=&quot;tooltip-inner bg-danger-500&quot;></div></div>"
                            data-toggle="tooltip" data-id="{{ $employee->id }}" title="Non-aktifkan"
                            onclick="btnNonAktifPegawai(event)">
                            <span class="fal fa-eye-slash ikon-non-aktif"></span>
                            <div class="span spinner-text d-none">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            </div>
                        </button>
                        <button type="button" data-backdrop="static" data-keyboard="false"
                            class="badge mx-1 badge-info p-2 border-0 text-white btn-link"
                            data-template="<div class=&quot;tooltip&quot; role=&quot;tooltip&quot;><div class=&quot;tooltip-inner bg-info-500&quot;></div></div>"
                            data-toggle="tooltip" data-id="{{ $employee->id }}" title="Edit Approval Line"
                            onclick="btnLink(event)">
                            <span class="fal fa-link ikon-link"></span>
                            <div class="span spinner-text d-none">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>

                            </div>
                        </button>
                        <button type="button" data-backdrop="static" data-keyboard="false" onclick="btnEditLocation(event)"
                            class="badge mx-1 btn-edit-map badge-warning p-2 border-0 text-white"
                            data-template="<div class=&quot;tooltip&quot; role=&quot;tooltip&quot;><div class=&quot;tooltip-inner text-white bg-warning-500&quot;></div></div>"
                            data-toggle="tooltip" data-id="{{ $employee->id }}" title="Set Lokasi Absen">
                            <span class="fal fa-map-marker-alt ikon-edit"></span>
                            <div class="span spinner-text d-none">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            </div>
                        </button>
                        <button type="button" data-backdrop="static" data-keyboard="false"
                            class="badge mx-1 badge-primary p-2 border-0 text-white btn-organisasi"
                            data-template="<div class=&quot;tooltip&quot; role=&quot;tooltip&quot;><div class=&quot;tooltip-inner bg-primary&quot;></div></div>"
                            data-toggle="tooltip" data-employee-id="{{ $employee->id }}"
                            data-id="{{ $employee->organization_id }}" title="Ubah Organisasi"
                            onclick="btnOrganisasi(event)">
                            <span class="fal fa-address-book ikon-organisasi"></span>
                            <div class="span spinner-text d-none">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            </div>
                        </button>
                    </td>
                </tr>
            @endforeach
        @else
            @foreach ($employees as $employee)
                <tr>
                    {{-- <td style="white-space: nowrap">{{ $user->template_user->foto }}</td> --}}
                    <td style="white-space: nowrap">{{ $loop->iteration }}</td>
                    <td style="white-space: nowrap">{{ $employee->fullname }}</td>
                    <td style="white-space: nowrap">
                        {{ $employee->company->name ?? '*belum di setting' }}</td>
                    <td style="white-space: nowrap">
                        {{ $employee->organization->name ?? '*belum di setting' }}</td>
                    <td style="white-space: nowrap">
                        {{ $employee->is_management ? 'Management' : 'Pelayanan' }}</td>
                    <td style="white-space: nowrap">
                        {{ $employee->jobPosition->name ?? '*belum di setting' }}</td>
                    <td style="white-space: nowrap">
                        {{ $employee->employment_status ?? '*belum di setting' }}</td>
                    <td style="white-space: nowrap">
                        {{ $employee->join_date }}
                    </td>
                    <td style="white-space: nowrap">
                        {{ $employee->end_status_date }}
                    </td>
                    <td style="white-space: nowrap">
                        {{ $employee->resign_date ?? '-' }}</td>
                    <td style="white-space: nowrap">
                        {{ $employee->birthdate ?? '-' }}</td>
                    <td style="white-space: nowrap">
                        {{ $employee->place_of_birth ?? '-' }}</td>
                    <td style="white-space: nowrap">
                        {{ $employee->residental_address ?? '-' }}</td>
                    <td style="white-space: nowrap">
                        {{ $employee->email ?? '-' }}</td>
                    <td style="white-space: nowrap">
                        {{ $employee->mobile_phone ?? '-' }}</td>
                    <td style="white-space: nowrap">
                        {{ $employee->religion ?? '-' }}</td>
                    <td style="white-space: nowrap">
                        {{ $employee->gender ?? '-' }}</td>
                    <td style="white-space: nowrap">
                        {{ $employee->marital_status ?? '-' }}</td>
                    <td style="white-space: nowrap">
                        {{ $employee->identity_type ?? '-' }}</td>
                    <td style="white-space: nowrap">
                        {{ $employee->identity_number ?? '-' }}</td>
                    <td style="white-space: nowrap">
                        <!-- Edit Button - Warna Kuning -->
                        <button onclick="openEditPopup({{ $employee->id }})" class="badge mx-1 p-2 border-0 text-white"
                            style="background-color: #FFC107;" title="Ubah Data Pegawai">
                            <i class="fal fa-pencil"></i>
                        </button>
                        <!-- Non Aktifkan Button - Warna Merah -->
                        <button type="button" data-backdrop="static" data-keyboard="false"
                            class="badge mx-1 p-2 border-0 text-white" style="background-color: #DC3545;"
                            data-template="<div class=&quot;tooltip&quot; role=&quot;tooltip&quot;><div class=&quot;tooltip-inner bg-danger-500&quot;></div></div>"
                            data-toggle="tooltip" data-id="{{ $employee->id }}" title="Non-aktifkan"
                            onclick="btnNonAktifPegawai(event)">
                            <span class="fal fa-eye-slash ikon-non-aktif"></span>
                            <div class="span spinner-text d-none">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            </div>
                        </button>
                        <!-- Edit Approval Line - Warna Biru -->
                        <button type="button" data-backdrop="static" data-keyboard="false"
                            class="badge mx-1 p-2 border-0 text-white" style="background-color: #17A2B8;"
                            data-template="<div class=&quot;tooltip&quot; role=&quot;tooltip&quot;><div class=&quot;tooltip-inner bg-info-500&quot;></div></div>"
                            data-toggle="tooltip" data-id="{{ $employee->id }}" title="Edit Approval Line"
                            onclick="btnLink(event)">
                            <span class="fal fa-link ikon-link"></span>
                            <div class="span spinner-text d-none">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            </div>
                        </button>
                        <!-- Set Lokasi Absen - Warna Orange -->
                        <button type="button" data-backdrop="static" data-keyboard="false"
                            onclick="btnEditLocation(event)" class="badge mx-1 p-2 border-0 text-white"
                            style="background-color: #FF9800;"
                            data-template="<div class=&quot;tooltip&quot; role=&quot;tooltip&quot;><div class=&quot;tooltip-inner text-white bg-warning-500&quot;></div></div>"
                            data-toggle="tooltip" data-id="{{ $employee->id }}" title="Set Lokasi Absen">
                            <span class="fal fa-map-marker-alt ikon-edit"></span>
                            <div class="span spinner-text d-none">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            </div>
                        </button>
                        <!-- Ubah Organisasi - Warna Ungu -->
                        <button type="button" data-backdrop="static" data-keyboard="false"
                            class="badge mx-1 p-2 border-0 text-white" style="background-color: #6F42C1;"
                            data-template="<div class=&quot;tooltip&quot; role=&quot;tooltip&quot;><div class=&quot;tooltip-inner bg-primary&quot;></div></div>"
                            data-toggle="tooltip" data-employee-id="{{ $employee->id }}"
                            data-id="{{ $employee->organization_id }}" title="Ubah Organisasi"
                            onclick="btnOrganisasi(event)">
                            <span class="fal fa-address-book ikon-organisasi"></span>
                            <div class="span spinner-text d-none">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            </div>
                        </button>
                        <!-- Toggle Manajemen - Warna Hijau -->
                        <button type="button" class="badge mx-1 p-2 border-0 text-white"
                            style="background-color: #28A745;" data-id="{{ $employee->id }}"
                            data-status="{{ $employee->is_management }}" title="Toggle Manajemen">
                            <span class="fas fa-user-secret"></span>
                        </button>
                        <!-- Tanda Tangan - Warna Abu (ganti icon jadi fa-link, samakan dengan lain) -->
                        <button type="button" class="badge mx-1 p-2 border-0 text-white"
                            style="background-color: #6c757d;" title="Input Tanda Tangan"
                            onclick="openSignaturePopup({{ $employee->id }})">
                            <span class="fal fa-link"></span>
                        </button>
                    </td>
                </tr>
            @endforeach
        @endisset
    </tbody>
    <tfoot>
        <tr>
            <th style="white-space: nowrap">No</th>
            <th style="white-space: nowrap">Nama</th>
            <th style="white-space: nowrap">Perusahaan</th>
            <th style="white-space: nowrap">Unit</th>
            <th style="white-space: nowrap">Status</th>
            <th style="white-space: nowrap">Jabatan</th>
            <th style="white-space: nowrap">Status Shift</th>
            <th style="white-space: nowrap">Mulai Kontrak</th>
            <th style="white-space: nowrap">Akhir Kontrak</th>
            <th style="white-space: nowrap">Resign</th>
            <th style="white-space: nowrap">Tgl. Lahir</th>
            <th style="white-space: nowrap">Tempat Lahir</th>
            <th style="white-space: nowrap">Alamat</th>
            <th style="white-space: nowrap">Email</th>
            <th style="white-space: nowrap">No. Hp</th>
            <th style="white-space: nowrap">Agama</th>
            <th style="white-space: nowrap">Jenis Kelamin</th>
            <th style="white-space: nowrap">Status Menikah</th>
            <th style="white-space: nowrap">Tipe Identitas</th>
            <th style="white-space: nowrap">Nomor Identitas</th>
            <th style="white-space: nowrap">Aksi</th>
        </tr>
    </tfoot>
</table>
<!-- datatable end -->
