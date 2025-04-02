@extends('inc.layout')
@section('title', 'URL Shortener')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            URL Shortener
                            <small class="text-muted">Persingkat URL Anda dengan mudah</small>
                        </h2>
                        <div class="panel-toolbar">
                            <button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip"
                                data-offset="0,10" data-original-title="Collapse"></button>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="{{ route('shorten') }}" method="POST" class="needs-validation" novalidate>
                                @csrf
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="url" name="url" class="form-control form-control-lg"
                                            placeholder="Masukkan URL panjang Anda" required pattern="https?://.+">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary btn-lg" type="submit">
                                                <i class="fas fa-link mr-1"></i> Persingkat
                                            </button>
                                        </div>
                                        <div class="invalid-feedback">
                                            Harap masukkan URL yang valid (contoh: https://example.com)
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div id="panel-2" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Data Link Shortener
                            <small class="text-muted">Daftar URL yang telah dipersingkat</small>
                        </h2>
                        <div class="panel-toolbar">
                            <button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip"
                                data-offset="0,10" data-original-title="Collapse"></button>
                            <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip"
                                data-offset="0,10" data-original-title="Fullscreen"></button>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="table-responsive">
                                <table id="dt-link-shortener" class="table table-bordered table-hover table-striped w-100">
                                    <thead class="bg-primary-50">
                                        <tr>
                                            <th>No</th>
                                            <th>URL Asli</th>
                                            <th>Short Link</th>
                                            <th>Kode</th>
                                            <th>Klik</th>
                                            <th>Dibuat</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($links as $row)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="text-truncate" style="max-width: 250px;">
                                                    <a href="{{ $row->original_url }}" target="_blank"
                                                        title="{{ $row->original_url }}" class="text-primary">
                                                        {{ Str::limit($row->original_url, 50) }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="{{ url('/links/' . $row->short_code) }}" target="_blank"
                                                        class="text-success">
                                                        {{ url('/links/' . $row->short_code) }}
                                                    </a>
                                                </td>
                                                <td><span class="badge badge-primary">{{ $row->short_code }}</span></td>
                                                <td>
                                                    <span
                                                        class="badge badge-{{ $row->clicks > 0 ? 'success' : 'secondary' }}">
                                                        {{ $row->clicks }}
                                                    </span>
                                                </td>
                                                <td>{{ $row->created_at->format('d/m/Y H:i') }}</td>
                                                <td style="white-space: nowrap">
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <button class="btn btn-info btn-copy"
                                                            data-short-url="{{ url('/links/' . $row->short_code) }}"
                                                            data-toggle="tooltip" title="Salin Link">
                                                            <i class="fas fa-copy"></i>
                                                        </button>
                                                        <button class="btn btn-success btn-go"
                                                            data-short-url="{{ url('/links/' . $row->short_code) }}"
                                                            data-toggle="tooltip" title="Buka Link">
                                                            <i class="fas fa-external-link-alt"></i> Go
                                                        </button>
                                                        <button class="btn btn-danger btn-delete"
                                                            data-id="{{ $row->id }}"
                                                            data-short-code="{{ $row->short_code }}"
                                                            data-delete-url="{{ route('links.destroy', $row->id) }}"
                                                            data-toggle="tooltip" title="Hapus">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/datatable/jszip.min.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/notifications/toastr/toastr.js"></script>

    <script>
        $(document).ready(function() {
            // Form validation
            (function() {
                'use strict';
                window.addEventListener('load', function() {
                    var forms = document.getElementsByClassName('needs-validation');
                    Array.prototype.filter.call(forms, function(form) {
                        form.addEventListener('submit', function(event) {
                            if (form.checkValidity() === false) {
                                event.preventDefault();
                                event.stopPropagation();
                            }
                            form.classList.add('was-validated');
                        }, false);
                    });
                }, false);
            })();

            // Initialize DataTable
            $('#dt-link-shortener').DataTable({
                responsive: true,
                pageLength: 25,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                        extend: 'colvis',
                        text: '<i class="fas fa-eye"></i> Kolom',
                        titleAttr: 'Visibility',
                        className: 'btn-outline-default'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Print',
                        titleAttr: 'Print',
                        className: 'btn-outline-default',
                        exportOptions: {
                            columns: ':visible'
                        },
                        customize: function(win) {
                            $(win.document.body).find('table').addClass('compact').css('font-size',
                                'inherit');
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        titleAttr: 'Excel',
                        className: 'btn-outline-default',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        titleAttr: 'PDF',
                        className: 'btn-outline-default',
                        exportOptions: {
                            columns: ':visible'
                        }
                    }
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
                },
                columnDefs: [{
                        responsivePriority: 1,
                        targets: 0
                    },
                    {
                        responsivePriority: 2,
                        targets: 6
                    },
                    {
                        orderable: false,
                        targets: 6
                    }
                ]
            });

            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // Go to Link
            $(document).on('click', '.btn-go', function() {
                const url = $(this).data('short-url');
                window.open(url, '_blank');
            });

            // Copy to clipboard function dengan fallback
            $(document).on('click', '.btn-copy', function() {
                const shortUrl = $(this).data('short-url') || $(this).data('clipboard-text');
                const tempInput = $('<input>');

                $('body').append(tempInput);
                tempInput.val(shortUrl).select();

                try {
                    // Mencoba menggunakan Clipboard API modern
                    if (navigator.clipboard) {
                        navigator.clipboard.writeText(shortUrl).then(() => {
                            showToast('success', 'Link berhasil disalin: ' + shortUrl);
                        }).catch(err => {
                            fallbackCopy(shortUrl);
                        });
                    } else {
                        fallbackCopy(shortUrl);
                    }
                } catch (err) {
                    fallbackCopy(shortUrl);
                } finally {
                    tempInput.remove();
                }

                function fallbackCopy(text) {
                    try {
                        const success = document.execCommand('copy');
                        if (success) {
                            showToast('success', 'Link berhasil disalin: ' + text);
                        } else {
                            throw new Error('Fallback copy failed');
                        }
                    } catch (err) {
                        showToast('error', 'Gagal menyalin link: ' + err.message);
                    }
                }

                function showToast(type, message) {
                    toastr[type](message, type === 'success' ? 'Sukses' : 'Error', {
                        closeButton: true,
                        progressBar: true,
                        positionClass: "toast-top-right",
                        timeOut: type === 'success' ? 3000 : 5000,
                        newestOnTop: true
                    });
                }
            });

            // Delete confirmation dengan auto refresh setelah berhasil
            $(document).on('click', '.btn-delete', function() {
                const id = $(this).data('id');
                const deleteUrl = $(this).data('delete-url') || '/links/' + id;
                const shortCode = $(this).data('short-code');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    html: `Link yang dihapus tidak dapat dikembalikan!<br><small>Kode: ${shortCode}</small>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: '<i class="fas fa-trash"></i> Ya, hapus!',
                    cancelButtonText: '<i class="fas fa-times"></i> Batal',
                    reverseButtons: true,
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return $.ajax({
                            url: deleteUrl,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            dataType: 'json'
                        }).fail(xhr => {
                            Swal.showValidationMessage(
                                `Request failed: ${xhr.responseJSON?.message || xhr.statusText}`
                            );
                        });
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (result.value?.success) {
                            Swal.fire({
                                title: 'Terhapus!',
                                text: 'Link telah berhasil dihapus.',
                                icon: 'success',
                                timer: 1000, // Timer lebih pendek
                                showConfirmButton: false,
                                willClose: () => {
                                    // Refresh halaman setelah SweetAlert tertutup
                                    location.reload();
                                }
                            });
                        } else {
                            Swal.fire(
                                'Gagal!',
                                result.value?.message ||
                                'Terjadi kesalahan saat menghapus link.',
                                'error'
                            );
                        }
                    }
                });
            });
        });
    </script>
@endsection
