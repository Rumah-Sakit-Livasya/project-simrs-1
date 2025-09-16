@extends('inc.layout')
@section('title', 'Geolocation RS (Leaflet)')

@section('extended-css')
    {{-- Memasukkan CSS Leaflet --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <style>
        /* Pastikan div peta memiliki tinggi yang ditentukan */
        #map {
            height: 450px;
            width: 100%;
            border-radius: .5rem;
            border: 1px solid #e5e5e5;
        }
    </style>
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Satu Sehat</a></li>
            <li class="breadcrumb-item active">Geolocation RS</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2><i class="fal fa-map-marked-alt mr-2"></i> Lokasi Rumah Sakit</h2>
                    </div>
                    @if ($location)
                        <div class="panel-content">
                            <div class="row">
                                {{-- Kolom Kiri: Peta Leaflet --}}
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <h5 class="mb-3">Peta Lokasi</h5>
                                    {{-- DIV ini akan menjadi container untuk peta Leaflet --}}
                                    <div id="map" class="shadow-sm"></div>
                                </div>

                                {{-- Kolom Kanan: Detail Lokasi (tidak berubah) --}}
                                <div class="col-md-6">
                                    <h5 class="mb-3">Detail Lokasi</h5>
                                    <div class="card border">
                                        <div class="card-body">
                                            <div class="form-group row"><label
                                                    class="col-sm-4 col-form-label location-detail-label">Nama
                                                    Lokasi</label>
                                                <div class="col-sm-8">
                                                    <p class="form-control-plaintext">: {{ $location->name }}</p>
                                                </div>
                                            </div>
                                            <div class="form-group row"><label
                                                    class="col-sm-4 col-form-label location-detail-label">Provinsi</label>
                                                <div class="col-sm-8">
                                                    <p class="form-control-plaintext">: {{ $location->province }}</p>
                                                </div>
                                            </div>
                                            <div class="form-group row"><label
                                                    class="col-sm-4 col-form-label location-detail-label">Kota</label>
                                                <div class="col-sm-8">
                                                    <p class="form-control-plaintext">: {{ $location->city }}</p>
                                                </div>
                                            </div>
                                            <div class="form-group row"><label
                                                    class="col-sm-4 col-form-label location-detail-label">Alamat</label>
                                                <div class="col-sm-8">
                                                    <p class="form-control-plaintext">: {{ $location->address }}</p>
                                                </div>
                                            </div>
                                            <div class="form-group row"><label
                                                    class="col-sm-4 col-form-label location-detail-label">Longitude</label>
                                                <div class="col-sm-8">
                                                    <p class="form-control-plaintext">: {{ $location->longitude }}</p>
                                                </div>
                                            </div>
                                            <div class="form-group row mb-0"><label
                                                    class="col-sm-4 col-form-label location-detail-label">Latitude</label>
                                                <div class="col-sm-8">
                                                    <p class="form-control-plaintext">: {{ $location->latitude }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer text-right">
                            <button type="button" class="btn btn-primary" onclick="mappingLokasiRS()">
                                <i class="fas fa-satellite-dish mr-2"></i> Submit Mapping Lokasi
                            </button>
                        </div>
                    @else
                        <div class="panel-content">
                            <div class="alert alert-danger text-center">
                                Data lokasi belum diatur. Silahkan jalankan seeder `GeoLocationSeeder`.
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    {{-- Memasukkan JavaScript Leaflet --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    {{-- Script untuk inisialisasi peta --}}
    @if ($location)
        <script>
            // Pastikan skrip berjalan setelah halaman dimuat
            document.addEventListener('DOMContentLoaded', function() {
                // Ambil data dari PHP (Blade)
                const lat = {{ $location->latitude }};
                const lng = {{ $location->longitude }};
                const locationName = "{{ $location->name }}";
                const locationAddress = "{{ $location->address }}";

                // Inisialisasi peta dan atur view ke koordinat yang ditentukan dengan zoom level 15
                const map = L.map('map').setView([lat, lng], 15);

                // Tambahkan tile layer dari OpenStreetMap
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map);

                // Tambahkan penanda (marker) di lokasi yang ditentukan
                const marker = L.marker([lat, lng]).addTo(map);

                // Tambahkan popup pada marker
                marker.bindPopup(`<b>${locationName}</b><br>${locationAddress}`).openPopup();
            });
        </script>
    @endif

    {{-- Script AJAX untuk mapping (tidak berubah) --}}
    <script>
        function mappingLokasiRS() {
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Anda akan melakukan mapping lokasi RS ke Satu Sehat.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Lanjutkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('satu-sehat.mapping-lokasi') }}", // URL dari route name
                        dataType: "json",
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Memproses...',
                                text: 'Harap tunggu sebentar.',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading()
                                }
                            });
                        },
                        success: function(data) {
                            showSuccessAlert(data.text);
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            // Cek jika ada response JSON dari server, jika tidak tampilkan error umum
                            var errorMsg = jqXHR.responseJSON && jqXHR.responseJSON.text ? jqXHR
                                .responseJSON.text : 'Terjadi kesalahan: ' + errorThrown;
                            showErrorAlert(errorMsg);
                        }
                    });
                }
            });
        }
    </script>
@endsection
