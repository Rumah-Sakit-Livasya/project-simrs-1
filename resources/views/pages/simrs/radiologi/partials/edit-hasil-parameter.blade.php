@extends('inc.layout-no-side')
@section('title', 'Pemeriksaan')
@section('extended-css')
    <style src="{{ asset('summernote-0.9.0/summernote-bs4.min.css') }}"></style>
@endsection
@section('content')

    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Pemeriksaan [{{ $parameter->parameter_radiologi->parameter }}]
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="{{ route('order.radiologi.parameter-check-update') }}" method="POST">
                                <input type="hidden" name="parameter_id" value="{{ $parameter->id }}">
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}">
                                @csrf
                                <div class="form-group">
                                    <textarea name="catatan" id="summernote">
                                        {{ $parameter->catatan }}
                                    </textarea>
                                </div>
                                <div class="col-xl-12 mt-5">
                                    <div class="row">
                                        <div class="col-xl-6">
                                            <a onclick="window.close()"
                                                class="btn btn-lg btn-default waves-effect waves-themed">
                                                <span class="fal fa-arrow-left mr-1 text-primary"></span>
                                                <span class="text-primary">Kembali</span>
                                            </a>
                                        </div>
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
            </div>
        </div>

    </main>
@endsection

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="{{ asset('summernote-0.9.0/summernote-bs4.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#summernote').summernote({
                height: 400,
                placeholder: 'Hasil pemeriksaan...'
            });
        });
    </script>
@endsection
