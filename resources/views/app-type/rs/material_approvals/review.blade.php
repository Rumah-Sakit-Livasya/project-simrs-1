@extends('inc.layout-no-side')
@section('title', 'Review Persetujuan Material')

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('material-approvals.index') }}">Persetujuan Material</a></li>
            <li class="breadcrumb-item active">Review</li>
        </ol>

        <div class="row">
            <div class="col-lg-8">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Detail Material: <span class="fw-300"><i>{{ $material->material_name }}</i></span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            {{-- Material Photo --}}
                            @if ($material->image_path)
                                <div class="text-center mb-4">
                                    <img src="{{ asset('storage/' . $material->image_path) }}" class="img-fluid rounded"
                                        style="max-height: 400px;" alt="Foto Material">
                                </div>
                            @endif

                            {{-- Details Table --}}
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <td style="width: 30%;"><strong>Nama Material</strong></td>
                                        <td>{{ $material->material_name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Merek / Tipe</strong></td>
                                        <td>{{ $material->brand ?? '-' }} / {{ $material->type_or_model ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Spesifikasi Teknis</strong></td>
                                        <td>{!! nl2br(e($material->technical_specifications)) !!}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status Saat Ini</strong></td>
                                        <td>
                                            <span class="badge badge-primary">{{ $material->status }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Diajukan Oleh</strong></td>
                                        <td>{{ $material->submitter->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Dokumen Referensi</strong></td>
                                        <td>
                                            @if ($material->document)
                                                <a href="{{ route('documents.preview', $material->document->id) }}"
                                                    target="_blank">
                                                    {{ $material->document->document_number }} -
                                                    {{ $material->document->title }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div id="panel-2" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Formulir Review
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="reviewForm">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label" for="status">Tindakan</label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="" disabled selected>-- Pilih Tindakan --</option>
                                        <option value="Approved">Setujui (Approve)</option>
                                        <option value="Rejected">Tolak (Reject)</option>
                                        <option value="Revision Required">Minta Revisi (Revision Required)</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="remarks">Catatan / Alasan (Wajib jika
                                        ditolak/revisi)</label>
                                    <textarea class="form-control" id="remarks" name="remarks" rows="5"></textarea>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" id="submitReviewBtn" class="btn btn-primary">Kirim
                                        Review</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            // Init Select2
            $('#status').select2({
                width: '100%'
            });

            // Handle form submission
            $('#reviewForm').on('submit', function(e) {
                e.preventDefault();

                $('#submitReviewBtn').html('Mengirim...').prop('disabled', true);

                $.ajax({
                    url: "{{ route('material-approvals.processReview', $material->id) }}",
                    type: "POST",
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        showSuccessAlert(response.success);
                        // Redirect back to the main list after a short delay
                        setTimeout(function() {
                            window.location.href =
                                "{{ route('material-approvals.index') }}";
                        }, 1500);
                    },
                    error: function(xhr) {
                        let errorMsg = "Terjadi kesalahan. Silakan coba lagi.";
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            // Collect and display validation errors
                            errorMsg = Object.values(xhr.responseJSON.errors).map(function(
                                error) {
                                return error[0];
                            }).join('<br>');
                        } else if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMsg = xhr.responseJSON.error;
                        }
                        showErrorAlertNoRefresh(errorMsg);
                        $('#submitReviewBtn').html('Kirim Review').prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endsection
