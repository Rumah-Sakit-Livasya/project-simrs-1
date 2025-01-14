@extends('inc.layout')
@section('title', 'Survei Kebersihan Kamar')
@section('content')

@dd($survei)
    <main id="js-page-content" role="main" class="page-content">
        <div class="row mb-5">
            <div class="col-xl-12">
                <a href="{{ route('tambah.survei.kebersihan-kamar') }}" class="btn btn-primary waves-effect waves-themed"
                    title="Tambah Survei">
                    <span class="fal fa-plus-circle mr-1"></span>
                    Tambah Survei
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Tabel Survei
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
                                        <th style="white-space: nowrap">Tanggal</th>
                                        <th style="white-space: nowrap">Petugas</th>
                                        <th style="white-space: nowrap">Ruangan</th>
                                        <th style="white-space: nowrap">Dokumentasi</th>
                                        <th style="white-space: nowrap">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($survei as $row)
                                        <tr>
                                            {{-- <td style="white-space: nowrap">{{ $user->template_user->foto }}</td> --}}
                                            <td style="white-space: nowrap">{{ $loop->iteration }}</td>
                                            <td style="white-space: nowrap">{{ tgl_waktu($row->tanggal) }}</td>
                                            <td style="white-space: nowrap">{{ $row->employee->name }}</td>
                                            <td style="white-space: nowrap">{{ $row->kamar->name }}</td>
                                            <td style="white-space: nowrap">
                                                <img src="{{ url('storage/private/survei/kebersihan_kamar/' . $row->dokumentasi) }}"
                                                    width="100%" alt="">
                                            </td>
                                            <td style="white-space: nowrap">
                                                <a href="{{ route('edit.survei.kebersihan-kamar', $row->id) }}"
                                                    class="badge mx-1 badge-primary p-2 border-0 text-white" title="Ubah">
                                                    <span class="fal fa-pencil ikon-edit"></span>
                                                    <div class="span spinner-text d-none">
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span>
                                                        Loading...
                                                    </div>
                                                </a>
                                                <button type="button" data-backdrop="static" data-keyboard="false"
                                                    class="badge mx-1 badge-success p-2 border-0 text-white btn-hapus"
                                                    data-id="{{ $row->id }}" title="Hapus">
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
                                        <th style="white-space: nowrap">Tanggal</th>
                                        <th style="white-space: nowrap">Petugas</th>
                                        <th style="white-space: nowrap">Ruangan</th>
                                        <th style="white-space: nowrap">Dokumentasi</th>
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
        @include('pages.master-data.banks.partials.create-data')
        @include('pages.master-data.banks.partials.update-data')
    </main>
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        /* demo scripts for change table color */
        /* change background */
        $(document).ready(function() {
            let dataId = null;

            $('#update-form').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                $.ajax({
                    type: "POST",
                    url: '/api/dashboard/banks/update/' + dataId,
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

            $('#store-form').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                $.ajax({
                    type: "POST",
                    url: '/api/dashboard/banks/store/',
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

            $('.btn-hapus').click(function() {
                let id = $(this).data('id')
                let url = "{{ route('delete.survei.kebersihan-kamar', ':id') }}".replace(':id', id);

                let confirmationMessage = 'Yakin ingin menghapus survei ini?';


                if (confirm(confirmationMessage)) {


                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            showSuccessAlert(response.message);

                            setTimeout(() => {
                                console.log('Reloading the page now.');
                                window.location.reload();
                            }, 1000);
                        },
                        error: function(xhr, status, error) {
                            showErrorAlert('Terjadi kesalahan: ' + error);
                        }
                    });
                } else {
                    console.log('Penghapusan dibatalkan oleh pengguna.');
                }
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
