@extends('inc.layout')
@section('title', 'Konfirmasi Kehadiran')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row d-flex justify-content-center">
            <div class="col-md-6">
                @foreach ($diklat as $item)
                    <div class="card mb-g">
                        <div class="card-body pb-4 px-4">
                            <div class="d-flex flex-row pb-3 pt-2  border-top-0 border-left-0 border-right-0">
                                <h5 class="mb-0 flex-1 text-dark fw-500">
                                    {{ $item->judul }}
                                    <small class="m-0 l-h-n">
                                        {{ $item->pembicara }}
                                    </small>
                                </h5>
                                <span class="text-muted fs-xs opacity-70">

                                    {{ $item->created_at->diffForHumans(null, true) }}
                                </span>
                            </div>
                            <div class="pb-3 pt-2 border-top-0 border-left-0 border-right-0 text-muted">
                                <p>
                                    Tanggal : <span class="text-danger">{{ tglDefault($item->datetime) }}</span> <br>
                                    Waktu : <span class="text-danger">{{ waktuDefault($item->datetime) }} </span>
                                    <hr style="border-color: #dddddd">
                                </p>
                                <p>{{ $item->catatan }}</p>
                            </div>
                            <div class="d-block align-items-center mt-1">
                                <button class="btn btn-primary btn-block" id="btn-accept" data-id="{{ $item->id }}">
                                    <div class="ikon">
                                        <span class="fal fa-check mr-1"></span>
                                        Konfirmasi Kehadiran
                                    </div>
                                    <div class="span spinner-text d-none">
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                        Loading...
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </main>
@endsection
@section('plugin')
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        /* demo scripts for change table color */
        /* change background */
        $(document).ready(function() {
            $('#btn-accept').on('click', function(e) {
                e.preventDefault();
                let formData = {
                    employee_id: "{{ auth()->user()->employee->id }}",
                }
                let id = $(this).attr('data-id');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "PUT",
                    url: '/api/dashboard/pendidikan-pelatihan/confirm/' + id,
                    data: formData,
                    beforeSend: function() {
                        $('#btn-accept').find('.ikon').hide();
                        $('#btn-accept').find('.spinner-text')
                            .removeClass(
                                'd-none');
                    },
                    success: function(response) {
                        $('#btn-accept').find('.ikon').show();
                        $('#btn-accept').find('.spinner-text').addClass('d-none');
                        showSuccessAlert(response.message)
                        setTimeout(function() {
                            window.location.href =
                                "{{ route('dashboard') }}"; // Ganti dengan URL yang ingin Anda muat ulang
                        }, 1000);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            })

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
