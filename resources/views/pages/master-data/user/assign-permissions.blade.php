@extends('inc.layout')
@section('title', 'User Akses')
@section('extended-css')
    <style>
        .custom-control-input:checked~.custom-control-label::before {
            background: #fd3995;
            border-color: #cc2875;
        }

        .list-unstyled {
            list-style-type: none;
            /* Removes bullets */
            padding-left: 0;
            /* Removes default padding */
        }

        .list-unstyled li {
            margin-bottom: 0.5rem;
            /* Optional: Adds some space between list items */
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
                            Daftar Permissions <code>{{ $user_name }}</code>
                        </h2>
                    </div>

                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="panel-tag">
                                Ceklis tombol dibawah untuk memberi akses pada user
                                <code>{{ $user_name }}</code>
                            </div>
                            <form action="#" method="POST" id="assign-permissions-form">
                                @csrf
                                <div class="accordion accordion-outline" id="js_demo_accordion-3">
                                    @foreach ($permissions as $group => $groupPermissions)
                                        <div class="card">
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <div class="custom-control custom-checkbox" style="margin-left: 15px;">
                                                    <input type="checkbox" class="custom-control-input group-checkbox"
                                                        id="group-{{ strtolower(str_replace(' ', '-', $group)) }}"
                                                        data-group="{{ strtolower(str_replace(' ', '-', $group)) }}">
                                                    <label class="custom-control-label"
                                                        for="group-{{ strtolower(str_replace(' ', '-', $group)) }}">{{ $group }}</label>
                                                </div>
                                                <a href="javascript:void(0);" class="card-title" data-toggle="collapse"
                                                    data-target="#js_demo_accordion-{{ strtolower(str_replace(' ', '-', $group)) }}"
                                                    aria-expanded="true">
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
                                            <div id="js_demo_accordion-{{ strtolower(str_replace(' ', '-', $group)) }}"
                                                class="collapse show" data-parent="#js_demo_accordion-3">
                                                <div class="card-body">
                                                    <div class="row">
                                                        @foreach ($groupPermissions as $permission)
                                                            <div class="col-md-4"> <!-- Three columns -->
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox"
                                                                        class="custom-control-input permission-checkbox"
                                                                        id="permission-{{ $permission->id }}"
                                                                        name="permissions[]" value="{{ $permission->id }}"
                                                                        data-group="{{ strtolower(str_replace(' ', '-', $group)) }}"
                                                                        {{ in_array($permission->id, $userPermissions) ? 'checked' : '' }}>
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
                                <button type="submit" class="btn btn-primary mr-auto" id="button-simpan">
                                    <div class="ikon-tambah">
                                        <span class="fal fa-plus-circle mr-1"></span>
                                        Tambah
                                    </div>
                                    <div class="span spinner-text d-none">
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                        Loading...
                                    </div>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>

    <script>
        function updateGroupCheckbox(group) {
            const allChecked = $(`.permission-checkbox[data-group="${group}"]`).length === $(
                `.permission-checkbox[data-group="${group}"]:checked`).length;
            $(`#group-${group}`).prop('checked', allChecked);
        }

        $(document).ready(function() {
            $('.group-checkbox').on('change', function() {
                const isChecked = $(this).is(':checked');
                const groupId = $(this).attr('id').replace('group-', ''); // Extract group id

                // Check/uncheck all permissions in this group
                $(`#js_demo_accordion-${groupId} .custom-control-input`).prop('checked', isChecked);
            });

            // Initially update all group checkboxes
            $('.group-checkbox').each(function() {
                const group = $(this).data('group');
                updateGroupCheckbox(group);
            });

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

            // Initially update all group checkboxes
            $('.group-checkbox').each(function() {
                const group = $(this).data('group');
                updateGroupCheckbox(group);
            });

            // When an individual permission checkbox is clicked
            $('.permission-checkbox').on('change', function() {
                const group = $(this).data('group');
                updateGroupCheckbox(group);
            });
            // Pastikan form menggunakan method POST secara native
            $('#assign-permissions-form').attr('method', 'post');

            $('#assign-permissions-form').on('submit', async function(event) {
                event.preventDefault(); // Prevent the default form submission

                // Serialize the form data
                let formDataArray = $(this).serializeArray();

                // Append the user_id to the form data
                formDataArray.push({
                    name: 'user_id',
                    value: @json($user_id)
                });

                const formData = $.param(formDataArray);

                // Show spinner and hide the add icon
                $('#assign-permissions-form').find('.ikon-tambah').hide();
                $('#assign-permissions-form').find('.spinner-text').removeClass('d-none');

                try {
                    // Make the AJAX request using fetch with POST method to the named route 'store.permission'
                    const response = await fetch('{{ route('store.permission') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: formData,
                    });

                    // Parsing response JSON
                    const result = await response.json();

                    // Hide spinner and show add icon
                    $('#assign-permissions-form').find('.ikon-tambah').show();
                    $('#assign-permissions-form').find('.spinner-text').addClass('d-none');

                    // Check if response is ok
                    if (!response.ok) {
                        throw new Error(result.message || 'An error occurred, please try again.');
                    }

                    // Show success alert and reload page
                    showSuccessAlert(result.message);
                    $('#tambah-data').modal('hide');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);

                } catch (error) {
                    // Handle the error response
                    $('#assign-permissions-form').find('.ikon-tambah').show();
                    $('#assign-permissions-form').find('.spinner-text').addClass('d-none');
                    showErrorAlert(error.message);
                }
            });
        });
    </script>

@endsection
