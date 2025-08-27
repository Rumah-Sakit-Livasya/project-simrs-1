<style>
    .auto-grid{
        text-align: center;
    }

    /* Highlight the label when the associated input is checked */
    input.btn-check:checked+label.toggle-fix {
        background-color: #0d6efd;
        /* Bootstrap 5 primary */
        color: white;
        border-color: #0d6efd;
    }

    .toggle-fix {
        padding: 8px 8px !important;
    }

    #modal-jam-pemberian #title{
        width: 100%;
    }
</style>
<div class="modal fade" id="modal-jam-pemberian" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-jam-pemberian-content" role="document">
        <div class="modal-content">
            <div class="modal-header pb-1 mb-0">
                <h5 id="title" class="modal-title font-weight-bold text-center">Jam Pemberian「<span id="nama-obat"></span>」(Format 24H)</h5>
            </div>
            <div class="modal-body py-2 row">
                <div class="col-md-12">
                    <hr style="border-color: #dedede;" class="mb-1 mt-1">
                </div>
                <div class="col-md-12 mb-3 auto-grid">

                    @for ($i = 0; $i <= 23; $i++)
                        <div class="form-check form-check-inline">
                            <input type="checkbox" hidden class="btn-check jam-pemberian-checks"
                                id="jam{{ $i }}" value="{{ $i }}" autocomplete="off">
                            <label class="btn btn-outline-primary toggle-fix"
                                for="jam{{ $i }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</label>
                        </div>
                    @endfor

                </div>
            </div>
        </div>
    </div>
</div>
