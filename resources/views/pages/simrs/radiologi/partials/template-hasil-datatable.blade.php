<style>
    .display-none {
        display: none;
    }

    .popover {
        max-width: 100%;
        max-height:
    }
</style>


<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    Daftar <span class="fw-300"><i>Template Hasil Radiologi</i></span>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <!-- datatable start -->
                    <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                        <thead class="bg-primary-600">
                            <tr>
                                <th style="width: 200px">Judul</th>
                                <th>Template</th>
                                <th style="width: 100px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($templates as $template)
                                <tr>
                                    <td>
                                        <p>
                                            <strong> {{ $template->judul }} </strong>
                                        </p>
                                    <td>
                                        {!! $template->template !!}
                                    </td>
                                    <td>
                                        -
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- datatable end -->
                </div>
            </div>
        </div>
    </div>
</div>
