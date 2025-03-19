<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="importModalLabel">Upload photo
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <input type="hidden" class="input-{{ $parameter->id }}" name="parameter_id" value="{{ $parameter->id }}">
            <input type="hidden" class="input-{{ $parameter->id }}" name="user_id" value="{{ auth()->user()->id }}">
            <input type="hidden" class="input-{{ $parameter->id }}" name="employee_id"
                value="{{ auth()->user()->employee->id }}">
            @csrf
            <div class="form-group">
                <div align="center" class="container">
                    <h1>Upload Photo Parameter</h1>
                    <p>Dapat memilih beberapa photo</p>
                    <input type="file" class="form-control file-input input-{{ $parameter->id }}" name="photo[]"
                        multiple accept="image/*">
                    <br>
                    <button type="button" onclick="UploadPhotoParameterRadiologiClass.handleUploadButtonClick(event)"
                        data-parameter-id="{{ $parameter->id }}" class="btn btn-primary upload-btn">Upload</button>
                </div>
            </div>
        </div>
    </div>
</div>
