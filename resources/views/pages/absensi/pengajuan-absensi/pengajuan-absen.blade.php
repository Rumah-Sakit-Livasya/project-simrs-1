@extends('inc.layout')
@section('title', 'Pengajuan Absensi')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            DAFTAR FORM PENGAJUAN ABSENSI
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @can('create pengajuan absen')
        @include('pages.absensi.pengajuan-absensi.partials.create')
    @endcan
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
