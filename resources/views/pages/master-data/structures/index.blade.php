@extends('inc.layout')
@section('title', 'Struktur')
@section('extended-css')
    <style>
        .select2-dropdown.select2-dropdown--below {
            z-index: 2500 !important;
        }
    </style>
@endsection
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row mb-5">
            <div class="col-xl-12">
                <button type="button" id="btn-tambah" class="btn btn-primary waves-effect waves-themed" data-backdrop="static"
                    data-keyboard="false" data-toggle="modal" data-target="#tambah-data" title="Tambah Job Level">
                    <span class="fal fa-plus-circle mr-1"></span>
                    Tambah Struktur
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Tabel Struktur
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- datatable start -->
                            <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                <thead>
                                    <tr>
                                        {{-- <th style="white-space: nowrap">Foto</th> --}}
                                        <th style="white-space: nowrap">No</th>
                                        <th style="white-space: nowrap">Organisasi</th>
                                        <th style="white-space: nowrap">Parent Organisasi</th>
                                        <th style="white-space: nowrap">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($structures as $row)
                                        <tr>
                                            {{-- <td style="white-space: nowrap">{{ $user->template_user->foto }}</td> --}}
                                            <td style="white-space: nowrap">{{ $loop->iteration }}</td>
                                            <td style="white-space: nowrap">
                                                {{ $row->organization->name }}</td>
                                            <td style="white-space: nowrap">
                                                {{ $row->parent_organization == null ? '-' : $row->organization_parent->name }}
                                            </td>
                                            <td style="white-space: nowrap">
                                                <button type="button" data-backdrop="static" data-keyboard="false"
                                                    class="badge mx-1 btn-edit badge-primary p-2 border-0 text-white"
                                                    data-id="{{ $row->id }}" title="Ubah" onclick="btnEdit(event)">
                                                    <span class="fal fa-pencil ikon-edit"></span>
                                                    <div class="span spinner-text d-none">
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span>
                                                        Loading...
                                                    </div>
                                                </button>
                                                <button type="button" data-backdrop="static" data-keyboard="false"
                                                    class="badge mx-1 badge-success p-2 border-0 text-white btn-hapus"
                                                    data-id="{{ $row->id }}" title="Hapus" onclick="btnDelete(event)">
                                                    <span class="fal fa-trash ikon-hapus"></span>
                                                    <div class="span spinner-text d-none">
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span>
                                                        Loading...
                                                    </div>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th style="white-space: nowrap">No</th>
                                        <th style="white-space: nowrap">Organisasi</th>
                                        <th style="white-space: nowrap">Parent Organisasi</th>
                                        <th style="white-space: nowrap">Aksi</th>
                                    </tr>
                                </tfoot>
                            </table>
                            <!-- datatable end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('pages.master-data.structures.partials.create-data')
        @include('pages.master-data.structures.partials.update-data')
    </main>
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        /* demo scripts for change table color */
        /* change background */
        function btnEdit(event) {
            event.preventDefault();
            let button = event.currentTarget;
            let id = button.getAttribute('data-id');
            let ikonEdit = button.querySelector('.ikon-edit');
            let spinnerText = button.querySelector('.spinner-text');
            ikonEdit.classList.add('d-none');
            spinnerText.classList.remove('d-none');


            $.ajax({
                type: "GET", // Method pengiriman data bisa dengan GET atau POST
                url: `/api/dashboard/structures/get/${id}`, // Isi dengan url/path file php yang dituju
                dataType: "json",
                beforeSend: function() {
                    $('#child_organization').select2({
                        placeholder: 'Pilih Data Berikut',
                        dropdownParent: $('#ubah-data')
                    });
                    $('#parent_organization').select2({
                        placeholder: 'Pilih Data Berikut',
                        dropdownParent: $('#ubah-data')
                    });
                },
                success: function(data) {
                    ikonEdit.classList.remove('d-none');
                    ikonEdit.classList.add('d-block');
                    spinnerText.classList.add('d-none');
                    $('#ubah-data').modal('show');
                    $('#ubah-data #child_organization').val(data.child_organization).select2();
                    $('#ubah-data #parent_organization').val(data.parent_organization).select2();
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });

            $('#update-form').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                $.ajax({
                    type: "POST",
                    url: '/api/dashboard/structures/update/' + id,
                    data: formData,
                    beforeSend: function() {
                        $('#update-form').find('.ikon-edit').hide();
                        $('#update-form').find('.spinner-text')
                            .removeClass(
                                'd-none');
                    },
                    success: function(response) {
                        $('#ubah-data').modal('hide');
                        showSuccessAlert(response.message)
                        setTimeout(function() {
                            location.reload();
                        }, 500);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });
        }

        function btnDelete(event) {
            event.preventDefault();
            let button = event.currentTarget;
            alert('Yakin ingin menghapus ini ?');
            let id = button.getAttribute('data-id');
            let ikonHapus = button.querySelector('.ikon-hapus');
            let spinnerText = button.querySelector('.spinner-text');

            $.ajax({
                type: "GET",
                url: '/api/dashboard/structures/delete/' + id,
                beforeSend: function() {
                    ikonHapus.classList.add('d-none');
                    spinnerText.classList.remove('d-none');
                },
                success: function(response) {
                    ikonHapus.classList.remove('d-none');
                    ikonHapus.classList.add('d-block');
                    spinnerText.classList.add('d-none');
                    showSuccessAlert(response.message)
                    setTimeout(function() {
                        location.reload();
                    }, 500);
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        }

        $(document).ready(function() {
            $(function() {
                $('#child_organization1').select2({
                    placeholder: 'Pilih Data Berikut',
                    dropdownParent: $('#tambah-data')
                });
                $('#parent_organization1').select2({
                    placeholder: 'Pilih Data Berikut',
                    dropdownParent: $('#tambah-data')
                });
            });
            $('#store-form').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                $.ajax({
                    type: "POST",
                    url: '/api/dashboard/structures/store',
                    data: formData,
                    beforeSend: function() {
                        $('#store-form').find('.ikon-tambah').hide();
                        $('#store-form').find('.spinner-text').removeClass(
                            'd-none');
                    },
                    success: function(response) {
                        $('#store-form').find('.ikon-edit').show();
                        $('#store-form').find('.spinner-text').addClass('d-none');
                        $('#tambah-data').modal('hide');
                        showSuccessAlert(response.message)
                        setTimeout(function() {
                            location.reload();
                        }, 500);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });

            $('#dt-basic-example').dataTable({
                responsive: true
            });

            $('.js-thead-colors a').on('click', function() {
                var theadColor = $(this).attr("data-bg");
                console.log(theadColor);
                $('#dt-basic-example thead').removeClassPrefix('bg-').addClass(theadColor);
            });

            $('.js-tbody-colors a').on('click', function() {
                var theadColor = $(this).attr("data-bg");
                console.log(theadColor);
                $('#dt-basic-example').removeClassPrefix('bg-').addClass(theadColor);
            });

        });
    </script>
@endsection
