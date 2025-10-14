{{-- @dd($employee->organization) --}}
@extends('inc.layout')
@section('title', 'Profil Pengguna')
@section('tmp_body', 'nav-function-minify layout-composed')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="panel-container show">
            <div class="panel-content">
                <div class="row">
                    @include('pages.pegawai.profil-pegawai.partials.left-content')
                    <div class="col-lg-9">
                        <div class="card mb-g">
                            <div class="row mt-4">
                                <div class="col-12 px-5">
                                    <div class="row row-grid no-gutters">
                                        <div class="col mb-4">
                                            <div class="tab-content" id="v-pills-tabContent">
                                                @include('pages.pegawai.profil-pegawai.partials.section.general-section')
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('pages.pegawai.profil-pegawai.partials.modal.edit-employee')
            @include('pages.pegawai.profil-pegawai.partials.modal.edit-identity')
            @include('pages.pegawai.profil-pegawai.partials.modal.edit-profile-picture')
            @include('pages.pegawai.profil-pegawai.partials.modal.edit-dokumen')
            @include('pages.pegawai.profil-pegawai.partials.modal.create-attendance-request')
            @include('pages.pegawai.profil-pegawai.partials.modal.create-dokumen')

    </main>
@endsection

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script>
        // Preview Image Modal Update Profile
        $('#ubah-profil').on('click', function() {
            var employeeId = $(this).data('id');
            $('#employee-id').val(employeeId);

            $.ajax({
                type: 'GET',
                url: '/employees/edit-profil/' + employeeId,
                success: function(data) {
                    var previewImage = $('.img-preview');
                    if (previewImage.length) {
                        previewImage.attr('src', '/storage/employee/profile/' + data.foto);
                    }
                    $('#changeProfileModal').modal('show');
                },
                error: function(error) {
                    showErrorAlertNoRefresh('Terjadi kesalahan:', error.message);
                }
            });
        });

        // Update Form
        $('#update-profile-picture').on('submit', function(e) {
            e.preventDefault();

            var employeeId = $('#employee-id').val();
            var formData = new FormData(this);

            $.ajax({
                type: 'post',
                url: '/employees/update-profil/' + employeeId,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#changeProfileModal').modal('hide');
                    showSuccessAlert('Foto Profil Diubah!');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                },
                error: function(error) {
                    $('#changeProfileModal').modal('hide');
                    showErrorAlertNoRefresh('Cek kembali data yang dikirim');
                }
            });
        });

        // Preview Image Update Profile
        function previewImage() {
            const image = document.querySelector('#foto');
            const imgPreview = document.querySelector('.img-preview')
            imgPreview.style.display = 'block';
            const oFReader = new FileReader();
            oFReader.readAsDataURL(image.files[0])
            oFReader.onload = function(oFREvent) {
                imgPreview.src = oFREvent.target.result;
            }
        }

        $(document).ready(function() {
            let dokumenId = null;

            $('#dt-basic-example').dataTable({
                responsive: false
            });

            // Action untuk tombol download dokumen
            $(document).on('click', '.btn-download-dokumen', function(e) {
                e.preventDefault();
                var $btn = $(this);
                var id = $btn.data('id');
                $btn.find('.ikon-download-dokumen').hide();
                $btn.find('.spinner-text').removeClass('d-none');
                // Proses download versi window.open agar bisa untuk file apapun
                $.ajax({
                    url: '/api/dashboard/files/' + id + '/download',
                    type: 'GET',
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(data, status, xhr) {
                        // Get filename from header if present
                        var disposition = xhr.getResponseHeader('Content-Disposition');
                        var filename = 'file.pdf';
                        if (disposition && disposition.indexOf('filename=') !== -1) {
                            filename = disposition.split('filename=')[1].split(';')[0].replace(
                                /"/g, "");
                        }
                        var url = window.URL.createObjectURL(data);
                        var a = document.createElement('a');
                        a.href = url;
                        a.download = filename;
                        document.body.appendChild(a);
                        a.click();
                        setTimeout(function() {
                            window.URL.revokeObjectURL(url);
                            document.body.removeChild(a);
                        }, 0);
                        showSuccessAlert('File berhasil diunduh.');
                    },
                    error: function(xhr) {
                        showErrorAlertNoRefresh('Gagal mengunduh file.');
                    },
                    complete: function() {
                        $btn.find('.ikon-download-dokumen').show();
                        $btn.find('.spinner-text').addClass('d-none');
                    }
                });
            });

            // Edit and Hapus tombol
            // Edit dokumen: tampilkan modal sesuai struktur form modal edit-dokumen.blade.php
            $(document).on('click', '.btn-edit-dokumen', function(e) {
                e.preventDefault();
                var docId = $(this).data('id');
                $.ajax({
                    type: 'GET',
                    url: '/api/dashboard/files/' + docId + '/edit',
                    success: function(response) {
                        // Set input modal sesuai partial modal edit-dokumen.blade.php
                        $('#edit-dokumen-form #edit-dokumen-id').val(response.id ?? "");
                        $('#edit-dokumen-form #edit-nama').val(response.nama ?? "");
                        $('#edit-dokumen-form #edit-expire').val(response.masa_berlaku ??
                            ""
                        ); // field backend: masa_berlaku; field modal: expire (pastikan disamakan)
                        if (response.hard_copy == 1) {
                            $('#edit-dokumen-form #edit-hard-copy').prop('checked', true);
                        } else {
                            $('#edit-dokumen-form #edit-hard-copy').prop('checked', false);
                        }

                        // Tampilkan/link file sebelumnya jika ada
                        if (response.file) {
                            $('#file-sekarang').html('<a href="/storage/uploads/' + response
                                .file +
                                '" target="_blank" class="text-sm text-blue-600 underline">Lihat file saat ini</a>'
                            );
                        } else {
                            $('#file-sekarang').html(
                                '<span class="text-danger text-sm">File tidak tersedia</span>'
                            );
                        }

                        // Bersihkan input file & label
                        $('#edit-dokumen-form #edit-file').val('');
                        $('#edit-dokumen-form .custom-file-label').text('Pilih file baru...');

                        $('#edit-dokumen-modal').modal('show');
                    },
                    error: function(xhr) {
                        showErrorAlertNoRefresh('Gagal memuat data dokumen.');
                    }
                });
            });

            // Update dokumen sesuai struktur modal
            $('#edit-dokumen-form').on('submit', function(e) {
                e.preventDefault();

                var formData = new FormData(this);
                var id = $('#edit-dokumen-id').val();

                // Disable button dan tampilkan spinner
                var $btn = $('#edit-dokumen-form button[type=submit]');
                $btn.prop('disabled', true);
                $btn.find('.spinner-border').removeClass('d-none');

                $.ajax({
                    type: 'POST',
                    url: '/api/dashboard/files/update/' + id,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        $('#edit-dokumen-modal').modal('hide');
                        showSuccessAlert('Dokumen berhasil diupdate!');
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    error: function(xhr) {
                        $('#edit-dokumen-modal').modal('hide');
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            showErrorAlertNoRefresh(xhr.responseJSON.error);
                        } else {
                            showErrorAlertNoRefresh('Gagal mengedit dokumen.');
                        }
                    },
                    complete: function() {
                        $btn.prop('disabled', false);
                        $btn.find('.spinner-border').addClass('d-none');
                    }
                });
            });

            $(document).on('click', '.btn-delete-dokumen', function(e) {
                e.preventDefault();
                var idDihapus = $(this).data('id');
                if (confirm('Yakin ingin menghapus dokumen ini?')) {
                    $.ajax({
                        type: 'DELETE',
                        url: '/api/dashboard/files/delete/' + idDihapus,
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            showSuccessAlert("Dokumen berhasil dihapus");
                            setTimeout(function() {
                                location.reload();
                            }, 800);
                        },
                        error: function(xhr) {
                            showErrorAlertNoRefresh("Gagal menghapus dokumen.");
                        }
                    });
                }
            });

            // Tombol download
            $('a.download').on('click', function(e) {
                e.preventDefault();
                var documentId = $(this).data('id');
                var url = "/api/dashboard/files/download-document/" + documentId;
                $.ajax({
                    type: "GET",
                    url: url,
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(data, status, xhr) {
                        var disposition = xhr.getResponseHeader('Content-Disposition');
                        var filename = disposition ? disposition.split('filename=')[1] :
                            'document.pdf';
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(data);
                        link.download = filename;
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    },
                    error: function(xhr) {
                        showErrorAlertNoRefresh(xhr.responseJSON.error);
                    }
                });
            });

            $('#tambah-dokumen').click(function(e) {
                $('#tambah-dokumen-modal').modal('show');
            });

            $('#tambah-dokumen-form').on('submit', function(e) {
                e.preventDefault();
                const employeeId = "{{ auth()->user()->employee->id }}";
                var formData = new FormData(this);
                formData.append('employee_id', employeeId);
                $.ajax({
                    type: 'post',
                    url: '/api/dashboard/files/store',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#tambah-dokumen-modal').modal('hide');
                        showSuccessAlert(response.message);
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    error: function(error) {
                        $('#tambah-dokumen-modal').modal('hide');
                        showErrorAlertNoRefresh(error.error);
                    }
                });
            });

            // --- Existing forms & step logic, attendance, etc. remains unchanged below ---

            $('.btn-ubah-personal').click(function(e) {
                e.preventDefault();
                let button = $(this);
                let id = button.attr('data-id');
                button.find('.ikon-edit').hide();
                button.find('.spinner-text').removeClass('d-none');
                $.ajax({
                    type: "GET",
                    url: `/api/dashboard/employee/get/${id}`,
                    dataType: "json",
                    success: function(data) {
                        button.find('.ikon-edit').show();
                        button.find('.spinner-text').addClass('d-none');
                        $('#ubah-personal').modal('show');
                        $('#ubah-personal #fullname').val(data.fullname);
                        $('#ubah-personal #mobile_phone').val(data.mobile_phone);
                        $('#ubah-personal #email').val(data.email);
                        $('#ubah-personal #place_of_birth').val(data.place_of_birth);
                        $('#ubah-personal #birthdate').datepicker({
                            todayBtn: "linked",
                            clearBtn: false,
                            todayHighlight: true,
                            format: "yyyy-mm-dd",
                        }).val(data.birthdate);
                        $('#ubah-personal #gender').val(data.gender).select2({
                            dropdownParent: $('#ubah-personal')
                        });
                        $('#ubah-personal #marital-status').val(data.marital_status).select2({
                            dropdownParent: $('#ubah-personal')
                        });
                        $('#ubah-personal #religion').val(data.religion).select2({
                            dropdownParent: $('#ubah-personal')
                        });
                        $('#ubah-personal #blood-type').val(data.blood_type);
                    },
                    error: function(xhr) {
                        $('#ubah-personal').modal('hide');
                        showErrorAlertNoRefresh(xhr.responseText);
                    }
                });

                $('#update-personal-form').off('submit').on('submit', function(e) {
                    e.preventDefault();
                    let formData = $(this).serialize();
                    $.ajax({
                        type: "POST",
                        url: '/api/dashboard/employee/update-personal/' + id,
                        data: formData,
                        beforeSend: function() {
                            $('#update-personal-form').find('.ikon-edit').hide();
                            $('#update-personal-form').find('.spinner-text')
                                .removeClass('d-none');
                        },
                        success: function(response) {
                            $('#ubah-personal').modal('hide');
                            showSuccessAlert(response.message)
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        },
                        error: function(xhr) {
                            showErrorAlertNoRefresh(xhr.responseText);
                        }
                    });
                });
            });

            $('.btn-ubah-identitas').click(function(e) {
                e.preventDefault();
                let button = $(this);
                let id = button.attr('data-id');
                button.find('.ikon-edit').hide();
                button.find('.spinner-text').removeClass('d-none');
                $.ajax({
                    type: "GET",
                    url: `/api/dashboard/employee/get/${id}`,
                    dataType: "json",
                    success: function(data) {
                        button.find('.ikon-edit').show();
                        button.find('.spinner-text').addClass('d-none');
                        $('#ubah-identitas').modal('show');
                        $('#ubah-identitas #identity-type').val(data.identity_type);
                        $('#ubah-identitas #identity-number').val(data.identity_number);
                        var identityNumberExpired = data.identity_expire_date;
                        if (!identityNumberExpired) {
                            $('#ubah-identitas #identity-expire-date').prop('disabled', true);
                        } else {
                            $('#ubah-identitas #identity-expire-date').val(
                                identityNumberExpired);
                        }
                        $('#ubah-identitas #postal-code').val(data.postal_code);
                        $('#ubah-identitas #citizen-id-address').val(data.citizen_id_address);
                        $('#ubah-identitas #residental-address').val(data.residental_address);
                    },
                    error: function(xhr) {
                        $('#ubah-identitas').modal('hide');
                        showErrorAlertNoRefresh(xhr.responseText);
                    }
                });

                $('#update-identity-form').off('submit').on('submit', function(e) {
                    e.preventDefault();
                    let formData = $(this).serialize();
                    $.ajax({
                        type: "POST",
                        url: '/api/dashboard/employee/update-identitas/' + id,
                        data: formData,
                        beforeSend: function() {
                            $('#update-identity-form').find('.ikon-edit').hide();
                            $('#update-identity-form').find('.spinner-text')
                                .removeClass('d-none');
                        },
                        success: function(response) {
                            $('#ubah-identitas').modal('hide');
                            showSuccessAlert(response.message)
                            setTimeout(function() {
                                location.reload();
                            }, 500);
                        },
                        error: function(xhr) {
                            $('#ubah-identitas').modal('hide');
                            showErrorAlertNoRefresh(xhr.responseText);
                        }
                    });
                });
            });

            $('.btn-ubah-pekerjaan').click(function(e) {
                e.preventDefault();
                let button = $(this);
                let id = button.attr('data-id');
                button.find('.ikon-edit').hide();
                button.find('.spinner-text').removeClass('d-none');
                $.ajax({
                    type: "GET",
                    url: `/api/dashboard/employee/get/${id}`,
                    dataType: "json",
                    success: function(data) {
                        button.find('.ikon-edit').show();
                        button.find('.spinner-text').addClass('d-none');
                        $('#ubah-identitas').modal('show');
                        $('#ubah-identitas #identity-type').val(data.identity_type);
                        $('#ubah-identitas #identity-number').val(data.identity_number);
                        var identityNumberExpired = data.identity_expire_date;
                        if (!identityNumberExpired) {
                            $('#ubah-identitas #identity-expire-date').prop('disabled', true);
                        } else {
                            $('#ubah-identitas #identity-expire-date').val(
                                identityNumberExpired);
                        }
                        $('#ubah-identitas #postal-code').val(data.postal_code);
                        $('#ubah-identitas #citizen-id-address').val(data.citizen_id_address);
                        $('#ubah-identitas #residental-address').val(data.residental_address);
                    },
                    error: function(xhr) {
                        $('#ubah-identitas').modal('hide');
                        showErrorAlertNoRefresh(xhr.responseText);
                    }
                });

                $('#update-identity-form').off('submit').on('submit', function(e) {
                    e.preventDefault();
                    let formData = $(this).serialize();
                    $.ajax({
                        type: "POST",
                        url: '/api/dashboard/employee/update-identitas/' + id,
                        data: formData,
                        beforeSend: function() {
                            $('#update-identity-form').find('.ikon-edit').hide();
                            $('#update-identity-form').find('.spinner-text')
                                .removeClass('d-none');
                        },
                        success: function(response) {
                            $('#ubah-identitas').modal('hide');
                            showSuccessAlert(response.message)
                            setTimeout(function() {
                                location.reload();
                            }, 500);
                        },
                        error: function(xhr) {
                            $('#ubah-identitas').modal('hide');
                            showErrorAlertNoRefresh(xhr.responseText);
                        }
                    });
                });
            });

            $('.btn-ajukan').click(function(e) {
                var today = new Date();
                var yesterday = new Date(today);
                yesterday.setDate(today.getDate() - 1);

                $('#store-attendance-request #date').datepicker({
                    todayBtn: "linked",
                    clearBtn: false,
                    todayHighlight: true,
                    format: "yyyy-mm-dd",
                    startDate: yesterday,
                    endDate: today
                });

                $('#create-attendance-form').modal('show');

                $('#store-attendance-request').off('submit').on('submit', function(e) {
                    e.preventDefault();
                    let formData = $(this).serialize();
                    $.ajax({
                        type: "POST",
                        url: '/attendance-request/store/',
                        data: formData,
                        beforeSend: function() {
                            $('#store-attendance-request').find('.ikon-tambah').hide();
                            $('#store-attendance-request').find('.spinner-text')
                                .removeClass('d-none');
                        },
                        success: function(response) {
                            $('#store-attendance-request').find('.ikon-edit').show();
                            $('#store-attendance-request').find('.spinner-text')
                                .addClass('d-none');
                            $('#tambah-data').modal('hide');
                            showSuccessAlert(response.message)
                            setTimeout(function() {
                                location.reload();
                            }, 500);
                        },
                        error: function(xhr) {
                            $('#tambah-data').modal('hide');
                            showErrorAlertNoRefresh(xhr.responseText);
                        }
                    });
                });
            });

            $(function() {
                $('.select2').select2({
                    placeholder: 'Pilih Data Berikut'
                });
            });

            $('#sama-alamat').change(function() {
                if ($(this).is(':checked')) {
                    $('#residental_address').val($('#citizen_id_address').val());
                } else {
                    $('#residental_address').val("");
                }
            });

            $('.btn-next .btn-prev').click(function(e) {
                e.preventDefault();
                let parent = $(this).parent().parent();
                if (parent.attr('id') == 'step-2') {
                    $('#step-round-1-done').addClass('d-none');
                    $('#step-round-1').removeClass('d-none');
                    $('#step-round-2').removeClass('btn-primary');
                    $('#step-round-2').addClass('btn-outline-primary');
                    $("html, body").animate({
                        scrollTop: 0
                    }, 500);
                    $('#step-2').fadeOut(300, function() {
                        $('#step-1').fadeIn(300, function() {
                            $('#step-1').removeClass('hidden-content')
                        })
                    });
                } else if (parent.attr('id') == 'step-3') {
                    $('#step-round-2-done').addClass('d-none');
                    $('#step-round-2').removeClass('d-none');
                    $('#step-round-3').removeClass('btn-primary');
                    $('#step-round-3').addClass('btn-outline-primary');
                    $("html, body").animate({
                        scrollTop: 0
                    }, 500);
                    $('#step-3').fadeOut(300, function() {
                        $('#step-2').fadeIn(300, function() {
                            $('#step-2').removeClass('hidden-content')
                        })
                    });
                } else if (parent.attr('id') == 'step-4') {
                    $('#step-round-3-done').addClass('d-none');
                    $('#step-round-3').removeClass('d-none');
                    $('#step-round-4').removeClass('btn-primary');
                    $('#step-round-4').addClass('btn-outline-primary');
                    $("html, body").animate({
                        scrollTop: 0
                    }, 500);
                    $('#step-4').fadeOut(300, function() {
                        $('#step-3').fadeIn(300, function() {
                            $('#step-3').removeClass('hidden-content')
                        })
                    });
                }
            });

            $('.btn-next .btn-next-step').click(function(e) {
                e.preventDefault();
                let parent = $(this).parent().parent();
                $("html, body").animate({
                    scrollTop: 0
                }, 500);
                parent.fadeOut(300, function() {
                    parent.addClass('hidden-content');
                    parent.removeAttr('style');

                    if (parent.attr('id') == 'step-1') {
                        $('#step-2').fadeIn(300);
                        $('#step-round-1-done').removeClass('d-none');
                        $('#step-round-1').addClass('d-none');
                        $('#step-round-2').removeClass('btn-outline-primary');
                        $('#step-round-2').addClass('btn-primary');
                    } else if (parent.attr('id') == 'step-2') {
                        $('#step-2').fadeOut(300, function() {
                            $('#step-2').addClass('hidden-content');
                            $('#step-2').removeAttr('style');
                        });
                        $('#step-3').fadeIn(300);
                        $('#step-round-2').addClass('d-none');
                        $('#step-round-2-done').removeClass('d-none');
                        $('#step-round-3').removeClass('btn-outline-primary');
                        $('#step-round-3').addClass('btn-primary');
                    } else if (parent.attr('id') == 'step-3') {
                        $('#step-3').fadeOut(300, function() {
                            $('#step-3').addClass('hidden-content');
                            $('#step-3').removeAttr('style');
                        });
                        $('#step-4').fadeIn(300);
                        $('#step-round-3').addClass('d-none');
                        $('#step-round-3-done').removeClass('d-none');
                        $('#step-round-4').removeClass('btn-outline-primary');
                        $('#step-round-4').addClass('btn-primary');
                    }
                });
            });

            $('#datepicker-3').datepicker({
                todayBtn: "linked",
                clearBtn: true,
                todayHighlight: true,
            });

            $('#identity_expire_date').datepicker({
                todayBtn: "linked",
                clearBtn: true,
                todayHighlight: true,
            });

            $('#join_date').datepicker({
                todayBtn: "linked",
                clearBtn: true,
                todayHighlight: true,
            });
        });
    </script>
@endsection
