@extends('inc.layout')
@section('title', 'User')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row d-flex justify-content-center">
            <div class="col-md-6">
                @foreach ($day_off_requests as $item)
                    <div class="card mb-g">
                        <div class="card-body pb-4 px-4">
                            <div class="d-flex flex-row pb-3 pt-2  border-top-0 border-left-0 border-right-0">
                                <div class="d-inline-block align-middle status status-success mr-3">
                                    <span class="profile-image rounded-circle d-block"
                                        style="background-image:url('{{ $item->employee->gender == 'Laki-laki' ? asset('img/demo/avatars/avatar-c.png') : asset('img/demo/avatars/avatar-p.png') }}'); background-size: cover; margin-top: -6px"></span>
                                </div>
                                <h5 class="mb-0 flex-1 text-dark fw-500">
                                    {{ $item->employee->fullname }}
                                    <small class="m-0 l-h-n">
                                        {{ $item->employee->organization->name }}
                                    </small>
                                </h5>
                                <span class="text-muted fs-xs opacity-70">

                                    {{ $item->created_at->diffForHumans(null, true) }}
                                </span>
                            </div>
                            <div class="pb-3 pt-2 border-top-0 border-left-0 border-right-0 text-muted">
                                <p>
                                    <span class="text-primary font-weight-bold" style="font-size: 1.1em">Pengajuan
                                        @php
                                            $attendance_code_id = $item->attendance_code->id;
                                        @endphp
                                        {{ $item->attendance_code->description }}</span> :
                                    <br>
                                    Mulai : <span class="text-danger">{{ tgl($item->start_date) }}</span> <br>
                                    Akhir : <span class="text-danger">{{ tgl($item->end_date) }} </span>
                                    <hr style="border-color: #dddddd">
                                </p>
                                <p>{{ $item->description }}</p>
                                <img src="{{ asset('/storage/img/pengajuan/cuti/' . $item->photo) }}" class="img-fluid"
                                    alt="">
                            </div>
                            <div class="d-block align-items-center mt-1">
                                <button class="btn btn-primary btn-block" id="btn-accept" data-id="{{ $item->id }}">
                                    <div class="ikon">
                                        <span class="fal fa-check mr-1"></span>
                                        Setujui
                                    </div>
                                    <div class="span spinner-text d-none">
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                        Loading...
                                    </div>
                                </button>

                                <button class="btn btn-danger mt-2 btn-block w-100" id="btn-tolak"
                                    data-id="{{ $item->id }}">
                                    <div class="ikon">
                                        <span class="fal fa-times mr-1"></span>
                                        Tolak
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
            $(function() {
                $('.select2').select2({
                    placeholder: 'Pilih Data Berikut',
                    dropdownParent: $('#tambah-data')
                });
            });
            $('#datepicker-modal-2').daterangepicker({
                opens: 'left'
            }, function(start, end, label) {
                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end
                    .format('YYYY-MM-DD'));
            });

            $('#store-form').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                formData.append("employee_id", "{{ auth()->user()->employee->id }}");
                formData.append("approved_line_child", "{{ auth()->user()->employee->approval_line }}");
                formData.append("approved_line_parent",
                    "{{ auth()->user()->employee->approval_line_parent }}");

                $.ajax({
                    type: "POST",
                    url: '/attendances/request/day-off',
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
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });

            $('#btn-accept').on('click', function(e) {
                e.preventDefault();
                const attendanceCodeId = @json($attendance_code_id);
                let formData = {
                    employee_id: "{{ auth()->user()->employee->id }}",
                    attendance_code_id: attendanceCodeId
                }
                let id = $(this).attr('data-id');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "PUT",
                    url: '/attendances/approve/day-off/' + id,
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

            $('#btn-tolak').on('click', function(e) {
                e.preventDefault();
                console.log("click");
                let formData = {
                    employee_id: "{{ auth()->user()->employee->id }}"
                }
                let id = $(this).attr('data-id');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "PUT",
                    url: '/attendances/reject/day-off/' + id,
                    data: formData,
                    beforeSend: function() {
                        $('#btn-tolak').find('.ikon').hide();
                        $('#btn-tolak').find('.spinner-text')
                            .removeClass(
                                'd-none');
                    },
                    success: function(response) {
                        $('#btn-tolak').find('.ikon').show();
                        $('#btn-tolak').find('.spinner-text').addClass('d-none');
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
