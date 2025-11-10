@extends('inc.layout')
@section('title', 'Tambah Template SOAP')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-lg-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            <i class="fal fa-plus-circle mr-2"></i> Formulir Tambah Template SOAP
                        </h2>
                        <div class="panel-toolbar">
                            <a href="{{ route('dokter.template-soap.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fal fa-arrow-left mr-1"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form method="post" action="{{ route('dokter.template-soap.store') }}" autocomplete="off">
                                @csrf
                                <div class="form-group row">
                                    <label for="template_name" class="col-sm-2 col-form-label">Nama Template</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="template_name" id="template_name"
                                            value="{{ old('template_name') }}"
                                            class="form-control @error('template_name') is-invalid @enderror">
                                        @error('template_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="card border mb-g">
                                            <div class="card-header bg-primary-600">
                                                <h5 class="card-title text-white mb-0">Subjective</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <textarea class="form-control" id="subjective" name="subjective" rows="8">{{ old('subjective') }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="card border mb-g">
                                            <div class="card-header bg-success-600">
                                                <h5 class="card-title text-white mb-0">Objective</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <textarea class="form-control" id="objective" name="objective" rows="8">{{ old('objective') }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="card border mb-g">
                                            <div class="card-header bg-danger-600">
                                                <h5 class="card-title text-white mb-0">Assesment</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <textarea class="form-control" id="assesment" name="assesment" rows="8">{{ old('assesment') }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="card border mb-g">
                                            <div class="card-header bg-warning-600">
                                                <h5 class="card-title text-white mb-0">Planning</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <textarea class="form-control" id="planning" name="planning" rows="8">{{ old('planning') }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="alert alert-info">
                                    <strong>Panduan Link Data :</strong><br>
                                    <code>{TTV}</code> = Untuk memanggil data tanda tanda vital <br>
                                    <code>{PAWAL}</code> = Untuk Memanggil inputan pemeriksaan awal
                                </div>

                                <div class="panel-footer text-right">
                                    <button type="submit" class="btn btn-primary"><i class="fal fa-save mr-1"></i>
                                        Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
