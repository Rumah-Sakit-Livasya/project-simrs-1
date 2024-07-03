<div class="tab-pane fade show active" id="v-pills-personal" role="tabpanel" aria-labelledby="v-pills-personal-tab">
    <div class="border px-3 pt-3 pb-0 rounded">
        <ul class="nav nav-pills" role="tablist">
            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#personal">Info
                    Personal</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#identitas">Identitas &amp;
                    Alamat</a></li>
        </ul>
        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="personal" role="tabpanel">
                <h3>
                    Info Personal
                </h3>
                <hr>
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
                                    <div class="col-sm-8">+{{ $employee->mobile_phone }}
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
                                        <p class="d-flex">
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
                                        <p>{{ $employee->marital_status }}</p>
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
                                        <p>{{ $employee->blood_type }}</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="row py-2">
                                    <div class="col-sm-4 d-flex align-items-center">
                                        <span class="font-weight-bold">Agama</span>
                                    </div>
                                    <div class="col-sm-8">
                                        <p>{{ $employee->religion }}</p>
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
                <h3>
                    Identitas &amp; Alamat
                </h3>
                <hr>
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
                                        <p>{{ $employee->identity_type }}</p>
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
                                        <p>{{ $employee->identity_number }}</p>
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
                                        <p>{{ $employee->identity_expire_date ?? 'Permanen' }}</p>
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
                                        <p>{{ $employee->postal_code }}</p>
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
                                        <p>{{ $employee->citizen_id_address }}</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="row py-2">
                                    <div class="col-sm-4 d-flex align-items-center">
                                        <span class="font-weight-bold">Tempat Tinggal</span>
                                    </div>
                                    <div class="col-sm-8">
                                        <p>{{ $employee->residental_address }}</p>
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
            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#pekerjaan">Info
                    Pekerjaan</a>
            </li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#subordinate">Subordinate</a></li>
        </ul>
        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="pekerjaan" role="tabpanel">
                <h3>
                    Info Pekerjaan
                </h3>
                <hr>
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
                                        <p>
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
                                        <p>{{ $employee->employee_code ?? '*belum disetting' }}</p>
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
                                        <p>{{ $employee->barcode ?? '*belum disetting' }}</p>
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
                                        <p>
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
                                        <p>
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
                                        <p>
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
                                        <p>
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
                                        <p class="d-flex">
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
                                        <p class="d-flex">
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
                                        <p>
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
                                        <p>
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
                                        <p>
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
                                        <p>
                                            Tidak ada manajer
                                        </p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="col">
                        <button type="button" class="d-inline-flex btn btn-basic btn-sm">Sunting</button>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="subordinate" role="tabpanel">
                <h3>
                    Subordinate
                </h3>
                <hr>

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
