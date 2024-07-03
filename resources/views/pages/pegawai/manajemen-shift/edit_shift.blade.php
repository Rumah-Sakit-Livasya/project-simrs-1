@extends('inc.layout')
@section('title', 'Edit Management Shift')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row mb-3">
            <div class="col-xl-12">
                <a href="{{ url()->previous() }}" class="btn btn-primary waves-effect waves-themed">
                    <span class="fal fa-caret-circle-left"></span>
                    Kembali
                </a>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Edit Shift &nbsp;<span
                                class="text-primary">{{ $attendances[0]->employees->fullname ?? '-' }}</span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="" method="post" id="update-form">
                                @csrf
                                @method('PUT')
                                @foreach ($attendances as $i => $item)
                                    <div class="row mb-2">
                                        <div class="col-md-2 col-xs-2 col-sm-2 d-flex align-items-center">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    {{-- <input type="text" class="form-control" value="{{ $item->date }}"
                                                        disabled> --}}
                                                    <label class="font-weight-bold"
                                                        style="font-size: 1em; color: #666666 !important;">{{ tgl($item->date) }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-10 col-xs-10 col-sm-10">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input type="hidden" name="tanggal" id="tanggal"
                                                        value="{{ $item->date }}">
                                                    @if ($item->attendance_code)
                                                        <select
                                                            class="select2 form-control w-100  @error('shift_id') is-invalid @enderror"
                                                            id="shift_id_{{ $i++ }}" name="shift_id[]">
                                                            <option value="CT" selected>CT</option>
                                                            @foreach ($shifts as $col)
                                                                <option value="{{ $col->id }}">
                                                                    {{ $col->name }} -
                                                                    {{ '(' . $col->time_in . ' - ' . $col->time_out . ')' }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    @else
                                                        <select
                                                            class="select2 form-control w-100  @error('shift_id') is-invalid @enderror"
                                                            id="shift_id_{{ $i++ }}" name="shift_id[]">
                                                            <option value="CT">CT</option>
                                                            @foreach ($shifts as $col)
                                                                <option value="{{ $col->id }}"
                                                                    {{ $item->shift_id == $col->id ? 'selected' : '' }}>
                                                                    {{ $col->name }} -
                                                                    {{ '(' . $col->time_in . ' - ' . $col->time_out . ')' }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    @endif
                                                    @error('shift_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <button type="submit" class="btn btn-primary btn-block float-right my-3">
                                    <div class="ikon-tambah">
                                        <span class="fas fa-pencil mr-1"></span>
                                        Update
                                    </div>
                                    <div class="span spinner-text d-none">
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                        Loading...
                                    </div>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
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
                });
            });
            $('#datepicker-modal-2').daterangepicker({
                opens: 'left'
            }, function(start, end, label) {
                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end
                    .format('YYYY-MM-DD'));
            });

            $('#update-form').on('submit', function(e) {
                e.preventDefault();
                let employeeId = "{{ $attendances[1]->employee_id ?? null }}";
                var data = []; // Array untuk menampung data yang akan dikirimkan

                // Loop melalui setiap elemen formulir
                $('form#update-form .row').each(function() {
                    var date = $(this).find('#tanggal').val(); // Mengambil nilai tanggal dari label
                    var shiftId = $(this).find('select')
                        .val(); // Mengambil nilai shift_id dari select

                    // Menambahkan data ke dalam array
                    data.push({
                        date: date,
                        shift_id: shiftId,
                        employee_id: employeeId
                    });
                });
                $.ajax({
                    type: "PUT",
                    url: '/api/dashboard/management-shift/update',
                    data: {
                        attendances: data
                    },
                    beforeSend: function() {
                        $('#update-form').find('.ikon-tambah').hide();
                        $('#update-form').find('.spinner-text').removeClass('d-none');
                    },
                    success: function(response) {
                        $('#update-form').find('.ikon-tambah').show();
                        $('#update-form').find('.spinner-text').addClass('d-none');
                        showSuccessAlert(response.message)
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    error: function(xhr) {
                        showErrorAlert(xhr.responseJSON.error);
                    }
                });
            });

            $('.btn-accept').on('click', function(e) {
                e.preventDefault();
                console.log("click");
                let formData = {
                    employee_id: "{{ auth()->user()->employee->id }}"
                }
                let id = $(this).attr('data-id');
                $.ajax({
                    type: "PUT",
                    url: '/employee/approve/day-off/' + id,
                    data: formData,
                    beforeSend: function() {
                        $('#approve-request').find('.ikon-edit').hide();
                        $('#approve-request').find('.spinner-text')
                            .removeClass(
                                'd-none');
                    },
                    success: function(response) {
                        showSuccessAlert(response.message)
                        setTimeout(function() {
                            location.reload();
                        }, 500);
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
