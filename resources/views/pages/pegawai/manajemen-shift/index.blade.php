@extends('inc.layout')
@section('title', 'Manajemen Shift')
@section('extended-css')
    <style>
        .upload-container {
            width: 100%;
        }

        .upload-wrapper {
            border: 2px dashed #ccc;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            background-color: #fff;
        }

        .upload-icon {
            font-size: 48px;
            margin-bottom: 10px;
        }

        .upload-text p {
            margin: 0;
        }

        .button {
            margin-top: 10px;
            display: inline-block;
            padding: 6px 15px;
            background-color: #fd1381;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #0056b3;
        }

        #fileList {
            margin-top: 10px;
            margin-bottom: 10px;
            text-align: center;
        }
    </style>
@endsection
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-container show">
                        <div class="panel-content upload-file-wrapper">
                            <ul class="nav nav-pills mb-5 mt-2" role="tablist">
                                <li class="nav-item"><a class="nav-link active" data-toggle="tab"
                                        href="#lists_employee_tabs" role="tab"><i
                                            class="fal fa-user-circle mr-1"></i>Daftar Pegawai</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#import_shift_tab"
                                        role="tab"><i class="fal fa-plus-circle mr-1"></i>Import Shift</a></li>
                            </ul>
                            <div class="tab-content px-0">
                                <div class="tab-pane fade show active" id="lists_employee_tabs" role="tabpanel">
                                    <!-- datatable start -->
                                    <table id="dt-basic-example"
                                        class="table table-bordered table-hover table-striped w-100">
                                        <thead>
                                            <tr>
                                                {{-- <th style="white-space: nowrap">Foto</th> --}}
                                                <th style="white-space: nowrap">No</th>
                                                <th style="white-space: nowrap">Nama</th>
                                                <th style="white-space: nowrap">Perusahaan</th>
                                                <th style="white-space: nowrap">Organisasi / Unit</th>
                                                <th style="white-space: nowrap">Jabatan</th>
                                                <th style="white-space: nowrap">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
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
                                                        {{ $employee->jobPosition->name ?? '*belum di setting' }}</td>
                                                    <td style="white-space: nowrap">
                                                        <a href="{{ route('edit-management-shift', $employee->id) }}"
                                                            data-backdrop="static" data-keyboard="false"
                                                            class="badge mx-1 badge-success p-2 border-0 text-white btn-edit"
                                                            data-template="<div class=&quot;tooltip&quot; role=&quot;tooltip&quot;><div class=&quot;tooltip-inner bg-success-500&quot;></div></div>"
                                                            data-toggle="tooltip" data-id="{{ $employee->id }}"
                                                            title="Edit Shift">
                                                            <span class="fal fa-pencil ikon-edit"></span>
                                                            <div class="span spinner-text d-none">
                                                                <span class="spinner-border spinner-border-sm"
                                                                    role="status" aria-hidden="true"></span>
                                                                Loading...
                                                            </div>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th style="white-space: nowrap">No</th>
                                                <th style="white-space: nowrap">Nama</th>
                                                <th style="white-space: nowrap">Mulai Kontrak</th>
                                                <th style="white-space: nowrap">Akhir Kontrak</th>
                                                <th style="white-space: nowrap">Perusahaan</th>
                                                <th style="white-space: nowrap">Aksi</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <!-- datatable end -->
                                </div>
                                <div class="tab-pane fade" id="import_shift_tab" role="tabpanel">
                                    <button class="btn btn-success mb-4" data-backdrop="static" data-keyboard="false"
                                        id="downloadTemplateBtn">
                                        <i class="fas fa-download mr-2"></i> Unduh Template
                                    </button>

                                    <form action="" id="store-form" enctype="multipart/form-data">
                                        @method('POST')
                                        @csrf
                                        <div class="upload-container">
                                            <div class="upload-wrapper" id="drop-area">
                                                <div class="upload-icon">
                                                    <i class="fas fa-file-excel"></i>
                                                </div>
                                                <div class="upload-text">
                                                    <p>Klik tombol dibawah ini untuk upload file</p>
                                                    <label class="button" for="fileElem">Browse Files</label>
                                                    <input type="file" id="fileElem" multiple accept=".xls, .xlsx"
                                                        style="display: none;" name="attendance_shift">
                                                </div>
                                            </div>
                                            <div id="fileList"></div>
                                            <button type="submit" class="btn btn-primary btn-block">
                                                <div class="ikon-tambah">
                                                    <span class="fal fa-upload mr-1"></span>
                                                    Tambah
                                                </div>
                                                <div class="span spinner-text d-none">
                                                    <span class="spinner-border spinner-border-sm" role="status"
                                                        aria-hidden="true"></span>
                                                    Loading...
                                                </div>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </main>

    @include('pages.pegawai.manajemen-shift.partials.download-template')



@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        document.getElementById('downloadTemplateBtn').addEventListener('click', function() {
            $('#downloadTemplateModal').modal('show');
        });

        $(document).ready(function() {
            $('#organization').select2({
                dropdownParent: $('#downloadTemplateModal')
            });
            $('#month').select2({
                dropdownParent: $('#downloadTemplateModal')
            });
            $('#year').select2({
                dropdownParent: $('#downloadTemplateModal')
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

            // Import Shift
            $("#fileElem").on("change", function() {
                var files = $(this)[0].files;
                handleFiles(files);
            });

            function handleFiles(files) {
                console.log(files);
                var allowedExtensions = /(\.xls|\.xlsx)$/i;
                var fileList = $("#fileList");
                fileList.empty();

                for (var i = 0; i < files.length; i++) {
                    var file = files[i];

                    if (!allowedExtensions.test(file.name)) {
                        alert("Only Excel files (.xls, .xlsx) are allowed.");
                        continue;
                    }

                    // Display file name
                    fileList.append("<div>" + file.name + "</div>");

                    // You can handle the file here
                    console.log("File uploaded:", file.name);
                }
            }

            $('#store-form').submit(function(event) {
                event.preventDefault();

                var formData = new FormData($(this)[0]);

                $.ajax({
                    url: '/api/dashboard/management-shift/store', // Ganti dengan endpoint API Anda
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    async: true, // Set async menjadi true untuk melakukan operasi secara asynchronous
                    cache: false,
                    contentType: false,
                    processData: false,
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
                        }, 1000);
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        showErrorAlert(xhr.responseJSON.error);
                        // Tampilkan pesan error kepada pengguna
                    }
                });
            });
        });
    </script>
@endsection
