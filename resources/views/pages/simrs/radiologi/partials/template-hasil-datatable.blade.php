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
                                @if (!isset($orderParameterId))
                                    <th style="width: 100px">Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($templates as $template)
                                @if (isset($orderParameterId))
                                    <tr style="cursor: pointer"
                                        onclick="TemplateHasilRadiologiClass.useTemplate({{ $orderParameterId }}, `{!! $template->template !!}`)">
                                    @else
                                    <tr>
                                @endif
                                <td>
                                    <p>
                                        <strong> {{ $template->judul }} </strong>
                                    </p>
                                <td>
                                    {!! $template->template !!}
                                </td>
                                @if (!isset($orderParameterId))
                                    <td>
                                        <a class="mdi mdi-pencil pointer mdi-24px text-secondary edit-btn"
                                            title="Edit Template" data-toggle="modal"
                                            data-target="#editTemplateModal{{ $template->id }}"
                                            data-id="{{ $template->id }}"></a>
                                        <div class="modal fade" id="editTemplateModal{{ $template->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="editTemplateModal{{ $template->id }}" aria-hidden="true">
                                            @include('pages.simrs.radiologi.partials.template-hasil-tambah',[
                                                'template' => $template
                                            ])
                                        </div>

                                        <a class="mdi mdi-close pointer mdi-24px text-danger delete-btn"
                                            title="Hapus Template" data-id="{{ $template->id }}"></a>
                                    </td>
                                @endif
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


@if (isset($orderParameterId))
    {{-- Opened from template-hasil-form.blade.php --}}
    </div>
    </div>
    </div>
    <script src="{{ asset('js/simrs/template-hasil-radiologi.js') }}?v={{ time() }}"></script>
@endif
