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
                                                    @if ($row->user)
                                                        <input type="checkbox" class="custom-control-input acc" 
                                                            id="aksi-{{ $i }}" data-id="{{ $row->user->id }}"
                                                            {{ $row->user->is_request_attendance == 1 ? 'checked' : '' }}
                                                            value="{{ $row->user->is_request_attendance }}"
                                                            autocomplete="off">
                                                        <label class="custom-control-label" id="label-{{ $i }}"
                                                            for="aksi-{{ $i }}">{{ $row->user->is_request_attendance == 1 ? 'On' : 'Off' }}</label>
                                                    @else
                                                        <p>User data not found.</p>
                                                    @endif
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
                const checkbox = $(this);
                const label = $(`label[for="${checkbox.attr('id')}"]`);

                if (checkbox.is(':checked')) {
                    label.text('Checked');
                    checkbox.val('on');
                } else {
                    label.text('Unchecked');
                    checkbox.val('off');
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

            $('input.acc').on('change', function() {
                var checkboxId = $(this).attr('id');
                var userId = $(this).data('id');
                var isChecked = $(this).is(':checked');
                var labelId = 'label-' + checkboxId.split('-')[1];

                // Set value berdasarkan checked state
                $(this).val(isChecked ? 1 : 0);
                
                // Update label text
                var label = $('#' + labelId);
                if (label.length) {
                    label.text(isChecked ? 'On' : 'Off');
                }

                $.ajax({
                    url: "{{ route('acc.update', ['user_id' => ':user_id']) }}".replace(':user_id',
                        userId),
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        userId: userId,
                        is_request_attendance: isChecked ? 1 : 0 // Kirim nilai 1 atau 0
                    },
                    success: function(response) {
                        console.log(response.message);
                    },
                    error: function(error) {
                        console.error(error);
                    }
                });
            });
        });
    </script>
@endsection
