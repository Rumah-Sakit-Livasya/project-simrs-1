<div class="card border mt-3">
    <div class="card-header py-2">
        <div class="card-title">
            {{ $organization->name }}
        </div>
    </div>
    <div class="card-body">
        <div class="d-flex flex-wrap demo demo-h-spacing mt-3 mb-3">
            @foreach ($organization->employees as $employee)
                <div class="rounded-pill bg-white shadow-sm p-2 border-faded mr-3 d-flex align-items-center">
                    <a href="{{ $employee->foto && Storage::exists('employee/profile/' . $employee->foto)
                        ? asset('storage/employee/profile/' . $employee->foto)
                        : ($employee->gender == 'Laki-laki'
                            ? '/img/demo/avatars/avatar-c.png'
                            : '/img/demo/avatars/avatar-p.png') }}"
                        data-lightbox="employee-{{ $organization->id }}" data-title="{{ $employee->fullname }}">

                        <img src="{{ $employee->foto && Storage::exists('employee/profile/' . $employee->foto)
                            ? asset('storage/employee/profile/' . $employee->foto)
                            : ($employee->gender == 'Laki-laki'
                                ? '/img/demo/avatars/avatar-c.png'
                                : '/img/demo/avatars/avatar-p.png') }}"
                            alt="{{ $employee->fullname }}" class="img-thumbnail rounded-circle"
                            style="width: 5rem; height: 5rem; object-fit: cover;">
                    </a>

                    <div class="ml-2">
                        <h5 class="m-0">{{ $employee->fullname }} ({{ $employee->jobLevel->name }})</h5>
                        <small>{{ $employee->jobPosition->name }}</small><br>
                        <a href="https://wa.me/{{ formatNomorIndo($employee->mobile_phone) }}" target="_blank"
                            class="text-info fs-sm">
                            +{{ formatNomorIndo($employee->mobile_phone) }}
                        </a> -
                        <a href="mailto:{{ $employee->email }}" target="_blank" class="text-info fs-sm">
                            <i class="fal fa-envelope"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Tampilkan anak-anak organisasi --}}
@if (!empty($organization->child_nodes))
    <div class="ml-4">
        @foreach ($organization->child_nodes as $child)
            @include('pages.pegawai.team.partials.organization-block', ['organization' => $child])
        @endforeach
    </div>
@endif
