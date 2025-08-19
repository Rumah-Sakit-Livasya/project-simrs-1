@extends('inc.layout')
@section('title', 'Konfirmasi Asuransi')
@section('content')

    <main id="js-page-content" role="main" class="page-content">

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Tabel Testing
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="container">
                                <h1>Testing</h1>
                                <p>Ini adalah halaman testing.</p>
                                <button class="btn btn-primary" id="create-btn">
                                    create
                                </button>
                            </div>
                            <div class="container mt-4">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nama</th>
                                            <th>Tanggal</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($testingData as $item)
                                            <tr>
                                                <td>{{ $item->id }}</td>
                                                <td>{{ $item->nama }}</td>
                                                <td>{{ $item->tanggal }}</td>
                                                <td>
                                                    <button class="btn btn-warning edit-btn"
                                                        data-id="{{ $item->id }}">Edit</button>
                                                    <button class="btn btn-danger del-btn"
                                                        data-id="{{ $item->id }}">Hapus</button>
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
    {{-- {{ $konfirmasiAsuransi->links() }} --}}
    @include('testing.partials.create')
    @include('testing.partials.edit')
@endsection

@section('plugin')
    <script>
        $(document).ready(function() {
            // Show Modal for Create
            $('#create-btn').on('click', function() {
                $('#tambah-data').modal('show');
            });

            // Store Form 
            $('#store-form').on('submit', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'POST',
                    url: '/testing-data',
                    data: formData,
                    success: function(response) {
                        // Handle success response
                        showSuccessAlert(response.success);
                        $('#tambah-data').modal('hide');
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        console.error(xhr.responseText);
                        $('#tambah-data').modal('hide');
                        showErrorAlertNoRefresh(xhr.responseText);
                    }
                });
            });

            // Edit Form
            // GET data for edit
            $('.edit-btn').on('click', function() {
                var id = $(this).data('id');
                $.ajax({
                    type: 'GET',
                    url: '/testing-data/' + id + '/get',
                    success: function(response) {
                        // Populate the form with the response data
                        $('#edit-data').modal('show');
                        $('#edit-data #nama').val(response.nama);
                        $('#edit-data #tanggal').val(response.tanggal);

                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });

            // Update Form 
            $('#update-form').on('submit', function(e) {
                e.preventDefault();
                var id = $('.edit-btn').data('id');
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '/testing-data/' + id,
                    data: formData,
                    success: function(response) {
                        // Handle success response
                        $('#edit-data').modal('hide');
                        showSuccessAlert(response.success);
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        $('#edit-data').modal('hide');
                        showErrorAlertNoRefresh(xhr.responseText);
                    }
                });
            });

            // Delete Form
            $('.del-btn').on('click', function() {
                var id = $(this).data('id');

                Swal.fire({
                    title: 'Apakah kamu yakin?',
                    text: "Data ini akan dihapus dan tidak bisa dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'DELETE',
                            url: '/testing-data/' + id,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Berhasil!',
                                    'Data berhasil dihapus.',
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr, status, error) {
                                Swal.fire(
                                    'Gagal!',
                                    'Terjadi kesalahan saat menghapus data.',
                                    'error'
                                );
                                console.error(xhr.responseText);
                            }
                        });
                    }
                });
            });

        });
    </script>
@endsection
