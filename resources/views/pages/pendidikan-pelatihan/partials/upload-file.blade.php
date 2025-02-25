<!-- Modal Upload File -->
<div class="modal fade" id="uploadFileModal" tabindex="-1" role="dialog" aria-labelledby="uploadFileModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadFileModalLabel">Upload File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('time.schedule.rapat.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="file" name="file" class="form-control" required>
                    <input type="hidden" name="id" id="ts-id" value="">
                    <input type="hidden" name="type" id="upload-type" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
