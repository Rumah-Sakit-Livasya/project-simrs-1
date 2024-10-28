@extends('inc.layout')
@section('title', 'Organisasi')
@section('content')
    <main id="js-page-content" role="main" class="page-content">

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Tabel Survei
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <h2>Kondisi Kamar</h2>
                            <hr>
                            <div class="form-group">
                                <label class="form-label" for="lantai_kamar">Lantai</label>
                                <textarea class="form-control" id="lantai_kamar" name="lantai_kamar" rows="5"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="sudut_kamar">Sudut</label>
                                <textarea class="form-control" id="sudut_kamar" name="sudut_kamar" rows="5"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="plafon_kamar">Plafon</label>
                                <textarea class="form-control" id="plafon_kamar" name="plafon_kamar" rows="5"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="dinding_kamar">Dinding</label>
                                <textarea class="form-control" id="dinding_kamar" name="dinding_kamar" rows="5"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="bed_head">Bed Head</label>
                                <textarea class="form-control" id="bed_head" name="bed_head" rows="5"></textarea>
                            </div>
                            <h2>Kondisi Toilet</h2>
                            <hr>
                            <div class="form-group">
                                <label class="form-label" for="lantai_toilet">Lantai</label>
                                <textarea class="form-control" id="lantai_toilet" name="lantai_toilet" rows="5"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="wastafel_toilet">Wastafel</label>
                                <textarea class="form-control" id="wastafel_toilet" name="wastafel_toilet" rows="5"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="closet_toilet">Kloset</label>
                                <textarea class="form-control" id="closet_toilet" name="closet_toilet" rows="5"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="kaca_toilet">Kaca</label>
                                <textarea class="form-control" id="kaca_toilet" name="kaca_toilet" rows="5"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="dinding_toilet">Dinding</label>
                                <textarea class="form-control" id="dinding_toilet" name="dinding_toilet" rows="5"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="shower_toilet">Shower</label>
                                <textarea class="form-control" id="shower_toilet" name="shower_toilet" rows="5"></textarea>
                            </div>
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
    <script>
        /* demo scripts for change table color */
        /* change background */
        $(document).ready(function() {
            let dataId = null;

            $('.btn-edit').click(function(e) {
                e.preventDefault();
                let button = $(this);
                let id = button.attr('data-id');
                dataId = id;
                button.find('.ikon-edit').hide();
                button.find('.spinner-text').removeClass('d-none');

                $.ajax({
                    type: "GET", // Method pengiriman data bisa dengan GET atau POST
                    url: `/api/dashboard/banks/get/${id}`, // Isi dengan url/path file php yang dituju
                    dataType: "json",
                    success: function(data) {
                        button.find('.ikon-edit').show();
                        button.find('.spinner-text').addClass('d-none');
                        $('#ubah-data').modal('show');
                        $('#ubah-data #name').val(data.name)
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });

            });

            $('#update-form').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                $.ajax({
                    type: "POST",
                    url: '/api/dashboard/banks/update/' + dataId,
                    data: formData,
                    beforeSend: function() {
                        $('#update-form').find('.ikon-edit').hide();
                        $('#update-form').find('.spinner-text')
                            .removeClass(
                                'd-none');
                    },
                    success: function(response) {
                        $('#ubah-data').modal('hide');
                        showSuccessAlert(response.message)
                        setTimeout(function() {
                            location.reload();
                        }, 500);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });

            $('#store-form').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                $.ajax({
                    type: "POST",
                    url: '/api/dashboard/banks/store/',
                    data: formData,
                    beforeSend: function() {
                        $('#store-form').find('.ikon-tambah').hide();
                        $('#store-form').find('.spinner-text').removeClass(
                            'd-none');
                    },
                    success: function(response) {
                        $('#store-form').find('.ikon-edit').show();
                        $('#store-form').find('.spinner-text').addClass('d-none');
                        $('#tambah-data').modal('hide');
                        showSuccessAlert(response.message)
                        setTimeout(function() {
                            location.reload();
                        }, 500);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });

            $('.btn-hapus').click(function(e) {
                e.preventDefault();
                let button = $(this);
                alert('Yakin ingin menghapus ini ?');
                let id = button.attr('data-id');
                $.ajax({
                    type: "GET",
                    url: '/api/dashboard/banks/delete/' + id,
                    beforeSend: function() {
                        button.find('.ikon-hapus').hide();
                        button.find('.spinner-text').removeClass(
                            'd-none');
                    },
                    success: function(response) {
                        button.find('.ikon-edit').show();
                        button.find('.spinner-text').addClass('d-none');
                        showSuccessAlert(response.message)
                        setTimeout(function() {
                            location.reload();
                        }, 500);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });

            $('#dt-basic-example').dataTable({
                responsive: true
            });

            $('.js-thead-colors a').on('click', function() {
                var theadColor = $(this).attr("data-bg");
                console.log(theadColor);
                $('#dt-basic-example thead').removeClassPrefix('bg-').addClass(theadColor);
            });

            $('.js-tbody-colors a').on('click', function() {
                var theadColor = $(this).attr("data-bg");
                console.log(theadColor);
                $('#dt-basic-example').removeClassPrefix('bg-').addClass(theadColor);
            });

        });
    </script>
@endsection
