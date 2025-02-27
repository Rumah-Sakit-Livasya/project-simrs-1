@extends('inc.layout')
@section('title', 'Parameter Radiologi')
@section('extended-css')
    <style>
        div.table-responsive>div.dataTables_wrapper>div.row>div[class^="col-"]:last-child {
            padding: 0px;
        }

        .dataTables_scrollHeadInner,
        .dataTables_scrollFootInner {
            width: 100% !important;
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
                            Departemen untuk {{ $tarif_registrasi->nama_tarif }}
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="departement-form">
                                @csrf
                                <div class="mt-4">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th width="10%">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="check-all">
                                                        <label class="custom-control-label" for="check-all"></label>
                                                    </div>
                                                </th>
                                                <th>Nama Departemen</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($departement as $dept)
                                                <tr>
                                                    <td class="text-center">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox"
                                                                class="custom-control-input dept-checkbox"
                                                                id="dept{{ $dept->id }}" name="departments[]"
                                                                value="{{ $dept->id }}"
                                                                {{ $tarif_registrasi->departements->contains($dept->id) ? 'checked' : '' }}>
                                                            <label class="custom-control-label"
                                                                for="dept{{ $dept->id }}"></label>
                                                        </div>
                                                    </td>
                                                    <td>{{ $dept->name }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <button type="submit" class="btn btn-primary mt-3 btn-block">Update Departemen</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    <script>
        $(document).ready(function() {
            // Handle "Check All" functionality
            $('#check-all').change(function() {
                $('.dept-checkbox').prop('checked', $(this).prop('checked'));
            });

            // Update "Check All" state based on individual checkboxes
            $('.dept-checkbox').change(function() {
                if ($('.dept-checkbox:checked').length === $('.dept-checkbox').length) {
                    $('#check-all').prop('checked', true);
                } else {
                    $('#check-all').prop('checked', false);
                }
            });

            // Handle form submission
            $('#departement-form').on('submit', function(e) {
                e.preventDefault();
                const url =
                    "{{ route('master-data.setup.tarif-registrasi.departements.store', ['tarifRegistId' => $tarif_registrasi->id]) }}";

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        showSuccessAlert(response.message);
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    },
                    error: function(xhr, status, error) {
                        showErrorAlert('Terjadi kesalahan: ' + error);
                    }
                });
            });
        });
    </script>
@endsection
