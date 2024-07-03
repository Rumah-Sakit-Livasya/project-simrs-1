@extends('inc.layout')
@section('title', 'Company')
@section('extended-css')
    <style>
        .modal {
            top: 0 !important;
        }
    </style>
@endsection
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row mb-5">
            <div class="col-xl-12">
                <button type="button" id="btn-tambah" class="btn btn-primary waves-effect waves-themed" data-backdrop="static"
                    data-keyboard="false" data-toggle="modal" data-target="#tambah-data" title="Tambah Perusahaan">
                    <span class="fal fa-plus-circle mr-1"></span>
                    Tambah Perusahaan
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Perusahaan
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content pb-5">
                            {{-- <div class="card m-auto" style="max-width: 18rem; border:none; box-shadow:none;">
                                <img src="{{ asset('/storage/img/' . $company->logo) }}"
                                    class="card-img-top create-img-preview" alt="...">
                            </div>
                            <div class="container mt-4">
                                <form class="form font-weight-bold" id="update-form" role="form" autocomplete="off"
                                    data-id="{{ $company->id }}" enctype="multipart/form-data">
                                    @method('POST')
                                    @csrf
                                    <div class="form-group font-weight-bold row">
                                        <label class="col-lg-3 col-form-label form-control-label">Nama
                                            Instansi/Perusahaan</label>
                                        <div class="col-lg-9">
                                            <input class="form-control" name="name" type="text"
                                                value="{{ $company->name }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label form-control-label">Nomor Telp</label>
                                        <div class="col-lg-9">
                                            <input class="form-control" name="phone_number" type="text"
                                                value="{{ $company->phone_number }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label form-control-label">Email</label>
                                        <div class="col-lg-9">
                                            <input class="form-control" name="email" type="email"
                                                value="{{ $company->email }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label form-control-label">Alamat</label>
                                        <div class="col-lg-9">
                                            <input class="form-control" name="address" type="text"
                                                value="{{ $company->address }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label form-control-label">Provinsi</label>
                                        <div class="col-lg-9">
                                            <input class="form-control" name="province" type="text"
                                                value="{{ $company->province }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label form-control-label">Kabupaten/Kota</label>
                                        <div class="col-lg-9">
                                            <input class="form-control" name="city" type="text"
                                                value="{{ $company->city }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label form-control-label">Kategori</label>
                                        <div class="col-lg-9">
                                            <input class="form-control" name="category" type="text"
                                                value="{{ $company->category }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label form-control-label">Kelas</label>
                                        <div class="col-lg-9">
                                            <input class="form-control" name="class" type="text"
                                                value="{{ $company->class }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label form-control-label">Izin Operasional Rumah
                                            Sakit</label>
                                        <div class="col-lg-9">
                                            <input class="form-control" name="operating_permit_number" type="text"
                                                value="{{ $company->operating_permit_number }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label form-control-label">Logo</label>
                                        <div class="col-lg-9">
                                            <input class="form-control" name="logo" type="file" id="create-image"
                                                onchange="createPreviewImage()">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label form-control-label"></label>
                                        <div class="col-lg-9 mb-2">
                                            <button class="btn btn-primary waves-effect waves-themed" type="submit">Save
                                                Changes</button>
                                        </div>
                                    </div>
                                </form>
                            </div> --}}

                            <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                <thead>
                                    <tr>
                                        {{-- <th style="white-space: nowrap">Foto</th> --}}
                                        <th style="white-space: nowrap">No</th>
                                        <th style="white-space: nowrap">Nama Perusahaan</th>
                                        <th style="white-space: nowrap">Alamat</th>
                                        <th style="white-space: nowrap">No Tel</th>
                                        <th style="white-space: nowrap">Email</th>
                                        <th style="white-space: nowrap">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($companies as $company)
                                        <tr>
                                            {{-- <td style="white-space: nowrap">{{ $user->template_user->foto }}</td> --}}
                                            <td style="white-space: nowrap">{{ $loop->iteration }}</td>
                                            <td style="white-space: nowrap">{{ $company->name }}</td>
                                            <td>{{ $company->address }}</td>
                                            <td style="white-space: nowrap">{{ $company->phone_number }}</td>
                                            <td style="white-space: nowrap">{{ $company->email }}</td>
                                            <td style="white-space: nowrap">
                                                <button type="button" data-backdrop="static" data-keyboard="false"
                                                    onclick="btnEditData(event)"
                                                    class="badge mx-1 btn-edit badge-primary p-2 border-0 text-white"
                                                    data-id="{{ $company->id }}" title="Ubah">
                                                    <span class="fal fa-pencil ikon-edit"></span>
                                                    <div class="span spinner-text d-none">
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span>
                                                    </div>
                                                </button>
                                                <button type="button" data-backdrop="static" data-keyboard="false"
                                                    onclick="btnEditLocation(event)"
                                                    class="badge mx-1 btn-edit-map badge-warning p-2 border-0 text-white"
                                                    data-id="{{ $company->id }}" title="Ubah Lokasi">
                                                    <span class="fal fa-map-marker-alt ikon-edit"></span>
                                                    <div class="span spinner-text d-none">
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span>
                                                    </div>
                                                </button>
                                                <button type="button" data-backdrop="static" data-keyboard="false"
                                                    data-toggle="modal" data-target="#ubah-data"
                                                    class="badge mx-1 badge-success p-2 border-0 text-white btn-hapus"
                                                    data-id="{{ $company->id }}" title="Hapus">
                                                    <span class="fal fa-trash ikon-hapus"></span>
                                                    <div class="span spinner-text d-none">
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span>
                                                    </div>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th style="white-space: nowrap">No</th>
                                        <th style="white-space: nowrap">Nama Perusahaan</th>
                                        <th style="white-space: nowrap">Alamat</th>
                                        <th style="white-space: nowrap">No Tel</th>
                                        <th style="white-space: nowrap">Email</th>
                                        <th style="white-space: nowrap">Aksi</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('pages.master-data.company.partials.create-data')
    @include('pages.master-data.company.partials.update-data')
    @include('pages.master-data.company.partials.update-location')
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        let dataId = null;

        function createPreviewImage() {
            const image = document.querySelector('#create-image');
            const imgPreview = document.querySelector('.create-img-preview')

            imgPreview.style.display = 'block';

            const oFReader = new FileReader();
            oFReader.readAsDataURL(image.files[0])

            oFReader.onload = function(oFREvent) {
                imgPreview.src = oFREvent.target.result;
            }
        }

        function btnEditData(event) {
            event.preventDefault();
            let button = event.currentTarget;
            let id = button.getAttribute('data-id');
            dataId = id;
            let ikonEdit = button.querySelector('.ikon-edit');
            let spinnerText = button.querySelector('.spinner-text');
            ikonEdit.classList.add('d-none');
            spinnerText.classList.remove('d-none');
            // button.find('.ikon-edit').hide();
            // button.find('.spinner-text').removeClass('d-none');

            $.ajax({
                type: "GET", // Method pengiriman data bisa dengan GET atau POST
                url: '/dashboard/company/get/' + id, // Isi dengan url/path file php yang dituju
                dataType: "json",
                success: function(data) {
                    ikonEdit.classList.remove('d-none');
                    ikonEdit.classList.add('d-block');
                    spinnerText.classList.add('d-none');
                    // button.find('.ikon-edit').show();
                    // button.find('.spinner-text').addClass('d-none');
                    $('#ubah-data').modal('show');
                    $('#ubah-data #name').val(data.name)
                    $('#ubah-data #phone_number').val(data.phone_number)
                    $('#ubah-data #email').val(data.email)
                    $('#ubah-data #address').val(data.address)
                    $('#ubah-data #province').val(data.province)
                    $('#ubah-data #city').val(data.city)
                    $('#ubah-data #logo').val(data.logo)
                    $('#ubah-data #category').val(data.category)
                    $('#ubah-data #class').val(data.class)
                    $('#ubah-data #operating_permit_number').val(data.operating_permit_number)
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        }

        function btnEditLocation(event) {
            event.preventDefault();
            let button = event.currentTarget;
            let id = button.getAttribute('data-id');
            let ikonEdit = button.querySelector('.ikon-edit');
            let spinnerText = button.querySelector('.spinner-text');
            ikonEdit.classList.add('d-none');
            spinnerText.classList.remove('d-none');
            // button.find('.ikon-edit').hide();
            // button.find('.spinner-text').removeClass('d-none');

            $.ajax({
                type: "GET", // Method pengiriman data bisa dengan GET atau POST
                url: '/dashboard/company/get/' + id, // Isi dengan url/path file php yang dituju
                dataType: "json",
                success: function(data) {
                    ikonEdit.classList.remove('d-none');
                    ikonEdit.classList.add('d-block');
                    spinnerText.classList.add('d-none');
                    // button.find('.ikon-edit').show();
                    // button.find('.spinner-text').addClass('d-none');
                    $('#ubah-lokasi').modal('show');
                    $('#ubah-lokasi #latitude').val(data.latitude.replace(',', '.'));
                    $('#ubah-lokasi #longitude').val(data.longitude.replace(',', '.'));
                    $('#ubah-lokasi #radius').val(data.radius);
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });

            $('#update-form-location').on('submit', function(e) {
                e.preventDefault();
                const fd = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: '/dashboard/company/update-location/' + id,
                    processData: false,
                    contentType: false,
                    data: fd,
                    success: function(response) {
                        $('#ubah-lokasi').modal('hide');
                        showSuccessAlert(response.message);
                        setTimeout(function() {
                            location.reload();
                        }, 500);
                    },
                    error: function(xhr, status, error) {
                        $('#ubah-lokasi').modal('hide');
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showErrorAlert(errorMessage);
                    }
                });
            });
        }

        $(document).ready(function() {

            $('#update-form').on('submit', function(e) {
                e.preventDefault();
                const fd = new FormData(this);
                // console.log(fd);
                $.ajax({
                    type: 'POST',
                    url: '/dashboard/company/update/' + dataId,
                    processData: false,
                    contentType: false,
                    data: fd,
                    success: function(response) {
                        $('#ubah-data').modal('hide');
                        showSuccessAlert(response.message);
                        setTimeout(function() {
                            location.reload();
                        }, 500);
                    },
                    error: function(xhr, status, error) {
                        $('#ubah-data').modal('hide');
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showErrorAlert(errorMessage);
                    }

                });
            });

            $('#store-form').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    type: "POST",
                    url: '/dashboard/company/store',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('#store-form').find('.ikon-tambah').hide();
                        $('#store-form').find('.spinner-text').removeClass('d-none');
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
                    error: function(xhr, status, error) {
                        $('#tambah-data').modal('hide');
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showErrorAlert(errorMessage);
                    }
                });
            });
        });
    </script>
@endsection
