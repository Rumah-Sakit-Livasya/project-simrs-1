@extends('inc.layout')
@section('title', 'User')
@section('content')
    <main id="js-page-content" role="main" class="page-content">

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Kirim Pesan Whatsapp
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="{{ route('whatsapp.send') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('POST')
                                <div class="form-group">
                                    <label class="form-label" for="nama">Nama Pasien</label>
                                    <input type="text" id="nama" name="nama" class="form-control"
                                        placeholder="Nama Pasien...">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="nomor">Nomor HP</label>
                                    <input type="text" id="nomor" name="nomor" class="form-control"
                                        placeholder="Nomor HP...">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="message">Text area</label>
                                    <textarea class="form-control" id="message" name="message" rows="5"></textarea>
                                </div>
                                <div class="form-group mb-0">
                                    <label class="form-label">File </label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="file" id="customFile">
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block mt-3">Kirim</button>
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
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script></script>
@endsection
