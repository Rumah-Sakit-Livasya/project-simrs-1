<div class="tab-pane fade show active" id="v-pills-personal" role="tabpanel" aria-labelledby="v-pills-personal-tab">
    <div class="border px-3 pt-3 pb-0 rounded">
        <ul class="nav nav-pills" role="tablist">
            <li class="nav-item" style="margin-left: -4px;"><a class="nav-link active" data-toggle="tab"
                    href="#personal">Info
                    Personal</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#identitas">Identitas &amp;
                    Alamat</a></li>
        </ul>
        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="personal" role="tabpanel">
                <h3 class="mt-3">
                    Info Personal
                </h3>
                <hr class="my-1">
                <div class="row">
                    <div class="col-10">
                        <ul class="list-unstyled mb-0">
                            <li>
                                <div class="row py-2">
                                    <div class="col-sm-4 d-flex align-items-center">
                                        <span class="font-weight-bold">Nama
                                            Lengkap</span>
                                    </div>
                                    <div class="col-sm-8">{{ $employee->fullname }}</div>
                                </div>
                            </li>
                            <li>
                                <div class="row py-2">
                                    <div class="col-sm-4 d-flex align-items-center">
                                        <span class="font-weight-bold">No
                                            HP</span>
                                    </div>
                                    <div class="col-sm-8">{{ $employee->mobile_phone }}
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="row py-2">
                                    <div class="col-sm-4 d-flex align-items-center">
                                        <span class="font-weight-bold">Email</span>
                                    </div>
                                    <div class="col-sm-8">
                                        {{ $employee->email }}
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="row py-2">
                                    <div class="col-sm-4 d-flex align-items-center">
                                        <span class="font-weight-bold">Tempat
                                            Lahir</span>
                                    </div>
                                    <div class="col-sm-8">{{ $employee->place_of_birth }}</div>
                                </div>
                            </li>
                            <li>
                                <div class="row py-2">
                                    <div class="col-sm-4 d-flex align-items-center">
                                        <span class="font-weight-bold">Tanggal
                                            Lahir</span>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="d-flex mb-0">
                                            {{ tgl($employee->birthdate) }}
                                            <span
                                                class="ml-2 py-0 align-self-center badge badge-secondary p-1">{{ hitungUmur($employee->birthdate) }}</span>
                                        </p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="row py-2">
                                    <div class="col-sm-4 d-flex align-items-center">
                                        <span class="font-weight-bold">Jenis
                                            Kelamin</span>
                                    </div>
                                    <div class="col-sm-8">{{ $employee->gender }}</div>
                                </div>
                            </li>
                            <li>
                                <div class="row py-2">
                                    <div class="col-sm-4 d-flex align-items-center">
                                        <span class="font-weight-bold">Status
                                            Pernikahan</span>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0">{{ $employee->marital_status }}</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="row py-2">
                                    <div class="col-sm-4 d-flex align-items-center">
                                        <span class="font-weight-bold">Golongan
                                            Darah</span>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0">{{ $employee->blood_type }}</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="row py-2">
                                    <div class="col-sm-4 d-flex align-items-center">
                                        <span class="font-weight-bold">Agama</span>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0">{{ $employee->religion }}</p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="col">
                        <button type="button" data-backdrop="static" data-keyboard="false"
                            class="badge mx-1 badge-success p-2 border-0 text-white btn-ubah-personal"
                            data-id="{{ $employee->id }}" title="Ubah">
                            <i class="fal fa-pencil-alt mr-1 ikon-edit"></i>
                            <div class="span spinner-text d-none">
                                <span class="spinner-border spinner-border-sm " role="status"
                                    aria-hidden="true"></span>
                                Loading...
                            </div>
                        </button>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="identitas" role="tabpanel">
                <h3 class="mt-3">
                    Identitas & Alamat
                </h3>
                <hr class="my-1">
                <div class="row">
                    <div class="col-10">
                        <ul class="list-unstyled mb-0">
                            <li>
                                <div class="row py-2">
                                    <div class="col-sm-4 d-flex align-items-center">
                                        <span class="font-weight-bold">Tipe
                                            ID</span>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0">{{ $employee->identity_type }}</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="row py-2">
                                    <div class="col-sm-4 d-flex align-items-center">
                                        <span class="font-weight-bold">No
                                            ID</span>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0">{{ $employee->identity_number }}</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="row py-2">
                                    <div class="col-sm-4 d-flex align-items-center">
                                        <span class="font-weight-bold">Tanggal
                                            kedaluwarsa ID</span>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0">{{ $employee->identity_expire_date ?? 'Permanen' }}</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="row py-2">
                                    <div class="col-sm-4 d-flex align-items-center">
                                        <span class="font-weight-bold">Kode
                                            Pos</span>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0">{{ $employee->postal_code }}</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="row py-2">
                                    <div class="col-sm-4 d-flex align-items-center">
                                        <span class="font-weight-bold">Alamat
                                            KTP</span>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0">{{ $employee->citizen_id_address }}</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="row py-2">
                                    <div class="col-sm-4 d-flex align-items-center">
                                        <span class="font-weight-bold">Tempat Tinggal</span>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0">{{ $employee->residental_address }}</p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="col">
                        <button type="button" data-backdrop="static" data-keyboard="false"
                            class="badge mx-1 badge-success p-2 border-0 text-white btn-ubah-identitas"
                            data-id="{{ $employee->id }}" title="Ubah">
                            <i class="fal fa-pencil-alt mr-1 ikon-edit"></i>
                            <div class="span spinner-text d-none">
                                <span class="spinner-border spinner-border-sm " role="status"
                                    aria-hidden="true"></span>
                                Loading...
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="tab-pane fade" id="v-pills-pekerjaan" role="tabpanel" aria-labelledby="v-pills-pekerjaan-tab">
    <div class="border px-3 pt-3 pb-0 rounded">
        <ul class="nav nav-pills" role="tablist">
            <li class="nav-item" style="margin-left: -4px"><a class="nav-link active" data-toggle="tab"
                    href="#pekerjaan">Info
                    Pekerjaan</a>
            </li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#subordinate">Subordinate</a></li>
        </ul>
        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="pekerjaan" role="tabpanel">
                <h3 class="mt-3">
                    Info Pekerjaan
                </h3>
                <hr class="my-1">
                <div class="row">
                    <div class="col-10">
                        <ul class="list-unstyled mb-0">
                            <li>
                                <div class="row py-2">
                                    <div class="col-sm-4 d-flex align-items-center">
                                        <span class="font-weight-bold">
                                            Nama Perusahaan
                                        </span>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0">
                                            {{ $employee->company->name }}
                                        </p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="row py-2">
                                    <div class="col-sm-4 d-flex align-items-center">
                                        <span class="font-weight-bold">
                                            identitas pegawai
                                        </span>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0">{{ $employee->employee_code ?? '*belum disetting' }}</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="row py-2">
                                    <div class="col-sm-4 d-flex align-items-center">
                                        <span class="font-weight-bold">
                                            Barcode
                                        </span>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0">{{ $employee->barcode ?? '*belum disetting' }}</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="row py-2">
                                    <div class="col-sm-4 d-flex align-items-center">
                                        <span class="font-weight-bold">
                                            Nama Organisasi
                                        </span>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0">
                                            {{ $employee->organization->name }}
                                        </p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="row py-2">
                                    <div class="col-sm-4 d-flex align-items-center">
                                        <span class="font-weight-bold">
                                            Posisi pekerjaan
                                        </span>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0">
                                            {{ $employee->jobPosition->name }}
                                        </p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="row py-2">
                                    <div class="col-sm-4 d-flex align-items-center">
                                        <span class="font-weight-bold">
                                            Tingkat pekerjaan
                                        </span>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0">
                                            {{ $employee->jobLevel->name }}
                                        </p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="row py-2">
                                    <div class="col-sm-4 d-flex align-items-center">
                                        <span class="font-weight-bold">
                                            Status Pekerjaan
                                        </span>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0">
                                            {{ $employee->employment_status }}
                                        </p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="row py-2">
                                    <div class="col-sm-4 d-flex align-items-center">
                                        <span class="font-weight-bold">
                                            Tanggal bergabung
                                        </span>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="d-flex mb-0">
                                            {{ tgl($employee->join_date) }}
                                            <span class="badge badge-secondary ml-2 py-0 align-self-center">
                                                {{ hitungHari($employee->join_date) }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="row py-2">
                                    <div class="col-sm-4 d-flex align-items-center">
                                        <span class="font-weight-bold">
                                            Tanggal akhir status
                                            pekerjaan
                                        </span>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="d-flex mb-0">
                                            {{ tgl($employee->end_status_date) }}
                                        </p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="row py-2">
                                    <div class="col-sm-4 d-flex align-items-center">
                                        <span class="font-weight-bold">
                                            Nilai
                                        </span>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0">
                                            -
                                        </p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="row py-2">
                                    <div class="col-sm-4 d-flex align-items-center">
                                        <span class="font-weight-bold">
                                            Kelas
                                        </span>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0">
                                            -
                                        </p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="row py-2">
                                    <div class="col-sm-4 d-flex align-items-center">
                                        <span class="font-weight-bold">
                                            Garis persetujuan
                                        </span>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0">
                                            @if (isset($approvalLine))
                                                {{ $approvalLine->employee_code }} - {{ $approvalLine->fullname }}
                                            @elseif(isset($approvalParent))
                                                {{ $approvalParent->employee_code }} - {{ $approvalParent->fullname }}
                                            @else
                                                <strong>*tidak ada approval</strong>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="row py-2">
                                    <div class="col-sm-4 d-flex align-items-center">

                                        <span class="font-weight-bold">Pengelola<i
                                                data-title="Manager will affect organization chart"
                                                class="ic ic-small ic-info-fill c-pointer"></i>
                                        </span>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0">
                                            Tidak ada manajer
                                        </p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="col">
                        <button type="button" data-backdrop="static" data-keyboard="false"
                            class="badge mx-1 badge-success p-2 border-0 text-white btn-ubah-pekerjaan"
                            data-id="{{ $employee->id }}" title="Ubah">
                            <i class="fal fa-pencil-alt mr-1 ikon-edit-pekerjaan"></i>
                            <div class="span spinner-text d-none">
                                <span class="spinner-border spinner-border-sm " role="status"
                                    aria-hidden="true"></span>
                                Loading...
                            </div>
                        </button>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="subordinate" role="tabpanel">
                <div class="row">
                    <div class="col-12">
                        <ul class="nav nav-pills justify-content-center" role="tablist">
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#approval">Approval
                                    Line</a>
                            </li>

                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#manager">Group</a>
                            </li>
                        </ul>
                        <div class="tab-content py-3">
                            <div class="tab-pane fade" id="approval" role="tabpanel">

                                <p class="d-block mt-3 text-center mb-2">
                                    Approval line Anda
                                </p>

                                @if (isset($approvalLine) || isset($approvalParent))
                                    @if (isset($approvalLine))
                                        <div
                                            class="card-body border-faded border-top-0 border-left-0 border-right-0 rounded-top">
                                            <div class="d-flex flex-row align-items-center">
                                                <span class="status status-success mr-3">
                                                    <span class="rounded-circle profile-image d-block "
                                                        style="background-image:url('/img/demo/avatars/avatar-a.png'); background-size: cover;"></span>
                                                </span>
                                                <div class="info-card-text flex-1">
                                                    <a href="javascript:void(0);"
                                                        class="fs-xl text-truncate text-truncate-lg text-info"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                        {{ isset($approvalLine) ? $approvalLine->fullname : '' }}
                                                        <i class="fal fa-angle-down d-inline-block ml-1 fs-md"></i>
                                                    </a>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">Send
                                                            Email</a>
                                                        <a class="dropdown-item" href="#">Create
                                                            Appointment</a>
                                                        <a class="dropdown-item" href="#">Block
                                                            User</a>
                                                    </div>
                                                    <span
                                                        class="text-truncate text-truncate-xl">{{ isset($approvalLine) ? $approvalLine->company->name : '' }}
                                                        |
                                                        {{ isset($approvalLine) ? $approvalLine->jobPosition->name : '' }}</span>
                                                </div>
                                                <button
                                                    class="js-expand-btn btn btn-sm btn-default d-none waves-effect waves-themed"
                                                    data-toggle="collapse"
                                                    data-target="#c_1 > .card-body + .card-body"
                                                    aria-expanded="false">
                                                    <span class="collapsed-hidden">+</span>
                                                    <span class="collapsed-reveal">-</span>
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                    @if (isset($approvalParent))
                                        <div
                                            class="card-body border-faded border-top-0 border-left-0 border-right-0 rounded-top">
                                            <div class="d-flex flex-row align-items-center">
                                                <span class="status status-success mr-3">
                                                    <span class="rounded-circle profile-image d-block "
                                                        style="background-image:url('/img/demo/avatars/avatar-b.png'); background-size: cover;"></span>
                                                </span>
                                                <div class="info-card-text flex-1">
                                                    <a href="javascript:void(0);"
                                                        class="fs-xl text-truncate text-truncate-lg text-info"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                        {{ isset($approvalParent) ? $approvalParent->fullname : '' }}
                                                        <i class="fal fa-angle-down d-inline-block ml-1 fs-md"></i>
                                                    </a>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">Send
                                                            Email</a>
                                                        <a class="dropdown-item" href="#">Create
                                                            Appointment</a>
                                                        <a class="dropdown-item" href="#">Block
                                                            User</a>
                                                    </div>
                                                    <span
                                                        class="text-truncate text-truncate-xl">{{ isset($approvalParent) ? $approvalParent->company->name : '' }}
                                                        |
                                                        {{ isset($approvalParent) ? $approvalParent->jobPosition->name : '' }}</span>
                                                </div>
                                                <button
                                                    class="js-expand-btn btn btn-sm btn-default d-none waves-effect waves-themed"
                                                    data-toggle="collapse"
                                                    data-target="#c_1 > .card-body + .card-body"
                                                    aria-expanded="false">
                                                    <span class="collapsed-hidden">+</span>
                                                    <span class="collapsed-reveal">-</span>
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                            <div class="tab-pane fade" id="manager" role="tabpanel">

                                <p class="d-block mt-3 text-center mb-2">
                                    Group Bagian Anda
                                </p>

                                @foreach ($employeeGroup as $employe)
                                    <div
                                        class="card-body border-faded border-top-0 border-left-0 border-right-0 rounded-top">
                                        <div class="d-flex flex-row align-items-center">
                                            <span class="status status-success mr-3">
                                                <span class="rounded-circle profile-image d-block "
                                                    style="background-image:url('/img/demo/avatars/avatar-c.png'); background-size: cover;"></span>
                                            </span>


                                            <div class="info-card-text flex-1">
                                                <a href="javascript:void(0);"
                                                    class="fs-xl text-truncate text-truncate-lg text-info"
                                                    data-toggle="dropdown" aria-expanded="false">
                                                    {{ $employe->fullname }}
                                                    <i class="fal fa-angle-down d-inline-block ml-1 fs-md"></i>
                                                </a>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="#">Send
                                                        Email</a>
                                                    <a class="dropdown-item" href="#">Create
                                                        Appointment</a>
                                                    <a class="dropdown-item" href="#">Block
                                                        User</a>
                                                </div>
                                                <span class="text-truncate text-truncate-xl">
                                                    {{ $employe->company->name }} |
                                                    {{ $employe->jobPosition->name }}</span>
                                            </div>


                                            <button
                                                class="js-expand-btn btn btn-sm btn-default d-none waves-effect waves-themed"
                                                data-toggle="collapse" data-target="#c_1 > .card-body + .card-body"
                                                aria-expanded="false">
                                                <span class="collapsed-hidden">+</span>
                                                <span class="collapsed-reveal">-</span>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="tab-pane fade" id="v-pills-dokumen-kepegawaian" role="tabpanel"
    aria-labelledby="v-pills-dokumen-kepegawaian-tab">
    <div class="border px-3 pt-3 pb-0 rounded">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="pekerjaan" role="tabpanel">
                <h3 class="font-weight-bold text-primary">
                    Dokumen Kepegawaian
                </h3>
                <hr class="mt-3" style="border-color:#fd3995">
                <div class="row">
                    <div class="col-xl-12 my-2">
                        <a href="javascript:void(0);" id="tambah-dokumen" class="btn btn-primary btn-sm mb-4 ">Tambah
                            Dokumen</a>
                        <!-- datatable start -->
                        <div class="table-responsive">
                            <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                <thead>
                                    <tr>
                                        <!-- <th style="white-space: nowrap">Foto</th> -->
                                        <th style="white-space: nowrap">Nama</th>
                                        <th style="white-space: nowrap">File</th>
                                        <th style="white-space: nowrap">Masa Berlaku</th>
                                        <th style="white-space: nowrap">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($upload_files as $item)
                                        <tr>
                                            <td>{{ $item->nama }}</td>
                                            <td>
                                                @if (!empty($item->file))
                                                    <a href="{{ asset('storage/uploads/' . $item->file) }}"
                                                        target="_blank" class="text-primary text-underline">Lihat
                                                        dokumen</a>
                                                @else
                                                    <span class="text-danger">Tidak ada file</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $item->expire ? \Carbon\Carbon::parse($item->expire)->locale('id')->isoFormat('DD MMMM YYYY') : 'Tidak ada' }}
                                            </td>
                                            <td>
                                                <a href="#" data-backdrop="static" data-keyboard="false"
                                                    class="badge mx-1 badge-success p-2 border-0 text-white btn-edit-dokumen"
                                                    data-template="<div class=&quot;tooltip&quot; role=&quot;tooltip&quot;><div class=&quot;tooltip-inner bg-success-500&quot;></div></div>"
                                                    data-toggle="tooltip" data-id="{{ $item->id }}"
                                                    title="Edit">
                                                    <span class="fal fa-pencil ikon-edit-dokumen"></span>
                                                    <div class="span spinner-text d-none">
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span>
                                                        Loading...
                                                    </div>
                                                </a>
                                                <a href="#" data-backdrop="static" data-keyboard="false"
                                                    class="badge mx-1 badge-danger p-2 border-0 text-white btn-delete-dokumen"
                                                    data-template="<div class=&quot;tooltip&quot; role=&quot;tooltip&quot;><div class=&quot;tooltip-inner bg-danger-500&quot;></div></div>"
                                                    data-toggle="tooltip" data-id="{{ $item->id }}"
                                                    title="Delete">
                                                    <span class="fal fa-trash ikon-delete-dokumen"></span>
                                                    <div class="span spinner-text d-none">
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span>
                                                        Loading...
                                                    </div>
                                                </a>
                                                <a href="javascript:void(0)"
                                                    class="badge mx-1 badge-info p-2 border-0 text-white btn-download-dokumen"
                                                    data-template="<div class=&quot;tooltip&quot; role=&quot;tooltip&quot;><div class=&quot;tooltip-inner bg-info-500&quot;></div></div>"
                                                    data-toggle="tooltip" data-id="{{ $item->id }}"
                                                    title="Download">
                                                    <span class="fal fa-download ikon-download-dokumen"></span>
                                                    <div class="span spinner-text d-none">
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span>
                                                        Loading...
                                                    </div>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th style="white-space: nowrap">Nama</th>
                                        <th style="white-space: nowrap">File</th>
                                        <th style="white-space: nowrap">Masa Berlaku</th>
                                        <th style="white-space: nowrap">Aksi</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- datatable end -->

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
