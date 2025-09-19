@extends('inc.layout')
@section('title', 'Role Permissions')
@section('extended-css')
    <style>
        .custom-control-input:checked~.custom-control-label::before {
            background: #fd3995;
            border-color: #cc2875;
        }
    </style>
@endsection
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Daftar Permissions untuk Role: <code>{{ ucfirst($role->name) }}</code>
                        </h2>
                    </div>

                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="panel-tag">
                                Centang izin di bawah untuk memberikannya pada role
                                <code>{{ ucfirst($role->name) }}</code>.
                            </div>
                            <form action="{{ route('roles.syncPermissions', $role->id) }}" method="POST"
                                id="assign-permissions-form">
                                @csrf
                                <div class="accordion accordion-outline" id="permission-accordion">
                                    {{-- Loop berdasarkan grup yang sudah benar dari controller --}}
                                    @foreach ($permissionsByGroup as $group => $groupPermissions)
                                        <div class="card">
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <div class="custom-control custom-checkbox ml-3">
                                                    <input type="checkbox" class="custom-control-input group-checkbox"
                                                        id="group-{{ Str::slug($group) }}"
                                                        data-group="{{ Str::slug($group) }}">
                                                    <label class="custom-control-label"
                                                        for="group-{{ Str::slug($group) }}">{{ $group }}</label>
                                                </div>
                                                <a href="javascript:void(0);" class="card-title" data-toggle="collapse"
                                                    data-target="#collapse-{{ Str::slug($group) }}" aria-expanded="true">
                                                    <span class="ml-auto">
                                                        <span class="collapsed-reveal">
                                                            <i class="fal fa-minus fs-xl"></i>
                                                        </span>
                                                        <span class="collapsed-hidden">
                                                            <i class="fal fa-plus fs-xl"></i>
                                                        </span>
                                                    </span>
                                                </a>
                                            </div>
                                            <div id="collapse-{{ Str::slug($group) }}" class="collapse show"
                                                data-parent="#permission-accordion">
                                                <div class="card-body">
                                                    <div class="row">
                                                        @foreach ($groupPermissions as $permission)
                                                            <div class="col-md-4">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox"
                                                                        class="custom-control-input permission-checkbox"
                                                                        id="permission-{{ $permission->id }}"
                                                                        name="permissions[]" value="{{ $permission->id }}"
                                                                        data-group="{{ Str::slug($group) }}"
                                                                        {{-- Logika ini sekarang akan bekerja dengan benar --}}
                                                                        {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                                                    <label class="custom-control-label"
                                                                        for="permission-{{ $permission->id }}">{{ $permission->name }}</label>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-4">
                                    <a href="{{ route('roles.index') }}" class="btn btn-secondary">Kembali</a>
                                    <button type="submit" class="btn btn-primary" id="button-simpan">
                                        <span class="ikon-simpan">
                                            <i class="fal fa-save mr-1"></i>
                                            Simpan Perubahan
                                        </span>
                                        <span class="spinner-text d-none">
                                            <span class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true"></span>
                                            Loading...
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('plugin')
    {{-- Script JavaScript tidak perlu diubah sama sekali --}}
    <script>
        $(document).ready(function() {
            function updateGroupCheckbox(group) {
                const $groupCheckbox = $(`#group-${group}`);
                const $permissions = $(`.permission-checkbox[data-group="${group}"]`);
                const total = $permissions.length;
                const checked = $permissions.filter(':checked').length;

                if (checked === 0) {
                    $groupCheckbox.prop('checked', false).prop('indeterminate', false);
                } else if (checked === total) {
                    $groupCheckbox.prop('checked', true).prop('indeterminate', false);
                } else {
                    $groupCheckbox.prop('checked', false).prop('indeterminate', true);
                }
            }

            $('.group-checkbox').each(function() {
                const group = $(this).data('group');
                updateGroupCheckbox(group);
            });

            $('.group-checkbox').on('change', function() {
                const group = $(this).data('group');
                const isChecked = $(this).is(':checked');
                $(`.permission-checkbox[data-group="${group}"]`).prop('checked', isChecked);
            });

            $('.permission-checkbox').on('change', function() {
                const group = $(this).data('group');
                updateGroupCheckbox(group);
            });

            $('#assign-permissions-form').on('submit', async function(event) {
                event.preventDefault();

                const form = $(this);
                const button = form.find('#button-simpan');
                const formData = form.serialize();
                const url = form.attr('action');

                button.prop('disabled', true);
                button.find('.ikon-simpan').addClass('d-none');
                button.find('.spinner-text').removeClass('d-none');

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: formData,
                    });

                    const result = await response.json();

                    if (!response.ok) {
                        throw new Error(result.message || 'Terjadi kesalahan.');
                    }

                    showSuccessAlert(result.message);
                    setTimeout(function() {
                        window.location.href = '{{ route('roles.index') }}';
                    }, 1500);

                } catch (error) {
                    showErrorAlert(error.message);
                } finally {
                    button.prop('disabled', false);
                    button.find('.ikon-simpan').removeClass('d-none');
                    button.find('.spinner-text').addClass('d-none');
                }
            });
        });
    </script>
@endsection
