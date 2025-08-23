@extends('inc.layout') {{-- Sesuaikan dengan layout utama Anda --}}

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Daftar Plasma Antrian Poliklinik
                        </h2>
                        <div class="panel-toolbar">
                            <a href="{{ route('poliklinik.antrian-poli.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus mr-1"></i> Tambah Plasma
                            </a>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            <!-- datatable start -->
                            <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                <i id="loading-spinner" class="fas fa-spinner fa-spin"></i>
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Plasma</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($plasmas as $plasma)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-tv me-2 text-primary"></i>
                                                    <span>{{ $plasma->name }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a href="{{ route('poliklinik.antrian-poli.edit', $plasma->id) }}"
                                                        class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip"
                                                        title="Edit Plasma">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                    <a href="{{ route('poliklinik.antrian-poli.show', $plasma->id) }}"
                                                        target="_blank" class="btn btn-sm btn-outline-info"
                                                        data-bs-toggle="tooltip" title="Lihat Tampilan Plasma">
                                                        <i class="fas fa-desktop"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    Belum ada data Plasma Antrian
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-center">
                                            <button class="btn btn-outline-primary waves-effect waves-themed"
                                                onclick="window.location.reload()">
                                                <span class="fas fa-sync-alt"></span>
                                                Refresh Data
                                            </button>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                            <!-- datatable end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
