@if (isset($orderParameterId))
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
    {{-- Will be closed on template-hasil-datatable.blade.php --}}
@endif

<div class="row justify-content-center">
    <div class="col-xl-8">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    Form <span class="fw-300"><i>Pencarian</i></span>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">

                    <form action="{{ route('radiologi.template-hasil') }}" method="GET">
                        @csrf
                        <div class="form-group">
                            <div class="row">
                                <div class="col-xl-2" style="text-align: right">
                                    <label for="judul">Judul</label>
                                </div>
                                <div class="col-xl">
                                    <div class="form-group row">
                                        <div class="col-xl ">
                                            <input type="text" class="form-control"
                                                style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                id="cari-judul" name="cari-judul">
                                        </div>
                                    </div>
                                    @error('judul')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-xl-2" style="text-align: right">
                                    <label for="template">Template</label>
                                </div>
                                <div class="col-xl">
                                    <div class="form-group row">
                                        <div class="col-xl ">
                                            <input type="text" class="form-control"
                                                style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                id="cari-template" name="cari-template">
                                        </div>
                                    </div>
                                    @error('template')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-end mt-3">
                            <div class="col-xl-3">
                                @if (!isset($orderParameterId))
                                    <button class="btn btn-primary waves-effect waves-themed" id="tambah-btn"
                                        data-toggle="modal" data-target="#tambahTemplateModal">
                                        <span class="fal fa-plus mr-1"></span>
                                        Tambah
                                    </button>

                                    <div class="modal fade" id="tambahTemplateModal" tabindex="-1" role="dialog"
                                        aria-labelledby="importModalLabel" aria-hidden="true">
                                        @include('pages.simrs.radiologi.partials.template-hasil-tambah')
                                    </div>
                                @endif


                            </div>
                            <div class="col-xl-3">
                                <button type="submit" class="btn btn-outline-primary waves-effect waves-themed">
                                    <span class="fal fa-search mr-1"></span>
                                    Cari
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
