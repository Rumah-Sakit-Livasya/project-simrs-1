@extends('inc.layout')
@section('title', 'Migrasi Nilai Normal Laboratorium')

@section('content')
    <main id="js-page-content" role="main" class="page-content" @style('margin-top: 5rem;')>
        <div class="container-fluid" style="padding-top: 20px;">
            <div class="row">
                {{-- Pesan Sukses atau Error --}}
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Form Export --}}
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Export Template Tarif Peralatan</div>
                        <div class="card-body">
                            <form action="{{ route('peralatan.export') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="group_penjamin_id" class="form-label">Pilih Grup Penjamin</label>
                                    <select name="group_penjamin_id" id="group_penjamin_id" class="form-control" required>
                                        <option value="">-- Pilih Grup --</option>
                                        @foreach ($groupPenjamin as $grup)
                                            <option value="{{ $grup->id }}">{{ $grup->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-success">Download Template</button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Form Import --}}
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Import Data dari Template</div>
                        <div class="card-body">
                            <form action="{{ route('peralatan.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="file" class="form-label">Pilih File Excel</label>
                                    <input type="file" name="file" id="file" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Upload dan Import Data</button>
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
        function cek_upload(obj) {
            if (!document.getElementById('file').value) {
                alert('File belum dipilih!');
                return false;
            }
            obj.disabled = true;
            obj.innerHTML = 'Mohon tunggu...';
            document.getElementById('fupload').submit();
        }
    </script>
@endsection
