@extends('inc.layout')
@section('title', 'Pengajuan Absensi')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row mb-5">
            <div class="col-xl-12">
                @if (auth()->user()->is_request_attendance == 1)
                    <button type="button" class="btn btn-primary waves-effect waves-themed btn-ajukan" data-backdrop="static"
                        data-keyboard="false" data-toggle="modal" data-target="#tambah-data" title="Tambah User">
                        <span class="fal fa-plus-circle mr-1"></span>
                        Pengajuan Absensi
                    </button>
                @else
                    <div class="alert alert-danger">
                        Mohon maaf akses pengajuan absensi belum diberikan. Silahkan
                        menghubungi atasan masing-masing untuk dibukakan aksesnya!
                    </div>
                @endif

                @can('pengajuan pj')
                    <a href="{{ route('attendance-requests.form') }}" class="btn btn-primary">
                        <span class="fal fa-plus-circle mr-1"></span>
                        Tambah Form Pengajuan
                    </a>
                   
                    <a href="{{ route('attendances-requests.download-lampiran') }}" class="btn btn-primary">
                        <span class="fal fa-plus-circle mr-1"></span>
                        Tambah Form Pengajuan
                    </a> 
                @endcan
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Histori Pengajuan Absensi
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- datatable start -->
                            <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                <thead>
                                    <tr>
                                        <th style="white-space: nowrap">No</th>
                                        <th style="white-space: nowrap">Tanggal</th>
                                        <th style="white-space: nowrap">Clockin</th>
                                        <th style="white-space: nowrap">Clockout</th>
                                        <th style="white-space: nowrap">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($attendance_requests as $row)
                                        <tr>
                                            <td style="white-space: nowrap">{{ $loop->iteration }}</td>
                                            <td style="white-space: nowrap">{{ tgl($row->date) }}
                                            </td>
                                            <td style="white-space: nowrap">{{ $row->clockin ?? '*tidak diajukan' }}
                                            <td style="white-space: nowrap">{{ $row->clockout ?? '*tidak diajukan' }}
                                            </td>
                                            <td style="white-space: nowrap">
                                                <span
                                                    class="badge {{ $row->is_approved == 'Pending' ? 'badge-warning' : ($row->is_approved == 'Disetujui' ? 'badge-success' : ($row->is_approved == 'Ditolak' ? 'badge-danger' : ($row->is_approved == 'Verifikasi' ? 'badge-primary' : ''))) }}">
                                                    {{ ucfirst($row->is_approved) }} </span>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th style="white-space: nowrap">No</th>
                                        <th style="white-space: nowrap">Tanggal</th>
                                        <th style="white-space: nowrap">Clockin</th>
                                        <th style="white-space: nowrap">Clockout</th>
                                        <th style="white-space: nowrap">Status</th>
                                    </tr>
                                </tfoot>
                            </table>
                            <!-- datatable end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @if (auth()->user()->is_request_attendance == 1)
        @include('pages.absensi.pengajuan-absensi.partials.create')
    @endif
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script>
        // Preview Image Update Profile
        function previewImage() {
            const image = document.querySelector('#file');
            const imgPreview = document.querySelector('.img-preview')

            imgPreview.style.display = 'block';

            const oFReader = new FileReader();
            oFReader.readAsDataURL(image.files[0])

            oFReader.onload = function(oFREvent) {
                imgPreview.src = oFREvent.target.result;
            }
        }

        $(document).ready(function() {

            // Ajukan
            $('.btn-ajukan').click(function(e) {
                // Mendapatkan tanggal hari ini
                var today = new Date();
                // Mendapatkan tanggal satu hari sebelumnya
                var yesterday = new Date(today);
                yesterday.setDate(today.getDate() - 1);

                $('#store-form #date').datepicker({
                    todayBtn: "linked",
                    clearBtn: false,
                    todayHighlight: true,
                    format: "yyyy-mm-dd",
                    startDate: yesterday, // Mengatur tanggal mulai satu hari sebelumnya
                    endDate: today // Mengatur tanggal akhir hari ini
                });

                $('#create-attendance-form').modal('show');

                $('#store-form').on('submit', function(e) {
                    e.preventDefault();
                    let clockin = $('#clockin').val();
                    let clockout = $('#clockout').val();
                    let formData = new FormData(this);
                    formData.append("clockin", clockin);
                    formData.append("clockout", clockout);
                    formData.append("employee_id", "{{ auth()->user()->employee->id }}");
                    formData.append("approved_line_child",
                        "{{ auth()->user()->employee->approval_line }}");
                    formData.append("approved_line_parent",
                        "{{ auth()->user()->employee->approval_line_parent }}");

                    $.ajax({
                        type: "POST",
                        url: '/attendances/request/attendance',
                        data: formData,
                        processData: false,
                        contentType: false,
                        beforeSend: function() {
                            $('#store-form').find('.ikon-tambah').hide();
                            $('#store-form').find('.spinner-text').removeClass(
                                'd-none');
                        },
                        success: function(response) {
                            $('#store-form').find('.ikon-edit').show();
                            $('#store-form').find('.spinner-text').addClass('d-none');
                            $('#create-attendance-form').modal('hide');
                            showSuccessAlert(response.message)
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        },
                        error: function(xhr) {
                            $('#create-attendance-form').modal('hide');
                            showErrorAlert(xhr.responseJSON.error);
                        }
                    });
                });
            });

            // Datatable
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
