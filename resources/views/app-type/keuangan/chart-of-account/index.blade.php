@extends('inc.layout')
@section('title', 'Chart of Account')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row mb-3">
            <div class="col-xl-12 d-flex justify-content-between">
                <div>
                    @foreach ($groupCOA as $group)
                        <button class="btn btn-outline-primary btn-group-filter"
                            data-group-id="{{ $group->id }}">{{ $group->name }}</button>
                    @endforeach
                </div>
                <div>
                    <button type="button" id="btn-tambah" class="btn btn-outline-primary" data-backdrop="static"
                        data-keyboard="false" data-toggle="modal" data-target="#tambah-coa" title="Tambah COA">
                        <span class="fal fa-plus-circle mr-1"></span>
                        Tambah COA
                    </button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Peta Hirarki COA
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div id="treeview"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @include('app-type.keuangan.chart-of-account.partials.create-coa')
    @include('app-type.keuangan.chart-of-account.partials.update-coa')
@endsection
@section('plugin')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-treeview@1.2.0/dist/bootstrap-treeview.min.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: 'Pilih Opsi',
                dropdownParent: $('#tambah-coa'),
            });

            // Event listener untuk submit form tambah COA
            $('#store-form').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('chart-of-account.store') }}",
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            $('#tambah-coa').modal('hide');
                            $('#store-form')[0].reset();
                            showSuccessAlert(response.message || 'COA berhasil ditambahkan');
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                            loadCoaByGroup(1); // Refresh data COA untuk group pertama
                        } else {
                            if (response.errors) {
                                let errorMessages = Object.values(response.errors).flat().join(
                                    '<br>');
                                showErrorAlertNoRefresh(errorMessages);
                            } else {
                                showErrorAlertNoRefresh(response.message ||
                                    'Terjadi kesalahan saat menambahkan COA');
                            }
                            $('#tambah-coa').modal('hide');
                        }
                    },
                    error: function(error) {
                        console.error('Error storing COA:', error);
                        if (error.responseJSON && error.responseJSON.errors) {
                            let errorMessages = Object.values(error.responseJSON.errors).flat()
                                .join(', ');
                            $('#tambah-coa').modal('hide');
                            showErrorAlertNoRefresh(errorMessages);
                        } else {
                            $('#tambah-coa').modal('hide');
                            showErrorAlertNoRefresh('Terjadi kesalahan saat menambahkan COA');
                        }
                    }
                });
            });

            // Fungsi untuk memuat data COA berdasarkan group_id
            function loadCoaByGroup(groupId) {
                $.ajax({
                    url: `/api/coa/group/${groupId}`,
                    method: 'GET',
                    success: function(response) {
                        let formattedData = formatTree(response);
                        $('#treeview').treeview({
                            data: formattedData,
                            levels: 5,
                            showIcon: true,
                            showTags: true,
                            highlightSelected: true,
                            expandIcon: 'fas fa-plus-circle mr-3',
                            collapseIcon: 'fas fa-minus-circle mr-3',
                            emptyIcon: 'fas fa-file mr-3',
                            onNodeSelected: function(event, node) {
                                // Ketika COA di klik, ambil data dan tampilkan modal edit
                                loadCoaDetails(node.id);
                            }
                        });
                    },
                    error: function(error) {
                        console.error('Error loading hierarchy:', error);
                    }
                });
            }

            $('#edit-form').on('submit', function(e) {
                e.preventDefault(); // Mencegah form submit secara default

                var formData = $(this).serialize(); // Mengambil semua data dari form

                var coaId = $('#edit_coa_id').val(); // Mengambil ID COA dari input tersembunyi

                $.ajax({
                    url: `{{ url('keuangan/setup/chart-of-account/') }}/${coaId}`,
                    type: 'PATCH',
                    data: formData,
                    beforeSend: function() {
                        $('#update-form').find('.ikon-edit').hide();
                        $('#update-form').find('.spinner-text').removeClass(
                            'd-none');
                    },
                    success: function(response) {
                        $('#edit-coa').modal('hide');
                        showSuccessAlert(response.message);
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);

                        setTimeout(() => {
                            console.log('Reloading the page now.');
                            window.location.reload();
                        }, 1000);
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            var errorMessages = '';

                            $.each(errors, function(key, value) {
                                errorMessages += value +
                                    '\n';
                            });

                            $('#edit-coa').modal('hide');
                            showErrorAlertNoRefresh('Terjadi kesalahan:\n' +
                                errorMessages);
                        } else {
                            $('#edit-coa').modal('hide');
                            showErrorAlertNoRefresh('Terjadi kesalahan: ' + error);
                            console.log(error);
                        }
                    }
                });
            });

            // Fungsi untuk memuat detail COA ke dalam modal edit
            function loadCoaDetails(coaId) {
                $.ajax({
                    url: `/api/coa/${coaId}`,
                    method: 'GET',
                    success: function(response) {
                        // Isi data ke dalam form edit
                        $('#edit_coa_id').val(response.id);
                        $('#edit_group_id').val(response.group_id).trigger('change');
                        $('#edit_parent_id').val(response.parent_id).trigger('change');
                        $('#edit_code').val(response.code);
                        $('#edit_name').val(response.name);
                        $('#edit_description').val(response.description);

                        // Set radio buttons
                        $(`#edit_header_${response.header === 1 ? 'yes' : 'no'}`).prop('checked', true);
                        $(`#edit_default_${String(response.default).toLowerCase()}`).prop('checked',
                            true);
                        $(`#edit_status_${response.status === 1 ? 'active' : 'inactive'}`).prop(
                            'checked', true);

                        // Tampilkan modal edit
                        $('#edit-coa').modal('show');
                    },
                    error: function(error) {
                        console.error('Error loading COA details:', error);
                    }
                });
            }

            // Fungsi untuk memformat data treeview
            function formatTree(data) {
                return data.map(function(node) {
                    let children = Array.isArray(node.children) && node.children.length > 0 ?
                        formatTree(node.children) :
                        null;

                    return {
                        id: node.id, // Pastikan ID disertakan
                        text: node.header ?
                            `<span style="font-weight: bold; color: #444; display: inline-block; border-bottom: 2px solid transparent;" onmouseover="this.style.borderBottom='2px solid #007bff';" onmouseout="this.style.borderBottom='2px solid transparent';">${node.code} ${node.name}</span>` :
                            `<span style="font-weight: normal; color: #333; display: inline-block; border-bottom: 2px solid transparent;" onmouseover="this.style.borderBottom='2px solid #007bff';" onmouseout="this.style.borderBottom='2px solid transparent';">${node.code} ${node.name}</span>`,
                        nodes: children
                    };
                });
            }

            // Event listener untuk tombol group filter
            $('.btn-group-filter').on('click', function() {
                let groupId = $(this).data('group-id');
                loadCoaByGroup(groupId);
            });

            // Muat data default untuk group pertama
            loadCoaByGroup(1);
        });
    </script>
@endsection
