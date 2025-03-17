<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="importModalLabel">Tambah Template
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('radiologi.template.tambah') }}" method="POST">
                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}">
                @csrf
                <input type="text" class="form-control" name="judul" id="judul" placeholder="Judul template...">
                <br>
                <div class="form-group">
                    <textarea name="template" id="summernote"></textarea>
                </div>
                <div class="col-xl-12 mt-5">
                    <div class="row">
                        <div class="col-xl-6 text-right">
                            <button type="submit" id="radiologi-submit"
                                class="btn btn-lg btn-primary waves-effect waves-themed">
                                <span class="fal fa-save mr-1"></span>
                                Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('summernote-0.9.0/summernote-bs4.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var summernoteElement = document.getElementById('summernote');
        if (summernoteElement) {
            summernoteElement.summernote({
                height: 400,
                placeholder: 'Isi template...'
            });
        }
    });
</script>
