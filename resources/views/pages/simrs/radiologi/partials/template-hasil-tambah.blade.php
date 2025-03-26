@php
    $edit = isset($template);
@endphp

<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="importModalLabel">{{ $edit ? 'Edit' : 'Tambah' }} Template
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="tambah-template-form" method="POST">
                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}">
                @csrf
                <input type="text" class="form-control" name="judul{{ $edit ? $template->id : '' }}" id="judul" value="{{ $edit ? $template->judul : '' }}"
                    placeholder="Judul template...">
                <br>
                <div class="form-group">
                    <textarea name="template{{ $edit ? $template->id : '' }}" class="summernote">{!! $edit ? $template->template : '' !!}</textarea>
                </div>
                <div class="col-xl-12 mt-5">
                    <div class="row">
                        <div class="col-xl-6 text-right">
                            <button type="submit"
                                onclick="TemplateHasilRadiologiClass.handleAddButtonClick(event, {{ $edit ? $template->id : 0 }})"
                                id="radiologi-submit" class="btn btn-lg btn-primary waves-effect waves-themed">
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
