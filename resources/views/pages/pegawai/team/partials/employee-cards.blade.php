@foreach ($organizations as $organization)
    @if ($organization->employees->isNotEmpty())
        <div class="card border mt-3">
            <div class="card-header py-2">
                <div class="card-title">
                    {{ $organization->name }}
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap demo demo-h-spacing mt-3 mb-3">
                    @foreach ($organization->employees as $employee)
                        <div
                            class="rounded-pill bg-white shadow-sm p-2 border-faded mr-3 d-flex flex-row align-items-center justify-content-center flex-shrink-0">
                            @if ($employee->foto != null && Storage::exists('employee/profile/' . $employee->foto))
                                <img src="{{ asset('storage/employee/profile/' . $employee->foto) }}"
                                    alt="{{ $employee->fullname }}" class="img-thumbnail img-responsive rounded-circle"
                                    style="width:5rem; height: 5rem; object-fit: cover; z-index:100;">
                            @else
                                <img src="{{ $employee->gender == 'Laki-laki' ? '/img/demo/avatars/avatar-c.png' : '/img/demo/avatars/avatar-p.png' }}"
                                    alt="{{ $employee->fullname }}" class="img-thumbnail img-responsive rounded-circle"
                                    style="width:5rem; height: 5rem; z-index:100;">
                            @endif
                            <div class="ml-2 mr-3">
                                <h5 class="m-0">
                                    {{ $employee->fullname }} ({{ $employee->jobLevel->name }})
                                    <small class="m-0 fw-300">
                                        {{ $employee->jobPosition->name }}
                                    </small>
                                </h5>
                                <a href="https://wa.me/{{ formatNomorIndo($employee->mobile_phone) }}"
                                    class="text-info fs-sm" target="_blank">
                                    +{{ formatNomorIndo($employee->mobile_phone) }}
                                </a> -
                                <a href="mailto:{{ $employee->email }}" class="text-info fs-sm" target="_blank"
                                    title="Contact {{ $employee->fullname }}">
                                    <i class="fal fa-envelope"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endforeach
