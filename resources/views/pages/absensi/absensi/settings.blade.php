@extends('inc.layout')
@section('title', 'Pengajuan Absensi')
@section('extended-css')
    <style>
        @media(max-width: 760px) {

            .custom-switch .custom-control-input:checked~.custom-control-label::after,
            .custom-control-input:checked~.custom-control-label::before,
            .custom-control-input {
                z-index: 1;
            }

            .custom-control label.custom-control-label span {
                display: none;
            }

            .custom-control-input {
                z-index: 2 !important;
                opacity: 0;
                left: 8px;
                top: -2px;
                width: 20px;
                height: 20px;
            }
            
        }
    </style>
@endsection
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
                    @php
                        use Carbon\Carbon;
                    @endphp
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                <thead>
                                    <tr>
                                        <th style="white-space: nowrap">No</th>
                                        <th style="white-space: nowrap">Tanggal Pengajuan</th>
                                        <th style="white-space: nowrap">Unit</th>
                                        <th style="white-space: nowrap">Lampiran</th>
                                        <th style="white-space: nowrap">Daftar Pegawai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pengajuan as $i => $row)
                                        <tr>
                                            <td style="white-space: nowrap">{{ $loop->iteration }}</td>
                                            <td style="white-space: nowrap">
                                                {{ Carbon::parse($row->created_at)->format('Y-m-d H:i') }}
                                            </td>
                                            <td style="white-space: nowrap">{{ $row->organization->name ?? '-' }}
                                            </td>
                                            <td style="white-space: nowrap">
                                                <a href="{{ asset('storage/' . $row->lampiran) }}"
                                                    class="btn btn-primary py-1 px-2" target="_blank"><i
                                                        class="fas fa-eye"></i></a>
                                            </td>
                                            <td style="white-space: nowrap">
                                                @foreach ($row->attendance_request_lamp_details as $item)
                                                    <div
                                                        class="wrapper-pegawai {{ $loop->first ? '' : 'mt-2' }}  w-100 d-flex justify-content-between align-items-start">
                                                        <div class="pegawai">{{ $item->employee->fullname }}</div>
                                                        <div class="custom-control custom-switch">
                                                            @if ($item->employee->user && $item->tanggal == now()->format('Y-m-d'))
                                                                <input type="checkbox" class="custom-control-input acc"
                                                                    id="aksi-{{ $i }}"
                                                                    data-id="{{ $item->employee->user->id }}"
                                                                    {{ $item->employee->user->is_request_attendance == 1 ? 'checked' : '' }}
                                                                    value="{{ $item->employee->user->is_request_attendance }}"
                                                                    onclick="handleCheckboxChange(this)" autocomplete="off">
                                                                <label class="custom-control-label"
                                                                    id="label-{{ $i }}"
                                                                    for="aksi-{{ $i }}">
                                                                    <span class="label-text">
                                                                        {{ $item->employee->user->is_request_attendance == 1 ? 'On' : 'Off' }}
                                                                    </span>
                                                                </label>
                                                            @elseif(
                                                                $item->tanggal == Carbon::yesterday()->toDateString() &&
                                                                    (($item->employee->user && Carbon::parse($item->clock_in)->format('H:i') > '16:00') ||
                                                                        ($item->employee->user && Carbon::parse($item->clock_out)->format('H:i') < '08:00')))
                                                                <input type="checkbox" class="custom-control-input acc"
                                                                    id="aksi-{{ $i }}"
                                                                    data-id="{{ $item->employee->user->id }}"
                                                                    onclick="handleCheckboxChange(this)"
                                                                    {{ $item->employee->user->is_request_attendance == 1 ? 'checked' : '' }}
                                                                    value="{{ $item->employee->user->is_request_attendance }}"
                                                                    autocomplete="off">
                                                                <label class="custom-control-label"
                                                                    id="label-{{ $i }}"
                                                                    for="aksi-{{ $i }}">
                                                                    <span class="label-text">
                                                                        {{ $item->employee->user->is_request_attendance == 1 ? 'On' : 'Off' }}
                                                                    </span>
                                                                </label>
                                                            @else
                                                                <p class="badge badge-success mb-0">Selesai</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="mt-0">
                                                        <span
                                                            class="badge badge-primary">{{ Carbon::parse($item->clock_in)->format('H:i') ?? '-' }}</span>
                                                        <span
                                                            class="badge badge-primary">{{ Carbon::parse($item->clock_out)->format('H:i') ?? '-' }}</span>
                                                    </div>
                                                @endforeach
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th style="white-space: nowrap">No</th>
                                        <th style="white-space: nowrap">Tanggal Pengajuan</th>
                                        <th style="white-space: nowrap">Unit</th>
                                        <th style="white-space: nowrap">Lampiran</th>
                                        <th style="white-space: nowrap">Daftar Pegawai</th>
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
        function handleCheckboxChange(element) {
            let checkbox = $(element);
            let checkboxId = checkbox.attr('id');
            let userId = checkbox.data('id');
            let isChecked = checkbox.is(':checked');
            let labelId = 'label-' + checkboxId.split('-')[1];
            console.log(labelId);

            // Set nilai checkbox
            checkbox.val(isChecked ? 1 : 0);

            // Update teks label
            let label = $('#' + labelId + " span");
            console.log(label)
            label.text(isChecked ? 'On' : 'Off');

            // Kirim request AJAX
            $.ajax({
                url: "{{ route('acc.update', ['user_id' => ':user_id']) }}".replace(':user_id',
                    userId),
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    userId: userId,
                    is_request_attendance: isChecked ? 1 : 0
                },
                success: function(response) {
                    console.log(response.message || 'Update berhasil!');
                },
                error: function(error) {
                    console.error('Error:', error);
                    alert('Gagal memperbarui data. Coba lagi.');
                }
            });
        }

        // function previewImage() {
        //     const image = document.querySelector('#file');
        //     const imgPreview = document.querySelector('.img-preview')

        //     imgPreview.style.display = 'block';

        //     const oFReader = new FileReader();
        //     oFReader.readAsDataURL(image.files[0])

        //     oFReader.onload = function(oFREvent) {
        //         imgPreview.src = oFREvent.target.result;
        //     }
        // }

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
            // $('input.acc').on('change', function() {
            //     var checkboxId = $(this).attr('id');
            //     var userId = $(this).data('id');
            //     var isChecked = $(this).is(':checked');
            //     var labelId = 'label-' + checkboxId.split('-')[1];

            //     // Set value berdasarkan checked state
            //     $(this).val(isChecked ? 1 : 0);

            //     // Update label text
            //     var label = $('#' + labelId);
            //     if (label.length) {
            //         label.text(isChecked ? 'On' : 'Off');
            //     }

            //     $.ajax({
            //         url: "{{ route('acc.update', ['user_id' => ':user_id']) }}".replace(':user_id',
            //             userId),
            //         method: 'POST',
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         },
            //         data: {
            //             userId: userId,
            //             is_request_attendance: isChecked ? 1 : 0 // Kirim nilai 1 atau 0
            //         },
            //         success: function(response) {
            //             console.log(response.message);
            //         },
            //         error: function(error) {
            //             console.error(error);
            //         }
            //     });
            // });
        });
    </script>
@endsection
