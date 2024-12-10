@extends('inc.layout')
@section('title', 'Pengajuan Absensi')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            ACC PENGAJUAN ABSENSI
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                <thead>
                                    <tr>
                                        <th style="white-space: nowrap">No</th>
                                        <th style="white-space: nowrap">Nama</th>
                                        <th style="white-space: nowrap">Unit</th>
                                        <th style="white-space: nowrap">Acc Pengajuan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employees as $i => $row)
                                        <tr>
                                            <td style="white-space: nowrap">{{ $loop->iteration }}</td>
                                            <td style="white-space: nowrap">{{ $row->fullname }}
                                            </td>
                                            <td style="white-space: nowrap">{{ $row->organization->name ?? '-' }}
                                            </td>
                                            <td style="white-space: nowrap">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="aksi-{{ $i }}" value="on">
                                                    <label class="custom-control-label"
                                                        for="aksi-{{ $i }}">Unchecked</label>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th style="white-space: nowrap">No</th>
                                        <th style="white-space: nowrap">Nama</th>
                                        <th style="white-space: nowrap">Unit</th>
                                        <th style="white-space: nowrap">Acc Pengajuan</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
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

            $('.switch-control').on('change', function() {
                // Dapatkan checkbox yang berubah
                const checkbox = $(this);

                // Temukan label yang terkait menggunakan atribut "for"
                const label = $(`label[for="${checkbox.attr('id')}"]`);

                // Perbarui teks label berdasarkan status checkbox
                if (checkbox.is(':checked')) {
                    label.text('Checked'); // Ubah teks menjadi "Checked" jika dicentang
                    checkbox.val('on'); // Ubah nilai checkbox
                } else {
                    label.text('Unchecked'); // Ubah teks menjadi "Unchecked" jika tidak dicentang
                    checkbox.val('off'); // Ubah nilai checkbox
                }
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
